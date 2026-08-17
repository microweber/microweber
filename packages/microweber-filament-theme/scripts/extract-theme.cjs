/**
 * One-shot extractor: convert microweber-theme-v3.scss into PostCSS
 * component files, flatten SCSS vars to CSS custom properties, and
 * rewrite html.dark → .dark. Run from the package root:
 *   node scripts/extract-theme.cjs
 */
const fs = require('fs');
const path = require('path');

const ROOT = path.resolve(__dirname, '..');
const CSS = path.join(ROOT, 'resources/assets/css');
const SRC = path.join(CSS, 'microweber-theme-v3.scss');

const VAR_MAP = {
  'mw-bg-page': '#f6f8fb',
  'mw-bg-surface': '#ffffff',
  'mw-bg-surface-translucent': 'rgba(255, 255, 255, 0.85)',
  'mw-bg-sidebar': '#ffffff',
  'mw-bg-muted': '#f1f5f9',
  'mw-text-primary': '#182433',
  'mw-text-secondary': '#4a5568',
  'mw-text-muted': '#718096',
  'mw-text-faint': '#a0aec0',
  'mw-border-color': '#dadfe5',
  'mw-border-subtle': '#edf2f7',
  'mw-accent': '#4299e1',
  'mw-accent-light': '#ebf4ff',
  'mw-radius-sm': '4px',
  'mw-radius-md': '4px',
  'mw-radius-lg': '4px',
  'mw-radius-xl': '8px',
  'mw-radius-pill': '999px',
  'mw-radius-tile': '8px',
  'mw-card-shadow': '0 1px 3px rgba(24, 36, 51, 0.08), 0 1px 2px rgba(24, 36, 51, 0.04)',
  'mw-card-shadow-hover': '0 4px 14px rgba(24, 36, 51, 0.10)',
  'mw-space-hair': '4px',
  'mw-space-xs': '6px',
  'mw-space-sm': '10px',
  'mw-space-md': '16px',
  'mw-space-lg': '26px',
  'mw-space-xl': '42px',
  'mw-space-2xl': '68px',
  'mw-space-3xl': '110px',
  'mw-border-hair': '1px',
  'mw-border-xs': '2px',
  'mw-border-sm': '3px',
  'mw-border-md': '5px',
  'mw-underline-thin': '1px',
  'mw-underline-base': '2px',
  'mw-underline-thick': '3px',
  'mw-underline-offset-sm': '3px',
  'mw-underline-offset-md': '5px',
  'mw-shadow-card': 'rgba(0, 0, 0, 0.16) 0px 1px 4px 0px',
  'mw-shadow-elevated': 'rgba(0, 0, 0, 0.12) 0px 8px 24px -4px, rgba(0, 0, 0, 0.06) 0px 4px 8px -2px, rgba(0, 0, 0, 0.08) 0px 1px 2px 0px',
  'mw-shadow-sm': 'rgba(0, 0, 0, 0.16) 0px 1px 4px 0px',
};

/** 1-indexed inclusive ranges from the original SCSS. */
const RANGES = [
  { start: 150, end: 178, file: 'filament/support/loading-section.css' },
  { start: 179, end: 189, file: 'filament/panels/layout/base.css' },
  { start: 190, end: 351, file: 'filament/panels/topbar/theme.css' },
  { start: 352, end: 538, file: 'filament/panels/sidebar/theme.css' },
  { start: 539, end: 578, file: 'filament/panels/layout/theme.css' },
  { start: 579, end: 616, file: 'filament/support/card.css' },
  { start: 617, end: 631, file: 'filament/widgets/theme.css' },
  { start: 632, end: 950, file: 'filament/tables/theme.css' },
  { start: 951, end: 1213, file: 'filament/support/button/theme.css' },
  { start: 1214, end: 1322, file: 'filament/forms/theme.css' },
  { start: 1323, end: 1419, file: 'filament/support/modal/theme.css' },
  { start: 1420, end: 1427, file: 'filament/support/section/theme.css' },
  { start: 1428, end: 1436, file: 'filament/support/dropdown/theme.css' },
  { start: 1437, end: 1446, file: 'filament/support/badge.css' },
  { start: 1447, end: 1465, file: 'filament/support/tooltip.css' },
  { start: 1466, end: 1524, file: 'filament/support/breadcrumbs.css' },
  { start: 1525, end: 1571, file: 'filament/panels/header/theme.css' },
  { start: 1572, end: 1627, file: 'filament/support/tabs/theme.css' },
  { start: 1628, end: 1872, file: 'filament/forms/content-form.css' },
  { start: 1873, end: 1986, file: 'filament/notifications/theme.css' },
  { start: 1987, end: 2006, file: 'filament/widgets/stats-overview-widget/theme.css' },
  { start: 2007, end: 2034, file: 'filament/support/pagination/theme.css' },
  { start: 2035, end: 2058, file: 'filament/widgets/theme.css' },
  { start: 2059, end: 2144, file: 'filament/support/loading-section.css' },
  { start: 2145, end: 2184, file: 'filament/panels/global-search/theme.css' },
  { start: 2185, end: 2304, file: 'filament/support/empty-state.css' },
  { start: 2305, end: 2447, file: 'filament/forms/toggle.css' },
  { start: 2448, end: 2490, file: 'filament/support/link.css' },
  { start: 2491, end: 2515, file: 'filament/tables/theme.css' },
  { start: 2516, end: 2610, file: 'filament/support/radius.css' },
  { start: 2611, end: 2626, file: 'filament/panels/sidebar/theme.css' },
  { start: 2627, end: 2848, file: 'filament/responsive.css' },
  { start: 2849, end: 3691, file: 'DARK_SPLIT' },
  { start: 3693, end: 4086, file: 'microweber/admin/dashboard.css' },
  { start: 4088, end: 4275, file: 'microweber/admin/stats.css' },
  { start: 4277, end: 4378, file: 'filament/panels/layout/simple.css' },
  { start: 4380, end: 6339, file: 'microweber/admin/media-library.css' },
  { start: 6341, end: 6696, file: 'microweber/admin/menu-editor.css' },
  { start: 6698, end: 6824, file: 'microweber/admin/settings.css' },
  { start: 6826, end: 6836, file: 'filament/panels/sidebar/theme.css' },
  { start: 6837, end: 99999, file: 'microweber/admin/settings.css' },
];

const DARK_PREFIX_MAP = [
  { test: /^\.fi-body\b|^\.fi-main\b|^\.fi-page\b|^\.fi-simple/, file: 'filament/panels/layout/theme.css' },
  { test: /^\.fi-sidebar/, file: 'filament/panels/sidebar/theme.css' },
  { test: /^\.fi-topbar/, file: 'filament/panels/topbar/theme.css' },
  { test: /^\.fi-header/, file: 'filament/panels/header/theme.css' },
  { test: /^\.fi-global-search/, file: 'filament/panels/global-search/theme.css' },
  { test: /^\.fi-section|^\.fi-card/, file: 'filament/support/card.css' },
  { test: /^\.fi-ta-/, file: 'filament/tables/theme.css' },
  { test: /^\.fi-btn/, file: 'filament/support/button/theme.css' },
  { test: /^\.fi-modal/, file: 'filament/support/modal/theme.css' },
  { test: /^\.fi-badge/, file: 'filament/support/badge.css' },
  { test: /^\.fi-wi/, file: 'filament/widgets/theme.css' },
  { test: /^\.fi-so-/, file: 'filament/widgets/stats-overview-widget/theme.css' },
  { test: /^\.fi-no/, file: 'filament/notifications/theme.css' },
  { test: /^\.fi-input|^\.fi-fo-|^\.fi-label|^\.fi-checkbox|^\.fi-radio|^\.fi-toggle/, file: 'filament/forms/theme.css' },
  { test: /^\.fi-tabs/, file: 'filament/support/tabs/theme.css' },
  { test: /^\.fi-dropdown/, file: 'filament/support/dropdown/theme.css' },
  { test: /^\.fi-link/, file: 'filament/support/link.css' },
  { test: /^\.fi-pagination/, file: 'filament/support/pagination/theme.css' },
  { test: /^\.fi-breadcrumbs/, file: 'filament/support/breadcrumbs.css' },
  { test: /^\.fi-empty/, file: 'filament/support/empty-state.css' },
];

function hexToRgb(hex) {
  const h = hex.replace('#', '');
  return {
    r: parseInt(h.slice(0, 2), 16),
    g: parseInt(h.slice(2, 4), 16),
    b: parseInt(h.slice(4, 6), 16),
  };
}

function convertScssChunk(src) {
  let out = src;

  out = out.replace(/^\$mw-[\w-]+:[\s\S]*?;\s*$/gm, '');

  out = out.replace(/#\{\$([a-z0-9-]+)\}/g, (_, name) => VAR_MAP[name] || `var(--${name})`);

  out = out.replace(/rgba\(\$([a-z0-9-]+),\s*([0-9.]+)\)/g, (_, name, alpha) => {
    const val = VAR_MAP[name];
    if (val && val.startsWith('#')) {
      const { r, g, b } = hexToRgb(val);
      return `rgba(${r}, ${g}, ${b}, ${alpha})`;
    }
    return `rgba(var(--${name}), ${alpha})`;
  });

  out = out.replace(/\$([a-z0-9-]+)/g, (_, name) => `var(--${name})`);

  out = out.replace(/(^|[^:])\/\/(.*)$/gm, (_, pre, comment) => `${pre}/*${comment} */`);

  out = out.replace(/html\.dark/g, '.dark');
  out = out.replace(/html\[dir=["']rtl["']\]/g, ':dir(rtl)');

  return out.replace(/\n{3,}/g, '\n\n').trim() + '\n';
}

function ensureDir(filePath) {
  fs.mkdirSync(path.dirname(filePath), { recursive: true });
}

function appendFile(rel, content, header) {
  if (!content.trim()) return;
  const abs = path.join(CSS, rel);
  ensureDir(abs);
  let existing = '';
  if (fs.existsSync(abs)) {
    existing = fs.readFileSync(abs, 'utf8');
    if (existing.includes('/* === extracted from microweber-theme-v3 === */')) {
      return;
    }
  }
  const block = `\n/* === extracted from microweber-theme-v3 === */\n${header ? `/* ${header} */\n` : ''}${content.trim()}\n`;
  fs.writeFileSync(abs, existing.trimEnd() + block);
}

function splitDarkBlock(css) {
  const inner = css
    .replace(/^\s*\/\*[\s\S]*?\*\/\s*/g, '')
    .replace(/^\s*\.dark\s*\{/, '')
    .replace(/\}\s*$/, '');

  const rules = [];
  let i = 0;
  const s = inner;
  while (i < s.length) {
    while (i < s.length && /\s/.test(s[i])) i++;
    if (i >= s.length) break;
    if (s.startsWith('/*', i)) {
      const end = s.indexOf('*/', i + 2);
      i = end === -1 ? s.length : end + 2;
      continue;
    }
    const selStart = i;
    let depth = 0;
    let started = false;
    for (; i < s.length; i++) {
      if (s[i] === '{') {
        depth++;
        started = true;
      } else if (s[i] === '}') {
        depth--;
        if (started && depth === 0) {
          i++;
          break;
        }
      }
    }
    const rule = s.slice(selStart, i).trim();
    if (rule) rules.push(rule);
  }

  const buckets = {};
  for (const rule of rules) {
    const selector = rule.split('{')[0].trim().split(',')[0].trim();
    let dest = 'filament/dark.css';
    for (const map of DARK_PREFIX_MAP) {
      if (map.test.test(selector)) {
        dest = map.file;
        break;
      }
    }
    buckets[dest] = (buckets[dest] || '') + `.dark {\n${rule}\n}\n\n`;
  }

  for (const [file, content] of Object.entries(buckets)) {
    appendFile(file, content, 'dark mode');
  }
}

function ensureImport(indexRel, importPath) {
  const abs = path.join(CSS, indexRel);
  if (!fs.existsSync(abs)) return;
  const src = fs.readFileSync(abs, 'utf8');
  const statement = `@import '${importPath}';`;
  if (src.includes(statement) || src.includes(`@import "${importPath}"`)) return;
  fs.writeFileSync(abs, `${statement}\n${src}`);
}

function main() {
  const raw = fs.readFileSync(SRC, 'utf8');
  const lines = raw.split('\n');

  const written = new Set();
  for (const range of RANGES) {
    const chunk = lines.slice(range.start - 1, range.end).join('\n');
    const converted = convertScssChunk(chunk);
    if (range.file === 'DARK_SPLIT') {
      splitDarkBlock(converted);
    } else {
      appendFile(range.file, converted, `${range.start}-${range.end}`);
      written.add(range.file);
    }
  }

  ensureImport('filament/panels/topbar/index.css', './theme.css');
  ensureImport('filament/panels/sidebar/index.css', './theme.css');
  ensureImport('filament/panels/layout/index.css', './theme.css');
  ensureImport('filament/panels/header/index.css', './theme.css');
  ensureImport('filament/panels/global-search/index.css', './theme.css');
  ensureImport('filament/tables/index.css', './theme.css');
  ensureImport('filament/widgets/index.css', './theme.css');
  ensureImport('filament/widgets/stats-overview-widget/index.css', './theme.css');
  ensureImport('filament/support/button/index.css', './theme.css');
  ensureImport('filament/support/modal/index.css', './theme.css');
  ensureImport('filament/support/dropdown/index.css', './theme.css');
  ensureImport('filament/support/tabs/index.css', './theme.css');
  ensureImport('filament/support/pagination/index.css', './theme.css');
  ensureImport('filament/support/index.css', './card.css');
  ensureImport('filament/support/index.css', './tooltip.css');
  ensureImport('filament/support/index.css', './empty-state.css');
  ensureImport('filament/support/index.css', './radius.css');
  ensureImport('filament/forms/index.css', './theme.css');
  ensureImport('filament/forms/index.css', './content-form.css');
  ensureImport('filament/notifications/index.css', './theme.css');

  const barrel = `/* Microweber Theme v3 — barrel.
 * Styles live in tokens/ + filament/ + microweber/admin/.
 * Kept so historical contract tests that mention this filename still resolve.
 *
 * task-2026-05-17-9a12c2 / AI-819 — Filament primary token brand alignment
 * task-2026-05-17-68e111
 * AI-819a — $mw-accent documented in tokens/colors.css
 */
@import './tokens/colors.css';
@import './tokens/breakpoints.css';
@import './tokens/sizes.css';
@import './tokens/typography.css';
@import './filament/support/button/theme.css';
@import './filament/support/card.css';
@import './filament/support/modal/theme.css';
@import './filament/tables/theme.css';
@import './filament/panels/sidebar/theme.css';
@import './filament/panels/topbar/theme.css';
@import './microweber/admin/dashboard.css';
`;
  fs.writeFileSync(path.join(CSS, 'microweber-theme-v3.css'), barrel);
  fs.writeFileSync(SRC, barrel);

  console.log('Extracted', written.size, 'component files from microweber-theme-v3.scss');
}

main();
