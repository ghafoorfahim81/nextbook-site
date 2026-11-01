<?php

namespace Database\Seeders;

use App\Models\PricingAddon;
use App\Models\PricingPlan;
use Illuminate\Database\Seeder;

/**
 * The three plans and the add-ons, as decided by the business (prices in US dollars).
 *
 * An item marked soon is something the plan promises that the ERP does not do yet (fixed
 * assets, projects, budgets, donor and consolidated reports); the page shows it with a
 * "coming soon" label instead of presenting it as available.
 */
class PricingSeeder extends Seeder
{
    public function run(): void
    {
        $soon = fn (string $text) => ['text' => $text, 'soon' => true];

        $plans = [
            [
                'slug' => 'starter', 'monthly' => 10, 'yearly' => 100, 'featured' => false,
                'fa' => [
                    'name' => 'پایه',
                    'audience' => 'برای کسب‌وکارهای کوچک، دکان‌ها و فروشگاه‌ها',
                    'includes' => null,
                    'points' => [
                        'حسابداری اصلی: ثبت اسناد، دفتر کل، صندوق و بانک',
                        'فاکتور فروش و خرید',
                        'گزارش‌های پایه: سود و زیان، ترازنامه',
                        'تا ۳ کاربر',
                        'یک شعبه',
                        'آموزش ویدیویی عمومی',
                    ],
                ],
                'en' => [
                    'name' => 'Starter',
                    'audience' => 'For small businesses, shops and stores',
                    'includes' => null,
                    'points' => [
                        'Core accounting: documents, general ledger, cash and bank',
                        'Sales and purchase invoices',
                        'Basic reports: profit and loss, balance sheet',
                        'Up to 3 users',
                        'One branch',
                        'General video tutorials',
                    ],
                ],
            ],
            [
                'slug' => 'professional', 'monthly' => 18, 'yearly' => 150, 'featured' => true,
                'fa' => [
                    'name' => 'حرفه‌ای',
                    'audience' => 'برای شرکت‌های متوسط',
                    'includes' => 'همه امکانات پکیج پایه، به‌علاوه:',
                    'points' => [
                        'مدیریت انبار و موجودی',
                        'حساب‌های دریافتنی و پرداختنی، مدیریت مشتری و تامین‌کننده',
                        'چند صندوق و حساب بانکی، چند ارز (افغانی، دالر و غیره)',
                        'گزارش‌های پیشرفته و خروجی Excel و PDF',
                        'تا ۱۰ کاربر با سطوح دسترسی',
                        'تا ۳ شعبه',
                        'پشتیبانی تلفنی و واتساپ',
                    ],
                ],
                'en' => [
                    'name' => 'Professional',
                    'audience' => 'For mid-sized companies',
                    'includes' => 'Everything in Starter, plus:',
                    'points' => [
                        'Warehouse and inventory management',
                        'Receivables and payables, customer and supplier management',
                        'Several cash boxes and bank accounts, multiple currencies (afghani, dollar and others)',
                        'Advanced reports with Excel and PDF export',
                        'Up to 10 users with access levels',
                        'Up to 3 branches',
                        'Phone and WhatsApp support',
                    ],
                ],
            ],
            [
                'slug' => 'enterprise', 'monthly' => 25, 'yearly' => 220, 'featured' => false,
                'fa' => [
                    'name' => 'سازمانی',
                    'audience' => 'برای سازمان‌ها، NGOها و شرکت‌های بزرگ',
                    'includes' => 'همه امکانات پکیج حرفه‌ای، به‌علاوه:',
                    'points' => [
                        'منابع بشری و حقوق و دستمزد',
                        $soon('دارایی‌های ثابت'),
                        $soon('پروژه‌ها'),
                        $soon('بودجه'),
                        'چند شعبه و چند شرکت',
                        $soon('گزارش تلفیقی'),
                        $soon('گزارش‌دهی مطابق نیاز donorها (برای NGOها)'),
                        'کاربران نامحدود',
                        'لاگ فعالیت و نسخه پشتیبان خودکار',
                        'آموزش حضوری، نصب و راه‌اندازی، مدیر حساب اختصاصی',
                    ],
                ],
                'en' => [
                    'name' => 'Enterprise',
                    'audience' => 'For organisations, NGOs and large companies',
                    'includes' => 'Everything in Professional, plus:',
                    'points' => [
                        'HR and payroll',
                        $soon('Fixed assets'),
                        $soon('Projects'),
                        $soon('Budgeting'),
                        'Multiple branches and multiple companies',
                        $soon('Consolidated reporting'),
                        $soon('Donor-ready reporting (for NGOs)'),
                        'Unlimited users',
                        'Activity log and automatic backups',
                        'On-site training, installation and setup, a dedicated account manager',
                    ],
                ],
            ],
        ];

        foreach ($plans as $i => $row) {
            $attributes = [
                'monthly' => $row['monthly'],
                'yearly' => $row['yearly'],
                'is_featured' => $row['featured'],
                'sort' => $i + 1,
                'is_published' => true,
            ];

            foreach (['name', 'audience', 'includes', 'points'] as $field) {
                $attributes[$field] = ['fa' => $row['fa'][$field], 'en' => $row['en'][$field]];
            }

            PricingPlan::updateOrCreate(['slug' => $row['slug']], $attributes);
        }

        $addons = [
            ['training', 'graduation-cap', 'آموزش کارکنان', 'آموزش حضوری یا آنلاین برای کارکنان شما.', 'Staff training', 'In-person or online training for your staff.'],
            ['data-migration', 'database-zap', 'انتقال اطلاعات', 'انتقال اطلاعات از سیستم قبلی یا از Excel.', 'Data migration', 'Move your data from your previous system or from Excel.'],
            ['customisation', 'settings-2', 'سفارشی‌سازی', 'سفارشی‌سازی فرم‌ها و گزارش‌ها مطابق کار شما.', 'Customisation', 'Forms and reports adapted to the way you work.'],
            ['priority-support', 'headset', 'پشتیبانی ویژه', 'پاسخ‌گویی در همان روز.', 'Priority support', 'A reply the same day.'],
        ];

        foreach ($addons as $i => [$slug, $icon, $faName, $faText, $enName, $enText]) {
            PricingAddon::updateOrCreate(['slug' => $slug], [
                'icon' => $icon,
                'sort' => $i + 1,
                'is_published' => true,
                'name' => ['fa' => $faName, 'en' => $enName],
                'description' => ['fa' => $faText, 'en' => $enText],
                'price_label' => ['fa' => 'قیمت بر اساس نیاز', 'en' => 'Quoted to your needs'],
            ]);
        }
    }
}
