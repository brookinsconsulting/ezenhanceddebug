<?php

$Module = array( "name" => "ezenhanceddebug" );

$Views = array();

$ViewList["testall"] = array(
    "script" => "testall.php" );

$ViewList["testloops"] = array(
    "script" => "testloops.php" );

$ViewList["testfetches"] = array(
    "script" => "testfetches.php" );

$ViewList["testcacheblocks"] = array(
    "script" => "testcacheblocks.php" );

$ViewList["pieceofcode"] = array(
    "script" => "pieceofcode.php",
    "params" => array( "File", "Line", "Column" ) );

$ViewList["testloopswithdelimiter"] = array(
    "script" => "testloopswithdelimiter.php",
    "params" => array() );
?>