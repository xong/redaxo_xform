<?php

$mypage = 'email';

if(rex::isBackend() && rex::getUser())
{
	if (rex::getUser()->isAdmin()) {	}
}