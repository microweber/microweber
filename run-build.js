import { exec } from 'child_process';
import { promises as fs } from 'fs';
import path from 'path';

// Directories to scan for second-level subfolders
const directories = ['packages', 'Modules', 'Templates'];

const runNpmScript = (folder) => {
    return new Promise((resolve, reject) => {
        console.log(`Running 'npm install' in ${folder}`);
        exec('npm install', { cwd: folder }, (err, stdout, stderr) => {
            if (err) {
                console.error(`Error running npm install in ${folder}:`, err);
                reject(err);
                return;
            }
            console.log(`Output from npm install in ${folder}:`, stdout);
            if (stderr) {
                console.error(`Stderr from npm install in ${folder}:`, stderr);
            }

            console.log(`Running 'npm run build' in ${folder}`);
            exec('npm run build', { cwd: folder }, (err, stdout, stderr) => {
                if (err) {
                    console.error(`Error running npm run build in ${folder}:`, err);
                    reject(err);
                    return;
                }
                console.log(`Output from npm run build in ${folder}:`, stdout);
                if (stderr) {
                    console.error(`Stderr from npm run build in ${folder}:`, stderr);
                }
                resolve();
            });


        });
    });
};

// Function to find and execute npm in second-level directories
const findAndRunNpmInSecondLevel = async (dir) => {
    try {
        const files = await fs.readdir(dir);
        const promises = [];

        for (const file of files) {
            const fullPath = path.join(dir, file);
            const stat = await fs.lstat(fullPath);

            // Only run if it's a second-level directory
            if (stat.isDirectory()) {
                const packageJsonPath = path.join(fullPath, 'package.json');
                try {
                    await fs.access(packageJsonPath);  // Check if package.json exists
                    promises.push(runNpmScript(fullPath));  // Run npm script if package.json exists
                } catch {
                    // package.json does not exist, skip
                }
            }
        }

        await Promise.all(promises);  // Run all npm scripts in parallel
    } catch (err) {
        console.error(`Error reading directory ${dir}:`, err);
    }
};

// Run the script in all specified second-level directories
const run = async () => {
    const promises = directories.map(async (dir) => {
        const dirPath = path.resolve(dir);  // Resolve the path of the directory
        await findAndRunNpmInSecondLevel(dirPath);  // Only go one level deep
    });

    await Promise.all(promises);
    console.log('All npm build jobs are done!');

    console.log(`Running 'composer publish-assets'`);
    exec('composer publish-assets', (err, stdout, stderr) => {
        if (err) {
            console.error(`Error running composer publish-assets:`, err);
            return;
        }
        console.log(`Output from composer publish-assets:`, stdout);
        if (stderr) {
            console.error(`Stderr from composer publish-assets:`, stderr);
        }
    });
    console.log('All done!');

};

run();
