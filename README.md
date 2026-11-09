# Stockify — Item Stock Management System

A modern warehouse stock management application built with **Laravel 10 + Tailwind CSS + Flowbite**.

## ✨ Features

- 🔐 **Multi-role Authentication** — Admin, Manajer Gudang, Staff Gudang
- 📦 **Product Management** — CRUD with categories, suppliers, and SKU tracking
- 📊 **Stock Management** — Real-time stock in/out with automatic updates
- 📋 **Stock Opname** — Physical inventory reconciliation
- 🏭 **Supplier Management**
- 👥 **User Management**
- 📄 **Reports** — Stock, transactions, and user activity
- 🌓 **Dark Mode** — Full dark theme support

## 🛠️ Tech Stack

| Layer | Technology |
|-------|-----------|
| Backend | Laravel 10 (PHP 8.1+) |
| Database | MySQL 8.0 |
| Frontend | Tailwind CSS 3.4 |
| UI Components | Flowbite 2.5 |
| Charts | ApexCharts |
| Bundler | Vite 5 |

## 🚀 Setup

```bash
# 1. Install PHP dependencies
composer install

# 2. Install JS dependencies
npm install

# 3. Configure environment
cp .env.example .env
php artisan key:generate

# 4. Edit .env with your DB credentials
# DB_DATABASE=stockify
# DB_USERNAME=root
# DB_PASSWORD=

# 5. Import the database
mysql -u root -p < database/sql/06_full_setup.sql

# 6. Build frontend assets
npm run build

# 7. Start server
php artisan serve