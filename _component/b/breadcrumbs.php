<ul class="breadcrumb fwb">
    <li><a href="/b/index.php" class="main-pjax">後台首頁</a></li>
    <?php
    foreach ($map as $category => $category_content) {
        foreach ($category_content as $title =>$instance_url) {
            if ($instance_url == $url) {
    ?>
    <li><span class="divider">&raquo;</span> <?php echo $category." - ".$title; ?></li>
    <?php
            }
        }
    }
    ?>
</ul>