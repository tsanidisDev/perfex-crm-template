<?php
defined('BASEPATH') or exit('No direct script access allowed');

$route['custom_pricing/customer_pricing'] = 'customer_pricing/index';
$route['custom_pricing/customer_pricing/create'] = 'customer_pricing/pricing';
$route['custom_pricing/customer_pricing/edit/(:num)'] = 'customer_pricing/pricing/$1';
$route['custom_pricing/customer_pricing/delete/(:num)'] = 'customer_pricing/delete/$1';

$route['custom_pricing/group_pricing'] = 'group_pricing/index';
$route['custom_pricing/group_pricing/create'] = 'group_pricing/pricing';
$route['custom_pricing/group_pricing/edit/(:num)'] = 'group_pricing/pricing/$1';
$route['custom_pricing/group_pricing/delete/(:num)'] = 'group_pricing/delete/$1';

$route['custom_pricing/item_groups'] = 'item_groups/index';
$route['custom_pricing/item_groups/create'] = 'item_groups/group';
$route['custom_pricing/item_groups/edit/(:num)'] = 'item_groups/group/$1';
$route['custom_pricing/item_groups/delete/(:num)'] = 'item_groups/delete/$1';
$route['custom_pricing/item_groups/pricing'] = 'item_groups/pricing';
$route['custom_pricing/item_groups/pricing/edit/(:num)'] = 'item_groups/edit_pricing/$1';

// In config/routes.php
$route['custom_pricing/table'] = 'custom_pricing/table';
$route['custom_pricing/group_pricing_table'] = 'custom_pricing/group_pricing_table';
$route['custom_pricing/item_groups_table'] = 'custom_pricing/item_groups_table';