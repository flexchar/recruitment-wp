<?php


/**
 * Plugin Name:       Employees
 * Description:       A WordPress plugin to add, edit, delete and show employees
 * Version:           1.0.0
 * Author:            Flexchar
 * Author URI:        https://github.com/flexchar/
 * Text Domain:       ls_employees
 * GitHub Plugin URI: https://github.com/flexchar/recruitment-wp/
 */


// Should not be accessed directly
if (!defined('ABSPATH')) exit;

// Autoload classes
spl_autoload_register(function ($class) {
    $look_up = str_replace('\\', '/', $class);
    require_once plugin_dir_path(__FILE__) . $look_up . '.php';
});

/**
 * Bootstrap the plugin
 */
if (class_exists(App\Init::class)) {
    new App\Init();
}

// On remove event, remove table
register_uninstall_hook(__FILE__, \App\Methods\DatabaseApi::dropTable());

