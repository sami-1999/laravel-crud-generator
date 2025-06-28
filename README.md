# Laravel CRUD Generator

![Packagist Version](https://img.shields.io/packagist/v/muhammadsami/laravel-crud-generator)
![License](https://img.shields.io/github/license/muhammadsami-dev/laravel-crud-generator)
![Stars](https://img.shields.io/github/stars/muhammadsami-dev/laravel-crud-generator?style=social)

**Generate Laravel CRUD boilerplate using the Repository Pattern — in seconds.**  
Built with  by [Muhammad Sami](https://www.linkedin.com/in/muhammad-sami-600700182/)

![Demo](https://raw.githubusercontent.com/muhammadsami-dev/laravel-crud-generator/main/preview.png)

---

## ✨ Features

- 🔧 Generates: Model, Migration, Form Request, Controller
- 💡 Repository & Interface Pattern
- ⚙️ Artisan Command Based
- 🧱 PSR-4 Autoloaded Structure
- ✏️ Stubs Fully Customizable
- 🧼 Clean, Scalable & Maintainable Code

---

## 📦 Installation

Install via Composer:

```bash
composer require muhammadsami/laravel-crud-generator



⚡ Usage
Run the command to scaffold a full CRUD:


php artisan make:crud Product

This will generate:

app/Models/Product.php

app/Http/Requests/ProductRequest.php

app/Interfaces/ProductInterface.php

app/Repositories/ProductRepository.php

app/Http/Controllers/Api/ProductController.php

database/migrations/xxxx_xx_xx_xxxxxx_create_products_table.php

🛠 Configuration Steps

1. Bind Interface to Repository
Update AppServiceProvider:


public function register()
{
    $this->app->bind(
        \App\Interfaces\ProductInterface::class,
        \App\Repositories\ProductRepository::class
    );
}
2. Define Routes
In routes/api.php:


use App\Http\Controllers\Api\ProductController;

Route::apiResource('products', ProductController::class);
3. Customize Migration
You can modify the generated migration file before running:

php artisan migrate
