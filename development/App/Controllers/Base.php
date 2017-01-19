<?php

class App_Controllers_Base extends MvcCore_Controller
{
	private static $_session = NULL;
	public function PreDispatch () {
		parent::PreDispatch();
		if (!$this->ajax) {
			MvcCoreExt_ViewHelpers_Assets::SetGlobalOptions(array(
				'cssMinify'	=> 1,
				'cssJoin'	=> 1,
				'jsMinify'	=> 1,
				'jsJoin'	=> 1,
			));
			$static = self::$staticPath;
			$this->view->Css('fixedHead')
				->AppendRendered($static . '/css/fonts.css')
				->AppendRendered($static . '/css/all.css')
				->AppendRendered($static . '/css/button.css');
			$this->view->Js('fixedHead')
				->Append($static . '/js/libs/class.min.js')
				->Append($static . '/js/libs/ajax.min.js')
				->Append($static . '/js/libs/Helpers.js')
				->Append($static . '/js/libs/Module.js');
			$this->view->Js('varFoot')
				->Append($static . '/js/Front.js');
		}
	}
	protected function & getSessionTexts () {
		if (is_null(self::$_session)) {
			self::$_session = MvcCore_Session::GetNamespace('texts');
			self::$_session->SetExpirationSeconds(3600); // hour
		}
		return self::$_session;
	}
}