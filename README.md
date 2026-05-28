<<<<<<< HEAD
# Inchangs Boutique Online Website

Laravel-based fashion boutique system for the ITC 121 / ITP 121 final exam. The code uses Laravel controllers, Blade views, sessions, validation, CSRF protection, AJAX requests, DataTables, Query Builder, and a normalized relational database design. No Eloquent models are used.

## Features
- Admin login/logout and customer login/register/logout
- Admin dashboard with counts and sales chart
- Admin management for orders, users, logs, and customer-facing boutique data
- Customer registration, product browsing, search/filtering, cart checkout, and order history
- AJAX form submissions with dynamic feedback
- DataTables search, sorting, and pagination
- Activity logging
- SQL schema: `database/fashion_boutique.sql`
- ERD image: `ERD-fashion-boutique.svg`

## Demo Accounts
- Admin: `admin@inchangsboutique.test` / `admin123`
- Customer: `mia@inchangsboutique.test` / `customer123`

## Setup
1. Import `database/fashion_boutique.sql` in phpMyAdmin or MySQL CLI.
2. Copy `.env.example` to `.env`.
3. Set `DB_DATABASE=fashion_boutique`, `DB_USERNAME=root`, and your MySQL password.
4. Run `composer install` if the `vendor` folder is missing.
5. Run `php artisan key:generate`.
6. Run `php artisan serve` and open `http://127.0.0.1:8000`.

## Exam Checklist
- Laravel Framework: yes
- No Eloquent ORM: yes, all database access uses `DB::table()` Query Builder
- Minimum five tables: yes, six tables included
- Relationships and normalization: users, categories, products, orders, order_items, activity_logs
- SQL file: `database/fashion_boutique.sql`
- ERD: `ERD-fashion-boutique.svg`
- Roles: admin and customer, with customer self-registration
- CRUD: users and order status management; product/category APIs remain available for database-driven data
- DataTables: categories, products, orders, users, logs, my orders
- AJAX: login, customer registration, logout, CRUD, checkout, order status updates
=======
# Final-exam
laravelproject
>>>>>>> d4dc739ecdd50732ac8a5fcd322459a756e76923
