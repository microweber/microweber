#!/usr/bin/env node

/**
 * Admin Console Error Audit
 *
 * Logs into the Filament admin panel, visits every page from the admin-pages
 * fixture, and reports all console.error messages per page.
 *
 * Usage:
 *   node scripts/audit-admin-console.mjs [--base-url=http://localhost:8000] [--email=admin@admin.com] [--password=admin123]
 *
 * Requirements:
 *   npx playwright install chromium   # first run only
 */

import { chromium } from 'playwright';
import { readFileSync } from 'fs';
import { execSync } from 'child_process';
import { resolve, dirname } from 'path';
import { fileURLToPath } from 'url';

const __dirname = dirname(fileURLToPath(import.meta.url));
const PROJECT_ROOT = resolve(__dirname, '..');

// ---------------------------------------------------------------------------
// CLI args
// ---------------------------------------------------------------------------
function arg(name, fallback) {
    const match = process.argv.find(a => a.startsWith(`--${name}=`));
    return match ? match.split('=').slice(1).join('=') : fallback;
}

const BASE_URL  = arg('base-url', 'http://localhost:8000');
const EMAIL     = arg('email', 'admin@admin.com');
const PASSWORD  = arg('password', 'admin123');

// ---------------------------------------------------------------------------
// Load fixture — PHP file, so we shell out to parse it
// ---------------------------------------------------------------------------
function loadFixture() {
    const phpScript = resolve(PROJECT_ROOT, 'scripts', '_load-admin-fixture.php');
    const out = execSync(`php ${phpScript}`, { encoding: 'utf-8', cwd: PROJECT_ROOT });
    return JSON.parse(out.trim());
}

// ---------------------------------------------------------------------------
// Main
// ---------------------------------------------------------------------------
async function main() {
    const urls = loadFixture();
    console.log(`\n🔍 Auditing ${urls.length} admin pages for console errors\n`);
    console.log(`   Base URL: ${BASE_URL}`);
    console.log(`   Email:    ${EMAIL}\n`);

    const browser = await chromium.launch({ headless: true });
    const context = await browser.newContext({ viewport: { width: 1440, height: 900 } });
    const page = await context.newPage();

    // Collect console errors per page
    const results = {};
    let currentUrl = '';
    const errors = [];

    page.on('console', msg => {
        if (msg.type() === 'error') {
            errors.push(msg.text());
        }
    });

    page.on('pageerror', err => {
        errors.push(`[PageError] ${err.message}`);
    });

    // -----------------------------------------------------------------------
    // Login
    // -----------------------------------------------------------------------
    console.log('⏳ Logging in...');
    await page.goto(`${BASE_URL}/admin/login`, { waitUntil: 'networkidle', timeout: 30000 });
    await page.fill('input[type="email"], input[name="email"]', EMAIL);
    await page.fill('input[type="password"], input[name="password"]', PASSWORD);
    await page.click('button[type="submit"]');
    await page.waitForURL('**/admin', { timeout: 15000 }).catch(() => {});
    await page.waitForLoadState('networkidle');
    console.log('✅ Logged in\n');

    // -----------------------------------------------------------------------
    // Walk every page
    // -----------------------------------------------------------------------
    let pagesWithErrors = 0;
    let totalErrors = 0;

    for (const uri of urls) {
        const url = `${BASE_URL}/${uri}`;
        errors.length = 0; // clear

        try {
            await page.goto(url, { waitUntil: 'networkidle', timeout: 30000 });
            // Small settle time for late-firing JS
            await page.waitForTimeout(500);
        } catch (err) {
            errors.push(`[NavigationError] ${err.message}`);
        }

        if (errors.length > 0) {
            results[uri] = [...errors];
            pagesWithErrors++;
            totalErrors += errors.length;
            console.log(`❌ ${uri}  (${errors.length} error${errors.length > 1 ? 's' : ''})`);
            for (const e of errors) {
                console.log(`     ${e.substring(0, 200)}`);
            }
        } else {
            console.log(`✅ ${uri}`);
        }
    }

    await browser.close();

    // -----------------------------------------------------------------------
    // Summary
    // -----------------------------------------------------------------------
    console.log('\n' + '='.repeat(70));
    console.log(`SUMMARY: ${urls.length} pages visited, ${pagesWithErrors} with errors, ${totalErrors} total errors`);
    console.log('='.repeat(70));

    if (pagesWithErrors > 0) {
        console.log('\nPages with errors:\n');
        for (const [uri, errs] of Object.entries(results)) {
            console.log(`  ${uri}:`);
            // Deduplicate
            const unique = [...new Set(errs)];
            for (const e of unique) {
                console.log(`    - ${e.substring(0, 300)}`);
            }
        }
        process.exit(1);
    } else {
        console.log('\n🎉 No console errors found!\n');
        process.exit(0);
    }
}

main().catch(err => {
    console.error('Fatal error:', err);
    process.exit(2);
});
