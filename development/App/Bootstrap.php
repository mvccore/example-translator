<?php

class App_Bootstrap
{
	public static function Init () {
		// patch debug class
		MvcCore::GetInstance()->SetDebugClass(MvcCoreExt_Tracy::class);

		// use this line only if you want to pack application without JS/CSS/fonts/images
		// placed on hard drive manualy, in preserve package mode or in preserve hdd mode:
		//MvcCoreExt_ViewHelpers_Assets::SetAssetUrlCompletion(FALSE);

		// add another view helper namespace
		MvcCore_View::AddHelpersClassBases('MvcCoreExt_ViewHelpers');
	}
}