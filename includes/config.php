<?php
// Set this to your app's base URL, e.g. '' for root, or '/task-manager' for subfolder
// No trailing slash
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
    $protocol = 'https://';
} else {
    $protocol = 'http://';
}
$host = $_SERVER['HTTP_HOST'];
$basePath = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
$baseUrl = $protocol . $host . ($basePath === '/' ? '' : $basePath);
define('BASE_URL', $baseUrl);
