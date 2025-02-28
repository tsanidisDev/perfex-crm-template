<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Custom_pricing_model extends App_Model {

    public function get_custom_pricing($customer_id, $item_id) {
        $this->db->where('customer_id', $customer_id);
        $this->db->where('item_id', $item_id);
        return $this->db->get(db_prefix() . 'custom_pricing_rules')->row_array();
    }

    public function get_all_pricing_rules() {
        $this->db->select('tblcustom_pricing_rules.*, tblclients.company as customer_name, tblitems.description as item_description');
        $this->db->join('tblclients', 'tblclients.userid = tblcustom_pricing_rules.customer_id');
        $this->db->join('tblitems', 'tblitems.id = tblcustom_pricing_rules.item_id');
        return $this->db->get(db_prefix() . 'custom_pricing_rules')->result_array();
    }

    public function add_pricing_rule($data) {
        $this->db->insert(db_prefix() . 'custom_pricing_rules', $data);
        return $this->db->insert_id();
    }

    public function update_pricing_rule($id, $data) {
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'custom_pricing_rules', $data);
        return $this->db->affected_rows() > 0;
    }

    public function delete_pricing_rule($id) {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'custom_pricing_rules');
        return $this->db->affected_rows() > 0;
    }
    
    public function save_pricing_rule($data) {
        // Set default values if fields are empty
        if (empty($data['custom_price'])) {
            // Fetch the default item price
            $item = $this->db->get_where(db_prefix() . 'items', ['id' => $data['item_id']])->row();
            $data['custom_price'] = $item ? $item->rate : 0;
        }
    
        if (empty($data['discount_percentage'])) {
            $data['discount_percentage'] = 0.00;
        }
    
        // Insert or update the pricing rule
        if (isset($data['id']) && $data['id']) {
            $this->db->where('id', $data['id']);
            $this->db->update(db_prefix() . 'custom_pricing_rules', $data);
        } else {
            $this->db->insert(db_prefix() . 'custom_pricing_rules', $data);
        }
    
        return $this->db->affected_rows() > 0;
    }
}
