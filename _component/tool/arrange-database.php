<?php
include DB_CONFIG_FILE;

echo '<title>Initialize Database</title>';
echo '<pre>';

$connection = mysqli_connect($database_host, $database_user, $database_password);

// check connection
echo 'Check connection...';
if (mysqli_connect_errno()) {

    echo PHP_EOL."\t=>Failed to connect to MySQL: ".mysqli_connect_error();
    throw new RuntimeException();

}
echo 'ok'.PHP_EOL.PHP_EOL;

// create database
echo 'Check database...';
$database_selected = mysqli_select_db($connection, $database_name);
if (!$database_selected) {

    echo "not exists".PHP_EOL;
    $sql = "CREATE DATABASE $database_name";
    if (!mysqli_query($connection, $sql)) {

        echo "\t=>Error when creating database `$database_name`: ".mysql_error().PHP_EOL;
        exit;

    }
    echo "\tCreate database `$database_name`...done".PHP_EOL.PHP_EOL;

} else {

    echo 'ok'.PHP_EOL.PHP_EOL;

}

// create tables
$db_obj = new DatabaseAccess();
$new_table_array = array();
foreach (glob(TABLE_SQL_ROOT.'/*.sql') as $sql_file) {

    $new_table_array[] = str_replace('.sql', '', str_replace(TABLE_SQL_ROOT.'/', '', $sql_file));

}
foreach ($new_table_array as $table_name) {

    $class_name = str_replace(' ', '', ucwords(str_replace('_', ' ', $table_name)));

    echo 'Create `'.$table_name.'`...';
    if (ModelHelper::importTable($table_name)) {

        echo 'ok'.PHP_EOL;

        // sync class file
        $class_path = CLASS_ROOT.'/model/'.$class_name.'.php';
        if (file_exists($class_path)) {

            echo "\tClass file exists".PHP_EOL;

        } else {

            $column_list = $db_obj->getTableColumns($table_name);

            echo "\tCreate class file...";
            if (ModelHelper::createClassFile($table_name, $column_list)) {

                echo 'ok'.PHP_EOL;

            } else {

                echo 'fail'.PHP_EOL;

            }

        }

        // sync class god file
        $class_path = CLASS_ROOT.'/model/'.$class_name.'God.php';

        echo "\tCreate class god file...";
        if (file_exists($class_path)) {

            echo "\tClass god file exists".PHP_EOL;

        } else {

            $column_list = $db_obj->getTableColumns($table_name);
            if (ModelHelper::createClassGodFile($table_name)) {

                echo 'ok'.PHP_EOL;

            } else {

                echo 'fail'.PHP_EOL;

            }

        }

        // sync table data
        $sql_path = DATA_SQL_ROOT.'/'.$table_name.'.sql';
        if (file_exists($sql_path)) {

            echo "\tTruncate `$table_name` data...";
            $sql = "TRUNCATE $table_name";
            if ($db_obj->query($sql)) {

                echo 'ok'.PHP_EOL;

                echo "\t\tImport `$table_name` data...";
                $sql = file_get_contents($sql_path);
                if ($db_obj->query($sql)) {

                    echo 'ok'.PHP_EOL;

                } else {

                    echo 'fail'.PHP_EOL;

                }

            } else {

                echo 'fail'.PHP_EOL;

            }

        } else {

            echo "\tNo data to import".PHP_EOL;

        }

    } else {

        echo 'fail'.PHP_EOL;

    }
    echo PHP_EOL;

}

echo '</pre>';
?>