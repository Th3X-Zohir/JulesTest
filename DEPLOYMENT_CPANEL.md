# eCourt Bangladesh - cPanel Deployment Guide

This guide describes how to deploy the **eCourt** application to a standard cPanel shared hosting environment.

## 1. Prerequisites
- cPanel access with File Manager and MySQL Database Wizard.
- PHP 8.2+ enabled in cPanel (MultiPHP Manager).
- SSH access (optional but recommended for running Composer/Artisan).

## 2. Prepare Files for Upload
Run the build script to generate a deployable ZIP file (if available), or manually:
1. Run `composer install --optimize-autoloader --no-dev`.
2. Run `npm run build`.
3. Zip the entire project folder *excluding* `.git`, `node_modules`, and `tests`.

## 3. Upload to cPanel
1. Go to **File Manager**.
2. Upload the `project.zip` to your root directory (e.g., `/home/username/`).
3. Extract it into a folder, e.g., `/home/username/ecourt`.

## 4. Setup Public Folder
Laravel's `public` folder must be the document root.
1. If deploying to the main domain (`public_html`), move the *contents* of `ecourt/public` to `public_html`.
2. Edit `public_html/index.php`:
   Change:
   ```php
   require __DIR__.'/../vendor/autoload.php';
   $app = require_once __DIR__.'/../bootstrap/app.php';
   ```
   To:
   ```php
   require __DIR__.'/../ecourt/vendor/autoload.php';
   $app = require_once __DIR__.'/../ecourt/bootstrap/app.php';
   ```

## 5. Database Setup
1. Go to **MySQL Database Wizard** in cPanel.
2. Create a database (e.g., `user_ecourt`).
3. Create a user (e.g., `user_admin`) and password.
4. Give the user **All Privileges** to the database.

## 6. Environment Configuration
1. Rename `.env.example` to `.env` inside `/home/username/ecourt/`.
2. Edit `.env` with your database credentials:
   ```
   APP_NAME="eCourt"
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://yourdomain.com

   DB_DATABASE=user_ecourt
   DB_USERNAME=user_admin
   DB_PASSWORD=your_password
   ```
3. Generate App Key (if you have SSH):
   `php artisan key:generate`

   *If no SSH:* Run this locally and copy the `APP_KEY` to the server's `.env`.

## 7. Run Migrations
**Option A: SSH (Recommended)**
```bash
cd /home/username/ecourt
php artisan migrate --force
php artisan db:seed --force
```

**Option B: No SSH (Route)**
Create a temporary route in `routes/web.php` (remove immediately after use):
```php
Route::get('/migrate', function() {
    Artisan::call('migrate', ['--force' => true]);
    return 'Migrated!';
});
```
Visit `https://yourdomain.com/migrate`.

## 8. Storage Linking
Symlink the storage folder to public.
**SSH:**
```bash
php artisan storage:link
```
**No SSH:**
Create a PHP file `link.php` in `public_html`:
```php
<?php
symlink('/home/username/ecourt/storage/app/public', '/home/username/public_html/storage');
```
Run it once in the browser.
