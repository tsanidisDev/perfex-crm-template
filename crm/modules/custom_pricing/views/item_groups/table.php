<?php
defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    'id',
    'name',
    'description',
    'is_active',
];

$sIndexColumn = 'id';
$sTable       = 'tblitem_groups';

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, [], [], ['id']);

$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];

    $row[] = $aRow['name'];
    $row[] = $aRow['description'];
    
    $row[] = '<div class="onoffswitch">
        <input type="checkbox" data-switch-url="' . admin_url() . 'custom_pricing/item_groups/change_status" name="onoffswitch" class="onoffswitch-checkbox" id="ig_' . $aRow['id'] . '" data-id="' . $aRow['id'] . '" ' . ($aRow['is_active'] == 1 ? 'checked' : '') . '>
        <label class="onoffswitch-label" for="ig_' . $aRow['id'] . '"></label>
    </div>';

    $options = '';
    if (has_permission('custom_pricing', '', 'edit')) {
        $options .= '<a href="' . admin_url('custom_pricing/item_groups/edit/' . $aRow['id']) . '" class="btn btn-default btn-icon"><i class="fa fa-pencil-square-o"></i></a>';
    }
    if (has_permission('custom_pricing', '', 'delete')) {
        $options .= '<a href="' . admin_url('custom_pricing/item_groups/delete/' . $aRow['id']) . '" class="btn btn-danger btn-icon _delete"><i class="fa fa-remove"></i></a>';
    }

    $row[] = $options;

    $output['aaData'][] = $row;
}