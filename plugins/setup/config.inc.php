<?php
if(rex::isBackend() && rex::getUser() && rex::getUser()->isAdmin())
{
  $pages = $this->getProperty('pages');
  $pages[] = array ('description', rex_i18n::msg('xform_setup'));
  $this->setProperty('pages', $pages);
}

