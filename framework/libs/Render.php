<?php
/**
 * @author Olivarès Georges (https://github.com/Thiktak)
 * @author Bruno Muller (https://github.com/Bruno-Muller)
 */

Class Render {
	protected $view;
	protected $vars = array();
	protected $headers = array(
		'statusCode' => 200, // todo
	);
	protected $options = array(
		'format' => 'html', // todo
		'layout' => array('content', null),
	);

	public function __construct($view, array $vars, $statusCode = 200, array $options = null) {
		$this->setView($view);
		$this->setVars($vars);
		$this->options = array_merge($this->options, (array) $options);
	
		if( $statusCode )
			$this->setHeader('statusCode', $statusCode);
	}

	public function setView($view) { $this->view = $view; }
	public function setVars(array $vars) { $this->vars = $vars; }
	public function setVar($name, $value) { $this->vars[$name] = $value; }
	public function setHeader($name, $value) { $this->headers[$name] = $value; }
	public function setLayout($layout, $var = 'content') { $this->options['layout'] = array($var, $layout); }

	public function getVar($name) { return isset($this->vars[$name]) ? $this->vars[$name] : null; }
	public function getOption($name) { return isset($this->options[$name]) ? $this->options[$name] : null; }

	public function getHeaders() { // todo
		return $this->headers;
	}

	private function _preg_replace_callback1($matche) {
			return isset($vars[$matche[1]]) ? $vars[$matche[1]] : null;
	}

	private function _preg_replace_callback2($matche) {
			switch(trim(strtolower($matche[1]))) {
				case 'print_r':
				case 'var_dump':
				case 'trim':
				case 'substr':
					return eval('return ' . sprintf('%s(%s)', $matche[1], $matche[2]) . ';');
					break;
			}
			return 'func$1($2)';
		}

	protected function _ob_start($buffer, array $vars = null) {

		$vars = array_merge($this->vars, (array) $vars);
		$_this = $this;

		$buffer = preg_replace_callback('`\{\{ \$([a-zA-Z0-9_]+) \}\}`sUi', array($this, '_preg_replace_callback1'), $buffer);

		$buffer = preg_replace_callback('`\{\{ ([a-zA-Z0-9_]+)\((.*)\) \}\}`sUi', array($this, '_preg_replace_callback2'), $buffer);
		
		return $buffer;
	}


	protected static function _display(Render $object, $view, array $vars = null) {
		$possiblesFiles = array(
			APP . 'Views' . DS . $view,
			APP . 'Views' . DS . $view . '.php',
			APP . 'Views' . DS . $view . '.' . $object->getOption('format'),
			APP . 'Views' . DS . $view . '.' . $object->getOption('format') . '.php',
		);

		$vars = (array) $vars;
		$render = null;
		$viewFile = null;

		foreach( $possiblesFiles as $file )
			if( file_exists($file) ) {
				$viewFile = $file;
				break;
			}

		if( !file_exists($viewFile) )
			throw new Exception(sprintf('`%s` file does not exist !', $view));

		ob_start();
		$tpl = $object;

		extract($vars);
		include $viewFile;

		$content = $object->_ob_start(ob_get_clean(), $vars);

		$layout = $object->getOption('layout');
		if( $layout[1] ) {
			$newRender = clone $object;
			$newRender->setLayout(null);
			$newRender->setVar($layout[0], $content);

			return self::_display($newRender, $layout[1], $vars);
		}

		return trim($content, "\n\r");
	}

	public function display() {
		return $this->_display($this, $this->view, $this->vars);
	}
}