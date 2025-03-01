<?php
defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    'cp.id',
    'c.company',
    'i.description',
    'cp.price',
    'cp.discount_percent',
    'cp.discount_amount',
    'cp.is_active',
];

$sIndexColumn = 'id';
$sTable       = 'tblcustomer_pricing cp';

$join = [
    'LEFT JOIN tblclients c ON c.userid = cp.customer_id',
    'LEFT JOIN tblitems i ON i.id = cp.item_id',
];

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, [], ['cp.id']);

$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];

    $row[] = $aRow['company'];
    $row[] = $aRow['description'];
    $row[] = app_format_money($aRow['price'], get_base_currency());
    $row[] = $aRow['discount_percent'] . '%';
    $row[] = app_format_money($aRow['discount_amount'], get_base_currency());
    
    $row[] = '<div class="onoffswitch">
        <input type="checkbox" data-switch-url="' . admin_url() . 'custom_pricing/customer_pricing/change_status" name="onoffswitch" class="onoffswitch-checkbox" id="c_' . $aRow['id'] . '" data-id="' . $aRow['id'] . '" ' . ($aRow['is_active'] == 1 ? 'checked' : '') . '>
        <label class="onoffswitch-label" for="c_' . $aRow['id'] . '"></label>
    </div>';

    $options = '';
    if (has_permission('custom_pricing', '', 'edit')) {
        $options .= '<a href="' . admin_url('custom_pricing/customer_pricing/edit/' . $aRow['id']) . '" class="btn btn-default btn-icon"><i class="fa fa-pencil-square-o"></i></a>';
    }
    if (has_permission('custom_pricing', '', 'delete')) {
        $options .= '<a href="' . admin_url('custom_pricing/customer_pricing/delete/' . $aRow['id']) . '" class="btn btn-danger btn-icon _delete"><i class="fa fa-remove"></i></a>';
    }

    $row[] = $options;

    $output['aaData'][] = $row;
}