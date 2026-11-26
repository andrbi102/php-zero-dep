#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""generate_case_studies.py — مطالعهٔ موردی کامل ۱۰-فازی برای هر ۲۰ دستهٔ use-case.

برای هر دسته (docs/use-cases/07..26) یک فایل مطالعهٔ موردی در
docs/use-cases/case-studies/ می‌سازد با:
  - یک کارفرمای فرضی concreet (نام، حوزه، محدودیت)
  - ۱۰ فاز (کشف → تحویل/نگهداری) پیوندی به scaffold/Worker/humanizer/skillspector
  - artifactها و معیار DoD

هر دسته با دادهٔ اختصاصی خود (کارفرما، اسکلت‌ها، امنیت،...) پر می‌شود — نه قالب
generic. سپس یک بخش «مطالعهٔ موردی کامل» به فایل دسته اضافه/به‌روز می‌شود.

اجرا:  python3 scripts/generate_case_studies.py
"""
import re
import sys
from pathlib import Path

ROOT = Path(__file__).resolve().parent.parent
UC_DIR = ROOT / "docs" / "use-cases"
OUT_DIR = UC_DIR / "case-studies"

# ۲۰ دستهٔ v0.0.2 — هرکدام با دادهٔ concreet اختصاصی.
CATEGORIES = [
    {"num": "07", "slug": "publishing", "title": "نشر و محتوای مکتوب",
     "client": "نشر چراغ (نشر مستقل، ۱۲ نفر)", "one_line": "سیستم مدیریت کتاب و مقاله با حقوق مؤلف و DRM نرم",
     "users": "ویراستارها، مؤلفان، خوانندگان", "constraint": "هاست اشتراکی، بدون SSH، متن‌های فارسی RTL",
     "profile": "team", "arch": "MVC + ماژول محتوا", "scaffolds": ["router", "view", "database", "cache", "validator"],
     "data_model": "books, chapters, authors, royalties", "feature": "ویراستاری هم‌زمان فصل + شماره‌گذاری خودکار پاورقی",
     "frontend": "صفحه خواننده RTL با فونت نستعلیق", "humanizer": "معرفی کتاب و خلاصهٔ پشت جلد",
     "worker": "T-053 Content Writer", "moa_use": "انتخاب عنوان پوشش از چند پیشنهاد ویراستار",
     "security": "CSRF روی فرم ویرایش + RateLimiter روی دانلود + hashing حقوق مؤلف",
     "claim": "ادعای «چاپ هفتم»", "deploy": "هاست اشتراکی cPanel + .htaccess",
     "migration": "از مرورگر با محافظت توکن", "maintenance": "پشتیبان‌گیری روزانهٔ SQLite + بازبینی فصلی لینک‌ها"},
    {"num": "08", "slug": "education", "title": "آموزش و دانش",
     "client": "آکادمی کدنویس (موسسه آموزش برنامه‌نویسی)", "one_line": "پلتفرم دوره با آزمون، گواهی و پیشرفت دانشجو",
     "users": "دانشجویان، مدرس‌ها، مدیر آموزش", "constraint": "بازدید هم‌زمان بالا در زمان آزمون",
     "profile": "team", "arch": "HMVC (ماژول دوره/آزمون/گواهی)", "scaffolds": ["hmvc-dispatcher", "session", "csrf", "rate-limiter", "event-store"],
     "data_model": "courses, lessons, quizzes, enrollments, certificates", "feature": "آزمون زمان‌دار با جلوگیری از تقلب (RateLimiter + session lock)",
     "frontend": "داشبورد پیشرفت با design-tokens", "humanizer": "معرفی دوره و بازخورد خودکار",
     "worker": "T-018 API Designer", "moa_use": "طراحی ساختار سؤالات آزمون از چند منظر (سختی/پوشش)",
     "security": "session_regenerate پس از ورود + CSRF روی ثبت‌نام + RateLimiter روی ارسال آزمون",
     "claim": "ادعای «۹۰٪ گذر»", "deploy": "VPS با PHP-FPM + nginx",
     "migration": "خط فرمان با db-migrate", "maintenance": "نگهداری رویدادها در event-store برای تحلیل افت"},
    {"num": "09", "slug": "ecommerce", "title": "تجارت الکترونیک و فروشگاه",
     "client": "بازار دست‌ساز ایران (فروشگاه صنایع‌دستی)", "one_line": "فروشگاه با درگاه پرداخت، سبد و سفارش idempotent",
     "users": "خریداران، فروشندگان خرد، مدیر فروش", "constraint": "پرداخت واقعی + موجودی هم‌زمان",
     "profile": "full", "arch": "Modular + REST API + CQRS", "scaffolds": ["router", "database", "crypto", "rate-limiter", "command-bus", "event-store"],
     "data_model": "products, cart, orders, payments, inventory", "feature": "سفارش idempotent با کلید idempotency + تراکنش موجودی",
     "frontend": "صفحه محصول + سبد با design-tokens", "humanizer": "توضیح محصول و سیاست بازگشت",
     "worker": "T-055 Forms Developer + T-010 Chief Architect", "moa_use": "تصمیم معماری checkout (امنیت vs عملکرد vs UX)",
     "security": "crypto برای توکن درگاه + RateLimiter روی checkout + CSRF + hashing کارت‌های ذخیره‌شده",
     "claim": "ادعای «ارسال ۲۴ ساعته»", "deploy": "هاست با گواهی SSL + Redis-نداشته (FileCache)",
     "migration": "از مرورگر با محافظت", "maintenance": "بازآوری موجودی از event-store + پایش نرخ تبدیل"},
    {"num": "10", "slug": "finance", "title": "مالی، پرداخت و بیمه",
     "client": "تعاونی اعتبار امید (مؤسسه مالی کوچک)", "one_line": "مدیریت وام و اقساط با حسابرسی کامل",
     "users": "اعضا، کارشناس وام، حسابرس", "constraint": "حسابرسی دقیق + رعایت محرمانگی",
     "profile": "full", "arch": "Event Sourcing + CQRS", "scaffolds": ["event-store", "command-bus", "query-bus", "crypto", "tracing"],
     "data_model": "loans, installments, events (audit log)", "feature": "محاسبه اقساط با ثبت رویداد هر تغییر (event-store)",
     "frontend": "داشبورد اقساط با design-tokens", "humanizer": "نامه اقساط و اطلاع‌رسانی تأخیر",
     "worker": "T-011 Security Engineer + T-014 DB Lead", "moa_use": "ارزیابی ریسک وام از چند منظر (مالی/امنیت/انطباق)",
     "security": "crypto libsodium برای دادهٔ حساس + Tracing برای هر تراکنش + RateLimiter روی API",
     "claim": "ادعای «بهره صفر»", "deploy": "سرور اختصاصی با دیسک رمزشده",
     "migration": "خط فرمان + بازآفرینی event-store", "maintenance": "بازیابی از رویدادها + حسابرسی ماهانه"},
    {"num": "11", "slug": "forums", "title": "انجمن، پرسش و پاسخ و پشتیبانی",
     "client": "انجمن داده‌دانان (جامعهٔ تخصصی)", "one_line": "انجمن پرسش‌وپاسخ با رأی‌گیری، تگ و اعتبار",
     "users": "اعضا، ناظران، مدیر", "constraint": "متن بلند فارسی + مهار اسپم",
     "profile": "team", "arch": "MVC + Pipeline middleware", "scaffolds": ["router", "view", "validator", "rate-limiter", "pipeline"],
     "data_model": "threads, posts, votes, tags, reputation", "feature": "اعتبارسنجی پست + مهار اسپم با RateLimiter و فیلتر محتوا",
     "frontend": "نمایش رشته‌ای با design-tokens", "humanizer": "راهنمای استفاده و قوانین انجمن",
     "worker": "T-055 Forms Developer", "moa_use": "رده‌بندی خودکار پست‌ها از چند منظر (موضوع/کیفیت)",
     "security": "CSRF روی رأی و پست + RateLimiter روی ارسال + View::e() روی همهٔ خروجی",
     "claim": "ادعای «پاسخ در ۱ ساعت»", "deploy": "هاست اشتراکی + opcode cache",
     "migration": "از مرورگر", "maintenance": "بایگانی رشته‌های قدیمی + پاکسازی روزانهٔ اسپم"},
    {"num": "12", "slug": "social", "title": "شبکه اجتماعی و ارتباطات",
     "client": "هما (شبکه اجتماعی محلی)", "one_line": "فید، دنبال‌کردن و پیام خصوصی با اعلان لحظه‌ای",
     "users": "کاربران، مدیر محتوا", "constraint": "اعلان Push بدون WebSocket (long-polling)",
     "profile": "full", "arch": "Event-Driven + HMVC", "scaffolds": ["event-dispatcher", "event-store", "cache", "rate-limiter", "hmvc-dispatcher"],
     "data_model": "users, follows, posts, notifications", "feature": "اعلان با EventDispatcher (long-polling سبک)",
     "frontend": "فید با design-tokens + Lazy load", "humanizer": "راهنمای حریم خصوصی و قوانین",
     "worker": "T-016 Integration Lead", "moa_use": "اولویت‌بندی فید از چند سیگنال",
     "security": "session سه‌پرچمی + RateLimiter روی پیام + CSRF + hashing رمز عبور Argon2",
     "claim": "ادعای «رمزگذاری end-to-end»", "deploy": "VPS + PHP-FPM",
     "migration": "خط فرمان", "maintenance": "فشرده‌سازی رویداد قدیمی + بازبینی کش"},
    {"num": "13", "slug": "media", "title": "رسانه صوتی و تصویری",
     "client": "رادیو پادکست فارسی", "one_line": "میزبان پادکست با پخش جریانی و فهرست‌بندی اپیزود",
     "users": "شنوندگان، تولیدکننده، مدیر", "constraint": "پهنای باند محدود + streaming بدون CDN اختصاصی",
     "profile": "team", "arch": "MVC + cache لایه‌ای", "scaffolds": ["router", "cache", "http-client", "rate-limiter", "view"],
     "data_model": "podcasts, episodes, play_history", "feature": "پخش با Range request + cache manifest",
     "frontend": "پخش‌کننده با design-tokens", "humanizer": "توضیح اپیزود و معرفی میزبان",
     "worker": "T-055 Forms Developer", "moa_use": "پیشنهاد اپیزود مرتبط از چند منظر",
     "security": "RateLimiter روی پخش + CSRF + hotlink protection با .htaccess",
     "claim": "ادعای «کیفیت HD»", "deploy": "هاست با پهنای باند بالا + nginx",
     "migration": "از مرورگر", "maintenance": "پاکسازی کش قدیمی + پایش پهنای باند"},
    {"num": "14", "slug": "booking", "title": "تقویم، رزرو، سفر و حمل‌ونقل",
     "client": "تورینو (آژانس مسافرتی کوچک)", "one_line": "رزرو تور و بلیط با تقویم و موجودی صندلی",
     "users": "مسافران، کارشناس فروش", "constraint": "رزرو هم‌زمان + انقضا رزرو موقت",
     "profile": "team", "arch": "MVC + Command Bus", "scaffolds": ["command-bus", "event-store", "cache", "validator", "rate-limiter"],
     "data_model": "tours, seats, reservations, expirations", "feature": "قفل صندلی با انقضا (cache TTL + command)",
     "frontend": "تقویم با design-tokens", "humanizer": "شرایط تور و سیاست لغو",
     "worker": "T-010 Chief Architect", "moa_use": "قیمت‌گذاری پویا از چند سیگنال (فصل/پرشدگی/تقاضا)",
     "security": "crypto برای پرداخت + CSRF + RateLimiter روی رزرو + idempotency",
     "claim": "ادعای «تضمین最低 قیمت»", "deploy": "هاست با SSL + FileCache",
     "migration": "خط فرمان", "maintenance": "آزادسازی رزرو منقضی + گزارش روزانه"},
    {"num": "15", "slug": "classifieds", "title": "آگهی، املاک، خودرو و بازار کار",
     "client": "جدول املاک (نیازمندی‌های محلی)", "one_line": "ثبت آگهی املاک با جستجو و فیلتر پیشرفته",
     "users": "مشتریان، مشاور املاک، مدیر", "constraint": "جستجوی سریع روی هزاران آگهی + ضد اسپم",
     "profile": "team", "arch": "MVC + Graph برای جستجو", "scaffolds": ["router", "database", "graph", "validator", "rate-limiter"],
     "data_model": "listings, agents, districts, filters", "feature": "جستجوی گرافی محله + فیلتر چندبُعدی",
     "frontend": "کارت آگهی با design-tokens", "humanizer": "راهنمای ثبت آگهی و قوانین",
     "worker": "T-053 Content Writer", "moa_use": "دسته‌بندی خودکار آگهی از چند منظر",
     "security": "CSRF روی ثبت + RateLimiter روی آگهی‌های تکراری + View::e()",
     "claim": "ادعای «بدون کمیسیون»", "deploy": "هاست اشتراکی + SQLite",
     "migration": "از مرورگر", "maintenance": "بایگانی آگهی منقضی + بازنمایه‌سازی گراف"},
    {"num": "16", "slug": "health", "title": "سلامت و سبک زندگی",
     "client": "کلینیک سلامت برتر", "one_line": "نوبت‌دهی و پروندهٔ بیمار با حریم خصوصی سخت‌گیرانه",
     "users": "بیماران، پذیرش، پزشک", "constraint": "محرمانگی دادهٔ پزشکی + رعایت انطباق",
     "profile": "full", "arch": "Modular + Event Sourcing برای پرونده", "scaffolds": ["crypto", "event-store", "session", "rate-limiter", "tracing"],
     "data_model": "patients, appointments, records (encrypted)", "feature": "رمزگذاری libsodium پرونده + audit log رویداد",
     "frontend": "نوبت‌دهی با design-tokens", "humanizer": "یادآوری نوبت و توضیح procedure",
     "worker": "T-001 Council Security + T-011 Security Engineer", "moa_use": "ارزیابی ریسک افشای داده از چند منظر",
     "security": "crypto libsodium + Tracing هر دسترسی + session کوتاه + RateLimiter + deny-by-default",
     "claim": "ادعای «تأیید FDA» (هرگز — جلو است)", "deploy": "سرور اختصاصی رمزشده + گواهی",
     "migration": "خط فرمان با رمزگشایی", "maintenance": "چرخش کلید + حسابرسی دسترسی هفتگی"},
    {"num": "17", "slug": "gov", "title": "سازمانی، دولتی و خدمات شهری",
     "client": "شهرداری محلهٔ نمونه", "one_line": "سامانه خدمت شهری (گزارش معابر، پرداخت عوارض)",
     "users": "شهروندان، کارمند شهرداری", "constraint": "دسترس‌پذیری + بایگانی بلندمدت + انطباق",
     "profile": "full", "arch": "Modular + CQRS + Tracing", "scaffolds": ["command-bus", "query-bus", "tracing", "event-store", "rate-limiter"],
     "data_model": "tickets, payments, districts, audit", "feature": "ردیابی درخواست با Tracing (W3C) + بایگانی رویداد",
     "frontend": "فرم خدمت با design-tokens + دسترس‌پذیری", "humanizer": "راهنمای خدمت و پاسخ به پرسش‌های متداول",
     "worker": "T-004 Council Compliance + T-010 Chief Architect", "moa_use": "ارزیابی انطباق از چند منظر (قانون/امنیت/دسترس‌پذیری)",
     "security": "CSRF + RateLimiter + Tracing + deny-by-default + بایگانی tamper-evident",
     "claim": "ادعای «پاسخ فوری»", "deploy": "مرکز دادهٔ دولتی + PHP-FPM",
     "migration": "خط فرمان + بایگانی رویداد", "maintenance": "حفظ بایگانی ۷ ساله + بازبینی انطباق سالانه"},
    {"num": "18", "slug": "productivity", "title": "بهره‌وری، همکاری و ابزار آنلاین",
     "client": "تیم هم‌اکنون (ابزار همکاری تیم‌های کوچک)", "one_line": "وظایف، یادداشت‌ها و اشتراک با هم‌زمانی سبک",
     "users": "اعضای تیم، مدیر", "constraint": "هم‌زمانی بدون WebSocket (long-polling)",
     "profile": "team", "arch": "HMVC + Event-Driven", "scaffolds": ["hmvc-dispatcher", "event-dispatcher", "cache", "session", "rate-limiter"],
     "data_model": "tasks, notes, members, presence", "feature": "حضور با long-polling + یادداشت هم‌زمان (cache lock)",
     "frontend": "بورد کانبان با design-tokens", "humanizer": "راهنمای همکاری و میان‌برها",
     "worker": "T-055 Forms Developer", "moa_use": "اولویت‌بندی وظایف از چند منظر",
     "security": "session سه‌پرچمی + CSRF + RateLimiter روی هم‌زمانی + View::e()",
     "claim": "ادعای «هم‌زمانی واقعی»", "deploy": "VPS + PHP-FPM",
     "migration": "از مرورگر", "maintenance": "فشرده‌سازی رویداد قدیمی + بازبینی کش"},
    {"num": "19", "slug": "ai-data", "title": "هوش مصنوعی و تحلیل داده",
     "client": "خانهٔ داده (استارتاپ تحلیل داده)", "one_line": "پلتفرم pipeline داده با داشبورد و export",
     "users": "تحلیل‌گر، مدیر داده", "constraint": "پردازش دسته‌ای بدون Redis/queue",
     "profile": "full", "arch": "Pipeline + Event Sourcing", "scaffolds": ["pipeline", "command-bus", "event-store", "cache", "tracing"],
     "data_model": "datasets, jobs, results, exports", "feature": "pipeline چندمرحله‌ای با Tracing + cache نتیجه",
     "frontend": "داشبورد با design-tokens", "humanizer": "تفسیر خودکار نتیجه (با حفظ دقت)",
     "worker": "T-019 Data Engineer + T-010 Chief Architect", "moa_use": "انتخاب مدل تحلیل از چند منظر (دقت/هزینه/سرعت)",
     "security": "crypto برای دادهٔ حساس + RateLimiter روی API + Tracing + deny-by-default",
     "claim": "ادعای «دقت ۹۹٪» (باید مستند باشد)", "deploy": "VPS با RAM بالا",
     "migration": "خط فرمان", "maintenance": "بازآفرینی نتیجه از رویداد + پایش pipeline"},
    {"num": "20", "slug": "business", "title": "مدیریت کسب‌وکار و بازاریابی",
     "client": "گروه تجارت آریا (شرکت متوسط)", "one_line": "CRM سبک با سرنخ، فرصت و گزارش فروش",
     "users": "فروش، مدیر فروش", "constraint": "یکپارچه‌سازی با ایمیل بدون SaaS",
     "profile": "team", "arch": "MVC + CQRS", "scaffolds": ["router", "database", "command-bus", "query-bus", "cache"],
     "data_model": "leads, opportunities, contacts, activities", "feature": "قیف فروش با گزارش زنده (query-bus)",
     "frontend": "داشبورد فروش با design-tokens", "humanizer": "قالب ایمیل فروش و یادآوری",
     "worker": "T-053 Content Writer", "moa_use": "امتیازدهی سرنخ از چند سیگنال",
     "security": "CSRF + RateLimiter روی ایمیل + hashing + View::e()",
     "claim": "ادعای «افزایش ۲ برابری فروش»", "deploy": "هاست با SSL + FileCache",
     "migration": "از مرورگر", "maintenance": "بایگانی سرنخ سرد + بازبینی گزارش"},
    {"num": "21", "slug": "games", "title": "بازی و سرگرمی",
     "client": "بازی کلمه (بازی مرورگری فارسی)", "one_line": "بازی کلمه‌سازی چندنفره با امتیاز و رتبه‌بندی",
     "users": "بازیکنان، مدیر بازی", "constraint": "نوبت‌گردانی سبک + ضد تقلب",
     "profile": "team", "arch": "HMVC + Event-Driven", "scaffolds": ["hmvc-dispatcher", "event-store", "cache", "rate-limiter", "session"],
     "data_model": "games, turns, words, scores", "feature": "نوبت با cache TTL + اعتبارسنجی کلمه + ضد تقلب",
     "frontend": "صفحه بازی با design-tokens + انیمیشن سبک", "humanizer": "راهنمای بازی و پیام‌های تشویق",
     "worker": "T-055 Forms Developer", "moa_use": "تولید کلمه از چند منظر (سختی/نادر بودن)",
     "security": "session + CSRF + RateLimiter روی نوبت + View::e() + ضد replay",
     "claim": "ادعای «نمایش نخست»", "deploy": "VPS + PHP-FPM",
     "migration": "از مرورگر", "maintenance": "بایگانی بازی تمام‌شده + پایش تقلب"},
    {"num": "22", "slug": "infra-platform", "title": "زیرساخت، پلتفرم و دانلود",
     "client": "مخزن پکیج فارسی (میزان بسته)", "one_line": "میزبان دانلود بسته با mirror و آمار",
     "users": "توسعه‌دهندگان، مدیر مخزن", "constraint": "پهنای باند بالا + mirror ساده",
     "profile": "full", "arch": "Microservice + Cache", "scaffolds": ["router", "cache", "http-client", "rate-limiter", "tracing"],
     "data_model": "packages, versions, downloads, mirrors", "feature": "هدایت mirror با cache + آمار دانلود",
     "frontend": "صفحه بسته با design-tokens", "humanizer": "راهنمای نصب و توضیح بسته",
     "worker": "T-010 Chief Architect", "moa_use": "انتخاب mirror از چند منظر (جغرافیا/بار/دسترس‌پذیری)",
     "security": "RateLimiter روی دانلود + CSRF روی آپلود + Tracing + checksum",
     "claim": "ادعای «دانلود نامحدود»", "deploy": "سرور با پهنای باند بالا + nginx",
     "migration": "خط فرمان", "maintenance": "هم‌گام‌سازی mirror + پاکسازی نسخه‌های قدیمی"},
    {"num": "23", "slug": "virtual-gallery", "title": "نمایشگاه، موزه و گالری مجازی",
     "client": "گالری هنر معاصر", "one_line": "نمایشگاه مجازی با تور راهنما و فروش اثر",
     "users": "بازدیدکنندگان، گرداننده، هنرمند", "constraint": "تصاویر باکیفیت + بار سبک",
     "profile": "team", "arch": "MVC + HMVC", "scaffolds": ["router", "view", "cache", "database", "rate-limiter"],
     "data_model": "artworks, rooms, tours, sales", "feature": "تور راهنما با cache + فروش اثر",
     "frontend": "نمایشگاه با design-tokens + Lazy load", "humanizer": "توضیح اثر و بیوگرافی هنرمند",
     "worker": "T-053 Content Writer", "moa_use": "توضیح اثر از چند منظر (تاریخی/زیبایی‌شناختی)",
     "security": "CSRF روی خرید + RateLimiter روی تصاویر + hotlink protection",
     "claim": "ادعای «اصالت اثر»", "deploy": "هاست با SSL + CDN سبک",
     "migration": "از مرورگر", "maintenance": "بهینه‌سازی تصویر + بازبینی فروش"},
    {"num": "24", "slug": "local-community", "title": "محلی، اجتماعی و مشارکتی",
     "client": "محلهٔ ما (پلتفرم محلی)", "one_line": "اعلان محلی، رویداد و مشارکت همسایگان",
     "users": "همسایگان، مدیر محله", "constraint": "متن کوتاه + جستجوی محله",
     "profile": "solo", "arch": "MVC سبک", "scaffolds": ["router", "view", "database", "validator", "csrf"],
     "data_model": "announcements, events, members", "feature": "اعلان محله با فیلتر جغرافیایی سبک",
     "frontend": "فید محله با design-tokens", "humanizer": "راهنمای مشارکت و قوانین",
     "worker": "T-055 Forms Developer", "moa_use": "— (solo: moa غیرفعال)",
     "security": "CSRF + RateLimiter + View::e() + session",
     "claim": "ادعای «همسایه‌ی تأییدشده»", "deploy": "هاست اشتراکی + SQLite",
     "migration": "از مرورگر", "maintenance": "بایگانی اعلان قدیمی + پشتیبان روزانه"},
    {"num": "25", "slug": "housing-services", "title": "مسکن و خدمات منزل",
     "client": "خدمات منزل پایدار", "one_line": "درخواست خدمات منزل (نظافت، تعمیر) با رزرو و امتیاز",
     "users": "مشتریان، ارائه‌دهنده خدمات", "constraint": "رزرو + امتیازدهی + پرداخت",
     "profile": "team", "arch": "MVC + Command Bus", "scaffolds": ["router", "database", "command-bus", "rate-limiter", "validator"],
     "data_model": "services, providers, bookings, ratings", "feature": "تطبیق ارائه‌دهنده + امتیازدهی با تأیید",
     "frontend": "کارت خدمت با design-tokens", "humanizer": "شرایط خدمت و سیاست لغو",
     "worker": "T-010 Chief Architect", "moa_use": "تطبیق ارائه‌دهنده از چند سیگنال (امتیاز/فاصله/قیمت)",
     "security": "CSRF + RateLimiter روی رزرو + hashing پرداخت + View::e()",
     "claim": "ادعای «ارائه‌دهندهٔ تأییدشده»", "deploy": "هاست با SSL",
     "migration": "از مرورگر", "maintenance": "بازآوری امتیاز + گزارش روزانه"},
    {"num": "26", "slug": "food-daily", "title": "غذا، رستوران و خرید روزانه",
     "client": "سفره (سامانه سفارش غذا محلی)", "one_line": "منو، سفارش و تحویل با موجودی لحظه‌ای",
     "users": "مشتریان، رستوران، پیک", "constraint": "موجودی لحظه‌ای + ردیابی سفارش",
     "profile": "team", "arch": "MVC + Event-Driven", "scaffolds": ["router", "event-store", "cache", "rate-limiter", "command-bus"],
     "data_model": "restaurants, menu_items, orders, deliveries", "feature": "موجودی لحظه‌ای با cache + ردیابی با event-store",
     "frontend": "منو با design-tokens + سبد", "humanizer": "توضیح غذا و سیاست تحویل",
     "worker": "T-055 Forms Developer", "moa_use": "پیشنهاد غذا از چند منظر (امتیاز/زمان/فاصله)",
     "security": "CSRF + RateLimiter روی سفارش + hashing پرداخت + View::e()",
     "claim": "ادعای «تحویل ۳۰ دقیقه»", "deploy": "هاست با SSL + FileCache",
     "migration": "از مرورگر", "maintenance": "بازآوری موجودی + گزارش روزانه"},
]


def build_case_study(cat: dict) -> str:
    n, slug = cat["num"], cat["slug"]
    sc = ", ".join(f"`{s}`" for s in cat["scaffolds"])
    return f"""# مطالعهٔ موردی — {cat['title']} (دسته {n})

> نسخه مهارت: **0.0.2** | دسته: [`{n}-{slug}.md`](../{n}-{slug}.md)
> مطالعهٔ ۱۰-فازی concreet — کارفرمای فرضی + پیوند به scaffold/Worker/humanizer/skillspector.

## کارفرما

**{cat['client']}** — {cat['one_line']}.
- **کاربران:** {cat['users']}
- **محدودیت کلیدی:** {cat['constraint']}
- **پروفایل پیشنهادی:** {cat['profile']} | **معماری:** {cat['arch']}

---

## ۱۰ فاز

### فاز ۱ — کشف (Discovery)
{cat['client']} به ما مراجعه کرد: {cat['one_line'].lower()}. کاربران هدف {cat['users']} هستند
و محدودیت کلیدی «{cat['constraint']}» است. در این فاز نیازها و قیود ثبت و پروفایل
{cat['profile']} انتخاب می‌شود. خروجی: سند نیازمندی‌ها با قید صریح «هاست اشتراکی،
بدون Composer». نقش راهبر: **{cat['worker']}**.

### فاز ۲ — معماری (Architecture)
با توجه به {cat['constraint']}، معماری **{cat['arch']}** و پروفایل **{cat['profile']}**
انتخاب شد. فازهای F1..F10 به‌ترتیب اجرا می‌شوند. در پروفایل {cat['profile']}، فازهای
غیرضروری (مانند moa اگر solo است) صرفه‌جویی می‌شوند. تصمیم معماری در ADR ثبت می‌شود.

### فاز ۳ — انتخاب اسکلت (Scaffold Selection)
از ۲۹ اسکلت موجود، موارد مرتبط با این دسته: {sc}. دلیل انتخاب: پوشش مستقیم
نیازهای «{cat['feature']}» بدون افزودن وابستگی. هیچ اسکلت خارج‌از‌فهرست استفاده نمی‌شود.

### فاز ۴ — پیاده‌سازی هسته (Core Implementation)
مدل داده: **{cat['data_model']}**. ویژگی امضای این پروژه: {cat['feature']}. پیاده‌سازی
با `declare(strict_types=1)` و Prepared Statements (PDO). خروجی فاز: اسکلت‌های هسته +
migration اولیه.

### فاز ۵ — فرانت‌اند و توکن طراحی (ui-ux-pro-max)
{cat['frontend']} با `design-tokens.php` ساخته می‌شود (primitive→semantic→component،
صدور PHP/JSON/CSS از یک resolver). متن UI («{cat['humanizer']}») برای فاز ۷标记 می‌شود.

### فاز ۶ — ارکستراسیون (hermes-agent)
{cat['worker']} با بدیههٔ delegate فرزندان لازم را spawn می‌کند (در پروفایل {cat['profile']}).
kanban برای مدیریت کارت‌های وظیفه و review برای بازبینی مستقل (SoD-5). moa برای
{cat['moa_use']}. هر Worker یک capability_token با پیشوند `hmac:` دریافت می‌کند.

### فاز ۷ — امنیت (Security)
تمرکز امنیتی این دسته: {cat['security']}. خروجی‌ها با `View::e()` رندر می‌شوند و هیچ
`composer.json` تولید نمی‌شود. در موارد حساس (دادهٔ {cat['title']}) deny-by-default اعمال می‌شود.

### فاز ۸ — انسانی‌سازی متن (humanizer)
`humanizer.php` روی متن UI («{cat['humanizer']}») اجرا می‌شود: کلیشه‌های «بوی AI»
(powerful/seamless/leverage) حذف می‌شوند ولی ادعای واقعی «{cat['claim']}» با claimCheck
حفظ می‌شود (ok=true، lost خالی). هیچ ادعای جعل‌شدهٔ جدید اضافه نمی‌شود.

### فاز ۹ — بازبینی و انتشار (skillspector)
`skill_score.py` با ۶ درگاه (org/security/behavior/ingest-budget/version/content-completeness)
اجرا می‌شود. هدف: `SKILL SCORE: 0/100 [SAFE]` و `completeness: 100%`. review با بازبین
مستقل (SoD-5) پیش از پذیرش هر artifact.

### فاز ۱۰ — تحویل و نگهداری (Handoff/Maintenance)
استقرار در **{cat['deploy']}**. migration با روش «{cat['migration']}». نگهداری: {cat['maintenance']}.
دسترس‌پذیری و بازبینی دوره‌ای لینک‌ها با `check-links.sh` (سه‌ماهه).

---

## artifactهای این مطالعه

- اسکلت‌های کلیدی: {sc}
- Worker راهبر: **{cat['worker']}**
- درگاه کیفی: `skill_score.py` (content-completeness)
- الگوی ترکیب: [`docs/references/06-composition-map.md`](../../references/06-composition-map.md)

---

> مهارت `php-zero-dep` — نسخه `0.0.2` | دستهٔ متناظر: [`{n}-{slug}.md`](../{n}-{slug}.md)
"""


def link_section(cat: dict) -> str:
    n, slug = cat["num"], cat["slug"]
    return (f"\n\n## مطالعهٔ موردی کامل\n\n"
            f"یک مطالعهٔ ۱۰-فازی concreet (کارفرما + scaffoldها + امنیت + humanizer + skillspector) "
            f"برای این دسته: [`case-studies/{n}-{slug}-case-study.md`](case-studies/{n}-{slug}-case-study.md).\n")


def main() -> int:
    if len(CATEGORIES) != 20:
        sys.exit(f"FATAL: categories={len(CATEGORIES)} (expected 20)")
    OUT_DIR.mkdir(parents=True, exist_ok=True)
    written = 0
    linked = 0
    for cat in CATEGORIES:
        # 1) فایل مطالعهٔ موردی
        out = OUT_DIR / f"{cat['num']}-{cat['slug']}-case-study.md"
        out.write_text(build_case_study(cat), encoding="utf-8")
        written += 1
        # 2) افزودن بخش پیوند به فایل دسته (idempotent)
        cat_file = UC_DIR / f"{cat['num']}-{cat['slug']}.md"
        if cat_file.is_file():
            txt = cat_file.read_text(encoding="utf-8")
            if "مطالعهٔ موردی کامل" not in txt:
                # حذف بخش پایانی تکراری اگر وجود داشت (نسخهٔ قدیمی) سپس افزودن
                txt = re.sub(r"\n## مطالعهٔ موردی کامل\n.*?(?=\n---|\Z)", "", txt, flags=re.DOTALL)
                cat_file.write_text(txt.rstrip() + link_section(cat), encoding="utf-8")
                linked += 1
    print(f"case studies written: {written}/20")
    print(f"category files linked: {linked}/20")
    print("PASS: 10-phase case studies generated")
    return 0


if __name__ == "__main__":
    sys.exit(main())
