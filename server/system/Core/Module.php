<?php
namespace Diode\Core;

/**
* 
*/
class Module implements \Phalcon\Mvc\ModuleDefinitionInterface
{	

	protected $name;
	protected $path;

	public function __construct($config = array()) {
		$this->name = isset($config['name']) ? $config['name'] : '';
		$this->path = isset($config['path']) ? $config['path'] : '';
	}	

	public function getName() {
		return $this->name;
	}

	public function registerAutoloaders(\Phalcon\DiInterface $di = NULL) {
		$loader = $di->get('loader');

		$loader->registerNamespaces(array(
			$this->name.'\Controller' => $this->path.'Controller',
			$this->name.'\Model' => $this->path.'Model'
		), TRUE);

		$loader->register();
	}

	public function registerServices(\Phalcon\DiInterface $di) {
		
		$path = $this->path;

		$dispatcher = new \Phalcon\Mvc\Dispatcher();
		
		$di->set('view', function() use ($path) {
			$view = new \Phalcon\Mvc\View();
			$view->setViewsDir($path.'View/');

			return $view;
		});

		$di->set('dispatcher', $dispatcher);
	}
}