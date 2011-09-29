<?php

include_once 'smarty/Smarty.class.php';

/**
 * Smarty Wrapperclass to adapt to own framework
 * 
 * @author Timo Witte <timo.witte@googlemail.com>
 * @copyright unknown
 * @version 2.1
 */
class Template extends Smarty {
	private $app;

	public static function get($app) {
		$t = new Template();
		$t->setApp($app);

		// basedir
		$basedir = dirname(dirname(__FILE__));

		$t->template_dir = $basedir."/tpl";
		$t->compile_dir = $basedir."/tmp/templates_c";
		$t->cache_dir = $basedir."/tmp/cache";
		$t->basetpl = 'base.tpl';

		// $t->config_dir = '/web/www.domain.com/smarty/configs';

		return $t;
	}

	public function setApp(Application $app) {
		$this->app = $app;
	}

	public function display($tpl) {
		$basedir = dirname(dirname(__FILE__));
		$this->assign("_TEMPLATE", $tpl);

		$this->app->Log->debug("Rendering Page ".$tpl." with basetpl ".$this->basetpl." basedir is ".$basedir);

		parent::display($this->basetpl);
	}
}

?>