<?php

namespace app\core;

use app\core\Application;
use app\core\Request;


class Controller
{
	public function render($view, $params = []){

		return Application::$app->router->renderView($view, $params);
	}
}

?>