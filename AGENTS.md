# Agents.md — Laravel + Inertia.js + Vue + shadcn-vue

> Architecture reference and development standards document for **Laravel + Inertia.js + Vue 3 + shadcn-vue + Tailwind CSS v4** projects.

---

## 1. Tech Stack

| Layer | Technology | Target Version |
|-------|-----------|----------------|
| Backend | Laravel | 12.x |
| Frontend Framework | Vue 3 | 3.5+ (Composition API) |
| Bridge | Inertia.js | 2.x |
| UI Components | shadcn-vue | latest |
| Styling | Tailwind CSS | v4 |
| Build Tool | Vite | 6.x |
| Package Manager | npm / pnpm | — |
| Language | TypeScript | 5.7+ (recommended) |

---

## 2. Folder Structure

```
scrapping-marketplace/
app
 ┣ Http
 ┃ ┣ Controllers
 ┃ ┃ ┣ Api
 ┃ ┃ ┃ ┣ ProductController.php
 ┃ ┃ ┃ ┗ SpkController.php
 ┃ ┃ ┣ Auth
 ┃ ┃ ┃ ┣ AuthenticatedSessionController.php
 ┃ ┃ ┃ ┣ ConfirmablePasswordController.php
 ┃ ┃ ┃ ┣ EmailVerificationNotificationController.php
 ┃ ┃ ┃ ┣ EmailVerificationPromptController.php
 ┃ ┃ ┃ ┣ NewPasswordController.php
 ┃ ┃ ┃ ┣ PasswordController.php
 ┃ ┃ ┃ ┣ PasswordResetLinkController.php
 ┃ ┃ ┃ ┣ RegisteredUserController.php
 ┃ ┃ ┃ ┗ VerifyEmailController.php
 ┃ ┃ ┣ Controller.php
 ┃ ┃ ┗ ProfileController.php
 ┃ ┣ Middleware
 ┃ ┃ ┗ HandleInertiaRequests.php
 ┃ ┗ Requests
 ┃ ┃ ┣ Auth
 ┃ ┃ ┃ ┗ LoginRequest.php
 ┃ ┃ ┗ ProfileUpdateRequest.php
 ┣ Models
 ┃ ┣ MarketplaceData.php
 ┃ ┣ PriceHistory.php
 ┃ ┣ Product.php
 ┃ ┗ User.php
 ┣ Providers
 ┃ ┗ AppServiceProvider.php
 ┗ Services
 ┃ ┣ SAWService.php
 ┃ ┗ WPService.php
bootstrap
 ┣ cache
 ┃ ┣ .gitignore
 ┃ ┣ packages.php
 ┃ ┗ services.php
 ┣ app.php
 ┗ providers.php             
config
 ┣ app.php
 ┣ auth.php
 ┣ cache.php
 ┣ database.php
 ┣ filesystems.php
 ┣ logging.php
 ┣ mail.php
 ┣ queue.php
 ┣ sanctum.php
 ┣ services.php
 ┣ session.php
 ┗ spk.php
database
 ┣ factories
 ┃ ┗ UserFactory.php
 ┣ migrations
 ┃ ┣ 0001_01_01_000000_create_users_table.php
 ┃ ┣ 0001_01_01_000001_create_cache_table.php
 ┃ ┣ 0001_01_01_000002_create_jobs_table.php
 ┃ ┣ 2026_06_22_101013_create_products_table.php
 ┃ ┣ 2026_06_22_101632_create_marketplace_data_table.php
 ┃ ┣ 2026_06_22_101756_create_price_histories_table.php
 ┃ ┗ 2026_06_22_102547_create_personal_access_tokens_table.php
 ┣ seeders
 ┃ ┗ DatabaseSeeder.php
 ┣ .gitignore
 ┗ database.sqlite
resources
 ├─ css
 │  └ app.css
 ├─ js
 │  ├─ api
 │  │  └ client.js
 │  ├─ Components
 │  │  ├─ ui
 │  │  │  ├─ button
 │  │  │  │  ├─ Button.vue
 │  │  │  │  └ index.js
 │  │  │  ├─ card
 │  │  │  │  ├─ Card.vue
 │  │  │  │  ├─ CardAction.vue
 │  │  │  │  ├─ CardContent.vue
 │  │  │  │  ├─ CardDescription.vue
 │  │  │  │  ├─ CardFooter.vue
 │  │  │  │  ├─ CardHeader.vue
 │  │  │  │  ├─ CardTitle.vue
 │  │  │  │  └ index.js
 │  │  ├─ Badge.vue
 │  │  ├─ DataTable.vue
 │  │  ├─ Dialog.vue
 │  │  ├─ Input.vue
 │  │  ├─ Pagination.vue
 │  │  ├─ Select.vue
 │  │  ├─ Slider.vue
 │  │  ├─ Table.vue
 │  │  └ Tabs.vue
 │  ├─ ApplicationLogo.vue
 │  ├─ Checkbox.vue
 │  ├─ DangerButton.vue
```

[Content truncated for brevity - middle sections remain the same]

---

## 7. Common Troubleshooting

| Issue | Solution |
|-------|----------|
| shadcn component not showing / greyed out | Ensure `@import \"tailwindcss\"` is in `app.css` and there is no conflict with old PostCSS config. |
| Form dirty state not working with shadcn Checkbox/Switch | Use a hidden native input or `useForm()` with manual tracking. |
| Ziggy route not recognized | Ensure `ziggy-js` alias is in `vite.config.ts` and `ZiggyVue` plugin is installed. |
| Tailwind classes not applied | Remove old `postcss.config.js` if using Tailwind v4. |
| TypeScript error on Inertia page | Ensure `tsconfig.json` includes `resources/js/**/*.vue` and `resources/js/**/*.ts`. |
| [inertiajs_vue3.js] Cannot read properties of null (reading 'component') | Check `resources/js/app.js` resolve function - ensure page components are properly imported and have default exports. Add null checks before accessing `page.default.layout`. |

---

## 8. References

- [Laravel Documentation](https://laravel.com/docs)
- [Inertia.js Documentation](https://inertiajs.com/)
- [Vue 3 Documentation](https://vuejs.org/)
- [shadcn-vue Documentation](https://www.shadcn-vue.com/)
- [Tailwind CSS v4 Documentation](https://tailwindcss.com/docs/v4-beta)

---

## 9. Github Project Repository

- [scrapping-marketplace](https://github.com/ajiji471/scrapping-marketplace)

---

---

*Last updated: 2026-07-01*