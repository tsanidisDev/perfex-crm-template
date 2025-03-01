<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
Module Name: Custom Pricing
Description: Customer-specific pricing and item group discounting
Version: 1.0.0
Requires at least: 2.3.*
Author: Your Name
*/

hooks()->add_action('admin_init', 'custom_pricing_module_init_menu_items');
hooks()->add_action('app_admin_head', 'custom_pricing_module_add_head_components');
hooks()->add_action('app_admin_footer', 'custom_pricing_module_add_footer_components');
hooks()->add_action('admin_init', 'custom_pricing_module_permissions');

// Invoice, estimate, proposal hooks
hooks()->add_filter('before_add_item', 'custom_pricing_modify_item_price');
hooks()->add_filter('before_update_item', 'custom_pricing_modify_item_price');
hooks()->add_filter('before_add_estimate_item', 'custom_pricing_modify_item_price');
hooks()->add_filter('before_update_estimate_item', 'custom_pricing_modify_item_price');
hooks()->add_filter('before_add_proposal_item', 'custom_pricing_modify_item_price');
hooks()->add_filter('before_update_proposal_item', 'custom_pricing_modify_item_price');

// Register activation module hook
register_activation_hook('custom_pricing', 'custom_pricing_module_activation_hook');

function custom_pricing_module_activation_hook()
{
    $CI = &get_instance();
    require_once(__DIR__ . '/helpers/custom_pricing_helper.php');
    require_once(__DIR__ . '/migrations/100_version_100.php');
    
    $migration = new Migration_Version_100();
    $migration->up();
}

function custom_pricing_module_init_menu_items()
{
    $CI = &get_instance();
    
    if (has_permission('custom_pricing', '', 'view')) {
        $CI->app_menu->add_sidebar_menu_item('custom-pricing', [
            'name'     => _l('custom_pricing'),
            'icon'     => 'fa fa-tags',
            'position' => 30,
        ]);

        $CI->app_menu->add_sidebar_children_item('custom-pricing', [
            'slug'     => 'customer-pricing',
            'name'     => _l('customer_pricing'),
            'href'     => admin_url('custom_pricing/customer_pricing'),
            'position' => 5,
        ]);

        $CI->app_menu->add_sidebar_children_item('custom-pricing', [
            'slug'     => 'customer-group-pricing',
            'name'     => _l('customer_group_pricing'),
            'href'     => admin_url('custom_pricing/group_pricing'),
            'position' => 10,
        ]);

        $CI->app_menu->add_sidebar_children_item('custom-pricing', [
            'slug'     => 'item-groups',
            'name'     => _l('item_groups'),
            'href'     => admin_url('custom_pricing/item_groups'),
            'position' => 15,
        ]);

        $CI->app_menu->add_sidebar_children_item('custom-pricing', [
            'slug'     => 'item-group-pricing',
            'name'     => _l('item_group_pricing'),
            'href'     => admin_url('custom_pricing/item_groups/pricing'),
            'position' => 20,
        ]);
    }
}

function custom_pricing_module_add_head_components()
{
    $CI = &get_instance();
    $viewuri = $_SERVER['REQUEST_URI'];
    
    if (strpos($viewuri, '/custom_pricing/') !== false) {
        echo '<link href="' . base_url('modules/custom_pricing/assets/css/custom_pricing.css') . '" rel="stylesheet" type="text/css" />';
    }
}

function custom_pricing_module_add_footer_components()
{
    $CI = &get_instance();
    $viewuri = $_SERVER['REQUEST_URI'];
    
    if (strpos($viewuri, '/custom_pricing/') !== false) {
        echo '<script src="' . base_url('modules/custom_pricing/assets/js/custom_pricing.js') . '"></script>';
    }

    // For invoices, estimates, proposals
    if ($CI->uri->segment(1) == 'invoices' || $CI->uri->segment(1) == 'estimates' || $CI->uri->segment(1) == 'proposals') {
        echo '<script>
        $(function(){
            $(document).on("change", "select.selectpicker[name*=\'itemid\']", function(){
                var itemid = $(this).val();
                var rel_id = $("input[name=\'rel_id\']").val();
                var rel_type = $("input[name=\'rel_type\']").val();
                
                if (itemid && rel_id && rel_type == "customer") {
                    $.get(admin_url + "custom_pricing/get_price/" + rel_id + "/" + itemid, function(price){
                        // Update the price field
                        var row = $("select.selectpicker[name*=\'itemid\'][value=\'" + itemid + "\']").parents("tr");
                        row.find("input[name*=\'rate\']").val(price);
                    });
                }
            });
        });
        </script>';
    }
}

function custom_pricing_module_permissions()
{
    $capabilities = [];

    $capabilities['capabilities'] = [
        'view'   => _l('permission_view') . '(' . _l('permission_global') . ')',
        'create' => _l('permission_create'),
        'edit'   => _l('permission_edit'),
        'delete' => _l('permission_delete'),
    ];

    register_staff_capabilities('custom_pricing', $capabilities, _l('custom_pricing'));
}

/**
 * Modify item price based on customer pricing rules
 */
function custom_pricing_modify_item_price($item)
{
    $CI = &get_instance();
    $CI->load->helper('custom_pricing_helper');

    if (isset($item['rel_type']) && $item['rel_type'] == 'customer' && isset($item['rel_id']) && isset($item['itemid'])) {
        $customer_id = $item['rel_id'];
        $item_id = $item['itemid'];
        
        $item['rate'] = get_customer_item_price($customer_id, $item_id);
    }
    
    return $item;
}