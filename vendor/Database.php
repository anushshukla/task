<?php

class Database {
  private $_driver = 'mysql';
  private $_host = 'localhost';
  private $_port = 3306;
  private $_user = 'root';
  private $_password = null;
  private $_database = 'test';
  protected $connection;

  public function __construct($host,$port,$user,$password,$database)
  {
    $this->_host = $host;
    $this->_port = $port;
    $this->_user = $user;
    $this->_password = $password;
    $this->_database = $database;
    $this->connect();
    }

  protected function connect()
  {
    $this->connection = new PDO($this->_driver . ':host='.$this->_host.';port='.$this->_port.';dbname=' . $this->_database, $this->_user, $this->_password);;
  }

  protected function query()
  {
    $this->connection->query($this->query);
  }
}