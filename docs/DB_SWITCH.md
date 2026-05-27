# Quick Database Switch Guide

Use this guide when you only need to switch the app between SQLite and MySQL.

## Use SQLite

SQLite is the default option. In `.env`, use:

```env
DB_DRIVER=sqlite
DB_SQLITE_PATH=database/coffee_shop.db
```

Start the app:

```bash
php -S localhost:8000
```

The SQLite database is stored at `database/coffee_shop.db`.

## Switch To MySQL

Create the database:

```sql
CREATE DATABASE coffee_shop CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Update `.env`:

```env
DB_DRIVER=mysql
DB_MYSQL_HOST=localhost
DB_MYSQL_PORT=3306
DB_MYSQL_USER=root
DB_MYSQL_PASSWORD=your_password
DB_MYSQL_DATABASE=coffee_shop
```

Start the app:

```bash
php -S localhost:8000
```

Tables and sample products are created automatically on first run.

## Switch Back To SQLite

Change `.env` back to:

```env
DB_DRIVER=sqlite
DB_SQLITE_PATH=database/coffee_shop.db
```

## Auto Fallback

If MySQL fails to connect, the app logs the error and falls back to SQLite so the store can still run.

## Environment File

The environment file belongs in the project root:

```bash
cp .env.example .env
```

Then edit `.env` for your local database settings.
