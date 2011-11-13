<?php

class rex_xform_value_captcha extends rex_xform_value_abstract
{

	function enterObject()
	{

		global $REX;

		require_once (realpath(dirname (__FILE__).'/../../ext/captcha/class.captcha_x.php'));

		$captcha = new captcha_x ();
		$captchaRequest = rex_request('captcha', 'string');

		if ($captchaRequest == "show")
		{
			while(@ob_end_clean());
			$captcha->handle_request();
			exit;
		}



		$class = $this->getHTMLClass();
		$classes = $class;
		
		// hier bewusst nur ein "&" (konditionales und, kein boolsches und!)
		if ( $this->params["send"] == 1 & $captcha->validate($this->getValue()))
		{
			// Alles ist gut.
			// *** Captcha Code leeren, nur einmal verwenden, doppelt versand des Formulars damit auch verhindern
			if (isset($_SESSION['captcha'])) 
			{
				unset($_SESSION['captcha']);
			}
		}
		elseif($this->params["send"]==1)
		{
			// Error. Fehlermeldung ausgeben
			$this->params["warning"][$this->getId()] = $this->getElement(2);
			$this->params["warning_messages"][$this->getId()] = $this->getElement(2);
			$classes = $this->params["error_class"];
		}

		$link = rex_getUrl($this->params["article_id"],$this->params["clang"],array("captcha"=>"show"),"&");

		
		$classes = (trim($classes) != '') ? ' class="'.trim($classes).'"' : '';

		
    $before = '';
    $after = '';
		$label = ($this->getElement(2) != '') ? '<label'.$classes.' for="' . $this->getFieldId() . '">' . rex_i18n::translate($this->getElement(2)) . '</label>' : '';
		$field = '<input'.$classes.' id="'.$this->getFieldId().'"" type="text" name="'.$this->getFieldName().' maxlength="5" size="5" />';
		$extra = '<span'.$classes.'><img src="'.$link.'" onclick="javascript:this.src=\''.$link.'&\'+Math.random();" alt="CAPTCHA image" /></span>';
    $html_id = $this->getHTMLId();
    $name = $this->getName();
    
    
		$f = new rex_fragment();
		$f->setVar('before', $before, false);
		$f->setVar('after', $after, false);
		$f->setVar('label', $label, false);
		$f->setVar('field', $field, false);
		$f->setVar('extra', $extra, false);
		$f->setVar('html_id', $html_id, false);
		$f->setVar('name', $name, false);
		$f->setVar('class', $class, false);
		
		$fragment = $this->params['fragment'];
		$this->params["form_output"][$this->getId()] = $f->parse($fragment);
	}

	function getDescription()
	{
		return "captcha -> Beispiel: captcha|Beschreibungstext|Fehlertext";
	}

}

?>