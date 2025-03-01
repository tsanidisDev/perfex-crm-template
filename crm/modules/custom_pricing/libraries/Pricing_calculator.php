<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pricing_calculator
{
    private $CI;

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->helper('custom_pricing_helper');
    }

    /**
     * Calculate price for invoice or estimate items
     * 
     * @param array $items
     * @param int $customer_id
     * @return array
     */
    public function calculate_prices($items, $customer_id)
    {
        foreach ($items as &$item) {
            $item['rate'] = get_customer_item_price($customer_id, $item['itemid']);
        }
        
        return $items;
    }

    /**
     * Get pricing exceptions report
     * 
     * @param string $type (customer|group|item_group)
     * @return array
     */
    public function get_pricing_exceptions_report($type = 'customer')
    {
        $result = [];
        
        if ($type == 'customer') {
            $this->CI->load->model('custom_pricing/customer_pricing_model');
            $result = $this->CI->customer_pricing_model->get(['is_active' => 1]);
        } elseif ($type == 'group') {
            $this->CI->load->model('custom_pricing/group_pricing_model');
            $result = $this->CI->group_pricing_model->get(['is_active' => 1]);
        } elseif ($type == 'item_group') {
            $this->CI->load->model('custom_pricing/item_groups_model');
            $groups = $this->CI->item_groups_model->get(['is_active' => 1]);
            
            foreach ($groups as $group) {
                $pricing = $this->CI->item_groups_model->get_group_pricing($group['id']);
                if ($pricing) {
                    $group['discount_percent'] = $pricing->discount_percent;
                    $group['discount_amount'] = $pricing->discount_amount;
                    $result[] = $group;
                }
            }
        }
        
        return $result;
    }
}