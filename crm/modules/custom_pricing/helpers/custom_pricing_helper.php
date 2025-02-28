<?php

defined('BASEPATH') or exit('No direct script access allowed');

function custom_pricing_get_customers() {
    $CI = &get_instance();
    $CI->db->select('userid, company');
    return $CI->db->get(db_prefix() . 'clients')->result_array();
}

function custom_pricing_get_items() {
    $CI = &get_instance();
    $CI->db->select('id, description');
    return $CI->db->get(db_prefix() . 'items')->result_array();
}
