<?php

$Module = $Params["Module"];

$tpl = eZTemplate::factory();

$Result['pagelayout'] = '';
$Result['content'] = $tpl->fetch( "design:ezenhanceddebug/testloopswithdelimiter.tpl" );
?>
