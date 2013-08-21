<?php

class FeverFullFeed {

	/**
	* Configuration parameters
	*/

	// MySQL 
	protected mysqlHost = '';
	protected mysqlUser = '';
	protected mysqlPassword = '';
	protected mysqlFeverDb = '';

	protected $configs = array(
		'spiegel.de' => "//*[@id="main"]"
	);

	/**
	* \PDO
	*/
	protected $mysqlConnection;


	public function run() {
		$this->openMySQLConnection();
		$this->processArticles()
	}


	protected function openMySQLConnection() {

	}


	protected function processArticles() {
		$items = $this->getUnreadItems();

		foreach($items as $item) {

			$url = $item['link'];
			$urlConfig = $this->getConfigForURL($url);

			if($urlConfig !== FALSE) {
				$item['description'] = $this->getItemFulltext($url, $urlConfig);
				$this->persistItem($item);
			}
		}
	}


	protected getConfigForURL($url) {
		
	}


	protected function getUnreadItems() {
		Sstatement = "SELECT * FROM `fever_items` 
						WHERE `read_on_time` = 0
						AND description NOT like '<!--FULLTEXT-->%'";
		// $this->mysqlConnection->
	}


	protected function getItemFulltext($url, $config) {

	}


	protected function persistItem($item) {
		$statementTemplate = "UPDATE `fever_items` SET `description` = '%s' WHERE `id` = %s";

		$this->mysqlConnection->exec(sprintf($statementTemplate, $item['description'], $item['id']));
	}

}

?>