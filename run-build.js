import { exec } from 'child_process';
import  fs  from 'fs';
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
const findAndRunNpmInSecondLevel = (dir) => {
    const promises = [];

        const files = fs.readdirSync(dir);

        for (const file of files) {
            const fullPath = path.join(dir, file);
            const stat = fs.lstatSync(fullPath);

            // Only run if it's a second-level directory
            if (stat.isDirectory()) {
                const packageJsonPath = path.join(fullPath, 'package.json');
                console.log(packageJsonPath, fs.existsSync(packageJsonPath))
                if(fs.existsSync(packageJsonPath)) {
                    promises.push(runNpmScript(fullPath));  // Run npm script if package.json exists
                }
            }
        }

    return  promises;
};

// Run the script in all specified second-level directories
const run = async () => {




    const promises = [];

for (const dir of directories) {
    const dirPath = path.resolve(dir);  // Resolve the path of the directory
    const result =   findAndRunNpmInSecondLevel(dirPath);  // Only go one level deep
    promises.push(...result);
}



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
