import { exec } from 'child_process';
import { promises as fs } from 'fs';
import path from 'path';

// Directories to scan for second-level subfolders
const directories = ['packages', 'Modules', 'Templates'];

const runNpmScript = (folder) => {
    console.log(`Running 'npm run dev' in ${folder}`);
    exec('npm run dev', { cwd: folder }, (err, stdout, stderr) => {
        if (err) {
            console.error(`Error running npm run dev in ${folder}:`, err);
            return;
        }
        console.log(`Output from ${folder}:`, stdout);
        if (stderr) {
            console.error(`Stderr from ${folder}:`, stderr);
        }
    });
};

// Function to find and execute npm in second-level directories
const findAndRunNpmInSecondLevel = async (dir) => {
    try {
        const files = await fs.readdir(dir);

        for (const file of files) {
            const fullPath = path.join(dir, file);
            const stat = await fs.lstat(fullPath);

            // Only run if it's a second-level directory
            if (stat.isDirectory()) {
                const packageJsonPath = path.join(fullPath, 'package.json');
                try {
                    await fs.access(packageJsonPath);  // Check if package.json exists
                    runNpmScript(fullPath);  // Run npm script if package.json exists
                } catch {
                    // package.json does not exist, skip
                }
            }
        }
    } catch (err) {
        console.error(`Error reading directory ${dir}:`, err);
    }
};

// Run the script in all specified second-level directories
const run = () => {
    directories.forEach(async (dir) => {
        const dirPath = path.resolve(dir);  // Resolve the path of the directory
        await findAndRunNpmInSecondLevel(dirPath);  // Only go one level deep
    });
};

run();
