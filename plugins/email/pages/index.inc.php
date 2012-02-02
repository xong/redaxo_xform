<?php
$content = '';

$SF = true;

$table = rex::getTablePrefix()."xform_email_template";
$bezeichner = rex_i18n::msg("xform_email_template");
$csuchfelder = array("name","mail_from","mail_subject","body");

$func = rex_request("func","string","");
$template_id = rex_request("template_id","int");


//------------------------------ Hinzufügen

if($func == "add" || $func == "edit")
{
	
	$content .= '<div class="rex-toolbar"><div class="rex-toolbar-content">';
	$content .= '<p><a class="rex-back" href="index.php?page='.$page.'&amp;subpage='.$subpage.'">translate:xform_back_to_overview</a></p>';
	$content .= '</div></div>';
	
	$content .= '<div class="rex-content-block"><div class="rex-content-block-content">
	<p>Durch folgende Markierungen <b>###field###</b> kann man die in den Formularen eingegebenen Felder hier im E-Mail Template verwenden. Weiterhin sind
	alle REDAXO Variablen wie $REX["SERVER"] als <b>###REX_SERVER###</b> verwendbar. Urlencoded, z.b. für Links, bekommt man diese Werte über <b>+++field+++</b></p>
	</div></div>';

	$content .= '<div class="rex-addon-output">';

	$form = new rex_form(rex::getTablePrefix()."xform_email_template", 'Template', 'id='. $template_id);
	if($func == 'edit')
		$form->addParam('template_id', $template_id);
		
	$field = &$form->addTextField('name');
	$field->setLabel(rex_i18n::msg("xform_email_key"));
	
	$field = &$form->addTextField('mail_from');
	$field->setLabel(rex_i18n::msg("xform_email_from"));
	
	$field = &$form->addTextField('mail_from_name');
	$field->setLabel(rex_i18n::msg("xform_email_from_name"));
	    
	$field = &$form->addTextField('subject');
	$field->setLabel(rex_i18n::msg("xform_email_subject"));
	
	$field = &$form->addTextareaField('body');
	$field->setLabel(rex_i18n::msg("xform_email_body"));

	$field = &$form->addTextareaField('body_html');
	$field->setLabel(rex_i18n::msg("xform_email_body_html"));
	
	$field = &$form->addMedialistField('attachments');
	$field->setLabel(rex_i18n::msg("xform_email_attachments"));
	
	$content .=  $form->get();
	$content .= '</div>';
}

//------------------------------> Löschen
if($func == "delete")
{
	$query = "delete from $table where id='".$template_id."' ";
	$delsql = rex_sql::factory();
	$delsql->debugsql=0;
	$delsql->setQuery($query);
	$func = "";
	
	$content .= rex_view::info(rex_i18n::msg('xform_email_info_template_deleted'));
	
}



//------------------------------> Liste
if($func == "")
{

	$content .= '<div class="rex-addon-output-v2">';
	/** Suche  **/
	$add_sql = "";
	$link	= "";
	
	$sql = "select * from $table ".$add_sql;
	
	$list = rex_list::factory($sql);
	$list->setCaption(rex_i18n::msg('xform_email_header_template_caption'));
	$list->addTableAttribute('summary', rex_i18n::msg('xform_email_header_template_summary'));
	
	$list->addTableColumnGroup(array(40, 40, '*', 153, 153));
	
	$img = '<img src="media/template.gif" alt="###name###" title="###name###" />';
	$imgAdd = '<img src="media/template_plus.gif" alt="'.rex_i18n::msg('xform_create_template').'" title="'.rex_i18n::msg('xform_email_create_template').'" />';
	$imgHeader = '<a href="'. $list->getUrl(array('page'=>$page, 'subpage'=>$subpage, 'func' => 'add')) .'">'. $imgAdd .'</a>';
	$list->addColumn($imgHeader, $img, 0, array('<th class="rex-icon">###VALUE###</th>','<td class="rex-icon">###VALUE###</td>'));
	$list->setColumnParams($imgHeader, array('page'=>$page, 'subpage'=>$subpage, 'func' => 'edit', 'template_id' => '###id###'));
	
	$list->setColumnLabel('id', 'ID');
	$list->setColumnLayout('id',  array('<th class="rex-small">###VALUE###</th>','<td class="rex-small">###VALUE###</td>'));
	
	$list->setColumnLabel('name', rex_i18n::msg('xform_email_header_template_description'));
	$list->setColumnParams('name', array('page'=>$page, 'subpage'=>$subpage, 'func' => 'edit', 'template_id' => '###id###'));
	
	$list->setColumnLabel('mail_from', rex_i18n::msg('xform_email_header_template_mail_from'));
	$list->setColumnLabel('mail_from_name', rex_i18n::msg('xform_email_header_template_mail_from_name'));
	$list->setColumnLabel('subject', rex_i18n::msg('xform_email_header_template_subject'));
	
	$list->removeColumn('body');
	$list->removeColumn('body_html');
	$list->removeColumn('attachments');
	
	$list->addColumn(rex_i18n::msg('xform_email_header_template_functions'), rex_i18n::msg('xform_delete_template'));
	$list->setColumnParams(rex_i18n::msg('xform_email_header_template_functions'), array('page'=>$page, 'subpage'=>$subpage, 'func' => 'delete', 'template_id' => '###id###'));
	$list->addLinkAttribute(rex_i18n::msg('xform_email_header_template_functions'), 'onclick', 'return confirm(\''.rex_i18n::msg('delete').' ?\')');
	
	$list->setNoRowsMessage(rex_i18n::msg('xform_email_templates_not_found'));
	
	$content .= $list->get();
	
	$content .= '</div>';
}

## Print Site
echo rex_view::title("XForm");
echo rex_view::contentBlock($content,'','tab');
?>