<?php

/**
 * XForm 
 * @author jan.kristinus[at]redaxo[dot]de Jan Kristinus
 * @author <a href="http://www.yakamara.de">www.yakamara.de</a>
 */

$sql = rex_sql::factory();

if ($sql->hasError()) {
	$msg = 'MySQL-Error: '.$sql->getErrno().'<br />';
	$msg .= $sql->getError();
	
	$REX['ADDON']['install']['xform'] = 0;
	$REX['ADDON']['installmsg']['xform'] = $msg;
	
}else {
	$REX['ADDON']['install']['xform'] = 1;

}

?>