<?php
$content = '';

$SF = true;

$table = rex::getTablePrefix()."xform_email_template";
$bezeichner = rex_i18n::msg("xform_email_template");
$csuchfelder = array("name","mail_from","mail_subject","body");

$func = rex_request("func","string","");
$template_id = rex_request("template_id","int");

/*
 * adding/edit email templates
 */
if($func == "add" || $func == "edit")
{
  $content .= '<h1>'.rex_i18n::msg('xform_email_add_template').'</h1>';
  $content .= '<p>Durch folgende Markierungen <b>###field###</b> kann man die in den Formularen eingegebenen Felder hier im E-Mail Template verwenden. Weiterhin sind
	alle REDAXO Variablen wie $REX["SERVER"] als <b>###REX_SERVER###</b> verwendbar. Urlencoded, z.b. für Links, bekommt man diese Werte über <b>+++field+++</b></p>';

  $form = rex_form::factory(rex::getTablePrefix()."xform_email_template", 'Template', 'id='. $template_id);
  
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
}
else
{
  $content .= '<h1>'.rex_i18n::msg('xform_email_templates').'</h1>';
  
  /*
   * remove email templates
   */
  if($func == "delete")
  {
    $query = "delete from $table where id='".$template_id."' ";
    $delsql = rex_sql::factory();
    $delsql->debugsql=0;
    $delsql->setQuery($query);
  
    $content .= rex_view::warning(rex_i18n::msg('xform_email_info_template_deleted'));
  }
  
  /*
   * list email templates
   */
  $sql = "select * from $table ";
  
  $list = rex_list::factory($sql);
  $list->setCaption(rex_i18n::msg('xform_email_header_template_caption'));
  $list->addTableAttribute('summary', rex_i18n::msg('xform_email_header_template_summary'));

  $list->addTableColumnGroup(array(40, 40, '*', 153, 153));

  $thIcon = '<a class="rex-ic-template rex-ic-add" href="'. $list->getUrl(array('page'=>$page, 'subpage'=>$subpage, 'func' => 'add')) .'"'. rex::getAccesskey(rex_i18n::msg('xform_email_create_template'), 'add') .'>'.rex_i18n::msg('xform_email_create_template').'</a>';
  $tdIcon = 'ed';
  $list->addColumn($thIcon, $tdIcon, 0, array('<th class="rex-icon">###VALUE###</th>','<td class="rex-icon">###VALUE###</td>'));
  $list->setColumnParams($tdIcon, array('page'=>$page, 'subpage'=>$subpage, 'func' => 'edit', 'template_id' => '###id###'));

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
}

## Print Site
echo rex_view::title("XForm");
echo rex_view::contentBlock($content,'','tab');
?>