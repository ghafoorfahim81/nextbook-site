<?php

namespace Database\Seeders;

use App\Models\Industry;
use Illuminate\Database\Seeder;

/**
 * The long-form text of the industry pages. What each trade gets mirrors the ERP's
 * business profiles (config/business_profiles.php): which item fields show, and whether
 * variants, batches and expiry, or serial numbers are on by default.
 *
 * Apostrophes in the English text are typographic (’) so the strings stay single-quoted.
 */
class IndustryContentSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->content() as $slug => $row) {
            $industry = Industry::where('slug', $slug)->first();

            if (! $industry) {
                continue;
            }

            $attributes = ['feature_slugs' => $row['features']];

            foreach (['fa', 'en'] as $locale) {
                [$overview, $highlights, $steps] = $row[$locale];
                $attributes['overview'][$locale] = $overview;
                $attributes['highlights'][$locale] = array_map(fn (array $h) => ['title' => $h[0], 'text' => $h[1]], $highlights);
                $attributes['steps'][$locale] = $steps;
            }

            $industry->update($attributes);
        }
    }

    private function content(): array
    {
        $core = ['inventory', 'sales', 'purchases', 'accounting'];

        return [

            'pharmacy' => [
                'features' => ['inventory', 'sales', 'purchases', 'accounting', 'reports'],
                'fa' => [
                    "در دواخانه خطای کوچک در انقضا یا بچ بزرگ تمام می‌شود. نکست‌بوک هر دوا را با بچ و تاریخ انقضا نگه می‌دارد، هنگام فروش بچ را انتخاب می‌کنید و گزارش دواهای نزدیک به انقضا را هر روز می‌بینید.\n\nفورم جنس برای دواخانه تنظیم شده است: نام جنریک و تولیدکننده دارد و بچ و انقضا از اول روشن است.",
                    [
                        ['بچ و انقضا', 'هر بچ تاریخ انقضا و مقدار خودش را دارد. در فروش، بچ مشخص انتخاب می‌شود و برگشت هم به همان بچ برمی‌گردد.'],
                        ['نزدیک به انقضا', 'داشبورد تعداد بچ‌های نزدیک به انقضا را نشان می‌دهد تا قبل از ضایع شدن اقدام کنید.'],
                        ['نام جنریک و تولیدکننده', 'در فورم جنس می‌نویسید و در جستجو پیدا می‌کنید.'],
                        ['حساب شرکت‌های توزیع', 'بدهی هر تامین‌کننده و بیل‌های باز او را ببینید و پرداخت‌ها را به بیل‌ها وصل کنید.'],
                    ],
                    ['دوا را با بچ و انقضا ثبت یا موجودی افتتاحیه را وارد می‌کنید.', 'خرید جدید بچ تازه می‌سازد.', 'در فروش، بچ را انتخاب می‌کنید.', 'هر روز گزارش نزدیک به انقضا را کنترول می‌کنید.'],
                ],
                'en' => [
                    "In a pharmacy a small mistake with expiry or batch is costly. NextBook keeps every medicine by batch and expiry date, you pick the batch when you sell, and you see the near-expiry report every day.\n\nThe item form is set up for pharmacies: it has generic name and manufacturer, and batch and expiry are on from the start.",
                    [
                        ['Batch and expiry', 'Each batch has its own expiry date and quantity. In a sale you pick the exact batch, and a return goes back to that batch.'],
                        ['Near expiry', 'The dashboard shows how many batches are close to expiry so you can act before they are wasted.'],
                        ['Generic name and manufacturer', 'Enter them on the item form and find items by them.'],
                        ['Distributor accounts', 'See what you owe each supplier and their open bills, and match payments to bills.'],
                    ],
                    ['Register medicines with batch and expiry, or enter opening stock.', 'A new purchase creates a new batch.', 'When selling, you pick the batch.', 'Check the near-expiry report every day.'],
                ],
            ],

            'supermarket' => [
                'features' => ['inventory', 'sales', 'purchases', 'reports'],
                'fa' => [
                    "سوپرمارکت اجناس زیاد، چند اندازه بسته‌بندی و تاریخ انقضا دارد. هر جنس واریانت‌های خودش را با بارکد و قیمت جدا دارد و فروش با اسکن بارکد سریع انجام می‌شود.\n\nانقضا از اول روشن است و می‌توانید جنس را بین گدام و قفسه‌ها منتقل کنید.",
                    [
                        ['اسکن بارکد', 'جنس را با بارکد در فاکتور فروش اضافه کنید.'],
                        ['اندازه‌های بسته', 'هر اندازه یک واریانت با کد، بارکد و قیمت خودش است.'],
                        ['انقضا', 'تاریخ انقضا ثبت و گزارش نزدیک به انقضا آماده است.'],
                        ['قوانین تخفیف', 'تخفیف‌ها را یک بار تعریف کنید تا خودکار اعمال شوند.'],
                    ],
                    ['اجناس را با واریانت و بارکد ثبت می‌کنید.', 'خرید موجودی را زیاد می‌کند.', 'فروش با اسکن بارکد انجام می‌شود.', 'اجناس کم‌موجودی را از داشبورد می‌بینید.'],
                ],
                'en' => [
                    "A supermarket has many items, several pack sizes and expiry dates. Each item has its own variants with separate barcode and price, and selling is fast with barcode scanning.\n\nExpiry is on from the start, and you can move items between warehouses and shelves.",
                    [
                        ['Barcode scanning', 'Add items to a sales invoice by scanning the barcode.'],
                        ['Pack sizes', 'Each size is a variant with its own code, barcode and price.'],
                        ['Expiry', 'Expiry dates are recorded and the near-expiry report is ready.'],
                        ['Discount rules', 'Define discounts once and they apply on their own.'],
                    ],
                    ['Register items with variants and barcodes.', 'Purchases raise stock.', 'Sell by scanning barcodes.', 'See low-stock items on the dashboard.'],
                ],
            ],

            'grocery' => [
                'features' => ['inventory', 'sales', 'purchases', 'cash-and-bank'],
                'fa' => [
                    "بقالی با اجناس خوراکی کار می‌کند که انقضا و وزن دارند. نکست‌بوک مدت ماندگاری، وزن و شماره قفسه را نگه می‌دارد و حساب مشتریان و تامین‌کننده‌ها را مرتب.\n\nبرای دکانی که مشتری دایمی دارد و نسیه می‌دهد، حساب هر مشتری و رسیدهایش واضح است.",
                    [
                        ['انقضا و ماندگاری', 'هر جنس تاریخ انقضا و مدت ماندگاری دارد.'],
                        ['وزن و قفسه', 'وزن جنس و شماره قفسه را ثبت کنید تا پیدا کردن آسان شود.'],
                        ['حساب مشتریان', 'ببینید هر مشتری چه مقدار باقی دارد و رسید بگیرید.'],
                        ['فروش نقدی', 'مشتری نقدی برای فروش‌های بدون نام آماده است.'],
                    ],
                    ['اجناس را با انقضا و وزن ثبت می‌کنید.', 'فروش نقدی یا نسیه را روی فاکتور می‌زنید.', 'وقتی مشتری پول می‌دهد، رسید ثبت می‌کنید.', 'اجناس نزدیک به انقضا را کنترول می‌کنید.'],
                ],
                'en' => [
                    "A grocery sells food items that have expiry and weight. NextBook keeps shelf life, weight and rack number, and keeps customer and supplier accounts in order.\n\nFor a shop with regular customers who buy on credit, each customer’s account and receipts are clear.",
                    [
                        ['Expiry and shelf life', 'Each item has an expiry date and a shelf life.'],
                        ['Weight and rack', 'Record the item’s weight and rack number to find it quickly.'],
                        ['Customer accounts', 'See what each customer still owes and take a receipt.'],
                        ['Cash sales', 'A cash customer is ready for sales without a name.'],
                    ],
                    ['Register items with expiry and weight.', 'Make cash or credit sales on the invoice.', 'When a customer pays, record a receipt.', 'Check items close to expiry.'],
                ],
            ],

            'retail' => [
                'features' => $core,
                'fa' => [
                    "برای پرچون‌فروشی که اجناس متنوع می‌فروشد، نکست‌بوک فروش، خرید، موجودی و حساب مشتریان را در یک جا نگه می‌دارد. اجناس واریانت دارند و گزارش سود و موجودی همیشه آماده است.",
                    [
                        ['اجناس با واریانت', 'یک جنس در چند نوع (رنگ، اندازه) با موجودی جدا.'],
                        ['فروش و برگشت', 'فاکتور فروش، برگشت و چاپ.'],
                        ['حساب مشتریان', 'طلب هر مشتری و رسیدهایش.'],
                        ['سود و موجودی', 'قیمت تمام‌شده FIFO سود هر فروش را درست حساب می‌کند.'],
                    ],
                    ['اجناس را ثبت می‌کنید.', 'خرید و فروش را روزانه ثبت می‌کنید.', 'حساب مشتریان را رسید می‌گیرید.', 'آخر ماه گزارش سود و موجودی را می‌بینید.'],
                ],
                'en' => [
                    "For a retail shop selling a varied range, NextBook keeps sales, purchases, stock and customer accounts in one place. Items have variants, and profit and stock reports are always ready.",
                    [
                        ['Items with variants', 'One item in several types (colour, size) with separate stock.'],
                        ['Sales and returns', 'Sales invoice, return and printing.'],
                        ['Customer accounts', 'What each customer owes, and their receipts.'],
                        ['Profit and stock', 'FIFO cost makes the profit on each sale right.'],
                    ],
                    ['Register your items.', 'Record purchases and sales daily.', 'Take receipts against customer accounts.', 'At month end, view the profit and stock reports.'],
                ],
            ],

            'clothing' => [
                'features' => ['inventory', 'sales', 'purchases', 'multi-branch'],
                'fa' => [
                    "در پوشاک یک مدل در چند سایز و رنگ فروخته می‌شود و هر ترکیب موجودی جدا دارد. نکست‌بوک واریانت را همان‌طور که می‌فروشید ثبت می‌کند: سایز و رنگ، هر کدام با کد، بارکد و قیمت خودش.\n\nشماره مدل (Style) هم در فورم هست و اگر چند شعبه دارید، موجودی هر شعبه را جدا می‌بینید.",
                    [
                        ['سایز و رنگ', 'یک بار ویژگی‌ها را تعریف کنید و ترکیب‌ها را در یک جدول بسازید.'],
                        ['بارکد هر ترکیب', 'هر سایز و رنگ بارکد خودش را دارد و با اسکن فروخته می‌شود.'],
                        ['موجودی هر سایز', 'ببینید کدام سایز تمام شده است.'],
                        ['چند شعبه', 'موجودی را بین شعبه‌ها منتقل و فروش آن‌ها را مقایسه کنید.'],
                    ],
                    ['مدل را با سایزها و رنگ‌ها ثبت می‌کنید.', 'بارکد چاپ و به جنس می‌چسبانید.', 'با اسکن می‌فروشید.', 'سایزهای تمام‌شده را می‌بینید و سفارش می‌دهید.'],
                ],
                'en' => [
                    "In clothing, one model sells in several sizes and colours, and each combination has its own stock. NextBook records variants the way you sell them: size and colour, each with its own code, barcode and price.\n\nThe style number is on the form too, and if you have several branches you see each branch’s stock separately.",
                    [
                        ['Size and colour', 'Define the attributes once and build the combinations in one grid.'],
                        ['A barcode for each combination', 'Each size and colour has its own barcode and is sold by scanning.'],
                        ['Stock of each size', 'See which size has run out.'],
                        ['Several branches', 'Move stock between branches and compare their sales.'],
                    ],
                    ['Register the model with its sizes and colours.', 'Print barcodes and attach them to the goods.', 'Sell by scanning.', 'See the sizes that ran out and order more.'],
                ],
            ],

            'mobile' => [
                'features' => ['inventory', 'sales', 'purchases', 'cash-and-bank'],
                'fa' => [
                    "در موبایل‌فروشی هر دستگاه با IMEI خودش فروخته می‌شود. نکست‌بوک سریال یا IMEI هر دستگاه را از خرید تا فروش نگه می‌دارد، پس می‌دانید هر دستگاه از کجا آمده و به چه کسی رفته است.\n\nظرفیت، رنگ و رم واریانت هستند و خرید با ارز خارجی با نرخ تبدیل ثبت می‌شود.",
                    [
                        ['IMEI هر دستگاه', 'در خرید ثبت می‌شود و در فروش همان دستگاه مشخص انتخاب می‌شود.'],
                        ['واریانت‌ها', 'ظرفیت، رنگ و رم هر کدام قیمت و موجودی خودش را دارد.'],
                        ['خرید با ارز', 'مبلغ به ارز خارجی و ارز اصلی با نرخ همان روز.'],
                        ['حساب‌ها', 'حساب تامین‌کننده‌ها و مشتریان با چند ارز.'],
                    ],
                    ['در خرید، IMEI دستگاه‌ها را وارد می‌کنید.', 'موجودی هر واریانت را می‌بینید.', 'در فروش، دستگاه مشخص را انتخاب می‌کنید.', 'هر IMEI را می‌توانید تا مشتری دنبال کنید.'],
                ],
                'en' => [
                    "In a phone shop every device is sold with its own IMEI. NextBook keeps the serial number or IMEI of each device from purchase to sale, so you know where each came from and who it went to.\n\nCapacity, colour and RAM are variants, and purchases in a foreign currency are recorded with the exchange rate.",
                    [
                        ['The IMEI of each device', 'Recorded at purchase, and the exact device is chosen at sale.'],
                        ['Variants', 'Capacity, colour and RAM each have their own price and stock.'],
                        ['Buying in a foreign currency', 'The amount in the foreign and the home currency at that day’s rate.'],
                        ['Accounts', 'Supplier and customer accounts in several currencies.'],
                    ],
                    ['At purchase, enter the devices’ IMEIs.', 'See the stock of each variant.', 'At sale, choose the exact device.', 'Follow any IMEI through to the customer.'],
                ],
            ],

            'computer' => [
                'features' => ['inventory', 'sales', 'purchases', 'cash-and-bank'],
                'fa' => [
                    "برای دکان کمپیوتر، لپ‌تاپ و لوازم با سریال و مشخصات فروخته می‌شوند. نکست‌بوک سریال هر دستگاه و واریانت‌های مشخصات را نگه می‌دارد و خرید با ارز خارجی و هزینه‌های وارداتی را روی قیمت جنس می‌آورد.",
                    [
                        ['سریال', 'هر دستگاه با سریال خودش از خرید تا فروش.'],
                        ['مشخصات', 'واریانت‌ها برای رم، حافظه، رنگ و غیره.'],
                        ['هزینه‌های وارداتی', 'کرایه و گمرک به قیمت جنس اضافه می‌شود.'],
                        ['ارز خارجی', 'خرید با ارز خارجی و نرخ تبدیل.'],
                    ],
                    ['در خرید سریال‌ها را ثبت می‌کنید.', 'هزینه‌های وارداتی را اضافه می‌کنید.', 'در فروش دستگاه مشخص را انتخاب می‌کنید.', 'گزارش سود با قیمت تمام‌شده درست را می‌بینید.'],
                ],
                'en' => [
                    "A computer shop sells laptops and parts with serial numbers and specifications. NextBook keeps each device’s serial number and specification variants, and brings foreign-currency purchases and landed costs into the item’s price.",
                    [
                        ['Serial numbers', 'Each device with its own serial from purchase to sale.'],
                        ['Specifications', 'Variants for RAM, storage, colour and so on.'],
                        ['Landed costs', 'Freight and customs are added to the item cost.'],
                        ['Foreign currency', 'Purchases in a foreign currency with the exchange rate.'],
                    ],
                    ['Record serials at purchase.', 'Add the landed costs.', 'At sale, choose the exact device.', 'See profit based on the correct cost.'],
                ],
            ],

            'electronics' => [
                'features' => ['inventory', 'sales', 'purchases', 'cash-and-bank'],
                'fa' => [
                    "لوازم برقی و الکترونیک انواع زیاد دارند و بعضی را باید تک‌تک ثبت کرد. نکست‌بوک هم واریانت دارد و هم ثبت جداگانه هر قطعه، و خرید و فروش را با چند ارز و هزینه وارداتی انجام می‌دهد.",
                    [
                        ['واریانت‌ها', 'مدل، رنگ و ظرفیت.'],
                        ['ثبت تک‌تک اجناس', 'برای اجناسی که باید جدا ردیابی شوند.'],
                        ['چند ارز', 'خرید و فروش با ارز خارجی.'],
                        ['هزینه‌های وارداتی', 'قیمت تمام‌شده واقعی هر جنس.'],
                    ],
                    ['جنس را با واریانت‌ها ثبت می‌کنید.', 'خرید را با هزینه‌های وارداتی وارد می‌کنید.', 'می‌فروشید و موجودی خودکار کم می‌شود.', 'سود هر جنس را در گزارش می‌بینید.'],
                ],
                'en' => [
                    "Electrical and electronic goods come in many types, and some have to be recorded one by one. NextBook has both variants and individual pieces, and handles purchases and sales in several currencies with landed costs.",
                    [
                        ['Variants', 'Model, colour and capacity.'],
                        ['Individual pieces', 'For items that must be tracked one by one.'],
                        ['Several currencies', 'Buying and selling in a foreign currency.'],
                        ['Landed costs', 'The real cost of each item.'],
                    ],
                    ['Register the item with its variants.', 'Enter the purchase with its landed costs.', 'Sell and stock drops on its own.', 'See each item’s profit in the report.'],
                ],
            ],

            'jewellery' => [
                'features' => ['inventory', 'sales', 'purchases', 'cash-and-bank'],
                'fa' => [
                    "در جواهر قیمت و موجودی با وزن سنجیده می‌شود. نکست‌بوک اجناس وزنی را پشتیبانی می‌کند و هر قطعه را می‌توان جداگانه ثبت کرد، با حساب‌هایی که به چند ارز نگه داشته می‌شود.",
                    [
                        ['اجناس وزنی', 'مقدار با وزن ثبت می‌شود.'],
                        ['هر قطعه جداگانه', 'قطعات ارزشمند را تک‌تک ثبت و ردیابی کنید.'],
                        ['واریانت‌ها', 'عیار، نوع و غیره.'],
                        ['چند ارز', 'حساب‌ها به ارزهای مختلف.'],
                    ],
                    ['جنس را به‌صورت وزنی ثبت می‌کنید.', 'قطعه‌های ارزشمند را جداگانه ثبت می‌کنید.', 'می‌فروشید و موجودی کم می‌شود.', 'حساب مشتریان را رسید می‌گیرید.'],
                ],
                'en' => [
                    "In jewellery, price and stock are measured by weight. NextBook supports weighed items, and each piece can be recorded individually, with accounts kept in several currencies.",
                    [
                        ['Weighed items', 'Quantity is recorded by weight.'],
                        ['Each piece on its own', 'Record and track valuable pieces one by one.'],
                        ['Variants', 'Purity, type and so on.'],
                        ['Several currencies', 'Accounts in different currencies.'],
                    ],
                    ['Record items by weight.', 'Record valuable pieces individually.', 'Sell and stock goes down.', 'Take receipts against customer accounts.'],
                ],
            ],

            'carpet' => [
                'features' => ['inventory', 'sales', 'purchases', 'cash-and-bank'],
                'fa' => [
                    "هر قالین یک قطعه جداست. نکست‌بوک هر تخته را جداگانه با اندازه و مشخصاتش ثبت می‌کند و خرید و فروش را با ارز خارجی هم می‌پذیرد، که برای صادرات و تجارت قالین مهم است.",
                    [
                        ['هر تخته جداگانه', 'اندازه و مشخصات هر قطعه.'],
                        ['واریانت‌ها', 'اندازه و نقشه.'],
                        ['ارز خارجی', 'خرید و فروش با ارز و نرخ تبدیل.'],
                        ['حساب خریداران', 'طلب هر خریدار و رسیدهایش.'],
                    ],
                    ['هر تخته را ثبت می‌کنید.', 'می‌فروشید و همان قطعه از موجودی خارج می‌شود.', 'پول را با ارز مناسب رسید می‌گیرید.', 'گزارش موجودی و سود را می‌بینید.'],
                ],
                'en' => [
                    "Every carpet is a separate piece. NextBook records each carpet individually with its size and details, and accepts purchases and sales in a foreign currency, which matters for carpet trade and exports.",
                    [
                        ['Each carpet on its own', 'Size and details of each piece.'],
                        ['Variants', 'Size and design.'],
                        ['Foreign currency', 'Buying and selling in a currency with the exchange rate.'],
                        ['Buyer accounts', 'What each buyer owes and their receipts.'],
                    ],
                    ['Record each carpet.', 'Sell, and that exact piece leaves stock.', 'Take the money in the right currency.', 'See stock and profit reports.'],
                ],
            ],

            'ply_carpet' => [
                'features' => ['inventory', 'sales', 'purchases', 'cash-and-bank'],
                'fa' => [
                    "پلایوود و قالین با ابعاد فروخته می‌شوند و قیمت‌شان بر اساس فوت یا متر است. نکست‌بوک ابعاد هر جنس، کشور مبدا و کد HS را در فورم جنس نگه می‌دارد و روش قیمت‌گذاری را مشخص می‌کند.\n\nاین برای تاجرانی که جنس وارد یا صادر می‌کنند مهم است: کد HS و کشور مبدا همراه جنس است و خرید با ارز خارجی با هزینه‌های وارداتی روی قیمت می‌آید.",
                    [
                        ['ابعاد', 'طول و عرض هر جنس ثبت می‌شود.'],
                        ['قیمت با فوت یا متر', 'روش قیمت‌گذاری را برای هر جنس مشخص کنید.'],
                        ['کشور مبدا و کد HS', 'معلومات لازم برای واردات و صادرات در فورم جنس.'],
                        ['قطعه‌ها و واریانت‌ها', 'هر قطعه جداگانه ثبت و ردیابی می‌شود.'],
                    ],
                    ['جنس را با ابعاد و روش قیمت‌گذاری ثبت می‌کنید.', 'خرید را با ارز خارجی و هزینه‌های وارداتی وارد می‌کنید.', 'می‌فروشید و قطعه مشخص از موجودی خارج می‌شود.', 'حساب مشتریان و سود را در گزارش می‌بینید.'],
                ],
                'en' => [
                    "Plywood and carpets are sold by dimensions and priced by the foot or the metre. NextBook keeps each item’s dimensions, country of origin and HS code on the item form, and lets you set the pricing method.\n\nThis matters for traders who import or export: the HS code and country of origin travel with the item, and foreign-currency purchases bring their landed costs into the price.",
                    [
                        ['Dimensions', 'The length and width of each item are recorded.'],
                        ['Pricing by foot or metre', 'Set the pricing method for each item.'],
                        ['Country of origin and HS code', 'The details imports and exports need, on the item form.'],
                        ['Pieces and variants', 'Each piece is recorded and tracked on its own.'],
                    ],
                    ['Register the item with its dimensions and pricing method.', 'Enter the purchase in a foreign currency with its landed costs.', 'Sell, and that exact piece leaves stock.', 'See customer accounts and profit in the reports.'],
                ],
            ],
            'automobile' => [
                'features' => ['inventory', 'sales', 'purchases', 'cash-and-bank'],
                'fa' => [
                    "برای موتر و لوازم یدکی هر موتر یا قطعه می‌تواند جداگانه ثبت شود و لوازم یدکی با واریانت. خرید با ارز خارجی و هزینه‌های وارداتی روی قیمت جنس می‌آید تا سود واقعی معلوم باشد.",
                    [
                        ['هر موتر یا قطعه', 'ثبت و ردیابی جداگانه.'],
                        ['لوازم یدکی', 'واریانت‌ها برای مدل‌ها و انواع.'],
                        ['هزینه‌های وارداتی', 'کرایه و گمرک روی قیمت.'],
                        ['حساب مشتریان', 'فروش نقد و نسیه.'],
                    ],
                    ['موتر یا لوازم را ثبت می‌کنید.', 'هزینه‌های وارداتی را اضافه می‌کنید.', 'می‌فروشید.', 'حساب مشتری و سود را می‌بینید.'],
                ],
                'en' => [
                    "For cars and spare parts, each vehicle or part can be recorded individually, and spare parts with variants. Foreign-currency purchases and landed costs are brought into the item’s price so the real profit is clear.",
                    [
                        ['Each vehicle or part', 'Recorded and tracked individually.'],
                        ['Spare parts', 'Variants for models and types.'],
                        ['Landed costs', 'Freight and customs on the price.'],
                        ['Customer accounts', 'Cash and credit sales.'],
                    ],
                    ['Register the vehicle or parts.', 'Add the landed costs.', 'Sell.', 'See the customer account and the profit.'],
                ],
            ],

            'book' => [
                'features' => ['inventory', 'sales', 'purchases', 'accounting'],
                'fa' => [
                    "برای کتاب‌فروشی و نشر، کتاب‌ها با کد و دسته‌بندی ثبت می‌شوند و می‌توانند واریانت داشته باشند (مثلاً چاپ یا جلد). فروش، خرید و برگشت و حساب ناشران و مشتریان در یک جاست.",
                    [
                        ['کد و دسته‌بندی', 'هر کتاب کد و دسته خودش را دارد.'],
                        ['واریانت‌ها', 'چاپ، جلد یا نوع.'],
                        ['فروش و برگشت', 'برگشت کتاب‌ها از دکان‌داران.'],
                        ['حساب ناشران', 'بدهی و پرداخت به ناشران.'],
                    ],
                    ['کتاب‌ها را ثبت می‌کنید.', 'از ناشران می‌خرید.', 'می‌فروشید و برگشت می‌گیرید.', 'حساب ناشران را پرداخت می‌کنید.'],
                ],
                'en' => [
                    "For bookshops and publishers, books are recorded with a code and category and can have variants (for example edition or binding). Sales, purchases and returns and the accounts of publishers and customers are in one place.",
                    [
                        ['Code and category', 'Each book has its own code and category.'],
                        ['Variants', 'Edition, binding or type.'],
                        ['Sales and returns', 'Returns of books from retailers.'],
                        ['Publisher accounts', 'What you owe publishers and your payments to them.'],
                    ],
                    ['Register the books.', 'Buy from publishers.', 'Sell and take returns.', 'Pay the publishers’ accounts.'],
                ],
            ],

            'manufacturing' => [
                'features' => ['inventory', 'purchases', 'sales', 'expenses', 'hr'],
                'fa' => [
                    "در تولیدی، مواد خام و محصول نهایی در گدام‌های مختلف‌اند و تولید بچ یا لات دارد. نکست‌بوک لات را نگه می‌دارد، گدام‌ها را جدا می‌بیند و مصارف و معاش کارگران را در همان سیستم ثبت می‌کند.",
                    [
                        ['لات و بچ', 'هر لات تولید ردیابی می‌شود.'],
                        ['چند گدام', 'گدام مواد خام و گدام محصول جدا.'],
                        ['مصارف', 'مصارف تولید با دسته‌بندی و مراکز هزینه.'],
                        ['معاش کارگران', 'حاضری و معاش در همان سیستم.'],
                    ],
                    ['مواد را می‌خرید و ثبت می‌کنید.', 'لات‌های تولید را ثبت می‌کنید.', 'محصول را می‌فروشید.', 'مصارف و معاش را کنترول می‌کنید.'],
                ],
                'en' => [
                    "In manufacturing, raw materials and finished goods sit in different warehouses and production has batches or lots. NextBook tracks the lot, sees the warehouses separately and records expenses and workers’ payroll in the same system.",
                    [
                        ['Lots and batches', 'Each production lot is tracked.'],
                        ['Several warehouses', 'A raw-material warehouse and a product warehouse, kept apart.'],
                        ['Expenses', 'Production costs with categories and cost centers.'],
                        ['Workers’ payroll', 'Attendance and payroll in the same system.'],
                    ],
                    ['Buy and record materials.', 'Record the production lots.', 'Sell the product.', 'Check expenses and payroll.'],
                ],
            ],

            'pharma_manufacturing' => [
                'features' => ['inventory', 'purchases', 'sales', 'hr', 'reports'],
                'fa' => [
                    "تولید دوا به بچ، انقضا و مدت ماندگاری دقیق نیاز دارد. نکست‌بوک هم بچ و انقضا را نگه می‌دارد، هم مدت ماندگاری و حداقل ماندگاری باقی‌مانده هنگام تحویل و ناحیه نگهداری را. مواد، محصول و کارمندان در یک سیستم‌اند.",
                    [
                        ['بچ و انقضا', 'ردیابی کامل هر بچ.'],
                        ['ماندگاری', 'مدت ماندگاری و حداقل ماندگاری باقی‌مانده.'],
                        ['ناحیه نگهداری', 'محل نگهداری هر جنس.'],
                        ['منابع بشری', 'کارمندان، حاضری و معاش.'],
                    ],
                    ['مواد را می‌خرید با بچ و انقضا.', 'محصول را با بچ ثبت می‌کنید.', 'می‌فروشید و بچ مشخص را انتخاب می‌کنید.', 'گزارش انقضا و موجودی را می‌بینید.'],
                ],
                'en' => [
                    "Pharmaceutical manufacturing needs precise batch, expiry and shelf-life tracking. NextBook keeps batch and expiry, shelf life and the minimum remaining shelf life at delivery, and the storage zone. Materials, products and staff are in one system.",
                    [
                        ['Batch and expiry', 'Full tracking of every batch.'],
                        ['Shelf life', 'Shelf life and minimum remaining shelf life.'],
                        ['Storage zone', 'Where each item is kept.'],
                        ['HR', 'Employees, attendance and payroll.'],
                    ],
                    ['Buy materials with batch and expiry.', 'Record the product by batch.', 'Sell, choosing the exact batch.', 'See the expiry and stock reports.'],
                ],
            ],

            'hotel' => [
                'features' => ['expenses', 'hr', 'accounting', 'reports', 'multi-branch'],
                'fa' => [
                    "برای هوتل، نکست‌بوک بخش مالی و اداری را اداره می‌کند: مصارف با دسته‌بندی و مراکز هزینه، کارمندان با حاضری و معاش، حسابداری و گزارش‌ها، و چند شعبه. (نکست‌بوک فعلاً سیستم رزرو اتاق ندارد.)",
                    [
                        ['مصارف', 'مصارف روزانه با دسته‌بندی و مراکز هزینه.'],
                        ['کارمندان', 'حاضری، رخصتی و معاش.'],
                        ['حسابداری', 'دفتر حساب و گزارش‌های مالی.'],
                        ['چند شعبه', 'اگر چند هوتل دارید، مقایسه کنید.'],
                    ],
                    ['مصارف را ثبت می‌کنید.', 'حاضری و معاش کارمندان را اداره می‌کنید.', 'قیدها خودکار در دفتر می‌نشینند.', 'گزارش مالی را می‌بینید.'],
                ],
                'en' => [
                    "For a hotel, NextBook handles the finance and administration: expenses with categories and cost centers, staff with attendance and payroll, accounting and reports, and several branches. (NextBook does not have a room booking system at the moment.)",
                    [
                        ['Expenses', 'Daily expenses with categories and cost centers.'],
                        ['Staff', 'Attendance, leave and payroll.'],
                        ['Accounting', 'The ledger and financial reports.'],
                        ['Several branches', 'Compare them if you run more than one hotel.'],
                    ],
                    ['Record expenses.', 'Run staff attendance and payroll.', 'Entries land in the ledger on their own.', 'View the financial reports.'],
                ],
            ],

            'accounting' => [
                'features' => ['accounting', 'cash-and-bank', 'multi-branch', 'reports'],
                'fa' => [
                    "برای دفتری که حساب چند مشتری را نگه می‌دارد، هر مشتری یک شرکت جدا با داده جدا است. قید روزنامچه، ووچر، رسید و پرداخت، بیلانس افتتاحیه و دوره مالی در اختیار شماست و گزارش‌ها به Excel و PDF می‌روند.",
                    [
                        ['چند شرکت', 'هر مشتری یک شرکت با داده جدا.'],
                        ['قید و ووچر', 'قید روزنامچه، ووچر، رسید و پرداخت.'],
                        ['دوره مالی', 'باز و بسته کردن دوره و بیلانس افتتاحیه.'],
                        ['گزارش‌ها', 'گزارش‌های مالی به Excel و PDF.'],
                    ],
                    ['شرکت مشتری را اضافه می‌کنید.', 'بیلانس افتتاحیه را وارد می‌کنید.', 'قیدها و اسناد را ثبت می‌کنید.', 'گزارش را به مشتری می‌دهید.'],
                ],
                'en' => [
                    "For an office that keeps the books of several clients, each client is a separate company with separate data. Journal entries, vouchers, receipts and payments, opening balances and financial periods are yours, and reports go to Excel and PDF.",
                    [
                        ['Several companies', 'Each client is a company with separate data.'],
                        ['Entries and vouchers', 'Journal entries, vouchers, receipts and payments.'],
                        ['Financial periods', 'Open and close periods, and opening balances.'],
                        ['Reports', 'Financial reports to Excel and PDF.'],
                    ],
                    ['Add the client’s company.', 'Enter the opening balances.', 'Record entries and documents.', 'Hand the report to the client.'],
                ],
            ],
        ];
    }
}
