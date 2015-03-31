<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <link rel="icon" href="/_asset/img/ico.ico">

        <script src="/_asset/js/jquery/jquery-1.7.2.min.js"></script>
        <script src="/_asset/js/jquery-form/jquery.form.js"></script>
        <script src="/_asset/js/jquery-ui/js/jquery-ui-1.10.2.custom.min.js"></script>
        <script src="/_asset/js/konami/jquery.konami.js"></script>
        <script src="/_asset/js/pjax/pjax.js"></script>
        <script src="/_asset/js/masonry/jquery.masonry.min.js"></script>

        <link href="/_asset/css/bootstrap/css/bootstrap-responsive.min.css" type="text/css" rel="stylesheet"></link>
        <link href="/_asset/css/bootstrap/css/bootstrap.min.css" type="text/css" rel="stylesheet"></link>
        <!-- <link rel="stylesheet" href="/_asset/css/bootstrap/3.3.4/css/bootstrap.min.css"></link> -->
        <link href="/_asset/js/jquery-ui/css/dark-hive/jquery-ui-1.10.2.custom.min.css" type="text/css" rel="stylesheet"></link>

        <script src="/_asset/js/main.js.php"></script>
        <link rel="stylesheet" href="/_asset/css/main.css"></link>

        <title><?php echo $page_title; ?></title>
    </head>
    <body>
        <div id="system-message"></div>
        <h1>&nbsp;</h1>
        <h4>&nbsp;</h4>
        <div id="main-section">
            <?php 
            include $view_path; 
            ?>
        </div>
        <footer class="footer" style="text-align:center">
            <h1>&nbsp;</h1>
            <div id="footer-signature">
                <div id="footer-signature-text"> BigData YB102 Team1 &copy; 2015 </div>
            </div>
        </footer>
        <script src="/_asset/css/bootstrap/js/bootstrap.min.js"></script>
    </body>
</html>