<?php

defined('BASEPATH') or exit('No direct script access allowed');

function custom_pricing_uninstall() {
    $CI = &get_instance();

    // Drop the custom pricing rules table
    if ($CI->db->table_exists(db_prefix() . 'custom_pricing_rules')) {
        $CI->db->query('DROP TABLE `' . db_prefix() . 'custom_pricing_rules`');
    }
}
