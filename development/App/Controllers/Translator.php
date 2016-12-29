<?php

class App_Controllers_Translator extends App_Controllers_Base
{
	const DEFAULT_ORIGINAL_TEXT = "";
	const DEFAULT_TRANSLATED_TEXT = "";

	private static $translatorCfg = array(
		'vowels'		=> 'aeiouy',						// store the vowels, or other characters that we want to break up words with
		'vowelEndings'	=> array('ay','yay','way','hey'),	// we need to store the various different endings for different dialects
		'additional'	=> 'qu',							// store a set of other rules that we can use to split words
	);
	
	private $translatorRules = array();
	
	private $consonant = '';
    private $vowel = '';
    private $other = '';
	
	public function init ()
	{
		parent::init();
		
		self::$translatorCfg = (object) self::$translatorCfg;
		
		// store a set of rules, as we create them from the config
		$this->translatorRules = (object) array(
			'consonant'	=> '/^([^' . self::$translatorCfg->vowels . self::$translatorCfg->additional . ']*)(.*)/',
			'vowel'		=> '/^([' . self::$translatorCfg->vowels . ']+)(.*)/',
			'other'		=> '/^(' . self::$translatorCfg->additional . '+)(.*)/',
		);
	}

	public function htmlSubmitAction ()
	{
		list($originalText, $translatedText) = $this->validateInputAndTryToTranslate();
		$this->redirect($this->url('Default::default', array()), 303);
	}
	
	public function jsSubmitAction ()
	{
		list($originalText, $translatedText) = $this->validateInputAndTryToTranslate();
		$this->jsonResponse(array(
			'success'	=> TRUE,
			'data'		=> array($originalText, $translatedText),
		));
	}
	
	public function validateInputAndTryToTranslate ()
	{
		$originalText = $this->getParam('original-text', ".*");
		$originalText = trim(strip_tags($originalText));
		
		if (empty($originalText)) yxcv('Please type any text to translate.');
		if (preg_match("#[a-zA-Z]+#", $originalText) !== 1) yxcv('Please type some words.');
		
		$translatedText = $this->translateToPigLatin($originalText);
		
		$this->setSessionTexts($originalText, $translatedText);
		
		return array($originalText, $translatedText);
	}
	
	private function translateToPigLatin ($str = "")
	{
        $result = '';

		// remove the punctiation to avoid things like oday?-tay
        $str = preg_replace("/[^\w\s]/", '', $str);

		// get a list of words
		$words = explode(' ', $str);

		foreach ($words as $word) {
			//xcv($word);
			// check the rules and translate
			if ($this->translatorCheckStartWithVowel($word)) {
				//xcv(1);
				$result .= preg_replace($this->translatorRules->vowel, "$1$2'" . self::$translatorCfg->vowelEndings[1], $word);
				
			} elseif ($this->translatorCheckStartWithConsonant($word)) {
				//xcv(2);
				$result .= preg_replace($this->translatorRules->consonant, "$2-$1ay", $word);
				
			} elseif ($this->translatorCheckStartWithOther($word)) {
				//xcv(3);
				$result .= preg_replace($this->translatorRules->other, "$2-$1ay", $word);
			}

			// space after each word
			$result .= " ";
		}

        return $result;
	}
	
	/**
     * checks if word starts with a consonant
     * it is necessary to know if the string starts with a character not in our configured list [$2-$1ay]
	 * @param $word string
	 * @return boolean
	 */
    private function translatorCheckStartWithConsonant ($word)
	{
        return (preg_match($this->translatorRules->consonant, $word) == 1) ? TRUE : FALSE;
    }

	/**
     * checks if word starts with a vowel [$1$2'self::$translatorCfg->vowelEndings[1]]
	 * @param $word string
	 * @return boolean
	 */
    private function translatorCheckStartWithVowel ($word)
	{
        return (preg_match($this->translatorRules->vowel, $word) == 1) ? TRUE : FALSE;
    }

	/**
	 * checks if word starts with other, eg. Qu [$2-$1ay]
	 * @param $word string
	 * @return boolean
	 */
    private function translatorCheckStartWithOther($word)
	{
        return (preg_match($this->translatorRules->other, $word) == 1) ? TRUE : FALSE;
    }
}