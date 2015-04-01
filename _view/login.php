<?php
SiteHelper::getNavBar($url);
if (SiteHelper::isLogin()) {
    header("Refresh: 0; url=/index.php");
}
?>
<div class="jumbotron"></div>
<form class="form-signin">
    <h3 class="form-signin-heading fwb">
        請輸入帳號密碼
    </h3>
    <input id="user-id" class="form-control input-xlarge" type="text" autofocus="" required="" placeholder="請輸入帳號" style="margin-bottom: 0;" />
    <input id="password" class="form-control input-xlarge" type="password" required="" placeholder="請輸入密碼" />
    <h4>&nbsp;</h4>
    <button id="login-btn" class="btn btn-lg btn-primary btn-block fwb" type="button">
        <h4>登入</h4>
    </button>
</form>
<script>
$(document).ready(function() {

    $(document.body).off('click', '#login-btn');
    $(document.body).on('click', '#login-btn', function() {
        $.ajax({
            url: '/action/site/login',
            data: {
                user_id: $('#user-id').val(),
                password: $('#password').val()
            },
            type: 'post',
            dataType: "json",
            success: function( response ) {
                if (response.status.code == 0) {

                    $('#system-message').html('成功');
                    $('#system-message').fadeOut(2000);
                    window.location = '/';

                } else {

                    $('#system-message').html('失敗');
                    $('#system-message').fadeOut(2000);

                }
            }
        });
    });

}); 
</script>