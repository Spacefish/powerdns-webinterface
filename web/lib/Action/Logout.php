<?php

class Action_Logout extends Action {
	public function logout() {
		$this->app->Auth->logout();
		header("Location: ?p=login");
		exit;
	}
}

?>
