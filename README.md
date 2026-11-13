# Fix Your Home — API & UI Prototype

A home-services application prototype built with Laravel 12 and React. This version contains versioned API routes for users, workers, services, job requests and admin statistics.

## Technology
PHP 8.2+, Laravel 12, Inertia, React, TypeScript, Tailwind CSS and Vite.

## Repository guide
| Path | Purpose |
| --- | --- |
| [routes/api.php](routes/api.php) | `/v1` API route definitions |
| [app/](app/) | Backend application code |
| [resources/](resources/) | Laravel/Inertia interface |
| [rud/](rud/) | Separate React/Vite interface |
| [database/](database/) | Migrations, factories and seeders |
| [tests/](tests/) | Existing PHP test suite |

## Local setup
1. Install PHP, Composer and Node.js.
2. Run `composer install` and `npm install`.
3. Copy `.env.example` to `.env`, then configure your local database.
4. Run `php artisan key:generate` and `php artisan migrate` against that local database.
5. Run `composer run dev`.

For the separate UI, open `rud/`, run `npm install` and `npm run dev`. Configure its API endpoint for your backend.

## Existing checks
- `composer test` — PHP tests
- `npm run types` — TypeScript checks
- `npm run format:check` — formatting check
- `npm run build` — front-end build

The current API route file does not attach authentication middleware to its `v1` group. Review access controls before using real customer data.

## Related project
[Fix Your Home](https://github.com/Rudra0075-rudi/Fix_Your_Home) is a separate repository with customer, worker and admin flows. The two versions are kept separate.
