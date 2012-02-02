<?php

$sql = rex_sql::factory();
$sql->setQuery('CREATE TABLE IF NOT EXISTS `'.rex::getTablePrefix().'xform_email_template` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default "",
  `mail_from` varchar(255) NOT NULL default "",
  `mail_from_name` varchar(255) NOT NULL default "",
  `subject` varchar(255) NOT NULL default "",
  `body` text NOT NULL,
  `body_html` text NOT NULL,
   `attachments` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;');

$this->setProperty('install', true);