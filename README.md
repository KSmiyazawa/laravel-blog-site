# Laravel Blog Site

A modern blog application built with Laravel, featuring user authentication, blog post management, and a clean, responsive interface.

![image](https://github.com/user-attachments/assets/bc50bf98-b40b-4ade-89d6-45301490f423)

## Features

- Username-based authentication (no email)
- Blog post CRUD with featured image support
- Public access to blog list and detail pages
- Inertia.js + React frontend
- Responsive, clean layout

## Requirements

- PHP 8.1 or higher
- Composer
- Node.js and NPM
- MySQL or PostgreSQL
- Git

## Installation

1. Clone the repository:
```bash
git clone https://github.com/KSmiyazawa/laravel-blog-site.git
cd laravel-blog-site
```

2. Install dependencies:
```bash
composer install
npm install

```

3. Set up environment:
```bash
cp .env.example .env
php artisan key:generate
```

4. Configure your .env file:
```bash
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=laravel_blog
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

5. Run migrations:
```bash
php artisan migrate
```

6. Link storage for images:
```bash
php artisan storage:link
```

7. Build assets:
```bash
npm run build
```

8. Start the server:
```bash
php artisan serve
```
The application should now be running at http://localhost:8000

## Creating Test Data

The application comes with a seeder that creates test users and blog posts. To populate your database with test data:

```bash
php artisan migrate:fresh --seed
```

## Test Credentials

- Username: `testuser`
- Password: `password`

## Development Commands

- Run tests: `php artisan test`
- Dev build: `npm run dev`
- Production build: `npm run build`
- Reseed DB: `php artisan migrate:fresh --seed`

## Project Structure

- `app/Http/Controllers` - Application controllers
- `app/Models` - Eloquent models
- `database/factories` - Model factories for testing
- `database/seeders` - Seeder setup
- `resources/js/Pages` - Inertia.js page components
- `routes` - Application routes
- `storage/app/public` - Uploaded images

## Contributing

1. Fork the repository
2. Create your feature branch `git checkout -b feature/your-feature`
3. Commit your changes `git commit -m 'feat: add new feature'`
4. Push to the branch `git push`
5. Open a Pull Request
