<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="_buttons">
                            <?php if (has_permission('custom_pricing', '', 'create')) { ?>
                                <a href="<?php echo admin_url('custom_pricing/item_groups/group'); ?>" class="btn btn-info">
    <?php echo _l('add_new', _l('item_group')); ?>
</a>
                            <?php } ?>
                        </div>
                        <div class="clearfix"></div>
                        <hr class="hr-panel-heading" />
                        <div class="clearfix"></div>
                        <?php render_datatable([
                            _l('name'),
                            _l('description'),
                            _l('is_active'),
                            _l('options'),
                        ], 'item-groups'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script>
    $(function() {
        initDataTable('.table-item-groups', window.location.href, [3], [3]);
    });
</script>
</body>
</html>