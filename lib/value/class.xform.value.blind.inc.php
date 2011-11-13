<?php

class rex_xform_value_blind extends rex_xform_value_abstract
{

	function enterObject()
	{



	}

	function getDescription()
	{
		return "blind -> Beispiel: blind|label";
	}

	function getDefinitions()
	{
		return array(
						'type' => 'value',
						'name' => 'blind',
						'values' => array(
									array( 'type' => 'name',   'label' => 'Feld' ),
		        		),
						'description' => 'Ein leeres Feld - unsichtbar im Formular',
						'dbtype' => 'text'
						);

	}
}

?>