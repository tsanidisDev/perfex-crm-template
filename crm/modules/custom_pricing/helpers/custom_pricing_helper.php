<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Get customer-specific price for an item
 * 
 * @param int $customer_id
 * @param int $item_id
 * @return float
 */
function get_customer_item_price($customer_id, $item_id)
{
    $CI = &get_instance();
    
    // Check for direct customer-item pricing
    $CI->db->where('customer_id', $customer_id);
    $CI->db->where('item_id', $item_id);
    $CI->db->where('is_active', 1);
    $customer_price = $CI->db->get('tblcustomer_pricing')->row();
    
    if ($customer_price) {
        return calculate_final_price($customer_price, $item_id);
    }
    
    // Check for customer group pricing
    $customer_groups = get_customer_groups($customer_id);
    if ($customer_groups) {
        foreach ($customer_groups as $group) {
            $CI->db->where('customer_group_id', $group->groupid);
            $CI->db->where('item_id', $item_id);
            $CI->db->where('is_active', 1);
            $group_price = $CI->db->get('tblcustomer_group_pricing')->row();
            
            if ($group_price) {
                return calculate_final_price($group_price, $item_id);
            }
        }
    }
    
    // Check for item group pricing
    $item_groups = get_item_groups($item_id);
    if ($item_groups) {
        foreach ($item_groups as $group) {
            $CI->db->where('group_id', $group->group_id);
            $CI->db->where('is_active', 1);
            $group_discount = $CI->db->get('tblitem_group_pricing')->row();
            
            if ($group_discount) {
                $item = $CI->db->get_where('tblitems', ['id' => $item_id])->row();
                $price = $item->rate;
                
                if ($group_discount->discount_percent > 0) {
                    $price = $price - ($price * $group_discount->discount_percent / 100);
                }
                
                if ($group_discount->discount_amount > 0) {
                    $price = $price - $group_discount->discount_amount;
                }
                
                return max(0, $price);
            }
        }
    }
    
    // Return standard price if no custom pricing is found
    $item = $CI->db->get_where('tblitems', ['id' => $item_id])->row();
    return $item->rate;
}

/**
 * Calculate final price based on pricing record
 * 
 * @param object $pricing_record
 * @param int $item_id
 * @return float
 */
function calculate_final_price($pricing_record, $item_id)
{
    $CI = &get_instance();
    $item = $CI->db->get_where('tblitems', ['id' => $item_id])->row();
    
    // Start with the custom price if set, otherwise use standard price
    $price = ($pricing_record->price > 0) ? $pricing_record->price : $item->rate;
    
    // Apply percentage discount if set
    if ($pricing_record->discount_percent > 0) {
        $price = $price - ($price * $pricing_record->discount_percent / 100);
    }
    
    // Apply fixed amount discount if set
    if ($pricing_record->discount_amount > 0) {
        $price = $price - $pricing_record->discount_amount;
    }
    
    // Ensure price doesn't go below zero
    return max(0, $price);
}

/**
 * Get customer groups for a customer
 * 
 * @param int $customer_id
 * @return array
 */
function get_customer_groups($customer_id)
{
    $CI = &get_instance();
    
    return $CI->db->query("
        SELECT g.* 
        FROM tblcustomergroups_clients gc
        JOIN tblcustomers_groups g ON g.id = gc.groupid
        WHERE gc.customer_id = ?
    ", [$customer_id])->result();
}

/**
 * Get item groups for an item
 * 
 * @param int $item_id
 * @return array
 */
function get_item_groups($item_id)
{
    $CI = &get_instance();
    
    return $CI->db->query("
        SELECT ig.group_id, g.name 
        FROM tblitem_group_items ig
        JOIN tblitem_groups g ON g.id = ig.group_id
        WHERE ig.item_id = ?
    ", [$item_id])->result();
}