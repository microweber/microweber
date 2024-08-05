import { exec } from 'child_process';
import { parallel } from 'async';

const runNpmDev = () => {
    parallel([
        (callback) => {
            exec('npm run dev', (err, stdout, stderr) => {
                if (err) {
                    console.error(`Error in main folder: ${err}`);
                    return callback(err);
                }
                console.log(`Main folder output: ${stdout}`);
                console.error(`Main folder errors: ${stderr}`);
                callback(null, stdout);
            });
        },
        (callback) => {
            exec('npm run dev', { cwd: 'packages/frontend-assets-libs' }, (err, stdout, stderr) => {
                if (err) {
                    console.error(`Error in packages/frontend-assets-libs: ${err}`);
                    return callback(err);
                }
                console.log(`packages/frontend-assets-libs output: ${stdout}`);
                console.error(`packages/frontend-assets-libs errors: ${stderr}`);
                callback(null, stdout);
            });
        },
        (callback) => {
            exec('npm run dev', { cwd: 'packages/frontend-assets' }, (err, stdout, stderr) => {
                if (err) {
                    console.error(`Error in packages/frontend-assets: ${err}`);
                    return callback(err);
                }
                console.log(`packages/frontend-assets output: ${stdout}`);
                console.error(`packages/frontend-assets errors: ${stderr}`);
                callback(null, stdout);
            });
        }
    ], (err, results) => {
        if (err) {
            console.error('One or more commands failed to execute.');
        } else {
            console.log('All commands executed successfully.');
        }
    });
};

// Execute the function
runNpmDev();
