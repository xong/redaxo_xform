<?php

class rex_xform_validate_unique extends rex_xform_validate_abstract 
{

	function postValueAction()
	{
		if($this->params["send"]=="1")
		{
		
			$table = $this->params["main_table"];
			if($this->getElement(4) != "")
				$table = $this->getElement(4);
			
			$fields = explode(",",$this->getElement(2));
			
			$where = array();
			$params = array();

			foreach($this->obj as $o)
			{
				if (in_array($o->getName(),$fields))
				{
					$where[] =  '`'.$o->getName().'` = ? ';
					$params[] = $o->getValue();
					$object_ids[$o->getName()] = $o->getId();
				}
			}

			if($this->params["main_where"] != "")
			{
				$where[] = '!('.$this->params["main_where"].')';
			}
			$sql = 'select '.implode(",",$fields).' from '.$table.' where '.implode(" and ",$where).' LIMIT 1';

			$cd = rex_sql::factory();
			if($this->params["debug"])
			  $cd->debugsql = 1;
			$cd->setQuery($sql,$params);
			if (count($fields) != count($params) || $cd->getRows()>0)
			{
				foreach($fields as $f)
					$this->params["warning"][$object_ids[$f]] = $this->params["error_class"];
				$this->params["warning_messages"][] = $this->getElement(3);
			}
			
		}
	}
	
	function getDescription()
	{
		return "unique -> prüft ob unique, beispiel: validate|unique|dbfeldname|Dieser Name existiert schon|[table]";
	}
	
	function getDefinitions()
	{
		return array(
						'type' => 'validate',
						'name' => 'unique',
						'values' => array(
             				array( 'type' => 'select_name', 'label' => 'Name' ),
              				array( 'type' => 'text',    	'label' => 'Fehlermeldung'),
              				array( 'type' => 'text',    	'label' => 'Tabelle [opt]'),
						),
						'description' => 'Hiermit geprüft, ob ein Wert bereits vorhanden ist.',
			);
	
	}
}

?>