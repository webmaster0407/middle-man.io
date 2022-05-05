<?php
namespace App;

/**
 * SQLite connnection
 */
class SqlConnection {
    /**
     * PDO instance
     * @var type 
     */
    private $conn;
    

    /**
     * return in instance of the mysqli object that connects to the mysql database
     * @return \mysqli
     */
    public function connect() {
        if ($this->conn == null) {
            // create database

            // $this->conn = new \mysqli(Config::SERVER_NAME, Config::USER_NAME, Config::PASSWORD);
            // if ($this->conn->connect_error) {
            //     die("Connection failed: " . $this->conn->connect_error);
            // }
            
            // // create database 
            // $sql = 'CREATE DATABASE ' . Config::DATABASE_NAME ;
            // if (mysqli_query($this->conn, $sql)) {
            //     echo 'Database created successfully';
            // } else {
            //     echo "Error creating database: " . mysqli_error($this->conn);
            // }


            // connect to database
            $this->conn = new \mysqli(Config::SERVER_NAME, Config::USER_NAME, Config::PASSWORD, Config::DATABASE_NAME);
            if ($this->conn->connect_error) {
                die("Connection failed: " . $this->conn->connect_error);
            }
        }
        return $this->conn;
    }
}