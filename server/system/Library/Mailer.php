<?php 

namespace Diode\Library;

class Mailer {

	public function initialize($config) {
		foreach($config as $key => $val) {
			$this->$key = $val;
		}
	}

	static function factory($config) {
		$mailer = new Mailer();
		$mailer->initialize($config);
		return $mailer;
	}

	public function send() {
		var_dump('Mail sent!');
	}
}