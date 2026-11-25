# سند راه‌اندازی محیط توسعه PHP — راهنمای عمومی و مستقل از نسخه

> این سند راهنمای قابل‌استفادهٔ مجدد برای راه‌اندازی کامل محیط توسعهٔ PHP درون sandbox است، **کاملاً در فضای کاربر و بدون نیاز به root/sudo**. در شروع هر مکالمهٔ جدید که به توسعهٔ PHP مربوط می‌شود، این سند را به agent ارجاع بده تا مراحل را با نسخه‌های انتخاب‌شده برای همان پروژه اجرا کند. نسخهٔ PHP و دیتابیس در هر پروژه می‌تواند متفاوت باشد؛ به‌جای ذکر نسخهٔ ثابت، روش کشف آخرین نسخهٔ موجود آمده است.

ساختار سند به دو بخش تقسیم شده است: **مسیر اصلی** (گام‌های ۱ تا ۵) که برای هر پروژه‌ای لازم است، و **مسیرهای پیشرفته** (گام‌های ۶ تا ۹) که فقط در صورت نیاز به بیلد از سورس، سرور واقعی چندنخی، benchmark، یا اشتراک‌گذاری زندهٔ پروژه در مرورگر کاربر انتخاب می‌شوند.

---

## نحوهٔ استفاده در شروع هر مکالمه

این متن کوتاه را در ابتدای مکالمهٔ جدید به agent بده:

> «سند `/home/z/my-project/download/PHP_DEV_ENV_SETUP.md` را بخوان و محیط توسعهٔ PHP را طبق آن راه‌اندازی کن. برای این پروژه از PHP `<نسخه>` و دیتابیس `<sqlite|mariadb|none>` استفاده کن. اگر به سرور واقعی یا benchmark نیاز دارم جداً اعلام می‌کنم. دیتابیس و سرور توسعه را استارت بزن و منتظر دستور بعدی من بمان.»

سپس agent: نسخهٔ موردنظر PHP را از منبع باینری استاتیک نصب می‌کند، Composer را نصب می‌کند، در صورت نیاز MariaDB را در فضای کاربر راه‌اندازی می‌کند، ساختار پروژه را می‌سازد، سرویس‌ها را استارت می‌زند و صحت کار را تأیید می‌کند. گام‌های پیشرفته فقط با درخواست صریح اجرا می‌شوند.

---

## پیش‌فرض‌ها و محدودیت‌های sandbox (تأییدشده)

محیط توسعه در یک sandbox لینوکسی (Debian، glibc) با شرایط زیر اجرا می‌شود. شناخت این محدودیت‌ها ضروری است چون مسیر راه‌اندازی را تعیین می‌کنند:

- **بدون root و بدون sudo**: کاربر عادی (uid غیرصفر)، در گروه‌های `wheel`/`sudo` نیست؛ `sudo -n` رمز می‌خواهد. `/etc`، `/usr/bin`، `/root` فقط‌خواندنی‌اند و `/etc/passwd` قابل نوشتن نیست. باینری‌های SUID استاندارد و به‌روز هستند، پس **root قابل دستیابی نیست** و تلاش برای privilege escalation نامناسب و ممنوع است. همه‌چیز در فضای کاربر نصب می‌شود.
- **ابزارهای build موجود**: `gcc`، `make`، `autoconf`، `automake`، `bison`، `re2c`، `perl`، `pkg-config`، `git`، `curl`، `python3`. یعنی `./configure && make` برای بسیاری از پروژه‌های C اجرا می‌شود.
- **هدرهای dev غایب**: هدرهای توسعه برای `libxml2`، `oniguruma`، `libcurl`، `libzip`، `freetype`، `libjpeg`، `libicu`، `libreadline` موجود نیستند و بدون root قابل `apt install` نیستند. در نتیجه بیلد کامل php-src با اکستنشن‌های استاندارد غیرعملی است؛ راهکار پیشنهادی باینری **استاتیک ازپیش‌کامپایل‌شده** است (که همان php-src را با همهٔ وابستگی‌ها به‌صورت static لینک‌شده ارائه می‌دهد).
- **هدرهای موجود**: `sqlite3.h`، `openssl/ssl.h`، `libpng/png.h` موجودند، پس بیلد حداقلی php-src با `sqlite3` و `openssl` و `gd` (با libpng) شدنی است.
- **ماندگاری**: فقط مسیر `/home/z/my-project/` بین ریست‌های sandbox تضمین‌شده حفظ می‌شود؛ **پردازش‌ها ماندگار نیستند**. پس باینری‌ها و داده‌ها باید زیر مسیر پروژه نصب شوند، و سرویس‌های long-run (دیتابیس، PHP-FPM، nginx) در ابتدای هر مکالمهٔ جدید دوباره استارت زده شوند.
- **PATH**: `php` روی `$PATH` نیست (نه root برای افزودن سیستم‌ویی وجود دارد و نه روی ویرایش dotfileهای home تکیه می‌شود). همیشه با مسیر مطلق `bin/php` یا `bin/composer` فراخوانی کن، یا در ابتدای دستور `export PATH=/home/z/my-project/bin:$PATH` بزن.

---

## تصمیمات پیش از شروع (برای هر پروژه)

پیش از اجرای گام‌ها، این تصمیمات را برای پروژهٔ فعلی بگیر:

1. **نسخهٔ PHP**: هر نسخه‌ای که پروژهٔ `static-php-cli` در شاخهٔ `gnu-bulk` منتشر کرده (از PHP 8.0 تا آخرین نسخه). پیش‌فرض: آخرین نسخهٔ پایدار از شاخه‌ای که پروژه نیاز دارد.
2. **SAPI**: اگر فقط CLI/سرور توسعه لازم است، بیلد `-cli-` کافی است. اگر nginx + PHP-FPM (گام ۷) لازم است، بیلد `-fpm-` را دانلود کن (شامل هم `php` و هم `php-fpm`).
3. **دیتابیس**: `sqlite` (همیشه موجود، بدون تنظیم) | `mariadb`/`mysql` (tarball فضای کاربر) | `none`.
4. **اکستنشن‌ها**: بیلد `gnu-bulk` شامل ۵۰+ اکستنشن است و برای اغلب پروژه‌ها کافی است. تنها در صورت نبودن اکستنشن خاصی از مسیر دیگری استفاده کن.

---

# مسیر اصلی راه‌اندازی (گام‌های ۱ تا ۵)

## گام ۱ — نصب PHP (باینری استاتیک، glibc، بدون root)

### ۱-الف. کشف نسخه‌های موجود

```bash
curl -sSL https://dl.static-php.dev/static-php-cli/gnu-bulk/ \
  | grep -oE 'php-[0-9]+\.[0-9]+\.[0-9]+-cli-linux-x86_64\.tar\.gz' \
  | sort -Vu | tail -20
```

از خروجی، نسخهٔ موردنظر پروژه را انتخاب کن (مثلاً آخرین 8.2.x یا 8.3.x یا 8.4.x). مقدار را در متغیر `PHPVER` قرار بده. اگر به PHP-FPM نیاز داری (گام ۷)، به‌جای `-cli-` از `-fpm-` استفاده کن.

### ۱-ب. دانلود و نصب در مسیر پروژه

```bash
cd /home/z/my-project
mkdir -p bin
SAPI=cli            # یا fpm اگر گام ۷ لازم است
PHPVER=<نسخه>       # مثلاً 8.4.23
curl -fsSL -o /tmp/php.tar.gz \
  "https://dl.static-php.dev/static-php-cli/gnu-bulk/php-${PHPVER}-${SAPI}-linux-x86_64.tar.gz"
mkdir -p /tmp/phpx && tar -xzf /tmp/php.tar.gz -C /tmp/phpx
install -m 0755 /tmp/phpx/php bin/php
# در بیلد fpm، باینری php-fpm هم در آرشیو هست:
[ -f /tmp/phpx/php-fpm ] && install -m 0755 /tmp/phpx/php-fpm bin/php-fpm || true
```

> اگر پروژه به بیلد سبک‌تری با اکستنشن‌های کمتر نیاز داشت، به‌جای `gnu-bulk` از شاخهٔ `common` یا `minimal` همان منبع استفاده کن؛ الگوی URL یکسان است.

### ۱-ج. تأیید

```bash
bin/php -v
bin/php -m                         # بررسی وجود اکستنشن‌های موردنیاز پروژه
bin/php -r 'echo "ok ".PHP_VERSION."\n";'
```

اگر اکستنشن ضروری مفقود بود، ابتدا بیلد `bulk`/`common` را امتحان کن؛ در صورت نبودن، به گام ۶ (بیلد از سورس) مراجعه کن.

---

## گام ۲ — نصب Composer (فضای کاربر)

```bash
cd /home/z/my-project
curl -fsSL -o /tmp/cs.php https://getcomposer.org/installer
bin/php /tmp/cs.php --install-dir=bin --filename=composer.phar
cat > bin/composer <<'EOF'
#!/usr/bin/env bash
exec /home/z/my-project/bin/php /home/z/my-project/bin/composer.phar "$@"
EOF
chmod +x bin/composer
bin/composer --version
```

`bin/composer` یک wrapper است که از `bin/php` پروژه استفاده می‌کند؛ به این ترتیب نیازی به بودن `php` روی `$PATH` نیست.

---

## گام ۳ (اختیاری) — نصب MariaDB در فضای کاربر

اگر پروژه از SQLite یا بدون دیتابیس استفاده می‌کند، این گام را رد کن. MariaDB فورک drop-in از MySQL است و tarball گlibc آن بدون root و بدون systemd اجرا می‌شود.

### ۳-الف. کشف آخرین نسخهٔ پایدار

```bash
curl -sSL "https://downloads.mariadb.org/rest-api/mariadb/all-releases/?maturity=stable&release_status=stable&limit=10" \
  | python3 -c "import sys,json;[print(r['release_number']) for r in json.load(sys.stdin)['releases']]"
```

یکی از نسخه‌های stable را انتخاب کن و در `MDBVER` بگذار.

### ۳-ب. دانلود و استخراج

```bash
cd /home/z/my-project
mkdir -p opt
MDBVER=<نسخه>            # مثلاً 11.8.9
curl -fL -o /tmp/m.tar.gz \
  "https://downloads.mariadb.org/rest-api/mariadb/${MDBVER}/mariadb-${MDBVER}-linux-systemd-x86_64.tar.gz"
mkdir -p opt/mariadb && tar -xzf /tmp/m.tar.gz -C opt/mariadb --strip-components=1
```

### ۳-ج. راه‌اندازی datadir (ساخت حساب بدون رمز با socket auth)

```bash
export LD_LIBRARY_PATH=/home/z/my-project/opt/mariadb/lib:$LD_LIBRARY_PATH
opt/mariadb/scripts/mariadb-install-db \
  --basedir=opt/mariadb --datadir=opt/mariadb-data \
  --user=$USER --skip-test-db
```

این دستور حساب `<os-user>@localhost` با احراز هویت socket (بدون رمز) می‌سازد که برای توسعه عالی است. **datadir ماندگار است ولی پردازش سرور ماندگار نیست**؛ در نتیجه در ابتدای هر مکالمهٔ جدید باید سرور را دوباره استارت زد (گام ۵).

### ۳-د. ذخیرهٔ اسکریپت‌های start/stop

`scripts/mariadb-start.sh`:

```bash
#!/usr/bin/env bash
set -e
export LD_LIBRARY_PATH=/home/z/my-project/opt/mariadb/lib:${LD_LIBRARY_PATH:-}
MARIA=/home/z/my-project/opt/mariadb
DATA=/home/z/my-project/opt/mariadb-data
SOCK=$MARIA/mysql.sock; PIDF=$MARIA/mariadb.pid; LOG=$MARIA/error.log
PORT=${1:-13306}
[ -S "$SOCK" ] && { echo "already up: $SOCK"; exit 0; }
rm -f "$SOCK" "$PIDF"
"$MARIA/bin/mariadbd" --basedir="$MARIA" --datadir="$DATA" --socket="$SOCK" \
  --port="$PORT" --bind-address=127.0.0.1 --pid-file="$PIDF" --log-error="$LOG" >/dev/null 2>&1 &
for i in $(seq 1 50); do [ -S "$SOCK" ] && break; sleep 0.2; done
[ -S "$SOCK" ] || { echo "mariadb failed to start:"; tail -15 "$LOG" >&2; exit 1; }
echo "mariadb up: socket=$SOCK port=$PORT pid=$(cat "$PIDF")"
```

`scripts/mariadb-stop.sh`:

```bash
#!/usr/bin/env bash
MARIA=/home/z/my-project/opt/mariadb
SOCK=$MARIA/mysql.sock; PIDF=$MARIA/mariadb.pid
if [ -f "$PIDF" ]; then
  PID=$(cat "$PIDF"); kill "$PID" 2>/dev/null || true
  for i in $(seq 1 30); do kill -0 "$PID" 2>/dev/null || break; sleep 0.2; done
  kill -9 "$PID" 2>/dev/null || true; rm -f "$PIDF"
fi
rm -f "$SOCK"; echo "mariadb stopped."
```

هر دو را با `chmod +x` قابل اجرا کن.

---

## گام ۴ — داربست پروژه

```bash
cd /home/z/my-project
mkdir -p public src tests scripts data
```

`composer.json` (محدودیت PHP را به نسخهٔ پروژه تنظیم کن):

```bash
cat > composer.json <<'EOF'
{
  "name": "app/project",
  "require": { "php": ">=8.2" },
  "require-dev": { "phpunit/phpunit": "^11.0" },
  "autoload": { "psr-4": { "App\\": "src/" } },
  "autoload-dev": { "psr-4": { "Tests\\": "tests/" } },
  "scripts": {
    "serve": "@php -S 127.0.0.1:8000 -t public",
    "test": "phpunit"
  }
}
EOF
bin/composer install
```

`phpunit.xml`:

```bash
cat > phpunit.xml <<'EOF'
<phpunit xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
         bootstrap="vendor/autoload.php" colors="false">
  <testsuites>
    <testsuite name="unit"><directory>tests</directory></testsuite>
  </testsuites>
</phpunit>
EOF
```

ساختار پایه: `public/index.php` (نقطهٔ ورود)، `src/` کلاس‌ها، `tests/` موارد PHPUnit. برای اتصال به MariaDB از PHP:

```php
<?php
$sock = '/home/z/my-project/opt/mariadb/mysql.sock';
$user = get_current_user() ?: getenv('USER');   // حساب socket auth بدون رمز
$m = new mysqli('localhost', $user, '', null, 3306, $sock);  // host=localhost → استفاده از socket
$m->query('CREATE DATABASE IF NOT EXISTS app');
$m->select_db('app');
```

> نکته: برای SQLite از اکستنشن native `sqlite3` استفاده کن (`new SQLite3(__DIR__.'/../data/app.sqlite')`)؛ بیلد استاتیک معمولاً `pdo_sqlite` ندارد.

`scripts/serve.sh` (سرور توسعه با `php -S`):

```bash
#!/usr/bin/env bash
set -e; PORT=${1:-8000}; cd "$(dirname "$0")/.."
echo "Serving on http://127.0.0.1:${PORT}"
exec /home/z/my-project/bin/php -S 127.0.0.1:"$PORT" -t public
```

---

## گام ۵ — استارت سرویس‌ها و تأیید نهایی

پردازش‌ها ماندگار نیستند؛ در هر مکالمه‌ای که به آن‌ها نیاز است، دوباره اجرا کن:

```bash
# دیتابیس (در صورت استفاده از MariaDB) — روی datadir ماندگار، داده‌ها حفظ می‌شوند
bash /home/z/my-project/scripts/mariadb-start.sh

# سرور توسعهٔ HTTP (پیش‌فرض: http://127.0.0.1:8000) — تک‌نخی، مخصوص توسعه
bash /home/z/my-project/scripts/serve.sh
```

تأیید نهایی:

```bash
bin/php -v && bin/composer --version
bin/composer test                                   # PHPUnit
curl -sS http://127.0.0.1:8000/                      # پاسخ برنامه
# در صورت استفاده از MariaDB:
opt/mariadb/bin/mariadb -S opt/mariadb/mysql.sock -u $USER -e 'SELECT VERSION();'
```

اگر همهٔ موارد بالا پاسخ دادند، محیط آمادهٔ توسعه است. برای سرور واقعی چندنخی یا benchmark به گام‌های ۷ و ۸ مراجعه کن.

---

# مسیرهای پیشرفته (اختیاری — گام‌های ۶ تا ۸)

این گام‌ها فقط با درخواست صریح اجرا می‌شوند و در پروژه‌های ساده نیازی به آن‌ها نیست.

## گام ۶ (اختیاری) — بیلد PHP از سورس php-src

### چه زمانی این گام لازم است

باینری استاتیک گام ۱ **همان `php-src`** است که با همهٔ وابستگی‌ها به‌صورت static لینک شده، پس در اغلب موارد چیزی کم نمی‌ماند. این گام فقط در شرایط زیر لازم می‌شود:

- نسخهٔ PHP که `static-php-cli` منتشر نکرده (نسخهٔ قدیمی، snapshot، یا RC خاص)
- نیاز به اکستنشن بخصوصی که در بیلد استاتیک نیست
- اعمال patch روی سورس و کامپایل مجدد
- نیاز به بیلد با فلگ‌های خاص (مثلاً `--enable-debug`، ZTS، ASAN)

منابع رسمی سورس:
- tarball نسخه‌های منتشرشده: `https://www.php.net/distributions/php-<VER>.tar.xz`
- مخزن Git: `https://github.com/php/php-src` (تگ‌ها به‌صورت `php-<VER>`، شاخه‌ها `PHP-<MAJOR>.<MINOR>`)

### ۶-الف. بیلد حداقلی (توصیه‌شده در sandbox)

```bash
cd /home/z/my-project
PHPVER=<نسخه>            # مثلاً 8.4.23
curl -fsSL -o /tmp/php-src.tar.xz "https://www.php.net/distributions/php-${PHPVER}.tar.xz"
mkdir -p /tmp/phps && tar -xf /tmp/php-src.tar.xz -C /tmp/phps --strip-components=1
cd /tmp/phps
./configure --prefix=/home/z/my-project/opt/php-custom \
  --disable-all --enable-cli --enable-json --enable-filter --enable-tokenizer \
  --enable-mbstring --without-onig --enable-pcntl --enable-posix \
  --with-openssl=/usr --with-pdo-sqlite --with-sqlite3
make -j"$(nproc)"
make install
```

> نکته: tarball نسخهٔ release شامل فایل‌های parser/scanner ازپیش‌تولیدشده است، پس به `bison`/`re2c` نیاز ندارد. اگر از `git clone` استفاده می‌کنی، باید `./buildconf --force` را قبل از `./configure` اجرا کنی (که به `bison` و `re2c` نیاز دارد — هر دو در sandbox موجودند).

### ۶-ب. محدودیت‌های واقعی

هدرهای dev برای `libxml2`، `oniguruma`، `libcurl`، `libzip`، `freetype`، `libjpeg`، `libicu` غایب‌اند، پس این بیلد **اکستنشن‌های زیر را نخواهد داشت**:

- `ext/dom`، `ext/simplexml`، `ext/xml`، `ext/xsl` (نیاز به libxml2)
- `ext/mbstring` با Unicode regex کامل (نیاز به oniguruma؛ `--without-onig` بیلد می‌دهد ولی regex فقط POSIX است)
- `ext/curl`، `ext/zip`، `ext/gd` با JPEG، `ext/intl`

در نتیجه این بیلد برای اجرای Composer (که به `ext-json`، `ext-filter`، `ext-mbstring`، و معمولاً `ext-openssl` نیاز دارد) به‌سختی کار می‌کند و برای اغلب پروژه‌ها **پیشنهاد نمی‌شود**. فقط وقتی از باینری استاتیک هم محروم باشی یا نیاز خاصی داشته باشی به‌کار بیاید.

### ۶-ج. راه‌حل اگر وابستگی‌های مفقود لازم است

می‌توان کتابخانه‌های C مفقود را هم از سورس در فضای کاربر بیلد کرد (مثلاً libxml2 از `https://gitlab.gnome.org/GNOME/libxml2`، oniguruma از `https://github.com/kkos/oniguruma`، libzip از `https://github.com/nih-at/libzip`) با `./configure --prefix=/home/z/my-project/opt/libs` و سپس پاس دادن `PKG_CONFIG_PATH=/home/z/my-project/opt/libs/lib/pkgconfig` به `./configure` php-src. این کار شدنی است ولی زمان‌بر است (چند دقیقه تا چند ده دقیقه بیلد) و در اغلب موارد باینری استاتیک گام ۱ همان نتیجه را بدون این زحمت می‌دهد.

---

## گام ۷ (اختیاری) — nginx + PHP-FPM (سرور واقعی چندنخی)

`php -S` (گام ۵) تک‌نخی است و درخواست‌ها را سریال می‌زند؛ برای benchmark واقعی، production-like testing، TLS/HTTPS، HTTP/2، rewrite rules پیشرفته، یا کش static assets مناسب نیست. در این گام nginx و PHP-FPM را در فضای کاربر نصب و پیکربندی می‌کنیم.

### چه زمانی Apache به‌جای nginx؟

Apache httpd هم در فضای کاربر قابل بیلد است ولی وابستگی‌های سنگین‌تری دارد (`apr`، `apr-util`، `pcre`) و برای PHP به `mod_proxy_fcgi` + PHP-FPM نیاز دارد (`mod_php` منسوخ شده). در sandbox دلیلی برای انتخاب Apache به‌جای nginx نمی‌بینیم؛ nginx سبک‌تر است، build ساده‌تری دارد و برای benchmark گزینهٔ بهتری است.

### ۷-الف. پیش‌نیاز: بیلد `-fpm-` از PHP

اگر در گام ۱ بیلد `-cli-` دانلود کرده‌ای، برای PHP-FPM باید بیلد `-fpm-` را هم دانلود کنی (شامل هم `php` و هم `php-fpm`):

```bash
cd /home/z/my-project
PHPVER=<نسخه>            # همان نسخهٔ گام ۱
curl -fsSL -o /tmp/php-fpm.tar.gz \
  "https://dl.static-php.dev/static-php-cli/gnu-bulk/php-${PHPVER}-fpm-linux-x86_64.tar.gz"
mkdir -p /tmp/phpfpm && tar -xzf /tmp/php-fpm.tar.gz -C /tmp/phpfpm
install -m 0755 /tmp/phpfpm/php bin/php
install -m 0755 /tmp/phpfpm/php-fpm bin/php-fpm
bin/php-fpm -v          # تأیید
```

### ۷-ب. بیلد و نصب nginx در فضای کاربر

```bash
cd /home/z/my-project
# کشف آخرین نسخهٔ پایدار nginx:
NGXVER=$(curl -sSL https://nginx.org/en/download.html \
  | grep -oE 'nginx-[0-9]+\.[0-9]+\.[0-9]+' | head -1 | cut -d- -f2)
curl -fL -o /tmp/n.tar.gz "https://nginx.org/download/nginx-${NGXVER}.tar.gz"
mkdir -p /tmp/ngxx && tar -xzf /tmp/n.tar.gz -C /tmp/ngxx --strip-components=1
cd /tmp/ngxx
./configure --prefix=/home/z/my-project/opt/nginx \
  --with-http_ssl_module --with-http_v2_module \
  --without-http_rewrite_module --without-http_gzip_module   # build سبک‌تر؛ در صورت نیاز حذف کن
make -j"$(nproc)" && make install
```

> نکته: `--without-http_rewrite_module` و `--without-http_gzip_module` برای اجتناب از نیاز به PCRE و zlib نصب‌شده‌سیستمی است. اگر rewrite یا gzip لازم است، فلگ `--with-pcre=/path/to/pcre-src` را با PCRE از سورس اضافه کن.

### ۷-ج. پیکربندی PHP-FPM روی Unix socket

```bash
mkdir -p /home/z/my-project/opt
cat > /home/z/my-project/opt/php-fpm.conf <<'EOF'
[global]
pid = /home/z/my-project/opt/php-fpm.pid
error_log = /home/z/my-project/opt/php-fpm.log
daemonize = yes

[www]
listen = /home/z/my-project/opt/php-fpm.sock
listen.mode = 0666
pm = dynamic
pm.max_children = 8
pm.start_servers = 2
pm.min_spare_servers = 1
pm.max_spare_servers = 3
php_admin_value[error_log] = /home/z/my-project/opt/php-errors.log
php_admin_flag[log_errors] = on
EOF
```

### ۷-د. پیکربندی nginx با FastCGI به PHP-FPM

```bash
cat > /home/z/my-project/opt/nginx/conf/nginx.conf <<'EOF'
worker_processes  2;
pid /home/z/my-project/opt/nginx/nginx.pid;
error_log /home/z/my-project/opt/nginx/error.log;

events { worker_connections 256; }

http {
  client_body_temp_path /home/z/my-project/opt/nginx/tmp/client_body;
  proxy_temp_path       /home/z/my-project/opt/nginx/tmp/proxy;
  fastcgi_temp_path     /home/z/my-project/opt/nginx/tmp/fastcgi;
  uwsgi_temp_path       /home/z/my-project/opt/nginx/tmp/uwsgi;
  scgi_temp_path        /home/z/my-project/opt/nginx/tmp/scgi;

  server {
    listen 127.0.0.1:8080;
    server_name localhost;
    root /home/z/my-project/public;
    index index.php;

    location / {
      try_files $uri /index.php?$query_string;
    }

    location ~ \.php$ {
      fastcgi_pass unix:/home/z/my-project/opt/php-fpm.sock;
      fastcgi_index index.php;
      include fastcgi_params;
      fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    }
  }
}
EOF
mkdir -p /home/z/my-project/opt/nginx/tmp
```

### ۷-ه. اسکریپت start/stop

`scripts/web-start.sh`:

```bash
#!/usr/bin/env bash
set -e
PROJ=/home/z/my-project
# PHP-FPM
[ -S "$PROJ/opt/php-fpm.sock" ] || "$PROJ/bin/php-fpm" -y "$PROJ/opt/php-fpm.conf"
# nginx
"$PROJ/opt/nginx/sbin/nginx" -p "$PROJ/opt/nginx" -c "$PROJ/opt/nginx/conf/nginx.conf" 2>/dev/null \
  || "$PROJ/opt/nginx/sbin/nginx" -s reload
echo "web stack up: nginx→127.0.0.1:8080, fpm→opt/php-fpm.sock"
```

`scripts/web-stop.sh`:

```bash
#!/usr/bin/env bash
PROJ=/home/z/my-project
"$PROJ/opt/nginx/sbin/nginx" -p "$PROJ/opt/nginx" -c "$PROJ/opt/nginx/conf/nginx.conf" -s stop 2>/dev/null || true
[ -f "$PROJ/opt/php-fpm.pid" ] && kill "$(cat "$PROJ/opt/php-fpm.pid")" 2>/dev/null || true
rm -f "$PROJ/opt/php-fpm.sock"
echo "web stack stopped."
```

### ۷-و. اجرا و تأیید

```bash
bash scripts/web-start.sh
curl -sS http://127.0.0.1:8080/         # پاسخ از nginx→PHP-FPM
# تست چندنخی (دو درخواست هم‌زمان باید هم‌زمان پاسخ دهند، نه سریال):
curl -sS http://127.0.0.1:8080/ & curl -sS http://127.0.0.1:8080/ & wait
```

---

## گام ۸ (اختیاری) — ابزارهای benchmark

### ۸-الف. سه نوع benchmark

- **benchmark اپلیکیشن (end-to-end)**: سنجش سرعت کل اپ (routing، دیتابیس، منطق، render). به سرور واقعی (گام ۷) و load generator نیاز دارد.
- **benchmark میکرو**: سنجش سرعت یک تابع یا الگوریتم خاص. به سرور HTTP نیاز ندارد.
- **profiling**: یافتن گلوگاه با trace کال‌گراف.

### ۸-ب. نصب `wrk` (load generator برای benchmark اپلیکیشن)

```bash
cd /home/z/my-project
git clone --depth=1 https://github.com/wg/wrk.git opt/wrk
cd opt/wrk && make -j"$(nproc)"
./wrk --version
```

نمونهٔ اجرا (به سرور گام ۵ یا گام ۷ وصل می‌شود):

```bash
# warmup: ۱۰ درخواست اول را discard کن
for i in $(seq 1 10); do curl -sS http://127.0.0.1:8080/ >/dev/null; done
# benchmark واقعی: ۲ thread، ۵۰ اتصال هم‌زمان، ۱۰ ثانیه
opt/wrk/wrk -t2 -c50 -d10s --latency http://127.0.0.1:8080/
```

خروجی `wrk` شامل Requests/sec، Latency Distribution (p50/p75/p90/p99) و Transfer/sec است.

> اگر `wrk` سنگین است، جایگزین سبک‌تر: `hey` (Go) از `https://github.com/rakyll/hey` (باینری ازپیش‌کامپایل‌شده موجود است) یا اسکریپت ساده با `xargs -P` و `curl`.

### ۸-ج. benchmark میکرو با PHPBench

```bash
bin/composer require --dev phpbench/phpbench
# ساختار: benchmarks/ با کلاس‌های *@Revs و @Iterations*
bin/composer phpbench run --report=default
```

یا اسکریپت ساده با `hrtime(true)` (دقت نانوثانیه، بدون وابستگی):

```php
<?php
$N = 100000;
$start = hrtime(true);
for ($i = 0; $i < $N; $i++) {
    // ... کد زیر آزمایش ...
}
$elapsed_ns = hrtime(true) - $start;
printf("total: %.3f ms | per-op: %.0f ns\n", $elapsed_ns/1e6, $elapsed_ns/$N);
```

### ۸-د. profiling

- `xdebug` (از PECL؛ در بیلد استاتیک نیست، باید با `pecl install xdebug` و سپس بارگذاری به‌صورت `zend_extension` اضافه شود — نیازمند bیلد مجدد یا بیلد از سورس با `--enable-debug`).
- یا `xhprof` / `tideways` برای trace کال‌گراف.
- ساده‌ترین: ابزار دستی با `microtime(true)` در نقاط مختلف کد.

### ۸-ه. هشدار مهم دربارهٔ benchmark در sandbox

- **اعداد absolute معنی ندارند**: منابع sandbox (CPU، حافظه، I/O) اشتراکی و متغیر هستند؛ اعداد بدست‌آمده برای محیط تولید قابل تعمیم نیستند.
- **فقط مقایسهٔ relative معتبر است**: دو نسخه از کد را در همان اجرا و با همان شرایط benchmark کن و نسبتشان را مقایسه کن (مثلاً «نسخهٔ B ۱.۲ برابر سریع‌تر از A»).
- **همیشه warmup کن**: اولین درخواست‌ها کُلد هستند (OPcache، cache دیتابیس، JIT). حداقل ۱۰–۲۰ درخواست اول را discard کن.
- **مدت کافی**: حداقل چند ثانیه بار (`-d10s`)، نه یک درخواست.
- **تثبیت OPcache**: برای CLI با `-d opcache.enable_cli=1`، برای سرور با فعال بودن OPcache در php.ini.

---

## گام ۹ (اختیاری) — اشتراک‌گذاری زنده در مرورگر کاربر + بازبینی بصری agent

sandbox به اینترنت خروجی دارد ولی **هیچ پورت ورودی در دسترس عمومی ندارد**؛ یعنی اگر `php -S 0.0.0.0:8000` اجرا کنی، از مرورگر کاربر (بیرون sandbox) قابل دسترسی نیست. راه‌حل تنها **تونل خروجی** است: یک سرویس واسط ترافیک مرورگر کاربر را از یک URL عمومی می‌گیرد و از داخل sandbox به `127.0.0.1:<port>` می‌رساند. این گام دو ابزار متمم را پوشش می‌دهد: یکی برای اشتراک زنده با کاربر، دیگری برای بازبینی بصری توسط خود agent.

### ۹-الف. نصب cloudflared (تونل خروجی، بدون root)

cloudflared باینری تک‌فایلِ ~۴۰MB از Cloudflare است که یک تونل امن به زیرساخت Cloudflare برقرار می‌کند و یک URL عمومی HTTPS برمی‌گرداند. بدون حساب کاربری، بدون systemd، بدون apt.

```bash
cd /home/z/my-project
mkdir -p bin
curl -fL -o bin/cloudflared \
  "https://github.com/cloudflare/cloudflared/releases/latest/download/cloudflared-linux-amd64"
chmod +x bin/cloudflared
bin/cloudflared --version
```

### ۹-ب. اسکریپت تونل

`scripts/tunnel.sh` (همزمان با `serve.sh` یا گام ۷ اجرا می‌شود):

```bash
#!/usr/bin/env bash
# Start a cloudflared "quick" tunnel to expose the local dev server publicly
# over HTTPS. Prints the public URL on the last line. Run alongside serve.sh.
set -e
PROJ=/home/z/my-project
PORT="${1:-8000}"
LOG="${TMPDIR:-/tmp}/cloudflared.log"
PIDF="${TMPDIR:-/tmp}/cloudflared.pid"
if ! curl -fsS --max-time 2 "http://127.0.0.1:${PORT}/" >/dev/null 2>&1; then
  echo "nothing listening on 127.0.0.1:${PORT} — start serve.sh first" >&2; exit 1
fi
"$PROJ/bin/cloudflared" tunnel --no-autoupdate --url "http://127.0.0.1:${PORT}" >"$LOG" 2>&1 &
CFPID=$!
URL=""
for i in $(seq 1 60); do
  URL=$(grep -oE 'https://[a-z0-9-]+\.trycloudflare\.com' "$LOG" 2>/dev/null | head -1)
  [ -n "$URL" ] && break; sleep 0.5
done
[ -z "$URL" ] && { echo "cloudflared did not produce a URL:" >&2; tail -20 "$LOG" >&2; kill "$CFPID" 2>/dev/null; exit 1; }
echo "tunnel pid: $CFPID  (kill to stop)"; echo "$CFPID" > "$PIDF"
echo -n "waiting for URL to go live"
for i in $(seq 1 40); do
  echo -n "."
  code=$(curl -sS --max-time 5 -o /dev/null -w "%{http_code}" "${URL}/" 2>/dev/null || echo 000)
  case "$code" in 200|302|404) echo; echo "reachable: HTTP $code"; echo "$URL"; exit 0;; esac
  sleep 1
done
echo; echo "reachable: not yet verified from sandbox (DNS may still be propagating)"
echo "$URL"
```

### ۹-ج. اجرا و دریافت URL

```bash
# در یک ترمینال: سرور توسعه
bash scripts/serve.sh 8000
# در ترمینال دیگر (یا همان دستور با &): تونل
bash scripts/tunnel.sh 8000
```

خروجی چیزی شبیه:

```
tunnel pid: 26866  (kill to stop)
waiting for URL to go live.....
reachable: HTTP 200
https://des-aspects-ham-phys.trycloudflare.com
```

URL آخر را در مرورگر خودت باز کن — صفحهٔ live برنامه‌ات را با HTTPS می‌بینی. برای توقف: `kill $(cat /tmp/cloudflared.pid)`.

> نکته: propagation چند ثانیه طول می‌کشد؛ اسکریپت تا reachable شدن صبر می‌کند. اگر sandbox نتوانست خودش URL را verify کند (محدودیت DNS)، باز هم URL برای مرورگر بیرونی کار می‌کند — در مرورگر خودت امتحان کن.

### ۹-د. گزینهٔ جایگزین: VSCode Server tunnel

اگر همزمان editor کامل هم می‌خواهی، VSCode در حالت headless با `code tunnel` یک تونل به `vscode.dev` می‌سازد و هم editor کامل (در مرورگر) و هم port forwarding خودکار می‌دهد:

```bash
cd /home/z/my-project
curl -fL -o /tmp/vscode.tar.gz "https://code.visualstudio.com/sha/download?build=stable&os=linux-x64"
mkdir -p opt/vscode && tar -xzf /tmp/vscode.tar.gz -C opt/vscode --strip-components=1
opt/vscode/bin/code tunnel --accept-server-license-terms
# URL: https://<random>.vscode.dev  →  در مرورگر باز کن، پورت 8000 خودکار forward می‌شود
```

نصب سنگین‌تر (~۲۰۰MB) و گاهی ناپایدارتر از cloudflared است؛ فقط وقتی editor هم لازم است انتخاب کن.

### ۹-ه. بازبینی بصری توسط agent با agent-browser (Chromium headless)

این ابزار **برای agent** است، نه برای اشتراک با کاربر. agent می‌تواند صفحهٔ live را در Chromium headless load کند، screenshot بگیرد، DOM را بخواند، و تست E2E اجرا کند. `agent-browser` و Chromium از قبل در sandbox نصب‌اند (در `/usr/local/bin/agent-browser` و `~/.cache/ms-playwright/`).

`scripts/screenshot.sh`:

```bash
#!/usr/bin/env bash
# Screenshot of a URL (default: local dev server) via agent-browser (headless Chromium).
# Saves PNG under download/ for agent visual review. For agent self-review,
# NOT for sharing with the user (use tunnel.sh for live sharing).
set -e
PROJ=/home/z/my-project
URL="${1:-http://127.0.0.1:8000/}"
OUT="${2:-$PROJ/download/preview.png}"
mkdir -p "$PROJ/download"
agent-browser open "$URL" >/dev/null 2>&1
agent-browser screenshot "$OUT" >/dev/null 2>&1
agent-browser close >/dev/null 2>&1 || true
echo "screenshot saved: $OUT ($(du -h "$OUT" | cut -f1))"
```

استفاده:

```bash
bash scripts/screenshot.sh "http://127.0.0.1:8000/?name=Preview" download/preview.png
# agent تصویر را می‌خواند و در مکالمه گزارش می‌دهد که صفحه چه شکلی است
```

سایر دستورات مفید agent-browser (برای تست E2E و استخراج محتوا):

```bash
agent-browser open <url>           # رفتن به صفحه
agent-browser snapshot -i          # درختوسط دسترسیِ عناصر تعاملی با ref مثل @e1
agent-browser click @e1            # کلیک روی عنصر
agent-browser fill @e2 "text"      # پر کردن input
agent-browser screenshot --full    # screenshot کل صفحه
agent-browser close                # بستن مرورگر
```

> اگر agent-browser نصب نبود: `npm install -g agent-browser && agent-browser install`. اگر Playwright خام را به‌جای آن ترجیح دهی، توجه کن که نسخهٔ `playwright` npm باید با نسخهٔ Chromium کش‌شده مطابق باشد؛ در غیر این صورت `npx playwright install chromium` را اجرا کن تا browser متناسب دانلود شود.

### ۹-و. تفاوت دو ابزار — مرز روشن

| نیاز | ابزار | خروجی |
| --- | --- | --- |
| کاربر خودش در مرورگرش صفحه را ببیند و کلیک کند | cloudflared (گام ۹-الف تا ۹-ج) | URL عمومی HTTPS قابل‌اشتراک |
| کاربر editor کامل + port forwarding بخواهد | VSCode tunnel (گام ۹-د) | URL vscode.dev |
| agent صفحه را ببیند و گزارش دهد / تست E2E | agent-browser (گام ۹-ه) | تصویر PNG / DOM snapshot به agent |

دو ابزار متمم‌اند، نه جایگزین: می‌توان همزمان تونل زنده برای کاربر باز بود و agent هم screenshot گرفت.

### ۹-ز. محدودیت‌ها

- URL تونل cloudflared **موقت و random** است و در هر اجرای جدید عوض می‌شود؛ برای production مناسب نیست.
- تونل فقط HTTP/HTTPS را منتقل می‌کند؛ اتصال مستقیم دیتابیس از بیرون ممکن نیست (که این درست است — دیتابیس development نباید عمومی شود).
- پردازش تونل در هر مکالمه باید دوباره استارت زده شود (مانند سایر سرویس‌ها).
- screenshot با agent-browser فقط محتوای رندرشده را نشان می‌دهد؛ برای صفحات plain-text خروجی کم‌رنگ است (طبیعی است).
- URL پیش‌نمایش `preview-<bot-id>.space-z.ai` (مخصوص Next.js در مهارت fullstack-dev) **برای PHP کار نمی‌کند**؛ از cloudflared استفاده کن.

---

## بازنصب پس از ریست sandbox (خلاصه)

اگر sandbox ریست شد و مسیر پروژه هم پاک شده بود، کل زنجیره را با همین ترتیب بازنصب کن: گام ۱ (PHP) → گام ۲ (Composer) → گام ۳ (MariaDB، در صورت نیاز) → گام ۴ (داربست + `composer install`) → گام ۵ (استارت و تأیید). نسخه‌ها را از دستورات کشف انتخاب کن. اگر فقط پردازش‌ها قطع شده‌اند و فایل‌ها باقی‌اند، کافی است گام ۵ (و در صورت استفاده از nginx، گام ۷-ه؛ و در صورت اشتراک زنده، گام ۹-ج) را اجرا کنی.

---

## محدودیت‌ها و نکات شناخته‌شده

- بیلد استاتیک معمولاً **`sqlite3`** دارد ولی **`pdo_sqlite`** نه؛ از `new SQLite3(...)` استفاده کن.
- `php -S` (سرور داخلی) تک‌نخی و مخصوص توسعه است؛ برای benchmark یا production-like به گام ۷ (nginx + PHP-FPM) مراجعه کن.
- پردازش MariaDB و PHP-FPM و nginx و تونل cloudflared در هر مکالمه باید دوباره استارت زده شوند؛ datadir و فایل‌ها ماندگارند.
- URL تونل cloudflared (گام ۹) موقت و random است و در هر اجرای جدید عوض می‌شود؛ برای production مناسب نیست.
- Docker، systemd و apt بدون root در دسترس نیستند؛ همه‌چیز در فضای کاربر است.
- اگر دقیقاً MySQL (نه MariaDB) لازم بود، از tarball «Generic Linux» اوراکل در `dev.mysql.com/get/Downloads/MySQL-<MAJOR>.0/mysql-<VER>-linux-glibc<x>-x86_64-minimal.tar.xz` استفاده کن؛ الگوی راه‌اندازی مشابه MariaDB است.
- برای نسخهٔ خاص PHP که در `gnu-bulk` نیست، از گام ۶ (بیلد از سورس) استفاده کن، با آگاهی از محدودیت‌های اکستنشن.
- بیلد nginx با `--without-http_rewrite_module` و `--without-http_gzip_module` برای اجتناب از نیاز به PCRE/zlib سیستمی است؛ در صورت نیاز به rewrite یا gzip، آن فلگ‌ها را حذف و PCRE/zlib را از سورس بیلد کن.

---

## چک‌لیست کوتاه برای شروع مکالمه

متن زیر را در ابتدای مکالمهٔ جدید به agent بده:

> «سند `/home/z/my-project/download/PHP_DEV_ENV_SETUP.md` را بخوان و محیط توسعهٔ PHP را طبق آن راه‌اندازی کن. این پروژه از PHP `<نسخه>` و دیتابیس `<sqlite|mariadb|none>` استفاده می‌کند. اگر به سرور واقعی یا benchmark نیاز دارم جداً اعلام می‌کنم. دیتابیس و سرور توسعه را استارت بزن و منتظر دستور بعدی من بمان.»
