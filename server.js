const { spawn } = require('child_process');
const path = require('path');

const PHP_HOST = 'localhost';
const PHP_PORT = 8000;
const DOCROOT = path.join(__dirname, 'php-passenger', 'public');
const phpExe = 'C:\\xampp2\\php\\php.exe';

console.log('Memulai Passenger Service..,');

const php = spawn(phpExe, ['-S', `${PHP_HOST}:${PHP_PORT}`, '-t', DOCROOT], {
    stdio: 'inherit',
    shell: false
});

php.on('error', (err) => {
    console.error(' Gagal memulai PHP Server:', err.message);
});

php.on('close', (code) => {
    console.log(`Server PHP Berhenti dengan kode ${code}`);
});

console.log(`Passenger Service berjalan di http://${PHP_HOST}:${PHP_PORT}`);
