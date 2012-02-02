<?php

$mypage = 'email';

if(rex::isBackend() && rex::getUser())
{
	if (rex::getUser()->isAdmin())
	{
	  //$pages = $this->getProperty('pages');
	  //$pages[] = new rex_be_page("xform_email",array('page' => 'xform','subpage' => 'email'));
	  //$this->setProperty('pages',$pages);
	}
}