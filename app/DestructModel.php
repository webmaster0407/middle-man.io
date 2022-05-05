<?php

namespace App;
use App\EncDec as EncDec;

class DestructModel {

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
        * insert data to destruction table 
        */
        public function insertToDestructs(
                $note_id,
                $action_name, 
                $confirm_check,
                $hours,
                $is_read,
                $is_expired
            ) {

            $command = "INSERT INTO destruction (
                            note_id,
                            action_name,
                            confirm_check,
                            hours,
                            is_read,
                            is_expired
                        ) VALUES (
                            " . $note_id . ",
                            '" . $action_name . "',
                            " . $confirm_check . ",
                            " . $hours . ",
                            " . $is_read . ",
                            " . $is_expired . " )";
            if ( $this->conn->query($command) !== TRUE) {
                echo "Error: " . $command . "<br>" . $this->conn->error;
                exit();
            } else {
                $last_id = $this->conn->insert_id;
                return $last_id;
            }
        }

        /**
        * get data from destruction table 
        */
        public function getDestructs() {
            $command = "SELECT * FROM destruction";
            $result = $this->conn->query($command);

            $rltArray = [];

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $rlt_item = [
                        'id'            => $row['id'],
                        'note_id'       => $row['note_id'],
                        'action_name'   => $row['action_name'],
                        'confirm_check' => $row['confirm_check'],
                        'hours'         => $row['hours'],
                        'is_read'       => $row['is_read'],
                        'is_expired'    => $row['is_expired']
                    ];
                    $rltArray[] = $rlt_item;
                }
            }
            return $rltArray;
        }

        /**
        * get data from destruction table with the given id
        */
        public function getDestruct($id) {
            $command = "SELECT * FROM destruction WHERE id = " . $id;
            $result = $this->conn->query($command);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $rlt_item = [
                        'id'            => $row['id'],
                        'note_id'       => $row['note_id'],
                        'action_name'   => $row['action_name'],
                        'confirm_check' => $row['confirm_check'],
                        'hours'         => $row['hours'],
                        'is_read'       => $row['is_read'],
                        'is_expired'    => $row['is_expired']
                    ];
                    return $rlt_item;
                }
            } else {
                return null;
            }
        }

        /**
        * get data from destruction table with the given id
        */
        public function getDestructByNoteId($note_id) {
            $command = "SELECT * FROM destruction WHERE note_id = " . $note_id;
            $result = $this->conn->query($command);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $rlt_item = [
                        'id'            => $row['id'],
                        'note_id'       => $row['note_id'],
                        'action_name'   => $row['action_name'],
                        'confirm_check' => $row['confirm_check'],
                        'hours'         => $row['hours'],
                        'is_read'       => $row['is_read'],
                        'is_expired'    => $row['is_expired']
                    ];
                    return $rlt_item;
                }
            } else {
                return null;
            }
        }

        /**
        * get last id from destruction table 
        */
        public function getLastDestructId() {
            $command = "SELECT * FROM destruction ORDER BY id DESC";
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
        * delete data from destruction table with given id 
        */
        public function deleteDestruct($id) {
            $command = "DELETE FROM destruction WHERE id = '" . $id . "'";
            if ($this->conn->query($command) !== TRUE) {
                return "Error deleting record: " . $this->conn->error;
            } else {
                return true;
            }
        }


        /**
        * delete data from destruction table with given hashed_id 
        */
        public function deleteDestructByNoteId($note_id) {
            $command = "DELETE FROM destruction WHERE note_id = '" . $note_id . "'";
            if ($this->conn->query($command) !== TRUE) {
                return "Error deleting record: " . $this->conn->error;
            } else {
                return true;
            }
        }


        /**
        * update is_read state with the given id 
        */
        public function updateIsReadState($id) {
            $command = "UPDATE destruction SET is_read = 1 WHERE id = " . $id;
            if ($this->conn->query($command) !== TRUE) {
                return "Error deleting record: " . $this->conn->error;
            } else {
                return true;
            }
        }


        /**
        * update is_expired state with the given id 
        */
        public function updateIsExpiredState($id) {
            // $command = "UPDATE destruction SET is_expired = 1, hours = " . time() . " WHERE id = " . $id;
            $command = "UPDATE destruction SET is_expired = 1 WHERE id = " . $id;
            if ($this->conn->query($command) !== TRUE) {
                return "Error deleting record: " . $this->conn->error;
            } else {
                return true;
            }
        }


        /**
        * update hour when action name == 1 and is not expired 
        */
        public function updateExpiredTime($id) {
            $currentTime = time();
            $command = "UPDATE destruction SET hours = ". $currentTime . " WHERE id = " . $id;
            if ($this->conn->query($command) !== TRUE) {
                return "Error deleting record: " . $this->conn->error;
            } else {
                return true;
            }
        }

}