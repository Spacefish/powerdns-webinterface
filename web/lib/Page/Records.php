<?php

class Page_Records extends Page {
	public function LoadPage() {
		$this->app->Auth->forceAuth();
		$this->app->Auth->forceAdmin();

		$mRecords = new Model_DomainRecords($this->app);

		if($this->params['ajax']) {
			$this->tpl = "records_list.tpl";
			$this->t->basetpl = 'blank.tpl';
			$this->t->assign("records", $mRecords->searchRecordlist($this->post['search'], $this->post['col'], $this->post['dir']));
		}
		else {
			$this->tpl = "records.tpl";
			$this->t->assign("records", $mRecords->getRecordlist("d.name", "ASC"));
		}
	}
}

?>