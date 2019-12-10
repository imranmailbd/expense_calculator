<?php
spl_autoload_register(function($class) {

		$path = $class;

        require_once __DIR__.'/'.$path.'.php'; 
        
});