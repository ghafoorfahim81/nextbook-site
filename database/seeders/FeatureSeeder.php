<?php

namespace Database\Seeders;

use App\Models\Feature;
use Illuminate\Database\Seeder;

class FeatureSeeder extends Seeder
{
    public function run(): void
    {
        $features = [
            [
                'slug' => 'inventory', 'icon' => 'boxes', 'status' => 'available',
                'fa' => [
                    'title' => 'موجودی و گدام',
                    'summary' => 'اجناس، گدام‌ها و حرکت موجودی را دقیق نگه دارید.',
                    'points' => [
                        'اجناس با واریانت (سایز، رنگ، ظرفیت) که هر کدام SKU، بارکد و قیمت خودش را دارد',
                        'پیگیری بچ و تاریخ انقضا، و سریال یا IMEI برای اجناسی که تک‌تک ثبت می‌شوند',
                        'چند گدام و انتقال جنس بین گدام‌ها',
                        'تعدیل موجودی برای کمبود، ضایعات یا شمارش',
                        'محاسبه قیمت تمام‌شده به روش FIFO',
                        'هزینه‌های وارداتی (کرایه، گمرک) به قیمت جنس اضافه می‌شود',
                    ],
                ],
                'en' => [
                    'title' => 'Inventory and warehouses',
                    'summary' => 'Keep items, warehouses and stock movement accurate.',
                    'points' => [
                        'Items with variants (size, colour, capacity), each with its own SKU, barcode and price',
                        'Batch and expiry tracking, and serial or IMEI numbers for items recorded one by one',
                        'Several warehouses, and transfers of stock between them',
                        'Stock adjustments for shortages, waste or stock counts',
                        'FIFO cost calculation',
                        'Landed costs (freight, customs) added to the cost of the item',
                    ],
                ],
            ],
            [
                'slug' => 'sales', 'icon' => 'shopping-cart', 'status' => 'available',
                'fa' => [
                    'title' => 'فروش',
                    'summary' => 'از پیشنهاد قیمت تا بیل فروش و برگشت، همه در یک جریان.',
                    'points' => [
                        'پیشنهاد قیمت، سفارش فروش و بیل فروش',
                        'برگشت فروش',
                        'قوانین تخفیف که خودکار اعمال می‌شود',
                        'مشتری نقدی برای فروش‌های بدون نام',
                        'حساب هر مشتری: چه چیزی خریده و چه مقدار باقی است',
                        'چاپ بیل به PDF',
                    ],
                ],
                'en' => [
                    'title' => 'Sales',
                    'summary' => 'From quotation to invoice and return, in one flow.',
                    'points' => [
                        'Quotations, sales orders and sales invoices',
                        'Sales returns',
                        'Discount rules that apply automatically',
                        'A cash customer for walk-in sales',
                        'Each customer\'s account: what they bought and what is still owed',
                        'Invoices printed to PDF',
                    ],
                ],
            ],
            [
                'slug' => 'purchases', 'icon' => 'truck', 'status' => 'available',
                'fa' => [
                    'title' => 'خرید',
                    'summary' => 'سفارش خرید، بیل خرید و حساب تامین‌کننده‌ها.',
                    'points' => [
                        'سفارش خرید و بیل خرید',
                        'برگشت خرید',
                        'حساب هر تامین‌کننده و مبلغی که باید پرداخت شود',
                        'ثبت خودکار موجودی بعد از خرید',
                        'هزینه‌های وارداتی روی قیمت تمام‌شده',
                    ],
                ],
                'en' => [
                    'title' => 'Purchasing',
                    'summary' => 'Purchase orders, purchase bills and supplier accounts.',
                    'points' => [
                        'Purchase orders and purchase bills',
                        'Purchase returns',
                        'Each supplier\'s account and what is still to be paid',
                        'Stock recorded automatically after a purchase',
                        'Landed costs on the cost price',
                    ],
                ],
            ],
            [
                'slug' => 'accounting', 'icon' => 'calculator', 'status' => 'available',
                'fa' => [
                    'title' => 'حسابداری',
                    'summary' => 'حسابداری دوطرفه که با هر معامله خودکار به‌روز می‌شود.',
                    'points' => [
                        'هر رویداد مالی به‌صورت بدهکار و بستانکار در دفتر حساب ثبت می‌شود',
                        'چارت حساب آماده، با امکان اضافه کردن حساب',
                        'قید روزنامچه برای ثبت‌های دستی',
                        'ووچر، رسید و پرداخت',
                        'بیلانس افتتاحیه برای مشتریان و تامین‌کننده‌ها',
                        'دوره مالی و بستن دوره',
                        'تهاتر برای طرفی که هم مشتری است و هم تامین‌کننده',
                    ],
                ],
                'en' => [
                    'title' => 'Accounting',
                    'summary' => 'Double-entry accounting that updates itself with every transaction.',
                    'points' => [
                        'Every financial event posts debits and credits to the ledger',
                        'A ready chart of accounts, and you can add accounts',
                        'Journal entries for manual postings',
                        'Vouchers, receipts and payments',
                        'Opening balances for customers and suppliers',
                        'Financial periods and period closing',
                        'Contra settlement for a party that is both customer and supplier',
                    ],
                ],
            ],
            [
                'slug' => 'cash-and-bank', 'icon' => 'arrow-left-right', 'status' => 'available',
                'fa' => [
                    'title' => 'نقد، بانک و ارز',
                    'summary' => 'حساب‌های نقد و بانک، و چند ارز با نرخ تبدیل.',
                    'points' => [
                        'صندوق و حساب‌های بانکی',
                        'انتقال پول بین حساب‌ها',
                        'چند ارز: مبلغ هم به ارز اصلی و هم به ارز خارجی ثبت می‌شود',
                        'نرخ تبدیل هر معامله ثبت می‌شود',
                    ],
                ],
                'en' => [
                    'title' => 'Cash, bank and currencies',
                    'summary' => 'Cash and bank accounts, and several currencies with exchange rates.',
                    'points' => [
                        'Cash box and bank accounts',
                        'Transfers of money between accounts',
                        'Multiple currencies: each amount is kept in both the home and the foreign currency',
                        'The exchange rate of every transaction is recorded',
                    ],
                ],
            ],
            [
                'slug' => 'expenses', 'icon' => 'receipt-text', 'status' => 'available',
                'fa' => [
                    'title' => 'مصارف و شرکا',
                    'summary' => 'مصارف شرکت و حساب شرکا.',
                    'points' => [
                        'ثبت مصارف با دسته‌بندی',
                        'مصرف مشترک که بین شعبه‌ها تقسیم می‌شود',
                        'مراکز هزینه',
                        'حساب شرکا و ثبت برداشت‌ها',
                    ],
                ],
                'en' => [
                    'title' => 'Expenses and owners',
                    'summary' => 'Company expenses and owner accounts.',
                    'points' => [
                        'Expenses recorded with categories',
                        'Shared expenses split across branches',
                        'Cost centers',
                        'Owner accounts and drawings',
                    ],
                ],
            ],
            [
                'slug' => 'hr', 'icon' => 'users-round', 'status' => 'available',
                'fa' => [
                    'title' => 'منابع بشری و معاش',
                    'summary' => 'کارمندان، حاضری، رخصتی و معاش در همان سیستم.',
                    'points' => [
                        'کارمندان، قراردادها و اسناد',
                        'حاضری، شفت‌ها، تعطیلات و اتصال دستگاه حاضری',
                        'نوع‌های رخصتی، سهمیه و درخواست رخصتی',
                        'معاش، اجزای معاش و مالیات',
                        'قرضه کارمند و پرداخت معاش',
                        'استخدام: آگهی کار، درخواست‌ها و مصاحبه',
                        'پورتال خودخدمت برای کارمند',
                    ],
                ],
                'en' => [
                    'title' => 'HR and payroll',
                    'summary' => 'Employees, attendance, leave and payroll in the same system.',
                    'points' => [
                        'Employees, contracts and documents',
                        'Attendance, shifts, holidays and attendance device integration',
                        'Leave types, allocations and leave requests',
                        'Payroll, salary components and tax',
                        'Employee loans and salary payments',
                        'Recruitment: job openings, applications and interviews',
                        'A self-service portal for employees',
                    ],
                ],
            ],
            [
                'slug' => 'multi-branch', 'icon' => 'building-2', 'status' => 'available',
                'fa' => [
                    'title' => 'چند شرکت و چند شعبه',
                    'summary' => 'از یک دکان تا چند شعبه، با داده جدا و دسترسی مشخص.',
                    'points' => [
                        'چند شرکت در یک سیستم، با داده کاملاً جدا',
                        'شعبه‌ها و گدام‌های متعدد',
                        'مقایسه شعبه‌ها',
                        'ماژول‌ها را برای هر شرکت روشن یا خاموش کنید',
                        'نقش‌ها و دسترسی برای هر کاربر',
                    ],
                ],
                'en' => [
                    'title' => 'Multi-company and multi-branch',
                    'summary' => 'From one shop to many branches, with separate data and defined access.',
                    'points' => [
                        'Several companies in one system, with fully separate data',
                        'Several branches and warehouses',
                        'Branch comparison',
                        'Switch modules on or off for each company',
                        'Roles and permissions for each user',
                    ],
                ],
            ],
            [
                'slug' => 'reports', 'icon' => 'chart-column', 'status' => 'available',
                'fa' => [
                    'title' => 'گزارش‌ها و کنترول',
                    'summary' => 'داشبورد، گزارش‌ها و ردیابی هر تغییر.',
                    'points' => [
                        'داشبورد و گزارش‌های مالی و موجودی',
                        'لاگ فعالیت: چه کسی، چه وقت، چه چیزی را تغییر داد',
                        'رکوردهای حذف‌شده قابل بازگرداندن',
                        'اطلاعیه‌ها',
                        'چاپ PDF',
                    ],
                ],
                'en' => [
                    'title' => 'Reports and control',
                    'summary' => 'A dashboard, reports and a trail of every change.',
                    'points' => [
                        'A dashboard and financial and stock reports',
                        'Activity log: who changed what, and when',
                        'Deleted records can be restored',
                        'Notifications',
                        'PDF printing',
                    ],
                ],
            ],
            [
                'slug' => 'languages', 'icon' => 'languages', 'status' => 'available',
                'fa' => [
                    'title' => 'زبان و تقویم',
                    'summary' => 'به زبان و تقویم خودتان کار کنید.',
                    'points' => [
                        'دری، پشتو و انگلیسی',
                        'تقویم شمسی و میلادی',
                        'حالت تیره و روشن',
                        'راهنمای کاربر داخل سیستم',
                    ],
                ],
                'en' => [
                    'title' => 'Language and calendar',
                    'summary' => 'Work in your own language and calendar.',
                    'points' => [
                        'Dari, Pashto and English',
                        'Jalali and Gregorian calendars',
                        'Dark and light mode',
                        'A user manual inside the system',
                    ],
                ],
            ],
            // Not built yet: shown with a "coming soon" label, never as available.
            ['slug' => 'pos', 'icon' => 'store', 'status' => 'soon',
                'fa' => ['title' => 'صندوق فروش (POS)', 'summary' => 'فروش سریع در دکان با صندوق.', 'points' => ['در دست ساخت']],
                'en' => ['title' => 'Point of sale (POS)', 'summary' => 'Fast counter sales in the shop.', 'points' => ['Under development']]],
            ['slug' => 'crm', 'icon' => 'handshake', 'status' => 'soon',
                'fa' => ['title' => 'مدیریت ارتباط با مشتری (CRM)', 'summary' => 'پیگیری مشتریان و فرصت‌های فروش.', 'points' => ['در دست ساخت']],
                'en' => ['title' => 'Customer relationship management (CRM)', 'summary' => 'Follow customers and sales opportunities.', 'points' => ['Under development']]],
            ['slug' => 'projects', 'icon' => 'layers', 'status' => 'soon',
                'fa' => ['title' => 'پروژه‌ها', 'summary' => 'مدیریت پروژه و هزینه هر پروژه.', 'points' => ['در دست ساخت']],
                'en' => ['title' => 'Projects', 'summary' => 'Project management and the cost of each project.', 'points' => ['Under development']]],
            ['slug' => 'fixed-assets', 'icon' => 'landmark', 'status' => 'soon',
                'fa' => ['title' => 'دارایی‌های ثابت', 'summary' => 'ثبت و استهلاک دارایی‌های ثابت.', 'points' => ['در دست ساخت']],
                'en' => ['title' => 'Fixed assets', 'summary' => 'Record fixed assets and their depreciation.', 'points' => ['Under development']]],
        ];

        foreach ($features as $i => $row) {
            $feature = new Feature(['slug' => $row['slug'], 'icon' => $row['icon'], 'status' => $row['status'], 'sort' => $i + 1]);
            foreach (['fa', 'en'] as $locale) {
                foreach (['title', 'summary', 'points'] as $field) {
                    $feature->setTranslation($field, $locale, $row[$locale][$field]);
                }
            }
            Feature::updateOrCreate(['slug' => $row['slug']], $feature->getAttributes());
        }
    }
}
