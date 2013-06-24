<?php
/**
 * @author Olivarès Georges (https://github.com/Thiktak)
 */

Class Controller {
	public $framework;

	public function __construct(Frontend $framework) {
		$this->framework = $framework;
	}

	public function render($view, array $vars) {
		return new Render($view, $vars);
	}

}