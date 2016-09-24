<?php 
namespace Diode\Core;

class Controller extends \Phalcon\Mvc\Controller {

	public function getRESTRequest() {
		return $this->request->GetJsonRawBody();
	}

	//------------------------------- REST ---------------------------
	public function getAction($id = NULL) {}

	public function postAction() {}

	public function putAction($id) {}

	public function deleteAction($id) {}

}