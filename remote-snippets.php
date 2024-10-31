<?php
/**
 * Plugin Name:     Remote Snippets
 * Plugin URI:      https://www.remotesnippets.com/
 * Description:     Import JSON and CSV data and display it using a Twig template. Consume API's and Webservices and display live data on your Wordpress site.
 * Author:          rgjo7n
 * Version:         1.0.3
 *
 * @package         Remote_Snippets
 */


if (!function_exists('add_filter')) {
	header('Status: 403 Forbidden');
	header('HTTP/1.1 403 Forbidden');
	exit();
}

if (!defined('REMOTESNIPPET_PATH')) {
    define('REMOTESNIPPET_PATH', plugin_dir_path(__FILE__));
}

/* polyfill */
function remotesnippets_array_key_first(array $arr) {
    foreach($arr as $key => $unused) {
        return $key;
    }
    return NULL;
}


require_once(ABSPATH . 'wp-admin/includes/screen.php'); // for get_current_screen()

function remotesnippets_autoload($class)
{
    $pt = explode('\\', $class);

    if ($pt[0] == 'RemoteSnippets') {
        array_shift($pt);
        $fn = sprintf('%ssrc/%s.php', REMOTESNIPPET_PATH, implode('/', $pt));
        require_once($fn);
        return;
    }
    $autoload_file = 'thirdparty/vendor/autoload.php';
    if (is_readable(REMOTESNIPPET_PATH.$autoload_file)) {
        require_once($autoload_file);
        return;
    }
}

if (function_exists('spl_autoload_register')) {
	spl_autoload_register('remotesnippets_autoload');
}

if (file_exists(__DIR__ . '/src/Pro/Remote.php')) {
    $as = new \RemoteSnippets\Pro\RemoteSnippets;
}
else {
    $as = new \RemoteSnippets\RemoteSnippets;
}
add_action('init', [$as, 'setup']);
