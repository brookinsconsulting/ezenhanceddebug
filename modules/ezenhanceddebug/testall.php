<?php

$Module = $Params["Module"];

$tpl = eZTemplate::factory();

$extensionDir = 'extension/ezenhanceddebug/modules/ezenhanceddebug/';
$excludedFiles = array( $extensionDir . 'testall.php', 
                        $extensionDir . 'pieceofcode.php',
                        $extensionDir . 'module.php' ) ;

$tplListTmp = glob( $extensionDir . "*.php" );

$tplList = array();

foreach( $tplListTmp as $tplNameTmp )
{
    if( in_array( $tplNameTmp, $excludedFiles ) )
        continue;

    $tplNameTmp = str_replace( '.php', '.tpl', $tplNameTmp ); 
    $tplNameTmp = substr( $tplNameTmp, strrpos( $tplNameTmp, '/' ) + 1 );

    $tplList[] = $tplNameTmp;
}

$tpl->setVariable( 'tplList', $tplList );

$Result['pagelayout'] = '';
$Result['content'] = $tpl->fetch( "design:ezenhanceddebug/testall.tpl" );
?>
