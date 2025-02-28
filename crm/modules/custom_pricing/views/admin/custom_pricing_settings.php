<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="panel_s">
    <div class="panel-body">
        <h4 class="no-margin"><?php echo _l('custom_pricing_settings'); ?></h4>
        <hr class="hr-panel-heading" />
        
        <?php echo form_open(admin_url('custom_pricing/save')); ?>
        
        <div class="form-group">
            <label for="customer_id"><?php echo _l('customer'); ?></label>
            <select id="customer_id" name="customer_id" class="form-control">
                <?php foreach ($customers as $customer): ?>
                    <option value="<?php echo $customer['userid']; ?>"><?php echo $customer['company']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="item_id"><?php echo _l('item'); ?></label>
            <select id="item_id" name="item_id" class="form-control">
                <?php foreach ($items as $item): ?>
                    <option value="<?php echo $item['id']; ?>"><?php echo $item['description']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="custom_price"><?php echo _l('custom_price'); ?></label>
            <!--<input type="number" id="custom_price" name="custom_price" class="form-control" placeholder="<?php echo _l('custom_price'); ?>" required>-->
            <input type="text" name="custom_price" value="<?php echo set_value('custom_price'); ?>" placeholder="Enter custom price or leave blank for default" />
        </div>

        <div class="form-group">
            <label for="discount_percentage"><?php echo _l('discount_percentage'); ?></label>
            <!--<input type="number" id="discount_percentage" name="discount_percentage" class="form-control" placeholder="<?php echo _l('discount_percentage'); ?>" required>-->
            <input type="text" name="discount_percentage" value="<?php echo set_value('discount_percentage'); ?>" placeholder="Enter discount percentage or leave blank for 0%" />
        </div>

        <button type="submit" class="btn btn-primary"><?php echo _l('save'); ?></button>

        <?php echo form_close(); ?>
    </div>
</div>
