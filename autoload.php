<?php
/**
 * Created by PhpStorm.
 * User: Zzyc
 * Date: 2022/4/17
 * Time: 2:04 PM
 */

function classLoader($class)
{
	$path = str_replace('\\', DIRECTORY_SEPARATOR, $class);
	$file = __DIR__ . '/src/' . $path . '.php';

	if (file_exists($file)) {
		require_once $file;
	}
}
spl_autoload_register('classLoader');