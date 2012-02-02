<?php
//TODO: $_REQUEST durch rex_request erssetzen. Tabellen Updates in R5 noch nötig?

## start ouput
echo rex_view::title("XForm");
$content = '';

/*
 * Install section for module
 */
$content .= '<h2>'.rex_i18n::msg('xform_setup_install_modul').'</h2>';
$content .= '<p>'.rex_i18n::msg('xform_setup_install_modul_description').'</p>';

$searchtext = 'module:xform_basic_out';

$gm = rex_sql::factory();
$gm->setQuery('select * from '.rex::getTablePrefix().'module where output LIKE "%'.$searchtext.'%"');

$module_id = 0;
$module_name = "";

foreach($gm->getArray() as $module)
{
  $module_id = $module["id"];
  $module_name = $module["name"];
}

if (isset($_REQUEST["install"]) && $_REQUEST["install"]==1)
{
  $xform_module_name = "XForm Formbuilder";

  ## get module content
  $in = rex_file::get(rex_path::plugin("xform","setup","module/module_in.inc"));
  $out = rex_file::get(rex_path::plugin("xform","setup","module/module_out.inc"));

  $mi = rex_sql::factory();
  // $mi->debugsql = 1;
  $mi->setTable(rex::getTablePrefix().'module');
  $mi->setValue('input',$in);
  $mi->setValue('output',$out);

  ## update existing module
  if (isset($_REQUEST["module_id"]) && $module_id==$_REQUEST["module_id"])
  {
    $mi->setWhere(array('id' => $module_id));
    $mi->update();
    $content .= rex_view::success('Modul "'.$module_name.'" wurde aktualisiert');
  }
  else
  {
    $mi->setValue("name",$xform_module_name);
    $mi->insert();
    $module_id = (int) $mi->getLastId();
    $content .= rex_view::success('XForm Modul wurde angelegt unter "'.$xform_module_name.'"');
  }
}

$content .= '<ul>';
$content .= '<li><a href="index.php?page=xform&amp;subpage=setup&amp;install=1">'.rex_i18n::msg('xform_setup_install_xform_modul').'</a></li>';
		
  if ($module_id > 0)
    $content .= '<li><a href="index.php?page=xform&amp;subpage=setup&amp;install=1&amp;module_id='.$module_id.'">'.rex_i18n::msg('xform_setup_update_following_modul',htmlspecialchars($module_name)).'</a></li>'; 

$content .= '</ul>';

## Print Site
echo rex_view::contentBlock($content,'','tab');


// ------------------------------- Noch alte Tabellen vorhanden
/*
if(OOPlugIn::isAvailable('xform','manager'))
{
	
	$func = rex_request("func","string");
	$table_name = rex_request("table_name","string");
	
	$current_tables = rex_xform_manager_table::getTablesAsArray();

	$gt = rex_sql::factory();
	$gt->setQuery('show tables;');
	
	$ts = array();
	foreach($gt->getArray() as $t) {
		$ts[] = current($t);
	}
	
	$ots = array();
	if(in_array('rex_em_table',$ts)) {
	
		$gt = rex_sql::factory();
		$gt->setQuery('select * from rex_em_table');
		$gts = $gt->getArray();
		
		if(count($gts)>0) {
			foreach($gts as $gt) {
				if(!in_array($gt['table_name'],$current_tables)) {
				
					if($func == 'copyoldtables' && $gt['table_name'] == $table_name) {
					
						// fields auslesen und übernehmen
						$gfs = rex_sql::factory();
						$gfs->setQuery('select * from rex_em_field where table_name="'.mysql_real_escape_string($gt['table_name']).'"');
						
						foreach($gfs->getArray() as $gf) {
							$u = rex_sql::factory();
							// $u->debugsql = 1;
							$u->setTable('rex_xform_field');
							foreach($gf as $k => $v) {
								if($k != "id") $u->setValue($k,$v);
							}
							$u->insert();						
						}
						
						// relations auslesen und übernehmen
						$gfs = rex_sql::factory();
						// $gfs->debugsql = 1;
						$gfs->setQuery('select * from rex_em_relation where source_table="'.mysql_real_escape_string($gt['table_name']).'"');
						
						foreach($gfs->getArray() as $gf) {
							$u = rex_sql::factory();
							// $u->debugsql = 1;
							$u->setTable('rex_xform_relation');
							foreach($gf as $k => $v) {
								if($k != "id") $u->setValue($k,$v);
							}
							$u->insert();						
						}
						
						// table auslesen und übernehmen
						$gfs = rex_sql::factory();
						// $gfs->debugsql = 1;
						$gfs->setQuery('select * from rex_em_table where table_name="'.mysql_real_escape_string($gt['table_name']).'"');
						
						foreach($gfs->getArray() as $gf) {
							$u = rex_sql::factory();
							// $u->debugsql = 1;
							$u->setTable('rex_xform_table');
							foreach($gf as $k => $v) {
								if($k != "id") $u->setValue($k,$v);
							}
							$u->insert();						
						}					

						$content .= rex_view::info(rex_i18n::msg('xform_setup_table_copied',$gt['table_name']));
					
					}else
					{
						$ots[] = $gt['table_name'];
					}
				}
			}
		}
		
	}
	
	if(in_array('rex_com_table',$ts)) {
	
		$gt = rex_sql::factory();
		$gt->setQuery('select * from rex_com_table');
		$gts = $gt->getArray();
		
		if(count($gts)>0) {
			foreach($gts as $gt) {
				if(!in_array($gt['table_name'],$current_tables)) {
					if($func == 'copyoldtables' && $gt['table_name'] == $table_name) {
					
						// fields auslesen und übernehmen
						$gfs = rex_sql::factory();
						$gfs->setQuery('select * from rex_com_field where table_name="'.mysql_real_escape_string($gt['table_name']).'"');
						
						foreach($gfs->getArray() as $gf) {
							$u = rex_sql::factory();
							// $u->debugsql = 1;
							$u->setTable('rex_xform_field');
							foreach($gf as $k => $v) {
								if($k != "id") $u->setValue($k,$v);
							}
							$u->insert();						
						}
						
						// relations auslesen und übernehmen
						$gfs = rex_sql::factory();
						// $gfs->debugsql = 1;
						$gfs->setQuery('select * from rex_com_relation where source_table="'.mysql_real_escape_string($gt['table_name']).'"');
						
						foreach($gfs->getArray() as $gf) {
							$u = rex_sql::factory();
							// $u->debugsql = 1;
							$u->setTable('rex_xform_relation');
							foreach($gf as $k => $v) {
								if($k != "id") $u->setValue($k,$v);
							}
							$u->insert();						
						}
						
						// table auslesen und übernehmen
						$gfs = rex_sql::factory();
						// $gfs->debugsql = 1;
						$gfs->setQuery('select * from rex_com_table where table_name="'.mysql_real_escape_string($gt['table_name']).'"');
						
						foreach($gfs->getArray() as $gf) {
							$u = rex_sql::factory();
							// $u->debugsql = 1;
							$u->setTable('rex_xform_table');
							foreach($gf as $k => $v) {
								if($k != "id") $u->setValue($k,$v);
							}
							$u->insert();						
						}					

						$content .= rex_view::info(rex_i18n::msg('xform_setup_table_copied',$gt['table_name']));
					
					}else
					{
						$ots[] = $gt['table_name'];
					}
				}
			}
		}
	
	} */
	
	
	/*
		$gf = rex_sql::factory();
		$gf->setQuery('select * from rex_em_field');
		$content .= '<pre>'; var_dump($gf->getArray()); $content .= '</pre>';
		
	
		$gf = rex_sql::factory();
		$gf->setQuery('select * from rex_em_table');
		$content .= '<pre>'; var_dump($gf->getArray()); $content .= '</pre>';
		
		
		$gf = rex_sql::factory();
		$gf->setQuery('select * from rex_em_relation');
		$content .= '<pre>'; var_dump($gf->getArray()); $content .= '</pre>';
	*/
	
/*	if(count($ots))
	{
	
		$content .= '
		<div class="rex-addon-output">
			<h2 class="rex-hl2">'.rex_i18n::msg('xform_setup_oldtables').'</h2>
			<div class="rex-addon-content">
			<p>'.rex_i18n::msg('xform_setup_oldtables_description').'</p>
			<ul>';
	
		foreach($ots as $ot)
		{
			$content .= '<li><a href="index.php?page=xform&amp;subpage=setup&amp;func=copyoldtables&amp;table_name='.$ot.'">'.rex_i18n::msg('xform_setup_copytables',$ot).'</a></li>';
		
		}
	
		$content .= '
			</ul>	
			</div>
		</div>';
	
	
	}


} */