## About This Project

This Laravel project provides an elegant framework for web development. It includes pre-configured routes, database migrations, seeders, and much more to get started quickly and efficiently.

## Setting Up the Project

Follow these steps to set up the project and prepare the database:

### 1. Clone the Repository

Clone this repository to your local machine:

```bash
git clone https://github.com/arifulhoque7/pos-bakcend-demo.git
cd pos-bakcend-demo
```

### 2. Install Dependencies

Install the required dependencies using Composer:

```bash
composer install
```

### 3. Configure Environment Variables

Copy the `.env.example` file to `.env` and update the database details:

```bash
cp .env.example .env
```

Edit the `.env` file to configure the database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password
```

### 4. Generate Application Key

Generate the application key required by Laravel:

```bash
php artisan key:generate
```

### 5. Run Migrations and Seeders

Set up the database schema and seed the database with demo data:

```bash
php artisan migrate --seed
```

This will:

-   Create all required tables.
-   Populate the database with default and demo data.

### 6. Serve the Application

Run the development server:

```bash
php artisan serve
```

Visit the application in your browser at `http://localhost:8000`.

## Demo Data

When seeding the database, the following data is created:

-   A default user with the following credentials:
    -   **Email:** `arif@yopmail,com`
    -   **Password:** `12345678`
-   Additional demo users, categories, products, and suppliers for testing.

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

---
