# my-kakeibo-laravel05
A new household accounting book created with Laravel

# Install dependencies
$ sudo apt install php composer sqlite3 php-sqlite3 php-curl php-gd php-pdo php-xml php-zip php-mbstring php-intl

# Setting php.ini
timezone language extension

# Create Laravel project
$ composer create-project laravel/laravel my-kakeibo-laravel05

$ cd my-kakeibo-laravel05

# SQLite3 Configuration
$ touch database/database.sqlite

# Edit .env
DB_CONNECTION=sqlite

DB_DATABASE=/absolute path/to/database/database.sqlite

# Create a table for the household accounting data
$ php artisan make:migration create_expenses_table

# Edit the migration file
Schema::create('expenses', function (Blueprint $table) {
    $table->id();
    $table->string('category');
    $table->integer('amount');
    $table->date('date');
    $table->timestamps();
});

$ php artisan migrate

# Creating models and controllers
$ php artisan make:model Expense -mcr

-m → migration

-c → controller

-r → resource controller

# Creating a view of inputs and outputs (Blade)

# routing

# Start and check
$ php artisan serve --port=8003

# 🧠 Additional Tips
$ composer require laravelcollective/html

# 🔄 Bonus: Reset and input test data
$ php artisan migrate:fresh --seed

# 📜 Conclusion: Why use SQLite and how professional it looks
SQLite is a little weak for production use, but it's perfect for learning, prototyping, and small-scale operations. Laravel also works well with lightweight DBs, making it the perfect combination for learning while you're creating.

🏢 Industry common: "SQLite locally, MySQL/PostgreSQL in production." Another strength of Laravel is that it can be switched using environment variables.

# Updates and Maintenance
$ composer self-update

$ rm composer.lock

$ rm -rf vendor

$ composer update

$ rm package-lock.json

$ rm -rf node_modules

$ node -v

$ nvm install 22

$ npm install


