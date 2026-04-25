# Microweber per-module docs

Each `Modules/<X>/docs/README.md` documents a single module's
data model, public API, service classes, events, and tests
according to the canonical
[`MODULE_DOCS_TEMPLATE.md`](./MODULE_DOCS_TEMPLATE.md).

The work of populating those per-module pages is tracked in
[`TODO.md`](../../TODO.md) under the "Per-module `docs/` folder"
section. The 95 modules under `Modules/` are banded into four
tiers by documentation value:

  - **Tier 1** (~25 modules): full data + API docs.
  - **Tier 2** (~10 modules): API-only docs (no rich data model).
  - **Tier 3** (~10 modules): admin tools / widgets — covered in
    aggregate at [`admin-widgets-overview.md`](./admin-widgets-overview.md)
    once shipped.
  - **Tier 4** (~50 modules): pure presentation — covered in
    aggregate alongside Tier 3.

## Index

| Tier | Module          | Status        | Docs                                                                   |
|------|-----------------|---------------|------------------------------------------------------------------------|
| 1    | Content         | ⏳ pending    |                                                                        |
| 1    | Page            | ⏳ pending    |                                                                        |
| 1    | Post            | ⏳ pending    |                                                                        |
| 1    | Product         | ⏳ pending    |                                                                        |
| 1    | Order           | ⏳ pending    |                                                                        |
| 1    | Customer        | ⏳ pending    |                                                                        |
| 1    | Invoice         | ⏳ pending    |                                                                        |
| 1    | Cart            | ⏳ pending    |                                                                        |
| 1    | Checkout        | ⏳ pending    |                                                                        |
| 1    | Coupons         | ⏳ pending    |                                                                        |
| 1    | Shipping        | ⏳ pending    |                                                                        |
| 1    | Tax             | ⏳ pending    |                                                                        |
| 1    | Payment         | ⏳ pending    |                                                                        |
| 1    | Newsletter      | ⏳ pending    |                                                                        |
| 1    | Billing         | ⏳ pending    |                                                                        |
| 1    | ContactForm     | ⏳ pending    |                                                                        |
| 1    | Form            | ⏳ pending    |                                                                        |
| 1    | Comments        | ⏳ pending    |                                                                        |
| 1    | Menu            | ⏳ pending    |                                                                        |
| 1    | Media           | ⏳ pending    |                                                                        |
| 1    | MediaLibrary    | ⏳ pending    |                                                                        |
| 1    | Tag             | ⏳ pending    |                                                                        |
| 1    | Category        | ⏳ pending    |                                                                        |
| 1    | Profile         | ⏳ pending    |                                                                        |
| 1    | Address         | ⏳ pending    |                                                                        |
| 1    | Settings        | ✅ documented | [`Modules/Settings/docs/README.md`](../../Modules/Settings/docs/README.md) |
| 1    | Ai              | ✅ partial    | [`Modules/Ai/README.md`](../../Modules/Ai/README.md) covers MCP + CLI. |
| 2    | OpenApi         | ⏳ pending    |                                                                        |
| 2    | Marketplace     | ⏳ pending    |                                                                        |
| 2    | Updater         | ⏳ pending    |                                                                        |
| 2    | Backup          | ⏳ pending    |                                                                        |
| 2    | Restore         | ⏳ pending    |                                                                        |
| 2    | Export          | ⏳ pending    |                                                                        |
| 2    | Multilanguage   | ⏳ pending    |                                                                        |
| 2    | Translation     | ⏳ pending    |                                                                        |
| 2    | MailTemplate    | ⏳ pending    |                                                                        |
| 2    | Layouts         | ⏳ pending    |                                                                        |
| 2    | LayoutContent   | ⏳ pending    |                                                                        |
| 2    | Offer           | ⏳ pending    |                                                                        |
| 3+4  | (60 modules)    | ⏳ pending    | Will land at [`admin-widgets-overview.md`](./admin-widgets-overview.md). |

Update the **Status** column when shipping each module's docs:

  - `✅ documented` once `Modules/<X>/docs/README.md` covers every
    template section that applies.
  - `🚧 in-progress` if the file exists but is incomplete.
  - `⏳ pending` if the file doesn't exist yet.

## Authoring

  1. Copy [`MODULE_DOCS_TEMPLATE.md`](./MODULE_DOCS_TEMPLATE.md) to
     `Modules/<X>/docs/README.md`.
  2. Walk the module's source — `Models/`, `Http/Controllers/Api/`,
     `Services/`, `routes/api.php`, `database/migrations/`,
     `Tests/`, `config/config.php` — and fill each section.
  3. Cross-link sibling modules and the relevant repo-level pages
     (e.g. `docs/mcp/README.md` for any module with MCP catalog
     entries).
  4. Update the **Status** column above to `✅ documented`.
