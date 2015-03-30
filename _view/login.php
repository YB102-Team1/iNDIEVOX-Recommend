<?php
SiteHelper::getNavBar('site', $url);

if (SiteHelper::isLogin()) {
    header("Refresh: 0; url=/index.php");
}
?>
<div class="jumbotron"></div>
<form class="form-signin">
    <h3 class="form-signin-heading fwb">
        請輸入帳號密碼
    </h3>
    <input id="user-id" class="form-control" type="text" autofocus="" required="" placeholder="請輸入帳號" style="margin-bottom: 0;" />
    <input id="password" class="form-control" type="password" required="" placeholder="請輸入密碼" />
    <input id="prev" type="hidden" value="<?php echo $_GET['prev']; ?>" />
    <h4>&nbsp;</h4>
    <button id="login-btn" class="btn btn-lg btn-primary btn-block" type="button">
        登入
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
                password: $('#password').val(),
                prev: $('#prev').val()
            },
            type: 'post',
            dataType: "json",
            success: function( response ) {
                if (response.status.code == 0) {

                    $('#system-message').html('成功');
                    $('#system-message').fadeOut(2000);
                    if (!response.parameter.url || response.parameter.url == '/login.php') {
                        window.location = '/';
                    } else {
                        window.location = response.parameter.url;
                    }
                    // console.log(response.parameter.url);

                } else {

                    $('#system-message').html('失敗');
                    $('#system-message').fadeOut(2000);

                }
            }
        });
    });

}); 
</script>