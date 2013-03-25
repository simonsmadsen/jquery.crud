<?php
// settings
$host = 'localhost';
$dabase = 'test';
$username = 'root';
$password = '';
$table = $_POST['table'];
$crud = new Crud($host,$dabase,$username,$password,$table);

// check type
    if($_POST['type'] === 'create'){
        $crud->create($_POST['data']);
    }
    if($_POST['type'] === 'read'){
        $crud->read($_POST['data']);
    }
    if($_POST['type'] === 'update'){
        $crud->update($_POST['data'],$_POST['where']);
    }
    if($_POST['type'] === 'delete'){
        $crud->delete($_POST['data']);
    }
 
  

class Crud{
    
   private $DBH; 
   private $table;
   function __construct($host,$dbname,$user,$password,$table){
        $this->DBH = new PDO("mysql:host=$host;dbname=$dbname", $user, $password); 
        $this->table = $table;
   }
   
   public function create($data){
      $columns = array();
      
      foreach($data as $key => $value){
          $columns[] = $key;      
      } 
      
      foreach($columns as $column){
	  $prepare[] = ':'.$column;
      }
        
      $sql = "
          INSERT INTO $this->table (".implode(',', $columns).") VALUES (".implode(',', $prepare).");
      ";
      
      $STH = $this->DBH->prepare($sql);
      
      foreach($data as $column => $value){
           $STH->bindParam("':{$column}'", $value);
      }
      
      $STH->execute($data);
      echo $this->DBH->lastInsertId(); 
   }
   
   public function read($sql){
        $STH = $this->DBH->query($sql);
        $STH->setFetchMode(PDO::FETCH_ASSOC);
        $result = array();
         while($row = $STH->fetch()){
                    $result[] = $row;
            }
        echo json_encode($result);
    }
   
   public function update($data,$where){
        $keys = array();
       foreach ($data as $key => $value){
            $keys[] = $key.'="'.$value.'"';
        }
         
        $sql = "UPDATE ".$this->table." SET ".implode(',', $keys)." ".$where;
        echo $sql;
        $sth = $this->DBH->prepare($sql);
        $sth->execute();
   }
   
   public function delete(){
       
   }
    
    
    
}