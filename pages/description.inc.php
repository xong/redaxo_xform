<?php

rex_title("XForm", $REX['ADDON'][$page]['SUBPAGES']);

echo '<div class="rex-addon-output">
	<h2 class="rex-hl2">Beschreibung</h2>
	<div class="rex-addon-content">
	<p>'.$I18N->msg('xform_description_all').'</p>
	<hr />';

rex_xform::showHelp(); 

echo '
	</div>
</div>';

?>