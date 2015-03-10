<?php
SiteHelper::getNavBar('tool', $url);
?>
<form id="import-table-form" class="form-horizontal">
    <div class="control-group">
        <label class="control-label" for="table-list">Import Table</label>
        <div class="controls">
            <div class="span2" style="margin-left: 0;">
                <input type="hidden" id="table-list" name="table_list" value="" />
                <table class="table table-bordered table-condensed">
                    <thead>
                    </thead>
                    <tbody>
                        <tr>
                            <td><input type="checkbox" id="table-checkbox-all" /></td>
                            <td class="span2"><strong>Table Name</strong></td>
                        </tr>
                        <tr><td colspan="2"></td></tr>
                        <?php
                        $db_obj = new DatabaseAccess();
                        $exist_table_array = $db_obj->getAllTables();
                        $new_table_array = array();
                        foreach (glob(TABLE_SQL_ROOT.'/*.sql') as $sql_file) {

                            $new_table_array[] = str_replace('.sql', '', str_replace(TABLE_SQL_ROOT.'/', '', $sql_file));

                        }// foreach (glob(TABLE_SQL_ROOT.'/*.sql') as $sql_file)

                        $total_table_array = array_unique(array_merge($exist_table_array, $new_table_array));
                        asort($total_table_array);

                        foreach ($total_table_array as $table_name) {

                            if (in_array($table_name, $exist_table_array)) {
                        ?>
                        <tr>
                            <td></td>
                            <td class="input-medium">&nbsp;&nbsp;&nbsp;<?php echo $table_name; ?></td>
                        </tr>
                        <?php
                            } else {// end if (in_array($table_name, $exist_table_array))
                        ?>
                        <tr class="success">
                            <td>
                                <input type="checkbox" class="table-checkbox" value="<?php echo $table_name; ?>" />
                            </td>
                            <td class="input-medium">+&nbsp;<?php echo $table_name; ?></td>
                        </tr>
                        <?php
                            }// end if (in_array($table_name, $exist_table_array)) else

                        }// end foreach ($total_table_array as $table_name)
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="control-group">
        <div class="controls">
            <button type="submit" class="btn btn-primary">Import</button>
        </div>
    </div>
</form>
<hr>
<div class="alert alert-info">
    <strong>Note：</strong>
    <ul>
        <li>將會在本機資料庫建立勾選的表格</li>
    </ul>
</div>
<script>
$(document).ready(function() {

    $(document.body).off('change', '#table-checkbox-all');
    $(document.body).on('change', '#table-checkbox-all', function() {

        if ($(this).is(':checked')) {

            $('.table-checkbox:not(:checked)').trigger("click");

        } else {

            $('.table-checkbox:checked').trigger("click");

        }

    });

    $(document.body).off('change', '.table-checkbox');
    $(document.body).on('change', '.table-checkbox', function() {

        var table_list = "";
        $.each($('.table-checkbox:checked'), function() {

            if (table_list.length > 0) {

                table_list += ',';

            }
            table_list += $(this).val();

        });
        $('#table-list').val(table_list);

    });

    function importTableValidate(formData, jqForm, options) {

        var validate = true;

        if ($('.table-checkbox:checked').length == 0) {

            validate = false;

        }

        if (validate) {

            $('#system-message').html('處理中');
            $('#system-message').show();

        }

        return validate;

    }

    function importTableResponse(response, statusText, xhr, $form) {

        if (response.status.code == 0) {

            $('#system-message').html(response.message);
            $('#system-message').fadeOut();
            window.location.reload();

        } else {

            $('#system-message').html('失敗');
            $('#system-message').fadeOut(2000);
        }

    }

    $('#import-table-form').ajaxForm({

        beforeSubmit: importTableValidate,
        success:      importTableResponse,
        url: '/action/tool/import-table',
        type: 'post',
        dataType: 'json'

    });

});
</script>