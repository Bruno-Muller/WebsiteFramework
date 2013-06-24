<?php
/**
 * @author Olivarès Georges (https://github.com/Thiktak)
 */

Class Router {
	protected $route = array();

	public function __construct() {
		$this->route['_route'] = array();
		$this->route['_controller'] = 'index';
		$this->route['_action'] = 'index';
		$this->route['_args'] = array();
		$this->route['_post'] = $_POST;
		$this->route['_get'] = $_GET;
		$this->route = self::parseArray($this->route, $this->route['_get']);
	}

	public static function parseArray(array $route, array $datas) {

		// search $_GET[0] or `?[/value]&...`
		$data = null;
		foreach( $datas as $key => $value)
			if( strpos($key, '/') !== false && $value === '' ) {
				$data = $key;
				unset($route['_get'][$key]);
				break;
			}

		$route['_route'] = trim($data, ' /');
		if( $route['_route'] ) {
			$routeDatas = explode('/', $route['_route']);

			if( isset($routeDatas[0]) )
				$route['_controller'] =  $routeDatas[0];

			if( isset($routeDatas[1]) )
				$route['_action'] =  $routeDatas[1];

			$route['_args'] = array_slice($routeDatas, 2);
		}


		return $route;
	}

	public function get($name) {
		return isset($this->route[$name]) ? $this->route[$name] : null;
	}

	public function all() {
		return array_merge(
			(array) $this->get('_post'),
			(array) $this->get('_get'),
			(array) $this->get('_args')
		);
	}
}

?>