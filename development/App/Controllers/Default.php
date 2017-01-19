<?php

class App_Controllers_Default extends App_Controllers_Base
{
	public function DefaultAction () {
		$sessionTexts = $this->getSessionTexts();
		$this->view->OriginalText = $sessionTexts->original;
		$this->view->TranslatedText = $sessionTexts->translated;
	}
	public function NotFoundAction () {
		$this->view->Title = "Error 404 - requested page not found.";
		$this->view->Message = $this->request->Params['message'];
	}
}