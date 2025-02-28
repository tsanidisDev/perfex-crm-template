<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="panel_s">
    <div class="panel-body">
        <h4 class="no-margin"><?php echo _l('custom_pricing_table'); ?></h4>
        <hr class="hr-panel-heading" />
        
        <table class="table dt-table">
            <thead>
                <tr>
                    <th><?php echo _l('customer'); ?></th>
                    <th><?php echo _l('item'); ?></th>
                    <th><?php echo _l('custom_price'); ?></th>
                    <th><?php echo _l('discount_percentage'); ?></th>
                    <th><?php echo _l('options'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pricing_rules as $rule): ?>
                    <tr>
                        <td><?php echo $rule['customer_name']; ?></td>
                        <td><?php echo $rule['item_description']; ?></td>
                        <td><?php echo $rule['custom_price']; ?></td>
                        <td><?php echo $rule['discount_percentage']; ?>%</td>
                        <td>
                            <a href="<?php echo admin_url('custom_pricing/edit/' . $rule['id']); ?>" class="btn btn-default btn-xs"><?php echo _l('edit'); ?></a>
                            <a href="<?php echo admin_url('custom_pricing/delete/' . $rule['id']); ?>" class="btn btn-danger btn-xs _delete"><?php echo _l('delete'); ?></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
