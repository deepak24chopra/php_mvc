<?php

class DB {
    private static $_instance = null;
    private $_pdo,$_query,$_error=false,$_result,$_count=0,$_last_insert_id=null;

    private function __construct() {
      try {
        $this->_pdo = new PDO('mysql:host=' . DB_HOST .';dbname=' . DB_NAME,DB_USER,DB_PASSWORD);
      } catch (PDOException $e) {
        die($e->getMessage());
      }

    }

    public static function get_instance() {
      if (!isset(self::$_instance)) {
        self::$_instance = new DB();
      }
      return self::$_instance;
    }

    public function query($sql, $params = []) {
      $this->_error = false;
      if ($this->_query = $this->_pdo->prepare($sql)) {
        $x = 1;
        if (count($params)) {
          foreach($params as $param) {
            $this->_query->bindValue($x,$param);
            $x++;
          }
        }
        if ($this->_query->execute()) {
          $this->_result = $this->_query->fetchALL(PDO::FETCH_OBJ);
          $this->_count = $this->_query->rowCount();
          $this->_last_insert_id = $this->_pdo->lastInsertId();
        } else {
          $this->_error = true;
        }
      }
      return $this;
    }

    protected function _read($table,$params=[]) {
      $condition_string = '';
      $bind = [];
      $order = '';
      $limit = '';

      //conditions
      if (isset($params['conditions'])) {
        if (is_array($params['conditions'])) {
          foreach ($params['conditions'] as $condition) {
            $condition_string .= ' ' . $condition . ' AND';
          }
          $condition_string = trim($condition_string);
          $condition_string = rtrim($condition_string, ' AND');
        } else {
          $condition_string = $params['conditions'];
        }
        if($condition_string != '') {
          $condition_string = ' where ' . $condition_string;
        }
      }
      //bind value
      if (array_key_exists('bind', $params)) {
        $bind = $params['bind'];
      }
      //order
      if (array_key_exists('order', $params)) {
        $order = ' order by ' . $params['order'];
      }
      //limit
      if (array_key_exists('limit', $params)) {
        $limit  = ' limit ' . $params['limit'];
      }

      $sql = "select * from {$table}{$condition_string}{$order}{$limit}";
      if ($this->query($sql, $bind)) {
        if (!count($this->_result)) return false;
        return true;
      }
      return false;
    }

    public function find($table,$params=[]) {
      if ($this->_read($table, $params)) {
        return $this->results();
      }
      return false;
    }

    public function find_first($table,$params=[]) {
      if ($this->_read($table,$params)) {
        return $this->first();
      }
      return false;
    }

    public function insert($table, $fields = []) {
      $field_string = '';
      $value_string = '';
      $values = [];

      foreach ($fields as $field => $value) {
        $field_string .= '`' . $field . '`,';
        $value_string .='?,';
        $values[] = $value; 
      }

      $field_string = rtrim($field_string, ',');
      $value_string = rtrim($value_string, ',');

      $sql = "INSERT INTO {$table} ({$field_string}) VALUES({$value_string})";
      if (!$this->query($sql,$values)->error()) {
        return true;
      }
      return false;
    }

    public function update($table,$id,$fields=[]) {
      $field_string = '';
      $values = [];
      foreach ($fields as $field => $value) {
        $field_string .= ' ' . $field . ' = ?, ';
        $values[] = $value; 
      }
      $field_string = trim($field_string);
      $field_string = rtrim($field_string, ',');
      $sql = "update {$table} set {$field_string} where id = {$id}";
      if(!$this->query($sql,$values)->error()) {
        return true;
      }
      return false;
    }

    public function delete($table,$id) {
      $sql = "delete from {$table} where id={$id}";
      if(!$this->query($sql)->error()) {
        return true;
      }
      return false;
    }

    public function results() {
      return $this->_result;
    }

    public function first() {
      return (!empty($this->_result)) ? $this->_result[0] : [];
    }

    public function count() {
      return $this->_count;
    }

    public function last_id() {
      return $this->_last_insert_id;
    }

    public function get_columns($table) {
      return $this->query("Show columns from {$table}")->results();
    }

    public function error() {
      return $this->_error;
    }


}
