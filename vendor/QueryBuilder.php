<?php

class QueryBuilder extends Database {
  private $columns;
  private $groupBy;
  private $having;
  private $where;
  private $orderBy;
  private $limit = 100;
  private $offset = 0;

  function __construct() {
    $this->pdo = (new Database)->connection;
    // $this->pdo = new PDO("mysql:host=".HOST.";dbname=".DATABASE,USERNAME,PASSWORD);
    // $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //PDO::ERRMODE_WARNING
    $this->tablename="product";
    $this->input=$_POST;
  }
  
  public function where($column,$value) {
    $args = func_get_args();
    if(count($args) > 3) {
      throw new Exception("Error Processing Request", 1);
    }
    $operator = "=";
    $column = end($args); 
    if(count($args) > 2) {
      $operator = $args[1]; 
    }
    $value = end($args); 
    if(!$this->where) {
      $this->where = ' WHERE';
    } else {
      $this->where .= ' AND';
    }
    $this->where += " $column $operator $value";      
  }

  public function orderBy($by,$order) {

  }

  public function insert()
  {
    $input=['name'=>'required',
        'price'=>'required',
        'discount'=>'required'];
    $sql = "INSERT INTO `$this->tablename` (name,price,discount)";
    $query = $this->pdo->prepare($sql);
    return json_encode($query->execute(array(':name'=>trim($this->input['name']),
                      ':price'=>$this->input['price'],
                      ':discount'=>$this->input['discount'])));
  }

  public function update($id)
  {
    $sql = "UPDATE `$this->tablename`   
          SET `name` = :name,
             `price` = :price, 
             `discount` = :discount 
        WHERE id = :id";
    $query = $this->pdo->prepare($sql);
    $query->bindValue(':id',trim($this->input['id']),PDO::PARAM_STR);
    $query->bindValue(':name',trim($this->input['name']),PDO::PARAM_STR);
    $query->bindValue(':price',$this->input['price'],PDO::PARAM_INT);
    $query->bindValue(':discount',$this->input['discount'],PDO::PARAM_INT);
    return json_encode($query->execute());
  }

  public function delete($id)
      $query = "DELETE FROM `$this->tablename` WHERE `id` =  :id";
      $query = $this->pdo->prepare($query);
      $query->bindValue(':id',$id,PDO::PARAM_STR);
      return json_encode($query->execute());
    }

  public function fire()
  {
    $this->query = 'SELECT * FROM `$this->tablename` LIMIT '. $this->limit . ', '. $this->offset;
    $stmt = $pdo->prepare();
    $stmt->execute();
  }
}