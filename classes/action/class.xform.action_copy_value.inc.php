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

class rex_xform_action_copy_value extends rex_xform_action_abstract
{

  function execute()
  {
  
    $label_from = $this->getElement(2);
    $label_to = $this->getElement(3);
  
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
