<?php

/**
* Core Config 
*/
namespace Diode\Core;

class Config extends \Phalcon\Config
{
	function __construct($config=array())
	{
		parent::__construct($config);
	}

	function load($name,$file)
	{
		$array 	= include_once($file);
		$this->offsetSet($name,$array);
		return $this;
	}

	static function scan($dir)
	{
		$config = new Config(array());

		foreach (scandir($dir) as $file) {
			if($file != '.' && $file !='..'){
				$name = basename($file,'.php');
				$config->name=$config->load($name,$dir.'/'.$file);
			}
		}

		return $config;
	}
}