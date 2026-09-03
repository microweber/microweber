#!/usr/bin/env node
/**
 * npm >= 12 blocks dependency install scripts until they are listed in the
 * `allowScripts` field of the package.json they are installed from. A blocked
 * esbuild postinstall leaves the platform binary unlinked, so `npm run build`
 * dies long after the install that actually caused it.
 *
 * This helper makes that self-healing: after an install it asks npm which
 * scripts were blocked, records them in package.json (unpinned, so a patch
 * bump of the dependency does not re-block it) and reinstalls so the scripts
 * really run. Approvals are written to disk on purpose — they land in the git
 * diff for review, and they keep `npm ci` in CI clean, which cannot write
 * package.json itself.
 *
 * Usage: node scripts/npm-approve-scripts.mjs <dir> [<dir> ...]
 */
import { execSync } from 'child_process';
import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const npmRun = (args, cwd, capture = false) =>
    execSync(`npm ${args.join(' ')}`, {
        cwd,
        encoding: 'utf8',
        stdio: capture ? ['ignore', 'pipe', 'pipe'] : 'inherit',
    });

/** Names of dependencies whose install scripts npm is currently blocking in `cwd`. */
const listBlocked = (cwd) => {
    let raw;
    try {
        raw = npmRun(['install-scripts', 'ls', '--json'], cwd, true);
    } catch {
        return []; // older npm has no install-scripts command — nothing to approve
    }
    try {
        const parsed = JSON.parse(raw);
        return (parsed.allowScripts ?? []).map((entry) => entry.name).filter(Boolean);
    } catch {
        return [];
    }
};

/**
 * Approve every blocked install script in `cwd` and reinstall so they run.
 * Returns the list of newly approved package names.
 */
export const approveBlockedScripts = (cwd, { reinstall = true } = {}) => {
    if (!fs.existsSync(path.join(cwd, 'package.json'))) return [];

    const blocked = listBlocked(cwd);
    if (blocked.length === 0) return [];

    console.log(`Approving blocked install scripts in ${cwd}: ${blocked.join(', ')}`);
    npmRun(['install-scripts', 'approve', '--no-allow-scripts-pin', ...blocked], cwd);

    if (reinstall) {
        // The scripts were skipped during the install that flagged them. A plain
        // reinstall reports "up to date" and does not refire them, so rebuild the
        // approved packages explicitly.
        npmRun(['install', '--no-audit', '--no-fund'], cwd);
        npmRun(['rebuild', ...blocked], cwd);
    }

    return blocked;
};

const invokedDirectly = process.argv[1] && path.resolve(process.argv[1]) === fileURLToPath(import.meta.url);

if (invokedDirectly) {
    const dirs = process.argv.slice(2);
    if (dirs.length === 0) dirs.push(process.cwd());
    for (const dir of dirs) {
        approveBlockedScripts(path.resolve(dir));
    }
}
