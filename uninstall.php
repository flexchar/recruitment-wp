<?php

/**
 * Remove plugin table on uninstall
 */

// Should not be accessed directly
if (!defined('WP_UNINSTALL_PLUGIN')) die;


// Drop plugin table   
global $wpdb;
$wpdb->query('DROP TABLE IF EXISTS wp_ls_employees');