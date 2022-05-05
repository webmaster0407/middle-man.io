<?php

namespace App;
use App\EncDec as EncDec;

class AttachesModel {

        /**
        * mysqli object
        * @var mysqli
        */
        private $conn;
        private $enc_dec;

        /**
        * connect to the mysql database
        */
        public function __construct($conn) {
                $this->conn = $conn;
                $this->enc_dec = new EncDec();
        }

        /**
        * insert data to attaches table 
        */
        public function insertToAttaches(
                $name,
                $size,
                $type, 
                $content 
            ) {

            // $name = $this->enc_dec->my_encode($name);

            $rootDir = realpath($content);
            $rootDir = str_replace('\\', '/', $rootDir);
            //echo $rootDir; exit;

            //echo $rootDir; exit();

            $file_content = base64_encode(file_get_contents($rootDir));

            $command = "INSERT INTO attaches (
                            name,
                            size,
                            type,
                            content
                        ) VALUES (
                            '" . $name . "',
                            " . $size . ",
                            '" . $type . "',
                            '" . $file_content . "'
                    )";
            if ( $this->conn->query($command) !== TRUE) {
                echo "Error: " . $command . "<br>" . $this->conn->error;
                exit();
            } else {
                $last_id = $this->conn->insert_id;
                return $last_id;
            }
        }

        /**
        * get data from attaches table 
        */
        public function getAttaches() {
            $command = "SELECT * FROM attaches";
            $result = $this->conn->query($command);

            $rltArray = [];

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $rlt_item = [
                        'id'        => $row['id'],
                        // 'name'      => $this->enc_dec->my_decode($row['name']),
                        'name'      => $row['name'],
                        'size'      => $row['size'],
                        'type'      => $row['type'],
                        'content'   => $row['content']
                    ];
                    $rltArray[] = $rlt_item;
                }
            }
            return $rltArray;
        }

        /**
        * get data from attaches table with the given id
        */
        public function getAttache($id) {
            $command = "SELECT * FROM attaches WHERE id = " . $id;
            $result = $this->conn->query($command);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $rlt_item = [
                        'id'        => $row['id'],
                        // 'name'      => $this->enc_dec->my_decode($row['name']),
                        'name'      => $row['name'],
                        'size'      => $row['size'],
                        'type'      => $row['type'],
                        'content'   => $row['content']
                    ];
                    return $rlt_item;
                }
            } else {
                return null;
            }
        }

        /**
        * get data from attaches table with the given id
        */
        public function getAttacheByName($name) {
            // $name = $this->enc_dec->my_encode($name);
            $command = "SELECT * FROM attaches WHERE name = '" . $name . "'";
            $result = $this->conn->query($command);

            if (isset($result) && $result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $rlt_item = [
                        'id'        => $row['id'],
                        // 'name'      => $this->enc_dec->my_decode($row['name']),
                        'name'      => $row['name'],
                        'size'      => $row['size'],
                        'type'      => $row['type'],
                        'content'   => $row['content']
                    ];
                    return $rlt_item;
                }
            } else {
                return null;
            }
        }

        /**
        * get last id from attaches table 
        */
        public function getLastAttachesId() {
            $command = "SELECT * FROM attaches ORDER BY id DESC";
            $result = $this->conn->query($command);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    return $row['id'];
                }
            } else {
                return 0;
            }
        }


        /**
        * delete data from table with given hashed_id 
        */
        public function deleteAttache($id) {
            $command = "DELETE FROM attaches WHERE id = '" . $id . "'";
            if ($this->conn->query($command) !== TRUE) {
                return "Error deleting record: " . $this->conn->error;
            } else {
                return true;
            }
        }
}