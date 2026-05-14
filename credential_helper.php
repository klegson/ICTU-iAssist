<?php

define('CREDENTIAL_ENCRYPT_KEY', 'DepEd-Helpdesk-Cred-Key-2024!@#$');
define('CREDENTIAL_TTL_DAYS', 10);

function encryptPassword($plaintext)
{
    $iv = openssl_random_pseudo_bytes(16);
    $encrypted = openssl_encrypt($plaintext, 'aes-256-cbc', CREDENTIAL_ENCRYPT_KEY, 0, $iv);
    return base64_encode($iv . '::' . $encrypted);
}

function decryptPassword($encoded)
{
    $data = base64_decode($encoded);
    $parts = explode('::', $data, 2);
    if (count($parts) !== 2) return '[error]';
    return openssl_decrypt($parts[1], 'aes-256-cbc', CREDENTIAL_ENCRYPT_KEY, 0, $parts[0]);
}

function credentialsExpired($createdAt)
{
    return strtotime($createdAt) < strtotime('-' . CREDENTIAL_TTL_DAYS . ' days');
}
