<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="clearfix"></div>
                        <hr class="hr-panel-heading" />
                        <div class="clearfix"></div>
                        <?php
                        $this->load->model('custom_pricing/item_groups_model');
                        $groups = $this->item_groups_model->get(['is_active' => 1]);
                        ?>
                        <table class="table dt-table">
                            <thead>
                                <tr>
                                    <th><?php echo _l('group_name'); ?></th>
                                    <th><?php echo _l('discount_percent'); ?></th>
                                    <th><?php echo _l('discount_amount'); ?></th>
                                    <th><?php echo _l('options'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($groups as $group) { 
                                    $pricing = $this->item_groups_model->get_group_pricing($group['id']); 
                                ?>
                                <tr>
                                    <td><?php echo $group['name']; ?></td>
                                    <td><?php echo $pricing ? $pricing->discount_percent : '0.00'; ?></td>
                                    <td><?php echo $pricing ? $pricing->discount_amount : '0.00'; ?></td>
                                    <td>
    <?php if (has_permission('custom_pricing', '', 'edit')) { ?>
        <a href="<?php echo admin_url('custom_pricing/item_groups/pricing/edit/'.$group['id']); ?>" class="btn btn-default btn-icon">
            <i class="fa fa-pencil-square"></i>
        </a>
    <?php } ?>
</td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
</body>
</html>