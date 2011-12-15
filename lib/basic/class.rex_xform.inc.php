<?php

/**
 * XForm
 * @author jan.kristinus[at]redaxo[dot]de Jan Kristinus
 * @author <a href="http://www.yakamara.de">www.yakamara.de</a>
 */

class rex_xform
{

  var $ValueObjectsparams;

  function rex_xform()
  {

    $this->objparams = array();

    $this->objparams['debug'] = FALSE;
    $this->objparams["fragment"] = "xform_standard";

    $this->objparams['form_data'] = "";

    $this->objparams["actions"] = array();

    $this->objparams["answertext"] = "";
    $this->objparams["submit_btn_label"] = "abschicken";
    $this->objparams["submit_btn_show"] = TRUE;
    $this->objparams["output"] = "";

    $this->objparams["main_where"] = ''; // z.B. id=12
    $this->objparams["main_id"] = -1; // unique ID
    $this->objparams["main_table"] = ""; // for db and unique

    $this->objparams["error_class"] = 'xform-warning';
    $this->objparams["unique_error"] = "";
    $this->objparams["unique_field_warning"] = "not unique";
    $this->objparams["article_id"] = 0;
    $this->objparams["clang"] = 0;

	$this->objparams["real_field_names"] = FALSE;

    $this->objparams["form_method"] = "post";
    $this->objparams["form_action"] = "index.php";
    $this->objparams["form_anchor"] = "";
    $this->objparams["form_showformafterupdate"] = 0;
    $this->objparams["form_show"] = TRUE;
    $this->objparams["form_name"] = "xform";
    $this->objparams["form_id"] = "xform_form";
    $this->objparams["form_wrap"] = array('<div class="xform">','</div>');
    $this->objparams["form_hiddenfields"] = array();

    $this->objparams["actions_executed"] = FALSE;
    $this->objparams["postactions_executed"] = FALSE;

    $this->objparams["Error-occured"] = "";
    $this->objparams["Error-Code-EntryNotFound"] = "ErrorCode - EntryNotFound";
    $this->objparams["Error-Code-InsertQueryError"] = "ErrorCode - InsertQueryError";
    $this->objparams["warning"] = array ();
    $this->objparams["warning_messages"] = array ();

    $this->objparams["fieldsets_opened"] = 0;
    $this->objparams["getdata"] = FALSE;

    $this->objparams["form_elements"] = array();
    $this->objparams["form_output"] = array();

    $this->objparams["value_pool"] = array();
    $this->objparams["value_pool"]["email"] = array();
    $this->objparams["value_pool"]["sql"] = array();

    $this->objparams["value"] = array(); // reserver for classes - $this->objparams["value"]["text"] ...
    $this->objparams["validate"] = array(); // reserver for classes
    $this->objparams["action"] = array(); // reserver for classes
    
    $this->objparams["this"] = $this;

  }

  function setDebug($s = TRUE) {
    $this->objparams['debug'] = $s;
  }

  function setFormData($form_definitions,$refresh = TRUE) {
    $this->setObjectparams("form_data",$form_definitions,$refresh);

    $this->objparams["form_data"] = str_replace("\n\r", "\n" ,$this->objparams["form_data"]); // Die Definitionen
    $this->objparams["form_data"] = str_replace("\r", "\n" ,$this->objparams["form_data"]); // Die Definitionen

    if(!is_array($this->objparams["form_elements"])) {
      $this->objparams["form_elements"] = array();
    }

    $form_elements_tmp = array ();
    $form_elements_tmp = explode("\n", $this->objparams['form_data']); // Die Definitionen

    // leere Zeilen aus $this->objparams["form_elements"] entfernen
    foreach($form_elements_tmp as $form_element) {
      if(trim($form_element) != "") {
        $this->objparams["form_elements"][] = explode("|", trim($form_element));
      }
    }
  }

  function setValueField($type = "",$values = array()) {
    $values = array_merge(array($type),$values);
    $this->objparams["form_elements"][] = $values;
  }

  function setValidateField($type = "",$values = array()) {
    $values = array_merge(array("validate",$type),$values);
    $this->objparams["form_elements"][] = $values;
  }

  function setActionField($type = "",$values = array()) {
    $values = array_merge(array("action",$type),$values);
    $this->objparams["form_elements"][] = $values;
  }

  function setRedaxoVars($aid = "",$clang = "",$params = array()) {

    if ($clang == "") {
      $clang = rex_clang::getCurrentClang();
    }
    if ($aid == "") {
      $aid = rex::getArticleID();
    }

    $this->setHiddenField("article_id",$aid);
    $this->setHiddenField("clang",$clang);

    $this->setObjectparams("form_action", rex_getUrl($aid, $clang, $params));
  }

  function setHiddenField($k,$v) {
    $this->objparams["form_hiddenfields"][$k] = $v;
  }

  function setObjectparams($k,$v,$refresh = TRUE) {
  	
    if (!$refresh && isset($this->objparams[$k])) {
      $this->objparams[$k] .= $v;
    }else {
      $this->objparams[$k] = $v;
    }
	return $this->objparams[$k];

  }

  function getObjectparams($k) {
    if(!isset($this->objparams[$k])) {
      return FALSE;
    }
    return $this->objparams[$k];
  }

  function getForm() {

    $preg_user_vorhanden = "~\*|:|\(.*\)~Usim"; // Preg der Bestimmte Zeichen/Zeichenketten aus der Bezeichnung entfernt

    $ValueObjects = array();
    $ValidateObjects = array();

    // *************************************************** ABGESCHICKT PARAMENTER
    $this->objparams["send"] = 0;
    
    if ($this->getFieldValue("xform_send",'',"xform_send") == "1")
    {
    	$this->objparams["send"] = 1;
    }


    // *************************************************** VALUE OBJEKTE

	$this->setValueField("submit",array("rex_xform_submit","","no_db"));


    // *************************************************** VALUE OBJEKTE
    $rows = count($this->objparams["form_elements"]);
    for ($i = 0; $i < $rows; $i++)
    {
      $element = $this->objparams["form_elements"][$i];

      if($element[0] == "validate") {
        	
      }elseif($element[0] == "action") {
        	
      }else {
      	
		$classname = "rex_xform_value_".trim($element[0]);
		$ValueObjects[$i] = new $classname;
		$ValueObjects[$i]->loadParams($this->objparams,$element);
		$ValueObjects[$i]->setId($i);
		$ValueObjects[$i]->init();
		$ValueObjects[$i]->setValue($this->getFieldValue($i,'',$ValueObjects[$i]->getName()));
		$ValueObjects[$i]->setObjects($ValueObjects);
		
		// muss hier gesetzt sein, damit ein value objekt die elemente erweitern kann
		$rows = count($this->objparams["form_elements"]);
        
      }
    }

    // ----- PRE VALUES
    // Felder aus Datenbank auslesen - Sofern Aktualisierung
    unset($SQLOBJ);
    if ($this->objparams['getdata'] && $this->objparams["main_where"] != "") {
    	
      $SQLOBJ = rex_sql::factory();
      $SQLOBJ->debugsql = $this->objparams['debug'];
	  $SQLOBJ->setQuery("SELECT * from ".$this->objparams["main_table"]. " WHERE ".$this->objparams["main_where"]);

      if ($SQLOBJ->getRows() > 1 || $SQLOBJ->getRows() == 0) {
        $this->objparams["warning"][] = $this->objparams["Error-Code-EntryNotFound"];
        $this->objparams["warning_messages"][] = $this->objparams["Error-Code-EntryNotFound"];
        $this->objparams["form_show"] = TRUE;
        unset($SQLOBJ);

      }
    }


    // ----- Felder mit Werten fuellen, fuer wiederanzeige
    // Die Value Objekte werden mit den Werten befuellt die
    // aus dem Formular nach dem Abschicken kommen
    if (!($this->objparams["send"] == 1) && $this->objparams["main_where"] != "") { //  && $this->objparams['form_type'] != "3"
      
      for ($i = 0; $i < count($this->objparams["form_elements"]); $i++) {
      	
        $element = $this->objparams["form_elements"][$i];

        if (($element[0] != "validate" && $element[0] != "action") and $element[1] != "") {

          if(isset($SQLOBJ)) {
          	$this->setFieldValue($i,@addslashes($SQLOBJ->getValue($element[1])),'',$element[1]);
          }

        }
        
        if($element[0]!="validate" && $element[0]!="action") {
          $ValueObjects[$i]->setValue($this->getFieldValue($i,'',$ValueObjects[$i]->getName()));
        }
      
      }
    }


    // *************************************************** VALIDATE OBJEKTE

    // ***** PreValidateActions
    foreach($ValueObjects as $value_object) {
      $value_object->preValidateAction();
    }

    for ($i = 0; $i < count($this->objparams["form_elements"]); $i++)
    {
      $element = $this->objparams["form_elements"][$i];
      if($element[0] == "validate")
      {
      	$classname = "rex_xform_validate_".trim($element[1]);
        $ValidateObjects[$element[1]][$i] = new $classname;
        $ValidateObjects[$element[1]][$i]->loadParams($this->objparams, $element);
        $ValidateObjects[$element[1]][$i]->setObjects($ValueObjects);
      }
    }

    // ***** Validieren
    if ($this->objparams["send"] == 1 && count($ValidateObjects)>0) {
      foreach($ValidateObjects as $validate_element_object) {
        foreach($validate_element_object as $validate_object) {
          $validate_object->enterObject();
        }
      }
    }

    // ***** PostValidateActions
    foreach($ValueObjects as $value_object) {
      $value_object->postValidateAction();
    }

    foreach($ValueObjects as $value_object) {
      $value_object->enterObject();
    }

	// ***** Post Validate Actions
    foreach($ValidateObjects as $validate_element_object) {
      foreach($validate_element_object as $validate_object) {
        $validate_object->postValueAction();
      }
    }
  
    // ***** PostValueActions
    foreach($ValueObjects as $value_object) {
      $value_object->postValueAction();
    }

    // *************************************************** ACTION OBJEKTE

    // ID setzen, falls vorhanden
    if($this->objparams["main_id"]>0) {
      $this->objparams["value_pool"]["email"]["ID"] = $this->objparams["main_id"];
    }

    for ($i = 0; $i < count($this->objparams["form_elements"]); $i++)
    {
      $element = $this->objparams["form_elements"][$i];
      if($element[0]=="action")
      {
        $this->objparams["actions"][] = array(
					"type" => trim($element[1]),
					"elements" => $element,
        );
      }
    }

    $hasWarnings = count($this->objparams["warning"]) != 0;
    $hasWarningMessages = count($this->objparams["warning_messages"]) != 0;


    // ----- Actions
    if ($this->objparams["send"] == 1 && !$hasWarnings && !$hasWarningMessages)
    {
      $this->objparams["form_show"] = FALSE;

	  $actions = array();
      $i=-1;
      foreach($this->objparams["actions"] as $action)
      {
        $i++;
        $classname = 'rex_xform_action_'.$action["type"];
        $actions[$i] = new $classname;
        $actions[$i]->loadParams($this->objparams,$action["elements"]);
        $actions[$i]->setObjects($ValueObjects);
      }
      foreach($actions as $action) {
        $action->execute();
      }

      $this->objparams["actions_executed"] = TRUE;

      // PostActions
      foreach($ValueObjects as $value_object) {
        $value_object->postAction();
      }

      $this->objparams["postactions_executed"] = TRUE;

    }

    $hasWarnings = count($this->objparams["warning"]) != 0;
    $hasWarningMessages = count($this->objparams["warning_messages"]) != 0;

	if($this->objparams["form_showformafterupdate"]) {
		$this->objparams["form_show"] = TRUE;	
	}

    if($this->objparams["form_show"])
    {

	  // -------------------- send definition
	  $this->setHiddenField($this->getFieldName("xform_send","","xform_send"),1);

	  // -------------------- form start
      if($this->objparams["form_anchor"] != ""){ $this->objparams["form_action"] .= '#'.$this->objparams["form_anchor"]; }

	  // -------------------- warnings output
	  $warningOut = '';
      $hasWarningMessages = count($this->objparams["warning_messages"]) != 0;
      if ($this->objparams["unique_error"] != '' || $hasWarnings || $hasWarningMessages)
      {
        $warningListOut = '';
        if($hasWarningMessages) {
          foreach($this->objparams["warning_messages"] as $k => $v) {
            $warningListOut .= '<li>'. rex_i18n::translate($v) .'</li>';
          }
        }
        if($this->objparams["unique_error"] != '') {
          $warningListOut .= '<li>'. rex_translate( preg_replace($preg_user_vorhanden, "", $this->objparams["unique_error"]) ) .'</li>';
        }

        if ($warningListOut != '') {
          if ($this->objparams["Error-occured"] != "") {
            $warningOut .= '<dl class="' . $this->objparams["error_class"] . '">';
            $warningOut .= '<dt>'. $this->objparams["Error-occured"] .'</dt>';
            $warningOut .= '<dd><ul>'. $warningListOut .'</ul></dd>';
            $warningOut .= '</dl>';
          }else {
            $warningOut .= '<ul class="' . $this->objparams["error_class"] . '">'. $warningListOut .'</ul>';
          }
        }
      }
      
	  // -------------------- formFieldsOut output
	  $formFieldsOut = '';
      foreach ($this->objparams["form_output"] as $v) {
        $formFieldsOut .= $v;
      }
      
      // -------------------- hidden fields 
      $hiddenOut = '';
      foreach($this->objparams["form_hiddenfields"] as $k => $v) {
        $hiddenOut .= '<input type="hidden" name="'.$k.'" value="'.htmlspecialchars($v).'" />';
      }
      
      // -------------------- formOut
      $formOut = $warningOut;
      $formOut .= '<form action="'.$this->objparams["form_action"].'" method="'.$this->objparams["form_method"].'" id="' . $this->objparams["form_id"] . '" enctype="multipart/form-data">';
	  $formOut .= $formFieldsOut;
	  $formOut .= $hiddenOut;
	  for($i=0;$i<$this->objparams["fieldsets_opened"];$i++) { $formOut .= '</fieldset>'; }
	  $formOut .= '</form>';
      
      $this->objparams["output"] .= $this->objparams["form_wrap"][0].$formOut.$this->objparams["form_wrap"][1];

    }

    return $this->objparams["output"];
  }

  function getTypes()
  {
    return array('value','validate','action');
  }

  // ---------------------------------------------------------------------------------

  // TODO: $this->objparams["real_field_names"]

  function getFieldName($id = "", $k = "", $label = "")
  {
  	if($this->objparams["real_field_names"] && $label != "") {
	    if($k == "") { 
	    	return $label;
	    }else {
	    	return $label.'['.$k.']';
	    }
  	}else
  	{
	    if($k == "") { 
	    	return 'FORM['.$this->objparams["form_name"].']['.$id.']';
	    }else {
	    	return 'FORM['.$this->objparams["form_name"].']['.$id.']['.$k.']';
	    }
  	}
  }

  function getFieldValue($id = "", $k = "", $label = "")
  {
  	if($this->objparams["real_field_names"] && $label != "") {
  		if($k == "" && isset($_REQUEST[$label])) {
		  	return $_REQUEST[$label];
	  	}elseif(isset($_REQUEST[$label][$k])) {
	  		return $_REQUEST[$label][$k];
	  	}
  	}else
  	{
	  	if($k == ""&& isset($_REQUEST["FORM"][$this->objparams["form_name"]][$id])) {
		  	return $_REQUEST["FORM"][$this->objparams["form_name"]][$id];
	  	}elseif(isset($_REQUEST["FORM"][$this->objparams["form_name"]][$id][$k])) {
	  		return $_REQUEST["FORM"][$this->objparams["form_name"]][$id][$k];
	  	}
  	}
	return "";
  }

  function setFieldValue($id = "", $value = "", $k = "", $label = "")
  {
  	if($this->objparams["real_field_names"] && $label != "") {
	  	if($k == "") {
		  	$_REQUEST[$label] = $value;
	  	}else {
	  		$_REQUEST[$label][$k] = $value;
	  	}
  		return;
  	}else
  	{
	  	if($k == "") {
		  	$_REQUEST["FORM"][$this->objparams["form_name"]][$id] = $value;
	  	}else {
	  		$_REQUEST["FORM"][$this->objparams["form_name"]][$id][$k] = $value;
	  	}
  	}
  }


  // ---------------------------------------------------------------------------------

  static function showHelp($script=false)
  {

	$paths = rex_config::get('xform-classes','paths');

    $return = "\n".'<ul class="xform root">';
    
    $return .= "\n".'<li class="type value"><strong class="toggler">Value</strong>';
    $return .= '<ul class="xform type value">';
	foreach($paths["value"] as $k => $path) {
		$return .= "\n".'<li>'.$k.'<ul>';
		foreach(glob($path.'*.inc.php') as $file) {
			$exx = explode(".", basename($file));
			$classname = "rex_xform_value_".$exx[3];
            $class = new $classname;
            $desc = $class->getDescription();
            if($desc != "") {
            	$return .= "\n".'<li><b>'.$exx[3].':</b> '.$desc.'</li>';
            }
		}
		$return .= '</ul></li>';
		
	}
	$return .= '</ul></li>';

    $return .= "\n".'<li class="type validate"><strong class="toggler">Validate</strong>';
    $return .= "\n".'<ul class="xform type validate">';
	foreach($paths["validate"] as $k => $path) {
		$return .= "\n".'<li>'.$k.'<ul>';
		foreach(glob($path.'*.inc.php') as $file) {
			$exx = explode(".", basename($file));
			$classname = "rex_xform_validate_".$exx[3];
            $class = new $classname;
            $desc = $class->getDescription();
            if($desc != "") { $return .= "\n".'<li><b>'.$exx[3].':</b> '.$desc.'</li>'; }
		}
		$return .= '</ul></li>';
		
	}
	$return .= '</ul></li>';

    $return .= "\n".'<li class="type action"><strong class="toggler">Action</strong>';
    $return .= "\n".'<ul class="xform type action">';
	foreach($paths["action"] as $k => $path) {
		$return .= "\n".'<li>'.$k.'<ul>';
		foreach(glob($path.'*.inc.php') as $file) {
			$exx = explode(".", basename($file));
			$classname = "rex_xform_action_".$exx[3];
            $class = new $classname;
            $desc = $class->getDescription();
            if($desc != "") { $return .= "\n".'<li><b>'.$exx[3].':</b> '.$desc.'</li>'; }
		}
		$return .= '</ul></li>';
	}
	$return .= '</ul></li>';

	$return .= '</ul>';

    if($script)
    {
      $return .= '
<script type="text/javascript">
(function($){

  $("ul.xform strong.toggler").click(function(){
    var me = $(this);
    var target = $(this).next("ul.xform");
    target.toggle(0, function(){
      if(target.css("display") == "block"){
        me.addClass("opened");
      }else{
        me.removeClass("opened");
      }
    });

  });

})(jQuery)
</script>
';
    }

	return $return;

  }

  static function getTypeArray()
  {

    $return = array();

	$paths = rex_config::get('xform-classes','paths');

	// Value
	foreach($paths["value"] as $k => $path) {
		foreach(glob($path.'*.inc.php') as $file) {
			$exx = explode(".", basename($file));
			$classname = "rex_xform_value_".$exx[3];
            $class = new $classname;
            $d = $class->getDefinitions();
            if(count($d)>0) { $return['value'][$d['name']] = $d; }
		}
	}


    // Validate
	foreach($paths["validate"] as $k => $path) {
		foreach(glob($path.'*.inc.php') as $file) {
			$exx = explode(".", basename($file));
			$classname = "rex_xform_validate_".$exx[3];
            $class = new $classname;
            $d = $class->getDefinitions();
            if(count($d)>0) { $return['validate'][$d['name']] = $d; }
		}
	}

	// Action
	foreach($paths["action"] as $k => $path) {
		foreach(glob($path.'*.inc.php') as $file) {
			$exx = explode(".", basename($file));
			$classname = "rex_xform_action_".$exx[3];
            $class = new $classname;
            $d = $class->getDefinitions();
            if(count($d)>0) { $return['action'][$d['name']] = $d; }
		}
	}

    return $return;

  }


	static function getBackendCSS($params) {
	
		$params['subject'] .= "\n".'<link rel="stylesheet" type="text/css" href="'.rex_path::addonAssets('xform','xform.css').'" media="screen, projection, print" />';
		$params['subject'] .= "\n".'<script src="'.rex_path::addonAssets('xform','manager.js').'" type="text/javascript"></script>';
		if(rex::isBackend()) {
		  $params['subject'] .= "\n".'<link rel="stylesheet" type="text/css" href="'.rex_path::addonAssets('xform','manager.css').'" media="screen, projection, print" />';
		}
		return $params['subject'];
	}

}