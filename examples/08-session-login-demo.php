<?php

declare(strict_types=1);

/**
 * مثال ۸ — جریان login با نشست امن و Argon2id
 * اجرا: php examples/08-session-login-demo.php
 *
 * @package PhpZeroDep\Examples
 * @version 0.0.2
 */
require __DIR__ . '/../scaffolds/session.php';
require __DIR__ . '/../scaffolds/crypto.php';

$hash = Crypto::passwordHash('correct horse battery staple');
echo "Argon2id hash: ", substr($hash, 0, 20), "...\n";
echo Crypto::passwordVerify('correct horse battery staple', $hash) ? "verify(correct): OK\n" : "FAIL\n";
echo Crypto::passwordVerify('wrong password', $hash) ? "FAIL\n" : "verify(wrong): rejected OK\n";

SecureSession::start();
$_SESSION['user_id'] = 42;
$before = session_id();
SecureSession::regenerate(); // پس از login الزامی
echo $before !== session_id() ? "session id regenerated: OK\n" : "FAIL (same id)\n";
