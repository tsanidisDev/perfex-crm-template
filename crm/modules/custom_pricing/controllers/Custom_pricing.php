<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Custom_pricing extends AdminController {

    public function __construct() {
        parent::__construct();
        $this->load->model('custom_pricing/custom_pricing_model');
        $this->load->helper(CUSTOM_PRICING_MODULE_NAME . '/custom_pricing');
    }

    public function index() {
        $data['total_pricing_rules'] = count($this->custom_pricing_model->get_all_pricing_rules());
        $data['total_customers'] = count(custom_pricing_get_customers());
        $data['total_items'] = count(custom_pricing_get_items());
        $data['title'] = _l('custom_pricing_overview');
        $this->load->view('custom_pricing_overview', $data);
    }

    public function settings() {
        $data['customers'] = custom_pricing_get_customers();
        $data['items'] = custom_pricing_get_items();
        $data['pricing_rules'] = $this->custom_pricing_model->get_all_pricing_rules();
        $data['title'] = _l('custom_pricing_settings');
        $this->load->view('admin/custom_pricing_settings', $data);
    }

    public function save() {
        $data = $this->input->post();
        if ($this->input->post('id')) {
            $this->custom_pricing_model->update_pricing_rule($this->input->post('id'), $data);
            set_alert('success', _l('updated_successfully', _l('custom_pricing')));
        } else {
            $this->custom_pricing_model->add_pricing_rule($data);
            set_alert('success', _l('added_successfully', _l('custom_pricing')));
        }
        redirect(admin_url('custom_pricing/settings'));
    }

    public function edit($id) {
        $data['customers'] = custom_pricing_get_customers();
        $data['items'] = custom_pricing_get_items();
        $data['pricing_rule'] = $this->custom_pricing_model->get_custom_pricing($id);
        $data['title'] = _l('edit_custom_pricing');
        $this->load->view('custom_pricing_settings', $data);
    }

    public function delete($id) {
        $this->custom_pricing_model->delete_pricing_rule($id);
        set_alert('success', _l('deleted', _l('custom_pricing')));
        redirect(admin_url('custom_pricing/settings'));
    }
    
    public function save_pricing_rule() {
        $data = $this->input->post();
    
        // Save the pricing rule using the model
        $success = $this->custom_pricing_model->save_pricing_rule($data);
    
        if ($success) {
            set_alert('success', 'Pricing rule saved successfully.');
        } else {
            set_alert('danger', 'Failed to save pricing rule.');
        }
    
        redirect(admin_url('custom_pricing/settings'));
    }
}
