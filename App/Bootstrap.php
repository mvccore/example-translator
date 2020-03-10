<?php

namespace App;

class Bootstrap {

	public static function Init () {

		$app = \MvcCore\Application::GetInstance();

		// Patch debug class:
		if (class_exists('\MvcCore\Ext\Debugs\Tracy')) {
			\MvcCore\Ext\Debugs\Tracy::$Editor = 'NotepadPP';
			$app->SetDebugClass('\MvcCore\Ext\Debugs\Tracy');
		}

		/**
		 * Uncomment this line before generate any assets into temporary directory, before application
		 * packing/building, only if you want to pack application without JS/CSS/fonts/images inside
		 * result PHP package and you want to have all those files placed on hard drive.
		 * You can use this variant in modes `PHP_PRESERVE_PACKAGE`, `PHP_PRESERVE_HDD` and `PHP_STRICT_HDD`.
		 */
		//\MvcCore\Ext\Views\Helpers\Assets::SetAssetUrlCompletion(FALSE);

		// Configure router to process automatically for homepage request (`/`) a route `Index:Index`:
		$app->GetRouter()->SetRouteToDefaultIfNotMatch();

		// Or you can configure router with multiple routes:
		/*$app->GetRouter()->SetRoutes([
			'Index:Index'			=> '/',
			'Translator:HtmlSubmit'	=> '/submit',
			'Translator:JsSubmit'	=> '/ajax-submit',
		]);*/

		return $app;
	}
}
