<?php

class Model
{
  private $dbHost = "localhost";
  private $dbUser = "root";
  private $dbPass = "";
  private $dbName = "hr";
  private $connection;
  private $tabelName;
  public $result;
  private $columnsName;

  /** 
   * @param string $_tabelName => take the table we wanna create obj to deal with
   * 
   * the constractor is making connection to the database and save it to the
   * $connection var
   */
  function __construct($_tabelName)
  {
    $this->tabelName = $_tabelName;
    $this->connection = new mysqli($this->dbHost, $this->dbUser, $this->dbPass, $this->dbName);
    if ($this->connection->connect_error) die("Connection failed: " . $this->connection->connect_error);
  }

  /**
   * the destructor is closing the connection after the object is destroyed
   */
  function __destruct()
  {
    $this->connection->close();
  }

  /**
   * @param int $_limit => the limit of record wanna to get if left it gets back all records
   * 
   * get the data from the database and save it to the $result var as assoc array
   */
  function get($_limit = 0)
  {
    //getting the type of the limit var if it isn't integer die and show error massage
    if (gettype($_limit) == "integer") {
      if ($_limit == 0) $this->result = $this->connection->query("SELECT * FROM $this->tabelName")->fetch_all();
      else if ($_limit > 0) $this->result = $this->connection->query("SELECT * FROM $this->tabelName LIMIT $_limit")->fetch_all();
      else die("please provide a positive number in the the get method on the object controlling the $this->tabelName tabel");
    } else die("please provide a positive number in the the get method on the object controlling the $this->tabelName tabel");
    $this->columnsName = $this->connection->query("SELECT * FROM $this->tabelName")->fetch_assoc();
    //this function is to save the keys of an array in a assoc array with they order
    $this->columnsName = array_keys($this->columnsName);
    return $this;
  }

  /**
   * @param string $_key => take the column name we look for
   * @param string $_value => take the value of what we need to look for
   * 
   * filtering the result of the get query to return any thing that meet the condition 
   */
  function where($_key, $_value)
  {
    /**
     * search for the key we passed in columnsName array and reassign the passed key
     * to the index of this key in the columnsName array
     */
    foreach ($this->columnsName as $key => $value) {
      if ($_key == $value) {
        $_key = $key;
        break;
      }
    }
    //flag to see if the record will be deleted or kept
    $founded = false;
    $recordCounter = 0;
    /**
     * loop over the result set and when find a record that has the value and the key
     * same as the passed kept it if not remove it
     */
    foreach ($this->result as $record) {
      foreach ($record as $key => $value) {
        if ($_key == $key and $_value == $value)
          $founded = true;
      }
      //fix bug if the array has only 1 element
      if (!$founded and count($this->result) == 1) $this->result = [];
      if (!$founded) unset($this->result[$recordCounter]);
      $recordCounter++;
      $founded = false;
    }
    return $this;
  }

  /**
   * @param array $_data => the assoc array holds the data we wanna insert into the database
   * @param array $_unique => an array of feilds that must be unique to prevent duplication
   * 
   * add a record to the database
   */
  function set($_data, $_unique = [])
  {
    $columns = [];
    $values = [];
    //separate the name of the columns and the values of it from the assoc array $_data
    foreach ($_data as $key => $value) {
      array_push($columns, $key);
      array_push($values, "'" . $value . "'");
    }
    //check if unique fildes provided to check befor insert
    if (count($_unique) == 0)
      $this->connection->query("INSERT INTO $this->tabelName (" . implode(", ", $columns) . ") VALUES (" . implode(", ", $values) . ")");
    else {
      $sql = "SELECT `" . implode("`, `", $_unique) . "` FROM $this->tabelName WHERE ";
      $counter = 0;
      foreach ($_unique as $colName) {
        if ($counter > 0) $sql .= " AND ";
        $value = $_data[$colName];
        $sql .= "`$colName` = '$value' ";
        $counter++;
      }
      if ($this->connection->query($sql)->num_rows > 0)
        die("there is a record with this unique value");
      else
        $this->connection->query("INSERT INTO $this->tabelName (" . implode(", ", $columns) . ") VALUES (" . implode(", ", $values) . ")");
    }
  }
}

$users = new Model("users");
// $users->get()->where('username', 'eslam')->where('password', 'eslam');
// $users->set(["id" => 12, "firstName" => "eslam", "lastName" => "magdy", "username" => "eslam3400"], ["username", "lastName"]);
