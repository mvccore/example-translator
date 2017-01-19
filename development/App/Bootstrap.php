<?php

class App_Bootstrap
{
	public static function Init () {
		// patch debug class
		MvcCore::GetInstance()->SetDebugClass(MvcCoreExt_Tracy::class);

		// add another view helper namespace
		MvcCore_View::AddHelpersClassBases('MvcCoreExt_ViewHelpers');

		// setup homepage route
		MvcCore_Router::GetInstance(array(
			new MvcCore_Route('default', 'Default', 'Default', "#^/$#")
		));
	}
}