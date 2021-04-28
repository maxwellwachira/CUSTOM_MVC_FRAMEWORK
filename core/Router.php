<?php

namespace app\core;
/**
 * 
 */
class Router {

	public Request $request;
	public Response $response;
	protected array $routes = [];

	public function __construct(Request $request, Response $response){

		$this->request = $request;
		$this->response = $response;
	}

	public function get($path, $callback)
	{
		$this->routes['get'][$path] = $callback;
	}

	public function post($path, $callback)
	{
		$this->routes['post'][$path] = $callback;
	}

	public function resolve(){

		$path = $this->request->getPath();
		$method = $this->request->getMethod();
		$callback = $this->routes[$method][$path] ?? false;
		if($callback === false){
			$this->response->setStatusCode(404);
			echo 'Not found';
			
		}
		if(is_string($callback)){
			return $this->renderView($callback, $params); 
		}
		if(is_array($callback)){
			$callback[0] = new $callback[0]();
		}
		return call_user_func($callback, $this->request);
	} 

	public function renderView($view, $params = []){

		foreach ($params as $key => $value) {
			$$key = $value;
		}

		/*$layoutContent = $this->layoutContent();
		$viewContent = $this->renderOnlyView($view);
		return str_ireplace('{{content}}', $viewContent, $layoutContent);*/
		include_once Application::$ROOT_DIR."/views/".$view.".php";
	}

	/*protected function layoutContent()
	{
		//start a buffer to cache the output. The output will only be cached and not displayed by the browser
		ob_start();
		include_once Application::$ROOT_DIR."/views/layouts/main.php";
		$data = ob_get_contents();
		//Return cached data and clear the buffer
		//ob_end_clean();

		return $data;
	}

	protected function renderOnlyView($view)
	{
		ob_start();
		include_once Application::$ROOT_DIR."/views/".$view.".php";
		$data2 = ob_get_contents();
		//Return cached data and clear the buffer
		//ob_end_clean();

		return $data2;
	}*/
}
?>