<link href="{'stylesheets/ezenhanceddebug.css'|ezdesign('no')}" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="{'javascripts/jquery/jquery-1.2.1.pack.js'|ezdesign('no')}"></script>
<script type="text/javascript" src="{'javascripts/jquery/jquery.tablesorter.js'|ezdesign('no')}"></script>
<script type="text/javascript" src="{'javascripts/ezenhanceddebug/scripts.js'|ezdesign('no')}"></script>

<h2>eZEnhancedDebug</h2>

<div id="ezenhanceddebug-panel">
    <table id="sortable_table" class="ezenhanceddebug-datagrid" cellspacing="0" border="0">

    <thead>
        <tr>
        <th>&nbsp;Operator</th>
        <th>&nbsp;File</th>
        <th>&nbsp;Line</th>
        <th>&nbsp;Column</th>
        <th>&nbsp;Rows fetched <br/> ( fetch only )</th>
        <th>&nbsp;Iterations <br/> ( loops only )</th>
        <th>&nbsp;Time sec</th>
        </tr>
    </thead>

    {foreach $executionStack as $execution}
        <tr>
        <td>{$execution.operator}</td>
            <td>
                <span class="file"><a href="javascript:void(0)" onclick="$('#code_show').load({'ezenhanceddebug/pieceofcode/'|ezurl('single')}, {ldelim}File:'{$execution.file}',LineStart:{$execution.linestart},LineEnd:{$execution.lineend}{rdelim});">{$execution.file}</a></span></td>
            <td>{$execution.linestart}</td>
            <td>{$execution.col}</td>
            <td>{$execution.fetchedrows}</td>
            <td>{$execution.iterations}</td>
            <td>{$execution.time}</td>
        </tr>
    {/foreach}

    </table>
</div>

<h3>Wanted code</h3>
<pre><div id="code_show"></div></pre>

<h2>Page statistics</h2>
<table cellspacing="1">
    <tr>
        <td><b>Median time : </b></td>
        <td><b>{$medianTime}</b></td>
    </tr>

    <tr>
        <td><b>Average time : </b></td>
        <td><b>{$averageTime}</b></td>
    </tr>

    <tr>
        <td><b>Slowest fetch : </b></td>
        <td><b>{$slowestFetch.file} {$slowestFetch.linestart} {$slowestFetch.col}, {$slowestFetch.time}</b></td>
    </tr>

    <tr>
    <td><b>Code:</b></td>
    <td><pre>{$slowestFetch.pieceofcode}</pre></p></td>
    </tr>

    <tr>
        <td><b>Fetch number : </b></td>
        <td><b>{$fetchNumber}</b></td>
    </tr>

    <tr>
        <td><b>Cache-blocks number : </b></td>
        <td><b>{$cacheBlocksNumber}</b></td>
    </tr>
</table>