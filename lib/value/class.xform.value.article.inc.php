<?php

class rex_xform_value_article extends rex_xform_value_abstract
{

	function enterObject()
	{
		$article = new rex_article();
		$article->setArticleId($this->getElement(1));
		
		$ctype = 1;
		if ((int)$this->getElement(2) > 1)
  		$ctype = (int)$this->getElement(2);
		

		$class = $this->getHTMLClass();

    $before = '';
    $after = '';
		$label = '';
		$field = '';
    $extra = $article->getArticle($ctype);
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
		return "article -> Beispiel: article|article_id|ctype";
	}

}

?>