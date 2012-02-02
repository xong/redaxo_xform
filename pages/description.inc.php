<?php

echo rex_view::title("XForm");

$content = '<h2>'.rex_i18n::msg('xform_description').'</h2>';
$content .= '<div class="xform-description">'.rex_i18n::msg('xform_description_all').'</div>';
$content .= rex_xform::showHelp(true,true);

echo rex_view::contentBlock($content,'','tab');
?>