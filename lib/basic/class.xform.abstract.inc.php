<?php

class rex_xform_abstract
{

  var $obj;

	/*

  var $params = array();
  var $elements = array();

  function loadParams(&$params, &$elements)
  {
    $this->params = &$params;
    $this->elements = &$elements;
  }
	*/

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

  function postValueAction()
  {
  	return "";	
  }  

}