// Exports the ERP's user manual (resources/js/Pages/UserManual/content) to JSON
// so the help center can be built from it without the ERP's source at runtime.
//
//   node scripts/export-manual.mjs [path-to-erp]     (default: ../nextbook)
//
// The guide files are plain `export default { ... }` object literals, so they are
// evaluated here instead of imported: the ERP's own index files use extensionless
// imports that Node's ESM loader rejects.
import fs from 'node:fs';
import path from 'node:path';
import vm from 'node:vm';
import { fileURLToPath } from 'node:url';

const here = path.dirname(fileURLToPath(import.meta.url));
const erp = path.resolve(process.argv[2] ?? path.join(here, '..', '..', 'nextbook'));
const content = path.join(erp, 'resources/js/Pages/UserManual/content');
const out = path.join(here, '..', 'database/data/manual.json');

const load = (file) => {
    const source = fs.readFileSync(file, 'utf8').replace(/^\s*export default/m, 'module.exports =');
    const sandbox = { module: { exports: {} } };
    vm.runInNewContext(source, sandbox, { filename: file });
    return sandbox.module.exports;
};

// Lucide names the ERP uses that differ from the file names of the icon set.
const iconAlias = { BarChart3: 'chart-column', Palmtree: 'tree-palm' };
const kebab = (name) => iconAlias[name] ?? name.replace(/([a-z0-9])([A-Z])/g, '$1-$2').replace(/([A-Za-z])(\d)/g, '$1-$2').toLowerCase();

// guides.js lists the order, the icon and the accent of every guide.
const meta = [...fs.readFileSync(path.join(content, 'guides.js'), 'utf8').matchAll(/\{\s*id:\s*'([^']+)',\s*icon:\s*'([^']+)'/g)]
    .map(([, id, icon]) => ({ id, icon: kebab(icon) }));

const locales = ['fa', 'en', 'ps'];
const guides = meta.map(({ id, icon }, index) => {
    const byLocale = {};
    for (const locale of locales) {
        const file = path.join(content, locale, `${id}.js`);
        if (fs.existsSync(file)) {
            const { title, subtitle, summary, chapters } = load(file);
            byLocale[locale] = { title, subtitle, summary, chapters };
        }
    }

    return { id, icon, sort: index + 1, locales: byLocale };
});

fs.mkdirSync(path.dirname(out), { recursive: true });
fs.writeFileSync(out, JSON.stringify({ guides }, null, 1));

const chapters = guides.reduce((n, g) => n + (g.locales.fa?.chapters.length ?? 0), 0);
console.log(`${guides.length} guides, ${chapters} chapters (fa) -> ${path.relative(process.cwd(), out)}`);
for (const g of guides) {
    const missing = locales.filter((l) => !g.locales[l]);
    if (missing.length) console.log(`  ${g.id}: no ${missing.join(', ')}`);
}
