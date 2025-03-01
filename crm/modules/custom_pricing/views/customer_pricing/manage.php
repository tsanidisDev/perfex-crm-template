<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<?php render_datatable([
    _l('customer'),
    _l('item'),
    _l('price'),
    _l('discount_percent'),
    _l('discount_amount'),
    _l('is_active'),
    _l('options'),
], 'customer-pricing', [], [
    'data-url' => admin_url('custom_pricing/customer_pricing/table')
]); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="_buttons">
                            <?php if (has_permission('custom_pricing', '', 'create')) { ?>
                                <a href="<?php echo admin_url('custom_pricing/customer_pricing/pricing'); ?>" class="btn btn-info">
    <?php echo _l('add_new', _l('customer_pricing_singular')); ?>
</a>
                            <?php } ?>
                        </div>
                        <div class="clearfix"></div>
                        <hr class="hr-panel-heading" />
                        <div class="clearfix"></div>
                        <?php render_datatable([
                            _l('customer'),
                            _l('item'),
                            _l('price'),
                            _l('discount_percent'),
                            _l('discount_amount'),
                            _l('is_active'),
                            _l('options'),
                        ], 'customer-pricing'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script>
    $(function() {
        initDataTable('.table-customer-pricing', window.location.href, [6], [6]);
    });
</script>
</body>
</html>