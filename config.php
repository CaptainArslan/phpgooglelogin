<?php

// ┌─────────────────────────────────────────────────────────────────────────┐
// │                   Place this file into root directory                   │
// └─────────────────────────────────────────────────────────────────────────┘

include_once __DIR__ . '/vendor/autoload.php';

// ┌─────────────────────────────────────────────────────────────────────────┐
// │                            OAUTH HANDSHAKE                              │
// └─────────────────────────────────────────────────────────────────────────┘

$credentials = __DIR__ . '/client_secret_252993217581-1tcku9i75uale7d3u44fu1ceg17k0en1.apps.googleusercontent.com.json';

$client = new Google\Client();
$client->setAuthConfig($credentials);
$client->addScope("https://www.googleapis.com/auth/business.manage");
$redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];

