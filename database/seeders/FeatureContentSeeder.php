<?php

namespace Database\Seeders;

use App\Models\Feature;
use Illuminate\Database\Seeder;

/**
 * The long-form text of the feature pages: an overview, titled highlights and a walk
 * through a working day. Everything here describes what the ERP does today; the user
 * manual chapters it is drawn from are the guides linked at the bottom of each page.
 */
class FeatureContentSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->content() as $slug => $row) {
            $feature = Feature::where('slug', $slug)->first();

            if (! $feature) {
                continue;
            }

            $attributes = ['help_slug' => $row['help'] ?? null];

            foreach (['fa', 'en'] as $locale) {
                $attributes['overview'][$locale] = $row[$locale]['overview'];
                $attributes['highlights'][$locale] = array_map(
                    fn (array $h) => ['title' => $h[0], 'text' => $h[1]],
                    $row[$locale]['highlights'] ?? [],
                );
                $attributes['steps'][$locale] = $row[$locale]['steps'] ?? [];
            }

            $feature->update($attributes);
        }
    }

    private function content(): array
    {
        return [

            'inventory' => [
                'help' => 'inventory',
                'fa' => [
                    'overview' => "موجودی گدام شما فقط وقتی به درد می‌خورد که درست باشد. در نکست‌بوک هر خرید، فروش، انتقال و تعدیل موجودی را خودکار تغییر می‌دهد، پس عدد روی صفحه همان چیزی است که در گدام دارید.\n\nهر جنس می‌تواند واریانت داشته باشد: مثلاً یک پیراهن در سه سایز و چهار رنگ، یا یک موبایل با ظرفیت‌های مختلف. هر واریانت کد، بارکد، قیمت و موجودی خودش را دارد. برای دواخانه و مواد غذایی، بچ و تاریخ انقضا هم ثبت می‌شود.",
                    'highlights' => [
                        ['واریانت‌ها', 'سایز، رنگ، ظرفیت یا هر ویژگی دیگر را یک بار تعریف کنید. هر ترکیب کد، بارکد، قیمت و حداقل موجودی خودش را دارد.'],
                        ['بچ، انقضا و سریال', 'اجناس دواخانه و بقالی را با بچ و تاریخ انقضا، و موبایل و کمپیوتر را با سریال یا IMEI نگه دارید. هنگام فروش بچ مورد نظر را انتخاب می‌کنید.'],
                        ['چند گدام و انتقال', 'جنس را بین گدام‌ها منتقل کنید و موجودی هر گدام را جداگانه ببینید. انتقال هر دو طرف را خودکار به‌روز می‌کند.'],
                        ['تعدیل موجودی', 'کمبود، ضایعات یا نتیجه شمارش را با یک سند تعدیل ثبت کنید. هر تغییر در تاریخچه ورود و خروج جنس می‌ماند.'],
                        ['قیمت تمام‌شده FIFO', 'اجناس به ترتیب ورود حساب می‌شوند، پس سود هر فروش بر اساس قیمت واقعی همان جنس محاسبه می‌شود.'],
                        ['قیمت‌گذاری و چاپ بارکد', 'قیمت چند جنس را یکجا تغییر دهید و برچسب بارکد را مستقیم از سیستم چاپ کنید.'],
                    ],
                    'steps' => [
                        'جنس را با دسته‌بندی، واحد و واریانت‌هایش ثبت می‌کنید، یا موجودی افتتاحیه را سریع وارد می‌کنید.',
                        'با هر خرید، موجودی زیاد می‌شود و با هر فروش کم.',
                        'وقتی جنسی کم شد، سیستم آن را در فهرست «کم‌موجودی» نشان می‌دهد.',
                        'برای جابه‌جایی بین گدام‌ها از انتقال، و برای اصلاح شمارش از تعدیل استفاده می‌کنید.',
                    ],
                ],
                'en' => [
                    'overview' => "Stock numbers are only useful when they are right. In NextBook every purchase, sale, transfer and adjustment changes stock on its own, so the number on the screen is what is in your warehouse.\n\nEvery item can have variants: a shirt in three sizes and four colours, or a phone in several capacities. Each variant has its own code, barcode, price and stock. For pharmacies and food, batch and expiry date are recorded too.",
                    'highlights' => [
                        ['Variants', 'Define size, colour, capacity or any other attribute once. Every combination has its own code, barcode, price and minimum stock.'],
                        ['Batch, expiry and serial numbers', 'Keep medicines and groceries by batch and expiry date, and phones and computers by serial number or IMEI. You choose the batch when you sell.'],
                        ['Several warehouses and transfers', 'Move items between warehouses and see the stock of each one. A transfer updates both sides on its own.'],
                        ['Stock adjustments', 'Record shortages, waste or the result of a stock count with an adjustment document. Every change stays in the item\'s in/out history.'],
                        ['FIFO costing', 'Items are costed in the order they arrived, so the profit on each sale is based on the real cost of that item.'],
                        ['Bulk pricing and barcode printing', 'Change the prices of many items at once and print barcode labels straight from the system.'],
                    ],
                    'steps' => [
                        'Register the item with its category, unit and variants, or enter opening stock in bulk.',
                        'Each purchase raises stock and each sale lowers it.',
                        'When an item runs low, it shows up in the low-stock list.',
                        'Use transfers to move goods between warehouses and adjustments to correct a count.',
                    ],
                ],
            ],

            'sales' => [
                'help' => 'sales',
                'fa' => [
                    'overview' => "فروش در نکست‌بوک یک جریان روشن دارد: پیش‌فاکتور، سفارش، فاکتور و در صورت لزوم برگشت. هر سند جای خودش را دارد و هر کدام فقط همان‌قدر در موجودی و حساب اثر می‌گذارد که باید.\n\nوقتی فاکتور را ثبت می‌کنید، موجودی از گدام کم می‌شود و طلب مشتری و درآمد در دفتر حساب نوشته می‌شود، بدون اینکه چیزی را دوباره وارد کنید. فاکتور ثبت‌شده قفل است؛ اشتباه را با برگشت اصلاح می‌کنید و رد آن در سیستم می‌ماند.",
                    'highlights' => [
                        ['از پیش‌فاکتور تا برگشت', 'پیش‌فاکتور تعهدی نمی‌سازد، سفارش تعهد فروش را ثبت می‌کند و فاکتور جنس را تحویل می‌دهد. برگشت فروش موجودی و حساب را به حالت قبل برمی‌گرداند.'],
                        ['مسوده و ثبت', 'فاکتور را اول به‌صورت مسوده ذخیره کنید تا موجودی آن رزرو شود، بعد وقتی همه چیز درست بود ثبتش کنید.'],
                        ['فروش سریع با بارکد', 'جنس را با اسکن بارکد یا جستجو اضافه کنید. میانبرهای صفحه‌کلید (مثل Ctrl+S برای ذخیره) کار را سریع‌تر می‌کند.'],
                        ['تخفیف و مصارف اضافی', 'تخفیف را روی هر ردیف یا روی کل فاکتور بدهید، یا قوانین تخفیف را یک بار تعریف کنید. کرایه حمل را به مشتری اضافه کنید.'],
                        ['پول همان لحظه', 'اگر مشتری همان موقع پول می‌دهد، مبلغ را در فاکتور وارد کنید و رسید خودکار ساخته می‌شود. باقی‌مانده در حساب مشتری می‌ماند.'],
                        ['چاپ با قالب شرکت', 'فاکتور را با قالب و نشان شرکت خودتان چاپ یا PDF کنید.'],
                    ],
                    'steps' => [
                        'مشتری را انتخاب می‌کنید و بلانس قبلی او را می‌بینید.',
                        'اجناس را با بارکد یا جستجو اضافه می‌کنید؛ برای اجناس بچ‌دار، بچ را انتخاب می‌کنید.',
                        'تخفیف و مبلغ دریافتی را می‌نویسید و ثبت می‌کنید.',
                        'فاکتور را چاپ می‌کنید. موجودی، طلب و درآمد خودکار نوشته شده است.',
                    ],
                ],
                'en' => [
                    'overview' => "Selling in NextBook follows a clear flow: quotation, order, invoice and, when needed, a return. Each document has its place, and each one affects stock and the books only as much as it should.\n\nWhen you post an invoice, stock leaves the warehouse and the customer receivable and the income are written to the ledger, with nothing to re-enter. A posted invoice is locked; you correct a mistake with a return, and the trail stays in the system.",
                    'highlights' => [
                        ['From quotation to return', 'A quotation commits nothing, an order records the commitment to sell, and an invoice delivers the goods. A sales return puts stock and the account back.'],
                        ['Draft and post', 'Save an invoice as a draft first, which reserves its stock, then post it once everything is right.'],
                        ['Fast selling with barcodes', 'Add items by scanning a barcode or searching. Keyboard shortcuts (such as Ctrl+S to save) speed things up.'],
                        ['Discounts and extra charges', 'Give a discount on a line or on the whole invoice, or define discount rules once. Add freight to the customer\'s bill.'],
                        ['Money taken on the spot', 'If the customer pays at once, enter the amount on the invoice and a receipt is created automatically. The rest stays on the customer\'s account.'],
                        ['Printing with your company template', 'Print the invoice, or save it as PDF, with your own template and logo.'],
                    ],
                    'steps' => [
                        'Choose the customer and see their previous balance.',
                        'Add items by barcode or search; for batch items, pick the batch.',
                        'Enter any discount and the amount received, then post.',
                        'Print the invoice. Stock, receivable and income are already written.',
                    ],
                ],
            ],

            'purchases' => [
                'help' => 'purchases',
                'fa' => [
                    'overview' => "خرید هم مثل فروش یک مسیر مشخص دارد: سفارش خرید، بیل خرید و برگشت. با ثبت بیل خرید، موجودی زیاد می‌شود و بدهی به تامین‌کننده در حسابش نوشته می‌شود.\n\nاگر جنس را وارد می‌کنید، خرج کرایه، گمرک و بقیه مصارف را به قیمت جنس اضافه کنید تا قیمت تمام‌شده واقعی باشد و سود فروش درست حساب شود.",
                    'highlights' => [
                        ['سفارش خرید و بیل خرید', 'اول سفارش بدهید، بعد وقتی جنس رسید بیل را ثبت کنید. موجودی همان موقع زیاد می‌شود.'],
                        ['حساب هر تامین‌کننده', 'ببینید به هر تامین‌کننده چه مقدار بدهکار هستید و با کدام بیل‌ها مطابقت دارد.'],
                        ['هزینه‌های وارداتی', 'کرایه، گمرک و مصارف وارد کردن جنس به قیمت تمام‌شده اضافه می‌شود، پس سود فروش واقعی محاسبه می‌شود.'],
                        ['برگشت خرید', 'اگر جنسی را پس می‌دهید، موجودی کم و حساب تامین‌کننده تصحیح می‌شود.'],
                        ['خرید با ارز خارجی', 'مبلغ بیل هم به ارز خارجی و هم به ارز اصلی با نرخ همان روز ثبت می‌شود.'],
                    ],
                    'steps' => [
                        'تامین‌کننده را انتخاب می‌کنید و سفارش خرید می‌دهید.',
                        'وقتی جنس رسید، بیل خرید را با قیمت‌ها وارد و ثبت می‌کنید.',
                        'مصارف وارداتی را اضافه می‌کنید تا قیمت تمام‌شده درست شود.',
                        'وقتی پول می‌دهید، پرداخت را به همان بیل‌ها وصل می‌کنید.',
                    ],
                ],
                'en' => [
                    'overview' => "Buying has a clear path just like selling: purchase order, purchase bill and return. Posting a bill raises stock and writes the payable to the supplier on their account.\n\nIf you import goods, add freight, customs and other costs to the item's price so the cost is the real one and the profit on each sale is right.",
                    'highlights' => [
                        ['Purchase orders and bills', 'Place an order first, then post the bill when the goods arrive. Stock goes up at that moment.'],
                        ['Each supplier\'s account', 'See how much you owe each supplier and which bills it covers.'],
                        ['Landed costs', 'Freight, customs and import costs are added to the cost price, so the profit on a sale is calculated on the real cost.'],
                        ['Purchase returns', 'When you send goods back, stock goes down and the supplier\'s account is corrected.'],
                        ['Buying in a foreign currency', 'The bill is kept in the foreign currency and in the home currency at that day\'s rate.'],
                    ],
                    'steps' => [
                        'Choose the supplier and place a purchase order.',
                        'When the goods arrive, enter the bill with its prices and post it.',
                        'Add the import costs so the cost price is right.',
                        'When you pay, match the payment to those bills.',
                    ],
                ],
            ],

            'accounting' => [
                'help' => 'accounting',
                'fa' => [
                    'overview' => "حسابداری نکست‌بوک دوطرفه است: هر رویداد مالی، بدهکار و بستانکار مساوی در دفتر حساب دارد. بیشتر قیدها خودکار از فروش، خرید و پول نقد می‌آیند، پس لازم نیست جدا ثبت کنید.\n\nبرای چیزهایی که هیچ بخش دیگری ثبت نمی‌کند، مثل استهلاک ماهانه، قید روزنامچه دارید. چارت حساب آماده است و می‌توانید حساب اضافه کنید.",
                    'highlights' => [
                        ['قید خودکار', 'فروش، خرید، رسید، پرداخت، مصارف و معاش هر کدام قید خودش را در دفتر می‌نویسد.'],
                        ['چارت حساب آماده', 'صندوق، بانک، حساب‌های دریافتنی و پرداختنی، موجودی و درآمد از اول ساخته شده‌اند.'],
                        ['قید روزنامچه', 'برای ثبت‌های دستی مثل استهلاک یا اصلاح، با کنترول بدهکار و بستانکار.'],
                        ['بیلانس افتتاحیه', 'آنچه از قبل طلب یا بدهی دارید را در روز شروع وارد کنید تا گزارش‌ها درست شروع شوند.'],
                        ['تهاتر', 'اگر یک طرف هم مشتری شماست و هم تامین‌کننده، طلب و بدهی را با یک سند در برابر هم بزنید.'],
                        ['دوره مالی و گزارش', 'دوره مالی را باز و ببندید و گزارش‌های مالی را ببینید و به Excel یا PDF بگیرید.'],
                    ],
                    'steps' => [
                        'بیلانس افتتاحیه مشتریان، تامین‌کننده‌ها و حساب‌ها را وارد می‌کنید.',
                        'معاملات روزانه خودکار در دفتر می‌نشینند.',
                        'برای موارد دستی قید روزنامچه می‌زنید.',
                        'پایان ماه یا سال، گزارش‌ها را کنترول می‌کنید و دوره را می‌بندید.',
                    ],
                ],
                'en' => [
                    'overview' => "NextBook's accounting is double-entry: every financial event has equal debits and credits in the ledger. Most entries come on their own from sales, purchases and cash, so you do not record them twice.\n\nFor things no other module records, such as monthly depreciation, there are journal entries. A chart of accounts is ready, and you can add accounts.",
                    'highlights' => [
                        ['Automatic entries', 'Sales, purchases, receipts, payments, expenses and payroll each write their own entry to the ledger.'],
                        ['A ready chart of accounts', 'Cash, bank, receivables, payables, stock and income are set up from the start.'],
                        ['Journal entries', 'For manual postings such as depreciation or corrections, with debits and credits checked.'],
                        ['Opening balances', 'Enter what you already owe or are owed on the start date so the reports begin correctly.'],
                        ['Contra settlement', 'If a party is both your customer and your supplier, offset what they owe against what you owe with one document.'],
                        ['Financial periods and reports', 'Open and close periods, view the financial reports and export them to Excel or PDF.'],
                    ],
                    'steps' => [
                        'Enter the opening balances of customers, suppliers and accounts.',
                        'Daily transactions land in the ledger on their own.',
                        'Use journal entries for manual items.',
                        'At month or year end, check the reports and close the period.',
                    ],
                ],
            ],

            'cash-and-bank' => [
                'help' => 'cash',
                'fa' => [
                    'overview' => "هر پولی که می‌آید یا می‌رود، یک رسید یا پرداخت دارد. رسید برای پولی است که از مشتری می‌گیرید و پرداخت برای پولی که به تامین‌کننده می‌دهید. هر دو هم حساب طرف و هم صندوق یا بانک را به‌روز می‌کنند.\n\nپول را می‌توانید به بیل‌های مشخص وصل کنید تا معلوم باشد کدام بیل پرداخت شده و کدام باقی است. اگر با چند ارز کار می‌کنید، نرخ تبدیل هر معامله ثبت می‌شود.",
                    'highlights' => [
                        ['رسید و پرداخت', 'پول گرفته‌شده از مشتری و پول داده‌شده به تامین‌کننده، با صندوق یا حساب بانکی انتخابی.'],
                        ['وصل کردن به بیل‌ها', 'پول را به بیل‌های مشخص تخصیص دهید تا بیل‌های باز و بسته معلوم باشد.'],
                        ['چند حساب بانکی و صندوق', 'موجودی هر حساب را جداگانه ببینید و بین آن‌ها پول منتقل کنید.'],
                        ['چند ارز', 'مبلغ به ارز اصلی و ارز خارجی با نرخ تبدیل همان معامله ثبت می‌شود.'],
                    ],
                    'steps' => [
                        'مشتری یا تامین‌کننده و حساب (صندوق یا بانک) را انتخاب می‌کنید.',
                        'مبلغ و ارز را می‌نویسید.',
                        'اگر لازم است، آن را به بیل‌های مشخص وصل می‌کنید.',
                        'ثبت می‌کنید؛ حساب طرف و صندوق هر دو به‌روز می‌شوند.',
                    ],
                ],
                'en' => [
                    'overview' => "Every payment in or out has a receipt or a payment. A receipt is for money you take from a customer and a payment is for money you give a supplier. Both update the party's account and the cash or bank account.\n\nYou can match money to specific bills so it is clear which ones are paid and which are still open. If you work in several currencies, the exchange rate of each transaction is recorded.",
                    'highlights' => [
                        ['Receipts and payments', 'Money taken from customers and paid to suppliers, with the cash box or bank account you choose.'],
                        ['Match to bills', 'Allocate money to specific bills so open and settled bills are clear.'],
                        ['Several bank accounts and cash boxes', 'See the balance of each one and move money between them.'],
                        ['Several currencies', 'The amount is recorded in the home and the foreign currency at that transaction\'s rate.'],
                    ],
                    'steps' => [
                        'Choose the customer or supplier and the account (cash or bank).',
                        'Enter the amount and currency.',
                        'If needed, match it to specific bills.',
                        'Post it; the party\'s account and the cash account are both updated.',
                    ],
                ],
            ],

            'expenses' => [
                'help' => 'expenses',
                'fa' => [
                    'overview' => "کرایه دکان، تیل، برق، معاش و بقیه مصارف روزانه را با دسته‌بندی ثبت کنید تا در پایان ماه معلوم باشد پول به کجا رفته است. مصرف می‌تواند چند ردیف داشته باشد و می‌تواند بدهی به یک طرف باشد که بعداً پرداخت می‌شود.\n\nبرداشت شریک جدا از مصرف ثبت می‌شود، چون مصرف نیست و روی سود شرکت اثر نمی‌گذارد.",
                    'highlights' => [
                        ['دسته‌بندی مصارف', 'هر مصرف در یک دسته می‌نشیند و گزارش مصارف به تفکیک دسته آماده است.'],
                        ['مصرف چندردیفه', 'یک سند مصرف می‌تواند چند قلم داشته باشد.'],
                        ['مصرف مشترک بین شعبه‌ها', 'کرایه یا مصرفی که برای چند شعبه است، بین آن‌ها تقسیم می‌شود. مراکز هزینه هم دارید.'],
                        ['شرکا و برداشت‌ها', 'برداشت هر شریک را جدا ثبت کنید تا سود شرکت را خراب نکند.'],
                    ],
                    'steps' => [
                        'دسته مصرف را انتخاب می‌کنید.',
                        'مبلغ و حسابی که از آن پرداخت می‌شود را می‌نویسید.',
                        'اگر مصرف چند شعبه دارد، تقسیم می‌کنید.',
                        'ثبت می‌کنید؛ قید حسابداری خودکار نوشته می‌شود.',
                    ],
                ],
                'en' => [
                    'overview' => "Record shop rent, fuel, electricity and other daily costs with categories, so at month end it is clear where the money went. An expense can have several lines, and it can be a payable to a party that you pay later.\n\nOwner drawings are recorded separately, because they are not an expense and do not affect the company's profit.",
                    'highlights' => [
                        ['Expense categories', 'Each expense goes into a category, and expense reports by category are ready.'],
                        ['Multi-line expenses', 'One expense document can hold several items.'],
                        ['Shared expenses across branches', 'Rent or a cost that belongs to several branches is split between them. Cost centers are available too.'],
                        ['Owners and drawings', 'Record each owner\'s drawings separately so they do not distort the company\'s profit.'],
                    ],
                    'steps' => [
                        'Choose the expense category.',
                        'Enter the amount and the account it is paid from.',
                        'If it covers several branches, split it.',
                        'Post it; the accounting entry is written on its own.',
                    ],
                ],
            ],

            'hr' => [
                'help' => 'employees',
                'fa' => [
                    'overview' => "منابع بشری نکست‌بوک از ثبت کارمند تا پرداخت معاش را در یک زنجیره نگه می‌دارد. حاضری، رخصتی و معاش هر کدام به پرونده همان کارمند وصل است، پس معاش بر اساس حاضری و رخصتی واقعی حساب می‌شود.\n\nمعاش محاسبه‌شده در دفتر حساب ثبت می‌شود و پرداخت آن هم مشخص است. کارمند خودش هم می‌تواند از پورتال خودخدمت رخصتی بخواهد و معاش خود را ببیند.",
                    'highlights' => [
                        ['کارمندان و قراردادها', 'پرونده کامل کارمند: مشخصات، وظیفه، قرارداد و اسناد، با ساختار سازمانی (دیپارتمنت و وظیفه).'],
                        ['حاضری', 'حاضری را با لیست روزانه، با وارد کردن فایل دستگاه حاضری یا خود کارمند (خودخدمت) ثبت کنید. شفت‌ها و تعطیلات در نظر گرفته می‌شوند و حاضری قابل قفل است.'],
                        ['رخصتی', 'نوع‌های رخصتی، سهمیه سالانه، درخواست و تایید. رخصتی تاییدشده خودکار در حاضری و معاش می‌آید.'],
                        ['معاش و مالیات', 'اجزای معاش، ساختار معاش، جدول مالیه و محاسبه معاش ماهانه. نتیجه در دفتر حساب ثبت می‌شود و در صورت لزوم برگشت‌پذیر است.'],
                        ['قرضه و پیش‌پرداخت', 'قرضه کارمند ثبت و از معاش‌های بعدی کم می‌شود.'],
                        ['استخدام', 'آگهی کار، درخواست‌ها، مراحل غربال و مصاحبه، و تبدیل کاندید به کارمند.'],
                    ],
                    'steps' => [
                        'کارمند را با قرارداد و ساختار معاشش ثبت می‌کنید.',
                        'هر روز حاضری ثبت می‌شود و رخصتی‌ها تایید می‌شوند.',
                        'آخر ماه معاش محاسبه می‌شود، مالیه کم می‌شود و قید حسابداری نوشته می‌شود.',
                        'معاش پرداخت می‌شود و روی پرونده کارمند می‌ماند.',
                    ],
                ],
                'en' => [
                    'overview' => "NextBook's HR keeps the chain from hiring an employee to paying their salary in one place. Attendance, leave and payroll each attach to that employee's record, so pay is worked out from real attendance and leave.\n\nThe calculated payroll is posted to the ledger and its payment is tracked. Employees can ask for leave and see their own pay from a self-service portal.",
                    'highlights' => [
                        ['Employees and contracts', 'A full employee record: personal details, job, contract and documents, with an organisation structure (departments and designations).'],
                        ['Attendance', 'Record attendance with a daily roster, by importing the attendance device file, or let employees do it themselves (self-service). Shifts and holidays are taken into account, and attendance can be locked.'],
                        ['Leave', 'Leave types, yearly entitlement, requests and approval. Approved leave appears in attendance and payroll on its own.'],
                        ['Payroll and tax', 'Salary components, salary structures, the tax table and the monthly run. The result is posted to the ledger and can be reversed if needed.'],
                        ['Loans and advances', 'Record an employee loan and have it deducted from later salaries.'],
                        ['Recruitment', 'Job openings, applications, screening and interviews, and turning a candidate into an employee.'],
                    ],
                    'steps' => [
                        'Register the employee with their contract and salary structure.',
                        'Attendance is recorded each day and leave is approved.',
                        'At month end payroll is calculated, tax is deducted and the accounting entry is written.',
                        'The salary is paid and stays on the employee\'s record.',
                    ],
                ],
            ],

            'multi-branch' => [
                'help' => 'administration',
                'fa' => [
                    'overview' => "چه یک دکان دارید چه ده شعبه و چند شرکت، ساختار همان است: شرکت، شعبه و گدام. هر کاربر در یک شعبه کار می‌کند و همان‌جا را می‌بیند.\n\nداده هر شرکت از شرکت دیگر جداست و این جداسازی در سطح دیتابیس هم اعمال می‌شود. ماژول‌هایی که نیاز ندارید را برای هر شرکت خاموش کنید؛ داده آن‌ها نمی‌رود و با روشن کردن دوباره برمی‌گردد.",
                    'highlights' => [
                        ['شرکت، شعبه، گدام', 'هر شعبه گدام‌های خودش را دارد و تنظیمات کلی (تقویم، ارز پایه، قالب فاکتور) در سطح شرکت است.'],
                        ['نقش‌ها و دسترسی‌ها', 'برای هر کاربر مشخص کنید چه چیزی را ببیند و چه چیزی را تغییر بدهد.'],
                        ['مقایسه شعبه‌ها', 'فروش و مصارف شعبه‌ها را کنار هم ببینید.'],
                        ['روشن و خاموش کردن ماژول‌ها', 'منابع بشری، مصارف، قوانین تخفیف و دیگر ماژول‌ها را برای هر شرکت جداگانه روشن یا خاموش کنید.'],
                        ['داده جدا', 'داده شرکت‌ها در دیتابیس هم جدا نگه داشته می‌شود، نه فقط در برنامه.'],
                    ],
                    'steps' => [
                        'شرکت و شعبه اصلی را تنظیم می‌کنید.',
                        'گدام‌ها، ارزها و واحدها را تعریف می‌کنید.',
                        'کاربران را با نقش مناسب اضافه می‌کنید.',
                        'هر وقت شعبه تازه خواستید، اضافه می‌کنید و با شعبه‌های دیگر مقایسه می‌کنید.',
                    ],
                ],
                'en' => [
                    'overview' => "Whether you have one shop or ten branches and several companies, the structure is the same: company, branch and warehouse. Each user works in one branch and sees that branch.\n\nOne company's data is separate from another's, and the separation is also enforced in the database. Switch off the modules you do not need for each company; their data is not lost and comes back when you switch them on again.",
                    'highlights' => [
                        ['Company, branch, warehouse', 'Each branch has its own warehouses, and company-wide settings (calendar, home currency, invoice template) sit at company level.'],
                        ['Roles and permissions', 'Decide for each user what they can see and what they can change.'],
                        ['Branch comparison', 'See the sales and expenses of your branches side by side.'],
                        ['Switch modules on or off', 'Turn HR, expenses, discount rules and other modules on or off for each company.'],
                        ['Separate data', 'Companies\' data is kept apart in the database itself, not only in the application.'],
                    ],
                    'steps' => [
                        'Set up the company and the main branch.',
                        'Define warehouses, currencies and units.',
                        'Add users with the right role.',
                        'Whenever you open a new branch, add it and compare it with the others.',
                    ],
                ],
            ],

            'reports' => [
                'help' => 'reports',
                'fa' => [
                    'overview' => "داشبورد وضعیت روز را نشان می‌دهد: موجودی نقد و بانک، طلب‌ها و بدهی‌ها، فروش و خرید امروز و مقایسه با دیروز، گردش فروش و خرید، و برترین مشتری‌ها، اجناس و تامین‌کننده‌ها.\n\nبرای هر نیاز گزارش جداگانه دارید و هر گزارش فیلتر دارد و به Excel یا PDF گرفته می‌شود. لاگ فعالیت نشان می‌دهد چه کسی چه چیزی را تغییر داد و رکورد حذف‌شده را از سطل زباله برمی‌گردانید.",
                    'highlights' => [
                        ['داشبورد شعبه', 'نقد و بانک، طلب و بدهی، فروش و خرید امروز، گردش فروش در برابر خرید، و وضعیت موجودی (کم‌موجودی، بدون موجودی، نزدیک به انقضا).'],
                        ['گزارش‌های مالی و موجودی', 'گزارش‌های مالی، گزارش طرف‌ها (مشتری و تامین‌کننده) و گزارش‌های عملیاتی و موجودی، با فیلتر.'],
                        ['Excel و PDF', 'هر گزارش به Excel یا PDF صادر می‌شود.'],
                        ['لاگ فعالیت', 'چه کسی، چه وقت، چه چیزی را ایجاد، تغییر یا حذف کرد.'],
                        ['سطل زباله', 'رکوردهای حذف‌شده قابل بازگرداندن‌اند.'],
                    ],
                    'steps' => [
                        'صبح داشبورد را باز می‌کنید و وضعیت نقد، طلب و موجودی را می‌بینید.',
                        'گزارش مورد نظر را با فیلتر تاریخ و شعبه باز می‌کنید.',
                        'در صورت نیاز به Excel یا PDF می‌گیرید.',
                        'اگر چیزی عوض شد، لاگ فعالیت می‌گوید کار چه کسی بوده است.',
                    ],
                ],
                'en' => [
                    'overview' => "The dashboard shows the state of the day: cash and bank balance, receivables and payables, today's sales and purchases against yesterday, the flow of sales and purchases, and the top customers, items and suppliers.\n\nThere is a separate report for each need, every report has filters and exports to Excel or PDF. The activity log shows who changed what, and a deleted record can be restored from the trash.",
                    'highlights' => [
                        ['Branch dashboard', 'Cash and bank, receivables and payables, today\'s sales and purchases, sales against purchases over time, and the stock picture (low, out, near expiry).'],
                        ['Financial and stock reports', 'Financial reports, party reports (customers and suppliers) and operational and inventory reports, with filters.'],
                        ['Excel and PDF', 'Every report exports to Excel or PDF.'],
                        ['Activity log', 'Who created, changed or deleted what, and when.'],
                        ['Trash', 'Deleted records can be restored.'],
                    ],
                    'steps' => [
                        'In the morning open the dashboard and see cash, receivables and stock.',
                        'Open the report you need with a date and branch filter.',
                        'Export it to Excel or PDF if needed.',
                        'If something changed, the activity log says who did it.',
                    ],
                ],
            ],

            'languages' => [
                'help' => 'overview',
                'fa' => [
                    'overview' => "نکست‌بوک برای کاربر افغان ساخته شده است. صفحه‌ها، فرم‌ها، چاپ و راهنما به دری، پشتو و انگلیسی است و هر کاربر زبان خودش را انتخاب می‌کند.\n\nتقویم شمسی و میلادی هر دو هست؛ نوع تقویم را برای شرکت انتخاب می‌کنید. حالت تیره و روشن هم دارد.",
                    'highlights' => [
                        ['سه زبان', 'دری، پشتو و انگلیسی، با جهت راست‌به‌چپ درست.'],
                        ['تقویم شمسی و میلادی', 'تاریخ‌ها در همان تقویمی نشان داده می‌شوند که برای شرکت انتخاب کرده‌اید.'],
                        ['راهنما به زبان خودتان', 'راهنمای کاربر کامل به هر سه زبان است.'],
                        ['حالت تیره', 'برای کار در شب یا چشم خسته.'],
                    ],
                    'steps' => [
                        'در ورود زبان خود را انتخاب می‌کنید.',
                        'تقویم شرکت را در تنظیمات می‌گذارید.',
                        'هر کاربر می‌تواند زبان و حالت نمایش خودش را داشته باشد.',
                    ],
                ],
                'en' => [
                    'overview' => "NextBook is built for Afghan users. Pages, forms, printouts and help are in Dari, Pashto and English, and each user picks their own language.\n\nBoth the Jalali and Gregorian calendars are supported; you choose the calendar for the company. There is a dark and a light mode too.",
                    'highlights' => [
                        ['Three languages', 'Dari, Pashto and English, with correct right-to-left layout.'],
                        ['Jalali and Gregorian calendars', 'Dates appear in the calendar you chose for the company.'],
                        ['Help in your language', 'The user manual is complete in all three languages.'],
                        ['Dark mode', 'For working at night or when your eyes are tired.'],
                    ],
                    'steps' => [
                        'Choose your language when you sign in.',
                        'Set the company calendar in the settings.',
                        'Each user can keep their own language and display mode.',
                    ],
                ],
            ],

            // Not built yet: a short, honest overview only.
            'pos' => [
                'fa' => ['overview' => 'صندوق فروش برای فروش سریع در دکان در دست ساخت است و هنوز در سیستم موجود نیست. فعلاً فروش با اسکن بارکد در صفحه فاکتور فروش انجام می‌شود.'],
                'en' => ['overview' => 'A point of sale for fast counter sales is under development and is not in the system yet. For now, selling is done on the sales invoice page, with barcode scanning.'],
            ],
            'crm' => [
                'fa' => ['overview' => 'مدیریت ارتباط با مشتری در دست ساخت است و هنوز در سیستم موجود نیست.'],
                'en' => ['overview' => 'Customer relationship management is under development and is not in the system yet.'],
            ],
            'projects' => [
                'fa' => ['overview' => 'ماژول پروژه‌ها در دست ساخت است و هنوز در سیستم موجود نیست.'],
                'en' => ['overview' => 'The projects module is under development and is not in the system yet.'],
            ],
            'fixed-assets' => [
                'fa' => ['overview' => 'ماژول دارایی‌های ثابت در دست ساخت است و هنوز در سیستم موجود نیست. فعلاً استهلاک را با قید روزنامچه ثبت می‌کنید.'],
                'en' => ['overview' => 'The fixed assets module is under development and is not in the system yet. For now, record depreciation with a journal entry.'],
            ],
        ];
    }
}
