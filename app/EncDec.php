<?php

namespace App;

use App\Config as Config;

class EncDec {

	public function my_encode_base64($input) {
		return strtr(base64_encode($input), '+/=', '-_A');
	}

	public function my_decode_base64($input) {
		return base64_decode(strtr($input, '-_A', '+/='));
	}

	public function my_encode($original_string) {
		$hash = substr(hash('sha256', Config::ENCRYPTION_KEY, true), 0, 32);
		$iv = chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0);
		$encrypted = base64_encode(
			openssl_encrypt(
				$original_string, 
				Config::ENC_DEC_ALGO, 
				$hash, 
				OPENSSL_RAW_DATA, 
				$iv
			)
		);
		return $this->my_encode_base64($encrypted);
	}

	public function my_decode($encrypted_string) {
		$encrypted_string = $this->my_decode_base64($encrypted_string);
		$hash = substr(hash('sha256', Config::ENCRYPTION_KEY, true), 0, 32);
		$iv = chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0);
		$original_string = openssl_decrypt(
			base64_decode($encrypted_string), 
			Config::ENC_DEC_ALGO, 
			$hash, 
			OPENSSL_RAW_DATA, 
			$iv
		);
		return $original_string;
	}

}

?>