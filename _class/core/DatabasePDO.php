<?php
class DatabasePDO
{

   private $link;

   public function __construct()
   {

      include DB_CONFIG_FILE;

      try {

         $this->link = new PDO(
                          'mysql:dbname='.$database_name.';host='.$database_host,
                          $database_user,
                          $database_password
                       );

      } catch (PDOException $e) {

         echo $e->getMessage();

      }

      $this->link->query("SET time_zone='+8:00'");
      $this->link->query("SET NAMES UTF8");

   }// end function __construct

   public function insert($sql, $param=array())
   {

      $statement = $this->link->prepare($sql);
      $query_result = $statement->execute($param);

      if (!$query_result) {

         echo '<h2>'.get_class($this).'</h2>';
         echo '<pre>';
         var_dump($this->link->errorInfo());
         echo '</pre>';
         exit;

      }// end if (!$query_result)

      return $this->link->lastInsertId();

   }// end function insert

   public function select($sql, $param=array())
   {

      $statement = $this->link->prepare($sql);
      $query_result = $statement->execute($param);

      if (!$query_result) {

         echo '<h2>'.get_class($this).'</h2>';
         echo '<pre>';
         var_dump($this->link->errorInfo());
         echo '</pre>';
         exit;

      }// end if (!$query_result)

      return $statement->fetchAll();

   }// end function select

   public function update($sql, $param=array())
   {

      $statement = $this->link->prepare($sql);
      $query_result = $statement->execute($param);

      if (!$query_result) {

         echo '<h2>'.get_class($this).'</h2>';
         echo '<pre>';
         var_dump($this->link->errorInfo());
         echo '</pre>';
         exit;

      }// end if (!$query_result)

      return $statement->rowCount();

   }// end function update

   public function delete($sql, $param=array())
   {

      $statement = $this->link->prepare($sql);
      $query_result = $statement->execute($param);

      if (!$query_result) {

         echo '<h2>'.get_class($this).'</h2>';
         echo '<pre>';
         var_dump($this->link->errorInfo());
         echo '</pre>';
         exit;

      }// end if (!$query_result)

      return $statement->rowCount();

   }// end function delete

}// end class DatabasePDO
?>