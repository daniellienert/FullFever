<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 Daniel Lienert <daniel@lienert.cc>, Daniel Lienert
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

class FeverFullFeed {

	/**
	* Configuration parameters
	*/

	// MySQL
	protected $mysqlHost = '';
	protected $mysqlUser = '';
	protected $mysqlPassword = '';
	protected $mysqlFeverDb = '';


	protected $feedConfiguration = array();

	/**
	* @var PDO
	*/
	protected $mysqlConnection;


	public function run() {
        $this->loadConfigs();
		$this->openMySQLConnection();
		$this->processArticles();
	}



    protected function loadConfigs() {

        $localConfiguration = array();
        $feedConfiguration = array();

        if(!file_exists(__DIR__ . '/LocalConfiguration.php')) Throw new Exception('The file LocalConfiguration.php has to be existent within the directory ' . __DIR__);
        if(!file_exists(__DIR__ . '/FeedConfiguration.php')) Throw new Exception('The file FeedConfiguration.php has to be existent within the directory ' . __DIR__);

        include __DIR__ . '/LocalConfiguration.php';
        include __DIR__ . '/FeedConfiguration.php';

        $this->mysqlHost = $localConfiguration['mysqlHost'];
        $this->mysqlUser = $localConfiguration['mysqlUser'];
        $this->mysqlPassword = $localConfiguration['mysqlPassword'];
        $this->mysqlFeverDb = $localConfiguration['mysqlFeverDb'];

        $this->feedConfiguration = $feedConfiguration;
    }



	protected function openMySQLConnection() {
        $this->mysqlConnection = new PDO('mysql:host='.$this->mysqlHost.';port=3306;dbname=' . $this->mysqlFeverDb, $this->mysqlUser, $this->mysqlPassword);
	}


	protected function processArticles() {
		$items = $this->getItemsToProcess();

		foreach($items as $item) {

			$url = $item['link'];
			$xPathQuery = $this->getConfigForURL($url);

			if($xPathQuery !== FALSE) {
                $fullText = $this->getItemFulltext($url, $xPathQuery);

                if($fullText) {
				    $item = $this->addFullTextToItem($item, $fullText);
                    $this->persistItem($item);
                }
			}
		}
	}



    /**
     * @param $item
     * @param $fullText
     */
    protected function addFullTextToItem(&$item, $fullText) {
        $description = $item['description'];
        $newDescriptionPattern = '%s<!--FULLTEXT--><hr><br/><br/>%s';

        $item['description'] = sprintf($newDescriptionPattern, $description, $fullText);
        return $item;
    }



    /**
     * @param $url
     * @return string
     */
    protected function getConfigForURL($url) {

        foreach($this->feedConfiguration as $urlRegex => $xPath) {
            if(preg_match($urlRegex, $url)) {
                echo "FOUND Config For URL $url!!";
                return $xPath;
            }
        }

        return NULL;
	}



    /**
     * @return array
     */
    protected function getItemsToProcess() {

		$statement = "SELECT * FROM `fever_items` 
						WHERE `read_on_time` = 0
						AND description NOT like '%<!--FULLTEXT-->%'";

        return $this->mysqlConnection->query($statement)->fetchAll();
	}



    /**
     * @param $url
     * @param $xPathQuery
     * @return bool|string
     */
    protected function getItemFulltext($url, $xPathQuery) {

        if($xPathQuery) {

            echo "GET FullText for " . $url . "\n $xPathQuery \n";

            $dom = new DOMDocument();
            $success = @$dom->loadHTML($this->loadHTMLData($url));

            if($success) {
                $domXPath = new DOMXPath($dom);

                $resultRows = $domXPath->query($xPathQuery);

                $itemFullText = $resultRows->item(0)->textContent;

                return $itemFullText;

            } else {
                echo "Error while parsing HTML Content for URL $url";
            }
        }

        return FALSE;
	}



    protected function loadHTMLData($url) {
        $html = file_get_contents($url);
        return $html;
    }


    /**
     * @param $item
     */
    protected function persistItem($item) {
        $query = $this->mysqlConnection->prepare('UPDATE `fever_items` SET `description` = :description WHERE `id` = :id');
        $query->execute(array('description' => $item['description'], 'id' => $item['id']));
    }

}


$feverFullFeed = new FeverFullFeed();
$feverFullFeed->run();

?>