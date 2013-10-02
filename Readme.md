# What does it do

The script retrieves the fulltext from the articles page and modifies the "description" field of the fever database, where fever store the article data.

# Installation

The installation is very simple. Basically there are three steps to get it running:

1. Clone the latest version of the script via git or download it from github (https://github.com/daniellienert/FullFever).
2. Copy the file LocalConfiguration.Sample.php to LocalConfiguration.php and modify the MySQL Server credentials to your needs.
3. Run the script from the console or add it to a cron task right after your call the fever cron task.


No changes on the Fever application has to be done.

# Config your feeds

The feeds are configured within the file FeedConfiguration.php. The file consists of an array with one entry per regular expression pattern matching the pages URL.

## Example

```php
$feedConfiguration = array(
    '/www.spiegel.de/' => array(
        'xPath' => '//*[@id="js-article-column"]',
        'keepAbstract' => TRUE,
        'replace' => array()
    )
);
```

## Config Values

| Key           | Description                                       | Default   |
| ---           | ---                                               | ---       |
| xPath         | A XPath, defining the HTML section to retrieve    | Mandatory | 
|keepAbstract   | Keep the abstract and add the fullText or replace the abstract completely | FALSE |
|replace        |array with from and to value to replace strings within the fulltext. First value represents search, second the replace value. | NONE |



# Links

* [Article on my blog](http://daniel.lienert.cc/blog/blog-post/2013/08/full-feeds-for-the-fever-rss-agregator/)