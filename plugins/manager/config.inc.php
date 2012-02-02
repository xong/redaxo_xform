<?php

$mypage = 'manager';


if(rex::isBackend() && is_object(rex::getUser()))
{
    ## register paths
	rex_xform::addPath('manager', 'value', rex_path::plugin("xform","manager","lib/value/"));
	rex_xform::addPath('manager', 'action', rex_path::plugin("xform","manager","lib/action/"));

	if(rex::getUser()->isAdmin()) {

    }

	$be_pages = $this->getProperty('pages');

	$t = new rex_xform_manager();
	$tables = $t->getTables();
	if(is_array($tables))
	{
		foreach($tables as $table)
		{

			// Recht um das AddOn ueberhaupt einsehen zu koennen
			$table_perm = rex_xform_manager_table::getTablePermName($table["table_name"]);
			rex_perm::register($table_perm);

			// check active-state and permissions
			if($table['status'] == 1 && $table['hidden'] != 1 && rex::getUser() && (rex::getUser()->isAdmin() || rex::getUser()->hasPerm($table_perm)))
			{

				$k = "xform_manager_".$table["table_name"];
				
				$be_pages[$k] = new rex_be_page("XTable: ".$table['name'], 
					array(
						'page' => 'xform', 
						'subpage' => 'manager', 
						'tripage' => 'data_edit', 
						'table_name' => $table['table_name']
						)
					);
				$be_pages[$k]->setHref('index.php?page=xform&subpage=manager&tripage=data_edit&table_name='.$table['table_name']);
			
			}
		}
		$this->setProperty('pages', $be_pages);
		

	}
	
	// $pages[] = array ('description', rex_i18n::msg('xform_description'));
	// $this->setProperty('pages', $be_pages);

	// hack - if data edit, then deactivate xform navigation 
	if(rex_request("tripage","string") == "data_edit")
	{
		$REX['ADDON']['navigation']['xform'] = array(
	      'activateCondition' => array('page' => 'xformmm'),
	      'hidden' => FALSE
		);
	}


}

