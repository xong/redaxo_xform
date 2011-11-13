<?php

class rex_xform_value_readtable extends rex_xform_value_abstract
{
	
	function enterObject()
	{
		foreach($this->params["value_pool"]["email"] as $k => $v)
		{
			if ($this->getElement(3) == $k) $value = $v;
		}
		
		
		$sql = rex_sql::factory();
    $sql->setTable($this->getElement(1));
    $sql->setWhere(array($this->getElement(2) => $value));
    $sql->select('*');

		if ($sql->getRows() == 1)
		{
			$ar = $sql->getArray();
			foreach($ar[0] as $k => $v)
			{
				$this->params["value_pool"]["email"][$k] = $v;
			}
		}	
		return;
	}

	function getDescription()
	{
		return "readtable|tablename|feldname|label";
	}

}

?>