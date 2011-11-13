<?php

class rex_xform_value_stamp extends rex_xform_value_abstract
{

	function enterObject()
	{
        
    $format = $this->getElement(3);
    
    $dateformat = 'Y-m-d';
    if ($format != '')
    {
      switch ($format)
      {
        case 'mysql':
        case 'mysql_datetime':
          $dateformat = 'Y-m-d H:i:s';
          break;
          
        case 'mysql_date':
          $dateformat = 'Y-m-d';
          break;
          
        case 'mysql_time':
          $dateformat = 'H:i:s';
          break;
          
        case 'mysql_year':
          $dateformat = 'Y';
          break;
        
        case 'timestamp';
          break;
          
        default:
          $dateformat = $format;
          break;
        
      }
    }
    
  
		 // 0 = immer setzen, 1 = nur wenn leer / create
		if($this->getElement(5) != 1)
		{
			$set = 0;
		}
		else
		{
		  $set = 1;
		}


		if($this->getValue() == '' || $set == 0)
		{
      $value = time();
      if ($format != 'timestamp')
        $value = date($dateformat, $value);
        
			$this->setValue($value);
		}



		$this->params["form_output"][$this->getId()] = '<input type="hidden" name="'.$this->getFieldName().'" id="'.$this->getFieldId().'" value="'.htmlspecialchars(stripslashes($this->getValue())).'" />';

		$this->params["value_pool"]["email"][$this->getName()] = $this->getValue();
		if (!($this->getElement(4) == "no_db"))
		{
			$this->params["value_pool"]["sql"][$this->getName()] = $this->getValue();
		}
	}

	function getDescription()
	{
		return "stamp -> Beispiel: stamp|label|[Y-m-d], timestamp oder mysql|[no_db]|[0-wird immer neu gesetzt,1-nur wenn leer]";
	}

	function getDefinitions()
	{

		return array(
            'type' => 'value',
            'name' => 'stamp',
            'values' => array(
				array( 'type' => 'name',   'label' => 'Name' ),
				array( 'type' => 'text',    'label' => 'Bezeichnung'),
				array( 'type' => 'text',    'label' => 'Eigenes Format [YmdHis], timestamp, mysql, mysql_datetime, mysql_date, mysql_time, mysql_year'),
				array( 'type' => 'no_db',   'label' => 'Datenbank',  'default' => 1),
				array( 'type' => 'select',  'label' => 'Wann soll Wert gesetzt werden', 'default' => '0', 'definition' => 'immer=0,nur wenn leer=1' ),
				),
            'description' => 'Ein Selectfeld mit festen Definitionen.',
            'dbtype' => 'varchar(255)'
            );


	}


}