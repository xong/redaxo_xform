<?php

/**
 * XForm 
 * @author jan.kristinus[at]redaxo[dot]de Jan Kristinus
 * @author <a href="http://www.yakamara.de">www.yakamara.de</a>
 */

$REX['PERM'][] = 'xform[]';

if(rex::isBackend() && rex::getUser())
{
	$paths = rex_config::get('xform-classes','paths');
	// TODO: immer $path auf array setzebn .. shutdown save verwenden
	if(!is_array($paths)) { 
		$paths = array();
		$paths["value"] = array();
		$paths["validate"] = array();
		$paths["action"] = array();
	}

	$paths["value"]["xform"] = rex_path::addon("xform","lib/value/");
	$paths["validate"]["xform"] = rex_path::addon("xform","lib/validate/");
	$paths["action"]["xform"] = rex_path::addon("xform","lib/action/");
	rex_config::set('xform-classes','paths',$paths);

	$pages = array();
	if(rex::getUser()->isAdmin())	{
		$pages[] = array ('description', rex_i18n::msg('xform_description'));
	}
	$this->setProperty('pages', $pages);
	rex_extension::register('PAGE_HEADER', 'rex_xform::getBackendCSS'); // rex_xform::css
}