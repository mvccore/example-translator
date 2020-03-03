<?php

namespace App\Controllers;

class Index extends Base {

	public function IndexAction () {
		$sessionTexts = $this->getSessionTexts();
		if ($this->viewEnabled) {
			$this->view->originalText = $sessionTexts->original;
			$this->view->translatedText = $sessionTexts->translated;
		}
	}

	public function NotFoundAction () {
		$this->ErrorAction();
	}

	public function ErrorAction () {
		$code = $this->response->GetCode();
		if ($code === 200) $code = 404;
		$message = $this->request->GetParam('message', 'a-zA-Z0-9_;, \\/\-\@\:\.');
		$message = preg_replace('#`([^`]*)`#', '<code>$1</code>', $message);
		$message = str_replace("\n", '<br />', $message);
		$this->view->title = "Error $code";
		$this->view->message = $message;
		$this->Render('error');
	}
}
