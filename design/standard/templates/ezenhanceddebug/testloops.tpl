<h3>loop : do</h3>
{def $counter = 0}
{do}
    {$counter}, 
    {set $counter=inc( $counter )}
{/do while ne( $counter, 8 )}
{undef $counter}

<h3>loop : for</h3>
{for 0 to 7 as $counter}
    {$counter}, 
{/for}

<h3>loop : foreach</h3>
{def $counter = array(0, 1, 2, 3, 4, 5, 6, 7)}
{foreach $counter as $c}
    {$c},
{/foreach}

<h3>loop : while</h3>
{set $counter = 0}
{while ne( $counter, 8 )}
    {$counter},
    {set $counter=inc( $counter )}
{/while}

{* deprecated
<h3>loop : section</h3>
{let elements=array('0', '1', '2', '3', '4', '5', '6', '7')}
    {section loop=$elements}
        {$item},
    {/section}
{/let}
*}
