<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel_s">
                    <div class="panel-body">
                        <h4 class="no-margin"><?php echo _l('edit', _l('item_group')); ?></h4>
                        <hr class="hr-panel-heading" />
                        <?php echo form_open(current_url(), ['id' => 'item-group-form']); ?>
                        <div class="row">
                            <div class="col-md-12">
                                <?php echo render_input('name', 'group_name', $group->name); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <?php echo render_textarea('description', 'group_description', $group->description); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="is_active"><?php echo _l('is_active'); ?></label>
                                    <div class="checkbox checkbox-primary">
                                        <input type="checkbox" name="is_active" id="is_active" <?php echo ($group->is_active == 1) ? 'checked' : ''; ?> value="1">
                                        <label for="is_active"></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <hr />
                                <h4><?php echo _l('group_items'); ?></h4>
                                <?php 
                                // Get array of item IDs that are in this group
                                $selected_items = [];
                                foreach ($group_items as $item) {
                                    $selected_items[] = $item['item_id'];
                                }
                                echo render_select('items[]', $items, ['id', 'description'], 'select_items', $selected_items, ['multiple' => true, 'data-actions-box' => true], [], '', '', false); 
                                ?>
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
        appValidateForm($('#item-group-form'), {
            name: 'required'
        });
        
        $('select[name="items[]"]').selectpicker({
            liveSearch: true,
            virtualScroll: true
        });
    });
</script>
</body>
</html>