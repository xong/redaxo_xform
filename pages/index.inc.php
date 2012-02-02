<?php

// $addon = rex_addon::get("xform");
// echo '<pre>'; var_dump($addon->getRegisteredPlugins()); echo '</pre>';

$t = new rex_xform_manager();
$tables = $t->getTables();
$content ='';

$page = 'xform';

$subpage = rex_request("subpage","string");

if ($subpage != "")
{
  switch($subpage)
  {
    case('description'):
      include rex_path::addon("xform","pages/description.inc.php");
      break;
    default:
      include rex_path::plugin("xform",$subpage,"pages/index.inc.php");
    break;
  }

}
else
{
  $content .= '<h2>XFORM - '.rex_i18n::msg("xform_overview").'</h2>';

  /*echo '<ul>';
  foreach($REX['ADDON'][$page]['SUBPAGES'] as $sp)
  {
  echo '<li><a href="index.php?page='.$page.'&amp;subpage='.$sp[0].'">'.$sp[1].'</a></li>';
  }  
  echo '</ul>';*/

  ## output
  echo rex_view::title(rex_i18n::msg('xform'),rex_addon::get('xform')->getProperty('subpages'));
  echo rex_view::contentBlock($content,'','tab');
}

?>