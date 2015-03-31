<div class="navbar navbar-inverse navbar-fixed-top">
    <div class="navbar-inner">
        <ul class="nav">
            <li><a href="/" class="main-pjax img-link"><img src="/_asset/img/indievoice.png" /></a></li>
            <?php
            function parseNavArray($nav_array, $page_url, $level = 1) {

                $output = "";

                foreach ($nav_array as $nav_title => $nav_url) {

                    if (is_array($nav_url)) {

                        $nav_class = '';
                        if (in_array_r($page_url, $nav_url)) {

                            $nav_class = ' active';

                        }// end if (in_array_r($page_url, $nav_url))

                        if ($level == 1) {

                            $output .= '<li class="dropdown'.$nav_class.'">'.
                                           '<a class="dropdown-toggle" data-toggle="dropdown">'.
                                               $nav_title.' <b class="caret"></b>'.
                                           '</a>'.
                                           '<ul class="dropdown-menu">'.
                                               parseNavArray($nav_url, $page_url, $level + 1).
                                           '</ul>'.
                                       '</li>';

                        } else {// end if ($level == 1)

                            $output .= '<li class="dropdown-submenu">'.
                                           '<a>'.$nav_title.'</a>'.
                                           '<ul class="dropdown-menu">'.
                                               parseNavArray($nav_url, $page_url, $level + 1).
                                           '</ul>'.
                                       '</li>';

                        }// end if ($level == 1) else

                    } else if ($nav_url == "divider") {// end if (is_array($nav_url))

                        $output .= '<li class="divider"></li>';

                    } else if ($nav_url == $page_url) {// end if ($nav_url == "divider")

                        if ($level == 1) {

                            $output .= '<li class="active"><a>'.$nav_title.'</a></li>';

                        } else {// end if ($level == 1)

                            $output .= '<li class="disabled"><a>'.$nav_title.'</a></li>';


                        }// end if ($level == 1) else

                    } else {// end if ($nav_url == $page_url)

                        if ($nav_url == "/b/similar_artist.php") {

                            $output .= '<li><a href="'.$nav_url.'">'.$nav_title.'</a></li>';

                        } else {

                            $output .= '<li><a href="'.$nav_url.'" class="main-pjax">'.$nav_title.'</a></li>';

                        }

                    }// end if ($nav_url == $page_url) else

                }// end foreach ($nav_array as $nav_title => $nav_url)

                return $output;

            }// end function parseNavArray

            echo parseNavArray($nav_array, $url);
            ?>
        </ul>
        <ul class="nav pull-right">
            <li style="padding-top: 30px;"><span style="color: #EEEEEE;" class="fwb">Data provided by</span> </li>
            <li>
                <a href="http://www.indievox.com" target="_blank" class="img-link"><img src="/_asset/img/indievox.svg" /></a>
            </li>
        </ul>
    </div>
</div>