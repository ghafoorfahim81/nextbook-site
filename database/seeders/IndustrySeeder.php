<?php

namespace Database\Seeders;

use App\Models\Industry;
use Illuminate\Database\Seeder;

class IndustrySeeder extends Seeder
{
    public function run(): void
    {
        // What each trade gets mirrors the ERP's config/business_profiles.php.
        $common = [
            'fa' => ['تقویم شمسی، چند ارز و حسابداری دوطرفه در کنار آن', 'دری و پشتو، با راهنمای کاربر داخل سیستم'],
            'en' => ['Jalali calendar, multiple currencies and double-entry accounting alongside', 'Dari and Pashto, with a user manual inside the system'],
        ];

        $industries = [
            ['pharmacy', 'pharmacy', 'pill',
                ['دواخانه', 'بچ و انقضای هر دوا را زیر کنترول نگه دارید.', ['پیگیری بچ و تاریخ انقضا برای هر جنس', 'نام جنریک و تولیدکننده در فورم جنس', 'واریانت‌ها (مثلاً قوطی و ورق)', 'FIFO: اول دوایی که زودتر منقضی می‌شود و زودتر آمده است', 'حساب تامین‌کننده‌ها و شرکت‌های توزیع']],
                ['Pharmacy', 'Keep the batch and expiry of every medicine under control.', ['Batch and expiry tracking for every item', 'Generic name and manufacturer on the item form', 'Variants (for example box and strip)', 'FIFO costing of stock', 'Accounts for suppliers and distributors']]],
            ['supermarket', 'supermarket', 'shopping-basket',
                ['سوپرمارکت', 'اجناس زیاد، تاریخ انقضا و چند اندازه بسته‌بندی.', ['پیگیری تاریخ انقضا', 'واریانت برای اندازه‌های مختلف بسته، هر کدام با بارکد و قیمت خودش', 'چند گدام و انتقال به قفسه یا گدام دیگر', 'قوانین تخفیف', 'مشتری نقدی برای فروش سریع']],
                ['Supermarket', 'Many items, expiry dates and several pack sizes.', ['Expiry tracking', 'Variants for pack sizes, each with its own barcode and price', 'Several warehouses and transfers between them', 'Discount rules', 'A cash customer for quick sales']]],
            ['grocery', 'grocery', 'wheat',
                ['بقالی', 'اجناس خوراکی با انقضا و وزن.', ['پیگیری انقضا', 'مدت ماندگاری و وزن جنس', 'شماره قفسه', 'حساب مشتریان و تامین‌کننده‌ها']],
                ['Grocery', 'Food items with expiry and weight.', ['Expiry tracking', 'Shelf life and weight on the item', 'Rack number', 'Customer and supplier accounts']]],
            ['retail', 'retail', 'store',
                ['پرچون‌فروشی', 'برای دکان‌هایی که اجناس متنوع می‌فروشند.', ['اجناس با واریانت', 'فروش، خرید و برگشت', 'حساب مشتریان', 'گزارش سود و موجودی']],
                ['Retail shop', 'For shops that sell a varied range of goods.', ['Items with variants', 'Sales, purchases and returns', 'Customer accounts', 'Profit and stock reports']]],
            ['clothing', 'clothing', 'shirt',
                ['پوشاک', 'سایز و رنگ هر جنس جداگانه ثبت و فروخته می‌شود.', ['واریانت‌ها: سایز و رنگ، هر کدام با SKU، بارکد و قیمت خودش', 'شماره مدل (Style)', 'موجودی هر سایز و رنگ جداگانه', 'چند شعبه']],
                ['Clothing', 'Each size and colour is recorded and sold separately.', ['Variants: size and colour, each with its own SKU, barcode and price', 'Style number', 'Stock of each size and colour on its own', 'Multiple branches']]],
            ['mobile', 'mobile', 'smartphone',
                ['موبایل', 'هر دستگاه با IMEI خودش ثبت می‌شود.', ['سریال یا IMEI برای هر دستگاه', 'واریانت‌ها: ظرفیت، رنگ، رم', 'پیدا کردن اینکه یک IMEI به چه کسی فروخته شد', 'حساب مشتریان و تامین‌کننده‌ها با چند ارز']],
                ['Mobile phones', 'Every device is recorded with its own IMEI.', ['Serial number or IMEI for every device', 'Variants: capacity, colour, RAM', 'Find out who a given IMEI was sold to', 'Customer and supplier accounts in several currencies']]],
            ['computer', 'computer', 'laptop',
                ['کمپیوتر', 'لپ‌تاپ و لوازم با سریال و مشخصات.', ['سریال برای هر دستگاه', 'واریانت‌ها برای مشخصات مختلف', 'خرید با ارز خارجی و نرخ تبدیل', 'هزینه‌های وارداتی روی قیمت جنس']],
                ['Computers', 'Laptops and parts with serial numbers and specifications.', ['Serial number for every unit', 'Variants for different specifications', 'Purchases in a foreign currency with exchange rate', 'Landed costs added to the item cost']]],
            ['electronics', 'electronics', 'monitor',
                ['الکترونیک', 'لوازم برقی با واریانت و اجناس جداگانه.', ['واریانت‌ها', 'ثبت تک‌تک اجناس در صورت نیاز', 'خرید و فروش با چند ارز', 'هزینه‌های وارداتی']],
                ['Electronics', 'Electrical goods with variants and individually recorded units.', ['Variants', 'Individual pieces recorded when needed', 'Buying and selling in several currencies', 'Landed costs']]],
            ['jewellery', 'jewellery', 'gem',
                ['جواهر', 'اجناسی که با وزن اندازه می‌شوند.', ['اجناس وزنی', 'واریانت‌ها', 'ثبت تک‌تک قطعه‌ها', 'حساب‌ها با چند ارز']],
                ['Jewellery', 'Items measured by weight.', ['Weighted items', 'Variants', 'Individual pieces recorded', 'Accounts in several currencies']]],
            ['carpet', 'carpet', 'layers',
                ['قالین', 'هر تخته قالین جداگانه ثبت می‌شود.', ['واریانت‌ها (اندازه، نقشه)', 'ثبت هر تخته به‌صورت جداگانه', 'صادرات و خرید با ارز خارجی', 'حساب خریداران و تامین‌کننده‌ها']],
                ['Carpets', 'Each carpet is recorded separately.', ['Variants (size, design)', 'Each carpet recorded as its own piece', 'Exports and purchases in a foreign currency', 'Buyer and supplier accounts']]],
            ['automobile', 'automobile', 'car',
                ['موتر', 'موتر و لوازم یدکی.', ['واریانت‌ها', 'ثبت تک‌تک قطعه‌ها یا موترها', 'خرید با ارز خارجی و هزینه‌های وارداتی', 'حساب مشتریان']],
                ['Automobiles', 'Vehicles and spare parts.', ['Variants', 'Individual vehicles or parts recorded', 'Foreign-currency purchases and landed costs', 'Customer accounts']]],
            ['book', 'book', 'book-open',
                ['کتاب', 'کتاب‌فروشی و نشر.', ['اجناس با واریانت', 'کد کتاب و دسته‌بندی', 'فروش، خرید و برگشت', 'حساب ناشران و مشتریان']],
                ['Books', 'Bookshops and publishers.', ['Items with variants', 'Book code and categories', 'Sales, purchases and returns', 'Publisher and customer accounts']]],
            ['manufacturing', 'manufacturing', 'factory',
                ['تولیدی', 'مواد خام، لات تولید و محصول نهایی.', ['پیگیری لات یا بچ تولید', 'واریانت‌ها', 'چند گدام برای مواد و محصول', 'مصارف و حساب‌ها']],
                ['Manufacturing', 'Raw materials, production lots and finished goods.', ['Batch or lot tracking', 'Variants', 'Several warehouses for materials and products', 'Expenses and accounts']]],
            ['pharma_manufacturing', 'pharma_manufacturing', 'pill',
                ['تولید دوا', 'بچ، انقضا و مدت ماندگاری برای تولیدکننده.', ['پیگیری بچ و انقضا', 'مدت ماندگاری و حداقل ماندگاری باقی‌مانده', 'ناحیه نگهداری', 'چند گدام']],
                ['Pharmaceutical manufacturing', 'Batch, expiry and shelf life for the manufacturer.', ['Batch and expiry tracking', 'Shelf life and minimum remaining shelf life', 'Storage zone', 'Several warehouses']]],
            ['hotel', 'hotel', 'bed-double',
                ['هوتل', 'مصارف، مواد و کارمندان هوتل.', ['مصارف با دسته‌بندی و مراکز هزینه', 'منابع بشری: حاضری، رخصتی و معاش', 'حسابداری و گزارش‌ها', 'چند شعبه']],
                ['Hotel', 'Expenses, supplies and staff of a hotel.', ['Expenses with categories and cost centers', 'HR: attendance, leave and payroll', 'Accounting and reports', 'Multiple branches']]],
            ['accounting', 'accounting', 'calculator',
                ['دفتر حسابداری', 'برای دفتری که دفتر حساب چند مشتری را نگه می‌دارد.', ['چند شرکت در یک سیستم، با داده جدا', 'قید روزنامچه، ووچر، رسید و پرداخت', 'دوره مالی و بیلانس افتتاحیه', 'گزارش‌های مالی']],
                ['Accounting office', 'For an office that keeps the books of several clients.', ['Several companies in one system, with separate data', 'Journal entries, vouchers, receipts and payments', 'Financial periods and opening balances', 'Financial reports']]],
        ];

        foreach ($industries as $i => [$slug, $type, $icon, $fa, $en]) {
            $attributes = ['business_type' => $type, 'icon' => $icon, 'sort' => $i + 1];
            foreach (['fa' => $fa, 'en' => $en] as $locale => [$title, $summary, $points]) {
                $attributes['title'][$locale] = $title;
                $attributes['summary'][$locale] = $summary;
                $attributes['points'][$locale] = array_merge($points, $common[$locale]);
            }
            Industry::updateOrCreate(['slug' => $slug], $attributes);
        }
    }
}
