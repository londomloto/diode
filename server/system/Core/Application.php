<?php
/**
 * Core Application
 */

namespace Diode\Core;
Class Application extends \Phalcon\Mvc\Application {

	public function __construct()
	{
		$di = new \Phalcon\DI\FactoryDefault();
		$config = Config::scan(APPPATH.'Config/');
		$registry = new \Phalcon\Registry();
		$registry->directory = (object) array(
			'Core'=> SYSPATH.'Core/',
			'Helper'=>SYSPATH.'Helper/',
			'Library'=>SYSPATH.'Library/',
			'Plugin'=>SYSPATH.'Plugin/',
			'Vendor'=>SYSPATH.'Vendor/',
			'App'=>APPPATH,
		);
		$di->setShared('config',$config);
		$di->setShared('registry',$registry);
		parent::__construct($di);
	}

	function run()
	{
		
		$this->setupLoader();
		$this->setupModule();
		$this->setupRouter();
		$this->setupLibrary();

		echo $this->handle()->getContent();
	}

	protected function setupLoader()
	{
		$loader = new \Phalcon\Loader();
		$registry = $this->getDI()->getRegistry();
		$modules = array();
		$namespaces = array();

		foreach ($registry->directory as $dir => $path) {
			if($dir=='App'){
				$namespaces[$dir] =  $path;
			}else{
				$namespaces['Diode\\'.$dir] =  $path;
			}
		}

		$loader->registerNamespaces($namespaces);
		$loader->register();
		$this->getDI()->set('loader',$loader);
	}

	protected function setupModule()
	{
		$path = APPPATH.'Module/';
		$namespaces = array();
		$bootstraps = array();

		$iter = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path),\RecursiveIteratorIterator::SELF_FIRST);

		foreach ($iter as $i => $object) {
			if($object->getFilename()=='Bootstrap.php'){
				$opath = $object->getPath();
				$name = 'App\\Module'.str_replace(APPPATH.'Module', '', $opath);
				$namespaces[$name] = $opath.'/';
				$bootstraps[$name] = array(
					'name'  => $name,
					'boot'  => $name.'\Bootstrap',
					'path'  => $opath.'/'
				);
			}
		}

		$loader = $this->getDI()->get('loader');
		$loader->registerNamespaces($namespaces, TRUE);
		$loader->register();

		$this->registerModules($bootstraps);
	}

	protected function setupRouter()
	{
		$config = $this->getDI()->get('config')->route;
		$router = new \Phalcon\Mvc\Router();
		$router->removeExtraSlashes(TRUE);

		if ($config->offsetExists('default')) {
			$router->setDefaults($config->default->toArray());
		}

		foreach($this->getModules() as $name => $module) {
			$default = array();
			$prefix = '/'.str_replace('\\', '/', strtolower(str_replace('App\Module\\', '', $name)));
			
			if ($config->offsetExists('module') && $config->module->offsetExists($name)) {
				if ($config->module->{$name}->offsetExists('default')) {
					$default = $config->module->{$name}->default;
				}
			}

			$group = new \Phalcon\Mvc\Router\Group(array(
				'namespace' => $name.'\Controller',
				'module' => $name
			));

			$group->setPrefix($prefix);

			$group->add('/:controller/:action/:params', array(
				'controller' => 1,
				'action' => 2,
				'params' => 3
			));

			$group->add('/:controller/:action', array(
				'controller' => 1,
				'action' => 2
			));

			$group->add('/:controller', array(
				'controller' => 1,
				'action' => 'index'
			));

			$group->add('', array(
				'controller' => 'index'
			));

			$router->mount($group);
		}

		$this->getDI()->set('router', $router);
	}

	protected function setupLibrary() {
		$di = $this->getDI();
		$config = $di->get('config');

		// database
		$database = $config->database;

		foreach($database as $name => $prop) {
			if($prop->autoload){
				$adapter = '\Phalcon\Db\Adapter\Pdo\\'.$prop->adapter;

				$conn = new $adapter([
					'host' => $prop->hostname,
					'port' => $prop->port,
					'username' => $prop->username,
					'password' => $prop->password,
					'dbname' => $prop->database
				]);

				$di->set(($name == 'default' ? 'db' : $name), $conn);
			}
			
		}

		// autoloader by user
		$autoload = $config->autoload->library;

		foreach($autoload as $name => $params) {
			$class = 'Diode\Library\\'.$name;
			if (class_exists($class)) {
				$instance = call_user_func_array(array($class, 'factory'), array($params->toArray()));
				$di->set(strtolower($name), $instance);
			}
		}
	}

	function registerModules(Array $bootstraps, $merge = NULL) {
		
		$modules = array();
		$di = $this->getDI();

		foreach($bootstraps as $name => $prop) {
			$boot = new $prop['boot']($prop);
			$modules[$name] = function() use ($di, $boot) {
				$boot->registerAutoloaders($di);
				$boot->registerServices($di);
				return $boot;
			};
		}

		return parent::registerModules($modules, $merge);
	}
}