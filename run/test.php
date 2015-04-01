<pre>
<?php
$user_id = 122034;
$item_type = 'disc';
$item_id = 5784;

include $_SERVER['DOCUMENT_ROOT'].'/_config/system_config.inc';
switch ($item_type) {
case 'song':
    $item_obj = new Song($item_id);
    $result = $item_obj->getPromoteDiscs($user_id);
    break;

default:
    $item_obj = new Disc($item_id);
    $result = $item_obj->getPromoteDiscs($user_id);
    break;
}

echo "user: ".$user_id."\n";
echo "type: ".$item_type."\n";
echo "id: ".$item_id."\n";
echo "title: ".$item_obj->title."\n";
echo "genre: ".$item_obj->genre."\n";
echo "artist_id: ".$item_obj->artist_id."\n\n";

echo "==================================================================\n";
echo "User $user_id recommend top 10 result on $item_type $item_id page:\n";
echo "==================================================================\n";

$c = 1;
foreach ($result as $key => $value) {
    switch ($item_type) {
    case 'song':
        $item_obj = new Song($key);
        break;

    default:
        $item_obj = new Disc($key);
        break;
    }
    echo "Recommend  item $c:\n";
    echo "id: $key($value)\n";
    echo "title: ".$item_obj->title."\n";
    echo "genre: ".$item_obj->genre."\n";
    echo "artist_id: ".$item_obj->artist_id."\n";
    echo "\n";
    echo "---------------------------------------------\n";
    unset($item_obj);
    $c++;
}
?>
</pre>