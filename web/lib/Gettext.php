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

	public function setup($locale) {
		setlocale(LC_MESSAGES, $locale);
		bindtextdomain("messages", dirname(dirname(__FILE__))."/locale");
		textdomain("messages");
		bind_textdomain_codeset("messages", "UTF-8");
	}
}

?>
