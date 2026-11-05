<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        $faqs = [
            [
                ['سیستم به چه زبان‌هایی است؟', 'نکست‌بوک به دری، پشتو و انگلیسی کار می‌کند و راهنمای کاربر نیز به همین سه زبان است.'],
                ['What languages does the system support?', 'NextBook works in Dari, Pashto and English, and the user manual is in the same three languages.'],
            ],
            [
                ['آیا تقویم شمسی دارد؟', 'بله. می‌توانید تقویم شمسی یا میلادی را برای شرکت خود انتخاب کنید.'],
                ['Does it support the Jalali calendar?', 'Yes. You can choose the Jalali or the Gregorian calendar for your company.'],
            ],
            [
                ['آیا برای نوع تجارت من مناسب است؟', 'نکست‌بوک برای دواخانه، سوپرمارکت، پوشاک، موبایل، کمپیوتر، جواهر، قالین، تولیدی و چند نوع دیگر تنظیم آماده دارد. اگر تجارت شما در لیست نیست با ما تماس بگیرید.'],
                ['Is it right for my type of business?', 'NextBook has a ready setup for pharmacies, supermarkets, clothing, mobile phones, computers, jewellery, carpets, manufacturing and more. If yours is not listed, get in touch.'],
            ],
            [
                ['آیا با چند ارز کار می‌کند؟', 'بله. مبلغ هر معامله هم به ارز اصلی و هم به ارز خارجی با نرخ تبدیل ثبت می‌شود.'],
                ['Does it work with several currencies?', 'Yes. The amount of each transaction is kept in both the home and the foreign currency, with the exchange rate.'],
            ],
            [
                ['می‌توانم چند شعبه داشته باشم؟', 'بله. می‌توانید چند شعبه و چند گدام داشته باشید و برای هر کاربر مشخص کنید به کدام بخش‌ها دسترسی دارد.'],
                ['Can I have several branches?', 'Yes. You can have several branches and warehouses, and decide what each user can access.'],
            ],
            [
                ['بخش‌هایی را که نیاز ندارم می‌توانم خاموش کنم؟', 'بله. ماژول‌هایی مثل منابع بشری، مصارف یا قوانین تخفیف برای هر شرکت روشن یا خاموش می‌شوند. معلومات آن‌ها حذف نمی‌شود و با روشن کردن دوباره برمی‌گردد.'],
                ['Can I switch off the parts I do not need?', 'Yes. Modules such as HR, expenses or discount rules can be switched on or off for each company. Their data is not deleted and returns when you switch them back on.'],
            ],
            [
                ['داده شرکت من از دیگران جداست؟', 'بله. داده هر شرکت جداست و این جداسازی در سطح دیتابیس نیز اعمال می‌شود.'],
                ['Is my company\'s data separate from others\'?', 'Yes. Each company\'s data is kept apart, and the separation is also enforced in the database.'],
            ],
            [
                ['چطور شروع کنم؟', 'یک دیمو بخواهید. با شما تماس می‌گیریم، سیستم را مطابق نوع تجارت شما نشان می‌دهیم و در راه‌اندازی کمک می‌کنیم. راهنمای شروع هفته اول نیز در مرکز راهنما هست.'],
                ['How do I get started?', 'Ask for a demo. We will call you, show the system for your type of business and help you set it up. There is also a first-week getting-started guide in the help center.'],
            ],
            [
                ['من تجربه کار با نرم‌افزار حسابداری را ندارم. آیا کار کردن با نرم‌افزار سخت است؟', 'نه. نکست‌بوک برای تاجرانی ساخته شده که با دفتر و کتابچه کار کرده‌اند و به دری، پشتو و انگلیسی است. با یک دیمو شروع می‌کنیم، سیستم را مطابق نوع تجارت شما تنظیم می‌کنیم و در روزهای اول کنار شما هستیم.'],
                ['I have never used accounting software. Is it hard to use?', 'No. NextBook is made for traders who have kept their books in a notebook, and it works in Dari, Pashto and English. We start with a demo, set the system up for your type of business and stay with you in the first days.'],
            ],
            [
                ['آیا نرم‌افزار آموزش و راهنمای استفاده دارد؟', 'بله. مرکز راهنما مقاله‌های گام‌به‌گام و راهنمای کاربر را به دری، پشتو و انگلیسی دارد. ویدیوهای آموزشی در همه بسته‌ها هست و آموزش حضوری یا آنلاین کارکنان به‌صورت خدمت جداگانه یا در بسته سازمانی داده می‌شود.'],
                ['Does it come with training and a user guide?', 'Yes. The help center has step-by-step articles and the user manual in Dari, Pashto and English. Training videos are available on every plan, and in-person or online staff training is offered as an add-on or with the Enterprise plan.'],
            ],
            [
                ['پشتیبانی نرم‌افزار تا چه مدت است؟ و آیا هزینه سالانه دارد؟', 'پشتیبانی با تلفن و واتساپ از بسته حرفه‌ای شروع می‌شود و برای بسته سازمانی پشتیبانی ویژه و مدیر حساب اختصاصی است. شرایط و هزینه دقیق هر بسته در صفحه قیمت‌ها آمده است؛ برای جزئیات بیشتر با ما تماس بگیرید.'],
                ['How long does support last, and is there a yearly fee?', 'Phone and WhatsApp support starts with the Professional plan, and the Enterprise plan has priority support and a dedicated account manager. The terms and cost of each plan are on the pricing page; contact us for details.'],
            ],
            [
                ['آیا نرم‌افزار پشتیبان‌گیری دارد؟', 'نسخه پشتیبان خودکار از معلومات در بسته سازمانی شامل است. برای بسته‌های دیگر با ما تماس بگیرید تا بهترین راه نگهداری معلومات شما را تنظیم کنیم.'],
                ['Does it have backups?', 'Automatic backups of your data are part of the Enterprise plan. For the other plans, get in touch and we will set up the best way to keep your data safe.'],
            ],
            [
                ['اگر نسخه پایه را خریداری کنم در آینده می‌توانم آن را به نسخه پیشرفته تبدیل کنم؟', 'بله. وقتی تجارت شما بزرگ شد می‌توانید از بسته پایه به حرفه‌ای یا سازمانی بروید. معلومات شما همان‌جا می‌ماند و تنها امکانات بیشتر برایتان باز می‌شود.'],
                ['If I buy the basic plan, can I upgrade later?', 'Yes. When your business grows you can move from the Starter plan to Professional or Enterprise. Your data stays where it is and only the extra features are opened for you.'],
            ],
        ];

        Faq::query()->delete();

        foreach ($faqs as $i => [$fa, $en]) {
            $faq = new Faq(['sort' => $i + 1]);
            $faq->setTranslation('question', 'fa', $fa[0])->setTranslation('answer', 'fa', $fa[1]);
            $faq->setTranslation('question', 'en', $en[0])->setTranslation('answer', 'en', $en[1]);
            $faq->save();
        }
    }
}
