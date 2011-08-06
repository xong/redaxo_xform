<?php

/*
$objparams["actions"][] = "db"; // z.b. email, datenbank, als datei speichern etc.
$objparams["action_params"][] = array(
	"table" => "REX_VALUE[8]",
	"where" => "REX_VALUE[8]",
	);
*/


class rex_xform_action_db extends rex_xform_action_abstract
{
	
	function execute()
	{
		// echo "DB EXECUTE";
		// return;

		$sql = rex_sql::factory();
		if ($this->params["debug"]) $sql->debugsql = TRUE;
    
    	$main_table = "";
		if (!$main_table = $this->action["elements"][2])
		{
			$main_table = $this->params["main_table"];
    	}
    	
    	if ($main_table == "")
    	{
    		$this->params["form_show"] = TRUE;
			$this->params["hasWarnings"] = TRUE;
			$this->params["warning_messages"][] = $this->params["Error-Code-InsertQueryError"];
			return FALSE;
    	}
    
    	$sql->setTable($main_table);

      	$where = "";
		if ($where = $this->getElement(3)) // isset($this->action["elements"][3]) && trim($this->action["elements"][3]) != ""
		{
			if($where == "main_where")
			{
				$where = $this->params["main_where"];
			}
		}

		// SQL Objekt mit Werten füllen
		foreach($this->elements_sql as $key => $value)
		{
			$sql->setValue($key, $value);
			if ($where != "") $where = str_replace('###'.$key.'###',addslashes($value),$where);
		}
			
		if ($where != "")
		{
			$sql->setWhere($where);
			$saved = $sql->update();
			$flag = "update";
		}else
		{
			$saved = $sql->insert();
			$flag = "insert";
			$id = $sql->getLastId();
			
			$this->params["main_id"] = $id;
			$this->elements_email["ID"] = $id;
			// $this->elements_sql["ID"] = $id;
			if ($id == 0)
			{
				$this->params["form_show"] = TRUE;
				$this->params["hasWarnings"] = TRUE;
				$this->params["warning_messages"][] = $this->params["Error-Code-InsertQueryError"];
			}
		}

		rex_register_extension_point('REX_XFORM_SAVED', $saved, array('form' => $this, 'sql' => $sql, 'xform' => true));
	}

	function getDescription()
	{
		return "action|db|tblname|[where(id=2)/main_where]";
	}

}

?>