// The site is server-rendered; this file only wires small interactions:
// the mobile menu, the light/dark switch, the search box and opening a linked FAQ answer.

const html = document.documentElement;

// --- Mobile menu ---------------------------------------------------------------------------
const navToggle = document.getElementById('nav-toggle');
const navMenu = document.getElementById('nav-menu');

if (navToggle && navMenu) {
    navToggle.addEventListener('click', () => {
        const open = navMenu.classList.toggle('hidden') === false;
        navToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    });
}

// --- Light / dark --------------------------------------------------------------------------
// The class is already set in <head> before first paint; this keeps the buttons in step,
// remembers the visitor's choice, and follows the system until they have made one.
const themeButtons = document.querySelectorAll('[data-theme-toggle]');

function applyTheme(dark, remember) {
    html.classList.toggle('dark', dark);
    themeButtons.forEach((button) => button.setAttribute('aria-pressed', String(dark)));

    if (remember) {
        try {
            localStorage.setItem('theme', dark ? 'dark' : 'light');
        } catch (e) { /* storage blocked: the choice lasts until the page is closed */ }
    }
}

themeButtons.forEach((button) => {
    button.setAttribute('aria-pressed', String(html.classList.contains('dark')));
    button.addEventListener('click', () => applyTheme(!html.classList.contains('dark'), true));
});

window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (event) => {
    try {
        if (!localStorage.getItem('theme')) applyTheme(event.matches, false);
    } catch (e) { /* ignore */ }
});

// --- Search --------------------------------------------------------------------------------
const dialog = document.getElementById('search-dialog');

if (dialog && typeof dialog.showModal === 'function') {
    const input = document.getElementById('search-input');
    const quick = document.getElementById('search-quick');
    const live = document.getElementById('search-live');
    const minLength = 2;
    let timer = null;
    let controller = null;
    let active = -1;

    const options = () => Array.from(dialog.querySelectorAll('[data-result]')).filter((el) => el.offsetParent !== null);

    function setActive(index) {
        const list = options();
        if (!list.length) {
            active = -1;
            input.removeAttribute('aria-activedescendant');
            return;
        }
        active = (index + list.length) % list.length;
        list.forEach((el, i) => {
            const on = i === active;
            el.classList.toggle('bg-brand-50', on);
            el.setAttribute('aria-selected', String(on));
            if (on) {
                el.scrollIntoView({ block: 'nearest' });
                input.setAttribute('aria-activedescendant', el.id);
            }
        });
    }

    function el(tag, className, text) {
        const node = document.createElement(tag);
        if (className) node.className = className;
        if (text) node.textContent = text;
        return node;
    }

    // Results are built with textContent, never innerHTML: they come from the database.
    function render(groups, query) {
        live.replaceChildren();
        quick.hidden = true;
        live.hidden = false;
        let n = 0;

        if (!groups.length) {
            live.append(el('p', 'px-3 py-8 text-center text-sm text-slate-500', dialog.dataset.none.replace(':q', query)));
            input.setAttribute('aria-expanded', 'false');
            setActive(-1);
            return;
        }

        groups.forEach((group) => {
            live.append(el('p', 'px-3 pb-1 pt-3 text-xs font-bold uppercase tracking-wide text-slate-400', group.label));
            group.items.forEach((item) => {
                const link = el('a', 'block rounded-xl px-3 py-2.5 hover:bg-brand-50');
                link.href = item.url;
                link.id = 'search-result-' + n++;
                link.setAttribute('role', 'option');
                link.setAttribute('data-result', '');
                if (item.meta) link.append(el('span', 'block text-xs font-bold text-brand-600', item.meta));
                link.append(el('span', 'block text-sm font-bold text-slate-900', item.title));
                if (item.snippet) link.append(el('span', 'mt-0.5 block text-xs text-slate-500', item.snippet));
                link.addEventListener('mousemove', () => setActive(options().indexOf(link)));
                live.append(link);
            });
        });

        const all = el('a', 'mt-1 block rounded-xl px-3 py-2.5 text-center text-sm font-bold text-brand-600 hover:bg-brand-50', dialog.dataset.all);
        all.href = dialog.dataset.endpoint + '?q=' + encodeURIComponent(query);
        all.id = 'search-result-' + n;
        all.setAttribute('role', 'option');
        all.setAttribute('data-result', '');
        live.append(all);

        input.setAttribute('aria-expanded', 'true');
        setActive(0);
    }

    async function run() {
        const query = input.value.trim();

        if (controller) controller.abort();

        if (query.length < minLength) {
            live.hidden = true;
            quick.hidden = false;
            input.setAttribute('aria-expanded', 'false');
            setActive(-1);
            return;
        }

        controller = new AbortController();

        try {
            const response = await fetch(dialog.dataset.endpoint + '?q=' + encodeURIComponent(query), {
                headers: { Accept: 'application/json' },
                signal: controller.signal,
            });
            if (!response.ok) throw new Error(String(response.status));
            render((await response.json()).groups, query);
        } catch (error) {
            if (error.name === 'AbortError') return;
            live.replaceChildren(el('p', 'px-3 py-8 text-center text-sm text-slate-500', dialog.dataset.error));
            live.hidden = false;
            quick.hidden = true;
        }
    }

    function open() {
        if (dialog.open) return;
        dialog.showModal();
        input.focus();
        input.select();
        run();
    }

    document.querySelectorAll('[data-search-open]').forEach((button) => {
        // The link still works without JavaScript; with it, the box opens in place.
        button.addEventListener('click', (event) => {
            event.preventDefault();
            open();
        });
    });

    dialog.querySelector('[data-search-close]').addEventListener('click', () => dialog.close());
    dialog.addEventListener('click', (event) => {
        if (event.target === dialog) dialog.close(); // a click on the dimmed backdrop
    });

    input.addEventListener('input', () => {
        clearTimeout(timer);
        timer = setTimeout(run, 180);
    });

    input.addEventListener('keydown', (event) => {
        if (event.key === 'ArrowDown') {
            event.preventDefault();
            setActive(active + 1);
        } else if (event.key === 'ArrowUp') {
            event.preventDefault();
            setActive(active - 1);
        } else if (event.key === 'Enter') {
            const current = options()[active];
            // With a result highlighted, go there; otherwise the form shows the full results page.
            if (current) {
                event.preventDefault();
                window.location.href = current.href;
            }
        }
    });

    document.addEventListener('keydown', (event) => {
        const typing = /^(INPUT|TEXTAREA|SELECT)$/.test(document.activeElement?.tagName) || document.activeElement?.isContentEditable;

        if ((event.ctrlKey || event.metaKey) && event.key.toLowerCase() === 'k') {
            event.preventDefault();
            dialog.open ? dialog.close() : open();
        } else if (event.key === '/' && !typing && !dialog.open) {
            event.preventDefault();
            open();
        }
    });

    dialog.addEventListener('close', () => {
        input.value = '';
        live.hidden = true;
        quick.hidden = false;
        setActive(-1);
    });
}

// A link such as /faq#faq-3 opens that answer.
if (location.hash.startsWith('#faq-')) {
    const target = document.querySelector(location.hash);
    if (target && target.tagName === 'DETAILS') {
        target.open = true;
        target.scrollIntoView({ block: 'center' });
    }
}
