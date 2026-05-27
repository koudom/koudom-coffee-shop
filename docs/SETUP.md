# Database Setup Guide

This guide explains how to configure Brew & Bean with SQLite or MySQL.

## Quick Start With SQLite

SQLite is the default database and needs no external server.

1. Copy `.env.example` to `.env` if you want to customize settings.
2. Keep `DB_DRIVER=sqlite`.
3. Start the PHP server.

```bash
cp .env.example .env
php -S localhost:8000
```

The database file is created automatically at `database/coffee_shop.db`.

## Use MySQL

Create the database:

```bash
mysql -u root -p
```

```sql
CREATE DATABASE coffee_shop CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Copy the example environment file if needed:

```bash
cp .env.example .env
```

Update `.env`:

```env
DB_DRIVER=mysql
DB_MYSQL_HOST=localhost
DB_MYSQL_PORT=3306
DB_MYSQL_USER=root
DB_MYSQL_PASSWORD=your_mysql_password
DB_MYSQL_DATABASE=coffee_shop
```

Start the app:

```bash
php -S localhost:8000
```

The app creates tables and inserts sample products automatically on first run.

## Environment Variables

SQLite:

```env
DB_DRIVER=sqlite
DB_SQLITE_PATH=database/coffee_shop.db
```

MySQL:

```env
DB_DRIVER=mysql
DB_MYSQL_HOST=localhost
DB_MYSQL_PORT=3306
DB_MYSQL_USER=root
DB_MYSQL_PASSWORD=
DB_MYSQL_DATABASE=coffee_shop
```

## Auto Fallback

When `DB_DRIVER=mysql` is set but MySQL cannot connect, the app:

1. Logs the MySQL connection error.
2. Switches to SQLite for the current request.
3. Continues running with the local SQLite database.

## Troubleshooting

SQLite issues:

- Make sure PHP has the SQLite PDO extension enabled.
- Make sure the project directory is writable.
- Check that `database/` can be created by the PHP process.

MySQL issues:

- Confirm the MySQL server is running.
- Check host, port, username, password, and database name in `.env`.
- Verify the MySQL user has permission to create tables.
- Confirm the database uses `utf8mb4`.

## Switching Databases

SQLite and MySQL store separate copies of data:

- SQLite data: `database/coffee_shop.db`
- MySQL data: `coffee_shop` database on your MySQL server

To switch, update `DB_DRIVER` in `.env` and refresh the app.
