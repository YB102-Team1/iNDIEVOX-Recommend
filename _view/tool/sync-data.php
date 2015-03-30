<?php
SiteHelper::getNavBar('tool', $url);
?>
<form id="sync-data-form" class="form-horizontal">
    <div class="control-group">
        <label class="control-label" for="table-list">Sync Data</label>
        <div class="controls">
            <div class="span6" style="margin-left: 0;">
                <input type="hidden" id="table-list" name="table_list" value="" />
                <table class="table table-bordered table-condensed">
                    <thead>
                    </thead>
                    <tbody>
                        <tr>
                            <td><input type="checkbox" id="table-checkbox-all" /></td>
                            <td class="span2"><strong>Table Name</strong></td>
                            <td class="span2"><strong>Data Last Modified</strong></td>
                            <td class="span2"><strong>File Last Modified</strong></td>
                        </tr>
                        <tr><td colspan="4"></td></tr>
                        <?php
                        $db_obj = new DatabaseAccess();
                        $exist_table_array = $db_obj->getAllTables();
                        $data_table_array = array();
                        foreach (glob(DATA_SQL_ROOT.'/*.sql') as $sql_file) {

                            $data_table_array[] = str_replace('.sql', '', str_replace(DATA_SQL_ROOT.'/', '', $sql_file));

                        }// foreach (glob(DATA_SQL_ROOT.'/*.sql') as $sql_file)

                        foreach ($exist_table_array as $table_name) {

                            $sql_path = DATA_SQL_ROOT.'/'.$table_name.'.sql';
                            $data_last_modify_time = $db_obj->getTableLastModifyTime($table_name);
                            $file_last_modify_time = @filemtime($sql_path) ? date('Y/m/d H:i:s', filemtime($sql_path)) : '--';

                            if (in_array($table_name, $data_table_array)) {

                                $row_class = '';
                                $file_last_modify_time_class = '';
                                if ($file_last_modify_time > $data_last_modify_time) {

                                    $row_class = 'info';
                                    $file_last_modify_time_class = 'text-error';

                                }// end if ($file_last_modify_time > $data_last_modify_time)
                        ?>
                        <tr class="<?php echo $row_class; ?>">
                            <td>
                                <input type="checkbox" class="table-checkbox" value="<?php echo $table_name; ?>" />
                            </td>
                            <td class="input-medium">&nbsp;&nbsp;&nbsp;<?php echo $table_name; ?></td>
                            <td class="input-medium"><?php echo $data_last_modify_time; ?></td>
                            <td class="input-medium <?php echo $file_last_modify_time_class; ?>"><?php echo $file_last_modify_time; ?></td>
                        </tr>
                        <?php
                            } else {// end if (in_array($table_name, $data_table_array))
                        ?>
                        <tr>
                            <td></td>
                            <td class="input-medium">&nbsp;&nbsp;&nbsp;<?php echo $table_name; ?></td>
                            <td class="input-medium"><?php echo $data_last_modify_time; ?></td>
                            <td class="input-medium"><?php echo $file_last_modify_time; ?></td>
                        </tr>
                        <?php
                            }// end if (in_array($table_name, $data_table_array)) else

                        }// end foreach ($exist_table_array as $table_name)
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="control-group">
        <div class="controls">
            <button type="submit" class="btn btn-primary">Sync</button>
        </div>
    </div>
</form>
<hr>
<div class="alert alert-info">
    <strong>Note：</strong>
    <ul>
        <li>將會把勾選的<strong><span class="text-warning">表格資料</span><span class="text-error">清空</span></strong>，再將存放在 <?php echo DATA_SQL_ROOT; ?> 裡的 sql 檔案資料<strong><span class="text-error">同步</span></strong>進表格中</li>
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

    function syncDataValidate(formData, jqForm, options) {

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

    function syncDataResponse(response, statusText, xhr, $form) {

        if (response.status.code == 0) {

            $('#system-message').html(response.message);
            $('#system-message').fadeOut(2000);
            window.location.reload();

        } else {

            $('#system-message').html('失敗');
            $('#system-message').fadeOut(2000);
        }

    }

    $('#sync-data-form').ajaxForm({

        beforeSubmit: syncDataValidate,
        success:      syncDataResponse,
        url: '/action/tool/sync-data',
        type: 'post',
        dataType: 'json'

    });

});
</script>