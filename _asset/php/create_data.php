<?php
include $_SERVER['DOCUMENT_ROOT'].'/_config/system_config.inc';
$db_obj = new DatabaseAccess();

// generate disc data: 5000
$genre = rand(1, 19);
$artist_id = rand(1, 1000);
$disc_sql = "INSERT INTO `disc` (`genre`, `artist_id`) VALUES ($genre, $artist_id)";
for ($disc_id = 2; $disc_id <= 5000; $i++) {
    $genre = rand(1, 19);
    $artist_id = rand(1, 1000);
    $disc_sql .= ", (".rand(1, 19).", ".rand(1, 1000).")";

    $song_number = rand(1, 12);
    $song_sql = "INSERT INTO `song` (`genre`, `artist_id`, `disc_id`) VALUES ($genre, $artist_id, $disc_id)";
}
$db_obj->query($disc_sql);

// generate single song data: 10000
$genre = rand(1, 19);
$artist_id = rand(1, 1000);
$song_sql = "INSERT INTO `song` (`genre`, `artist_id`, `disc_id`) VALUES ($genre, $artist_id, 0)";
for ($i = 2; $i <= 8000; $i++) {
    $song_sql .= ", (".rand(1, 19).", ".rand(1, 1000).", ".rand(1, 5000).")";
}
$db_obj->query($song_sql);
?>