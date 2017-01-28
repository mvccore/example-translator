<?php

namespace App\Controllers;

class Index extends Base
{
	public function IndexAction () {
		$sessionTexts = $this->getSessionTexts();
		$this->view->OriginalText = $sessionTexts->original;
		$this->view->TranslatedText = $sessionTexts->translated;
	}
	public function NotFoundAction () {
		$this->view->Title = "Error 404 - requested page not found.";
		$this->view->Message = $this->request->Params['message'];
	}
}