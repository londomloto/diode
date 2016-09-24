<?php

namespace App\Helper;

/**
* 
*/
class Text extends \Diode\Helper\Text
{
	static function upper($str)
	{
		return parent::upper($str).' From App';
	}

}