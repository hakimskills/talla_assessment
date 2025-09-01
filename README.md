# Laravel Filament Gallery

A Laravel 12 + Filament v4 application for managing images, favorites, and a gallery.

## Features

* Image Gallery with search and pagination
* User Favorites management
* Upload and manage personal images
* Modal image preview
* Download images directly from the gallery
* Fully localized (English & Arabic)

---

## Requirements

* PHP 8.2+
* Composer
* Node.js & npm/yarn
* Laravel 12.x
* MySQL or another supported database

---

## Installation

1. **Clone the repository**

```bash
git clone https://github.com/hakimskills/talla_assessment
cd talla_assessment
```

2. **Install PHP dependencies**

```bash
composer install
```

3. **Install Node.js dependencies and compile assets**

```bash
npm install
npm run build
```

4. **Copy `.env` file**

```bash
cp .env.example .env
```
5. Generate application key
```bash
php artisan key:generate
```
6. **Configure environment variables**
   Edit `.env`:

* `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`


7. **Run migrations and seed database**

```bash
php artisan migrate

```

8. **Set up storage link**

```bash
php artisan storage:link
```

9. **Run the local server**

```bash
php artisan serve
```

Access the app at [http://localhost:8000](http://localhost:8000)

---

## Filament Admin Panel

* Default URL: `/auth/register `
* Create a user:


Sign up email, name, and password.

---

## Configure environment variables for DB connection
Edit .env:
```env

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=


```

---
Or do your db connection variables 






