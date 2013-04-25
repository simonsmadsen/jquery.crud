<?php
class Crud {

  private $_DBH;
  private $_table;

  function __construct($host, $dbname, $username, $password) {
    try {
      $this->_DBH = new PDO("mysql:host=$host;dbname=$dbname", $username, $password
                            , array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
      if( ! isset($_POST)) {
        throw new Exception('CRUD : The POST is not even set');
      }
      $this->_route($_POST);
    } catch ( Exception $e) {
      die($e->getMessage());
    }
  }

  private function _route($route) { 
      if(empty($route['table'])) {
        throw new Exception('CRUD: You have not even set a table');
      }
      $this->_table = $route['table'];
      $where_is_set = ( ! isset($route['where'])) ? false : true;
      
      switch($route) {
        case ( ! isset($route['table'])):
        case ( ! isset($route['type'])):
          throw new Exception('CRUD : Missing table and type.');
          
        case 'create':
          $this->create($route['type']);
          break;
        
        case 'read':
          $this->read($route['type']);
          break;
        
        case 'update':
          if( ! $where_is_set) {
            throw new Exception('CRUD: You tried to update but set no where');
          }
          $this->update($route['type'], $route['where']);
          break;
        
        case 'delete':
          if( ! $where_is_set) {
            throw new Exception('CRUD: You tried to delete but set no where');
          }
          $this->delete($route['where']);
          break;
          
        default:
          throw new Exception('CRUD: You done something wrong. ');
          break;
      }
  }
  
  protected function create($data) {
    $columns = array();
    foreach ($data as $key => $value) {
      $columns[] = $key;
    }
    foreach ($columns as $column) {
      $prepare[] = ':' . $column;
    }
    $sql = "
          INSERT INTO $this->_table (" . implode(',', $columns) . ") VALUES (" . implode(',', $prepare) . ");
      ";
    try {
      $STH = $this->_DBH->prepare($sql);
      foreach ($data as $column => $value) {
        $STH->bindParam("':{$column}'", $value);
      }
      $STH->execute($data);
    } catch (Exception $e) {
      throw $e;
    }
    echo $this->_DBH->lastInsertId();
  }

  protected function read($sql) {
    try {
      $STH = $this->_DBH->query($sql);
      $STH->setFetchMode(PDO::FETCH_ASSOC);
      $result = array();
      while ($row = $STH->fetch()) {
        $result[] = $row;
      }
    } catch (Exception $e) {
      throw $e;
    }
    echo json_encode($result);
  }

  protected function update($data, $where) {
    $keys = array();
    foreach ($data as $key => $value) {
      $keys[] = $key . '="' . $value . '"';
    }

    $sql = "UPDATE " . $this->_table . " SET " . implode(',', $keys) . " " . $where;
    try {
      $sth = $this->_DBH->prepare($sql);
    } catch (Exception $e) {
      throw $e;
    }
    echo $sth->execute();
  }

  protected function delete($where) {
    $sql =
            "DELETE FROM $this->_table
              WHERE " . $where;
    try {
      
    } catch (Exception $e) {
      $sth = $this->_DBH->prepare($sql);
      echo $sth->execute(); 
    }
  }
}
