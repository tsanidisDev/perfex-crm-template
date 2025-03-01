<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Item_groups extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('item_groups_model');
        $this->load->model('invoice_items_model'); // Add this line
        
        if (!has_permission('custom_pricing', '', 'view')) {
            access_denied('Custom Pricing');
        }
    }

    /**
     * List all item groups
     */
    public function index()
    {
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data(module_views_path('custom_pricing', 'item_groups/table'));
        }
        
        $data['title'] = _l('item_groups');
        $this->load->view('item_groups/manage', $data);
    }

    // In controllers/Item_groups.php

/**
 * Create or edit item group
 * 
 * @param int $id
 */
public function group($id = '')
{
    if ($this->input->post()) {
        $data = $this->input->post();
        
        // Make sure is_active is set correctly
        $data['is_active'] = isset($data['is_active']) ? 1 : 0;
        
        if ($id == '') {
            if (!has_permission('custom_pricing', '', 'create')) {
                access_denied('Create Item Group');
            }
            
            $id = $this->item_groups_model->add($data);
            if ($id) {
                set_alert('success', _l('added_successfully', _l('item_group')));
                redirect(admin_url('custom_pricing/item_groups'));
            }
        } else {
            if (!has_permission('custom_pricing', '', 'edit')) {
                access_denied('Edit Item Group');
            }
            
            $success = $this->item_groups_model->update($data, $id);
            if ($success) {
                set_alert('success', _l('updated_successfully', _l('item_group')));
            }
            redirect(admin_url('custom_pricing/item_groups'));
        }
    }
    
    if ($id == '') {
        $title = _l('add_new', _l('item_group'));
        $data['group'] = [];
        $data['group_items'] = [];
        $this->load->view('item_groups/create', $data);
    } else {
        $data['group'] = $this->item_groups_model->get_by_id($id);
        if (!$data['group']) {
            show_404();
        }
        $data['group_items'] = $this->item_groups_model->get_group_items($id);
        $data['title'] = _l('edit', _l('item_group'));
        $data['items'] = $this->invoice_items_model->get();
        
        $this->load->view('item_groups/edit', $data);
    }
}

    /**
     * Delete item group
     * 
     * @param int $id
     */
    public function delete($id)
    {
        if (!has_permission('custom_pricing', '', 'delete')) {
            access_denied('Delete Item Group');
        }
        
        if ($this->item_groups_model->delete($id)) {
            set_alert('success', _l('deleted', _l('item_group')));
        }
        
        redirect(admin_url('custom_pricing/item_groups'));
    }

    /**
     * Item group pricing
     */
    public function pricing()
    {
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data(module_views_path('custom_pricing', 'item_group_pricing/table'));
        }
        
        $data['title'] = _l('item_group_pricing');
        $this->load->view('item_group_pricing/manage', $data);
    }

    /**
     * Edit group pricing
     * 
     * @param int $group_id
     */
    public function edit_pricing($group_id)
    {
        if (!has_permission('custom_pricing', '', 'edit')) {
            access_denied('Edit Item Group Pricing');
        }
        
        if ($this->input->post()) {
            $data = $this->input->post();
            
            $success = $this->item_groups_model->set_group_pricing($data, $group_id);
            if ($success) {
                set_alert('success', _l('updated_successfully', _l('item_group_pricing')));
            }
            redirect(admin_url('custom_pricing/item_groups/pricing'));
        }
        
        $data['group'] = $this->item_groups_model->get_by_id($group_id);
        $data['pricing'] = $this->item_groups_model->get_group_pricing($group_id);
        $data['group_items'] = $this->item_groups_model->get_group_items($group_id);
        $data['title'] = _l('edit', _l('item_group_pricing'));
        
        $this->load->view('item_group_pricing/edit', $data);
    }

    /**
 * Change group status (active/inactive)
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
    $this->db->update('tblitem_groups', [
        'is_active' => $status,
        'updated_at' => date('Y-m-d H:i:s')
    ]);
    
    echo json_encode([
        'success' => true
    ]);
}
}