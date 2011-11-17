<?php
class Action_TemplateNewRecord extends Action {
	private $db;

	public function save() {
		$this->app->Auth->forceAuth();
		$this->app->Auth->forceAdmin();
		$this->db = $this->app->DB;

		foreach($this->post['data'] as $row) {
			$sql = "UPDATE template_newrecord SET ";
			$sql.= "`value` = '".addslashes($row['value'])."' ";
			$sql.= "WHERE `key` = '".addslashes($row['id'])."'";

			$this->db->query($sql);
		}

		$this->msg(self::MSG_OK, _("Saved"));
		$this->cmd("reloadRecords(dl);");
	}
}
