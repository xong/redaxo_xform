<?php

$page = rex_request('page', 'string');
$subpage = rex_request('subpage', 'string');
$tripage = rex_request('tripage', 'string');
$table_name = rex_request('table_name', 'string');

switch($tripage)
{
	case 'table_field':
		echo rex_view::title(rex_i18n::msg('xform'), rex_addon::get('xform')->getProperty('subpages'));
		include rex_path::plugin("xform","manager","pages/table_field.inc.php");
		break;

	case 'table_import':
		echo rex_view::title(rex_i18n::msg('xform'), rex_addon::get('xform')->getProperty('subpages'));
		echo "TODO:";
		include rex_path::plugin("xform","manager","pages/table_import.inc.php");
		break;

	case 'data_edit':
		include rex_path::plugin("xform","manager","pages/data_edit.inc.php");
		break;

	default:
		echo rex_view::title(rex_i18n::msg('xform'), rex_addon::get('xform')->getProperty('subpages'));
		include rex_path::plugin("xform","manager","pages/table_edit.inc.php");

}
