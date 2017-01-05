<?php

class App_Controllers_Base extends MvcCore_Controller
{
	protected static $staticPath = '/static/';
	protected static $tmpPath = '/Var/Tmp';

	private static $_sessionKeyOriginalText = 'originalText';
	private static $_sessionKeyTranslatedText = 'translatedText';

	public function PreDispatch () {
		parent::PreDispatch();
		if (!$this->ajax && $this->request->params['controller'] !== 'assets') {
			App_Views_Helpers_Assets::SetGlobalOptions(array(
				'cssMinify'	=> 1,
				'cssJoin'	=> 1,
				'jsMinify'	=> 1,
				'jsJoin'	=> 1,
				'tmpDir'	=> self::$tmpPath,
				// for PHAR packing - uncomment line bellow to "md5_file"
				//'fileChecking'	=> 'md5_file',
			));
			$this->view->Css('fixedHead')
				->AppendRendered(self::$staticPath . 'css/fonts.css')
				->AppendRendered(self::$staticPath . 'css/all.css')
				->AppendRendered(self::$staticPath . 'css/button.css');
			$this->view->Js('fixedHead')
				->Append(self::$staticPath . 'js/libs/class.min.js')
				->Append(self::$staticPath . 'js/libs/ajax.min.js')
				->Append(self::$staticPath . 'js/libs/Helpers.js')
				->Append(self::$staticPath . 'js/libs/Module.js');
			$this->view->Js('varFoot')
				->Append(self::$staticPath . 'js/Front.js');
		}
	}
	protected function redirectToNotFound () {
		self::Redirect(
			$this->url('Default::NotFound'),
			404
		);
	}
	protected function setSessionTexts ($originalText = '', $translatedText = '') {
		$_SESSION[self::$_sessionKeyOriginalText] = $originalText;
		$_SESSION[self::$_sessionKeyTranslatedText] = $translatedText;
	}
	protected function getSessionTexts () {
		$originalText = isset($_SESSION[self::$_sessionKeyOriginalText]) ? $_SESSION[self::$_sessionKeyOriginalText] : App_Controllers_Translator::DEFAULT_ORIGINAL_TEXT ;
		$translatedText = isset($_SESSION[self::$_sessionKeyTranslatedText]) ? $_SESSION[self::$_sessionKeyTranslatedText] : App_Controllers_Translator::DEFAULT_TRANSLATED_TEXT ;
		return array($originalText, $translatedText);
	}
}