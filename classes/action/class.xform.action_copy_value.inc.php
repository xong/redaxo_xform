<?php

class rex_xform_action_copy_value extends rex_xform_action_abstract
{

	function execute()
	{
	
		$label_from = $this->action["elements"][2];
		$label_to = $this->action["elements"][3];
	
		foreach($this->elements_sql as $key => $value)
		{
			if ($label_from==$key)
			{
				$this->elements_sql[$label_to] = $value;
				break;
			}
		}
		
		return;

	}

	function getDescription()
	{
		return "action|copy_value|label_from|label_to";
	}

}

?>