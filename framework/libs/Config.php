<?php
/**
 * @author Olivarès Georges (https://github.com/Thiktak)
 */

Class Config {
	public function __construct($load = null) {
		$this->load($load);
	}

	public function load($datas) {
		if( is_null($datas) )
			return ;
		
		if( is_array($datas) )
			return $this->addAll($datas);

		if( is_string($datas) )
			return $this->loadFile($datas);

		throw new Exception();
	}

	public function loadFile($file) {}
	public function addAll(array $arrayDatas) {}

	public function all() { return array(); }
	public function get($name) { return null; }
}