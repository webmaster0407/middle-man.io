<?php

namespace App;

use App\EncDec as EncDec;

class NotesModel {

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
        * insert data to notes table 
        */
        public function insertToNote(
                $hashed_id, 
                $note, 
                $attached_id,
                $manual_password,
                $destruct_notification,
                $ref_name,
                $access_by_ip,
                $sms_secure
            ) {

            $hashed_id = $this->enc_dec->my_encode(
                substr($this->getLastNoteId() . $note, 0, 15)
            );

            $note = $this->enc_dec->my_encode($note);
            
            if ($manual_password == "") {
                $this->enc_dec->my_encode(strval(time()));
            } else {
                $manual_password = $this->enc_dec->my_encode($manual_password);
            }
            
            $destruct_notification = $this->enc_dec->my_encode($destruct_notification);
            $ref_name =             $this->enc_dec->my_encode($ref_name);
            $access_by_ip =         $this->enc_dec->my_encode($access_by_ip);
            $sms_secure =           $this->enc_dec->my_encode($sms_secure);

            if ($manual_password == "") { // set password automatically
                $manual_password = $this->enc_dec->my_encode(strval(dechex(time())));
            }

            $command = "INSERT INTO 
                notes (
                    hashed_id,
                    note,
                    attached_id,
                    manual_password,
                    destruct_notification,
                    ref_name,
                    access_by_ip,
                    sms_secure
                    )
                VALUES (
                    '" . $hashed_id . "',
                    '" . $note . "',
                    " . $attached_id . ",
                    '" . $manual_password . "',
                    '" . $destruct_notification . "',
                    '" . $ref_name . "',
                    '" . $access_by_ip . "',
                    '" . $sms_secure . "'
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
        * get data from notes table 
        */
        public function getNotes() {
            $command = "SELECT * FROM notes";
            $result = $this->conn->query($command);

            $rltArray = [];

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $rlt_item = [
                        'id'                    => $row['id'],
                        'hashed_id'             => $row['hashed_id'],
                        'note'                  => $this->enc_dec->my_decode($row['note']),
                        'attached_id'           => $row['attached_id'],
                        'manual_password'       => $this->enc_dec->my_decode($row['manual_password']),
                        'destruct_notification' => $this->enc_dec->my_decode($row['destruct_notification']),
                        'ref_name'              => $this->enc_dec->my_decode($row['ref_name']),
                        'access_by_ip'          => $this->enc_dec->my_decode($row['access_by_ip']),
                        'sms_secure'            => $this->enc_dec->my_decode($row['sms_secure'])
                    ];
                    $rltArray[] = $rlt_item;
                }
            }
            return $rltArray;
        }

        /**
        * get data from notes table with the given id
        */
        public function getNoteById($id) {
            $command = "SELECT * FROM notes WHERE id = '" . $id . "'";
            $result = $this->conn->query($command);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $rlt_item = [
                        'id'                    => $row['id'],
                        'hashed_id'             => $row['hashed_id'],
                        'note'                  => $this->enc_dec->my_decode($row['note']),
                        'attached_id'           => $row['attached_id'],
                        'manual_password'       => $this->enc_dec->my_decode($row['manual_password']),
                        'destruct_notification' => $this->enc_dec->my_decode($row['destruct_notification']),
                        'ref_name'              => $this->enc_dec->my_decode($row['ref_name']),
                        'access_by_ip'          => $this->enc_dec->my_decode($row['access_by_ip']),
                        'sms_secure'            => $this->enc_dec->my_decode($row['sms_secure'])
                    ];
                    return $rlt_item;
                }
            } else {
                return null;
            }
        }

        /**
        * get data from notes table with the given hashed_id
        */
        public function getNoteByHashedId($hashed_id) {
            $command = "SELECT * FROM notes WHERE hashed_id = '" . $hashed_id . "'";
            $result = $this->conn->query($command);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $rlt_item = [
                        'id'                    => $row['id'],
                        'hashed_id'             => $row['hashed_id'],
                        'note'                  => $this->enc_dec->my_decode($row['note']),
                        'attached_id'           => $row['attached_id'],
                        'manual_password'       => $this->enc_dec->my_decode($row['manual_password']),
                        'destruct_notification' => $this->enc_dec->my_decode($row['destruct_notification']),
                        'ref_name'              => $this->enc_dec->my_decode($row['ref_name']),
                        'access_by_ip'          => $this->enc_dec->my_decode($row['access_by_ip']),
                        'sms_secure'            => $this->enc_dec->my_decode($row['sms_secure'])
                    ];
                    return $rlt_item;
                }
            } else {
                return null;
            }
        }

        /**
        * get last id from notes table 
        */
        public function getLastNoteId() {
            $command = "SELECT * FROM notes ORDER BY id DESC";
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
        public function deleteNoteById($id) {
            $command = "DELETE FROM notes WHERE id = " . $id;
            if ($this->conn->query($command) !== TRUE) {
                return "Error deleting record: " . $this->conn->error;
            } else {
                return true;
            }
        }

        /**
        * delete data from table with given hashed_id 
        */
        public function deleteNote($hashed_id) {
            $command = "DELETE FROM notes WHERE hashed_id = '" . $hashed_id . "'";
            if ($this->conn->query($command) !== TRUE) {
                return "Error deleting record: " . $this->conn->error;
            } else {
                return true;
            }
        }


}