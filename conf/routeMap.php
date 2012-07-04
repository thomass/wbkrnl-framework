<?php
//
// RouteMap file
//
// Default route types:
// * regex: RegexRoute
// * simple: SimpleRoute
//
// Default Route format:
// [class/type, ['class','method', 'paramName' => 'value', ...], [routeOptions, ...]]

return [
//	['simple', ['DefaultController', 'actionIndex'], 'whole' => '/',],
//	['simple', ['DefaultController', 'actionIndex'], 'begin' => '/begin/',],
//	['simple', ['DefaultController', 'actionIndex'], 'end' => '/end',],
//	['simple', ['DefaultController', 'actionIndex'], 'contains' => '/contains/',],
	['regex', ['DefaultController', 'actionIndex', 'name' => 2, 'id' => 4], 'pattern' => '#^(/([a-zA-Z]+))?(/([0-9]+))?$#',],
];
