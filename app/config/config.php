<?php
//DATABASE CONFIGURATIONS
define("DB_HOST",'localhost');
define('DB_USER','root');
define('DB_PASSWORD','');
define('DB_NAME','melodylink');

//APPROOT
define('APPROOT',dirname(dirname(__FILE__)));


//URL ROOT
define('URLROOT', 'http://localhost/Framework/');

//WEBSITE NAME
define('SITENAME', 'MelodyLink');

// Load environment variables
function loadEnv() {
    $envFile = dirname(__DIR__) . '/.env';
    if (file_exists($envFile)) {
        $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
                list($key, $value) = explode('=', $line, 2);
                $key = trim($key);
                $value = trim($value);
                if (!array_key_exists($key, $_ENV)) {
                    putenv(sprintf('%s=%s', $key, $value));
                    $_ENV[$key] = $value;
                }
            }
        }
    }
}

// Load environment variables
loadEnv();
?>