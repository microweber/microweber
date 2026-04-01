## Done

- [x] 2026-04-01  feat: migrate old MW v2 admin design to Filament 5
  - Reconnaissance: captured screenshots and extracted CSS design tokens from demo.microweber.org
  - Created WelcomeWidget with "Welcome back, [username]" greeting matching MW v2 dashboard
  - Created DashboardQuickStatsWidget with colored icon cards (Emails, Comments, Sales, Orders)
  - Added dashboard widget CSS (welcome heading, 2x2 stat card grid with colored icons)
  - Updated Dashboard page to display welcome + stats widgets before analytics
  - Removed redundant "Dashboard" heading (replaced by welcome message)
  - Theme CSS (microweber-theme-v3.scss) already covers: sidebar, topbar, tables, forms, buttons, badges, tabs, breadcrumbs, pagination, modals, dark mode
  - Built and compiled theme CSS
  - Visual QA verified across: dashboard, pages list, orders, settings, create page
