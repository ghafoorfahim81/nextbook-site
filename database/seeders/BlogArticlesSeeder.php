<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;

/**
 * Twenty articles in Dari, English and Pashto: how the ERP is used, and the accounting and
 * money ideas a trader meets while using it. Safe to run again: posts are matched by slug.
 * Numbers in the examples are made up, and each article says so where it matters.
 *
 * Each body is a list of blocks, [type, value]: p, h2, ul, ol, tip, warn, formula.
 */
class BlogArticlesSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->articles() as $article) {
            $post = [];

            foreach (['fa', 'en', 'ps'] as $locale) {
                $post['title'][$locale] = $article[$locale]['title'];
                $post['excerpt'][$locale] = $article[$locale]['excerpt'];
                $post['content'][$locale] = $this->html($article[$locale]['body']);
            }

            Post::updateOrCreate(['slug' => $article['slug']], $post + [
                'icon' => $article['icon'],
                'is_published' => true,
                'published_at' => now()->subDays($article['days_ago'])->startOfDay()->addHours(9),
            ]);
        }
    }

    /** Turns a list of blocks into the HTML the blog renders (the same classes the help center uses). */
    private function html(array $blocks): string
    {
        $out = [];

        foreach ($blocks as [$type, $value]) {
            $out[] = match ($type) {
                'p' => "<p>{$value}</p>",
                'h2' => "<h2>{$value}</h2>",
                'ul' => '<ul>'.collect($value)->map(fn ($item) => "<li>{$item}</li>")->implode('').'</ul>',
                'ol' => '<ol>'.collect($value)->map(fn ($item) => "<li>{$item}</li>")->implode('').'</ol>',
                'tip' => "<div class=\"callout callout-tip\">{$value}</div>",
                'warn' => "<div class=\"callout callout-warn\">{$value}</div>",
                'formula' => "<div class=\"formula\">{$value}</div>",
            };
        }

        return implode("\n\n", $out);
    }

    private function articles(): array
    {
        return [
            // ARTICLES-START
            [
                'slug' => 'double-entry-bookkeeping-simply',
                'icon' => 'scale',
                'days_ago' => 3,
                'fa' => [
                    'title' => 'حسابداری دوطرفه به زبان ساده: چرا هر معامله در دو جا ثبت می‌شود',
                    'excerpt' => 'هر پولی که می‌آید از جایی می‌آید و به جایی می‌رود. حسابداری دوطرفه این دو طرف را کنار هم می‌نویسد تا دفتر همیشه جور باشد.',
                    'body' => [
                        ['p', 'وقتی جنسی را نقد می‌فروشید، دو چیز تغییر می‌کند: پول نقد شما زیاد می‌شود و یک فروش ثبت می‌شود. اگر فقط یکی را بنویسید، دفتر از تعادل می‌افتد. حسابداری دوطرفه یعنی هر معامله همیشه از دو طرف نوشته شود.'],
                        ['h2', 'سه مثال کوتاه'],
                        ['ul', [
                            'فروش نقد ۱۰٬۰۰۰ افغانی: نقد ۱۰٬۰۰۰ زیاد می‌شود (بدهکار) و درآمد فروش ۱۰٬۰۰۰ ثبت می‌شود (بستانکار).',
                            'همین فروش به‌صورت قرضی: به‌جای نقد، طلب از مشتری ۱۰٬۰۰۰ زیاد می‌شود (بدهکار) و درآمد ثبت می‌شود (بستانکار).',
                            'پرداخت کرایه ۲٬۰۰۰ از صندوق: مصرف کرایه ۲٬۰۰۰ (بدهکار) و نقد ۲٬۰۰۰ کم می‌شود (بستانکار).',
                        ]],
                        ['h2', 'فایده‌اش چیست؟'],
                        ['p', 'مجموع بدهکارها همیشه با مجموع بستانکارها برابر است. اگر نبود، یک جایی اشتباه شده و می‌دانید باید دنبالش بگردید. ترازنامه و گزارش سود و زیان هم از همین دفتر ساخته می‌شوند. (اعداد این مثال‌ها فرضی‌اند.)'],
                        ['tip', 'در نکست‌بوک هر فروش، خرید، رسید و پرداخت خودکار در دفتر حساب به‌صورت بدهکار و بستانکار ثبت می‌شود. شما معامله را یک بار می‌نویسید و دفتر خودش جور می‌شود.'],
                    ],
                ],
                'en' => [
                    'title' => 'Double-entry bookkeeping in plain words: why every transaction is written twice',
                    'excerpt' => 'Every bit of money comes from somewhere and goes somewhere. Double entry writes both sides together so the books always agree.',
                    'body' => [
                        ['p', 'When you sell goods for cash, two things change: your cash goes up and a sale is recorded. If you write only one of them, the books fall out of balance. Double entry means every transaction is always written from both sides.'],
                        ['h2', 'Three short examples'],
                        ['ul', [
                            'A cash sale of 10,000 AFN: cash goes up by 10,000 (debit) and sales income of 10,000 is recorded (credit).',
                            'The same sale on credit: instead of cash, what the customer owes you goes up by 10,000 (debit) and income is recorded (credit).',
                            'Paying 2,000 rent from the till: rent expense of 2,000 (debit) and cash goes down by 2,000 (credit).',
                        ]],
                        ['h2', 'What is it good for?'],
                        ['p', 'Total debits always equal total credits. If they do not, something is wrong and you know to look for it. The balance sheet and the profit and loss report are built from this same ledger. (The numbers in these examples are made up.)'],
                        ['tip', 'In NextBook every sale, purchase, receipt and payment posts debits and credits to the ledger on its own. You write the transaction once and the books balance themselves.'],
                    ],
                ],
                'ps' => [
                    'title' => 'دوه‌اړخیزه محاسبه په ساده ژبه: ولې هر معامله په دوو ځایونو کې لیکل کېږي',
                    'excerpt' => 'هره پیسه له یو ځایه راځي او بل ځای ته ځي. دوه‌اړخیزه محاسبه دا دواړه اړخونه یو ځای لیکي، چې دفتر تل سم وي.',
                    'body' => [
                        ['p', 'کله چې توکي نغدې پلورئ، دوه شیان بدلېږي: ستاسو نغدې پیسې زیاتېږي او یو پلور ثبتېږي. که یوازې یو یې ولیکئ، دفتر له توازنه وځي. دوه‌اړخیزه محاسبه یعنې هره معامله تل له دواړو اړخونو لیکل کېږي.'],
                        ['h2', 'درې لنډې بېلګې'],
                        ['ul', [
                            'د ۱۰٬۰۰۰ افغانیو نغدې پلور: نغدې ۱۰٬۰۰۰ زیاتېږي (بدهکار) او د پلور عاید ۱۰٬۰۰۰ ثبتېږي (بستانکار).',
                            'همدا پلور په قرض: د نغدو پر ځای د پېرېدونکي طلب ۱۰٬۰۰۰ زیاتېږي (بدهکار) او عاید ثبتېږي (بستانکار).',
                            'له صندوقه د ۲٬۰۰۰ کرایې ورکړه: د کرایې لګښت ۲٬۰۰۰ (بدهکار) او نغدې ۲٬۰۰۰ کمېږي (بستانکار).',
                        ]],
                        ['h2', 'ګټه یې څه ده؟'],
                        ['p', 'د بدهکارو مجموعه تل د بستانکارو له مجموعې سره برابره وي. که نه وي، یو ځای تېروتنه شوې او پوهېږئ چې باید یې وپلټئ. ترازنامه او د ګټې او تاوان راپور هم له همدې دفتره جوړېږي. (د دې بېلګو شمېرې فرضي دي.)'],
                        ['tip', 'په نکست‌بوک کې هر پلور، پېر، رسید او ورکړه پخپله د حساب په دفتر کې په بدهکار او بستانکار ثبتېږي. تاسو معامله یو ځل لیکئ او دفتر پخپله سمېږي.'],
                    ],
                ],
            ],
            [
                'slug' => 'five-account-types-explained',
                'icon' => 'layers',
                'days_ago' => 6,
                'fa' => [
                    'title' => 'پنج نوع حساب: دارایی، بدهی، سرمایه، درآمد و مصرف',
                    'excerpt' => 'هر حساب شما در یکی از پنج گروه می‌نشیند. وقتی این پنج گروه را بشناسید، خواندن هر گزارش مالی آسان می‌شود.',
                    'body' => [
                        ['p', 'تمام حساب‌های یک شرکت، چه کوچک چه بزرگ، در پنج گروه جا می‌گیرند. دو گروه از آن‌ها در ترازنامه می‌آیند و دو گروه در سود و زیان، و یکی میان این دو پل است.'],
                        ['h2', 'پنج گروه'],
                        ['ul', [
                            '<strong>دارایی:</strong> هر چیزی که دارید: نقد، بانک، موجودی گدام، طلب از مشتریان.',
                            '<strong>بدهی:</strong> هر چیزی که باید بدهید: بدهی به تامین‌کننده‌ها، معاش پرداخت‌نشده.',
                            '<strong>سرمایه:</strong> سهم صاحبان شرکت: سرمایه اولیه و سودهای باقی‌مانده.',
                            '<strong>درآمد:</strong> آنچه از فروش و خدمات به دست می‌آید.',
                            '<strong>مصرف:</strong> هزینه‌های اداره کار: کرایه، برق، معاش، حمل.',
                        ]],
                        ['h2', 'رابطه میان آن‌ها'],
                        ['formula', 'دارایی = بدهی + سرمایه'],
                        ['p', 'این برابری همیشه درست است. درآمد سرمایه را زیاد می‌کند و مصرف آن را کم. اگر آخر سال درآمد از مصرف بیشتر باشد، سرمایه شما بیشتر شده است.'],
                        ['tip', 'وقتی در نکست‌بوک یک حساب تازه به چارت اضافه می‌کنید، اول مشخص کنید در کدام گروه است. همین انتخاب تعیین می‌کند حساب در کدام گزارش بیاید.'],
                    ],
                ],
                'en' => [
                    'title' => 'The five types of account: assets, liabilities, equity, income and expenses',
                    'excerpt' => 'Every account you have falls into one of five groups. Once you know them, every financial report becomes easier to read.',
                    'body' => [
                        ['p', 'All the accounts of a company, small or large, fit into five groups. Two of them appear on the balance sheet, two on the profit and loss report, and one bridges the two.'],
                        ['h2', 'The five groups'],
                        ['ul', [
                            '<strong>Assets:</strong> everything you own: cash, bank, stock in the warehouse, what customers owe you.',
                            '<strong>Liabilities:</strong> everything you owe: suppliers, unpaid wages.',
                            '<strong>Equity:</strong> the owners\' share: starting capital and retained profit.',
                            '<strong>Income:</strong> what comes in from sales and services.',
                            '<strong>Expenses:</strong> the cost of running the business: rent, power, wages, transport.',
                        ]],
                        ['h2', 'How they relate'],
                        ['formula', 'Assets = Liabilities + Equity'],
                        ['p', 'This equation always holds. Income increases equity and expenses reduce it. If income is higher than expenses at the end of the year, your equity has grown.'],
                        ['tip', 'When you add a new account to the chart in NextBook, first decide which group it belongs to. That choice decides which report it appears in.'],
                    ],
                ],
                'ps' => [
                    'title' => 'د حسابونو پنځه ډولونه: شتمني، پور، پانګه، عاید او لګښت',
                    'excerpt' => 'ستاسو هر حساب له پنځو ډلو څخه په یوه کې ځای نیسي. کله چې دا پنځه ډلې وپېژنئ، هر مالي راپور لوستل اسان کېږي.',
                    'body' => [
                        ['p', 'د یو شرکت ټول حسابونه، وړوکي که لوی، په پنځو ډلو کې ځایېږي. دوه یې په ترازنامه کې راځي، دوه یې د ګټې او تاوان په راپور کې، او یو یې د دواړو ترمنځ پل دی.'],
                        ['h2', 'پنځه ډلې'],
                        ['ul', [
                            '<strong>شتمني:</strong> هر څه چې لرئ: نغدې، بانک، په ګدام کې موجودي، له پېرېدونکو طلب.',
                            '<strong>پور:</strong> هر څه چې باید ورکړئ: تامین‌کوونکو ته پور، نه‌ورکړل شوی معاش.',
                            '<strong>پانګه:</strong> د شرکت د مالکانو ونډه: لومړنۍ پانګه او پاتې ګټې.',
                            '<strong>عاید:</strong> هغه څه چې له پلور او خدمتونو لاسته راځي.',
                            '<strong>لګښت:</strong> د کار د پرمخبیولو مصارف: کرایه، برېښنا، معاش، لېږد.',
                        ]],
                        ['h2', 'ترمنځ یې اړیکه'],
                        ['formula', 'شتمني = پور + پانګه'],
                        ['p', 'دا برابري تل سمه وي. عاید پانګه زیاتوي او لګښت یې کموي. که د کال په پای کې عاید له لګښته ډېر وي، ستاسو پانګه زیاته شوې ده.'],
                        ['tip', 'کله چې په نکست‌بوک کې چارټ ته نوی حساب اضافه کوئ، لومړی وټاکئ چې په کومه ډله کې دی. همدا ټاکنه ښيي چې حساب په کوم راپور کې راځي.'],
                    ],
                ],
            ],
            [
                'slug' => 'chart-of-accounts-for-a-small-business',
                'icon' => 'list-tree',
                'days_ago' => 9,
                'fa' => [
                    'title' => 'چارت حساب برای یک تجارت کوچک: چه حساب‌هایی لازم است و چه حساب‌هایی نه',
                    'excerpt' => 'چارت حساب فهرست جاهایی است که پول شما در آن‌ها ثبت می‌شود. کوتاه و روشن نگهش دارید تا گزارش‌ها خوانا بمانند.',
                    'body' => [
                        ['p', 'چارت حساب ستون فقرات دفتر شماست. اگر خیلی کوتاه باشد، گزارش‌ها چیزی نمی‌گویند؛ اگر خیلی طولانی باشد، هر کس برای یک مصرف یک حساب تازه می‌سازد و دفتر شلوغ می‌شود.'],
                        ['h2', 'قاعده‌های ساده'],
                        ['ul', [
                            'برای هر گروه (دارایی، بدهی، سرمایه، درآمد، مصرف) فقط حساب‌هایی بسازید که واقعاً از آن‌ها تصمیم می‌گیرید.',
                            'مصرف‌ها را به اندازه‌ای جدا کنید که بدانید پول کجا می‌رود: کرایه، برق و آب، معاش، حمل و نقل، مصارف بازاریابی.',
                            'نام حساب‌ها را روشن بنویسید. «مصارف متفرقه» جای پنهان کردن اشتباه است.',
                            'حساب‌های بانکی و صندوق‌ها را هر کدام جدا نگه دارید.',
                        ]],
                        ['h2', 'سیستم از اول یک چارت دارد'],
                        ['p', 'لازم نیست از صفر شروع کنید. نکست‌بوک یک چارت حساب آماده دارد و می‌توانید حساب اضافه کنید. بهتر است اول با همان کار کنید و فقط وقتی حسابی را اضافه کنید که گزارشی بدون آن جواب سؤالتان را نمی‌دهد.'],
                        ['tip', 'یک آزمایش ساده: اگر برای یک حساب هرگز گزارشی نمی‌خوانید، شاید لازم نباشد. حساب‌های کم و روشن بهتر از حساب‌های زیاد و گنگ‌اند.'],
                    ],
                ],
                'en' => [
                    'title' => 'A chart of accounts for a small business: which accounts you need and which you do not',
                    'excerpt' => 'The chart of accounts is the list of places your money is recorded. Keep it short and clear so your reports stay readable.',
                    'body' => [
                        ['p', 'The chart of accounts is the backbone of your books. If it is too short, the reports say nothing. If it is too long, everyone makes a new account for every expense and the books get crowded.'],
                        ['h2', 'Simple rules'],
                        ['ul', [
                            'In each group (assets, liabilities, equity, income, expenses) create only the accounts you actually make decisions from.',
                            'Split expenses just enough to see where the money goes: rent, power and water, wages, transport, marketing.',
                            'Name accounts clearly. A "miscellaneous expenses" account is where mistakes hide.',
                            'Keep each bank account and each cash box separate.',
                        ]],
                        ['h2', 'The system starts with a chart'],
                        ['p', 'You do not have to start from zero. NextBook comes with a ready chart of accounts and you can add accounts. It is best to work with it first and add an account only when a report cannot answer your question without it.'],
                        ['tip', 'A simple test: if you never read a report for an account, you may not need it. A few clear accounts beat many vague ones.'],
                    ],
                ],
                'ps' => [
                    'title' => 'د وړوکې سوداګرۍ د حسابونو چارټ: کوم حسابونه په کار دي او کوم نه',
                    'excerpt' => 'د حسابونو چارټ د هغو ځایونو لړلیک دی چېرې چې ستاسو پیسې ثبتېږي. لنډ او څرګند یې وساتئ چې راپورونه ښه ولوستل شي.',
                    'body' => [
                        ['p', 'د حسابونو چارټ ستاسو د دفتر ملاتړ دی. که ډېر لنډ وي، راپورونه څه نه وايي؛ که ډېر اوږد وي، هر څوک د هر لګښت لپاره نوی حساب جوړوي او دفتر ګڼه‌ګوڼه کېږي.'],
                        ['h2', 'ساده قاعدې'],
                        ['ul', [
                            'په هره ډله کې (شتمني، پور، پانګه، عاید، لګښت) یوازې هغه حسابونه جوړ کړئ چې له هغو پرېکړه کوئ.',
                            'لګښتونه تر دې حده جلا کړئ چې وپوهېږئ پیسې چېرته ځي: کرایه، برېښنا او اوبه، معاش، لېږد، بازارموندنه.',
                            'د حسابونو نومونه څرګند ولیکئ. «متفرقه لګښتونه» هغه ځای دی چېرې چې تېروتنې پټېږي.',
                            'هر بانکي حساب او هر صندوق جلا وساتئ.',
                        ]],
                        ['h2', 'سیستم له پیله چارټ لري'],
                        ['p', 'اړتیا نشته چې له صفره پیل کړئ. نکست‌بوک چمتو د حسابونو چارټ لري او تاسو کولای شئ حسابونه زیات کړئ. ښه ده لومړی همدې سره کار وکړئ او یوازې هغه وخت حساب زیات کړئ چې بې له هغه راپور ستاسو پوښتنې ته ځواب نه ورکوي.'],
                        ['tip', 'یو ساده ازموینه: که د یوه حساب راپور هېڅکله نه لولئ، ښايي اړتیا یې نه وي. لږ او څرګند حسابونه له ډېرو او مبهمو ښه دي.'],
                    ],
                ],
            ],
            [
                'slug' => 'cash-vs-accrual-accounting',
                'icon' => 'calendar-check',
                'days_ago' => 12,
                'fa' => [
                    'title' => 'حسابداری نقدی یا تعهدی: فرق در چیست و کدام برای شما مناسب است',
                    'excerpt' => 'در یک روش، معامله وقتی ثبت می‌شود که پول رد و بدل شود؛ در روش دیگر، وقتی که معامله اتفاق می‌افتد. این فرق، سود شما را تغییر می‌دهد.',
                    'body' => [
                        ['p', 'فرض کنید امروز جنسی را قرضی فروختید و پولش یک ماه بعد می‌آید. آیا امروز فروش کرده‌اید یا ماه بعد؟ جواب به روش حسابداری بستگی دارد.'],
                        ['h2', 'روش نقدی'],
                        ['p', 'درآمد وقتی ثبت می‌شود که پول به دست بیاید و مصرف وقتی که پول داده شود. ساده است، ولی تصویر ناقصی می‌دهد: فروش‌های قرضی و بدهی‌های بعدی دیده نمی‌شوند.'],
                        ['h2', 'روش تعهدی'],
                        ['p', 'درآمد وقتی ثبت می‌شود که فروش اتفاق بیفتد، و مصرف وقتی که خرچ شود، پول رد و بدل شود یا نه. طلب‌ها و بدهی‌ها در دفتر می‌آیند، پس سود هر دوره واقعی‌تر است.'],
                        ['h2', 'کدام را انتخاب کنم؟'],
                        ['ul', [
                            'اگر فروش شما بیشتر نقد است و جنس و قرض زیاد ندارید، روش نقدی ممکن است کافی باشد.',
                            'اگر قرضی می‌فروشید، قرضی می‌خرید یا انبار دارید، روش تعهدی تصویر درست‌تری از سود و وضع مالی می‌دهد.',
                        ]],
                        ['tip', 'نکست‌بوک فروش و خرید قرضی را همان وقت ثبت می‌کند و طلب‌ها و بدهی‌ها را جدا نگه می‌دارد، یعنی شما با تصویر تعهدی کار می‌کنید بدون اینکه چیزی را دستی جابه‌جا کنید.'],
                    ],
                ],
                'en' => [
                    'title' => 'Cash or accrual accounting: what is the difference and which suits you',
                    'excerpt' => 'In one method a transaction is recorded when money changes hands; in the other, when the transaction happens. The difference changes your profit.',
                    'body' => [
                        ['p', 'Say you sold goods on credit today and the money arrives a month later. Did you make the sale today or next month? The answer depends on the accounting method.'],
                        ['h2', 'The cash method'],
                        ['p', 'Income is recorded when the money arrives and an expense when the money is paid. It is simple, but it gives an incomplete picture: credit sales and later debts are not visible.'],
                        ['h2', 'The accrual method'],
                        ['p', 'Income is recorded when the sale happens and an expense when it is incurred, whether or not money has moved. What customers owe you and what you owe appear in the books, so the profit for each period is closer to reality.'],
                        ['h2', 'Which should I choose?'],
                        ['ul', [
                            'If most of your sales are cash and you hold little stock or credit, the cash method may be enough.',
                            'If you sell on credit, buy on credit or hold stock, the accrual method gives a truer picture of profit and position.',
                        ]],
                        ['tip', 'NextBook records credit sales and purchases when they happen and keeps receivables and payables apart, so you work with the accrual picture without moving anything by hand.'],
                    ],
                ],
                'ps' => [
                    'title' => 'نغدي که تعهدي محاسبه: توپیر په څه کې دی او کومه ستاسو لپاره ښه ده',
                    'excerpt' => 'په یوه طریقه کې معامله هغه وخت ثبتېږي چې پیسې لاس په لاس شي؛ په بله کې کله چې معامله وشي. همدا توپیر ستاسو ګټه بدلوي.',
                    'body' => [
                        ['p', 'فرض کړئ نن مو توکي په قرض وپلورل او پیسې یې یوه میاشت وروسته راځي. آیا پلور نن کړی که بله میاشت؟ ځواب د محاسبې په طریقې پورې اړه لري.'],
                        ['h2', 'نغدي طریقه'],
                        ['p', 'عاید هغه وخت ثبتېږي چې پیسې ترلاسه شي او لګښت هغه وخت چې پیسې ورکړل شي. ساده ده، خو نیمګړی انځور ورکوي: قرضي پلورونه او راتلونکي پورونه نه ښکاري.'],
                        ['h2', 'تعهدي طریقه'],
                        ['p', 'عاید هغه وخت ثبتېږي چې پلور وشي او لګښت هغه وخت چې رامنځته شي، پیسې لاس په لاس شوې وي که نه. طلبونه او پورونه په دفتر کې راځي، نو د هرې دورې ګټه ریښتینې ته نږدې وي.'],
                        ['h2', 'کومه یې غوره کړم؟'],
                        ['ul', [
                            'که ستاسو ډېر پلور نغدې وي او ډېر توکي یا قرض نه لرئ، نغدي طریقه شاید بس وي.',
                            'که په قرض پلورئ، په قرض پېرئ یا ګدام لرئ، تعهدي طریقه د ګټې او مالي حالت ریښتینی انځور ورکوي.',
                        ]],
                        ['tip', 'نکست‌بوک قرضي پلورونه او پېرونه هماغه وخت ثبتوي او طلبونه او پورونه جلا ساتي، یعنې تاسو له تعهدي انځور سره کار کوئ بې له دې چې څه په لاس جابجا کړئ.'],
                    ],
                ],
            ],
            [
                'slug' => 'month-end-closing-checklist',
                'icon' => 'clipboard-check',
                'days_ago' => 15,
                'fa' => [
                    'title' => 'فهرست بستن ماه: هشت کاری که پایان هر ماه باید بکنید',
                    'excerpt' => 'بستن ماه یعنی مطمئن شوید دفتر با واقعیت می‌خواند. با یک فهرست ثابت، کار ساعت‌ها می‌شود نه هفته‌ها.',
                    'body' => [
                        ['p', 'آخر ماه، پیش از اینکه به گزارش‌ها نگاه کنید، باید مطمئن باشید همه معامله‌ها ثبت شده‌اند و اعداد درست‌اند. این فهرست را هر ماه به همین ترتیب تکرار کنید.'],
                        ['ol', [
                            'همه بیل‌های فروش و خرید ماه را پست کنید و پیش‌نویس باقی نگذارید.',
                            'رسیدها و پرداخت‌ها را کنترول کنید و به بیل مربوطشان وصل کنید.',
                            'موجودی نقد و صندوق را بشمارید و با دفتر مقایسه کنید.',
                            'مانده هر حساب بانکی را با صورت‌حساب بانک بسنجید.',
                            'موجودی اجناس مهم را بشمارید و فرق‌ها را با تعدیل درست کنید.',
                            'مصارفی را که فراموش شده (کرایه، برق، معاش) ثبت کنید.',
                            'لیست قرضداران و بدهی‌ها را ببینید و کسی را که باید پیگیری کنید یادداشت کنید.',
                            'گزارش سود و زیان و ترازنامه را بخوانید و هر عدد عجیب را بپرسید.',
                        ]],
                        ['warn', 'پیش از بستن دوره، همه کنترول‌ها را تمام کنید. بعد از بستن دوره، تغییر ثبت‌های آن ماه محدود می‌شود.'],
                        ['tip', 'در نکست‌بوک دوره مالی و بستن دوره دارید. وقتی همه کنترول‌ها تمام شد، دوره را ببندید تا ثبت‌های آن ماه بی‌خبر تغییر نکنند.'],
                    ],
                ],
                'en' => [
                    'title' => 'A month-end closing checklist: eight things to do at the end of every month',
                    'excerpt' => 'Closing the month means making sure the books match reality. With a fixed checklist, the work takes hours, not weeks.',
                    'body' => [
                        ['p', 'At month end, before you look at any report, make sure every transaction is recorded and the numbers are right. Repeat this list in the same order every month.'],
                        ['ol', [
                            'Post all the month\'s sales and purchase bills and leave no drafts behind.',
                            'Check receipts and payments and link them to their bills.',
                            'Count the cash in the till and compare it with the books.',
                            'Compare each bank account\'s balance with the bank statement.',
                            'Count your important stock and correct differences with an adjustment.',
                            'Record any forgotten expenses (rent, power, wages).',
                            'Look at the lists of who owes you and whom you owe, and note who to follow up.',
                            'Read the profit and loss report and the balance sheet and ask about every odd number.',
                        ]],
                        ['warn', 'Finish all the checks before you close the period. After closing, changes to that month\'s entries are restricted.'],
                        ['tip', 'NextBook has financial periods and period closing. When every check is done, close the period so that the month\'s entries cannot change unnoticed.'],
                    ],
                ],
                'ps' => [
                    'title' => 'د میاشتې د تړلو لړلیک: اته کارونه چې د هرې میاشتې په پای کې باید وکړئ',
                    'excerpt' => 'د میاشتې تړل یعنې ډاډ ترلاسه کړئ چې دفتر له واقعیت سره سمون لري. په ثابت لړلیک سره کار ساعتونه نیسي، نه اونۍ.',
                    'body' => [
                        ['p', 'د میاشتې په پای کې، مخکې له دې چې راپورونو ته وګورئ، باید ډاډ ولرئ چې ټولې معاملې ثبت شوې او شمېرې سمې دي. دا لړلیک هره میاشت په همدې ترتیب تکرار کړئ.'],
                        ['ol', [
                            'د میاشتې ټول د پلور او پېر بیلونه پوسټ کړئ او مسوده مه پرېږدئ.',
                            'رسیدونه او ورکړې وګورئ او د هغوی له اړوند بیل سره یې وتړئ.',
                            'په صندوق کې نغدې وشمېرئ او له دفتر سره یې پرتله کړئ.',
                            'د هر بانکي حساب مانده د بانک له صورت‌حساب سره وسنجوئ.',
                            'مهم توکي وشمېرئ او توپیرونه په تعدیل سره سم کړئ.',
                            'هېر شوي لګښتونه (کرایه، برېښنا، معاش) ثبت کړئ.',
                            'د پوروړو او پورونو لړلیکونه وګورئ او هغه څوک ولیکئ چې باید تعقیب یې کړئ.',
                            'د ګټې او تاوان راپور او ترازنامه ولولئ او د هرې عجیبې شمېرې په اړه پوښتنه وکړئ.',
                        ]],
                        ['warn', 'مخکې له دورې تړلو ټول کنټرولونه بشپړ کړئ. وروسته له تړلو، د هغې میاشتې د ثبتونو بدلون محدودېږي.'],
                        ['tip', 'په نکست‌بوک کې مالي دوره او د دورې تړل لرئ. کله چې ټول کنټرولونه بشپړ شول، دوره وتړئ چې د میاشتې ثبتونه بې خبره ونه بدلېږي.'],
                    ],
                ],
            ],
            [
                'slug' => 'managing-customer-credit',
                'icon' => 'hand-coins',
                'days_ago' => 18,
                'fa' => [
                    'title' => 'مدیریت قرض مشتریان: چطور بدون از دست دادن مشتری، پولتان را جمع کنید',
                    'excerpt' => 'فروش قرضی مشتری را نگه می‌دارد، ولی پولی که جمع نشود فروش نیست. چند عادت ساده خطر را کم می‌کند.',
                    'body' => [
                        ['p', 'در بازار، قرض بخشی از کار است. مشکل از قرض نیست؛ از بی‌حسابی است: کسی نمی‌داند چه کسی چقدر و از کی قرضدار است.'],
                        ['h2', 'پنج عادت ساده'],
                        ['ul', [
                            'برای هر مشتری یک سقف قرض تعیین کنید و آن را تا مبلغ اطمینانتان بالا ببرید، نه بیشتر.',
                            'تاریخ سررسید را از اول بگویید و بنویسید.',
                            'هر هفته لیست قرضداران را ببینید، نه آخر سال.',
                            'قرض‌های کهنه‌تر را جدا کنید: قرضی که ۶۰ روز گذشته، با قرض دیروز یکی نیست.',
                            'پیش از فروش تازه به کسی که قرض دیر مانده، اول پول قبلی را بخواهید.',
                        ]],
                        ['h2', 'قرض کهنه چرا خطرناک است؟'],
                        ['p', 'هر هفته‌ای که می‌گذرد، احتمال جمع شدن پول کمتر می‌شود. قرضی که پیگیری نشود، آهسته به ضرر تبدیل می‌شود، در حالی که در دفتر هنوز دارایی نوشته شده است.'],
                        ['tip', 'در نکست‌بوک هر مشتری حساب جدا دارد و می‌توانید طلب‌ها و بیلانس افتتاحیه را ببینید. هر روز نگاهی به حساب‌های باز، از قرض‌های فراموش‌شده جلوگیری می‌کند.'],
                    ],
                ],
                'en' => [
                    'title' => 'Managing customer credit: how to collect your money without losing the customer',
                    'excerpt' => 'Selling on credit keeps customers, but money you never collect is not a sale. A few simple habits cut the risk.',
                    'body' => [
                        ['p', 'In the market, credit is part of the job. The problem is not the credit; it is the lack of records: nobody knows who owes how much and since when.'],
                        ['h2', 'Five simple habits'],
                        ['ul', [
                            'Set a credit limit for each customer and raise it only as far as you trust them, no further.',
                            'State the due date at the start and write it down.',
                            'Look at the list of who owes you every week, not at the end of the year.',
                            'Separate the older debts: a debt 60 days old is not the same as yesterday\'s.',
                            'Before a new sale to someone who is late, ask for the earlier money first.',
                        ]],
                        ['h2', 'Why are old debts dangerous?'],
                        ['p', 'Each week that passes, the chance of collecting falls. A debt nobody follows up slowly turns into a loss, while the books still show it as an asset.'],
                        ['tip', 'In NextBook every customer has their own account and you can see receivables and opening balances. A daily look at open accounts stops debts from being forgotten.'],
                    ],
                ],
                'ps' => [
                    'title' => 'د پېرېدونکو د قرض مدیریت: څنګه پیسې راټولې کړئ او پېرېدونکی له لاسه ورنکړئ',
                    'excerpt' => 'قرضي پلور پېرېدونکی ساتي، خو هغه پیسې چې راټولې نه شي پلور نه دی. څو ساده عادتونه خطر کموي.',
                    'body' => [
                        ['p', 'په بازار کې قرض د کار برخه ده. ستونزه له قرضه نه ده؛ له بې‌حسابۍ ده: هېڅوک نه پوهېږي چې څوک څومره او له کله پوروړی دی.'],
                        ['h2', 'پنځه ساده عادتونه'],
                        ['ul', [
                            'د هر پېرېدونکي لپاره د قرض سقف وټاکئ او یوازې تر هغه حده یې لوړ کړئ چې باور ورباندې لرئ.',
                            'د سررسید نېټه له پیله ووایئ او ولیکئ.',
                            'هره اونۍ د پوروړو لړلیک وګورئ، نه د کال په پای کې.',
                            'زاړه پورونه جلا کړئ: هغه پور چې ۶۰ ورځې تېر شوی، له پرون پور سره یو شان نه دی.',
                            'مخکې له نوي پلور هغه چا ته چې پور یې ځنډېدلی، لومړی پخوانی پیسې وغواړئ.',
                        ]],
                        ['h2', 'زاړه پورونه ولې خطرناک دي؟'],
                        ['p', 'هره اونۍ چې تېرېږي، د پیسو د راټولېدو شونتیا کمېږي. هغه پور چې تعقیب نه شي، ورو ورو تاوان ګرځي، که څه هم په دفتر کې لا هم شتمني لیکل شوې وي.'],
                        ['tip', 'په نکست‌بوک کې هر پېرېدونکی جلا حساب لري او تاسو کولای شئ طلبونه او پیل‌مانده وګورئ. هره ورځ پرانیستو حسابونو ته یوه کتنه د هېر شوو پورونو مخه نیسي.'],
                    ],
                ],
            ],
            [
                'slug' => 'supplier-payables-and-payment-planning',
                'icon' => 'receipt',
                'days_ago' => 21,
                'fa' => [
                    'title' => 'بدهی به تامین‌کننده‌ها: چطور پرداخت‌ها را برنامه‌ریزی کنیم',
                    'excerpt' => 'بدهی خوب یا بد نیست؛ بی‌برنامه بودنش بد است. با یک فهرست سررسیدها، هم اعتبارتان می‌ماند و هم نقدی‌تان.',
                    'body' => [
                        ['p', 'خرید قرضی از تامین‌کننده یعنی پول او چند هفته در دست شما کار می‌کند. این یک فایده است، به شرطی که سررسید را فراموش نکنید و ندانید که همه پرداخت‌ها یک‌جا نمی‌رسند.'],
                        ['h2', 'چه چیزهایی را بدانید'],
                        ['ul', [
                            'هر تامین‌کننده چقدر از شما طلب دارد، و کدام بخشش سررسید شده.',
                            'در چند هفته آینده چه مبلغی باید پرداخت شود.',
                            'از نقد و بانک چقدر می‌توانید بپردازید بدون اینکه کار روزانه بایستد.',
                        ]],
                        ['h2', 'ترتیب پرداخت'],
                        ['p', 'اول چیزی را که سررسیدش گذشته یا تخفیف پرداخت زود دارد، بعد تامین‌کننده‌های اصلی که اعتبار شما نزدشان مهم است، و بعد بقیه. اگر نمی‌توانید همه را بدهید، با تامین‌کننده صادقانه صحبت کنید و تاریخ مشخص بدهید.'],
                        ['tip', 'در نکست‌بوک هر خرید قرضی روی حساب تامین‌کننده می‌نشیند و پرداخت را به بیل وصل می‌کنید. یک نگاه به حساب‌های باز، فهرست پرداخت‌های بعدی شماست.'],
                    ],
                ],
                'en' => [
                    'title' => 'Supplier payables: how to plan your payments',
                    'excerpt' => 'Debt is neither good nor bad; being unplanned is bad. With a list of due dates you keep both your credit and your cash.',
                    'body' => [
                        ['p', 'Buying on credit from a supplier means their money works for you for a few weeks. That is an advantage, as long as you do not forget the due date or assume every payment arrives at once.'],
                        ['h2', 'What to know'],
                        ['ul', [
                            'How much each supplier is owed, and which part is overdue.',
                            'How much must be paid in the next few weeks.',
                            'How much you can pay from cash and bank without stopping daily work.',
                        ]],
                        ['h2', 'The order of payment'],
                        ['p', 'First what is overdue or carries a discount for early payment, then the main suppliers whose trust matters most, then the rest. If you cannot pay everyone, speak honestly with the supplier and give a firm date.'],
                        ['tip', 'In NextBook each credit purchase lands on the supplier\'s account and you link the payment to the bill. A look at the open accounts is your list of upcoming payments.'],
                    ],
                ],
                'ps' => [
                    'title' => 'تامین‌کوونکو ته پورونه: ورکړې څنګه پلان کړو',
                    'excerpt' => 'پور نه ښه دی نه بد؛ بې‌پلانه والی بد دی. د سررسیدونو په لړلیک سره هم اعتبار ساتئ او هم نغدې.',
                    'body' => [
                        ['p', 'له تامین‌کوونکي په قرض پېر کول یعنې د هغه پیسې څو اونۍ ستاسو په لاس کې کار کوي. دا یوه ګټه ده، که سررسید مو هېر نه شي او فکر مو نه وي چې ټولې ورکړې یو ځای نه رسېږي.'],
                        ['h2', 'څه باید وپوهېږئ'],
                        ['ul', [
                            'هر تامین‌کوونکی څومره طلب لري او کومه برخه یې سررسید شوې.',
                            'په راتلونکو څو اونیو کې کومه پیسه باید ورکړل شي.',
                            'له نغدو او بانک څخه څومره ورکولای شئ چې ورځنی کار ودرېږي نه.',
                        ]],
                        ['h2', 'د ورکړې ترتیب'],
                        ['p', 'لومړی هغه چې سررسید یې تېر شوی یا د ژر ورکړې تخفیف لري، بیا اصلي تامین‌کوونکي چې ستاسو اعتبار ورسره مهم دی، او بیا پاتې. که ټولو ته نه شئ ورکولای، له تامین‌کوونکي سره صادقانه خبرې وکړئ او ټاکلې نېټه ورکړئ.'],
                        ['tip', 'په نکست‌بوک کې هر قرضي پېر د تامین‌کوونکي په حساب کې ځای نیسي او ورکړه له بیل سره تړئ. پرانیستو حسابونو ته یوه کتنه ستاسو د راتلونکو ورکړو لړلیک دی.'],
                    ],
                ],
            ],
            [
                'slug' => 'stock-counting-and-adjustments',
                'icon' => 'package-check',
                'days_ago' => 24,
                'fa' => [
                    'title' => 'شمارش گدام و تعدیل موجودی: چرا عدد سیستم و عدد قفسه فرق می‌کند',
                    'excerpt' => 'حتی بهترین گدام‌دار هم گاهی اشتباه می‌کند، جنس خراب می‌شود یا کسی بی‌ثبت چیزی را برمی‌دارد. شمارش منظم این فرق را زود پیدا می‌کند.',
                    'body' => [
                        ['p', 'موجودی در سیستم یعنی آنچه ثبت شده، و موجودی در قفسه یعنی آنچه واقعاً هست. اگر این دو برابر نباشند، قیمت تمام‌شده و سود شما هم درست نیست.'],
                        ['h2', 'چرا فرق می‌افتد'],
                        ['ul', [
                            'یک فروش یا انتقال بی‌ثبت ماند.',
                            'جنس خراب یا منقضی شد و کسی ثبت نکرد.',
                            'در شمارش یا ثبت خرید اشتباه شد.',
                            'دزدی یا ضایعات.',
                        ]],
                        ['h2', 'روش پیشنهادی'],
                        ['ol', [
                            'یک روز یا ساعت ثابت را برای شمارش انتخاب کنید و در آن ساعت معامله ثبت نکنید.',
                            'اجناس پرفروش و گران را هر ماه بشمارید، بقیه را هر سه ماه.',
                            'فرق را مقایسه کنید و علت را بپرسید، پیش از آنکه درست کنید.',
                            'بعد از یافتن علت، فرق را با یک تعدیل با دلیل مشخص ثبت کنید.',
                        ]],
                        ['tip', 'در نکست‌بوک تعدیل موجودی ابزار مستقیم برای درست کردن فرق است و در لاگ فعالیت ثبت می‌شود که چه کسی، چه وقت و چرا. هیچ تعدیلی بی‌اثر نمی‌ماند.'],
                    ],
                ],
                'en' => [
                    'title' => 'Stock counts and adjustments: why the system number and the shelf number differ',
                    'excerpt' => 'Even the best storekeeper makes mistakes, goods spoil, or someone takes something unrecorded. Regular counts find the gap early.',
                    'body' => [
                        ['p', 'Stock in the system is what was recorded; stock on the shelf is what is really there. If they differ, your cost price and your profit are wrong too.'],
                        ['h2', 'Why gaps appear'],
                        ['ul', [
                            'A sale or transfer went unrecorded.',
                            'Goods spoiled or expired and nobody recorded it.',
                            'A mistake in counting or in entering a purchase.',
                            'Theft or waste.',
                        ]],
                        ['h2', 'A method that works'],
                        ['ol', [
                            'Pick a fixed day or hour for counting and record no transactions during it.',
                            'Count your fast-moving and expensive items monthly, the rest every three months.',
                            'Compare the difference and ask why before you correct it.',
                            'Once you know the cause, record the difference with an adjustment and a clear reason.',
                        ]],
                        ['tip', 'In NextBook a stock adjustment is the direct tool for correcting a difference, and the activity log records who did it, when and why. No adjustment goes unnoticed.'],
                    ],
                ],
                'ps' => [
                    'title' => 'د ګدام شمېرل او د موجودۍ تعدیل: ولې د سیستم شمېره او د المارۍ شمېره توپیر لري',
                    'excerpt' => 'تر ټولو ښه ګدام‌وال هم ځینې وخت تېروتنه کوي، توکي خرابېږي یا څوک بې ثبته څه اخلي. منظم شمېرل دا توپیر ژر موندلی شي.',
                    'body' => [
                        ['p', 'په سیستم کې موجودي هغه ده چې ثبت شوې، او په المارۍ کې موجودي هغه ده چې واقعاً شته. که دا دواړه برابر نه وي، ستاسو تمام‌شوې بیه او ګټه هم سمه نه ده.'],
                        ['h2', 'توپیر ولې پیدا کېږي'],
                        ['ul', [
                            'یو پلور یا انتقال بې ثبته پاتې شو.',
                            'توکي خراب یا ختم‌میعاده شول او چا ثبت نه کړل.',
                            'په شمېرنه یا د پېر په ثبت کې تېروتنه وشوه.',
                            'غلا یا ضایع.',
                        ]],
                        ['h2', 'یوه ښه طریقه'],
                        ['ol', [
                            'د شمېرلو لپاره یوه ټاکلې ورځ یا ساعت وټاکئ او په هغه وخت کې معامله مه ثبتوئ.',
                            'ډېر پلورل شوي او قیمتي توکي هره میاشت وشمېرئ، پاتې هرې درې میاشتې.',
                            'توپیر پرتله کړئ او مخکې له سمولو دلیل وپوښتئ.',
                            'کله چې دلیل مو وموند، توپیر په یوه تعدیل او څرګند دلیل سره ثبت کړئ.',
                        ]],
                        ['tip', 'په نکست‌بوک کې د موجودۍ تعدیل د توپیر د سمولو مستقیمه وسیله ده او د فعالیت په لاګ کې ثبتېږي چې چا، کله او ولې. هېڅ تعدیل بې‌اثره نه پاتېږي.'],
                    ],
                ],
            ],
            [
                'slug' => 'profit-is-not-cash',
                'icon' => 'wallet',
                'days_ago' => 27,
                'fa' => [
                    'title' => 'سود با پول نقد یکی نیست: چرا شرکت سودده می‌تواند نقدی نداشته باشد',
                    'excerpt' => 'سود روی کاغذ است و نقد در صندوق. یک تجارت سودده هم اگر پولش در قرض‌ها و گدام بماند، ممکن است برای معاش پول نداشته باشد.',
                    'body' => [
                        ['p', 'این یکی از تعجب‌آورترین چیزها برای صاحبان کار است: گزارش می‌گوید این ماه سود کرده‌ایم، ولی در بانک پول نیست. هر دو عدد درست‌اند.'],
                        ['h2', 'پول کجا رفت؟'],
                        ['ul', [
                            'در قرض‌های مشتریان که هنوز جمع نشده است.',
                            'در جنس جدیدی که برای فروش آینده خریده‌اید و در گدام نشسته است.',
                            'در بدهی‌هایی که پرداخته‌اید یا قسط‌هایی که داده‌اید.',
                            'در برداشت صاحبان.',
                        ]],
                        ['h2', 'دو گزارش، دو سؤال'],
                        ['p', 'گزارش سود و زیان می‌گوید «آیا کار ما سودآور است؟» و موجودی نقد و بانک می‌گوید «آیا فردا می‌توانیم معاش بدهیم؟». هر دو را ببینید.'],
                        ['tip', 'برای نقدی بهتر: قرض‌ها را سریع‌تر جمع کنید، خرید اضافی نکنید، و از بدهی به تامین‌کننده تا حد ممکن در سررسید استفاده کنید. نکست‌بوک نقد و بانک را همراه سود نشان می‌دهد تا هر دو تصویر کنار هم باشند.'],
                    ],
                ],
                'en' => [
                    'title' => 'Profit is not cash: why a profitable business can run out of money',
                    'excerpt' => 'Profit is on paper and cash is in the till. A profitable business whose money sits in credit and stock may still be unable to pay wages.',
                    'body' => [
                        ['p', 'This surprises many owners: the report says we made a profit this month, but there is no money in the bank. Both numbers are right.'],
                        ['h2', 'Where did the money go?'],
                        ['ul', [
                            'Into customer credit that has not been collected yet.',
                            'Into new goods you bought for future sales, sitting in the warehouse.',
                            'Into debts and instalments you paid.',
                            'Into the owners\' drawings.',
                        ]],
                        ['h2', 'Two reports, two questions'],
                        ['p', 'The profit and loss report asks "is our business profitable?" and the cash and bank balance asks "can we pay wages tomorrow?". Look at both.'],
                        ['tip', 'For better cash: collect credit faster, avoid excess purchases, and use supplier credit up to its due date. NextBook shows cash and bank beside profit so both pictures sit side by side.'],
                    ],
                ],
                'ps' => [
                    'title' => 'ګټه او نغدې پیسې یو شی نه دي: ولې ګټور شرکت ښايي نغدې ونه لري',
                    'excerpt' => 'ګټه په کاغذ ده او نغدې په صندوق کې. ګټوره سوداګري که پیسې یې په قرضونو او ګدام کې پاتې شي، ښايي د معاش لپاره پیسې ونه لري.',
                    'body' => [
                        ['p', 'دا د مالکانو لپاره تر ټولو حیرانوونکې خبره ده: راپور وايي چې دا میاشت ګټه شوې، خو په بانک کې پیسې نشته. دواړه شمېرې سمې دي.'],
                        ['h2', 'پیسې چېرته لاړې؟'],
                        ['ul', [
                            'د پېرېدونکو په هغو قرضونو کې چې لا نه دي راټول شوي.',
                            'په نوو توکو کې چې د راتلونکي پلور لپاره مو اخیستي او په ګدام کې ناست دي.',
                            'په هغو پورونو او قسطونو کې چې مو ورکړي.',
                            'د مالکانو په برداشت کې.',
                        ]],
                        ['h2', 'دوه راپورونه، دوې پوښتنې'],
                        ['p', 'د ګټې او تاوان راپور وايي «آیا زموږ کار ګټور دی؟» او د نغدو او بانک مانده وايي «آیا سبا کولای شو معاش ورکړو؟». دواړه وګورئ.'],
                        ['tip', 'د ښه نغدو لپاره: قرضونه ژر راټول کړئ، ډېر پېر مه کوئ، او د تامین‌کوونکي له قرضه تر سررسیده پورې ګټه واخلئ. نکست‌بوک نغدې او بانک د ګټې تر څنګ ښيي چې دواړه انځورونه یو ځای وي.'],
                    ],
                ],
            ],
            [
                'slug' => 'margin-vs-markup',
                'icon' => 'percent',
                'days_ago' => 30,
                'fa' => [
                    'title' => 'مارجین و مارک‌آپ: دو درصد که اغلب با هم اشتباه گرفته می‌شوند',
                    'excerpt' => 'یک جنس را ۱۰۰ می‌خرید و ۱۳۰ می‌فروشید. سود شما ۳۰٪ است یا ۲۳٪؟ هر دو درست‌اند، ولی از دو زاویه.',
                    'body' => [
                        ['p', 'این دو عدد را اشتباه گرفتن باعث می‌شود قیمت را کم‌تر از آنچه فکر می‌کنید بگذارید. باید هر دو را بشناسید.'],
                        ['h2', 'مارک‌آپ (افزودن به قیمت خرید)'],
                        ['formula', 'مارک‌آپ = (قیمت فروش − قیمت خرید) ÷ قیمت خرید'],
                        ['p', 'خرید ۱۰۰، فروش ۱۳۰: مارک‌آپ ۳۰٪.'],
                        ['h2', 'مارجین (سود از قیمت فروش)'],
                        ['formula', 'مارجین = (قیمت فروش − قیمت خرید) ÷ قیمت فروش'],
                        ['p', 'همان خرید ۱۰۰ و فروش ۱۳۰: مارجین حدود ۲۳٪.'],
                        ['h2', 'چرا فرق مهم است؟'],
                        ['p', 'اگر هدف شما ۳۰٪ از فروش است و فقط ۳۰٪ روی خرید اضافه کنید، به ۳۰٪ مارجین نمی‌رسید. برای مارجین ۳۰٪ باید قیمت خرید را بر ۰٫۷ تقسیم کنید. (اعداد مثال‌ها فرضی‌اند.)'],
                        ['tip', 'قیمت تمام‌شده درست، پایه هر دو است. اگر هزینه حمل و گمرک را به قیمت خرید اضافه نکنید، مارجین واقعی شما کمتر از چیزی است که حساب کرده‌اید.'],
                    ],
                ],
                'en' => [
                    'title' => 'Margin and markup: two percentages that are often mixed up',
                    'excerpt' => 'You buy an item for 100 and sell it for 130. Is your profit 30% or 23%? Both are right, from two angles.',
                    'body' => [
                        ['p', 'Mixing these two numbers up makes you price lower than you think. You need to know both.'],
                        ['h2', 'Markup (added to the buying price)'],
                        ['formula', 'Markup = (selling price − buying price) ÷ buying price'],
                        ['p', 'Buy at 100, sell at 130: markup 30%.'],
                        ['h2', 'Margin (profit from the selling price)'],
                        ['formula', 'Margin = (selling price − buying price) ÷ selling price'],
                        ['p', 'The same buy at 100 and sale at 130: margin about 23%.'],
                        ['h2', 'Why the difference matters'],
                        ['p', 'If your target is 30% of sales and you only add 30% to the cost, you will not reach a 30% margin. For a 30% margin you divide the cost by 0.7. (The numbers in the examples are made up.)'],
                        ['tip', 'A correct cost price is the base of both. If you do not add freight and customs to the buying price, your real margin is lower than you calculated.'],
                    ],
                ],
                'ps' => [
                    'title' => 'مارجین او مارک‌اپ: دوه سلنې چې ډېر وخت سره ګډوډېږي',
                    'excerpt' => 'یو توکی په ۱۰۰ اخلئ او په ۱۳۰ یې پلورئ. ستاسو ګټه ۳۰٪ ده که ۲۳٪؟ دواړه سم دي، خو له دوو زاویو.',
                    'body' => [
                        ['p', 'د دې دوو شمېرو ګډوډول دا لامل کېږي چې بیه تر هغه ټیټه ټاکئ چې فکر یې کوئ. باید دواړه وپېژنئ.'],
                        ['h2', 'مارک‌اپ (د پېر پر بیه زیاتول)'],
                        ['formula', 'مارک‌اپ = (د پلور بیه − د پېر بیه) ÷ د پېر بیه'],
                        ['p', 'په ۱۰۰ پېر، په ۱۳۰ پلور: مارک‌اپ ۳۰٪.'],
                        ['h2', 'مارجین (د پلور له بیې ګټه)'],
                        ['formula', 'مارجین = (د پلور بیه − د پېر بیه) ÷ د پلور بیه'],
                        ['p', 'همدا په ۱۰۰ پېر او په ۱۳۰ پلور: مارجین شاوخوا ۲۳٪.'],
                        ['h2', 'توپیر ولې مهم دی؟'],
                        ['p', 'که ستاسو موخه د پلور ۳۰٪ وي او یوازې پر لګښت ۳۰٪ زیات کړئ، ۳۰٪ مارجین ته نه رسېږئ. د ۳۰٪ مارجین لپاره باید لګښت پر ۰٫۷ وویشئ. (د بېلګو شمېرې فرضي دي.)'],
                        ['tip', 'سمه تمام‌شوې بیه د دواړو بنسټ دی. که کرایه او ګمرک د پېر پر بیه زیات نه کړئ، ستاسو ریښتینی مارجین له هغه کم دی چې حساب مو کړی.'],
                    ],
                ],
            ],
            [
                'slug' => 'afghani-and-dollar-exchange-differences',
                'icon' => 'coins',
                'days_ago' => 33,
                'fa' => [
                    'title' => 'افغانی و دالر در یک دفتر: نرخ تبدیل و فرق ارز',
                    'excerpt' => 'وقتی به دالر می‌خرید و به افغانی می‌فروشید، نرخ تبدیل بین دو معامله تغییر می‌کند. این تغییر یا سود است یا زیان.',
                    'body' => [
                        ['p', 'در افغانستان، بسیاری از تاجران با دو ارز کار می‌کنند. اگر نرخ تبدیل را درست ثبت نکنید، ممکن است بدون اینکه بدانید پول از دست بدهید.'],
                        ['h2', 'یک مثال'],
                        ['p', 'امروز جنسی را ۱٬۰۰۰ دالر می‌خرید، وقتی هر دالر ۶۸ افغانی است: ۶۸٬۰۰۰ افغانی. دو هفته بعد که می‌پردازید، نرخ ۷۰ شده و باید ۷۰٬۰۰۰ افغانی بدهید. ۲٬۰۰۰ افغانی زیان از تغییر نرخ، نه از خود جنس. (اعداد فرضی‌اند.)'],
                        ['h2', 'چه چیزی را ثبت کنید'],
                        ['ul', [
                            'مبلغ معامله به ارز اصلی (دالر).',
                            'نرخ تبدیل روز معامله.',
                            'معادل به افغانی که دفتر شما با آن کار می‌کند.',
                        ]],
                        ['h2', 'فرق نرخ'],
                        ['p', 'هر بار که طلب یا بدهی به ارز خارجی با نرخ دیگری تسویه می‌شود، فرق به‌صورت سود یا زیان ارزی می‌آید. این را از سود فروش جدا ببینید تا بدانید جنس سودآور بوده یا فقط نرخ.'],
                        ['tip', 'نکست‌بوک مبلغ هر معامله را هم به ارز اصلی و هم به ارز دیگر با نرخ تبدیل ثبت می‌کند، پس لازم نیست با ماشین‌حساب جدا حساب کنید.'],
                    ],
                ],
                'en' => [
                    'title' => 'Afghani and dollars in one set of books: exchange rates and exchange differences',
                    'excerpt' => 'When you buy in dollars and sell in afghani, the exchange rate moves between the two deals. That move is either a gain or a loss.',
                    'body' => [
                        ['p', 'In Afghanistan many traders work in two currencies. If you do not record the exchange rate properly, you can lose money without knowing it.'],
                        ['h2', 'An example'],
                        ['p', 'Today you buy goods for 1,000 dollars when a dollar is 68 afghani: 68,000 afghani. Two weeks later when you pay, the rate is 70 and you must give 70,000 afghani. That is a 2,000 afghani loss from the rate, not from the goods. (The numbers are made up.)'],
                        ['h2', 'What to record'],
                        ['ul', [
                            'The amount of the deal in the original currency (dollars).',
                            'The exchange rate on the day of the deal.',
                            'The afghani equivalent your books are kept in.',
                        ]],
                        ['h2', 'The exchange difference'],
                        ['p', 'Each time a foreign-currency receivable or payable is settled at a different rate, the difference becomes an exchange gain or loss. Look at it apart from your sales profit, so you know whether the goods were profitable or only the rate was.'],
                        ['tip', 'NextBook records every transaction in its original currency and in the other currency at the exchange rate, so you do not have to calculate separately with a calculator.'],
                    ],
                ],
                'ps' => [
                    'title' => 'افغانۍ او ډالر په یوه دفتر کې: د تبادلې نرخ او د ارزي توپیر',
                    'excerpt' => 'کله چې په ډالر پېرئ او په افغانۍ پلورئ، د تبادلې نرخ د دوو معاملو ترمنځ بدلېږي. دا بدلون یا ګټه ده یا تاوان.',
                    'body' => [
                        ['p', 'په افغانستان کې ډېر سوداګر په دوو اسعارو کار کوي. که د تبادلې نرخ سم ثبت نه کړئ، ښايي بې له دې چې پوه شئ پیسې له لاسه ورکړئ.'],
                        ['h2', 'یوه بېلګه'],
                        ['p', 'نن توکي په ۱٬۰۰۰ ډالرو اخلئ، کله چې هر ډالر ۶۸ افغانۍ دی: ۶۸٬۰۰۰ افغانۍ. دوه اونۍ وروسته چې ورکړه کوئ، نرخ ۷۰ شوی او باید ۷۰٬۰۰۰ افغانۍ ورکړئ. ۲٬۰۰۰ افغانۍ تاوان د نرخ له بدلون، نه له توکو. (شمېرې فرضي دي.)'],
                        ['h2', 'څه باید ثبت کړئ'],
                        ['ul', [
                            'د معاملې مبلغ په اصلي اسعارو (ډالر).',
                            'د معاملې د ورځې د تبادلې نرخ.',
                            'هغه معادل په افغانیو چې ستاسو دفتر ورسره کار کوي.',
                        ]],
                        ['h2', 'د نرخ توپیر'],
                        ['p', 'هر ځل چې په بهرني اسعارو طلب یا پور په بل نرخ تسویه شي، توپیر د ارزي ګټې یا تاوان په توګه راځي. دا د پلور له ګټې جلا وګورئ چې وپوهېږئ توکي ګټور و که یوازې نرخ.'],
                        ['tip', 'نکست‌بوک د هرې معاملې مبلغ هم په اصلي اسعارو او هم په بل اسعارو د تبادلې نرخ سره ثبتوي، نو اړتیا نشته چې په ماشین‌حساب جلا حساب وکړئ.'],
                    ],
                ],
            ],
            [
                'slug' => 'bank-and-cash-reconciliation',
                'icon' => 'landmark',
                'days_ago' => 36,
                'fa' => [
                    'title' => 'تطبیق بانک و صندوق: چطور مطمئن شویم پول در دفتر همان پول واقعی است',
                    'excerpt' => 'مانده دفتر شما باید با مانده صورت‌حساب بانک و پول شمرده‌شده در صندوق برابر باشد. اگر نیست، دلیلش را باید پیدا کنید.',
                    'body' => [
                        ['p', 'تطبیق یعنی مقایسه دو منبع مستقل برای یک چیز. دفتر شما می‌گوید در بانک ۵۰۰٬۰۰۰ افغانی است؛ صورت‌حساب بانک هم باید همین را بگوید.'],
                        ['h2', 'چرا فرق می‌افتد؟'],
                        ['ul', [
                            'چکی که نوشته‌اید ولی هنوز از بانک رد نشده.',
                            'پولی که بانک کسر کرده (کارمزد) و شما ثبت نکرده‌اید.',
                            'واریزی که بانک ثبت کرده و در دفتر شما نیامده.',
                            'اشتباه در نوشتن مبلغ.',
                        ]],
                        ['h2', 'قدم‌ها'],
                        ['ol', [
                            'مانده صورت‌حساب بانک در پایان ماه را بگیرید.',
                            'هر ردیف دفتر شما را با یک ردیف صورت‌حساب بسنجید.',
                            'ردیف‌هایی که فقط در یک طرف‌اند را یادداشت کنید و دلیلشان را بیابید.',
                            'ثبت‌های فراموش‌شده، مثل کارمزد بانک، را در دفتر اضافه کنید.',
                        ]],
                        ['h2', 'برای صندوق هم همین'],
                        ['p', 'آخر هر روز پول صندوق را بشمارید و با مانده دفتر مقایسه کنید. فرق کوچک امروز را همان روز پیدا کنید، نه آخر ماه.'],
                        ['tip', 'در نکست‌بوک هر حساب بانکی و هر صندوق جدا ثبت می‌شود و هر رسید و پرداخت روی همان حساب می‌نشیند. مقایسه با صورت‌حساب بانک را ماهانه انجام دهید.'],
                    ],
                ],
                'en' => [
                    'title' => 'Bank and cash reconciliation: making sure the money in the books is the money you have',
                    'excerpt' => 'Your books\' balance must match the bank statement and the cash counted in the till. If it does not, you must find out why.',
                    'body' => [
                        ['p', 'Reconciling means comparing two independent sources for the same thing. Your books say there are 500,000 afghani in the bank; the bank statement should say the same.'],
                        ['h2', 'Why differences appear'],
                        ['ul', [
                            'A cheque you wrote that has not cleared yet.',
                            'Money the bank took (fees) that you did not record.',
                            'A deposit the bank recorded that is not in your books.',
                            'A mistake in writing an amount.',
                        ]],
                        ['h2', 'The steps'],
                        ['ol', [
                            'Get the bank statement balance at month end.',
                            'Match each line of your books against a line on the statement.',
                            'Note the lines that appear on only one side and find out why.',
                            'Add forgotten entries, such as bank fees, to your books.',
                        ]],
                        ['h2', 'The same goes for cash'],
                        ['p', 'At the end of each day count the till and compare it with the books. Find today\'s small gap today, not at month end.'],
                        ['tip', 'In NextBook each bank account and each cash box is recorded separately and every receipt and payment lands on its own account. Compare with the bank statement monthly.'],
                    ],
                ],
                'ps' => [
                    'title' => 'د بانک او صندوق تطبیق: څنګه ډاډ ترلاسه کړو چې په دفتر کې پیسې همغه ریښتینې پیسې دي',
                    'excerpt' => 'ستاسو د دفتر مانده باید د بانک له صورت‌حساب او په صندوق کې له شمېرل شوو پیسو سره برابره وي. که نه وي، دلیل یې باید ومومئ.',
                    'body' => [
                        ['p', 'تطبیق یعنې د یوه شي لپاره دوه خپلواک سرچینې پرتله کول. ستاسو دفتر وايي په بانک کې ۵۰۰٬۰۰۰ افغانۍ دي؛ د بانک صورت‌حساب هم باید همدا ووایي.'],
                        ['h2', 'توپیر ولې پیدا کېږي؟'],
                        ['ul', [
                            'هغه چېک چې مو لیکلی خو لا له بانکه نه دی تېر شوی.',
                            'هغه پیسې چې بانک کمې کړې (کارمزد) او تاسو نه دي ثبت کړي.',
                            'هغه جمع چې بانک ثبت کړې خو ستاسو په دفتر کې نه ده راغلې.',
                            'د مبلغ په لیکلو کې تېروتنه.',
                        ]],
                        ['h2', 'ګامونه'],
                        ['ol', [
                            'د میاشتې په پای کې د بانک د صورت‌حساب مانده واخلئ.',
                            'ستاسو د دفتر هر کتار د صورت‌حساب له یوه کتار سره وسنجوئ.',
                            'هغه کتارونه ولیکئ چې یوازې په یوه اړخ کې دي او دلیل یې ومومئ.',
                            'هېر شوي ثبتونه، لکه د بانک کارمزد، په دفتر کې زیات کړئ.',
                        ]],
                        ['h2', 'د صندوق لپاره هم همدا'],
                        ['p', 'د هرې ورځې په پای کې د صندوق پیسې وشمېرئ او له دفتر سره یې پرتله کړئ. د نن وړوکی توپیر په همدې ورځ ومومئ، نه د میاشتې په پای کې.'],
                        ['tip', 'په نکست‌بوک کې هر بانکي حساب او هر صندوق جلا ثبتېږي او هر رسید او ورکړه په خپل حساب کې ځای نیسي. له بانک صورت‌حساب سره پرتله هره میاشت وکړئ.'],
                    ],
                ],
            ],
            [
                'slug' => 'classify-expenses-and-owner-drawings',
                'icon' => 'banknote',
                'days_ago' => 39,
                'fa' => [
                    'title' => 'مصارف شرکت و برداشت مالک: چرا نباید با هم مخلوط شوند',
                    'excerpt' => 'پولی که صاحب کار برای خانه برمی‌دارد مصرف شرکت نیست. اگر مخلوط شود، سود واقعی را نمی‌دانید.',
                    'body' => [
                        ['p', 'در تجارت‌های کوچک، صندوق دکان و جیب صاحب کار اغلب یکی است. ولی در دفتر باید دو چیز جدا باشد: مصرف شرکت و برداشت صاحب.'],
                        ['h2', 'فرق چیست؟'],
                        ['ul', [
                            '<strong>مصرف شرکت:</strong> کرایه دکان، برق، معاش، حمل، ثبت جنس. این‌ها از سود کم می‌شوند.',
                            '<strong>برداشت صاحب:</strong> پولی که صاحب برای خود یا خانه برمی‌دارد. سود نیست و مصرف شرکت هم نیست؛ از سرمایه کم می‌شود.',
                        ]],
                        ['h2', 'اگر مخلوط شود چه می‌شود؟'],
                        ['p', 'اگر برداشت را مصرف بنویسید، سود شما کمتر از واقعیت نشان داده می‌شود. اگر مصرف را برداشت بنویسید، سود بیشتر از واقعیت می‌شود. در هر دو حالت تصمیم‌هایتان بر پایه عدد غلط است.'],
                        ['h2', 'دسته‌بندی مصارف'],
                        ['p', 'مصارف را به چند دسته روشن تقسیم کنید: کرایه، برق و آب، معاش، حمل، تبلیغات، بانک. بعد از چند ماه می‌بینید پول بیشتر کجا می‌رود.'],
                        ['tip', 'نکست‌بوک مصارف را در دسته‌ها نگه می‌دارد و برداشت مالکان را جداگانه ثبت می‌کند، تا سود شرکت از جیب صاحب جدا بماند.'],
                    ],
                ],
                'en' => [
                    'title' => 'Company expenses and owner drawings: why they should never be mixed',
                    'excerpt' => 'Money the owner takes for home is not a company expense. If the two are mixed, you will not know your real profit.',
                    'body' => [
                        ['p', 'In small businesses the shop till and the owner\'s pocket are often the same. But in the books two things must stay apart: company expenses and the owner\'s drawings.'],
                        ['h2', 'What is the difference?'],
                        ['ul', [
                            '<strong>Company expense:</strong> shop rent, power, wages, transport. These reduce profit.',
                            '<strong>Owner drawing:</strong> money the owner takes for themselves or the household. It is neither profit nor a company expense; it reduces equity.',
                        ]],
                        ['h2', 'What happens if they are mixed?'],
                        ['p', 'If you record a drawing as an expense, your profit looks lower than it is. If you record an expense as a drawing, profit looks higher. Either way your decisions rest on a wrong number.'],
                        ['h2', 'Categorising expenses'],
                        ['p', 'Divide expenses into a few clear categories: rent, power and water, wages, transport, advertising, bank. After a few months you see where most of the money goes.'],
                        ['tip', 'NextBook keeps expenses in categories and records owner drawings separately, so the company\'s profit stays apart from the owner\'s pocket.'],
                    ],
                ],
                'ps' => [
                    'title' => 'د شرکت لګښتونه او د مالک برداشت: ولې باید سره ګډ نه شي',
                    'excerpt' => 'هغه پیسې چې مالک د کور لپاره اخلي د شرکت لګښت نه دی. که ګډې شي، خپله ریښتینې ګټه نه پېژنئ.',
                    'body' => [
                        ['p', 'په وړو سوداګریو کې د دوکان صندوق او د مالک جیب ډېر وخت یو وي. خو په دفتر کې باید دوه شیان جلا وي: د شرکت لګښتونه او د مالک برداشت.'],
                        ['h2', 'توپیر څه دی؟'],
                        ['ul', [
                            '<strong>د شرکت لګښت:</strong> د دوکان کرایه، برېښنا، معاش، لېږد. دا له ګټې کمېږي.',
                            '<strong>د مالک برداشت:</strong> هغه پیسې چې مالک د ځان یا کور لپاره اخلي. نه ګټه ده نه د شرکت لګښت؛ له پانګې کمېږي.',
                        ]],
                        ['h2', 'که ګډ شي څه کېږي؟'],
                        ['p', 'که برداشت د لګښت په توګه ولیکئ، ستاسو ګټه له واقعیته لږ ښکاري. که لګښت د برداشت په توګه ولیکئ، ګټه له واقعیته ډېره ښکاري. په دواړو حالتونو کې ستاسو پرېکړې په غلطې شمېرې ولاړې دي.'],
                        ['h2', 'د لګښتونو ډلبندي'],
                        ['p', 'لګښتونه په څو څرګندو ډلو وویشئ: کرایه، برېښنا او اوبه، معاش، لېږد، اعلانات، بانک. څو میاشتې وروسته ګورئ چې ډېرې پیسې چېرته ځي.'],
                        ['tip', 'نکست‌بوک لګښتونه په ډلو کې ساتي او د مالکانو برداشت جلا ثبتوي، چې د شرکت ګټه د مالک له جیبه جلا پاتې شي.'],
                    ],
                ],
            ],
            [
                'slug' => 'payroll-basics-for-a-small-business',
                'icon' => 'users',
                'days_ago' => 42,
                'fa' => [
                    'title' => 'معاش کارمندان در یک تجارت کوچک: از قرارداد تا پرداخت',
                    'excerpt' => 'معاش فقط یک عدد نیست: قرارداد، حاضری، رخصتی و پرداخت باید به هم وصل باشند، وگرنه هر ماه جنجال است.',
                    'body' => [
                        ['p', 'اختلاف سر معاش معمولاً از بی‌حسابی می‌آید، نه از بدخواهی. وقتی هر چیز نوشته و روشن باشد، پرداخت معاش کاری ساده است.'],
                        ['h2', 'چهار چیز که باید روشن باشد'],
                        ['ul', [
                            '<strong>قرارداد:</strong> معاش اصلی، تاریخ شروع و شرایط کار.',
                            '<strong>حاضری:</strong> چند روز واقعاً کار کرده است.',
                            '<strong>رخصتی:</strong> کدام روزها با رخصتی گذشته و از کدام نوع.',
                            '<strong>پرداخت:</strong> چه مبلغی، کی و از کدام حساب داده شد.',
                        ]],
                        ['h2', 'یک ترتیب ثابت'],
                        ['ol', [
                            'آخر ماه حاضری و رخصتی را بسازید.',
                            'معاش را بر پایه آن حساب کنید و کسورات و پیش‌پرداخت‌ها را بیاورید.',
                            'فهرست را پیش از پرداخت بخوانید.',
                            'پرداخت را از صندوق یا بانک ثبت کنید و برگه معاش را نگه دارید.',
                        ]],
                        ['tip', 'نکست‌بوک بخش کارمندان و قراردادها، حاضری، رخصتی و معاشات دارد که به هم وصل‌اند. پس معاش بر پایه همان حاضری و رخصتی ثبت‌شده حساب می‌شود، نه حافظه.'],
                    ],
                ],
                'en' => [
                    'title' => 'Paying staff in a small business: from contract to payment',
                    'excerpt' => 'Wages are not just a number: contract, attendance, leave and payment must connect, or every month is an argument.',
                    'body' => [
                        ['p', 'Disputes about wages usually come from missing records, not from ill will. When everything is written down and clear, paying wages is simple.'],
                        ['h2', 'Four things that must be clear'],
                        ['ul', [
                            '<strong>The contract:</strong> base wage, start date and working terms.',
                            '<strong>Attendance:</strong> how many days the person really worked.',
                            '<strong>Leave:</strong> which days were leave and of what kind.',
                            '<strong>Payment:</strong> how much, when and from which account it was paid.',
                        ]],
                        ['h2', 'A fixed order'],
                        ['ol', [
                            'At month end, produce attendance and leave.',
                            'Calculate wages from them and include deductions and advances.',
                            'Read the list before paying.',
                            'Record the payment from cash or bank and keep the payslip.',
                        ]],
                        ['tip', 'NextBook has employees and contracts, attendance, leave and payroll, all connected. So wages are calculated from recorded attendance and leave, not from memory.'],
                    ],
                ],
                'ps' => [
                    'title' => 'په وړو سوداګریو کې د کارکوونکو معاش: له قرارداده تر ورکړې',
                    'excerpt' => 'معاش یوازې یوه شمېره نه ده: قرارداد، حاضري، رخصتي او ورکړه باید سره وتړل شي، که نه هره میاشت جنجال دی.',
                    'body' => [
                        ['p', 'د معاش اختلافونه معمولاً له بې‌حسابۍ راځي، نه له بدخواهۍ. کله چې هر څه لیکل شوي او څرګند وي، د معاش ورکړه ساده کار دی.'],
                        ['h2', 'څلور شیان چې باید څرګند وي'],
                        ['ul', [
                            '<strong>قرارداد:</strong> اصلي معاش، د پیل نېټه او د کار شرایط.',
                            '<strong>حاضري:</strong> څو ورځې یې واقعاً کار کړی.',
                            '<strong>رخصتي:</strong> کومې ورځې په رخصتۍ تېرې شوې او کوم ډول.',
                            '<strong>ورکړه:</strong> کومه پیسه، کله او له کوم حسابه ورکړل شوه.',
                        ]],
                        ['h2', 'یو ثابت ترتیب'],
                        ['ol', [
                            'د میاشتې په پای کې حاضري او رخصتي جوړه کړئ.',
                            'معاش پر همدې حساب کړئ او کسورات او پېشکي شاملې کړئ.',
                            'مخکې له ورکړې لړلیک ولولئ.',
                            'ورکړه له صندوق یا بانکه ثبت کړئ او د معاش پاڼه وساتئ.',
                        ]],
                        ['tip', 'نکست‌بوک د کارکوونکو او قراردادونو، حاضرۍ، رخصتۍ او معاشاتو برخې لري چې سره تړلي دي. نو معاش د ثبت شوې حاضرۍ او رخصتۍ پر بنسټ حسابېږي، نه د حافظې.'],
                    ],
                ],
            ],
            [
                'slug' => 'a-simple-budget-for-a-trader',
                'icon' => 'target',
                'days_ago' => 45,
                'fa' => [
                    'title' => 'بودجه ساده برای یک تاجر: سه عدد که هر ماه باید بدانید',
                    'excerpt' => 'بودجه لازم نیست پیچیده باشد. یک تخمین ساده از فروش، مصارف ثابت و حداقل نقد، برای تصمیم‌گیری کافی است.',
                    'body' => [
                        ['p', 'بودجه یعنی پیش از ماه بدانید چه انتظاری دارید، تا آخر ماه ببینید واقعیت چقدر فرق کرده. نیازی به فایل‌های بزرگ ندارد.'],
                        ['h2', 'سه عدد'],
                        ['ol', [
                            '<strong>فروش مورد انتظار:</strong> بر پایه سه ماه گذشته و فصل فعلی.',
                            '<strong>مصارف ثابت:</strong> چیزهایی که فروش کم هم بشود باید داد: کرایه، معاش، برق.',
                            '<strong>حداقل نقد:</strong> چقدر پول باید همیشه در صندوق و بانک باشد تا کار بایستد نه.',
                        ]],
                        ['h2', 'آخر ماه مقایسه کنید'],
                        ['p', 'فروش واقعی را با تخمین بسنجید. اگر فرق زیاد است، علت را بپرسید: قیمت، فصل، رقیب یا کمبود جنس. این پرسش‌ها از خود بودجه ارزشمندترند.'],
                        ['formula', 'مصارف ثابت ماهانه ÷ مارجین سود = فروش لازم برای سر به سر شدن'],
                        ['tip', 'فروش و مصارف واقعی هر ماه را از گزارش‌های نکست‌بوک بگیرید و کنار تخمین بگذارید. بعد از سه ماه، تخمین‌هایتان خیلی دقیق‌تر می‌شود.'],
                    ],
                ],
                'en' => [
                    'title' => 'A simple budget for a trader: three numbers to know every month',
                    'excerpt' => 'A budget does not have to be complicated. A simple estimate of sales, fixed costs and minimum cash is enough to decide with.',
                    'body' => [
                        ['p', 'A budget means knowing before the month what you expect, so that at month end you can see how far reality differed. You do not need big files.'],
                        ['h2', 'Three numbers'],
                        ['ol', [
                            '<strong>Expected sales:</strong> based on the last three months and the current season.',
                            '<strong>Fixed costs:</strong> what must be paid even if sales fall: rent, wages, power.',
                            '<strong>Minimum cash:</strong> how much must always be in the till and the bank so that work never stops.',
                        ]],
                        ['h2', 'Compare at month end'],
                        ['p', 'Compare real sales with the estimate. If the gap is large, ask why: price, season, a competitor or a stock shortage. These questions are worth more than the budget itself.'],
                        ['formula', 'Monthly fixed costs ÷ profit margin = sales needed to break even'],
                        ['tip', 'Take each month\'s real sales and expenses from NextBook\'s reports and put them beside the estimate. After three months your estimates are far more accurate.'],
                    ],
                ],
                'ps' => [
                    'title' => 'د سوداګر لپاره ساده بودجه: درې شمېرې چې هره میاشت باید وپېژنئ',
                    'excerpt' => 'بودجه باید پېچلې نه وي. د پلور، ثابتو لګښتونو او لږ تر لږه نغدو ساده اټکل د پرېکړې لپاره بس دی.',
                    'body' => [
                        ['p', 'بودجه یعنې مخکې له میاشتې پوه شئ چې څه تمه لرئ، څو د میاشتې په پای کې وګورئ واقعیت څومره توپیر کړی. لوی فایلونو ته اړتیا نشته.'],
                        ['h2', 'درې شمېرې'],
                        ['ol', [
                            '<strong>تمه شوی پلور:</strong> د تېرو درېیو میاشتو او اوسني فصل پر بنسټ.',
                            '<strong>ثابت لګښتونه:</strong> هغه څه چې پلور که کم شي هم باید ورکړل شي: کرایه، معاش، برېښنا.',
                            '<strong>لږ تر لږه نغدې:</strong> څومره پیسې باید تل په صندوق او بانک کې وي چې کار ونه دریږي.',
                        ]],
                        ['h2', 'د میاشتې په پای کې پرتله کړئ'],
                        ['p', 'ریښتینی پلور له اټکل سره وسنجوئ. که توپیر ډېر وي، دلیل وپوښتئ: بیه، فصل، سیال یا د توکو کمښت. دا پوښتنې له بودجې پخپله ډېرې ارزښتناکې دي.'],
                        ['formula', 'میاشتنی ثابت لګښتونه ÷ د ګټې مارجین = د برابرېدو لپاره اړین پلور'],
                        ['tip', 'د هرې میاشتې ریښتینی پلور او لګښتونه د نکست‌بوک له راپورونو واخلئ او د اټکل ترڅنګ یې کېږدئ. وروسته له درېیو میاشتو ستاسو اټکلونه ډېر دقیق کېږي.'],
                    ],
                ],
            ],
            [
                'slug' => 'break-even-point',
                'icon' => 'trending-up',
                'days_ago' => 48,
                'fa' => [
                    'title' => 'نقطه سر به سر: چقدر باید بفروشید تا ضرر نکنید',
                    'excerpt' => 'مصارف ثابت هر ماه می‌آیند چه بفروشید چه نه. نقطه سر به سر می‌گوید حداقل فروش برای زیان نکردن چقدر است.',
                    'body' => [
                        ['p', 'هر ماه کرایه و معاش را باید داد. سؤال این است: چقدر باید بفروشیم تا بعد از پرداخت مصارف ثابت، دست‌کم ضرر نکرده باشیم؟'],
                        ['h2', 'فرمول'],
                        ['formula', 'فروش سر به سر = مصارف ثابت ÷ مارجین سود'],
                        ['h2', 'یک مثال'],
                        ['p', 'مصارف ثابت ماهانه ۶۰٬۰۰۰ افغانی و مارجین سود ۲۰٪. فروش سر به سر: ۶۰٬۰۰۰ ÷ ۰٫۲۰ = ۳۰۰٬۰۰۰ افغانی در ماه. زیر این عدد زیان می‌کنید و بالای آن سود. (اعداد فرضی‌اند.)'],
                        ['h2', 'این عدد چه کمکی می‌کند؟'],
                        ['ul', [
                            'می‌فهمید در یک ماه کم‌فروشی چقدر خطر دارید.',
                            'می‌بینید اگر کرایه یا معاش بالا برود، فروش لازم چقدر بالا می‌رود.',
                            'می‌بینید بالا بردن مارجین (قیمت‌گذاری بهتر) چقدر فروش لازم را پایین می‌آورد.',
                        ]],
                        ['tip', 'مارجین سود واقعی به قیمت تمام‌شده درست وابسته است. هزینه‌های حمل و گمرک را به قیمت اضافه کنید تا این عدد فریبتان ندهد.'],
                    ],
                ],
                'en' => [
                    'title' => 'The break-even point: how much you must sell to avoid a loss',
                    'excerpt' => 'Fixed costs arrive every month whether you sell or not. The break-even point tells you the least you must sell to avoid a loss.',
                    'body' => [
                        ['p', 'Every month the rent and wages must be paid. The question is: how much must we sell so that, after paying fixed costs, we at least do not lose?'],
                        ['h2', 'The formula'],
                        ['formula', 'Break-even sales = fixed costs ÷ profit margin'],
                        ['h2', 'An example'],
                        ['p', 'Monthly fixed costs are 60,000 afghani and the margin is 20%. Break-even sales: 60,000 ÷ 0.20 = 300,000 afghani a month. Below that you lose and above it you profit. (The numbers are made up.)'],
                        ['h2', 'How does this number help?'],
                        ['ul', [
                            'You learn how risky a slow month is.',
                            'You see how much higher the required sales go if rent or wages rise.',
                            'You see how much a higher margin (better pricing) lowers the required sales.',
                        ]],
                        ['tip', 'The real profit margin depends on a correct cost price. Add freight and customs to the cost so that this number does not mislead you.'],
                    ],
                ],
                'ps' => [
                    'title' => 'د برابرېدو ټکی: څومره باید وپلورئ چې تاوان ونه کړئ',
                    'excerpt' => 'ثابت لګښتونه هره میاشت راځي که پلور وکړئ که نه. د برابرېدو ټکی وايي چې د تاوان نه کولو لپاره لږ تر لږه څومره پلور په کار دی.',
                    'body' => [
                        ['p', 'هره میاشت کرایه او معاش باید ورکړل شي. پوښتنه دا ده: څومره باید وپلورو چې د ثابتو لګښتونو له ورکړې وروسته لږ تر لږه تاوان ونه کړو؟'],
                        ['h2', 'فورمول'],
                        ['formula', 'د برابرېدو پلور = ثابت لګښتونه ÷ د ګټې مارجین'],
                        ['h2', 'یوه بېلګه'],
                        ['p', 'میاشتني ثابت لګښتونه ۶۰٬۰۰۰ افغانۍ او د ګټې مارجین ۲۰٪. د برابرېدو پلور: ۶۰٬۰۰۰ ÷ ۰٫۲۰ = ۳۰۰٬۰۰۰ افغانۍ په میاشت کې. له دې لاندې تاوان کوئ او له دې پورته ګټه. (شمېرې فرضي دي.)'],
                        ['h2', 'دا شمېره څه مرسته کوي؟'],
                        ['ul', [
                            'پوهېږئ چې په یوه سست‌پلور میاشت کې څومره خطر لرئ.',
                            'ګورئ چې که کرایه یا معاش لوړ شي، اړین پلور څومره لوړېږي.',
                            'ګورئ چې د مارجین لوړول (ښه بیه‌ټاکنه) اړین پلور څومره ټیټوي.',
                        ]],
                        ['tip', 'ریښتینی د ګټې مارجین پر سمې تمام‌شوې بیې ولاړ دی. کرایه او ګمرک په بیه کې زیات کړئ چې دا شمېره مو ونه غولوي.'],
                    ],
                ],
            ],
            [
                'slug' => 'fixed-assets-and-depreciation',
                'icon' => 'building-2',
                'days_ago' => 51,
                'fa' => [
                    'title' => 'دارایی ثابت و استهلاک: چرا موتر و ماشین‌آلات یک‌جا مصرف نمی‌شوند',
                    'excerpt' => 'چیزی که سال‌ها کار می‌کند، مصرفش را باید در همان سال‌ها پخش کرد. این کار استهلاک است.',
                    'body' => [
                        ['p', 'فرض کنید یک موتر باربری را ۸۰۰٬۰۰۰ افغانی می‌خرید. اگر همه را در همان ماه مصرف بنویسید، آن ماه ضرر بزرگ می‌بینید و سال‌های بعد سود غیرواقعی. ولی موتر پنج سال کار می‌کند.'],
                        ['h2', 'استهلاک چیست؟'],
                        ['p', 'استهلاک یعنی هزینه خرید یک دارایی را در طول عمر مفیدش بین سال‌ها تقسیم کنید. هر سال بخشی از آن به‌عنوان مصرف می‌آید.'],
                        ['formula', 'استهلاک سالانه = (قیمت خرید − ارزش باقی‌مانده) ÷ سال‌های عمر'],
                        ['h2', 'مثال'],
                        ['p', 'قیمت موتر ۸۰۰٬۰۰۰، ارزش بعد از ۵ سال ۱۵۰٬۰۰۰: استهلاک سالانه (۸۰۰٬۰۰۰ − ۱۵۰٬۰۰۰) ÷ ۵ = ۱۳۰٬۰۰۰ افغانی. (اعداد فرضی‌اند.)'],
                        ['h2', 'چه چیزهایی دارایی ثابت‌اند؟'],
                        ['ul', ['موتر و ماشین‌آلات', 'تجهیزات دفتر و دکان', 'ساختمان و بهسازی‌های ماندگار']],
                        ['warn', 'بخش دارایی ثابت در نکست‌بوک هنوز در دست ساخت است. تا آن زمان، این محاسبه را بیرون سیستم نگه دارید و استهلاک را با قید روزنامچه ثبت کنید.'],
                    ],
                ],
                'en' => [
                    'title' => 'Fixed assets and depreciation: why a truck is not spent all at once',
                    'excerpt' => 'Something that works for years should have its cost spread over those years. That is depreciation.',
                    'body' => [
                        ['p', 'Say you buy a delivery truck for 800,000 afghani. If you record all of it as an expense in that month, you see a huge loss that month and unrealistic profit in later years. But the truck works for five years.'],
                        ['h2', 'What is depreciation?'],
                        ['p', 'Depreciation means dividing the cost of an asset over its useful life. Each year a share of it appears as an expense.'],
                        ['formula', 'Annual depreciation = (purchase price − residual value) ÷ years of life'],
                        ['h2', 'An example'],
                        ['p', 'Truck price 800,000, value after 5 years 150,000: annual depreciation (800,000 − 150,000) ÷ 5 = 130,000 afghani. (The numbers are made up.)'],
                        ['h2', 'What counts as a fixed asset?'],
                        ['ul', ['Vehicles and machinery', 'Office and shop equipment', 'Buildings and lasting improvements']],
                        ['warn', 'The fixed assets module in NextBook is still being built. Until then, keep this calculation outside the system and record depreciation with a journal entry.'],
                    ],
                ],
                'ps' => [
                    'title' => 'ثابتې شتمنۍ او استهلاک: ولې موټر او ماشین‌آلات یو ځل نه مصرفېږي',
                    'excerpt' => 'هغه څه چې کلونه کار کوي، لګښت یې باید په همدې کلونو کې وویشل شي. دا استهلاک دی.',
                    'body' => [
                        ['p', 'فرض کړئ یو باربري موټر په ۸۰۰٬۰۰۰ افغانیو اخلئ. که ټول په همدې میاشت کې د لګښت په توګه ولیکئ، هغه میاشت لوی تاوان وینئ او په راتلونکو کلونو کې غیر واقعي ګټه. خو موټر پنځه کاله کار کوي.'],
                        ['h2', 'استهلاک څه دی؟'],
                        ['p', 'استهلاک یعنې د یوې شتمنۍ د پېر لګښت د هغې په ګټور ژوند کې پر کلونو وویشئ. هر کال یوه برخه یې د لګښت په توګه راځي.'],
                        ['formula', 'کلنی استهلاک = (د پېر بیه − پاتې ارزښت) ÷ د ژوند کلونه'],
                        ['h2', 'بېلګه'],
                        ['p', 'د موټر بیه ۸۰۰٬۰۰۰، له ۵ کلونو وروسته ارزښت ۱۵۰٬۰۰۰: کلنی استهلاک (۸۰۰٬۰۰۰ − ۱۵۰٬۰۰۰) ÷ ۵ = ۱۳۰٬۰۰۰ افغانۍ. (شمېرې فرضي دي.)'],
                        ['h2', 'کوم شیان ثابتې شتمنۍ دي؟'],
                        ['ul', ['موټرونه او ماشین‌آلات', 'د دفتر او دوکان تجهیزات', 'ودانۍ او دوامداره ښه‌والي']],
                        ['warn', 'په نکست‌بوک کې د ثابتو شتمنیو برخه لا د جوړېدو په حال کې ده. تر هغه وخته دا محاسبه له سیستمه بهر وساتئ او استهلاک د ژورنال له لارې ثبت کړئ.'],
                    ],
                ],
            ],
            [
                'slug' => 'internal-controls-for-a-small-business',
                'icon' => 'shield-check',
                'days_ago' => 54,
                'fa' => [
                    'title' => 'کنترول داخلی در تجارت کوچک: پنج قاعده که پول شما را حفظ می‌کند',
                    'excerpt' => 'کنترول داخلی یعنی طوری کار را تقسیم کنید که یک نفر به‌تنهایی نتواند اشتباه یا سوء‌استفاده را پنهان کند.',
                    'body' => [
                        ['p', 'بیشتر ضررهای پنهان از بدخواهی نیست؛ از نبودن قاعده است. چند قاعده ساده خطر را خیلی کم می‌کند، حتی در یک دکان کوچک.'],
                        ['h2', 'پنج قاعده'],
                        ['ol', [
                            '<strong>تفکیک وظایف:</strong> کسی که جنس را تحویل می‌گیرد، همان کسی نباشد که پول را حساب می‌کند.',
                            '<strong>دسترسی به اندازه نیاز:</strong> هر کس فقط آنچه برای کارش لازم است ببیند و تغییر دهد.',
                            '<strong>هر چیز ثبت شود:</strong> فروش، تخفیف، برگشت و تعدیل، همه با نام کاربر.',
                            '<strong>تأیید مبلغ‌های بزرگ:</strong> برای تخفیف یا تعدیل بزرگ، تأیید دوم لازم باشد.',
                            '<strong>بررسی مستقل:</strong> هر ماه یک نفر دیگر نتایج را مرور کند.',
                        ]],
                        ['h2', 'اشتباه و سوء‌استفاده'],
                        ['p', 'این قاعده‌ها فقط برای دزدی نیستند. بیشتر آن‌ها اشتباه‌های ساده را زود پیدا می‌کنند، پیش از آنکه بزرگ شوند.'],
                        ['tip', 'نکست‌بوک نقش‌ها و دسترسی‌ها دارد، لاگ فعالیت نشان می‌دهد چه کسی چه چیزی را کی تغییر داد، و رکوردهای حذف‌شده قابل برگرداندن‌اند. این سه، پایه کنترول داخلی شما هستند.'],
                    ],
                ],
                'en' => [
                    'title' => 'Internal controls for a small business: five rules that protect your money',
                    'excerpt' => 'Internal control means dividing the work so that one person cannot hide a mistake or misuse on their own.',
                    'body' => [
                        ['p', 'Most hidden losses do not come from ill will; they come from having no rules. A few simple rules cut the risk a great deal, even in a small shop.'],
                        ['h2', 'Five rules'],
                        ['ol', [
                            '<strong>Separate duties:</strong> the person who receives the goods should not be the one who handles the money.',
                            '<strong>Access to match the need:</strong> everyone sees and changes only what their job needs.',
                            '<strong>Everything recorded:</strong> sales, discounts, returns and adjustments, all under a user\'s name.',
                            '<strong>Approve large amounts:</strong> a large discount or adjustment needs a second approval.',
                            '<strong>An independent check:</strong> each month someone else reviews the results.',
                        ]],
                        ['h2', 'Mistakes and misuse'],
                        ['p', 'These rules are not only about theft. Most of them catch simple mistakes early, before they grow.'],
                        ['tip', 'NextBook has roles and permissions, an activity log that shows who changed what and when, and deleted records can be restored. Those three are the base of your internal control.'],
                    ],
                ],
                'ps' => [
                    'title' => 'په وړې سوداګرۍ کې داخلي کنټرول: پنځه قاعدې چې ستاسو پیسې ساتي',
                    'excerpt' => 'داخلي کنټرول یعنې کار داسې وویشئ چې یو کس په یوازې ځان تېروتنه یا ناوړه ګټه پټه نه کړای شي.',
                    'body' => [
                        ['p', 'ډېر پټ تاوانونه له بدخواهۍ نه، د قاعدو له نشتوالي راځي. څو ساده قاعدې خطر ډېر کموي، حتی په یوه وړوکي دوکان کې.'],
                        ['h2', 'پنځه قاعدې'],
                        ['ol', [
                            '<strong>د دندو جلا والی:</strong> هغه څوک چې توکي ترلاسه کوي، هغه نه وي چې پیسې حسابوي.',
                            '<strong>د اړتیا په اندازه لاسرسی:</strong> هر څوک یوازې هغه وګوري او بدل کړي چې د کار لپاره یې اړین دي.',
                            '<strong>هر څه ثبت شي:</strong> پلورونه، تخفیفونه، بېرته‌ورکړې او تعدیلونه، ټول د کاروونکي په نوم.',
                            '<strong>د لویو مبلغونو تایید:</strong> د لوی تخفیف یا تعدیل لپاره دویم تایید په کار دی.',
                            '<strong>خپلواک کتنه:</strong> هره میاشت بل څوک پایلې بیاکتنه وکړي.',
                        ]],
                        ['h2', 'تېروتنه او ناوړه ګټه'],
                        ['p', 'دا قاعدې یوازې د غلا لپاره نه دي. ډېری یې ساده تېروتنې مخکې له دې چې لوی شي ژر مومي.'],
                        ['tip', 'نکست‌بوک رولونه او لاسرسي لري، د فعالیت لاګ ښيي چې چا څه کله بدل کړل، او ړنګ شوي ریکارډونه بېرته راګرځول کېدای شي. دا درې ستاسو د داخلي کنټرول بنسټ دي.'],
                    ],
                ],
            ],
            [
                'slug' => 'read-a-balance-sheet-in-five-minutes',
                'icon' => 'chart-pie',
                'days_ago' => 57,
                'fa' => [
                    'title' => 'ترازنامه را در پنج دقیقه بخوانید: چهار سؤال که باید از آن بپرسید',
                    'excerpt' => 'ترازنامه عکس وضع مالی شما در یک روز است. با چهار سؤال، بدون حسابدار بودن هم می‌توانید از آن چیز مهم بفهمید.',
                    'body' => [
                        ['p', 'ترازنامه سه بخش دارد: دارایی (آنچه دارید)، بدهی (آنچه باید بدهید) و سرمایه (سهم صاحبان). دارایی همیشه برابر بدهی به‌علاوه سرمایه است.'],
                        ['h2', 'چهار سؤال'],
                        ['ol', [
                            '<strong>آیا نقد کافی دارم؟</strong> نقد و بانک را با بدهی‌های نزدیک مقایسه کنید.',
                            '<strong>پولم در چه چیزی بند است؟</strong> اگر بیشتر در قرض‌ها و گدام است، نقدی‌تان تنگ می‌شود.',
                            '<strong>چقدر بدهکارم؟</strong> بدهی به تامین‌کننده‌ها و دیگران، و چقدرش به‌زودی سررسید است.',
                            '<strong>سرمایه‌ام بیشتر شده یا کمتر؟</strong> با پایان دوره پیش مقایسه کنید.',
                        ]],
                        ['h2', 'نشانه‌های هشدار'],
                        ['ul', [
                            'قرض مشتریان سال به سال بزرگ‌تر می‌شود.',
                            'بدهی‌های نزدیک از نقد بیشتر است.',
                            'موجودی گدام بالا می‌رود ولی فروش نه.',
                        ]],
                        ['tip', 'در نکست‌بوک ترازنامه و گزارش سود و زیان از همان دفتر دوطرفه ساخته می‌شوند، پس با معامله‌های شما می‌خوانند. هر ماه یک بار هر دو را کنار هم ببینید.'],
                    ],
                ],
                'en' => [
                    'title' => 'Read a balance sheet in five minutes: four questions to ask of it',
                    'excerpt' => 'A balance sheet is a photograph of your finances on one day. With four questions you can learn what matters, even if you are not an accountant.',
                    'body' => [
                        ['p', 'A balance sheet has three parts: assets (what you own), liabilities (what you owe) and equity (the owners\' share). Assets always equal liabilities plus equity.'],
                        ['h2', 'Four questions'],
                        ['ol', [
                            '<strong>Do I have enough cash?</strong> Compare cash and bank with the debts falling due soon.',
                            '<strong>What is my money tied up in?</strong> If it is mostly in credit and stock, cash gets tight.',
                            '<strong>How much do I owe?</strong> To suppliers and others, and how much falls due soon.',
                            '<strong>Has my equity grown or shrunk?</strong> Compare with the end of the last period.',
                        ]],
                        ['h2', 'Warning signs'],
                        ['ul', [
                            'Customer credit grows larger year after year.',
                            'Debts due soon exceed cash.',
                            'Stock keeps rising but sales do not.',
                        ]],
                        ['tip', 'In NextBook the balance sheet and the profit and loss report are built from the same double-entry ledger, so they agree with your transactions. Look at both side by side once a month.'],
                    ],
                ],
                'ps' => [
                    'title' => 'ترازنامه په پنځو دقیقو کې ولولئ: څلور پوښتنې چې باید ترې وکړئ',
                    'excerpt' => 'ترازنامه په یوه ورځ کې ستاسو د مالي حالت انځور دی. په څلورو پوښتنو سره، بې له محاسب کېدو هم کولای شئ مهم شی ترې وپوهېږئ.',
                    'body' => [
                        ['p', 'ترازنامه درې برخې لري: شتمني (هغه چې لرئ)، پور (هغه چې باید ورکړئ) او پانګه (د مالکانو ونډه). شتمني تل له پور او پانګې له مجموعې سره برابره وي.'],
                        ['h2', 'څلور پوښتنې'],
                        ['ol', [
                            '<strong>آیا کافي نغدې لرم؟</strong> نغدې او بانک له نږدې پورونو سره پرتله کړئ.',
                            '<strong>زما پیسې په څه کې بندې دي؟</strong> که ډېرې په قرضونو او ګدام کې وي، نغدې تنګېږي.',
                            '<strong>څومره پور لرم؟</strong> تامین‌کوونکو او نورو ته، او څومره یې ژر سررسېږي.',
                            '<strong>زما پانګه زیاته شوې که کمه؟</strong> د تېرې دورې له پای سره یې پرتله کړئ.',
                        ]],
                        ['h2', 'د خبرتیا نخښې'],
                        ['ul', [
                            'د پېرېدونکو قرض کال په کال لوی کېږي.',
                            'نږدې پورونه له نغدو ډېر دي.',
                            'د ګدام موجودي لوړېږي خو پلور نه.',
                        ]],
                        ['tip', 'په نکست‌بوک کې ترازنامه او د ګټې او تاوان راپور له همدې دوه‌اړخیزه دفتره جوړېږي، نو ستاسو له معاملو سره سمون لري. هره میاشت یو ځل دواړه یو ځای وګورئ.'],
                    ],
                ],
            ],
            [
                'slug' => 'from-the-notebook-to-nextbook',
                'icon' => 'book-open',
                'days_ago' => 1,
                'fa' => [
                    'title' => 'از دفترچه به نکست‌بوک: برنامه ده روزه برای انتقال بدون سردرگمی',
                    'excerpt' => 'انتقال از دفترچه یا اکسل به یک سیستم، کار یک شب نیست، ولی با ترتیب درست در ده روز تمام می‌شود.',
                    'body' => [
                        ['p', 'بیشتر تاجران از انتقال می‌ترسند، چون فکر می‌کنند باید همه چیز را یک‌جا عوض کنند. لازم نیست. ترتیب درست، ترس را کم می‌کند.'],
                        ['h2', 'برنامه ده روزه'],
                        ['ul', [
                            '<strong>روز ۱ و ۲:</strong> تاریخ شروع را انتخاب کنید و تنظیمات پایه شرکت را کنترول کنید: ارزها، گدام‌ها و کاربران.',
                            '<strong>روز ۳ تا ۵:</strong> اجناس، مشتریان و تامین‌کننده‌ها را از دفترچه یا فایل اکسل وارد کنید.',
                            '<strong>روز ۶ و ۷:</strong> بیلانس‌های افتتاحیه را وارد کنید: موجودی گدام، طلب‌ها، بدهی‌ها و نقد و بانک.',
                            '<strong>روز ۸:</strong> کنترول: مجموع‌ها را با دفتر خودتان بسنجید.',
                            '<strong>روز ۹ و ۱۰:</strong> چند معامله واقعی را ثبت کنید و کار روزانه را از سیستم شروع کنید.',
                        ]],
                        ['h2', 'چند نکته'],
                        ['ul', [
                            'تا روز شروع، دفترچه را ادامه دهید و از آن روز به بعد فقط در سیستم بنویسید. دو جا نوشتن ممنوع.',
                            'اعداد غلط را وارد نکنید؛ ده دقیقه برای پاک‌سازی صرفه می‌کند.',
                            'کارمندان را با نقش مناسب اضافه کنید و یک نفر را مسئول سیستم بسازید.',
                        ]],
                        ['tip', 'راهنمای هفته اول در مرکز راهنمای نکست‌بوک، همین مراحل را قدم‌به‌قدم و با مثال شرح می‌دهد.'],
                    ],
                ],
                'en' => [
                    'title' => 'From the notebook to NextBook: a ten-day plan for moving without confusion',
                    'excerpt' => 'Moving from a notebook or Excel to a system is not an overnight job, but with the right order it takes ten days.',
                    'body' => [
                        ['p', 'Most traders fear the move because they think they must change everything at once. They do not. The right order takes the fear away.'],
                        ['h2', 'The ten-day plan'],
                        ['ul', [
                            '<strong>Days 1 and 2:</strong> pick a start date and check the company basics: currencies, warehouses and users.',
                            '<strong>Days 3 to 5:</strong> enter your items, customers and suppliers from the notebook or an Excel file.',
                            '<strong>Days 6 and 7:</strong> enter opening balances: warehouse stock, receivables, payables and cash and bank.',
                            '<strong>Day 8:</strong> check: compare the totals with your own books.',
                            '<strong>Days 9 and 10:</strong> record a few real transactions and start the daily work from the system.',
                        ]],
                        ['h2', 'A few tips'],
                        ['ul', [
                            'Keep the notebook going until the start date, and from that day write only in the system. Writing in two places is out.',
                            'Do not enter wrong numbers; ten minutes of cleaning saves a lot.',
                            'Add staff with suitable roles and make one person responsible for the system.',
                        ]],
                        ['tip', 'The first-week guide in the NextBook help center explains these same steps one at a time, with examples.'],
                    ],
                ],
                'ps' => [
                    'title' => 'له دفترچې نکست‌بوک ته: د لس ورځو پلان د ګډوډۍ پرته لېږد لپاره',
                    'excerpt' => 'له دفترچې یا اکسل څخه سیستم ته لېږد د یوې شپې کار نه دی، خو په سم ترتیب سره په لسو ورځو کې بشپړېږي.',
                    'body' => [
                        ['p', 'ډېر سوداګر له لېږد څخه ویره لري، ځکه فکر کوي باید ټول څه یو ځل بدل کړي. اړتیا نشته. سم ترتیب ویره کموي.'],
                        ['h2', 'د لسو ورځو پلان'],
                        ['ul', [
                            '<strong>۱ او ۲ ورځ:</strong> د پیل نېټه وټاکئ او د شرکت بنسټیز تنظیمات وګورئ: اسعار، ګدامونه او کاروونکي.',
                            '<strong>۳ تر ۵ ورځ:</strong> توکي، پېرېدونکي او تامین‌کوونکي له دفترچې یا اکسل فایل څخه داخل کړئ.',
                            '<strong>۶ او ۷ ورځ:</strong> پیل‌مانده‌ګانې داخل کړئ: د ګدام موجودي، طلبونه، پورونه او نغدې او بانک.',
                            '<strong>۸ ورځ:</strong> کنټرول: مجموعې له خپل دفتر سره وسنجوئ.',
                            '<strong>۹ او ۱۰ ورځ:</strong> څو ریښتینې معاملې ثبت کړئ او ورځنی کار له سیستمه پیل کړئ.',
                        ]],
                        ['h2', 'څو ګټورې خبرې'],
                        ['ul', [
                            'تر پیل نېټې دفترچه دوام ورکړئ او له هغې ورځې وروسته یوازې په سیستم کې ولیکئ. په دوو ځایونو لیکل منع دي.',
                            'غلطې شمېرې مه داخلوئ؛ د پاکولو لس دقیقې ډېر څه سپموي.',
                            'کارکوونکي په مناسبو رولونو سره زیات کړئ او یو کس د سیستم مسئول کړئ.',
                        ]],
                        ['tip', 'د نکست‌بوک په مرکز راهنما کې د لومړۍ اونۍ لارښود همدا ګامونه یو په یو او په بېلګو سره تشریح کوي.'],
                    ],
                ],
            ],
            // ARTICLES-END
        ];
    }
}
