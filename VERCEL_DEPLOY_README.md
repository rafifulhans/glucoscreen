# Vercel Deployment Fix - README

## Error Business

The error you saw is a CASCADE of TWO problems:

## Problem 1 (ROOT): Class Pdo\\MySql not found

config/database.php line 61 had:

  'options' => extension_loaded('pdo_mysql') ? array_filter([
      Pdo\\Mysql::ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA')
  ]) : [],

On Vercel's PHP 8.1 (remi/php81) runtime, this Pdo\\Mysql class reference
fails, throwing "Class Pdo\\MySql not found".

FIXED: Changed to simply:

  'options' => [],

## Problem 2 (CASCADE): Read-only filesystem for logs

When Laravel tried to LOG the above error, its default channel tried to
write to storage/logs/laravel.log. Vercel serverless has a READ-ONLY
filesystem except /tmp, so it threw:

  There is no existing directory at storage/logs... Read-only file system

FIXED in config/logging.php:
  - 'default' => 'stderr' (hardcoded)
  - single channel path -> /tmp/laravel.log
  - daily channel path -> /tmp/laravel.log
  - emergency channel -> php://stderr
  - stack uses stderr only

## How to Deploy

  git add config/database.php config/logging.php
  git commit -m "fix: Pdo\\MySql class + stderr logging for Vercel"
  git push origin main

## Verify

Check Vercel deployment logs. The app should boot without fatal error.

## If you still need BOTH file logs AND writable:
Vercel only guarantees /tmp is writable per-request. For durable logs use
stderr (goes to Vercel Logs) or an external service.
