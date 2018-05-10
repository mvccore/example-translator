<?php

namespace App;

class Bootstrap
{
	public static function Init () {
		
		$app = \MvcCore\Application::GetInstance();
		
		// patch debug class
		if (class_exists('\MvcCore\Ext\Debug\Tracy')) {
			//\MvcCore\Ext\Debug\Tracy::$Editor = 'MSVS2015';
			$app->SetDebugClass(\MvcCore\Ext\Debug\Tracy::class);
		}

		// use this line only if you want to pack application without JS/CSS/fonts/images
		// inside package and you want to have all those files placed on hard drive manualy.
		// You can use this variant in modes PHP_PRESERVE_PACKAGE, PHP_PRESERVE_HDD and PHP_STRICT_HDD
		//\MvcCore\Ext\View\Helpers\Assets::SetAssetUrlCompletion(FALSE);
	}
}