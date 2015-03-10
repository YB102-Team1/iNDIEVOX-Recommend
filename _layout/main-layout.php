<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />

        <script src="/_asset/js/jquery/jquery-1.9.1.min.js"></script>
        <script src="/_asset/js/jquery-form/jquery.form.js"></script>
        <script src="/_asset/js/jquery-ui/js/jquery-ui-1.10.2.custom.min.js"></script>
        <script src="/_asset/js/konami/jquery.konami.js"></script>
        <script src="/_asset/js/pjax/pjax.js"></script>

        <link href="/_asset/css/bootstrap/css/bootstrap-responsive.min.css" type="text/css" rel="stylesheet">
        <link href="/_asset/css/bootstrap/css/bootstrap.min.css" type="text/css" rel="stylesheet">
        <link href="/_asset/js/jquery-ui/css/dark-hive/jquery-ui-1.10.2.custom.min.css" type="text/css" rel="stylesheet">

        <script src="/_asset/js/main.js.php"></script>
        <link href="/_asset/css/main.css" type="text/css" rel="stylesheet">

        <title><?php echo $page_title; ?></title>
    </head>
    <body>
        <div id="system-message"></div>
        <h1>&nbsp;</h1>
        <div id="main-section">
            <?php include $view_path; ?>
        </div>
        <script src="/_asset/css/bootstrap/js/bootstrap.min.js"></script>
    </body>
</html>