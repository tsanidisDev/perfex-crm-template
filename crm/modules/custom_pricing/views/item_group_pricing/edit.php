<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel_s">
                    <div class="panel-body">
                        <h4 class="no-margin"><?php echo _l('edit', _l('item_group_pricing')) . ': ' . $group->name; ?></h4>
                        <hr class="hr-panel-heading" />
                        <?php echo form_open(current_url(), ['id' => 'item-group-pricing-form']); ?>
                        <div class="row">
                            <div class="col-md-6">
                                <?php echo render_input('discount_percent', 'discount_percent', isset($pricing->discount_percent) ? $pricing->discount_percent : '0', 'number', ['step' => 'any', 'min' => 0, 'max' => 100]); ?>
                            </div>
                            <div class="col-md-6">
                                <?php echo render_input('discount_amount', 'discount_amount', isset($pricing->discount_amount) ? $pricing->discount_amount : '0', 'number', ['step' => 'any', 'min' => 0]); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="is_active"><?php echo _l('is_active'); ?></label>
                                    <div class="checkbox checkbox-primary">
                                        <input type="checkbox" name="is_active" id="is_active" <?php echo (isset($pricing->is_active) && $pricing->is_active == 1) || !isset($pricing->is_active) ? 'checked' : ''; ?> value="1">
                                        <label for="is_active"></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <h5><?php echo _l('items_in_group'); ?></h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th><?php echo _l('item'); ?></th>
                                                <th><?php echo _l('price'); ?></th>
                                                <th><?php echo _l('item_group_pricing'); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            $base_currency = get_base_currency();
                                            $discount_percent = isset($pricing->discount_percent) ? $pricing->discount_percent : 0;
                                            $discount_amount = isset($pricing->discount_amount) ? $pricing->discount_amount : 0;
                                            
                                            foreach ($group_items as $item) { 
                                                $discounted_price = $item['rate'];
                                                if ($discount_percent > 0) {
                                                    $discounted_price = $discounted_price - ($discounted_price * $discount_percent / 100);
                                                }
                                                if ($discount_amount > 0) {
                                                    $discounted_price = $discounted_price - $discount_amount;
                                                }
                                                if ($discounted_price < 0) {
                                                    $discounted_price = 0;
                                                }
                                            ?>
                                            <tr>
                                                <td><?php echo $item['description']; ?></td>
                                                <td><?php echo app_format_money($item['rate'], $base_currency); ?></td>
                                                <td><?php echo app_format_money($discounted_price, $base_currency); ?></td>
                                            </tr>
                                            <?php } ?>
                                            <?php if (empty($group_items)) { ?>
                                            <tr>
                                                <td colspan="3" class="text-center"><?php echo _l('no_items_in_group'); ?></td>
                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
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
        appValidateForm($('#item-group-pricing-form'), {});
    });
</script>
</body>
</html>