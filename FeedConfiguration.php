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

/**
 * Feed Configuration Array:
 *  Key:    A Regex that has to match against the articles URL
 *  Value:  Array with the following keys:
 *     xPath: A XPath, defining the HTML section to retrieve
 *     keepAbstract: Keep the abstract and add the fullText or replace the abstract completely [Default: FALSE]
 *     replace: array with from and to value to replace strings within the fulltext. First value represents search, second the replace value
 */

$feedConfiguration = array(
    '/www.spiegel.de/' => array(
        'xPath' => '//*[@id="js-article-column"]',
        'keepAbstract' => TRUE,
        'replace' => array()
    ),
    '/www.maclife.de/' => array(
        'xPath' => '//*[@id="center_left_content"]/div[1]/div/div[3]'
    ),
    '/www.ka-news.de/' => array(
        'xPath' => '//*[@id="artdetail_text"]',
        'keepAbstract' => TRUE,
    ),
    '/www.heise.de/' => array(
        'xPath' => '//*[@id="mitte_news"]/article/div[2]',
        'keepAbstract' => FALSE,
    ),
);