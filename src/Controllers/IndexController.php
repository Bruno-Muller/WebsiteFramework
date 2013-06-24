<?php
/**
 * @author Olivarès Georges (https://github.com/Thiktak)
 */

Class IndexController extends Controller {
	public function indexAction() {

		return array(
			'entities' => array(array('id' => 1), array('id' => 2), array('id' => 3)),
		);
	}

	public function index2Action() {
		return $this->render('index/index.html', array(
			'entities' => array(array(), array(), array()),
		));
	}
}