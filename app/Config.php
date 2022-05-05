<?php

namespace App;

class Config {
   /**
    * path to the sql database
    */
    const SERVER_NAME = 'localhost';
    const USER_NAME = 'root';
    const PASSWORD = '';
    const DATABASE_NAME = 'private_x';

   /**
    * urls of the project
    */
    const BASE_URL = 'http://localhost/privateX';
    const ERROR_URL = 'http://localhost/privateX/err/404.php';

   /**
    * upload path (realative path for util/upload_file.php)
    */
    const UPLOAD_FILE_PATH = '../uploads';

    const DATE_EXPIRED_RANGE = [
         0,                  // after reading it
         60 * 60,            // 1 hours from now
         60 * 60 * 3,        // 3 hours from now
         60 * 60 * 24,       // 24 hours from now
         60 * 60 * 24 * 7,   // 4 days from now
         60 * 60 * 24 * 30   // 30 days from now
    ];

   /**
    * encrypt and decrypt 
    */
    const ENCRYPTION_KEY = "my_secret_key";
    
    // const ENCRYPT_OPTION = 0;
    const ENC_DEC_ALGO = "aes-256-cbc";

}