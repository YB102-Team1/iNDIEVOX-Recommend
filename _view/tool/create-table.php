<?php
SiteHelper::getNavBar('tool', $url);
?>
<style>
.remove-column-btn {
    margin-right: 5px;
    visibility: hidden;
}
div:hover > .remove-column-btn {
    visibility: visible;
}
</style>
<form id="create-table-form" class="form-horizontal">
    <div class="control-group">
        <label class="control-label" for="table-name">Table Name</label>
        <div class="controls">
            <input type="text" id="table-name" name="table_name" placeholde="Table Name" />
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="table-name">Add Column</label>
        <div class="controls">
            <div class="span12" style="margin-left: 0;">
                <table class="table table-bordered table-condensed table-striped">
                    <thead>
                        <tr>
                            <th class="span2">欄位名稱</th>
                            <th class="span2">型態</th>
                            <th class="span2">長度 / EUNM值</th>
                            <th class="span1">UNSIGNED</th>
                            <th class="span4">預設值</th>
                            <th class="span1">操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="input-medium">
                                <input id="column-name" type="text" class="input-medium" />
                                <span id="column-name-error" class="error-help"></span>
                            </td>
                            <td class="input-medium">
                                <select id="column-type" class="input-medium">
                                    <optgroup label="文字">
                                        <option value="varchar">VARCHAR</option>
                                        <option value="text">TEXT</option>
                                        <option value="enum">ENUM</option>
                                    </optgroup>
                                    <optgroup label="數值">
                                        <option value="tinyint">TINYINT</option>
                                        <option value="int">INT</option>
                                        <option value="double">DOUBLE</option>
                                    </optgroup>
                                    <optgroup label="日期">
                                        <option value="date">DATE</option>
                                        <option value="datetime">DATETIME</option>
                                    </optgroup>
                                </select>
                            </td>
                            <td class="input-medium">
                                <input id="column-length" type="text" class="input-medium" />
                                <span id="column-length-error" class="error-help"></span>
                            </td>
                            <td class="input-mini">
                                <input id="column-unsigned" type="checkbox" value="UNSIGNED" />
                            </td>
                            <td>
                                <input id="column-default-empty" type="radio" name="default_value_radio" value="none" checked />
                                <span style="padding: 5px 15px 5px 5px;">無</span>
                                <input id="column-default-as" type="radio" name="default_value_radio" value="as" />
                                <input id="column-default-value" type="text" class="input-medium" />
                            </td>
                            <td class="input-mini">
                                <button id="add-column-btn" type="button" class="btn btn-default">加入</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="table-name">SQL Preview</label>
        <div class="controls">
            CREATE TABLE IF NOT EXISTS `<span id="table-name-display" style="color: #3A87AD; font-weight: bold;"></span>` (
            <div style="margin-left: 40px; line-height: 25px;">
                <div>`id` int(11) unsigned NOT NULL,</div>
                <div id="column-block" style="color: #3A87AD;"></div>
                <div>`is_deleted` tinyint(1) unsigned NOT NULL DEFAULT '0',</div>
                <div>`create_time` datetime NOT NULL,</div>
                <div>`modify_time` datetime NOT NULL,</div>
                <div>`delete_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'</div>
            </div>
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        </div>
    </div>
    <div class="control-group">
        <div class="controls">
            <button type="submit" class="btn btn-primary">Create</button>
            <button id="reset-column-btn" type="button" class="btn btn-default">Reset Column</button>
        </div>
    </div>
</form>
<hr>
<div class="alert alert-info">
    <strong>Note：</strong>
    <ul>
        <li>將會在本機資料庫建立表格，若已經有<strong>相同名稱</strong>的表格則會<strong><span class="text-error">略過此步驟</span></strong></li>
        <li>將會產生相對應的 class 檔案，若已經有<strong>相同名稱</strong>的檔案則會<strong><span class="text-error">略過此步驟</span></strong></li>
        <li>將會產生相對應的 class god 檔案，若已經有<strong>相同名稱</strong>的檔案則會<strong><span class="text-error">略過此步驟</span></strong></li>
    </ul>
</div>
<script>
$(document).ready(function() {

    $(document.body).off('input', '#table-name');
    $(document.body).on('input', '#table-name', function() {

        $('#table-name-display').text($(this).val());
        if ($(this).val() != $(this).val().match(/[a-zA-Z0-9_]*/)[0]) {

            $('#system-message').html('不合法的表格名稱');
            $('#system-message').show();

        } else {

            $('#system-message').hide();

        }

    });

    $(document.body).off('focus', '#column-default-value');
    $(document.body).on('focus', '#column-default-value', function() {

        $('#column-default-as').click();

    });

    $(document.body).off('click', '#add-column-btn');
    $(document.body).on('click', '#add-column-btn', function() {

        var column_name = $('#column-name').val();
        var column_type = $('#column-type').val();
        var column_length = $('#column-length').val();
        var column_default_empty = $('#column-default-empty').is(':checked');
        var column_default_as = $('#column-default-as').is(':checked');
        var column_default_value = $('#column-default-value').val();
        var column_unsigned = $('#column-unsigned').is(':checked');
        var validate = true;

        if (!column_name) {

            $('#column-name-error').html('未填寫');
            $('#column-name-error').show();
            validate = false;

        } else if ($('input[name="' + column_name + '"]').length > 0) {

            $('#column-name-error').html('欄位名稱重複');
            $('#column-name-error').show();
            validate = false;

        } else if (column_name != column_name.match(/[a-zA-Z0-9_]*/)[0]) {

            $('#column-name-error').html('欄位名稱不合法');
            $('#column-name-error').show();
            validate = false;

        } else {

            $('#column-name-error').hide();

        }

        if (   column_type == 'varchar'
            || column_type == 'enum'
            || column_type == 'tinyint'
            || column_type == 'int'
        ) {

            if (column_type == 'enum' ) {

                if (!column_length) {

                    $('#column-length-error').html('無效的 ENUM 值');
                    $('#column-length-error').show();
                    validate = false;

                } else {

                    $('#column-length-error').hide();

                }

            } else if (parseInt(column_length) <= 0 || isNaN(parseInt(column_length))) {

                $('#column-length-error').html('無效的長度');
                $('#column-length-error').show();
                validate = false;

            } else {

                $('#column-length-error').hide();

            }

        } else {

            $('#column-length-error').hide();

        }

        if (validate) {

            var column_value = '';

            if (   column_type == 'varchar'
                || column_type == 'enum'
                || column_type == 'tinyint'
                || column_type == 'int'
            ) {

                column_value += column_type + '(' + column_length + ') ';

            } else {

                column_value += column_type + ' ';

            }

            if (   column_type == 'tinyint'
                || column_type == 'int'
                || column_type == 'double'
            ) {

                if (column_unsigned) {

                    column_value += 'unsigned ';

                }

            }

            column_value += 'NOT NULL ';

            if (column_default_as) {

                column_value += 'DEFAULT \'' + column_default_value + '\'';

            }

            html = '<div style="margin-left: -40px; line-height: 25px;">' +
                       '<button type="button" class="btn btn-danger btn-mini remove-column-btn">移除</button>' +
                       '<input name="' + column_name + '" value="' + column_value + '" type="hidden" />' +
                       '`' + column_name + '` ' + column_value + ',' +
                   '</div>';
            $('#column-block').append(html);

        }

    });

    $(document.body).off('click', '.remove-column-btn');
    $(document.body).on('click', '.remove-column-btn', function() {

        $(this).parent().remove();

    });

    $(document.body).off('click', '#reset-column-btn');
    $(document.body).on('click', '#reset-column-btn', function() {

        $('#column-block').html('');

    });

    function createTableValidate(formData, jqForm, options) {

        var validate = true;

        if (   !$('#table-name').val()
            || $('#table-name').val() != $('#table-name').val().match(/[a-zA-Z0-9_]*/)[0]
        ) {

            validate = false;

        }

        if (validate) {

            $('#system-message').html('處理中');
            $('#system-message').show();

        }

        return validate;

    }

    function createTableResponse(response, statusText, xhr, $form) {

        if (response.status.code == 0) {

            $('#system-message').html('成功');
            $('#system-message').fadeOut(2000);

        } else {

            $('#system-message').html('失敗');
            $('#system-message').fadeOut(2000);

        }

    }

    $('#create-table-form').ajaxForm({

        beforeSubmit: createTableValidate,
        success:      createTableResponse,
        url: '/action/tool/create-table',
        type: 'post',
        dataType: 'json'

    });

});
</script>