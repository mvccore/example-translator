<?php

class App_Controllers_Default extends App_Controllers_Base
{
	public function Init () {
		parent::Init();
	}
    public function PreDispatch() {
        parent::PreDispatch();
    }
	public function DefaultAction () {
		list ($originalText, $translatedText) = $this->getSessionTexts();
		$this->view->OriginalText = $originalText;
		$this->view->TranslatedText = $translatedText;
	}
	public function NotFoundAction () {
	}
}