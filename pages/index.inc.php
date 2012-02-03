<?php

$content ='';

$subpage = rex_request("subpage","string");
$page = rex_request("page","string");
$func = rex_request("func","string","");

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

  ## output
  echo rex_view::title(rex_i18n::msg('xform'),rex_addon::get('xform')->getProperty('subpages'));
  echo rex_view::contentBlock($content,'','block');
}
