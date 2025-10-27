# Presensia – QR Attendance Platform

Presensia is a Laravel-based attendance platform that blends Dynamic QR codes, selfie validation, and geofencing into a sleek glassmorphism interface. Employees, managers, and admins get tailored dashboards while the theming system keeps the experience cohesive in both light and dark modes.

## What's New (2025-10-27)

- Introduced a glass-inspired login hero and form, complete with backdrop blur, layered highlights, and route-safe CTAs.
- Enabled class-based dark mode in Tailwind and standardised `color-scheme` tokens for consistent native theming.
- Moved QR scanner behaviour into `resources/js/modules/qr-scanner.js`, centralising camera lifecycle, ZXing parsing, and attendance submission flows.
- Refined the employee attendance history view with dark-ready tables, responsive stacking, and improved pagination layout.
- Rebuilt the sidebar into a compact glass panel with refreshed active states, indicators, and theme/logout controls.
- Added guards around optional authentication routes so instances without password reset or self-registration do not fail.

## Core Features

- Dynamic QR generation with expiry safeguards.
- Offline-aware QR scanning that resumes when connectivity returns.
- Selfie capture and geolocation checks for reliable attendance validation.
- Role-driven dashboards for employees, managers, and admins.
- Theme switcher with persistent preference and seamless dark/light palettes.

## Tech Stack

- **Framework:** Laravel 10+
- **Frontend:** Blade, Tailwind CSS, Alpine.js
- **Build Tool:** Vite
- **QR Scanner:** ZXing (`@zxing/library`)
- **Database:** MySQL (configurable)

## Getting Started

```bash
# Install dependencies
composer install
npm install

# Environment bootstrap
cp .env.example .env
php artisan key:generate

# Database migrations & seed data
php artisan migrate:fresh --seed

# Serve application & assets
php artisan serve
npm run dev
```

Default seeded accounts can be found in `SETUP_GUIDE.md` along with environment notes and troubleshooting tips.

## Development Scripts

| Command          | Description                                   |
|------------------|-----------------------------------------------|
| `npm run dev`    | Vite dev server with HMR                      |
| `npm run build`  | Production asset build                        |
| `php artisan serve` | Laravel HTTP server                        |
| `php artisan test`  | Run feature/unit test suite                |

## Documentation & Support

- **Setup Guide:** `SETUP_GUIDE.md`
- **Changelog:** `CHANGELOG.md`
- **Issue Tracking:** Use GitHub Issues or your team’s tracker.

## License

This project is proprietary software. All rights reserved.
