<?php

namespace App\Libraries;

use App\Libraries\Database;

class Model
{
  protected string $databaseTable;

  protected string $primaryKey = 'id';

  protected array $fillable = [];

  protected string $query;

  protected array $binds = [];
  
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

  public function join(string $table, string $column1, string $column2, string $joinType = 'INNER')
  {
    $this->query .= ' ' . $joinType . ' JOIN ' . $table . ' ON ' . $column1 . ' = ' . $column2;
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

  public function orWhere(...$wheres)
  {
    $this->query .= ' OR ';
    foreach ($wheres as $where) {
      $this->query .= $where . ' OR ';
    }
    $this->query = substr($this->query, 0, -3);
    return $this;
  }

  public function whereRaw(string $where, array $binds = [])
  {
    $this->query .= ' WHERE ' . $where;
    $this->binds = $binds;
    return $this;
  }

  public function orWhereRaw(string $where, array $binds = [])
  {
    $this->query .= ' OR ' . $where;
    $this->binds = $binds;
    return $this;
  }

  public function having(...$havings)
  {
    $this->query .= ' HAVING ';
    foreach ($havings as $having) {
      $this->query .= $having . ' AND ';
    }
    $this->query = substr($this->query, 0, -4);
    return $this;
  }

  public function orHaving(...$havings)
  {
    $this->query .= ' OR ';
    foreach ($havings as $having) {
      $this->query .= $having . ' OR ';
    }
    $this->query = substr($this->query, 0, -3);
    return $this;
  }

  public function havingRaw(string $having, array $binds = [])
  {
    $this->query .= ' HAVING ' . $having;
    $this->binds = $binds;
    return $this;
  }


  public function orHavingRaw(string $having, array $binds = [])
  {
    $this->query .= ' OR ' . $having;
    $this->binds = $binds;
    return $this;
  }


  // Safe to use with user input
  public function orderBy($orderBy, $order)
  {
    $this->query .= ' ORDER BY ' . $orderBy . ' ' . $order;
    return $this;
  }

  public function groupBy(...$columns)
  {
    $this->query .= ' GROUP BY ';
    foreach ($columns as $column) {
      $this->query .= $column . ', ';
    }
    $this->query = substr($this->query, 0, -2);
    return $this;
  }

  public function limit($limit)
  {
    $this->query .= ' LIMIT ' . $limit;
    return $this;
  }


  public function get()
  {
    $this->db->query($this->query, $this->binds);
    if(!empty($this->binds)) {
      foreach ($this->binds as $key => $value) {
        $this->db->bind($key, $value);
      }
    } else {
      return $this->db->resultSet();
    }
  }

  public function create(array $data)
  {
    foreach ($data as $key => $value) {
      if(!in_array($key, $this->fillable)) {
        die("This column name is not mass assignable or does not exist");
      }
    }
    $this->db->query('INSERT INTO ' . $this->databaseTable . ' (' . implode(', ', array_keys($data)) . ') VALUES (:' . implode(', :', array_keys($data)) . ')');
    foreach ($data as $key => $value) {
      $this->db->bind(':' . $key, $value);
    }
    return $this->db->execute();
  }

  public function update(array $data, $id)
  {
    $placeholders = '';
    foreach ($data as $key => $value) {
      if(!in_array($key, $this->fillable)) {
        die("This column name is not mass assignable or does not exist");
      }
      $placeholders .= $key . ' = :' . $key . ', ';
    }
    $this->db->query('UPDATE ' . $this->databaseTable . ' SET '. $placeholders .'  WHERE ' . $this->primaryKey . ' = ' . $id);
    foreach ($data as $key => $value) {
      $this->db->bind(':' . $key, $value);
    }
    return $this->db->execute();
  }
  

  public function delete(int $id)
  {
    $this->db->query('DELETE FROM ' . $this->databaseTable . ' WHERE ' . $this->primaryKey . ' = :id');
    $this->db->bind(':id', $id);
    return $this->db->execute();
  }
}