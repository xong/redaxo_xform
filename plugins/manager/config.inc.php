<?php
## register paths
rex_xform::addPath('manager', 'value', rex_path::plugin("xform","manager","lib/value/"));
rex_xform::addPath('manager', 'action', rex_path::plugin("xform","manager","lib/action/"));

if(rex::isBackend() && is_object(rex::getUser()))
{
  $tables = new rex_xform_manager();
  $tables = $tables->getTables();
  
  if(is_array($tables))
  {
    foreach($tables as $table)
    {
      ## check addon-permission
      $table_perm = rex_xform_manager_table::getTablePermName($table["table_name"]);
      rex_perm::register($table_perm);
  
      ## check active-state and permissions
      if($table['status'] == 1 && $table['hidden'] != 1 && rex::getUser() && (rex::getUser()->isAdmin() || rex::getUser()->hasPerm($table_perm)))
      {
        $be_page = new rex_be_page($table['name'],
        array(  'page' => 'xform',
                'subpage' => 'manager',
                'tripage' => 'data_edit',
                'table_name' => $table['table_name']
                )
        );
   
        $be_page->setHref('index.php?page=xform&subpage=manager&tripage=data_edit&table_name='.$table['table_name']);
        $subpages[] = new rex_be_page_main('tables', $be_page);
      }
    }
    
    $this->setProperty('pages', $subpages);
        
    ## deactivate xform menu in be_navigation
    if(rex_request("tripage","string") == "data_edit")
      $this->getAddon()->setProperty('navigation', array('activateCondition' => array('page' => 'xformmm'),'hidden' => FALSE));
  }
}
