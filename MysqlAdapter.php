<?php
//require 'DB_config.php';
class MysqlAdapter{

    //store configuration
    protected $_config = array();
    protected $link;//store connection
    protected $result;//store retrieve result in db query
    public function __construct(array $config)
    {
        if(count($config) !== 4){
            throw new InvalidArgumentException("invalid number of connecting parameters");
        }
        $this->_config = $config;
    }
    //connect to db

    public function connect(){
        //Singletone connect only once
        if($this->link === null){
            list($host, $user, $password, $database) = $this->_config;
            if(!$this->link = mysqli_connect($host, $user, $password, $database)){
                throw new RuntimeException('Error connecting with server : ' . mysqli_connect_error());
                //echo "not connected";
               // mysqli_connect_error();
            }
            unset($host, $user, $password, $database);

        }
       // echo 'connected';
        return $this->link;
    }
    public function query($query){
        //check not empty query
        if(!is_string($query) || empty($query)){
            throw new InvalidArgumentException('The specified query is not valid');
        }
        //Lazy connect to mysql connection when query executed
        $this->connect();
        if (!$this->result = mysqli_query($this->link, $query)){
            throw new RuntimeException('Error Executing the specified query' . $query . mysqli_error($this->link));
          // mysqli_error($this->link);
        }
        return $this->result;

    }

    public function select($table, $where = '', $fields = '*', $order = '', $limit = null, $offset = null){
        $query = 'select'  . $fields . ' ' . $table
            . (($where) ?  'WHERE' . $where  :  '')
            . (($limit) ? 'LIMIT' . $limit : '')
            . (($limit && $offset) ? 'OFFSET' .  $offset :  '')
            . (($order)  ? 'ORDER BY' . $order  :  '');
             $this->query($query);
             return $this->countRows();


    }
    public function insert($table, array $data){
        $fields = implode(',', array_keys($data));
        $values = implode(',', array_map(array($this,'quoteValue'), array_values($data)));
        $query = "INSERT INTO " . $table . '(' . $fields . ')' . ' VALUES (' . $values . ')';
        $this->query($query);
        return $this->getInsertedId();


    }
    public function update($table, array $data, $where = ''){
        $set = array();
        foreach ($data as $field => $value){
            $set[] = $field . '=' . $this->quoteValue($value);
        }
        $set = implode(',', $set);
        $query = 'UPDATE' . $table . 'SET' . $set . (($where) ? 'WHERE' . $where : '');
        $this->query($query);
        return $this->getAffectedRows();


    }
    public function delete($table, $where = ''){
        $query = 'DELETE FROM' . $table . (($where) ? 'WHERE' . $where : '');
        $this->query($query);
        return $this->getAffectedRows();

    }


    // method escape to filter date and take each element make filter it by arr map
    public function quoteValue($value){
        $this->connect();
        if($value === null){
            $value = 'NULL';
        }else if (!is_numeric($value)){
            $value = "'" . mysqli_real_escape_string($this->link,$value) . "'";

    }
        return $value;

}
// function fetch on a  single row  from current result set as (associative array)
  public function fetch(){
        if($this->result !== null){
            if($row = mysqli_fetch_array($this->result, MYSQLI_ASSOC) === false){
               $this->freeResult();
            }
            return $row;

        }
        return false;
 }

    // function fetch all row  from current result set as (associative array)
    public function fetchAll(){
        if($this->result !== null){
            if($all = mysqli_fetch_all($this->result, MYSQLI_ASSOC) === false){
                $this->freeResult();
            }
            return $all;

        }
        return false;
    }
    public function getInsertedId(){
        return $this->link !== null ? mysqli_insert_id($this->link) : null;
    }
    // count rows
    public function countRows(){
        return $this->result !== null ? mysqli_num_rows($this->result) : 0;
    }
    // numb affected rows
    public function getAffectedRows(){
       return $this->link !== null ? mysqli_affected_rows($this->link) : 0;
    }

    // free result
    public function freeResult(){
        if($this->result === null){
            return false;
        }
        mysqli_free_result($this->result);
        return true;
    }
    // close database connection
    public function disconnect(){
        if ($this->link === null){
            return false;
        }
        mysqli_close($this->link);
        $this->link = null;
         return true;

    }
 // close db connection automatically when instance of class destroyed
    public function __destruct()
    {
        $this->disconnect();
    }


}
