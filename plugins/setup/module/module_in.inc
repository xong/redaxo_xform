<?php

// module:xform_rexdev_in
// v0.2
// --------------------------------------------------------------------------------

// DEBUG SELECT
////////////////////////////////////////////////////////////////////////////////
$dbg = new rex_select();
$dbg->setName('VALUE[7]');
$dbg->setSize(1);
$dbg->addOption('inakiv','0');
$dbg->addOption('Aktiv','1');
$dbg->setSelected('REX_VALUE[7]');
$dbg = $dbg->get();


// TABLE SELECT
////////////////////////////////////////////////////////////////////////////////
$gc = rex_sql::factory();
$gc->setQuery('SHOW TABLES');
$tables = $gc->getArray();
$ts = new rex_select;
$ts->setName('VALUE[8]');
$ts->setSize(1);
$ts->addOption('Keine Tabelle ausgewählt', '');
foreach ($tables as $key => $value)
{
  $ts->addOption(current($value), current($value));
}
$ts->setSelected('REX_VALUE[8]');
$ts = $ts->get();


// PLACEHOLDERS
////////////////////////////////////////////////////////////////////////////////
$xform = new rex_xform;
$form_data = 'REX_VALUE[3]';
$form_data = trim(str_replace('<br />','',rex_xform::unhtmlentities($form_data)));
$xform->setFormData($form_data);
$xform->setRedaxoVars(REX_ARTICLE_ID,REX_CLANG_ID);
$placeholders = '';
$ignores = array('html','validate','action');
foreach($xform->objparams['form_elements'] as $e)
{
  if(!in_array($e[0],$ignores))
  {
    $placeholders .= '<span>###'.$e[1].'###</span> ';
  }
}


// OTHERS
////////////////////////////////////////////////////////////////////////////////
$rows = count(explode("\r",'REX_VALUE[3]'));
$db_display = $mail_display = '';
$sel = 'REX_VALUE[1]';
if($sel=='' || $sel==1){
  $db_display = 'style="display:none"';
}
if($sel=='' || $sel==0){
  $mail_display = 'style="display:none"';
}

?>

<style type="text/css" media="screen">
<!--
  /*REXDEV MODUL STYLE*/
  #rexdev                       {margin:0;padding:0;line-height:25px;}
  #rexdev fieldset              {background:#E4E1D1;margin:-20px 0 0 0;padding: 4px 10px 10px 10px;-moz-border-radius:6px;-webkit-border-radius:6px;border-radius:6px;}
  #rexdev fieldset legend       {display:block !important;position:relative !important;height:auto !important;top:0 !important;left:0 !important;width:100% !important;margin:0 0 0 0 !important;padding:30px 0 0 0px !important;background:transparent !important;border-bottom:1px solid #B1B1B1 !important;color:gray;font-size:14px;font-weight:bold;}
  #rexdev fieldset legend em    {font-size:10px;font-weight:normal;font-style:normal;}
  #rexdev fieldset strong.label,
  #rexdev fieldset label        {display:inline-block !important;width:150px !important;font-weight:bold;}
  #rexdev fieldset label span   {font-weight:normal;}
  #rexdev input,
  #rexdev select                {width:460px;border:auto;margin:0 !important;padding:0 !important;}
  #rexdev input[type="checkbox"]{width:auto;}
  #rexdev hr                    {border:0;height:0;margin:4px 0 4px 0;padding:0;border-top:1px solid #B1B1B1 !important;clear:left;}
  #rexdev a.blank               {background:url("../files/addons/be_style/plugins/agk_skin/popup.gif") no-repeat 100% 0;padding-right:17px;}
  #rexdev #modulinfo            {font-size:10px;text-align:right;}
  /*XFORM MODUL*/
  #rexdev pre                   {clear:left;}
  #rexdev .help                 {display:none;color:#2C8EC0;line-height:12px;}
  #rexdev .area-wrapper         {background:white;border:1px solid #737373;margin-bottom:10px;width:100%;}
  #rexdev .fullwidth            {width:100% !important;}
  #rexdev #thx-markup           {width:auto !important;}
  #rexdev #thx-markup input     {width:auto !important;}
  #rexdev #mail-placeholders span:hover {color:red;cursor:pointer;}
  #rexdev #where-help,
  #rexdev #classes-showhelp,
  #rexdev #mail-placeholders    {display:none;}
  #rexdev #form-definition,
  #rexdev #mail-body            {width:100%;border:0;border-bottom:1px dotted silver;margin-bottom:0;background:white;}
  #rexdev #classes-showhelp     {max-height:200px;width:99%:200px;overflow:auto;}
  #rexdev em.hint               {color:silver;margin:0;padding:0 0 0 10px;}
  /*SHOWHELP OVERRIDES*/
  #rexdev ul.xform.root         {border:0;outline:0;margin-top:0;padding-top:0;width:100%;background:transparent;}
  #rexdev ul.xform              {font-size:1em;line-height:1.4em;}

-->
</style>


<div id="rexdev">
<fieldset>
  <legend>Formular</legend>

  <label class="fullwidth">Felddefinitionen: <span>[<a href="#" id="classes-toggler">verfügbare Klassen</a>]</span></label>
  <div class="area-wrapper">
    <textarea name="VALUE[3]" id="form-definition">REX_VALUE[3]</textarea>
    <div id="classes-showhelp">
      <em class="hint">Verfügbare Feldklassen:</em>
      <?php echo rex_xform::showHelp(true,true); ?>
    </div><!-- #classes-showhelp -->
  </div><!-- .area-wrapper -->

  <div id="thx-markup"><strong>Meldung bei erfogreichen Versand:</strong> (
    <input type="radio" name="VALUE[11]" value="0" <?php if("REX_VALUE[11]" == "0") echo 'checked="checked"'; ?>> Plaintext
    <input type="radio" name="VALUE[11]" value="1" <?php if("REX_VALUE[11]" == "1") echo 'checked="checked"'; ?>> HTML
    <input type="radio" name="VALUE[11]" value="2" <?php if("REX_VALUE[11]" == "2") echo 'checked="checked"'; ?>> Textile)
  </div><!-- #thx-markup -->
  <textarea name="VALUE[6]" id="thx-message" class="fullwidth">REX_VALUE[6]</textarea>

</fieldset>


<fieldset>
  <legend>Vordefinierte Aktionen</legend>

  <label>Bei Submit:</label>
  <select name="VALUE[1]" id="action-select" onchange="rex_xform_toggleDb(this);" style="width:auto;">
    <option value=""  <?php if("REX_VALUE[1]" == "")  echo " selected "; ?>>Nichts machen (actions im Formular definieren)</option>
    <option value="0" <?php if("REX_VALUE[1]" == "0") echo " selected "; ?>>Nur in Datenbank speichern oder aktualisieren wenn "main_where" gesetzt ist</option>
    <option value="1" <?php if("REX_VALUE[1]" == "1") echo " selected "; ?>>Nur E-Mail versenden</option>
    <option value="2" <?php if("REX_VALUE[1]" == "2") echo " selected "; ?>>E-Mail versenden und in Datenbank speichern</option>
    <option value="3" <?php if("REX_VALUE[1]" == "3") echo " selected "; ?>>E-Mail versenden und Datenbank abfragen</option>
  </select>

</fieldset>


<fieldset id="mail-fieldset" <?php echo $mail_display;?> >
  <legend>Emailversand:</legend>

  <label>Absender:</label>
  <input type="text" name="VALUE[2]" value="REX_VALUE[2]" />

  <label>Subject:</label>
  <input type="text" name="VALUE[4]" value="REX_VALUE[4]" />
  <label class="fullwidth">Mailbody: <span>[<a href="#" id="placeholders-toggler">Platzhalter anzeigen</a>]</span></label>

  <div class="area-wrapper">
  <textarea id="mail-body" name="VALUE[5]">REX_VALUE[5]</textarea>
  <div class="" id="mail-placeholders" style="width:95%;">
    <em class="hint">Resultierende Platzhalter aus Formdefinition: (insert via click)</em>
    <p style="float:none;margin:0 0 10px 10px;">
    <?php echo $placeholders;?>
    </p>
    </div><!-- #mail-placeholders -->
  </div><!-- .area-wrapper -->

</fieldset>


<fieldset id="db-fieldset" <?php echo $db_display;?> >
  <legend>Datenbank Einstellungen</legend>

  <label>Tabelle wählen <span>[<a href="#" id="db-help-toggler">?</a>]</span></label>
  <?php echo $ts;?>
  <ul class="help" id="db-select-help">
    <li>Diese Tabelle gilt auch bei Uniqueabfragen (Pflichtfeld=2) siehe oben</li>
  </ul>

  <hr />

  <label for="getdatapre">Daten initial aus DB holen</label>
  <input id="getdatapre" type="checkbox" value="1" name="VALUE[10]" <?php if("REX_VALUE[10]" != "") echo 'checked="checked"'; ?> />

  <div id="db_data">
    <hr />
    <label>Where Klausel: <span>[<a href="#" id="where-help-toggler">?</a>]</span></label>
    <textarea name="VALUE[9]" cols="30" rows="3" id="db-where" class="fullwidth">REX_VALUE[9]</textarea>
    <ul class="help" id="where-help">
      <li>PHP erlaubt. Beispiel: <em>$xform-&gt;setObjectparams("main_where",$where);</em></li>
      <li>Die User-Eingaben aus dem Formular können mittels Platzhaltern (Schema: ###<em>FELDNAME</em>###) in der WHERE Klausel verwendet werden - Beispiel: text|myname|Name|1 -> Platzhalter: ###myname###</li>
    </ul>
  </div><!-- #db_data -->

  <label>DebugModus:</label>
  <?php echo $dbg;?>

  </fieldset>

</div><!-- #rexdev -->

<script type="text/javascript" src="../files/addons/xform/jquery.autogrow.js"></script>
<script type="text/javascript">
<!--
(function($){

  // AUTOGROWS
  $('#form-definition').autogrow();
  $('#mail-body').autogrow();
  $('#db-where').autogrow();
  $('#thx-message').autogrow();


  // TOGGLERS
  $('#classes-toggler').click(function(){
    $('#classes-showhelp').toggle('fast');return false;
  });
  $('#placeholders-toggler').click(function(){
    $('#mail-placeholders').toggle('fast');return false;
  });
  $('#where-help-toggler').click(function(){
    $('#where-help').toggle('fast');return false;
  });
  $('#db-help-toggler').click(function(){
    $('#db-select-help').toggle('fast');return false;
  });


  // INSERT PLACEHOLDERS
  $('#mail-placeholders span').click(function(){
    newval = $('#mail-body').val()+' '+$(this).html();
    $('#mail-body').val(newval);
  });

  // TOGGLE MAIL/DB PANELS
  $('#action-select').change(function(){
    switch($(this).val()){
      case '':
        $('#db-fieldset').hide('slow');
        $('#mail-fieldset').hide('slow');
        break;
      case '1':
        $('#db-fieldset').hide('slow');
        $('#mail-fieldset').show('slow');
        break;
      case '0':
        $('#db-fieldset').show('slow');
        $('#mail-fieldset').hide('slow');
        break;
      case '2':
      case '3':
        $('#db-fieldset').show('slow');
        $('#mail-fieldset').show('slow');
        break;
    }
  });

})(jQuery)
//-->
</script>