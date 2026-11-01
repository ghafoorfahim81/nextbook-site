# نصب وب‌سایت nextbook.af روی cPanel

این راهنما مرحله‌به‌مرحله است و همه کارها از داخل cPanel و با ماوس انجام می‌شود. اگر Terminal دارید کار سریع‌تر می‌شود، ولی لازم نیست.

## فایل‌های آماده

بعد از اجرای `php scripts/build-release.php` سه فایل در پوشه `release` ساخته می‌شود:

| فایل | کجا می‌رود | چیست |
|---|---|---|
| `nextbook-site.zip` | پوشه اصلی حساب شما (home)، **بیرون** از public_html | خود برنامه، با فایل `.env` |
| `public_html.zip` | داخل `public_html` | فایل‌های عمومی (عکس، فونت، CSS، `index.php`) |
| `database-release.sql` | فقط اگر دستور artisan نتوانستید اجرا کنید | جدول‌ها و محتوای آماده |

> برنامه عمداً بیرون از `public_html` است تا هیچ‌کس نتواند به `.env` و کد برنامه دسترسی بگیرد.

## ۱. نسخه PHP و افزونه‌ها

در cPanel بخش **Select PHP Version** (یا **MultiPHP Manager**) را باز کنید.

- نسخه PHP باید **8.3 یا بالاتر** باشد.
- این افزونه‌ها باید روشن باشند: `pdo_pgsql`، `pgsql`، `intl`، `mbstring`، `zip`، `fileinfo`، `curl`، `xml`، `dom`، `openssl`، `tokenizer`، `ctype`.

## ۲. ساختن دیتابیس PostgreSQL

در بخش **PostgreSQL Databases**:

1. یک دیتابیس بسازید، مثلاً `nextbook`. cPanel نام حساب شما را جلوی آن می‌گذارد (مثلاً `myaccount_nextbook`).
2. یک کاربر بسازید، مثلاً `nextbook`، با یک رمز قوی. رمز را یادداشت کنید.
3. در پایین صفحه **Add User To Database**: کاربر را به دیتابیس اضافه کنید و **ALL PRIVILEGES** را بدهید.

نام کامل دیتابیس و کاربر (با پیشوند) را برای مرحله ۴ نگه دارید.

## ۳. آپلود فایل‌ها

در **File Manager**، از تنظیمات (Settings، بالا سمت راست) گزینه **Show Hidden Files (dotfiles)** را روشن کنید، چون `.env` و `.htaccess` فایل‌های مخفی‌اند.

**برنامه:**
1. به پوشه اصلی بروید (`/home/نام‌حساب`، بالاتر از public_html).
2. **Upload** کنید: `nextbook-site.zip`.
3. روی فایل راست‌کلیک کنید و **Extract** بزنید. پوشه `nextbook-site` ساخته می‌شود. بعد از آن فایل zip را پاک کنید.

**فایل‌های عمومی:**
1. وارد `public_html` شوید. اگر فایل‌های پیش‌فرض هاست آنجا هست (مثل `index.html` یا `cgi-bin`)، `index.html` را پاک کنید.
2. **Upload** کنید: `public_html.zip` و **Extract** بزنید. بعد فایل zip را پاک کنید.

اگر آپلود فایل بزرگ خطا داد، حداکثر اندازه آپلود را در **MultiPHP INI Editor** (`upload_max_filesize` و `post_max_size`) بالا ببرید، یا فایل را با FTP بفرستید.

## ۴. تنظیم `.env`

فایل `nextbook-site/.env` را با **Edit** باز کنید و فقط این‌ها را عوض کنید:

```
DB_DATABASE=myaccount_nextbook      ← نام کامل دیتابیس
DB_USERNAME=myaccount_nextbook      ← نام کامل کاربر
DB_PASSWORD=رمز-دیتابیس
MAIL_PASSWORD=رمز-ایمیل-info@nextbook.af
```

بقیه مقدارها (آدرس سایت، کلید برنامه، ایمیل‌های دریافت‌کننده) آماده‌اند. `APP_DEBUG` باید `false` بماند.

> ایمیل: در cPanel بخش **Email Accounts** برای `info@nextbook.af` روی **Connect Devices** بزنید. اگر نام سرور ایمیل (`MAIL_HOST`) یا پورت آنجا با `.env` فرق دارد، همان را بگذارید.

## ۵. ساختن جدول‌ها و محتوا

### راه الف: با Terminal (ساده‌تر)

بخش **Terminal** را باز کنید و بنویسید (به‌جای `myaccount` نام حساب خودتان):

```
cd ~/nextbook-site
php artisan migrate --force
php artisan db:seed --force
php artisan help:import
php artisan optimize
```

### راه ب: با فایل SQL

اگر Terminal ندارید: در cPanel **phpPgAdmin** را باز کنید، دیتابیس خودتان را انتخاب کنید، تب **SQL** را بزنید و فایل `database-release.sql` را آپلود کنید (گزینه Upload / Choose File). اجرا که تمام شد، جدول‌ها ساخته شده‌اند.

## ۶. ساختن کاربر مدیر

با Terminal:

```
cd ~/nextbook-site
php artisan make:filament-user
```

نام، ایمیل و رمز را بپرسد. رمز را قوی بگذارید.

**بدون Terminal:** در cPanel بخش **Cron Jobs**، یک کار جدید بسازید با زمان «هر دقیقه» (`* * * * *`) و این دستور (به‌جای نام‌حساب، ایمیل و رمز، مقدار خودتان):

```
cd /home/myaccount/nextbook-site && php artisan make:filament-user --name="مدیر" --email="you@example.com" --password="رمز-قوی"
```

بعد از یک دقیقه کاربر ساخته می‌شود. **همان لحظه آن کار را از Cron Jobs پاک کنید** و رمز را در جای دیگری ننویسید.

## ۷. دسترسی پوشه‌ها

در File Manager روی `nextbook-site/storage` و `nextbook-site/bootstrap/cache` راست‌کلیک و **Change Permissions** را بزنید: `755` کافی است، و اگر خطای نوشتن دیدید `775`. گزینه «اعمال روی همه زیرپوشه‌ها» (recursive) را بزنید.

## ۸. HTTPS

در بخش **SSL/TLS Status** مطمئن شوید برای `nextbook.af` و `www.nextbook.af` گواهی (AutoSSL) فعال است. فایل `.htaccess` همه آدرس‌ها را به `https://nextbook.af` می‌برد.

## ۹. آزمایش

- `https://nextbook.af` باز شود و به صفحه دری برود.
- `https://nextbook.af/en` انگلیسی باشد.
- `https://nextbook.af/admin` و ورود با کاربر مدیر.
- یک درخواست دیمو بفرستید: باید در پنل **Leads → Demo requests** دیده شود و ایمیل به `sales@nextbook.af` و Gmail برسد.
- `https://nextbook.af/robots.txt` و `https://nextbook.af/sitemap.xml` باز شوند.
- لینک سایت را در واتساپ بفرستید؛ باید عکس پیش‌نمایش و عنوان بیاید.

## ۱۰. ورود دو مرحله‌ای برای پنل مدیریت

بعد از ورود به `/admin`، از منوی کاربر (بالا) **Profile** را بزنید و **Authenticator app** را فعال کنید (Google Authenticator یا مشابه). کدهای بازیابی را جای امن نگه دارید.

## بروزرسانی بعدی

1. روی کمپیوتر خودتان: `npm run build` و بعد `php scripts/build-release.php --no-sql`.
2. `nextbook-site.zip` را دوباره آپلود و روی پوشه قبلی Extract کنید (گزینه Overwrite). **فایل `.env` را عوض نکنید**؛ برای همین بهتر است قبل از Extract یک نسخه از آن نگه دارید.
3. `public_html.zip` را هم همین‌طور.
4. در Terminal: `php artisan migrate --force` و `php artisan optimize:clear`، و دوباره `php artisan optimize`.
5. اگر فقط متن‌ها عوض شده، همه این‌ها لازم نیست: متن‌ها را از پنل مدیریت ویرایش کنید.

## پشتیبان‌گیری

- در cPanel بخش **Backup** نسخه کامل حساب را بگیرید.
- دیتابیس را از **phpPgAdmin** (Export) هم جدا بگیرید.
- مقاله‌ها، قیمت‌ها و درخواست‌ها فقط در دیتابیس هستند، پس بدون پشتیبان دیتابیس از بین می‌روند.

## مشکل‌ها

| علامت | علت و راه |
|---|---|
| خطای ۵۰۰ | فایل `nextbook-site/storage/logs/laravel-تاریخ.log` را باز کنید؛ آخرین خط علت را می‌گوید. |
| «No application encryption key» | `.env` پیدا نمی‌شود یا `APP_KEY` خالی است. مخفی بودن فایل را بررسی کنید. |
| «could not connect» / «password authentication failed» | `DB_*` در `.env` یا اضافه نشدن کاربر به دیتابیس (مرحله ۲). |
| «could not find driver» | افزونه `pdo_pgsql` روشن نیست (مرحله ۱). |
| صفحه بدون استایل | پوشه `public_html/build` آپلود نشده یا `public_html/hot` وجود دارد (پاکش کنید). |
| «Permission denied» روی storage | مرحله ۷. |
| ایمیل نمی‌رسد | `MAIL_*` را با **Connect Devices** مقایسه کنید. درخواست در پنل هست و از بین نرفته است. |
| بعد از تغییر `.env` اثر ندارد | در Terminal: `php artisan optimize:clear`. |

## درباره ERP روی همین هاست

سایت فقط یک دیتابیس معمولی PostgreSQL می‌خواهد. ERP نکست‌بوک دو نقش دیتابیس (مالک و `nextbook_app`) و سیاست‌های Row Level Security می‌خواهد و ساختن نقش در cPanel معمولاً محدود است. قبل از نصب ERP روی این هاست، این را جداگانه بررسی کنید (`docs/row-level-security.md` در پروژه ERP).
