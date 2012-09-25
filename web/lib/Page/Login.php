<?php

class Page_Login extends Page {
	public function LoadPage() {
		$this->tpl = "login.tpl";
		$fail = false;

		if($this->app->Auth->isAuthed()) {
			header("Location: ?p=overview");
			exit;
		}

		if(isset($this->post['username'])) {
			if($this->app->Auth->auth($this->post['username'], $this->post['password'])) {
				if($this->params['redirect_page']) {
					$params = unserialize($this->params['redirect_params']);
					$redirect = "?p=".$this->params['redirect_page'];
					if(is_array($params)) {
						foreach($params as $param_key => $param_val) {
							$redirect.="&pp[".$param_key."]=".$param_val;
						}
					}
				}
				else {
					$redirect = "?p=overview";
				}
				header("Location: ".$redirect);
				exit;
			}
			else {
				$fail = true;
			}
		}
		
		$this->t->assign(array(
			"username" => addslashes(isset($this->post['username']) ? $this->post['username'] : null),
			"fail" => $fail
		));
	}
}