# `mobile/` — the parts of `mobile-touch.css`

`mobile-touch.css` (one ~2,600-line monolith) is split here into per-concern
parts for editability. **Edit these files, not `mobile-touch.css`.**

`mobile-touch.css` is a **generated** byte-for-byte concatenation of the sorted
`NN-*.css` files in this directory. The build (`build.cjs → regenerateSplitMonoliths`)
regenerates it from these parts before every bundle build, and
`ThemeCssSplitMonolithDriftContractTest` pins that it stays in sync.

Order matters: the bundle is deliberately `@import`ed **after** `general-styles.css`
so its `!important` floors win equal-specificity battles. Keep the last concern
(`14-media-pagination-selects.css`, ending in the native `select` block) last.

To add a concern: add a new `NN-<slug>.css` (the `NN` prefix sets concat order),
then `cd packages/microweber-filament-theme && npm run build`.
