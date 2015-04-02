<?php
SiteHelper::getNavBar($url);
SiteHelper::getBackyardBreadcrumbs($url);
$map = SiteHelper::getBackyardMap();
?>
<div id="item-container">
    <?php
    foreach ($map as $category => $category_content) {
    ?>
    <table class="box table table-bordered backyard-table">
        <thead>
            <tr>
                <th><?php echo $category; ?></th>
            </tr>
        </thead>
        <tbody>
            <?php 
            foreach ($category_content as $title => $url) {
            ?>
            <tr>
                <td><a href="<?php echo $url; ?>"><?php echo $title; ?></a></td>
            </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
    <?php
    }
    ?>
</div>
<script>
$(document).ready(function() {
    $('#item-container').masonry({
        itemSelector: '.box',
        columnWidth: 100
    });
});
</script>