<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;

/**
 * The first articles. Each describes how the ERP really works (the user manual is the
 * source), so a reader who follows one finds the same thing on screen.
 */
class PostSeeder extends Seeder
{
    public function run(): void
    {
        $posts = [
            [
                'slug' => 'first-week-with-nextbook',
                'icon' => 'rocket',
                'days_ago' => 14,
                'fa' => [
                    'title' => 'اولین هفته با نکست‌بوک: از کجا شروع کنیم',
                    'excerpt' => 'ترتیبی که کار را آسان می‌کند: اساسات، فهرست‌ها، بیلانس‌های افتتاحیه و کنترول، قبل از اولین معامله واقعی.',
                    'content' => <<<'HTML'
<p>بیشتر مشکل‌های اول کار از ترتیب است، نه از سیستم. اگر مراحل را به همین ترتیب بروید، هر مرحله روی مرحله قبل می‌نشیند و بعداً چیزی را دوباره نمی‌سازید.</p>

<h2>اول، یک تاریخ شروع انتخاب کنید</h2>
<p>روزی مثل اول ماه یا اول سال را انتخاب کنید و به آن وفادار بمانید. هر بیلانس افتتاحیه باید همان چیزی باشد که در پایان روز قبل از آن تاریخ داشتید. از آن روز به بعد، معاملات واقعی را ثبت کنید.</p>

<h2>مرحله الف: اساسات</h2>
<p>شرکت، ارزها و گدام را کنترول کنید. سیستم یک چارت حساب، واحدهای اندازه‌گیری، دسته‌بندی‌ها، یک مشتری نقدی، یک دوره مالی و یک شعبه با گدام آماده کرده است؛ لازم نیست از صفر بسازید.</p>

<h2>مرحله ب: فهرست‌ها</h2>
<p>اجناس، مشتریان و تامین‌کننده‌ها را بسازید. برای چیزی که وجود ندارد نمی‌شود بیلانس ثبت کرد، برای همین فهرست‌ها پیش از بیلانس‌ها می‌آیند.</p>

<h2>مرحله ج: بیلانس‌های افتتاحیه</h2>
<p>موجودی گدام، طلب از مشتریان، بدهی به تامین‌کننده‌ها و مانده صندوق و بانک را در روز شروع وارد کنید. بدون این، گزارش‌های شما از صفر شروع می‌شوند.</p>

<h2>مرحله د: کنترول</h2>
<p>اعداد سیستم را با دفتر خودتان مقایسه کنید. اشتباه را همین حالا گرفتن آسان است، بعد از ماه‌ها سخت.</p>

<div class="callout callout-tip">بعد از کنترول، کارمندان را اضافه کنید و برای هر کدام نقش مناسب بدهید تا هر کس فقط آنچه لازم دارد ببیند.</div>

<h2>اولین روز واقعی</h2>
<p>حالا اولین فروش یا خریدتان را ثبت کنید و ببینید موجودی و حساب‌ها چطور خودکار تغییر می‌کنند. راهنمای کامل هفته اول در مرکز راهنما مرحله‌به‌مرحله نوشته شده است.</p>
HTML,
                ],
                'en' => [
                    'title' => 'Your first week with NextBook: where to start',
                    'excerpt' => 'The order that makes it easy: the basics, your lists, opening balances and a check, before the first real transaction.',
                    'content' => <<<'HTML'
<p>Most first-week problems come from the order of things, not from the system. If you follow the steps below, each one builds on the one before and you do not have to redo anything later.</p>

<h2>First, pick a start date</h2>
<p>Choose a day such as the first of a month or of a year, and stick to it. Every opening balance should be what you had at the end of the day before that date. From that day on, record real transactions.</p>

<h2>Stage A: the basics</h2>
<p>Check the company, the currencies and the warehouse. The system has already prepared a chart of accounts, units of measure, categories, a cash customer, a financial period and a branch with a warehouse, so you do not start from zero.</p>

<h2>Stage B: your lists</h2>
<p>Create your items, customers and suppliers. You cannot record a balance for something that does not exist, which is why the lists come before the balances.</p>

<h2>Stage C: opening balances</h2>
<p>Enter the stock in your warehouse, what customers owe you, what you owe suppliers and the cash and bank balances on the start date. Without them your reports start from zero.</p>

<h2>Stage D: check your numbers</h2>
<p>Compare the system's numbers with your own books. A mistake is easy to catch now and hard to catch months later.</p>

<div class="callout callout-tip">After the check, add your staff and give each one a suitable role, so everyone sees only what they need.</div>

<h2>Your first real day</h2>
<p>Now record your first sale or purchase and watch stock and the accounts change on their own. The full first-week guide in the help center walks through every step.</p>
HTML,
                ],
            ],
            [
                'slug' => 'draft-post-reverse',
                'icon' => 'lock',
                'days_ago' => 7,
                'fa' => [
                    'title' => 'مسوده، ثبت و برگشت: چرا فاکتور ثبت‌شده قفل است',
                    'excerpt' => 'هر سند سه حالت دارد. دانستن فرق آن‌ها جلوی بیشتر اشتباه‌های حسابی را می‌گیرد.',
                    'content' => <<<'HTML'
<p>اگر فاکتوری را اشتباه ثبت کردید و دیدید که دیگر نمی‌توانید ویرایشش کنید، این طراحی عمدی است. در نکست‌بوک هر معامله مالی از سه حالت می‌گذرد.</p>

<h2>مسوده</h2>
<p>فاکتور ذخیره شده ولی هنوز روی موجودی و حساب اثر نگذاشته است. موجودی اجناس آن فقط «رزرو» می‌شود تا در فاکتور دیگری فروخته نشود. مسوده را می‌توانید ویرایش یا حذف کنید.</p>

<h2>ثبت‌شده</h2>
<p>موجودی از گدام کم شده، طلب مشتری و درآمد در دفتر نوشته شده و فاکتور قفل است. چاپ و برگشت ممکن است، ویرایش نه.</p>

<h2>برگشت‌شده</h2>
<p>یک سند معکوس ساخته شده که موجودی و حساب را به حالت قبل برمی‌گرداند. هر دو سند در سیستم می‌مانند.</p>

<div class="callout callout-warn">اگر مشتری یا گدام را اشتباه انتخاب کرده‌اید، فاکتور ثبت‌شده را پنهانی اصلاح نمی‌کنید. آن را برگشت می‌دهید، فاکتور درست را وارد و ثبت می‌کنید. اگر همان روز است و هنوز مسوده است، فقط مسوده را ویرایش کنید.</div>

<h2>چرا اینطور بهتر است؟</h2>
<ul>
<li>هر تغییر ردی دارد و بعداً می‌توانید بگویید چه شد و چرا.</li>
<li>گزارشی که دیروز گرفتید، امروز بی‌صدا تغییر نمی‌کند.</li>
<li>موجودی و حساب‌ها همیشه با اسناد می‌خوانند.</li>
</ul>
HTML,
                ],
                'en' => [
                    'title' => 'Draft, post and reverse: why a posted invoice is locked',
                    'excerpt' => 'Every document has three states. Knowing the difference prevents most accounting mistakes.',
                    'content' => <<<'HTML'
<p>If you posted an invoice by mistake and found you cannot edit it, that is by design. In NextBook every financial transaction goes through three states.</p>

<h2>Draft</h2>
<p>The invoice is saved but has not yet affected stock or the accounts. Its stock is only "reserved" so it cannot be sold on another invoice. You can edit or delete a draft.</p>

<h2>Posted</h2>
<p>Stock has left the warehouse, the customer's receivable and the income have been written to the ledger, and the invoice is locked. You can print it and reverse it, but not edit it.</p>

<h2>Reversed</h2>
<p>A reversing document has been created that puts stock and the accounts back as they were. Both documents stay in the system.</p>

<div class="callout callout-warn">If you chose the wrong customer or warehouse, you do not quietly fix a posted invoice. You reverse it, then enter and post the correct one. If it is the same day and still a draft, just edit the draft.</div>

<h2>Why this is better</h2>
<ul>
<li>Every change leaves a trail, so you can say later what happened and why.</li>
<li>A report you ran yesterday does not silently change today.</li>
<li>Stock and the accounts always agree with the documents.</li>
</ul>
HTML,
                ],
            ],
            [
                'slug' => 'fifo-and-landed-costs',
                'icon' => 'calculator',
                'days_ago' => 2,
                'fa' => [
                    'title' => 'قیمت تمام‌شده: چرا سود شما باید با FIFO و هزینه‌های وارداتی حساب شود',
                    'excerpt' => 'اگر قیمت خرید جنس در طول سال تغییر می‌کند، سود هر فروش به این بستگی دارد که کدام جنس فروخته شد.',
                    'content' => <<<'HTML'
<p>فرض کنید یک جنس را دو بار خریده‌اید و قیمتش بالا رفته است. وقتی می‌فروشید، سودتان به این بستگی دارد که از کدام خرید فروخته‌اید. سیستم این را برای شما حساب می‌کند.</p>

<h2>FIFO یعنی چه؟</h2>
<p>FIFO یعنی اول ورود، اول خروج: جنسی که زودتر آمده زودتر فروخته می‌شود و قیمت تمام‌شده همان خرید حساب می‌شود.</p>

<h2>یک مثال ساده</h2>
<ul>
<li>۱۰ دانه را به قیمت ۱۰۰ خریدید.</li>
<li>بعداً ۱۰ دانه دیگر را به قیمت ۱۲۰ خریدید.</li>
<li>حالا ۱۲ دانه می‌فروشید.</li>
</ul>
<p>قیمت تمام‌شده این فروش ۱۰ دانه به ۱۰۰ و ۲ دانه به ۱۲۰ است، یعنی ۱٬۲۴۰. نه ۱٬۳۲۰ و نه ۱٬۲۰۰ که با میانگین یا حدس به دست می‌آید. سودتان از روی همین عدد حساب می‌شود. (اعداد این مثال فرضی‌اند.)</p>

<h2>هزینه‌های وارداتی</h2>
<p>اگر جنس را وارد می‌کنید، کرایه، گمرک و مصارف دیگر هم بخشی از قیمت جنس‌اند. اگر آن‌ها را به قیمت اضافه نکنید، سود روی کاغذ بیشتر از واقعیت است. در نکست‌بوک این مصارف را روی بیل خرید اضافه می‌کنید و به قیمت تمام‌شده می‌رود.</p>

<div class="callout callout-tip">قیمت تمام‌شده درست یعنی گزارش سود قابل اعتماد، و تصمیم‌های قیمت‌گذاری بر پایه عدد واقعی.</div>
HTML,
                ],
                'en' => [
                    'title' => 'Cost price: why your profit should be calculated with FIFO and landed costs',
                    'excerpt' => 'If the price you pay for an item changes during the year, the profit on each sale depends on which units were sold.',
                    'content' => <<<'HTML'
<p>Suppose you bought an item twice and the price went up. When you sell, your profit depends on which purchase the goods came from. The system works that out for you.</p>

<h2>What does FIFO mean?</h2>
<p>FIFO means first in, first out: the goods that arrived first are sold first, and the cost is that of the purchase they came from.</p>

<h2>A simple example</h2>
<ul>
<li>You bought 10 units at 100.</li>
<li>Later you bought 10 more units at 120.</li>
<li>Now you sell 12 units.</li>
</ul>
<p>The cost of this sale is 10 units at 100 and 2 units at 120, which is 1,240. Not 1,320, and not the 1,200 you would get from an average or a guess. Your profit is calculated from this number. (The numbers in this example are made up.)</p>

<h2>Landed costs</h2>
<p>If you import goods, freight, customs and other costs are part of the cost of the goods too. If you leave them out, the profit on paper is higher than the real one. In NextBook you add these costs on the purchase bill and they go into the cost price.</p>

<div class="callout callout-tip">A correct cost price means a profit report you can trust, and pricing decisions based on the real number.</div>
HTML,
                ],
            ],
        ];

        foreach ($posts as $row) {
            Post::updateOrCreate(['slug' => $row['slug']], [
                'icon' => $row['icon'],
                'is_published' => true,
                'published_at' => now()->subDays($row['days_ago'])->startOfDay()->addHours(9),
                'title' => ['fa' => $row['fa']['title'], 'en' => $row['en']['title']],
                'excerpt' => ['fa' => $row['fa']['excerpt'], 'en' => $row['en']['excerpt']],
                'content' => ['fa' => $row['fa']['content'], 'en' => $row['en']['content']],
            ]);
        }
    }
}
