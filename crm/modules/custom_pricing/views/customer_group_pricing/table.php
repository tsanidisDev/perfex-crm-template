<?php
defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    'cgp.id',
    'cg.name',
    'i.description',
    'cgp.price',
    'cgp.discount_percent',
    'cgp.discount_amount',
    'cgp.is_active',
];

$sIndexColumn = 'id';
$sTable       = 'tblcustomer_group_pricing cgp';

$join = [
    'LEFT JOIN tblcustomers_groups cg ON cg.id = cgp.customer_group_id',
    'LEFT JOIN tblitems i ON i.id = cgp.item_id',
];

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, [], ['cgp.id']);

$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];

    $row[] = $aRow['name'];
    $row[] = $aRow['description'];
    $row[] = app_format_money($aRow['price'], get_base_currency());
    $row[] = $aRow['discount_percent'] . '%';
    $row[] = app_format_money($aRow['discount_amount'], get_base_currency());
    
    $row[] = '<div class="onoffswitch">
        <input type="checkbox" data-switch-url="' . admin_url() . 'custom_pricing/group_pricing/change_status" name="onoffswitch" class="onoffswitch-checkbox" id="cg_' . $aRow['id'] . '" data-id="' . $aRow['id'] . '" ' . ($aRow['is_active'] == 1 ? 'checked' : '') . '>
        <label class="onoffswitch-label" for="cg_' . $aRow['id'] . '"></label>
    </div>';

    $options = '';
    if (has_permission('custom_pricing', '', 'edit')) {
        $options .= '<a href="' . admin_url('custom_pricing/group_pricing/edit/' . $aRow['id']) . '" class="btn btn-default btn-icon"><i class="fa fa-pencil-square-o"></i></a>';
    }
    if (has_permission('custom_pricing', '', 'delete')) {
        $options .= '<a href="' . admin_url('custom_pricing/group_pricing/delete/' . $aRow['id']) . '" class="btn btn-danger btn-icon _delete"><i class="fa fa-remove"></i></a>';
    }

    $row[] = $options;

    $output['aaData'][] = $row;
}