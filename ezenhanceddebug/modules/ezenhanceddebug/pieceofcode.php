<?php
include_once( 'lib/ezutils/classes/ezini.php'  );
include_once( 'lib/ezutils/classes/ezhttptool.php' );
include_once( 'extension/ezenhanceddebug/classes/ezenhanceddebug.php' );

// remove any debug informations in this page
$ini = eZINI::instance( 'site.ini' );
$ini->setVariable( 'DebugSettings', 'DebugOutput', 'disabled' );

$ezhttp = eZHTTPTool::instance();

$file      = $ezhttp->postVariable( 'File' );
$lineStart = $ezhttp->postVariable( 'LineStart' );
$lineEnd   = $ezhttp->postVariable( 'LineEnd' );

$pieceOfCode = '';

if( !is_null( $file ) and !is_null( $lineStart ) and !is_null( $lineEnd ) )
{
    $eZEnhancedDebug = eZEnhancedDebug::instance();
    $pieceOfCode = trim( $eZEnhancedDebug->getPieceOfCode( $file, $lineStart, $lineEnd ) );
}

$Result['pagelayout'] = '';
$Result['content'] = $pieceOfCode;
?>
