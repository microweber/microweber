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

- [x] 2026-04-01  feat: migrate dashboard chart widget from Chart.js to ECharts
  - Created SiteStatsEchartsWidget replacing SiteStatsDashboardChart (Chart.js)
  - Built ECharts area chart with smooth line, gradient fill, matching MW v2 style
  - Added Statistics card UI: icon + title, online count, Daily/Weekly/Monthly period tabs
  - Footer with views/visitors counters and "Show more" link
  - Updated SiteStatsServiceProvider to register new ECharts widget
  - Added .mw-stats-card CSS with dark mode support to theme SCSS
  - Built and compiled theme CSS

- [x] 2026-04-01  fix: sidebar inconsistencies between MW v2 and Filament 5
  - Fixed truncated sidebar text ("Variant Attri..." now shows full "Variant Attributes")
  - Removed white-space: nowrap from sidebar labels, allowing text to wrap naturally
  - Improved group header labels: darker color (#4a5568), slightly larger (0.7rem), better letter-spacing
  - Added subtle spacing (4px margin/padding) between navigation groups
  - Softened group separator border opacity (0.14 → 0.10)
  - Widened sidebar from 15rem to 16rem to accommodate longer labels
  - Fixed dark mode group separator border color (rgba white 6%)
  - Visual QA verified across: dashboard, pages list, settings

- [ ] make a plan for the full admin and add to to todo list , anumarate all new and ols pages and map them with [ ] items
