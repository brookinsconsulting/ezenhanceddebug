{def $tplPath = ''}
{foreach $tplList as $tpl}
    {set $tplPath = concat( 'design:ezenhanceddebug/', $tpl )}<br/>
    {include uri=$tplPath}
{/foreach}
