<?php

/**
 * Gettext Class
 * 
 * @author Timo Witte <timo.witte@googlemail.com>
 * @copyright GPLv3
 * 
 * @version 1.0
 */
class Gettext {
	private $app;

	public static function get(Application $app) {
		$gt = new Gettext();
		$gt->setApp($app);
		return $gt;
	}

	public function setApp(Application $app) {
		$this->app = $app;
	}

	private function toLocale($str) {
		if(strpos($str, "de") !== false)
			return "de_DE.utf8";
		return "en_US.utf8";
	}

	private function findLocale($locale) {
		// if the locale is set it´s ok
		if($locale)
			return $locale;
		else {
			$httpLang = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : null;
			// if not set switch to default english
			if(!$httpLang)
				return "en_US.utf8";

			$tmp = explode(",", $httpLang);
			foreach($tmp as $lang) {
				//  element is not q rated, so we just use that one
				if(strpos($lang, ";") === false) {
					return $this->toLocale($lang);
				}

				// first q rated element is taken! (highprio should be first!)
				$lang = explode(";", $lang);
				return $this->toLocale($lang[0]);
			}

			// if anything else we don´t know that language so we switch to english
			return "en_US.utf8";
		}
	}

	public function setup($locale) {
		$locale = $this->findLocale($locale);
		$bla = setlocale(LC_MESSAGES, $locale);
		bindtextdomain("messages", dirname(dirname(__FILE__))."/locale");
		textdomain("messages");
		bind_textdomain_codeset("messages", "UTF-8");
	}
}
