<?php

$Module = $Params["Module"];

$tpl = templateInit();

$Result['pagelayout'] = '';
$Result['content'] = $tpl->fetch( "design:ezenhanceddebug/testfetches.tpl" );
?>
