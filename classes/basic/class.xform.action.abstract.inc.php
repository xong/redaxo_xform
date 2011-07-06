<?php

/**
 * XForm
 *
 * @author jan.kristinus[at]redaxo[dot]de Jan Kristinus
 * @author <a href="http://www.yakamara.de">www.yakamara.de</a>
 *
 * @package redaxo4
 * @version svn:$Id$
 */

class rex_xform_action_abstract
{

  var $obj;

  var $params = array();
  var $elements = array();
  var $action = array();
  var $elements_email = array();
  var $elements_sql = array();

  // $this->objparams["warning"],$this->objparams["warning_messages"]
  function loadParams(&$params, $action, &$elements_email, &$elements_sql)
  {
    $this->params = &$params;
    $this->action = $action;
    $this->elements_email = &$elements_email;
    $this->elements_sql = &$elements_sql;
  }

  function setObjects(&$obj)
  {
    $this->obj = &$obj;
  }

  function execute()
  {
    return FALSE;
  }

  function getDescription()
  {
    return "Es existiert keine Klassenbeschreibung";
  }

  function getLongDescription()
  {
    return "Es existiert keine ausfuehrliche Klassenbeschreibung";
  }

  function getDefinitions()
  {
    return array();
  }
  
  function getElement($i)
  {
    if(!isset($this->action["elements"][$i]))
      return FALSE;
    else
      return $this->action["elements"][$i];
  }
  
  function getParam($param)
  {
    return $this->params[$param];
  }

}
