<?php
class DatabaseAccess
{

   private $link;

   public function __construct()
   {

      include DB_CONFIG_FILE;

      $this->link = new mysqli($database_host , $database_user, $database_password, $database_name);
      $this->link->query("SET time_zone='+8:00'");
      $this->link->query("SET NAMES UTF8");

   }// end function __construct

   public function getAllTables()
   {

      include DB_CONFIG_FILE;

      $sql = "SHOW TABLES FROM $database_name";
      $query_instance = $this->link->query($sql);
      $table_array = array();

      foreach ($query_instance as $instance_data) {

         array_push($table_array, $instance_data['Tables_in_'.strtolower($database_name)]);

      }// end foreach ($query_instance as $instance_data)

      return $table_array;

   }// end function getAllTables

   public function getTableColumns($table_name)
   {

      $sql = "SHOW COLUMNS FROM $table_name";
      $query_instance = $this->link->query($sql);
      $column_array = array();

      foreach ($query_instance as $instance_data) {

         $column_name = $instance_data['Field'];
         switch ($column_name) {

         case 'id':
         case 'is_deleted':
         case 'create_time':
         case 'modify_time':
         case 'delete_time':
            break;

         default:
            $type = $instance_data['Type'];
            $default_value = "";
            if ($instance_data['Default'] != NULL) {

               $default_value = " DEFAULT '".$instance_data['Default']."'";

            }// end if ($instance_data['Default'] != NULL)
            $column_array[$column_name] = "$type NOT NULL".$default_value;
            break;

         }// switch ($column_name)

      }// end foreach ($query_instance as $instance_data)

      return $column_array;

   }// end function getTableColumns

   public function getTableLastModifyTime($table_name)
   {

      $sql = "SELECT MAX(modify_time) last_time FROM $table_name LIMIT 1";
      $query_instance = $this->link->query($sql);

      foreach ($query_instance as $instance_data) {

         $last_mofity_time = $instance_data['last_time'];

      }// end foreach ($query_instance as $instance_data)

      return StringHelper::dateFormat($last_mofity_time, 0, 19);

   }// end function getTableLastModifyTime

   public function getTableMaxId($table_name)
   {

      $sql = "SELECT MAX(id) max_id FROM $table_name LIMIT 1";
      $query_instance = $this->link->query($sql);

      foreach ($query_instance as $instance_data) {

         $max_id = $instance_data['max_id'];

      }// end foreach ($query_instance as $instance_data)

      return $max_id;

   }// end function getTableMaxId

   public function insert($sql)
   {

      $query = $this->link->query($sql);
      if (!$query) {

         return array("error" => $this->link->error);

      } else {// end if (!$query)

         return $this->link->insert_id;

      }// end if (!$query) else

   }// end function insert

   public function select($sql)
   {

      $query = $this->link->query($sql);
      if (!$query) {

         return array("error" => $this->link->error);

      } else {// end if (!$query)

         return $query;

      }// end if (!$query) else

   }// end function select

   public function update($sql)
   {

      $query = $this->link->query($sql);
      if (!$query) {

         return array("error" => $this->link->error);

      } else { // end if (!$query)

         return $this->link->affected_rows;

      }// end if (!$query) else

   }// end function update

   public function delete($sql)
   {

      $query = $this->link->query($sql);
      if (!$query) {

         return array("error" => $this->link->error);

      } else { // end if (!$query)

         return $this->link->affected_rows;

      }// end if (!$query) else

   }// end function delete

   public function query($sql)
   {

      $query = $this->link->query($sql);
      if (!$query) {

         return array("error" => $this->link->error);

      } else { // end if (!$query)

         return true;

      }// end if (!$query) else

   }// end function query

   public function __destruct()
   {

      $this->link->close();

   }// end function __destruct

}// end of class DatabaseAccess
?>