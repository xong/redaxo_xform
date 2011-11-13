<?php

rex_title("XForm");

echo '<div class="rex-addon-output">
	<h2 class="rex-hl2">'.rex_i18n::msg('xform_description').'</h2>
	<div class="rex-addon-content">
	<div class="xform-description">'.rex_i18n::msg('xform_description_all').'</div>';

echo rex_xform::showHelp(true,true);

echo '
	</div>
</div>';

?>