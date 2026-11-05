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

// --- Pricing: monthly / yearly ---------------------------------------------------------------
const pricing = document.getElementById('pricing');

if (pricing) {
    const buttons = pricing.querySelectorAll('[data-billing-set]');

    buttons.forEach((button) => {
        button.addEventListener('click', () => {
            pricing.dataset.billing = button.dataset.billingSet;
            buttons.forEach((other) => other.setAttribute('aria-pressed', String(other === button)));
        });
    });
}

// --- Scroll reveal -----------------------------------------------------------------------------
const revealed = document.querySelectorAll('.reveal');

if ('IntersectionObserver' in window) {
    const watcher = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (!entry.isIntersecting) return;
            entry.target.classList.add('is-in');
            watcher.unobserve(entry.target);
        });
    }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });
    revealed.forEach((node) => watcher.observe(node));
} else {
    revealed.forEach((node) => node.classList.add('is-in'));
}

// --- Home: a day, before and after -------------------------------------------------------------
document.querySelectorAll('[data-day]').forEach((day) => {
    const buttons = day.querySelectorAll('[data-day-set]');
    buttons.forEach((button) => {
        button.addEventListener('click', () => {
            day.dataset.mode = button.dataset.daySet;
            buttons.forEach((other) => other.setAttribute('aria-pressed', String(other === button)));
        });
    });
});

// --- Home: the questions asked every night -------------------------------------------------------
const ask = document.getElementById('ask');

if (ask) {
    const tabs = ask.querySelectorAll('[data-ask]');
    const panels = ask.querySelectorAll('[data-ask-panel]');
    tabs.forEach((tab) => {
        tab.addEventListener('click', () => {
            tabs.forEach((other) => other.setAttribute('aria-selected', String(other === tab)));
            panels.forEach((panel) => { panel.hidden = panel.dataset.askPanel !== tab.dataset.ask; });
        });
    });
}

// --- Pictures that open in place and can be zoomed ----------------------------------------------------
// Any button with data-zoom that holds an <img> opens it large in a dialog. A tap or click zooms in
// (and back out); the +/- buttons step through the sizes, and a zoomed picture can be dragged or scrolled.
(() => {
    const triggers = document.querySelectorAll('[data-zoom]');
    if (!triggers.length) return;

    const STEPS = [1, 1.75, 2.5, 4];
    let dialog;
    let stage;
    let image;
    let level = 0;

    const build = (closeLabel) => {
        dialog = document.createElement('dialog');
        dialog.className = 'lightbox';
        dialog.innerHTML = '<div class="lightbox-bar" dir="ltr">'
            + '<button type="button" data-lb="out" aria-label="-">−</button>'
            + '<button type="button" data-lb="in" aria-label="+">+</button>'
            + '<button type="button" data-lb="close"></button></div>'
            + '<div class="lightbox-stage"><img alt=""></div>';
        dialog.querySelector('[data-lb="close"]').textContent = '×';
        dialog.querySelector('[data-lb="close"]').setAttribute('aria-label', closeLabel || 'Close');
        document.body.appendChild(dialog);
        stage = dialog.querySelector('.lightbox-stage');
        image = stage.querySelector('img');

        dialog.querySelector('[data-lb="close"]').addEventListener('click', () => dialog.close());
        dialog.querySelector('[data-lb="in"]').addEventListener('click', () => zoom(level + 1));
        dialog.querySelector('[data-lb="out"]').addEventListener('click', () => zoom(level - 1));
        // A click on the dark area closes it; a click on the picture zooms it.
        dialog.addEventListener('click', (event) => { if (event.target === dialog || event.target === stage) dialog.close(); });
        image.addEventListener('click', () => zoom(level === 0 ? 1 : 0));
        dialog.addEventListener('close', () => { document.documentElement.classList.remove('lightbox-open'); });
    };

    const zoom = (next) => {
        level = Math.max(0, Math.min(STEPS.length - 1, next));
        image.style.width = (STEPS[level] * 100) + '%';
        image.style.maxWidth = level === 0 ? '100%' : 'none';
        image.style.maxHeight = level === 0 ? '100%' : 'none';
        stage.classList.toggle('is-zoomed', level > 0);
        image.style.cursor = level === 0 ? 'zoom-in' : 'zoom-out';
    };

    triggers.forEach((trigger) => {
        trigger.addEventListener('click', () => {
            const source = trigger.querySelector('img');
            if (!source) return;
            if (!dialog) build(trigger.dataset.close);
            image.src = source.currentSrc || source.src;
            image.alt = source.alt;
            zoom(0);
            stage.scrollTo(0, 0);
            document.documentElement.classList.add('lightbox-open');
            dialog.showModal();
        });
    });
})();

// --- Home: the screens of the product ------------------------------------------------------------------
document.querySelectorAll('[data-shots]').forEach((box) => {
    const tabs = [...box.querySelectorAll('[data-shot-tab]')];
    const panels = [...box.querySelectorAll('[data-shot-panel]')];
    let current = 0;

    const show = (index) => {
        current = (index + tabs.length) % tabs.length;
        tabs.forEach((tab, i) => tab.setAttribute('aria-selected', String(i === current)));
        panels.forEach((panel, i) => { panel.hidden = i !== current; });
    };

    tabs.forEach((tab, i) => tab.addEventListener('click', () => show(i)));
    box.querySelectorAll('[data-shot-step]').forEach((button) => {
        button.addEventListener('click', () => show(current + Number(button.dataset.shotStep)));
    });
});

// --- Home: record a sale and watch the book write itself ---------------------------------------------
const sale = document.getElementById('sale-demo');

if (sale) {
    const d = sale.dataset;
    const fmt = new Intl.NumberFormat(d.locale);
    // A right-to-left mark keeps the plus sign on the starting side of the number in Dari.
    const RLM = d.locale === 'fa' ? '‏' : '';
    const price = Number(d.price);
    const cost = Number(d.cost);
    const stock = Number(d.stock);
    const out = (key) => sale.querySelector('[data-out="' + key + '"]');
    const qtyOut = sale.querySelector('[data-qty-out]');
    const totalOut = sale.querySelector('[data-total]');
    const stamp = sale.querySelector('[data-stamp]');
    const submit = sale.querySelector('[data-submit]');
    let qty = 20;
    let pay = 'cash';
    let done = false;

    const node = (tag, className, text) => {
        const el = document.createElement(tag);
        if (className) el.className = className;
        if (text !== undefined) el.textContent = text;
        return el;
    };

    function reset() {
        done = false;
        stamp.classList.add('hidden');
        submit.textContent = d.submitLabel;
        ['stock', 'debt', 'ledger', 'profit'].forEach((key) => {
            out(key).className = 'min-h-14 text-slate-400';
            out(key).textContent = d.waiting;
        });
    }

    function refresh() {
        qtyOut.textContent = fmt.format(qty);
        totalOut.textContent = fmt.format(qty * price);
        if (done) reset();
    }

    function line(label, value, delay) {
        const row = node('div', 'pop flex items-center justify-between gap-4 border-b border-dashed border-slate-200 py-1.5 text-sm last:border-0');
        row.style.setProperty('--d', delay + 's');
        row.append(node('span', 'text-slate-600', label), node('span', 'font-bold text-slate-900', value));
        return row;
    }

    function record() {
        const total = qty * price;
        const paid = pay === 'cash' ? total : Math.round(total / 2 / 50) * 50;
        const debt = total - paid;
        const money = (n) => fmt.format(n) + ' ' + d.money;
        done = true;

        const stockBox = out('stock');
        stockBox.className = 'pop text-slate-900';
        stockBox.replaceChildren(
            node('p', 'text-3xl font-bold', fmt.format(stock) + ' ← ' + fmt.format(stock - qty)),
            node('p', 'mt-1 text-sm text-slate-500', d.stockUnit),
        );

        const debtBox = out('debt');
        debtBox.className = 'pop text-slate-900';
        debtBox.style.setProperty('--d', '.1s');
        debtBox.replaceChildren(
            node('p', 'text-xl font-bold', debt ? money(debt) : '0'),
            node('p', 'mt-1 text-sm text-slate-500', debt ? d.debtHas : d.debtZero),
        );

        const ledger = out('ledger');
        ledger.className = 'text-slate-900';
        const rows = [[d.cash, RLM + '+' + money(paid)]];
        if (debt) rows.push([d.receivable, RLM + '+' + money(debt)]);
        rows.push([d.sales, money(total)]);
        ledger.replaceChildren(...rows.map(([label, value], i) => line(label, value, 0.2 + i * 0.12)));

        const profit = out('profit');
        profit.className = 'pop text-slate-900';
        profit.style.setProperty('--d', '.3s');
        profit.replaceChildren(node('p', 'text-2xl font-bold text-brand-600', RLM + '+' + money(qty * (price - cost))));

        stamp.classList.remove('hidden');
        stamp.style.animation = 'none';
        void stamp.offsetWidth;
        stamp.style.animation = '';
        submit.textContent = d.again;
    }

    sale.querySelectorAll('[data-qty]').forEach((button) => {
        button.addEventListener('click', () => {
            qty = Math.min(40, Math.max(5, qty + Number(button.dataset.qty)));
            refresh();
        });
    });

    const payButtons = sale.querySelectorAll('[data-pay]');
    payButtons.forEach((button) => {
        button.addEventListener('click', () => {
            pay = button.dataset.pay;
            payButtons.forEach((other) => other.setAttribute('aria-pressed', String(other === button)));
            if (done) reset();
        });
    });

    submit.addEventListener('click', () => (done ? reset() : record()));
}

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
