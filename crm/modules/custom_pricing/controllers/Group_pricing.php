<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Group_pricing extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('group_pricing_model');
        $this->load->model('clients_model'); // Add this line
        $this->load->model('invoice_items_model'); // Add this line
        
        if (!has_permission('custom_pricing', '', 'view')) {
            access_denied('Custom Pricing');
        }
    }

    /**
     * List all customer group pricing
     */
    public function index()
    {
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data(module_views_path('custom_pricing', 'customer_group_pricing/table'));
        }
        
        $data['title'] = _l('customer_group_pricing');
        $this->load->view('customer_group_pricing/manage', $data);
    }

    /**
 * Create or edit customer group pricing
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
                access_denied('Create Customer Group Pricing');
            }
            
            $id = $this->group_pricing_model->add($data);
            if ($id) {
                set_alert('success', _l('added_successfully', _l('customer_group_pricing')));
                redirect(admin_url('custom_pricing/group_pricing'));
            }
        } else {
            if (!has_permission('custom_pricing', '', 'edit')) {
                access_denied('Edit Customer Group Pricing');
            }
            
            $success = $this->group_pricing_model->update($data, $id);
            if ($success) {
                set_alert('success', _l('updated_successfully', _l('customer_group_pricing')));
            }
            redirect(admin_url('custom_pricing/group_pricing'));
        }
    }
    
    if ($id == '') {
        $title = _l('add_new', _l('customer_group_pricing_singular'));
        $data['pricing'] = [];
        $this->load->view('customer_group_pricing/create', $data);
    } else {
        $data['pricing'] = $this->group_pricing_model->get_by_id($id);
        if (!$data['pricing']) {
            show_404();
        }
        $data['title'] = _l('edit', _l('customer_group_pricing_singular'));
        $this->load->model('clients_model');
        $data['customer_groups'] = $this->clients_model->get_groups();
        $data['items'] = $this->invoice_items_model->get();
        
        $this->load->view('customer_group_pricing/edit', $data);
    }
}

    /**
     * Delete customer group pricing
     * 
     * @param int $id
     */
    public function delete($id)
    {
        if (!has_permission('custom_pricing', '', 'delete')) {
            access_denied('Delete Customer Group Pricing');
        }
        
        if ($this->group_pricing_model->delete($id)) {
            set_alert('success', _l('deleted', _l('customer_group_pricing')));
        }
        
        redirect(admin_url('custom_pricing/group_pricing'));
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
    $this->db->update('tblcustomer_group_pricing', [
        'is_active' => $status,
        'updated_at' => date('Y-m-d H:i:s')
    ]);
    
    echo json_encode([
        'success' => true
    ]);
}
}