# nextbook.af

The public website of NextBook: product pages, industries, pricing, FAQ, a help
center and demo/contact forms. It is a separate project from the ERP
(`nextbook`) on purpose, so site traffic and site changes never touch the
accounting system.

- **Laravel 13**, PostgreSQL, server-rendered Blade, Tailwind 4, Vite
- **Fonts:** Poppins for Latin text, IRANSans for Dari and Pashto, bundled from `resources/fonts` with no external font service. The IRANSans files we have stop at Medium (no Bold), so Medium also serves the bold weights; add an IRANSans Bold file to `resources/fonts` and a matching `@font-face` in `resources/css/app.css` if you get one
- **Filament** admin panel at `/admin` for the team (content, leads)
- Languages: Dari (`/fa`, default, RTL) and English (`/en`). Pashto content can
  already be entered in the admin; `ps` joins `config/site.php` `locales` once the
  interface texts in `lang/ps` exist.

## Run it

```bash
composer install
cp .env.example .env        # then set DB_PASSWORD
php artisan key:generate
php artisan migrate --seed  # creates the tables and the public content
php artisan help:import     # builds the help center from the ERP user manual
php artisan make:filament-user
npm install
composer dev                # server + Vite  ->  http://127.0.0.1:8000
```

The database named in `.env` (`nextbook_site`) must exist. Admin: `/admin`.

## Content

Everything on the public pages comes from the database and is edited in `/admin`:
features, industries, pricing plans, FAQ, help categories and articles. Demo
requests and contact messages arrive under **Leads**.

- **Prices** are never invented: with no plan published, the pricing page says to
  contact sales. Add plans in the admin when the prices are decided.
- **"Coming soon"** features (POS, CRM ...) are marked as such and kept off the home page.
- Contact details, the customers' subdomain (`acme.nextbook.af`) and the WhatsApp number are in `config/site.php`
  (`SITE_TENANT_DOMAIN`, `SITE_TENANT_SCHEME` and `SITE_WHATSAPP` in `.env`).

### Blog

Articles are written in `/admin` under **Content → Posts**, in Dari, English and Pashto, with a rich-text editor. A post appears when it is switched on and its date has arrived, so a future date schedules it. Dates show in the Jalali calendar on the Dari pages. Three starter articles are seeded.

### Help center

`database/data/manual.json` is the ERP user manual (15 guides, Dari / English / Pashto),
exported once from the ERP's `resources/js/Pages/UserManual/content`:

```bash
node scripts/export-manual.mjs ../nextbook   # regenerate the JSON from the ERP
php artisan help:import                      # load it (overwrites imported articles)
```

Screenshots live in `public/images/user-manual/`. A figure whose image file is missing
is left out of the article instead of showing a broken image.

## Tests

```bash
composer test
```

Tests run against a separate PostgreSQL database, `nextbook_site_test` (see
`phpunit.xml`), so the site's own data is never touched.
