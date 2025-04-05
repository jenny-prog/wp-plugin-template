<?php

/**
 * @wordpress-plugin
 * Plugin Name:       {{PLUGIN_NAME}}
 * Plugin URI:        {{PLUGIN_URL}}
 * Description:       {{PLUGIN_DESCRIPTION}}
 * Version:           1.0.0
 * Author:            {{AUTHOR_NAME}}
 * Author URI:        {{AUTHOR_URL}}
 * License:           GPL-2.0-or-later
 * Text Domain:       {{PLUGIN_SLUG}}
 * Domain Path:       /languages
 */

defined('WPINC') || die;

define('{{PLUGIN_CONSTANT}}_VERSION', '1.0.0');
define('{{PLUGIN_CONSTANT}}_PLUGIN_PREFIX', '{{PLUGIN_PREFIX}}');

define('{{PLUGIN_CONSTANT}}_PLUGIN_FILE', __FILE__);
define('{{PLUGIN_CONSTANT}}_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('{{PLUGIN_CONSTANT}}_PLUGIN_URL', plugin_dir_url(__FILE__));

define('{{PLUGIN_CONSTANT}}_ASSETS_URL', {{PLUGIN_CONSTANT}}_PLUGIN_URL . 'assets/');
define('{{PLUGIN_CONSTANT}}_ASSETS_PATH', {{PLUGIN_CONSTANT}}_PLUGIN_DIR . 'assets/');

define('{{PLUGIN_CONSTANT}}_AJAX_URL', admin_url('admin-ajax.php', 'relative'));


if (!defined('WP_ENV')) {
    if (isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] === 'localhost') {
		@ini_set('display_errors', 1);
		@ini_set('display_startup_errors', 1);
		error_reporting(E_ALL);		
        define('WP_ENV', 'development');
    } else {
        define('WP_ENV', 'production');
    }
}

require_once plugin_dir_path(__FILE__) . 'vendor/autoload.php';

/**
 * Plugin Activation
 */
register_activation_hook(__FILE__, function () {
	\{{PLUGIN_NAMESPACE}}\Core\Activator::activate();
});

/**
 * Plugin Deactivation
 */
register_deactivation_hook(__FILE__, function () {
	\{{PLUGIN_NAMESPACE}}\Core\Deactivator::deactivate();
});

/**
 * Run Plugin
 */
function run_{{PLUGIN_FUNCTION_SLUG}}() {
	$plugin = new \{{PLUGIN_NAMESPACE}}\{{PLUGIN_NAMESPACE}}('{{PLUGIN_SLUG}}', {{PLUGIN_CONSTANT}}_VERSION);
	$plugin->run();
}
run_{{PLUGIN_FUNCTION_SLUG}}();