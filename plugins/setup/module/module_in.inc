<?php

// module:xform_basic_in
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
  /*BAISC MODUL STYLE*/
  #xform-modul                       {margin:0;padding:0;line-height:25px;}
  #xform-modul fieldset              {background:#E4E1D1;margin:-20px 0 0 0;padding: 4px 10px 10px 10px;-moz-border-radius:6px;-webkit-border-radius:6px;border-radius:6px;}
  #xform-modul fieldset legend       {display:block !important;position:relative !important;height:auto !important;top:0 !important;left:0 !important;width:100% !important;margin:0 0 0 0 !important;padding:30px 0 0 0px !important;background:transparent !important;border-bottom:1px solid #B1B1B1 !important;color:gray;font-size:14px;font-weight:bold;}
  #xform-modul fieldset legend em    {font-size:10px;font-weight:normal;font-style:normal;}
  #xform-modul fieldset strong.label,
  #xform-modul fieldset label        {display:inline-block !important;width:150px !important;font-weight:bold;}
  #xform-modul fieldset label span   {font-weight:normal;}
  #xform-modul input,
  #xform-modul select                {width:460px;border:auto;margin:0 !important;padding:0 !important;}
  #xform-modul input[type="checkbox"]{width:auto;}
  #xform-modul hr                    {border:0;height:0;margin:4px 0 4px 0;padding:0;border-top:1px solid #B1B1B1 !important;clear:left;}
  #xform-modul a.blank               {background:url("../files/addons/be_style/plugins/agk_skin/popup.gif") no-repeat 100% 0;padding-right:17px;}
  #xform-modul #modulinfo            {font-size:10px;text-align:right;}
  /*XFORM MODUL*/
  #xform-modul pre                   {clear:left;}
  #xform-modul .help                 {display:none;color:#2C8EC0;line-height:12px;}
  #xform-modul .area-wrapper         {background:white;border:1px solid #737373;margin-bottom:10px;width:100%;}
  #xform-modul .fullwidth            {width:100% !important;}
  #xform-modul #thx-markup           {width:auto !important;}
  #xform-modul #thx-markup input     {width:auto !important;}
  #xform-modul #xfm-mail-placeholders span:hover {color:red;cursor:pointer;}
  #xform-modul #xfm-where-help,
  #xform-modul #xfm-classes-showhelp,
  #xform-modul #xfm-mail-placeholders{display:none;}
  #xform-modul #xfm-mail-placeholders{width:95%;}
  #xform-modul #xfm-form-definition,
  #xform-modul #xfm-mail-body        {width:100%;border:0;border-bottom:1px dotted silver;margin-bottom:0;background:white;}
  #xform-modul #xfm-classes-showhelp {max-height:200px;width:99%:200px;overflow:auto;}
  #xform-modul em.hint               {color:silver;margin:0;padding:0 0 0 10px;}
  /*SHOWHELP OVERRIDES*/
  #xform-modul ul.xform.root         {border:0;outline:0;margin:4px 0;padding:0;width:100%;background:transparent;}
  #xform-modul ul.xform              {font-size:1em;line-height:1.4em;}
</style>


<div id="xform-modul">
<fieldset>
  <legend>Formular</legend>

  <label class="fullwidth">Felddefinitionen: <span>[<a href="#" id="xfm-classes-toggler">verfügbare Klassen einblenden</a>]</span></label>
  <div class="area-wrapper">
    <textarea name="VALUE[3]" id="xfm-form-definition">REX_VALUE[3]</textarea>
    <div id="xfm-classes-showhelp">
      <?php echo rex_xform::showHelp(true,true); ?>
    </div><!-- #xfm-classes-showhelp -->
  </div><!-- .area-wrapper -->

  <div id="thx-markup"><strong>Meldung bei erfogreichen Versand:</strong> (
    <input type="radio" name="VALUE[11]" value="0" <?php if("REX_VALUE[11]" == "0") echo 'checked="checked"'; ?>> Plaintext
    <input type="radio" name="VALUE[11]" value="1" <?php if("REX_VALUE[11]" == "1") echo 'checked="checked"'; ?>> HTML
    <input type="radio" name="VALUE[11]" value="2" <?php if("REX_VALUE[11]" == "2") echo 'checked="checked"'; ?>> Textile)
  </div><!-- #thx-markup -->
  <textarea name="VALUE[6]" id="xfm-thx-message" class="fullwidth">REX_VALUE[6]</textarea>

</fieldset>


<fieldset>
  <legend>Vordefinierte Aktionen</legend>

  <label>Bei Submit:</label>
  <select name="VALUE[1]" id="xfm-action-select" onchange="rex_xform_toggleDb(this);" style="width:auto;">
    <option value=""  <?php if("REX_VALUE[1]" == "")  echo " selected "; ?>>Nichts machen (actions im Formular definieren)</option>
    <option value="0" <?php if("REX_VALUE[1]" == "0") echo " selected "; ?>>Nur in Datenbank speichern oder aktualisieren wenn "main_where" gesetzt ist</option>
    <option value="1" <?php if("REX_VALUE[1]" == "1") echo " selected "; ?>>Nur E-Mail versenden</option>
    <option value="2" <?php if("REX_VALUE[1]" == "2") echo " selected "; ?>>E-Mail versenden und in Datenbank speichern</option>
    <option value="3" <?php if("REX_VALUE[1]" == "3") echo " selected "; ?>>E-Mail versenden und Datenbank abfragen</option>
  </select>

</fieldset>


<fieldset id="xfm-mail-fieldset" <?php echo $mail_display;?> >
  <legend>Emailversand:</legend>

  <label>Absender:</label>
  <input type="text" name="VALUE[2]" value="REX_VALUE[2]" />

  <label>Subject:</label>
  <input type="text" name="VALUE[4]" value="REX_VALUE[4]" />
  <label class="fullwidth">Mailbody: <span>[<a href="#" id="xfm-placeholders-toggler">Platzhalter anzeigen</a>]</span></label>

  <div class="area-wrapper">
  <textarea id="xfm-mail-body" name="VALUE[5]">REX_VALUE[5]</textarea>
  <div class="" id="xfm-mail-placeholders">
    <em class="hint">Resultierende Platzhalter aus Formdefinition: (insert via click)</em>
    <p style="float:none;margin:0 0 10px 10px;">
    <?php echo $placeholders;?>
    </p>
    </div><!-- #xfm-mail-placeholders -->
  </div><!-- .area-wrapper -->

</fieldset>


<fieldset id="xfm-db-fieldset" <?php echo $db_display;?> >
  <legend>Datenbank Einstellungen</legend>

  <label>Tabelle wählen <span>[<a href="#" id="xfm-db-help-toggler">?</a>]</span></label>
  <?php echo $ts;?>
  <ul class="help" id="xfm-db-select-help">
    <li>Diese Tabelle gilt auch bei Uniqueabfragen (Pflichtfeld=2) siehe oben</li>
  </ul>

  <hr />

  <label for="getdatapre">Daten initial aus DB holen</label>
  <input id="getdatapre" type="checkbox" value="1" name="VALUE[10]" <?php if("REX_VALUE[10]" != "") echo 'checked="checked"'; ?> />

  <div id="db_data">
    <hr />
    <label>Where Klausel: <span>[<a href="#" id="xfm-xfm-where-help-toggler">?</a>]</span></label>
    <textarea name="VALUE[9]" cols="30" rows="3" id="xfm-db-where" class="fullwidth">REX_VALUE[9]</textarea>
    <ul class="help" id="xfm-where-help">
      <li>PHP erlaubt. Beispiel: <em>$xform-&gt;setObjectparams("main_where",$where);</em></li>
      <li>Die User-Eingaben aus dem Formular können mittels Platzhaltern (Schema: ###<em>FELDNAME</em>###) in der WHERE Klausel verwendet werden - Beispiel: text|myname|Name|1 -> Platzhalter: ###myname###</li>
    </ul>
  </div><!-- #db_data -->

  <label>DebugModus:</label>
  <?php echo $dbg;?>

  </fieldset>

  <p id="modulinfo">XForm Formbuilder v0.2</p>

</div><!-- #xform-modul -->

<script type="text/javascript" src="../files/addons/xform/jquery.autogrow.js"></script>
<script type="text/javascript">
<!--
(function($){

  // AUTOGROWS
  $('#xfm-form-definition').autogrow();
  $('#xfm-mail-body').autogrow();
  $('#xfm-db-where').autogrow();
  $('#xfm-thx-message').autogrow();


  // TOGGLERS
  $('#xfm-classes-toggler').click(function(){
    $('#xfm-classes-showhelp').toggle('fast');return false;
  });
  $('#xfm-placeholders-toggler').click(function(){
    $('#xfm-mail-placeholders').toggle('fast');return false;
  });
  $('#xfm-xfm-where-help-toggler').click(function(){
    $('#xfm-where-help').toggle('fast');return false;
  });
  $('#xfm-db-help-toggler').click(function(){
    $('#xfm-db-select-help').toggle('fast');return false;
  });


  // INSERT PLACEHOLDERS
  $('#xfm-mail-placeholders span').click(function(){
    newval = $('#xfm-mail-body').val()+' '+$(this).html();
    $('#xfm-mail-body').val(newval);
  });

  // TOGGLE MAIL/DB PANELS
  $('#xfm-action-select').change(function(){
    switch($(this).val()){
      case '':
        $('#xfm-db-fieldset').hide('slow');
        $('#xfm-mail-fieldset').hide('slow');
        break;
      case '1':
        $('#xfm-db-fieldset').hide('slow');
        $('#xfm-mail-fieldset').show('slow');
        break;
      case '0':
        $('#xfm-db-fieldset').show('slow');
        $('#xfm-mail-fieldset').hide('slow');
        break;
      case '2':
      case '3':
        $('#xfm-db-fieldset').show('slow');
        $('#xfm-mail-fieldset').show('slow');
        break;
    }
  });

})(jQuery)
//-->
</script>