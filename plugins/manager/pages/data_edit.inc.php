<?php

$table_name = rex_request("table_name","string");

if(
	$table_name != "" 
	&& rex::getUser() 
	&& (rex::getUser()->isAdmin() || rex::getUser()->hasPerm(rex_xform_manager_table::getTablePermName($table_name)) )
	)
{

	$page = new rex_xform_manager();
	$page->setFilterTable($table_name);
	$page->setLinkVars(	array('page'=>'xform', 'subpage'=>'manager', 'tripage'=>'data_edit', 'table_name'=>$table_name)	);
	echo $page->getDataPage();

}

?>