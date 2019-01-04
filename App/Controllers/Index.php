<?php

namespace App\Controllers;

class Index extends Base
{
	public function IndexAction () {
		$sessionTexts = $this->getSessionTexts();
		if ($this->GetViewEnabled()) {
			$this->view->OriginalText = $sessionTexts->original;
			$this->view->TranslatedText = $sessionTexts->translated;
		}
	}
	public function NotFoundAction(){
		$this->ErrorAction();
	}
	public function ErrorAction(){
		$code = $this->response->GetCode();
		$message = $this->request->GetParam('message', '\\a-zA-Z0-9_;, /\-\@\:');
		$message = preg_replace('#`([^`]*)`#', '<code>$1</code>', $message);
		$message = str_replace("\n", '<br />', $message);
		$this->view->Title = "Error $code";
		$this->view->Message = $message;
		$this->Render('error');
	}
}
