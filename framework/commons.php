<?php
/**
 * @author Olivarès Georges (https://github.com/Thiktak)
 * @author Bruno Muller (https://github.com/Bruno-Muller)
 */

define('DS', DIRECTORY_SEPARATOR);
define('FRAMEWORK', dirname(__FILE__) . DS);

if( !defined('APP') )
	define('APP', realpath(FRAMEWORK . '..') . DS . 'src' . DS);

function _spl_autoload_register($class)
{
    $directories = array(FRAMEWORK . 'libs' . DS, APP . 'Controllers' . DS); // dossiers de recherche des classes pour l'autoload
    $file = $class.'.php'; // nom du fichier de la classe

    foreach($directories as $directory) { // On cherche dans les répertoires
        $path = $directory . $file; // Chemin de recherche
        if (file_exists($path)) {
        	include_once $path;
        	return;
        }
    }

    throw new Exception(sprintf('File `%s` does not exist !', $file));
}

spl_autoload_register('_spl_autoload_register'); // autolad pour charger les classes automatiquement
