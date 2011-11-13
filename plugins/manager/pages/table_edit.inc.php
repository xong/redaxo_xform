<?php

// ********************************************* TABLE ADD/EDIT/LIST

$table = 'rex_xform_table';
$table_field = 'rex_xform_field';
$table_relation = 'rex_xform_relation';

$func = rex_request("func","string","");
$page = rex_request("page","string","");
$subpage = rex_request("subpage","string","");
$table_id = rex_request("table_id","int");

$show_list = TRUE;

// ********************************************* FORMULAR
if( ($func == "add" || $func == "edit") && rex::getUser()->isAdmin() )
{

  $xform = new rex_xform;
  // $xform->setDebug(TRUE);
  $xform->setHiddenField("page",$page);
  $xform->setHiddenField("subpage",$subpage);
  $xform->setHiddenField("func",$func);
  $xform->setActionField("showtext",array("",rex_i18n::msg("xform_manager_table_entry_saved")));
  $xform->setObjectparams("main_table",$table);

  $xform->setValueField("text",array("prio",rex_i18n::msg("xform_manager_table_prio")));

  if($func == "edit")
  {
    $xform->setValueField("showvalue",array("table_name","Name"));
    $xform->setHiddenField("table_id",$table_id);
    $xform->setActionField("db",array($table,"id=$table_id"));
    $xform->setObjectparams("main_id",$table_id);
    $xform->setObjectparams("main_where","id=$table_id");
    $xform->setObjectparams('getdata',true); // Datein vorher auslesen
  }elseif($func == "add")
  {
    $xform->setValueField("text",array("table_name",rex_i18n::msg("xform_manager_table_name")));
    $xform->setValidateField("empty",array("table_name",rex_i18n::msg("xform_manager_table_enter_name")));
    $xform->setValidateField("preg_match",array("table_name","/([a-z\_])*/",rex_i18n::msg("xform_manager_table_enter_specialchars")));
    $xform->setValidateField("customfunction",array("table_name","rex_xform_manager_checkLabelInTable","",rex_i18n::msg("xform_manager_table_exists")));
    $xform->setActionField("wrapper_value",array('table_name','###value###')); // Tablename
    $xform->setActionField("db",array($table));
  }

  $xform->setValueField("text",array("name",rex_i18n::msg("xform_manager_name")));
  $xform->setValidateField("empty",array("name",rex_i18n::msg("xform_manager_table_enter_name")));

  $xform->setValueField("textarea",array("description",rex_i18n::msg("xform_manager_table_description")));
  $xform->setValueField("checkbox",array("status",rex_i18n::msg("tbl_active")));
  // $xform->setValueField("fieldset",array("fs-list","Liste"));
  $xform->setValueField("text",array("list_amount",rex_i18n::msg("xform_manager_entries_per_page"),"50"));
  $xform->setValueField("checkbox",array("search",rex_i18n::msg("xform_manager_search_active")));
  $xform->setValidateField("type",array("list_amount","int",rex_i18n::msg("xform_manager_enter_number")));

  $xform->setValueField("checkbox",array("hidden",rex_i18n::msg("xform_manager_table_hide")));
  $xform->setValueField("checkbox",array("export",rex_i18n::msg("xform_manager_table_allow_export")));
  $xform->setValueField("checkbox",array("import",rex_i18n::msg("xform_manager_table_allow_import")));

  $form = $xform->getForm();

  if($xform->objparams["form_show"])
  {
    if($func == "edit"){
      echo '<div class="rex-area"><h3 class="rex-hl2">'.rex_i18n::msg("xform_manager_edit_table").'</h3><div class="rex-area-content">';
    }else{
      echo '<div class="rex-area"><h3 class="rex-hl2">'.rex_i18n::msg("xform_manager_add_table").'</h3><div class="rex-area-content">';
    }
    echo $form;
    echo '</div></div>';
    echo '<br />&nbsp;<br /><table cellpadding="5" class="rex-table"><tr><td><a href="index.php?page='.$page.'&amp;subpage='.$subpage.'"><b>&laquo; '.rex_i18n::msg('xform_back_to_overview').'</b></a></td></tr></table>';
    $show_list = FALSE;
  }else
  {
    if($func == "edit"){
      echo rex_info(rex_i18n::msg("xform_manager_table_updated"));
    }elseif($func == "add") {

      $table_name = $xform->objparams["value_pool"]["sql"]["table_name"];
      $t = new rex_xform_manager();
      $t->setFilterTable($table_name);
      $t->generateAll();
      echo rex_info(rex_i18n::msg("xform_manager_table_added"));
    }
  }

}





// ********************************************* LOESCHEN
if($func == "delete" && rex::getUser()->isAdmin()){

  // TODO:
  // querloeschen - bei be_xform_relation, muss die zieltabelle auch bearbeitet werden + die relationentabelle auch geloescht werden

  if($t = rex_xform_manager_table::get($table_id)) 
  {

	// $query = "delete from $table where id='".$table_id."' ";
	$delsql = rex_sql::factory();
	// $delsql->debugsql = 1;
	$delsql->setQuery('delete from '.($table).' where id = ?', array($table_id));
	$delsql->setQuery('delete from '.($table_field).' where table_name = ? ',array($t->getTableName()));
	$delsql->setQuery('delete from '.($table_relation).' where source_table = ? OR target_table = ? ',array($t->getTableName(),$t->getTableName()));
	$delsql->setQuery('drop table '.($t->getTableName()));

	$func = "";
	echo rex_info(rex_i18n::msg("xform_manager_table_deleted"));

  }

}





// ********************************************* LISTE
if($show_list && rex::getUser()->isAdmin()){

  // formatting func fuer status col
  function rex_xform_status_col($params)
  {
    global $I18N;
    $list = $params["list"];
    return $list->getValue("status") == 1 ? '<span style="color:green;">'.rex_i18n::msg("xform_tbl_active").'</span>' : '<span style="color:red;">'.rex_i18n::msg("xform_tbl_inactive").'</span>';
  }

  echo "<table cellpadding=5 class=rex-table><tr><td>
		<a href=index.php?page=".$page."&subpage=".$subpage."&func=add><b>+ ".rex_i18n::msg("xform_manager_table_add")."</b></a>
		<!-- |  <a href=index.php?page=".$page."&subpage=".$subpage."&func=table_import><b>".rex_i18n::msg("xform_manager_table_import")."</b></a> -->
		
		</td></tr></table><br />";

  $sql = "select * from $table order by prio,table_name";

  $list = rex_list::factory($sql,30);

  // $list->setColumnParams("id", array("table_id"=>"###id###","func"=>"edit"));
  $list->removeColumn("id");
  $list->removeColumn("list_amount");
  $list->removeColumn("search");
  $list->removeColumn("hidden");
  $list->removeColumn("export");
  $list->removeColumn("import");
  // $list->removeColumn("label");
  // $list->removeColumn("prio");
  $list->removeColumn("description");


  $list->setColumnFormat('status', 'custom', 'rex_xform_status_col');
  $list->setColumnParams("name", array("table_id"=>"###id###","func"=>"edit"));

  $list->addColumn(rex_i18n::msg("edit"),rex_i18n::msg("editfield"));
  $list->setColumnParams(rex_i18n::msg("edit"), array("subpage"=>"manager", "tripage"=>"table_field", "table_name"=>"###table_name###"));

  $list->addColumn(rex_i18n::msg("delete"),rex_i18n::msg("delete"));
  $list->setColumnParams(rex_i18n::msg("delete"), array("table_id"=>"###id###","func"=>"delete"));

  echo $list->get();
}


// ********************************************* LISTE OF TABLES TO EDIT FOR NOt ADMINS

if(!rex::getUser()->isAdmin())
{
  echo '<div class="rex-addon-output">';
  echo '<h2 class="rex-hl2">'.rex_i18n::msg("xform_table_overview").'</h2>';
  echo '<div class="rex-addon-content"><ul>';

  $t = new rex_xform_manager();
  $tables = $t->getTables();
  if(is_array($tables)) {
    foreach($tables as $table) {
      $table_perm = 'xform[table:'.$table["table_name"].']';
      if($table['status'] == 1 && $table['hidden'] != 1 && rex::getUser() && (rex::getUser()->isAdmin() || rex::getUser()->hasPerm($table_perm))) {
        echo '<li><a href="index.php?page=xform&subpage=manager&tripage=data_edit&table_name='.$table['table_name'].'">'.$table['name'].'</a></li>';
      }
    }
  }

  echo '</ul></div>';
  echo '</div>';
}
