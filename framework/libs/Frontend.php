<?php
/**
 * @author Olivarès Georges (https://github.com/Thiktak)
 */

Class Frontend {

	protected $objects = array();

	public function __construct($options = null) {
		$this->register('config', new Config());
		$this->register('router', new Router());
		$this->register('db', new PDO('sqlite:www/test.db'));
	}

	public function get($name) {
		if( !isset($this->objects[$name]) )
			throw new UndefinedFrameworkObjectException($name);

		return $this->objects[$name];
	}

	public function register($name, $value) {
		$this->objects[$name] = $value;
	}

	protected function call($controller, $action, array $arguments = null) {

		$sController = $controller . 'Controller';

		if( !class_exists($sController) )
			throw new Exception(sprintf('Controller `%s` does not exist', $controller));

		if( !method_exists($sController, $action . 'Action') )
			throw new Exception(sprintf('Method `%s` does not exist', $action));

		$oController = new $sController($this);

		$aArguments = array(); // array_merge(array('id' => end($explode))); // $explode[count($explode) - 1];

		$oMethod = new ReflectionMethod($sController, $action . 'Action');

		try {
			$mReturn = $oMethod->invokeArgs($oController, $aArguments); // retourne le resultat du controller

			if( is_array($mReturn) )
				$mReturn = new Render($controller . '/' . $action, $mReturn);

			if( is_object($mReturn) && is_a($mReturn, 'Render') ) {
				return $mReturn->display();
			}

			if( is_string($mReturn) ) {
				return $mReturn;
			}

			throw new Exception('Undefined content');
		}
		/*catch( SQLException $e) {

		}
		catch( InternalException $e ) {
			// $content = ob_get_content(); ob_clean();
			// avec les codes et tout ...
		}*/
		catch( Exception $e ) { // toutes les autres
			echo '<pre style="background-color: black; padding: 1em; color: white;">DEBUG : ', $e, '</pre>';
		}
	}

	public function display() {
		$router = $this->get('router');

		return $this->call($router->get('_controller'), $router->get('_action'), $router->all());
	}

	public function __toString() {
		try {
			return (String) $this->display();
		}
		catch(Exception $e) {
			return (String) $e;
		}
	}
}