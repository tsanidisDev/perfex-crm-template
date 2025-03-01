<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Customer_pricing extends AdminController
{
    
    public function __construct()
    {
        parent::__construct();
        $this->load->model('customer_pricing_model');
        $this->load->model('clients_model'); // Add this line
        $this->load->model('invoice_items_model'); // Add this line
        
        if (!has_permission('custom_pricing', '', 'view')) {
            access_denied('Custom Pricing');
        }
    }

    /**
     * List all customer pricing
     */
    public function index()
    {
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data(module_views_path('custom_pricing', 'customer_pricing/table'));
        }
        
        $data['title'] = _l('customer_pricing');
        $this->load->view('customer_pricing/manage', $data);
    }

    /**
     * Create or edit customer pricing
     * 
     * @param int $id
     */
    /**
 * Create or edit customer pricing
 * 
 * @param int $id
 */
public function pricing($id = '')
{
    if ($this->input->post()) {
        $data = $this->input->post();
        
        // Make sure is_active is set correctly
        $data['is_active'] = isset($data['is_active']) ? 1 : 0;
        
        if ($id == '') {
            if (!has_permission('custom_pricing', '', 'create')) {
                access_denied('Create Customer Pricing');
            }
            
            $id = $this->customer_pricing_model->add($data);
            if ($id) {
                set_alert('success', _l('added_successfully', _l('customer_pricing')));
                redirect(admin_url('custom_pricing/customer_pricing'));
            }
        } else {
            if (!has_permission('custom_pricing', '', 'edit')) {
                access_denied('Edit Customer Pricing');
            }
            
            $success = $this->customer_pricing_model->update($data, $id);
            if ($success) {
                set_alert('success', _l('updated_successfully', _l('customer_pricing')));
            }
            redirect(admin_url('custom_pricing/customer_pricing'));
        }
    }
    
    if ($id == '') {
        $title = _l('add_new', _l('customer_pricing_singular'));
        $data['pricing'] = [];
        $this->load->view('customer_pricing/create', $data);
    } else {
        $data['pricing'] = $this->customer_pricing_model->get_by_id($id);
        if (!$data['pricing']) {
            show_404();
        }
        $data['title'] = _l('edit', _l('customer_pricing_singular'));
        $data['customers'] = $this->clients_model->get();
        $data['items'] = $this->invoice_items_model->get();
        
        $this->load->view('customer_pricing/edit', $data);
    }
}

    /**
     * Delete customer pricing
     * 
     * @param int $id
     */
    public function delete($id)
    {
        if (!has_permission('custom_pricing', '', 'delete')) {
            access_denied('Delete Customer Pricing');
        }
        
        if ($this->customer_pricing_model->delete($id)) {
            set_alert('success', _l('deleted', _l('customer_pricing')));
        }
        
        redirect(admin_url('custom_pricing/customer_pricing'));
    }
}