<?php
SiteHelper::getNavBar('tool', $url);
?>
<form id="arrange-database-form">
    <button type="submit" class="btn btn-primary">Arrange</button>
</form>
<hr>
<div class="alert alert-info">
    <strong>Note：</strong>
    <ul>
        <li><strong><span class="text-error">請先確認 phpMyAdmin 使用者帳號已經建立</span></strong></li>
        <li>將會在本機建立資料庫，若已經有<strong>相同名稱</strong>的資料庫則會<strong><span class="text-error">略過此步驟</span></strong></li>
        <li>將會在本機資料庫建立表格，若已經有<strong>相同名稱</strong>的表格則會<strong><span class="text-error">略過此步驟</span></strong></li>
        <li>將會產生相對應的 class 檔案，若已經有<strong>相同名稱</strong>的檔案則會<strong><span class="text-error">略過此步驟</span></strong></li>
        <li>將會產生相對應的 class god 檔案，若已經有<strong>相同名稱</strong>的檔案則會<strong><span class="text-error">略過此步驟</span></strong></li>
        <li>將會把<strong><span class="text-error">所有</span><span class="text-warning">表格資料</span><span class="text-error">清空</span></strong>，再將存放在 <?php echo DATA_SQL_ROOT; ?> 裡的 sql 檔案資料<strong><span class="text-error">同步</span></strong>進表格中</li>
    </ul>
</div>
<hr>
<div id="result-block"></div>
<script>
$(document).ready(function() {

    function arrangeDatabaseValidate(formData, jqForm, options) {

        var validate = true;

        if (validate) {

            $('#system-message').html('處理中');
            $('#system-message').show();

        }

        return validate;

    }

    function arrangeDatabaseResponse(response, statusText, xhr, $form) {

        $('#result-block').html(response);

        $('#system-message').html('成功');
        $('#system-message').fadeOut(2000);

    }

    $('#arrange-database-form').ajaxForm({

        beforeSubmit: arrangeDatabaseValidate,
        success:      arrangeDatabaseResponse,
        url: '/action/tool/arrange-database',
        type: 'post',
        dataType: 'html'

    });

});
</script>