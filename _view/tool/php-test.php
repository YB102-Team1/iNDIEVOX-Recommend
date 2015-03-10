<?php
SiteHelper::getNavBar('tool', $url);
?>
<form id="eval-code-form">
    &lt;?php<br>
    <textarea name="code" rows="15" style="width: 99%; resize: none; overflow: scroll; margin-bottom: 0;"></textarea><br>
    ?&gt;<br>
    <button type="submit" class="btn btn-primary">Run</button>
</form>
<hr>
<div>
    Result:<br>
    <pre id="eval-block"></pre>
</div>
<script>
$(document).ready(function() {

    function evalCodeValidate(formData, jqForm, options) {

        var validate = true;

        if (validate) {

            $('#system-message').html('處理中');
            $('#system-message').show();

        }

        return validate;

    }

    function evalCodeResponse(response, statusText, xhr, $form) {

        $('#eval-block').html(response);

        $('#system-message').html('成功');
        $('#system-message').fadeOut(2000);

    }

    $('#eval-code-form').ajaxForm({

        beforeSubmit: evalCodeValidate,
        success:      evalCodeResponse,
        url: '/action/tool/eval-code',
        type: 'post',
        dataType: 'html'

    });

});
</script>