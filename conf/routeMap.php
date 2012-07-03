<?php
// RouteMap file
// Default route types:
// * regex: RegexRoute
// * simple: SimpleRoute
// format:
// [class/type, class/method/paramName1/_omit_/paramName3/paramName2, [routeOptions, ...]]
return [
	['regex', 'DefaultController/actionIndex', 'pattern' => '#^/$#',],
	['regex', 'DefaultController/actionIndex/_omit_/name/_omit_/id', 'pattern' => '#^(/([a-zA-Z]+))?(/([0-9]+))?$#',],
];
