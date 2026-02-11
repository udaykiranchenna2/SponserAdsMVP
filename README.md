# SponserAdsMVP

A Laravel-based MVP for Sponsor Ads management, built with Laravel 12, Inertia.js, and Vue 3.

## Prerequisites

Ensure you have the following installed on your local machine:

- [PHP 8.2+](https://www.php.net/downloads)
- [Composer](https://getcomposer.org/)
- [Node.js & NPM](https://nodejs.org/)

## Installation

1.  **Clone the repository:**

    ```bash
    git clone <repository-url>
    cd SponserAdsMVP
    ```

2.  **Install Dependencies:**

    ```bash
    composer install
    npm install
    ```

3.  **Configure Environment:**
    Copy the example environment file and generate the application key:

    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

4.  **Database Setup:**
    Create the database connection:

    Migrate the database:

    ```bash
    php artisan migrate
    ```

5.  **Build Assets:**
    ```bash
    npm run build
    ```

---

## Manual Installation

If you prefer to run the steps manually, follow these instructions.

### 1. Install Dependencies

```bash
composer install
npm install
```

### 2. Configure Environment

Copy the example environment file and generate the application key:

```bash
cp .env.example .env
php artisan key:generate
```

### 3. Database Setup

By default, this project is configured to use **SQLite**.

1.  Create database connection:

2.  Run the database migrations:

    ```bash
    php artisan migrate
    ```

### 4. Running the Application

You can run the servers in separate terminal windows:

**Terminal 1 (Laravel Server):**

```bash
php artisan serve
```

**Terminal 2 (Vite Assets):**

```bash
npm run dev
```

**Terminal 3 (Queue Worker):**

```bash
php artisan queue:listen
```

## Database Connection Details

The application uses **SQLite** by default, which requires zero configuration other than creating the file.

### Switching to MySQL/One Postgres

If you prefer to use MySQL or PostgreSQL:

1.  Open your `.env` file.
2.  Update the `DB_CONNECTION` and connection details:

    ```ini
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=your_database_name
    DB_USERNAME=your_username
    DB_PASSWORD=your_password
    ```

3.  Save the file and run migrations:
    ```bash
    php artisan migrate
    ```

## Development Notes

- **Frontend**: Built with Vue 3 and Inertia.js.
- **Styling**: Uses Tailwind CSS.
- **Code Style**: This project uses [Laravel Pint](https://laravel.com/docs/pint). Run `composer run lint` to format your code.
