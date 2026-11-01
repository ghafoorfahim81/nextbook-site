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
