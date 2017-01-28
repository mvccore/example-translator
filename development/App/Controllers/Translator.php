<?php

namespace App\Controllers;

class Translator extends Base
{
	const DEFAULT_ORIGINAL_TEXT = "";
	const DEFAULT_TRANSLATED_TEXT = "";

	private static $_translatorCfg = array(
		'vowels'		=> 'aeiouy',						// store the vowels, or other characters that we want to break up words with
		'vowelEndings'	=> array('ay','yay','way','hey'),	// we need to store the various different endings for different dialects
		'additional'	=> 'qu',							// store a set of other rules that we can use to split words
	);

	private $_translatorRules = array();
	private $_consonant = '';
    private $_vowel = '';
    private $_other = '';
	
	public function Init () {
		parent::Init();
		
		self::$_translatorCfg = (object) self::$_translatorCfg;
		
		// store a set of rules, as we create them from the config
		$this->_translatorRules = (object) array(
			'consonant'	=> '/^([^' . self::$_translatorCfg->vowels . self::$_translatorCfg->additional . ']*)(.*)/',
			'vowel'		=> '/^([' . self::$_translatorCfg->vowels . ']+)(.*)/',
			'other'		=> '/^(' . self::$_translatorCfg->additional . '+)(.*)/',
		);
	}

	public function HtmlSubmitAction () {
		$this->_validateInputAndTryToTranslate();
		self::Redirect($this->request->BasePath . '/');
	}
	
	public function JsSubmitAction () {
		$this->JsonResponse($this->_validateInputAndTryToTranslate());
	}
	
	/**
	 * Validate user input and try to translate english text into piglatin
	 * @return object
	 */
	private function _validateInputAndTryToTranslate () {
		$result = (object) array(
			'success'		=> FALSE,
			'originalText'	=> '',
			'translatedText'=> '',
			'message'		=> '',
		);
		
		$originalText = $this->GetParam('original-text', ".*");
		$originalText = trim(strip_tags($originalText));

		if (empty($originalText)) {
			$result->message = 'Please type any text to translate.';
			return $result;
		}
		if (preg_match("#[a-zA-Z]+#", $originalText) !== 1) {
			$result->message = 'Please type some words.';
			return $result;
		}
		
		$translatedText = $this->_translateToPigLatin($originalText);

		$sessionTexts = $this->getSessionTexts();
		$sessionTexts->original = $originalText;
		$sessionTexts->translated = $translatedText;

		$result->success = TRUE;
		$result->originalText = $originalText;
		$result->translatedText = $translatedText;
		return $result;
	}
	
	/**
	 * Translate validated english text into piglatin
	 * @param string $str 
	 * @return string
	 */
	private function _translateToPigLatin ($str = "") {
        $result = '';
		// remove the punctiation to avoid things like oday?-tay
        $str = preg_replace("#[^\w\s]#", '', $str);
		// get a list of words
		$words = explode(' ', $str);
		foreach ($words as $word) {
			//x($word);
			// check the rules and translate
			if ($this->_translatorCheckStartWithVowel($word)) {
				//x(1);
				$result .= preg_replace(
					$this->_translatorRules->vowel, 
					"$1$2'" . self::$_translatorCfg->vowelEndings[1], 
					$word
				);
			} elseif ($this->_translatorCheckStartWithConsonant($word)) {
				//x(2);
				$result .= preg_replace(
					$this->_translatorRules->consonant, 
					"$2-$1ay", 
					$word
				);
			} elseif ($this->_translatorCheckStartWithOther($word)) {
				//x(3);
				$result .= preg_replace(
					$this->_translatorRules->other, 
					"$2-$1ay", 
					$word
				);
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
    private function _translatorCheckStartWithConsonant ($word) {
        return (preg_match($this->_translatorRules->consonant, $word) == 1) ? TRUE : FALSE;
    }

	/**
     * checks if word starts with a vowel [$1$2'self::$translatorCfg->vowelEndings[1]]
	 * @param $word string
	 * @return boolean
	 */
    private function _translatorCheckStartWithVowel ($word) {
        return (preg_match($this->_translatorRules->vowel, $word) == 1) ? TRUE : FALSE;
    }

	/**
	 * checks if word starts with other, eg. Qu [$2-$1ay]
	 * @param $word string
	 * @return boolean
	 */
    private function _translatorCheckStartWithOther($word) {
        return (preg_match($this->_translatorRules->other, $word) == 1) ? TRUE : FALSE;
    }
}