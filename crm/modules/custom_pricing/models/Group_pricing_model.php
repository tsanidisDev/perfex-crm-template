<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Group_pricing_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get customer group pricing
     * 
     * @param array $where
     * @return array
     */
    public function get($where = [])
    {
        $this->db->select('cgp.*, cg.name as group_name, i.description as item_name');
        $this->db->from('tblcustomer_group_pricing cgp');
        $this->db->join('tblcustomers_groups cg', 'cg.id = cgp.customer_group_id', 'left');
        $this->db->join('tblitems i', 'i.id = cgp.item_id', 'left');
        
        if (!empty($where)) {
            $this->db->where($where);
        }
        
        return $this->db->get()->result_array();
    }

    /**
     * Get pricing by ID
     * 
     * @param int $id
     * @return object
     */
    public function get_by_id($id)
    {
        $this->db->where('id', $id);
        return $this->db->get('tblcustomer_group_pricing')->row();
    }

    /**
     * Add customer group pricing
     * 
     * @param array $data
     * @return int|bool
     */
    public function add($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        
        $this->db->insert('tblcustomer_group_pricing', $data);
        
        if ($this->db->affected_rows() > 0) {
            return $this->db->insert_id();
        }
        
        return false;
    }

    /**
     * Update customer group pricing
     * 
     * @param array $data
     * @param int $id
     * @return bool
     */
    public function update($data, $id)
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        
        $this->db->where('id', $id);
        $this->db->update('tblcustomer_group_pricing', $data);
        
        if ($this->db->affected_rows() > 0) {
            return true;
        }
        
        return false;
    }

    /**
     * Delete customer group pricing
     * 
     * @param int $id
     * @return bool
     */
    public function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('tblcustomer_group_pricing');
        
        if ($this->db->affected_rows() > 0) {
            return true;
        }
        
        return false;
    }
}