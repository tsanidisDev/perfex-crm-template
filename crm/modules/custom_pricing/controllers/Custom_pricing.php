<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Custom_pricing extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('custom_pricing_helper');
    }

    /**
     * Index method - redirect to customer pricing
     */
    public function index()
    {
        redirect(admin_url('custom_pricing/customer_pricing'));
    }

    /**
     * Get price for a customer-item combination
     * 
     * @param int $customer_id
     * @param int $item_id
     * @return string
     */
    public function get_price($customer_id, $item_id)
    {
        $price = get_customer_item_price($customer_id, $item_id);
        echo $price;
    }

    /**
 * Change pricing status (active/inactive)
 */
public function change_status()
{
    if (!has_permission('custom_pricing', '', 'edit')) {
        echo json_encode([
            'success' => false,
            'message' => _l('access_denied')
        ]);
        die;
    }
    
    $id = $this->input->post('id');
    $status = $this->input->post('status');
    
    $this->db->where('id', $id);
    $this->db->update('tblcustomer_pricing', [
        'is_active' => $status,
        'updated_at' => date('Y-m-d H:i:s')
    ]);
    
    echo json_encode([
        'success' => true
    ]);
}

// In controllers/Custom_pricing.php add these methods:

/**
 * DataTables data for customer pricing
 */
public function table()
{
    if (!has_permission('custom_pricing', '', 'view')) {
        ajax_access_denied();
    }

    $this->app->get_table_data(module_views_path('custom_pricing', 'customer_pricing/table'));
}

/**
 * DataTables data for customer group pricing
 */
public function group_pricing_table()
{
    if (!has_permission('custom_pricing', '', 'view')) {
        ajax_access_denied();
    }

    $this->app->get_table_data(module_views_path('custom_pricing', 'customer_group_pricing/table'));
}

/**
 * DataTables data for item groups
 */
public function item_groups_table()
{
    if (!has_permission('custom_pricing', '', 'view')) {
        ajax_access_denied();
    }

    $this->app->get_table_data(module_views_path('custom_pricing', 'item_groups/table'));
}
}