<?php

declare(strict_types=1);

/**
 * مثال ۲ — فرم با توکن CSRF امضاشده HMAC
 * اجرا: APP_KEY=test-key-32-bytes-aaaaaaaaaaaaaa php examples/02-form-csrf-demo.php
 *
 * @package PhpZeroDep\Examples
 * @version 0.0.2
 */
require __DIR__ . '/../scaffolds/session.php';
require __DIR__ . '/../scaffolds/csrf.php';

if (getenv('APP_KEY') === false || getenv('APP_KEY') === '') {
    putenv('APP_KEY=demo-key-not-for-production-0123456789');
}

$field = Csrf::field();
echo "form field: ", $field, "\n";

preg_match('/value="([^"]+)"/', $field, $m);
$token = $m[1] ?? '';

echo Csrf::verify($token) ? "verify(correct token): OK\n" : "verify(correct token): FAIL\n";
echo Csrf::verify('forged') ? "verify(forged): FAIL\n" : "verify(forged): rejected OK\n";
