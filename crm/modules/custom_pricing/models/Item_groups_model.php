<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Item_groups_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get item groups
     * 
     * @param array $where
     * @return array
     */
    public function get($where = [])
    {
        if (!empty($where)) {
            $this->db->where($where);
        }
        
        return $this->db->get('tblitem_groups')->result_array();
    }

    /**
     * Get item group by ID
     * 
     * @param int $id
     * @return object
     */
    public function get_by_id($id)
    {
        $this->db->where('id', $id);
        return $this->db->get('tblitem_groups')->row();
    }

    /**
     * Add item group
     * 
     * @param array $data
     * @return int|bool
     */
    public function add($data)
    {
        $items = [];
        if (isset($data['items'])) {
            $items = $data['items'];
            unset($data['items']);
        }
        
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        
        $this->db->insert('tblitem_groups', $data);
        $insert_id = $this->db->insert_id();
        
        if ($insert_id) {
            // Add items to group
            $this->add_items_to_group($insert_id, $items);
            return $insert_id;
        }
        
        return false;
    }

    /**
     * Update item group
     * 
     * @param array $data
     * @param int $id
     * @return bool
     */
    public function update($data, $id)
    {
        $items = [];
        if (isset($data['items'])) {
            $items = $data['items'];
            unset($data['items']);
        }
        
        $data['updated_at'] = date('Y-m-d H:i:s');
        
        $this->db->where('id', $id);
        $this->db->update('tblitem_groups', $data);
        
        if ($this->db->affected_rows() > 0 || $items) {
            // Update items in group
            $this->db->where('group_id', $id);
            $this->db->delete('tblitem_group_items');
            
            $this->add_items_to_group($id, $items);
            
            return true;
        }
        
        return false;
    }

    /**
     * Delete item group
     * 
     * @param int $id
     * @return bool
     */
    public function delete($id)
    {
        // First delete all related records
        $this->db->where('group_id', $id);
        $this->db->delete('tblitem_group_items');
        
        $this->db->where('group_id', $id);
        $this->db->delete('tblitem_group_pricing');
        
        // Then delete the group
        $this->db->where('id', $id);
        $this->db->delete('tblitem_groups');
        
        if ($this->db->affected_rows() > 0) {
            return true;
        }
        
        return false;
    }

/**
 * Add items to group
 * 
 * @param int $group_id
 * @param array $items
 * @return void
 */
private function add_items_to_group($group_id, $items)
{
    if (empty($items)) {
        return;
    }
    
    // First make sure the items array contains valid IDs
    $items = array_filter($items, function($item_id) {
        return is_numeric($item_id) && $item_id > 0;
    });
    
    // For each item, check if it already exists before inserting
    foreach ($items as $item_id) {
        // Check if this combination already exists
        $this->db->where('group_id', $group_id);
        $this->db->where('item_id', $item_id);
        $exists = $this->db->get('tblitem_group_items')->row();
        
        // Only insert if it doesn't exist
        if (!$exists) {
            $this->db->insert('tblitem_group_items', [
                'group_id' => $group_id,
                'item_id' => $item_id,
            ]);
        }
    }
}

    /**
     * Get items in group
     * 
     * @param int $group_id
     * @return array
     */
    public function get_group_items($group_id)
    {
        $this->db->select('i.*, gi.id as mapping_id');
        $this->db->from('tblitem_group_items gi');
        $this->db->join('tblitems i', 'i.id = gi.item_id', 'left');
        $this->db->where('gi.group_id', $group_id);
        
        return $this->db->get()->result_array();
    }

    /**
     * Get pricing for group
     * 
     * @param int $group_id
     * @return object
     */
    public function get_group_pricing($group_id)
    {
        $this->db->where('group_id', $group_id);
        return $this->db->get('tblitem_group_pricing')->row();
    }

    /**
     * Set pricing for group
     * 
     * @param array $data
     * @param int $group_id
     * @return bool
     */
    public function set_group_pricing($data, $group_id)
    {
        // Check if pricing exists
        $this->db->where('group_id', $group_id);
        $exists = $this->db->get('tblitem_group_pricing')->row();
        
        $data['updated_at'] = date('Y-m-d H:i:s');
        
        if ($exists) {
            $this->db->where('id', $exists->id);
            $this->db->update('tblitem_group_pricing', $data);
        } else {
            $data['group_id'] = $group_id;
            $data['created_at'] = date('Y-m-d H:i:s');
            $this->db->insert('tblitem_group_pricing', $data);
        }
        
        if ($this->db->affected_rows() > 0) {
            return true;
        }
        
        return false;
    }
}