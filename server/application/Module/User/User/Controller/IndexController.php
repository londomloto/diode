<?php 
namespace App\Module\User\User\Controller;

class IndexController extends \Diode\Core\Controller {

	public function indexAction($a = NULL) {
		var_dump("index", $a);
	}
}