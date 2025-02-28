<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="panel_s">
    <div class="panel-body">
        <h4 class="no-margin"><?php echo _l('custom_pricing_overview'); ?></h4>
        <hr class="hr-panel-heading" />

        <div class="row">
            <div class="col-md-4">
                <div class="stats">
                    <h5><?php echo _l('total_pricing_rules'); ?></h5>
                    <p><?php echo $total_pricing_rules; ?></p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats">
                    <h5><?php echo _l('total_customers'); ?></h5>
                    <p><?php echo $total_customers; ?></p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats">
                    <h5><?php echo _l('total_items'); ?></h5>
                    <p><?php echo $total_items; ?></p>
                </div>
            </div>
        </div>

        <hr />

        <a href="<?php echo admin_url('custom_pricing/settings'); ?>" class="btn btn-primary"><?php echo _l('manage_custom_pricing'); ?></a>
    </div>
</div>
