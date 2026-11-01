<?php

/**
 * Builds what goes onto a cPanel host, in ./release:
 *
 *   nextbook-site.zip     the application with its production vendor folder and its .env, for the home
 *                         folder (outside the web root)
 *   public_html.zip       the public files, with an index.php that finds the application one folder up
 *   database-release.sql  the empty site with its content, for phpPgAdmin (when artisan cannot be run)
 *
 *   php scripts/build-release.php          build everything
 *   php scripts/build-release.php --no-sql skip the database dump
 *
 * Needs: composer, the built assets (npm run build), and for the dump a local PostgreSQL with
 * pg_dump (PG_BIN, default C:/Program Files/PostgreSQL/16/bin) and the DB_* settings of the local .env.
 */

$root = str_replace('\\', '/', dirname(__DIR__));
$out = $root.'/release';
$work = $out.'/work';
$withSql = ! in_array('--no-sql', $argv, true);

function say(string $line): void
{
    fwrite(STDOUT, $line."\n");
}

function fail(string $line): never
{
    fwrite(STDERR, "ERROR: $line\n");
    exit(1);
}

/** Runs a command without a shell. Returns [exit code, output]. */
function run(array $command, ?string $cwd = null, array $env = []): array
{
    $process = proc_open(
        $command,
        [1 => ['pipe', 'w'], 2 => ['pipe', 'w']],
        $pipes,
        $cwd,
        $env ? array_merge(getenv(), $env) : null,
    );

    if (! is_resource($process)) {
        fail('could not start '.$command[0]);
    }

    $output = stream_get_contents($pipes[1]).stream_get_contents($pipes[2]);

    return [proc_close($process), $output];
}

/** How to start composer: COMPOSER_BIN, a composer.phar in the usual places, or "composer" on the path. */
function composerCommand(): array
{
    $candidates = array_filter([getenv('COMPOSER_BIN') ?: null, 'C:/ProgramData/ComposerSetup/bin/composer.phar', '/usr/local/bin/composer', '/usr/bin/composer']);

    foreach ($candidates as $path) {
        if (is_file($path)) {
            return str_ends_with($path, '.phar') ? [PHP_BINARY, $path] : [$path];
        }
    }

    return ['composer'];
}

function removeTree(string $path): void
{
    if (! file_exists($path) && ! is_link($path)) {
        return;
    }

    if (is_file($path) || is_link($path)) {
        @unlink($path);

        return;
    }

    foreach (new FilesystemIterator($path) as $item) {
        removeTree($item->getPathname());
    }

    @rmdir($path);
}

/** Copies a folder, skipping every path (relative to the source, with forward slashes) the filter rejects. */
function copyTree(string $from, string $to, callable $skip): int
{
    $count = 0;
    @mkdir($to, 0777, true);
    $base = strlen(rtrim($from, '/')) + 1;

    $iterator = new RecursiveIteratorIterator(
        new RecursiveCallbackFilterIterator(
            new RecursiveDirectoryIterator($from, FilesystemIterator::SKIP_DOTS),
            fn (SplFileInfo $file) => ! $skip(substr(str_replace('\\', '/', $file->getPathname()), $base), $file->isDir()),
        ),
        RecursiveIteratorIterator::SELF_FIRST,
    );

    foreach ($iterator as $file) {
        $relative = substr(str_replace('\\', '/', $file->getPathname()), $base);

        if ($file->isDir()) {
            @mkdir($to.'/'.$relative, 0777, true);
        } else {
            copy($file->getPathname(), $to.'/'.$relative);
            $count++;
        }
    }

    return $count;
}

function zipTree(string $folder, string $zipFile, string $prefix = ''): void
{
    $zip = new ZipArchive();

    if ($zip->open($zipFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
        fail("cannot write $zipFile");
    }

    $base = strlen(rtrim($folder, '/')) + 1;

    foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($folder, FilesystemIterator::SKIP_DOTS)) as $file) {
        $relative = substr(str_replace('\\', '/', $file->getPathname()), $base);
        $zip->addFile($file->getPathname(), $prefix.$relative);
    }

    $zip->close();
    say(sprintf('  %s  %.1f MB', basename($zipFile), filesize($zipFile) / 1048576));
}

// ---------------------------------------------------------------------------------------------

if (! is_file($root.'/public/build/manifest.json')) {
    fail('public/build/manifest.json is missing: run "npm run build" first.');
}

say('Cleaning release/ ...');
removeTree($out);
mkdir($work, 0777, true);

// 1. The application, without anything that is only for development or already built.
say('Copying the application ...');
$skipApp = function (string $path, bool $isDir): bool {
    $top = explode('/', $path)[0];

    if (in_array($top, ['.git', '.github', '.idea', '.vscode', 'node_modules', 'vendor', 'tests', 'release', 'scripts', 'docs', 'public'], true)) {
        return true;
    }
    if (str_starts_with($top, '.env') || in_array($top, ['phpunit.xml', 'package.json', 'package-lock.json', 'vite.config.js', '.phpunit.result.cache'], true)) {
        return true;
    }
    // Source for the build, which has already run.
    if (str_starts_with($path, 'resources/css') || str_starts_with($path, 'resources/js')) {
        return true;
    }
    // Caches, logs, sessions and compiled views are rebuilt on the host.
    if (preg_match('#^(storage/logs|storage/framework/(cache|sessions|views|testing)|bootstrap/cache)/.+#', $path)) {
        return true;
    }

    return false;
};
$files = copyTree($root, $work.'/nextbook-site', $skipApp);
say("  $files files");

foreach (['storage/logs', 'storage/framework/cache/data', 'storage/framework/sessions', 'storage/framework/views', 'storage/app/public', 'bootstrap/cache'] as $dir) {
    @mkdir($work.'/nextbook-site/'.$dir, 0777, true);
}

// 2. Production vendor folder.
say('Installing the production dependencies (composer, no dev) ...');
[$code, $output] = run([...composerCommand(), 'install', '--no-dev', '--optimize-autoloader', '--no-scripts', '--no-interaction', '--no-progress'], $work.'/nextbook-site');
if ($code !== 0) {
    fail("composer install failed:\n$output");
}

$key = 'base64:'.base64_encode(random_bytes(32));
[$code, $output] = run([PHP_BINARY, 'artisan', 'package:discover', '--ansi'], $work.'/nextbook-site', ['APP_KEY' => $key, 'APP_ENV' => 'production']);
if ($code !== 0) {
    fail("package:discover failed:\n$output");
}
say('  vendor ready');

// 3. The .env for the host: everything is decided except the values only the host can know.
$env = <<<ENV
APP_NAME=NextBook
APP_ENV=production
APP_KEY=$key
APP_DEBUG=false
APP_URL=https://nextbook.af
APP_LOCALE=fa
APP_FALLBACK_LOCALE=fa
APP_FAKER_LOCALE=en_US

LOG_CHANNEL=stack
LOG_STACK=daily
LOG_LEVEL=warning

# The PostgreSQL database made in cPanel. cPanel puts your account name in front of both names,
# for example DB_DATABASE=myaccount_nextbook and DB_USERNAME=myaccount_nextbook.
DB_CONNECTION=pgsql
DB_HOST=localhost
DB_PORT=5432
DB_DATABASE=CHANGE_ME
DB_USERNAME=CHANGE_ME
DB_PASSWORD=CHANGE_ME

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_SECURE_COOKIE=true
CACHE_STORE=file
QUEUE_CONNECTION=sync
FILESYSTEM_DISK=local

# Mail for the notification about new demo requests and messages: the mailbox made in cPanel.
MAIL_MAILER=smtp
MAIL_SCHEME=smtps
MAIL_HOST=mail.nextbook.af
MAIL_PORT=465
MAIL_USERNAME=info@nextbook.af
MAIL_PASSWORD=CHANGE_ME
MAIL_FROM_ADDRESS="info@nextbook.af"
MAIL_FROM_NAME="NextBook"

SITE_LEADS_TO=sales@nextbook.af,fahimghafoor@gmail.com
SITE_TENANT_DOMAIN=nextbook.af
SITE_TENANT_SCHEME=https
SITE_WHATSAPP=93728507537

# Visitor statistics: leave empty for none.
SITE_ANALYTICS_SRC=
SITE_ANALYTICS_DOMAIN=nextbook.af

VITE_APP_NAME="\${APP_NAME}"

ENV;
file_put_contents($work.'/nextbook-site/.env', $env);

// 4. The public files, which on cPanel go into public_html.
say('Preparing public_html ...');
copyTree($root.'/public', $work.'/public_html', fn (string $path) => in_array(explode('/', $path)[0], ['hot', 'storage', 'index.php', '.htaccess'], true) && $path !== '');

file_put_contents($work.'/public_html/index.php', <<<'PHP'
<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// The application lives in the "nextbook-site" folder next to public_html, outside the web root.
$app_dir = __DIR__.'/../nextbook-site';

if (file_exists($maintenance = $app_dir.'/storage/framework/maintenance.php')) {
    require $maintenance;
}

require $app_dir.'/vendor/autoload.php';

$app = require_once $app_dir.'/bootstrap/app.php';

// public_html is the public folder here, not nextbook-site/public.
$app->usePublicPath(__DIR__);

$app->handleRequest(Request::capture());
PHP);

file_put_contents($work.'/public_html/.htaccess', <<<'HT'
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # One address: https and no "www".
    RewriteCond %{HTTPS} !=on [OR]
    RewriteCond %{HTTP_HOST} ^www\. [NC]
    RewriteRule ^ https://nextbook.af%{REQUEST_URI} [L,R=301]

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Handle X-XSRF-Token Header
    RewriteCond %{HTTP:x-xsrf-token} .
    RewriteRule .* - [E=HTTP_X_XSRF_TOKEN:%{HTTP:X-XSRF-Token}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>

# Fonts and images that never change under the same name can be kept by the browser for a long time.
<IfModule mod_headers.c>
    <FilesMatch "\.(woff2?|ttf|png|webp|ico|svg)$">
        Header set Cache-Control "public, max-age=2592000"
    </FilesMatch>
    <FilesMatch "^build/assets/">
        Header set Cache-Control "public, max-age=31536000, immutable"
    </FilesMatch>
</IfModule>
HT);

// 5. The zips.
say('Zipping ...');
zipTree($work.'/nextbook-site', $out.'/nextbook-site.zip', 'nextbook-site/');
zipTree($work.'/public_html', $out.'/public_html.zip');

// 6. The database, as SQL.
if ($withSql) {
    say('Building the database dump ...');
    $localEnv = [];
    foreach (file($root.'/.env', FILE_IGNORE_NEW_LINES) as $line) {
        if (preg_match('/^(DB_[A-Z]+)=(.*)$/', $line, $m)) {
            $localEnv[$m[1]] = trim($m[2], "\"'");
        }
    }

    $pg = rtrim(getenv('PG_BIN') ?: 'C:/Program Files/PostgreSQL/16/bin', '/');
    $conn = ['PGPASSWORD' => $localEnv['DB_PASSWORD'] ?? ''];
    $common = ['-h', $localEnv['DB_HOST'] ?? '127.0.0.1', '-p', $localEnv['DB_PORT'] ?? '5432', '-U', $localEnv['DB_USERNAME'] ?? 'postgres'];
    $temp = 'nextbook_site_release_tmp';

    run([$pg.'/psql', ...$common, '-d', 'postgres', '-c', "DROP DATABASE IF EXISTS $temp"], null, $conn);
    [$code, $output] = run([$pg.'/psql', ...$common, '-d', 'postgres', '-c', "CREATE DATABASE $temp"], null, $conn);
    if ($code !== 0) {
        fail("cannot create the temporary database:\n$output");
    }

    $artisanEnv = [
        'DB_CONNECTION' => 'pgsql', 'DB_DATABASE' => $temp, 'APP_ENV' => 'production', 'APP_DEBUG' => 'false',
        'DB_HOST' => $localEnv['DB_HOST'] ?? '127.0.0.1', 'DB_PORT' => $localEnv['DB_PORT'] ?? '5432',
        'DB_USERNAME' => $localEnv['DB_USERNAME'] ?? 'postgres', 'DB_PASSWORD' => $localEnv['DB_PASSWORD'] ?? '',
    ];
    foreach ([['migrate', '--force'], ['db:seed', '--force'], ['help:import']] as $artisan) {
        [$code, $output] = run([PHP_BINARY, 'artisan', ...$artisan], $root, $artisanEnv);
        if ($code !== 0) {
            fail('artisan '.implode(' ', $artisan)." failed:\n$output");
        }
    }

    [$code, $output] = run([
        $pg.'/pg_dump', ...$common, '-d', $temp,
        '--no-owner', '--no-privileges', '--no-comments', '--format=plain', '--file='.$out.'/database-release.sql',
    ], null, $conn);
    if ($code !== 0) {
        fail("pg_dump failed:\n$output");
    }

    run([$pg.'/psql', ...$common, '-d', 'postgres', '-c', "DROP DATABASE IF EXISTS $temp"], null, $conn);
    say(sprintf('  database-release.sql  %.1f MB', filesize($out.'/database-release.sql') / 1048576));
}

removeTree($work);
say('Done: '.$out);
