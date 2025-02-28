<?php

defined('BASEPATH') or exit('No direct script access allowed');

function custom_pricing_install() {
    $CI = &get_instance();

    // Create the custom pricing rules table
    if (!$CI->db->table_exists(db_prefix() . 'custom_pricing_rules')) {
        $CI->db->query("
            CREATE TABLE `" . db_prefix() . "custom_pricing_rules` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `customer_id` int(11) NOT NULL,
              `item_id` int(11) NOT NULL,
              `custom_price` decimal(15,2) DEFAULT NULL,
              `discount_percentage` decimal(5,2) DEFAULT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");
    }
}
