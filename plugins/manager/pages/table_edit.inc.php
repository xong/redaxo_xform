<?php

// ********************************************* TABLE ADD/EDIT/LIST

$table = 'rex_xform_table';
$table_field = 'rex_xform_field';

$bezeichner = "Tabelle";

$func = rex_request("func","string","");
$page = rex_request("page","string","");
$subpage = rex_request("subpage","string","");
$table_id = rex_request("table_id","int");

$show_list = TRUE;

if($func == "update" && $REX['USER']->isAdmin())
{
	$t = new rex_xform_manager();
	$t->generateAll();
	echo rex_info("Tabelle und Felder wurden erstellt und/oder aktualisiert");
	$func = "";
}

// ********************************************* FORMULAR
if( ($func == "add" || $func == "edit") && $REX['USER']->isAdmin() )
{
	
	$xform = new rex_xform;
	// $xform->setDebug(TRUE);
	$xform->setHiddenField("page",$page);
	$xform->setHiddenField("subpage",$subpage);
	$xform->setHiddenField("func",$func);
	$xform->setActionField("showtext",array("","Vielen Dank fuer die Eintragung"));
	$xform->setObjectparams("main_table",$table); // für db speicherungen und unique abfragen

	$xform->setValueField("text",array("prio","Priorit&auml;t"));
	
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
		$xform->setValueField("text",array("table_name","Name"));
	    $xform->setValidateField("notEmpty",array("table_name","Bitte tragen Sie den Tabellenname ein"));
	    $xform->setValidateField("preg_match",array("table_name","/([a-z\_])*/","Bitte tragen Sie beim Tabellenname nur Buchstaben ein"));
	    $xform->setValidateField("customfunction",array("table_name","rex_xform_manage_checkLabelInTable","","Dieser Tabellenname ist bereits vorhanden"));
		$xform->setActionField("wrapper_value",array('table_name','###value###')); // Tablename
		$xform->setActionField("db",array($table));
	}
	
	$xform->setValueField("text",array("name","Bezeichnung"));
	$xform->setValueField("textarea",array("description","Beschreibung"));
	$xform->setValueField("checkbox",array("status","Aktiv"));
	// $xform->setValueField("fieldset",array("fs-list","Liste"));
	$xform->setValueField("text",array("list_amount","Datens&auml;tze pro Seite","50"));
	$xform->setValueField("checkbox",array("search","Suche aktiv"));
	$xform->setValidateField("type",array("list_amount","int","Bitte geben Sie eine Zahl f&uuml;r die Datens&auml;tze pro Seite ein"));
	
	$xform->setValueField("checkbox",array("hidden","In Navigation versteckt"));
	$xform->setValueField("checkbox",array("export","Export der Daten erlauben"));
	$xform->setValueField("checkbox",array("import","Import von Daten erlauben"));
  
	$xform->setValidateField("empty",array("name","Bitte den Namen eingeben"));
	$form = $xform->getForm();
	
	if($xform->objparams["form_show"])
	{	
		if($func == "edit")
			echo '<div class="rex-area"><h3 class="rex-hl2">Tabelle editieren</h3><div class="rex-area-content">';
		else
			echo '<div class="rex-area"><h3 class="rex-hl2">Tabelle hinzufügen</h3><div class="rex-area-content">';
		echo $form;
		echo '</div></div>';
		echo '<br />&nbsp;<br /><table cellpadding="5" class="rex-table"><tr><td><a href="index.php?page='.$page.'&amp;subpage='.$subpage.'"><b>&laquo; '.$I18N->msg('xform_back_to_overview').'</b></a></td></tr></table>';
		$show_list = FALSE;
	}else
	{
		if($func == "edit")
			echo rex_info("Vielen Dank f&uuml;r die Aktualisierung.");
		elseif($func == "add")
			echo rex_info("Vielen Dank f&uuml;r den Eintrag.");
	}
	
}





// ********************************************* LOESCHEN
if($func == "delete" && $REX['USER']->isAdmin()){

	// TODO:
	// querloeschen - bei be_xform_relation, muss die zieltabelle auch bearbeitet werden + die relationentabelle auch geloescht werden

	$query = "delete from $table where id='".$table_id."' ";
	$delsql = new rex_sql;
	// $delsql->debugsql=1;
	$delsql->setQuery($query);
	$query = "delete from $table_field where table_id='".$table_id."' ";
	$delsql->setQuery($query);
	
	$func = "";
	echo rex_info($bezeichner." wurde gel&ouml;scht");
}





// ********************************************* LISTE
if($show_list && $REX['USER']->isAdmin()){
  
	// formatting func fuer status col
	function rex_xform_status_col($params)
	{
		global $I18N;
		$list = $params["list"];
		return $list->getValue("status") == 1 ? '<span style="color:green;">'.$I18N->msg("xform_tbl_active").'</span>' : '<span style="color:red;">'.$I18N->msg("xform_tbl_inactive").'</span>'; 
	}
  
	echo "<table cellpadding=5 class=rex-table><tr><td>
		<a href=index.php?page=".$page."&subpage=".$subpage."&func=add><b>+ $bezeichner anlegen</b></a>
		 | 
		<a href=index.php?page=".$page."&subpage=".$subpage."&func=update><b>Tabellen und Felder updaten</b></a>
		<!-- |  <a href=index.php?page=".$page."&subpage=".$subpage."&func=table_import><b>Tabelle importieren</b></a> -->
		
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
	
	$list->addColumn($I18N->msg("edit"),$I18N->msg("editfield"));
	$list->setColumnParams($I18N->msg("edit"), array("subpage"=>"manager", "tripage"=>"table_field", "table_name"=>"###table_name###"));

	$list->addColumn($I18N->msg("delete"),$I18N->msg("delete"));
	$list->setColumnParams($I18N->msg("delete"), array("table_id"=>"###id###","func"=>"delete"));

	echo $list->get();
}


// ********************************************* LISTE OF TABLES TO EDIT FOR NOt ADMINS

if(!$REX['USER']->isAdmin())
{
	echo '<div class="rex-addon-output">';
	echo '<h2 class="rex-hl2">'.$I18N->msg("xform_table_overview").'</h2>';
	echo '<div class="rex-addon-content"><ul>';

	$t = new rex_xform_manager();
	$tables = $t->getTables();
	if(is_array($tables)) {
		foreach($tables as $table) {
			$table_perm = 'xform[table:'.$table["table_name"].']';
			if($table['status'] == 1 && $table['hidden'] != 1 && $REX['USER'] && ($REX['USER']->isAdmin() || $REX['USER']->hasPerm($table_perm))) {
				echo '<li><a href="index.php?page=xform&subpage=manager&tripage=data_edit&table_name='.$table['table_name'].'">'.$table['name'].'</a></li>';
			}
		}
	}

	echo '</ul></div>';
	echo '</div>';
}
