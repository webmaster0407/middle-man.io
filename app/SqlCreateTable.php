<?php

namespace App;

/**
 * Sql Create Tables
 */

class SqlCreateTable {

        /**
        * mysqli object
        * @var mysqli
        */
        private $conn;

        /**
        * connect to the mysql database
        */
        public function __construct($conn) {
                $this->conn = $conn;
        }

        /**
        * create tables 
        */
        public function createTables() {
                
                $commands = [
                        "CREATE TABLE IF NOT EXISTS `notes` (
                          `id` int(10) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
                          `hashed_id` varchar(255) NOT NULL,
                          `note` text DEFAULT NULL,
                          `attached_id` int(10) DEFAULT -1,                          
                          `manual_password` varchar(255) DEFAULT NULL,
                          `destruct_notification` varchar(255) DEFAULT NULL,
                          `ref_name` varchar(255) DEFAULT NULL,
                          `access_by_ip` varchar(255) DEFAULT NULL,
                          `sms_secure` varchar(255) DEFAULT NULL
                        )",
                        "CREATE TABLE IF NOT EXISTS `destruction` (
                          `id` int(10) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
                          `note_id` int(10) UNSIGNED NOT NULL,
                          `action_name` varchar(255) NOT NULL,
                          `confirm_check` tinyint(1) DEFAULT 0,
                          `hours` bigint(20) NOT NULL,
                          `is_read` tinyint(1) DEFAULT 0,
                          `is_expired` tinyint(1) DEFAULT 0
                        )",
                        "CREATE TABLE IF NOT EXISTS `attaches` (
                          `id` int(10) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
                          `name` varchar(255) NOT NULL,
                          `size` int(10) UNSIGNED NOT NULL,
                          `type` varchar(255) NOT NULL,
                          `content` LONGBLOB NOT NULL
                        )"
                ];

                foreach ($commands as $command) {
                    if  ($this->conn->query($command) !== TRUE) {
                        die("Error creating table: " . $this->conn->error . '<br>');
                    }
                }
        }
        
}