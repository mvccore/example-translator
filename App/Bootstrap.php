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
