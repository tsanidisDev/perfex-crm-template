<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Custom_pricing_lib {

    public function __construct() {
        $this->CI = &get_instance();
        $this->CI->load->model('custom_pricing/custom_pricing_model');
    }

    /**
     * Calculate custom price based on customer and item.
     *
     * @param int $customer_id
     * @param int $item_id
     * @return float|bool The custom price if available, otherwise false
     */
    public function calculate_custom_price($customer_id, $item_id) {
        $pricing_rule = $this->CI->custom_pricing_model->get_pricing_rule($customer_id, $item_id);
        if ($pricing_rule) {
            if (!empty($pricing_rule['custom_price'])) {
                return $pricing_rule['custom_price'];
            }
            if (!empty($pricing_rule['discount_percentage'])) {
                $item = $this->CI->custom_pricing_model->get_item_by_id($item_id);
                if ($item) {
                    return $item->rate * (1 - $pricing_rule['discount_percentage'] / 100);
                }
            }
        }
        return false;
    }

    /**
     * Apply custom pricing and discounts during invoice creation.
     *
     * @param int $invoice_id
     * @return void
     */
    public function apply_custom_pricing_to_invoice($invoice_id) {
        $invoice = $this->CI->invoices_model->get($invoice_id);
        if (!$invoice) {
            return;
        }

        foreach ($invoice->items as &$item) {
            $custom_price = $this->calculate_custom_price($invoice->clientid, $item->itemid);
            if ($custom_price !== false) {
                $item['rate'] = $custom_price;
            }
        }
        $this->CI->invoices_model->update_invoice_items($invoice_id, $invoice->items);
    }
}
