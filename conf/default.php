<?php
return [
	'appName' => 'Wbkr.nl',
	'locale' => 'en_US',
	'charset' => 'utf-8',
	'db' => [
		'class' => 'Mongo',
		'args' => ['mongodb://www0.sangers.nu'],
	],
	'dbName' => 'live',
	'routeMap' => [
		'class' => 'RouteMap',
		'args' => [[
			// e.g. [class/type-Route, Controller/method/paramName1/paramName3/paramName2, [options,..]]
			['regex', 'DefaultController/actionIndex', 'pattern' => '#^/$#',],
			['regex', 'DefaultController/actionIndex/_omit_/name/_omit_/id', 'pattern' => '#^(/([a-zA-Z]+))?(/([0-9]+))?$#',]
		]],
	],
];
