<?php

class rex_xform_value_fragment extends rex_xform_value_abstract
{

	function enterObject()
	{		
		
		$this->params['fragment'] = $this->getElement(1);
	}
	
	function getDescription()
	{
		return 'fragment -> Beispiel: fragment|Name eines Fragments';
	}
	
	function getDefinitions()
	{
    return array(
            'type' => 'value',
            'name' => 'fragment',
            'values' => array(
	              array( 'type' => 'name',   'label' => 'Name eines Fragments' ),
              ),
            'description' => 'Fragment ändern',
            'dbtype' => 'text'
      );
	}
}

?>