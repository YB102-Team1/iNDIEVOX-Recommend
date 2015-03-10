<?php
class ModelHelper
{

    public static function createTable($table_name, $column_array)
    {

        $db_obj = new DatabaseAccess();
        $exist_table_array = $db_obj->getAllTables();

        if (!in_array($table_name, $exist_table_array)) {

            $variable_list = "";

            foreach ($column_array as $column_name => $attribute) {

                switch ($column_name) {

                case 'id':
                case 'is_deleted':
                case 'create_time':
                case 'modify_time':
                case 'delete_time':
                    break;

                default:
                    $variable_list .= '`'.$column_name.'` '.$attribute.', ';
                    break;

                }// end switch ($column_name)

            }// end foreach ($column_array as $column_name => $attribute)

            $sql = 'CREATE TABLE IF NOT EXISTS `'.$table_name.'` ( '.
                       '`id` int(11) unsigned NOT NULL, '.
                       $variable_list.
                       '`is_deleted` tinyint(1) unsigned NOT NULL DEFAULT \'0\', '.
                       '`create_time` datetime NOT NULL, '.
                       '`modify_time` datetime NOT NULL DEFAULT \'0000-00-00 00:00:00\', '.
                       '`delete_time` datetime NOT NULL DEFAULT \'0000-00-00 00:00:00\' '.
                   ') ENGINE=InnoDB DEFAULT CHARSET=utf8;';
            $create_result = $db_obj->query($sql);
            $sql = "ALTER TABLE `$table_name` ADD PRIMARY KEY (`id`);";
            $primary_key_result = $db_obj->query($sql);
            $sql = "ALTER TABLE `$table_name` MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;";
            $auto_increment_result = $db_obj->query($sql);

            unset($db_obj);

            return $create_result && $primary_key_result && $auto_increment_result;

        } else {// end if (!in_array($table_name, $exist_table_array))

            unset($db_obj);

            return false;

        }// end if (!in_array($table_name, $exist_table_array)) else

    }// end function createTable

    public static function createClassFile($table_name, $column_array)
    {

        $class_name = str_replace(' ', '', ucwords(str_replace('_', ' ', $table_name)));
        $class_path = CLASS_ROOT.'/model/'.$class_name.'.php';

        if (!file_exists($class_path)) {

            $class_content = str_replace('Class', $class_name, file_get_contents(CLASS_TEMPLATE_FILE));
            $variable_list = "";

            foreach ($column_array as $column_name => $attribute) {

                switch ($column_name) {

                case 'id':
                case 'is_deleted':
                case 'create_time':
                case 'modify_time':
                case 'delete_time':
                    break;

                default:
                    $variable_list .= "    protected \$$column_name;".PHP_EOL;
                    break;

                }// end switch ($column_name)

            }// end foreach ($column_array as $column_name => $attribute)

            if (!file_exists(CLASS_ROOT.'/model/')) {
                mkdir(CLASS_ROOT.'/model/');
            }

            return file_put_contents($class_path, str_replace('    #variables#'.PHP_EOL, $variable_list, $class_content));

        } else {// end if (!file_exists($class_path))

            return false;

        }// end if (!file_exists($class_path)) else

    }// end function createClassFile

    public static function createClassGodFile($table_name)
    {

        $class_name = str_replace(' ', '', ucwords(str_replace('_', ' ', $table_name)));
        $class_god_path = CLASS_ROOT.'/model/'.$class_name.'God.php';

        if (!file_exists($class_god_path)) {

            if (!file_exists(CLASS_ROOT.'/model/')) {
                mkdir(CLASS_ROOT.'/model/');
            }

            $class_god_content = str_replace('Class', $class_name, file_get_contents(CLASS_GOD_TEMPLATE_FILE));

            return file_put_contents($class_god_path, $class_god_content);

        } else {// end if (!file_exists($class_god_path))

            return false;

        }// end if (!file_exists($class_god_path)) else

    }// end function createClassGodFile

    public static function createActionFile($type_name)
    {

        $action_name = str_replace(' ', '', ucwords(str_replace('-', ' ', $type_name)));
        $action_path = CLASS_ROOT.'/'.$class_name.'Action.php';

        if (!file_exists($action_path)) {

            $action_content = str_replace('Type', $action_name, file_get_contents(ACTION_TEMPLATE_FILE));

            return file_put_contents($action_path, $action_content);

        } else {// end if (!file_exists($action_path))

            return false;

        }// end if (!file_exists($action_path)) else

    }// end function createActionFile

    public static function exportTable($table_name)
    {

        $sql_path = TABLE_SQL_ROOT.'/'.$table_name.'.sql';

        if (!file_exists(SITE_ROOT.'/_asset/sql/')) {
            mkdir(SITE_ROOT.'/_asset/sql/');
        }

        if (!file_exists(TABLE_SQL_ROOT)) {
            mkdir(TABLE_SQL_ROOT);
        }

        $sql_content = str_replace('???', $table_name, file_get_contents(TABLE_SQL_TEMPLATE_FILE));
        $db_obj = new DatabaseAccess();
        $column_array = $db_obj->getTableColumns($table_name);
        $column_list = "";
        foreach ($column_array as $column_name => $attribute) {

            $column_list .= "    `$column_name` $attribute,".PHP_EOL;

        }// end foreach ($column_array as $column_name => $attribute)

        return file_put_contents($sql_path, str_replace('    ...'.PHP_EOL, $column_list, $sql_content));

    }// end function exportTable

    public static function importTable($table_name)
    {

        $sql_path = TABLE_SQL_ROOT.'/'.$table_name.'.sql';

        if (!file_exists($sql_path)) {

            return false;

        } else {// end if (!file_exists($sql_path))

            $db_obj = new DatabaseAccess();
            $sql = file_get_contents($sql_path);
            $create_result = $db_obj->query($sql);
            $sql = "ALTER TABLE `$table_name` ADD PRIMARY KEY (`id`);";
            $primary_key_result = $db_obj->query($sql);
            $sql = "ALTER TABLE `$table_name` MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;";
            $auto_increment_result = $db_obj->query($sql);
            unset($db_obj);

            return $create_result && $primary_key_result && $auto_increment_result;

        }// end if (!file_exists($sql_path)) else

    }// end function importTable

    public static function exportData($table_name)
    {

        $sql_path = DATA_SQL_ROOT.'/'.$table_name.'.sql';
        $god_class_name = str_replace(' ', '', ucwords(str_replace('_', ' ', $table_name))).'God';

        if (!file_exists(SITE_ROOT.'/_asset/sql/')) {
            mkdir(SITE_ROOT.'/_asset/sql/');
        }

        if (!file_exists(DATA_SQL_ROOT)) {
            mkdir(DATA_SQL_ROOT);
        }

        $db_obj = new DatabaseAccess();
        $column_array = $db_obj->getTableColumns($table_name);

        $sql_content = "INSERT INTO `$table_name`".PHP_EOL."(`id`, ";
        foreach ($column_array as $column_name => $attribute) {

            $sql_content .= "`$column_name`, ";

        }// end foreach ($column_array as $column_name => $attribute)
        $sql_content .= "`is_deleted`, `create_time`, `modify_time`, `delete_time`)".PHP_EOL."VALUES".PHP_EOL;

        $class_god_obj = new $god_class_name;
        $all_data = $class_god_obj->getAll();

        foreach ($all_data as $data) {

            $sql_content .= "('".$data['id']."', ";
            foreach ($column_array as $column_name => $attribute) {

                $sql_content .= "'".$data[$column_name]."', ";

            }// end foreach ($column_array as $column_name => $attribute)
            $sql_content .= "'".$data['is_deleted']."', ".
                            "'".$data['create_time']."', ".
                            "'".$data['modify_time']."', ".
                            "'".$data['delete_time']."'".
                            "),".PHP_EOL;

        }// end foreach ($all_data as $data)

        return file_put_contents($sql_path, substr($sql_content, 0, -2));

    }// end function exportData

    public static function syncData($table_name)
    {

        $sql_path = DATA_SQL_ROOT.'/'.$table_name.'.sql';

        if (!file_exists($sql_path)) {

            return false;

        } else {// end if (!file_exists($sql_path))

            $db_obj = new DatabaseAccess();
            $sql = "TRUNCATE $table_name";
            $auto_increment_result = $db_obj->query($sql);
            $sql = file_get_contents($sql_path);
            $import_result = $db_obj->query($sql);
            unset($db_obj);

            return $import_result;

        }// end if (!file_exists($sql_path)) else

    }// end function syncData

}// end class ModelHelper
?>