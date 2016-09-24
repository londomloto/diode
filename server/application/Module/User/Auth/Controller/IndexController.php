<?php 
namespace App\Module\User\Auth\Controller;

use App\Module\User\User\Model\Users;

class IndexController extends \Diode\Core\Controller {

	public function indexAction() {
		print('x');
	}

	public function testAction() {
		$users = Users::find();
		$this->view->setVar('users', $users);
	}

	public function helloAction() {
		exit();
	}

}