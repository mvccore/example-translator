<?php

namespace App\Controllers;

class Base extends \MvcCore\Controller {

	private static $_session = NULL;

	public function PreDispatch () {
		parent::PreDispatch();
		if (!$this->ajax) {

			\MvcCore\Ext\Views\Helpers\Assets::SetGlobalOptions([
				'cssMinify'		=> 1,
				'cssJoin'		=> 1,
				'jsMinify'		=> 1,
				'jsJoin'		=> 1,
			]);

			$static = self::$staticPath;
			$this->view->Css('fixedHead')
				->Append($static . '/css/resets.css')
				->Append($static . '/css/old-browsers-warning.css')
				->AppendRendered($static . '/css/fonts.css')
				->AppendRendered($static . '/css/all.css')
				->AppendRendered($static . '/css/forms-and-controls.css');
			$this->view->Js('fixedHead')
				->Append($static . '/js/libs/class.min.js')
				->Append($static . '/js/libs/ajax.min.js')
				->Append($static . '/js/libs/Module.js')
				->Append($static . '/js/libs/Helpers.js');
			$this->view->Js('varFoot')
				->Append($static . '/js/Front.js');
		}
	}
	protected function & getSessionTexts () {
		if (self::$_session === NULL) {
			self::$_session = \MvcCore\Session::GetNamespace('texts');
			self::$_session->SetExpirationSeconds(3600); // hour
		}
		return self::$_session;
	}
}
