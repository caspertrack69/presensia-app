# Changelog

## 2025-10-27

- Switched Tailwind to class-based dark mode and added base `color-scheme` tokens to ensure consistent theming across components.
- Refactored the authentication login page with a glassmorphism layout, responsive CTA buttons, and optional links that respect route availability.
- Extracted the QR scanner logic into `resources/js/modules/qr-scanner.js` with ZXing support, centralising camera lifecycle, verification, and attendance workflows.
- Simplified navigation cards and attendance tables to support the new theme styling, including responsive tweaks on the employee attendance history screen.
- Updated the sidebar component with a compact glass UI, refreshed active states, and aligned theme/logout controls.
- Hardened optional features by guarding `password.request` and `register` route usage in Blade templates.
