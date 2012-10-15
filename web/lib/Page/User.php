<?php

class Page_User extends Page {
	private $db;

	public function loadPage() {
		$this->app->Auth->forceAuth();
		$this->app->Auth->forceAdmin();

		$this->db = $this->app->DB;

		if($this->params["ajax"]) {
			$this->tpl = "user_list.tpl";
			$this->t->basetpl = "blank.tpl";
		}
		else {
			$this->tpl = "user.tpl";
		}

		$users = $this->db->getAll("SELECT * FROM user".(isset($this->post['search']) ? " WHERE username LIKE '%".addslashes($this->post['search'])."%'" : ""));

		$this->t->assign(array(
			"users" => $users,
		));
	}
}
