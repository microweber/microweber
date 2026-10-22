# `general/` — the parts of `general-styles.css`

`general-styles.css` (one ~6,000-line monolith) is split here into per-concern
parts for editability. **Edit these files, not `general-styles.css`.**

`general-styles.css` is a **generated** byte-for-byte concatenation of the sorted
`NN-*.css` files in this directory. The build (`build.cjs → regenerateSplitMonoliths`)
regenerates it from these parts before every bundle build, and
`ThemeCssSplitMonolithDriftContractTest` pins that it stays in sync.

Why the monolith is kept: `microweber/index.css` still `@import`s
`general-styles.css`, and ~40 contract tests read its bytes — so the compiled
output is guaranteed unchanged by the split.

To add a concern: add a new `NN-<slug>.css` (the `NN` prefix sets concat order),
then `cd packages/microweber-filament-theme && npm run build`.
