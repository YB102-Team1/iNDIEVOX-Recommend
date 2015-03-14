<?php
include $_SERVER['DOCUMENT_ROOT'].'/_config/system_config.inc';
$db_obj = new DatabaseAccess();
$tables = array('buy_disc_record', 'buy_song_record', 'disc', 'favorite', 'song');

echo "<pre>";
$command = escapeshellcmd("python ".$_SERVER['DOCUMENT_ROOT']."/_asset/python/split-sql.py");
$output = shell_exec($command);
echo "<strong>[$command]:</strong>\n$output\n";

foreach (glob(DATA_SQL_ROOT.'/*_structure.sql') as $sql_path) {
    $sql = file_get_contents($sql_path);
    $create_result = $db_obj->query($sql);
    // var_dump($create_result);
    echo "Table ".str_replace('_structure.sql', '', str_replace(DATA_SQL_ROOT.'/', '', $sql_path))." created.\n";
}

echo "\n";

foreach ($tables as $table_name) {
    echo "Importing data of $table_name...\n";
    foreach (glob(DATA_SQL_ROOT.'/'.$table_name.'_*.seg') as $sql_path) {
        $sql = file_get_contents($sql_path);
        $create_result = $db_obj->query($sql);
        // var_dump($create_result);
    }
    echo "\t".str_replace('.seg', '', str_replace(DATA_SQL_ROOT.'/'.$table_name.'_', '', $sql_path))." segments imported.\n\n";
}

echo "</pre>";
unset($db_obj);
?>