<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel_s">
                    <div class="panel-body">
                        <h4 class="no-margin"><?php echo _l('edit', _l('customer_group_pricing_singular')); ?></h4>
                        <hr class="hr-panel-heading" />
                        <?php echo form_open(current_url(), ['id' => 'customer-group-pricing-form']); ?>
                        <div class="row">
                            <div class="col-md-6">
                                <?php echo render_select('customer_group_id', $customer_groups, ['id', 'name'], 'customer_group', $pricing->customer_group_id); ?>
                            </div>
                            <div class="col-md-6">
                                <?php echo render_select('item_id', $items, ['id', 'description'], 'item', $pricing->item_id); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <?php echo render_input('price', 'price', $pricing->price, 'number', ['step' => 'any']); ?>
                            </div>
                            <div class="col-md-4">
                                <?php echo render_input('discount_percent', 'discount_percent', $pricing->discount_percent, 'number', ['step' => 'any', 'min' => 0, 'max' => 100]); ?>
                            </div>
                            <div class="col-md-4">
                                <?php echo render_input('discount_amount', 'discount_amount', $pricing->discount_amount, 'number', ['step' => 'any', 'min' => 0]); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="is_active"><?php echo _l('is_active'); ?></label>
                                    <div class="checkbox checkbox-primary">
                                        <input type="checkbox" name="is_active" id="is_active" <?php echo ($pricing->is_active == 1) ? 'checked' : ''; ?> value="1">
                                        <label for="is_active"></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-info pull-right"><?php echo _l('submit'); ?></button>
                            </div>
                        </div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script>
    $(function() {
        appValidateForm($('#customer-group-pricing-form'), {
            customer_group_id: 'required',
            item_id: 'required'
        });
    });
</script>
</body>
</html>