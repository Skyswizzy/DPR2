# Pickleball Equipment Inventory: setup

Laravel 11 or newer, PHP 8.2+. Custom authentication (no Breeze) and hand-written CSS, so there is no `npm` step.

## 1. Create the project and install the two packages

```bash
composer create-project laravel/laravel pickleball-inventory
cd pickleball-inventory
composer require barryvdh/laravel-dompdf maatwebsite/excel
```

PHP extensions needed by the Excel/PDF packages: `gd`, `zip`, `mbstring`, `xml`. In XAMPP, enable `gd` and `zip` in `php.ini`, then restart Apache.

## 2. Copy the overlay on top

Copy these folders from the zip into the project root and **choose Merge / Replace** when asked:

```
app/  database/  public/  resources/  routes/
```

Files it replaces: `app/Models/User.php`, `database/seeders/DatabaseSeeder.php`, `routes/web.php`. Everything else is new.

## 3. Configure `.env`

```env
APP_TIMEZONE=Asia/Manila

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pickleball_inventory
DB_USERNAME=root
DB_PASSWORD=
```

Create the empty `pickleball_inventory` database first. (SQLite also works if you leave the Laravel defaults.)

## 4. Migrate, seed, run

```bash
php artisan migrate:fresh --seed
php artisan serve
```

Open http://127.0.0.1:8000 and log in with either:

| Login | Password |
|---|---|
| `admin` | `password` |
| `admin@pickleball.test` | `password` |

## Where each requirement lives

| Requirement | File |
|---|---|
| Login with email **or** username, validation, throttling, secure logout | `app/Http/Controllers/Auth/LoginController.php` |
| Admin pages protected | `auth` middleware group in `routes/web.php` |
| Equipment CRUD | `app/Http/Controllers/EquipmentController.php`, `resources/views/equipment/*` |
| Server-side validation + messages | `app/Http/Requests/EquipmentRequest.php` |
| Old input preserved on errors | `resources/views/equipment/_form.blade.php` (`old()`) |
| `user_id` set on the server | `EquipmentController@store` (created through `$request->user()->equipment()`) |
| Relationships | `User::equipment()` hasMany, `Equipment::user()` belongsTo |
| Four reports + combined filters + reset + empty message | `app/Http/Controllers/ReportController.php`, `resources/views/reports/index.blade.php` |
| PDF / Excel exports matching filters | `ReportController@pdf`, `@excel`, `app/Exports/EquipmentExport.php`, `reports/pdf.blade.php` |
| Delete confirmation | `<dialog>` in `resources/views/layouts/app.blade.php` |
| Layouts with `@extends` / `@section` | `resources/views/layouts/app.blade.php` and every page |
| 8 demo records, 4 categories, both statuses | `database/seeders/DatabaseSeeder.php` |

## Quick demo script for your presentation

1. Log in with `admin`, log out, log in with `admin@pickleball.test`.
2. Add equipment with a negative price and a missing category. Show the messages and that typed values stay.
3. Add a valid item, edit it, delete it (confirm dialog).
4. Reports: open **Equipment Price List**, filter Category = Nets and Availability = Unavailable, click **Reset filters**.
5. Export PDF and Excel with a filter on and show they contain the same rows.
6. Filter to a combination with no rows to show the empty message.
