# سرویس‌های خارجی

> نسخه مهارت: **0.0.1** | ارتباط فقط از scaffolds/http-client.php

## پنج قاعده

ارتباط فقط از `scaffolds/http-client.php` (timeout + retry +
traceparent). قواعد: URL و کلید از env؛ پاسخ خارجی Validator-شده
(هرگز blind trust)؛ degradation روشن (اگر سرویس پایین، feature غیرفعال
با پیام، نه crash)؛ هزینه/نرخ سرویس در ADR.

## نمونه اجرایی — «ارسال پیامک با سرویس بیرونی»

```php
// درست — پنج قاعده با هم
$client = new HttpClient(timeout: 5, retries: 2);   // از scaffolds/http-client.php
$key    = getenv('SMS_API_KEY');                    // کلید از env
$resp   = $client->post(
    getenv('SMS_ENDPOINT'),                          // URL از env
    ['Authorization' => 'Bearer ' . $key],
    $payload
);
$v = (new Validator())->rules(['status' => 'int', 'message_id' => 'string']);
if (!$v->check($resp)) {                             // پاسخ اعتبارسنجی
    $logger->warning('sms: invalid response', ['code' => $resp['status'] ?? null]);
    return false;                                    // degradation بدون crash
}
```

```php
// غلط — سه نقض در سه خط
$key = 'sk_live_XXXX';                               // هاردکد
$r = file_get_contents("https://sms.example/send?to=$to&key=$key"); // بدون timeout/retry/اعتبارسنجی + پارامتر در URL
echo $r['message_id'];                               // blind trust روی پاسخ
```

**ADR هزینه/نرخ:** پیش از اتصال هر سرویس بیرونی، یک ADR با: قیمت هر
فراخوانی، سقف نرخ، رفتار در قطعی (feature غیرفعال؟ صف؟). مشورت اجباری
اگر داده حساس جابه‌جا می‌شود (severity >= high → C1..C7).

### ✅ Checklist

- [ ] هر سرویس خارجی ADR دارد
- [ ] پاسخ اعتبارسنجی می‌شود
- [ ] degradation بدون کرش

### ❌ Anti-patterns
- حلقه retry بی‌پایان روی سرویس قطع
- کلید/URL هاردکد در کد

---

> مهارت `php-zero-dep` — نسخه `0.0.1`
