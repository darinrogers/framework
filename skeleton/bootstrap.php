<?php
/**
 * bootstrap
 *
 * PHP version 5.3
 *
 * @category Skeleton
 * @package  Skeleton
 * @author   Darin Rogers <darinrogers@hotmail.com>
 * @license  http://opensource.org/licenses/bsd-license.php New BSD License
 * @link     https://github.com/darinrogers/framework
 */

define('APP_DIR', __DIR__);

require realpath(__DIR__ . '/../framework/Framework/bootstrap.php');

$router = new \Framework\Router($_SERVER['REQUEST_URI']);

$controllerName = '\\Controllers\\' . $router->getControllerName();
/* @var $controller \Framework\Controller */
$controller = new $controllerName();
$controller->runAction($router->getActionName());
echo $controller->getResponse();