<?php
/**
 * Route
 */

return array(
	
	'default' => array(
		'module'=>'App\Module\Main',
		'namespace'=>'App\Module\Main\Controller',
		'controller'=>'index',
		'action'=>'index'
	),

	'module' => array(
		'App\Module\User\User' => array(
			'default' => array(
				'module'=>'App\Module\User\User',
				'namespace'=>'App\Module\User\User\Controller',
				'controller'=>'index',
				'action'=>'index'
			)
		)
	)
);