$(function() {
  "use strict";

  // Item multi-select initialization for item groups
  if ($('select[name="items[]"]').length > 0) {
      $('select[name="items[]"]').selectpicker({
          liveSearch: true,
          virtualScroll: true
      });
  }

  // Handle price and discount value validations
  $('input[name="discount_percent"]').on('change', function() {
      var value = parseFloat($(this).val());
      if (value < 0) {
          $(this).val(0);
      } else if (value > 100) {
          $(this).val(100);
      }
  });

  $('input[name="discount_amount"], input[name="price"]').on('change', function() {
      var value = parseFloat($(this).val());
      if (value < 0) {
          $(this).val(0);
      }
  });

  // Init datatables where needed
  if ($('.table-customer-pricing').length > 0) {
      var customerPricingTable = $('.table-customer-pricing').DataTable({
          "processing": true,
          "serverSide": true,
          "ajax": {
              "url": admin_url + "custom_pricing/customer_pricing/table",
              "type": "POST"
          },
          "fnDrawCallback": function(oSettings) {
              _table_jump_to_page(this, oSettings);
          },
          "columns": [
              { "data": "customer" },
              { "data": "item" },
              { "data": "price" },
              { "data": "discount_percent" },
              { "data": "discount_amount" },
              { "data": "is_active" },
              { "data": "options", "orderable": false }
          ]
      });
  }

  if ($('.table-customer-group-pricing').length > 0) {
      var groupPricingTable = $('.table-customer-group-pricing').DataTable({
          "processing": true,
          "serverSide": true,
          "ajax": {
              "url": admin_url + "custom_pricing/group_pricing/table",
              "type": "POST"
          },
          "fnDrawCallback": function(oSettings) {
              _table_jump_to_page(this, oSettings);
          },
          "columns": [
              { "data": "customer_group" },
              { "data": "item" },
              { "data": "price" },
              { "data": "discount_percent" },
              { "data": "discount_amount" },
              { "data": "is_active" },
              { "data": "options", "orderable": false }
          ]
      });
  }

  if ($('.table-item-groups').length > 0) {
      var itemGroupsTable = $('.table-item-groups').DataTable({
          "processing": true,
          "serverSide": true,
          "ajax": {
              "url": admin_url + "custom_pricing/item_groups/table",
              "type": "POST"
          },
          "fnDrawCallback": function(oSettings) {
              _table_jump_to_page(this, oSettings);
          },
          "columns": [
              { "data": "name" },
              { "data": "description" },
              { "data": "is_active" },
              { "data": "options", "orderable": false }
          ]
      });
  }

    // For customer pricing table
    if ($('.table-customer-pricing').length > 0) {
        initDataTable('.table-customer-pricing', admin_url + 'custom_pricing/table', [6], [6]);
    }
    
    // For customer group pricing table
    if ($('.table-customer-group-pricing').length > 0) {
        initDataTable('.table-customer-group-pricing', admin_url + 'custom_pricing/group_pricing_table', [6], [6]);
    }
    
    // For item groups table
    if ($('.table-item-groups').length > 0) {
        initDataTable('.table-item-groups', admin_url + 'custom_pricing/item_groups_table', [3], [3]);
    }
});