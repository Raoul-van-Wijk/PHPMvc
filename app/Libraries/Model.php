<?php

namespace App\Libraries;

use App\Libraries\Database;

class Model
{
  protected string $databaseTable;

  protected string $primaryKey = 'id';

  protected string $query;
  
  public function __construct(string $dbName = null)
  {
    if(!is_null($dbName)) {
      $this->db = new Database($dbName);
    } else {
      $this->db = new Database;
    }
  }

  public function All()
  {
    $this->db->query('SELECT * FROM ' . $this->databaseTable);
    return $this->db->resultSet();
  }

  public function find($id)
  {
    $this->db->query('SELECT * FROM ' . $this->databaseTable . ' WHERE '. $this->primaryKey .' = :id');
    $this->db->bind(':id', $id);
    return $this->db->single();
  }

  public function search(array $wheres = [], array | string $orderBy = [], string $order, int $limit = 0) {
    $query = 'SELECT * FROM ' . $this->databaseTable . ' WHERE ';
    foreach ($wheres as $where) {
      $query .= $where . ' AND ';
    }
    $query = substr($query, 0, -4);

    if(isset($orderBy)) {
        $query .= 'ORDER BY' . $orderBy;
    }

    if(isset($order)) {
      $query .= ' ' . $order;
    }

    if(isset($limit)) {
      $query .= ' LIMIT ' . $limit;
    }
    return $this->db->resultSet();

  }

  public function select(...$columns)
  {
    $this->query = 'SELECT ';
    if(count($columns) > 0) {
      foreach ($columns as $column) {
        $this->query .= $column . ', ';
      }
      $this->query = substr($this->query, 0, -2);
    } else {
      $this->query .= '*';
    }
    $this->query .= ' FROM ' . $this->databaseTable;
    return $this;
  }

  public function where(...$wheres)
  {
    $this->query .= ' WHERE ';
    foreach ($wheres as $where) {
      $this->query .= $where . ' AND ';
    }
    $this->query = substr($this->query, 0, -4);
    return $this;
  }

  public function orderBy($orderBy, $order)
  {
    $this->query .= ' ORDER BY ' . $orderBy . ' ' . $order;
    return $this;
  }

  public function limit($limit)
  {
    $this->query .= ' LIMIT ' . $limit;
    return $this;
  }


  public function get()
  {
    $this->db->query($this->query);
    return $this->db->resultSet();
  }

  
}