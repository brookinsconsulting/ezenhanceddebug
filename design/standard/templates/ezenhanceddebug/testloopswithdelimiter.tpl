<h3>loop : do</h3>
{def $counter = 0}
{do}
    {$counter}
    {set $counter=inc( $counter )}
    {delimiter},{/delimiter}
{/do while ne( $counter, 8 )}
{undef $counter}

<h3>loop : for</h3>
{for 0 to 7 as $counter}
    {$counter}
    {delimiter},{/delimiter}
{/for}

<h3>loop : foreach</h3>
{def $counter = array(0, 1, 2, 3, 4, 5, 6, 7)}
{foreach $counter as $c}
    {$c}
    {delimiter},{/delimiter}
{/foreach}

<h3>loop : foreach + fetch + delimiter</h3>
{foreach fetch('content','translation_list') as $language}
    {$language.locale_code|wash(javascript)}":"{$language.intl_language_name|wash(javascript)}
    {delimiter},{/delimiter}
{/foreach}

<h3>loop : foreach + fetch + comments</h3>
{literal}
*}{foreach fetch('content','translation_list') as $language}{*
    *}"{$language.locale_code|wash(javascript)}":"{$language.intl_language_name|wash(javascript)}"{*
    *}{delimiter},{/delimiter}{*
*}{/foreach}{**}
{/literal}

<h3>loop : foreach + fetch <span style="color:red">without</span> comments</h3>
{foreach fetch('content','translation_list') as $language}
    "{$language.locale_code|wash(javascript)}":"{$language.intl_language_name|wash(javascript)}"
    {delimiter},{/delimiter}
{/foreach}

<h3>loop : foreach + fetch <span style="color:red">without</span> comments <span style="color:red">without</span> quotes </h3>
{foreach fetch('content','translation_list') as $language}
    {$language.locale_code|wash(javascript)}":"{$language.intl_language_name|wash(javascript)}
    {delimiter},{/delimiter}
{/foreach}

<h3>loop : foreach + fetch + comments <span style="color:green">bug revealed</span></h3>
*}{foreach fetch('content','translation_list') as $language}{*
    *}"{$language.locale_code|wash(javascript)}":"{$language.intl_language_name|wash(javascript)}"{*
    *}{delimiter},{/delimiter}{*
*}{/foreach}{**}

{literal}
    *}{foreach fetch('content','translation_list') as $language}{*<br/>
        &nbsp;&nbsp;&nbsp;&nbsp;*}"{$language.locale_code|wash(javascript)}":"{$language.intl_language_name|wash(javascript)}"{*<br/>
        &nbsp;&nbsp;&nbsp;&nbsp;*}{delimiter},{/delimiter}<span style="color:red">{* <=remove this</span><br/>
    *}{/foreach}{**}
{/literal}

<h3>loop : while</h3>
{set $counter = 0}
{while ne( $counter, 8 )}
    {$counter}
    {delimiter},{/delimiter}
    {set $counter=inc( $counter )}
{/while}

{* deprecated
<h3>loop : section</h3>
{let elements=array('0', '1', '2', '3', '4', '5', '6', '7')}
    {section loop=$elements}
        {$item}
        {delimiter},{/delimiter}
    {/section}
{/let}
*}
