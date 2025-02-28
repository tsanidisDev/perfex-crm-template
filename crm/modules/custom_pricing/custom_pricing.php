<?php

defined('BASEPATH') or exit('No direct script access allowed');

/*
Module Name: Custom Pricing
Description: Custom pricing and discount management for items based on customers.
Version: 1.0.0
Author: Your Company Name
*/

define('CUSTOM_PRICING_MODULE_NAME', 'custom_pricing');
define('CUSTOM_PRICING_UPLOAD_FOLDER', module_dir_path(CUSTOM_PRICING_MODULE_NAME, 'uploads'));

// Register install/uninstall hooks
register_activation_hook(CUSTOM_PRICING_MODULE_NAME, 'custom_pricing_install');
register_deactivation_hook(CUSTOM_PRICING_MODULE_NAME, 'custom_pricing_uninstall');

hooks()->add_action('admin_init', 'custom_pricing_permissions');
hooks()->add_action('app_admin_head', 'custom_pricing_add_head_components');
hooks()->add_action('app_admin_footer', 'custom_pricing_load_js');
hooks()->add_action('after_invoice_added', 'custom_pricing_apply_pricing_and_discounts');

hooks()->add_action('admin_init', 'custom_pricing_init_menu_items');

function custom_pricing_install() {
    include_once(__DIR__ . '/install.php');
}

function custom_pricing_uninstall() {
    include_once(__DIR__ . '/uninstall.php');
}

function custom_pricing_permissions() {
    $capabilities = [
        'view'   => _l('permission_view'),
        'create' => _l('permission_create'),
        'edit'   => _l('permission_edit'),
        'delete' => _l('permission_delete'),
    ];

    register_staff_capabilities('custom_pricing', $capabilities, _l('custom_pricing'));
}

function custom_pricing_add_head_components() {
    echo '<link href="' . module_dir_url(CUSTOM_PRICING_MODULE_NAME, 'assets/css/custom_pricing.css') . '" rel="stylesheet" type="text/css" />';
}

function custom_pricing_load_js() {
    echo '<script src="' . module_dir_url(CUSTOM_PRICING_MODULE_NAME, 'assets/js/custom_pricing.js') . '"></script>';
}

function custom_pricing_apply_pricing_and_discounts($invoice_id) {
    $CI = &get_instance();
    $CI->load->library(CUSTOM_PRICING_MODULE_NAME . '/Custom_pricing_lib');
    $CI->custom_pricing_lib->apply_custom_pricing_to_invoice($invoice_id);
}

function custom_pricing_calculate_discount($price, $discount_percentage) {
    return $price - ($price * ($discount_percentage / 100));
}

function custom_pricing_init_menu_items() {
    if (staff_can('view', 'custom_pricing')) {
        $CI = &get_instance();
        $CI->app_menu->add_sidebar_menu_item('custom_pricing', [
            'name'     => _l('custom_pricing'),
            'icon'     => 'fa fa-percent',
            'position' => 25,
        ]);

        $CI->app_menu->add_sidebar_children_item('custom_pricing', [
            'slug'     => 'custom_pricing_overview',
            'name'     => _l('custom_pricing_overview'),
            'href'     => admin_url('custom_pricing'),
            'position' => 5,
        ]);

        $CI->app_menu->add_sidebar_children_item('custom_pricing', [
            'slug'     => 'custom_pricing_settings',
            'name'     => _l('custom_pricing_settings'),
            'href'     => admin_url('custom_pricing/settings'),
            'position' => 10,
        ]);
    }

}