<?php

/**
 * Generic dispatcher Class
 * 
 * @author Timo Witte <timo.witte@googlemail.com>
 * @copyright 2009 Timo Witte
 * @licence GPLv3
 * 
 * @version 1.0
 */
class Dispatcher {
	private $app;

	public static function get(Application $app) {
		$dp = new Dispatcher();
		$dp->setApp($app);
		return $dp;
	}

	public function setApp(Application $app) {
		$this->app = $app;
	}

	public function dispatch() {
		$this->doDispatch(
			isset($_GET['p']) ? $_GET['p'] : null,
			isset($_GET['pp']) ? $_GET['pp'] : null,
			isset($_GET['a']) ? $_GET['a'] : null,
			isset($_GET['ap']) ? $_GET['ap'] : null
		);
	}

	public function doDispatch($page, $pageParams, $actions, $actionParams) {
		$ar = $this->performActions($actions, $actionParams);
		if(!$page && !$actions)
			$page = "login";
		if($page) {
			$html = $this->renderPage($page, $pageParams);
		}

		if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == "XMLHttpRequest") {
			$ar['html'] = $html;
			echo json_encode($ar);
		}
		else {
			echo $html;
		}
	}

	private function renderPage($page, $params) {
		ob_start();
		if(!$page) {
			$page = "login";
		}
		$classname = "Page_".ucfirst(strtolower($page));
		if(!class_exists($classname)) {
			throw new Exception("Page \"".$page."\" is not known");
		}
		$page = new $classname($this->app);

		$page->setParams($params);
		$page->setPostdata($_POST);
		$page->setGetdata($_GET);

		$this->app->Log->debug("Calling LoadPage on ".$classname);
		$page->LoadPage();
		$page->render();

		return ob_get_clean();

		// $this->app->Log->echoLog();
	}

	public function performActions($actions, $actionParams) {
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == "XMLHttpRequest") {
			ob_start();
		}
		if(is_array($actions)) {
			foreach($actions as $key => $action) {
				$rets[] = $this->performAction($action, $actionParams[$key]);
			}
		}
		if(isset($rets) && is_array($rets)) {
			foreach($rets as $ret) {
				if(is_array($ret['cmds'])) {
					foreach($ret['cmds'] as $cmd) {
						$fo['cmds'][] = $cmd;
					}
				}
			}
		}
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == "XMLHttpRequest") {
			$data = ob_get_clean();
			$fo['cmds'][] = "var bla = ".json_encode($data)."; console.warn(bla);";
		}
		return isset($fo) ? $fo : null;
	}

	public function performAction($action, $params) {
		list($class, $function) = explode("-", $action);
		$classname = "Action_".ucfirst($class);

		if(!class_exists($classname)) {
			throw new Exception("Action \"".$class."\" is not known");
		}
		$action = new $classname($this->app);

		$action->setParams($params);
		$action->setPostdata($_POST);
		$action->setGetdata($_GET);

		$action->$function();

		$ret = $action->getCommands();

		$this->app->Log->debug("Performing Action ".$classname.":".$function." with ".serialize($params));

		return $ret;
	}
}

?>
