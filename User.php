<?php
require 'DB_config.php';
require 'MysqlAdapter.php';
class User extends MysqlAdapter
{
    private $table = 'users';
    public function __construct()
    { // configuration file
        global $config;
        //calling the parent constructor
        parent::__construct($config);
    }
    // get all users
    public function getUsers(){
        $this->select($this->table);
        return $this->fetchAll();
    }
    // get user with id
    public function getUser($user_id){
        $this->select($this->table, 'id = ' . $user_id);
        return $this->fetch();
    }
    public function addUser($user_data){
        return $this->insert($this->table, $user_data);
    }
    public function updateUser($user_data, $user_id){
        return $this->update($this->table, $user_data,'id = ' . $user_id);
    }
    public function deleteUser($user_id){
        return $this->delete($this->table, 'id  =' . $user_id);
    }
    public function searchUsers($keyword){
        $this->select($this->table,"name Like '%$keyword%' OR email LIKE '%$keyword%'");
        return $this->fetchAll();
    }

}

