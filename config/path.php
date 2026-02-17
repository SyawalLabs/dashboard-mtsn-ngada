<?php
// config/path.php
if (!defined('ROOT_PATH')) {
    // Tentukan root path absolute
    define('ROOT_PATH', dirname(__DIR__)); // Akan mengarah ke C:\laragon\www\dashboard-mtsn-ngada

    // URL base untuk web
    define('BASE_URL', '/dashboard-mtsn-ngada');

    // Path untuk include
    define('CONFIG_PATH', ROOT_PATH . '/config');
    define('INCLUDES_PATH', ROOT_PATH . '/includes');
    define('PAGES_PATH', ROOT_PATH . '/pages');
}
