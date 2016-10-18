<div id="infomenu">
    <{foreach item=link from=$block.links}>
        <{if $link.kategorie == 1}>
            <{if $link.parent == 0}>
                <{if $link.click == 1}>
                    <a class="infocurrent" href="<{$link.address}>"
                       title="<{if $link.tooltip}><{$link.tooltip}><{else}><{$link.title}><{/if}>"><{$link.title}></a>
                <{else}>
                    <a class="infocurrent"><{$link.title}></a>
                <{/if}>
            <{else}>
                <{if $link.click == 1}>
                    <a class="infocurrent" href="<{$link.address}>"
                       title="<{if $link.tooltip}><{$link.tooltip}><{else}><{$link.title}><{/if}>"><{$link.title}></a>
                <{else}>
                    <a class="infocurrent"><{$link.title}></a>
                <{/if}>
            <{/if}>
        <{else}>
            <{if $link.parent == 0}>
                <{if $link.aktiv == 1}>
                    <a class="infoMain" href="<{$link.address}>"
                       title="<{if $link.tooltip}><{$link.tooltip}><{else}><{$link.title}><{/if}>"
                       <{if $link.target}>target=<{$link.target}><{/if}>><{$link.title}></a>
                <{/if}>
            <{else}>
                <{if $link.aktiv == 1}>
                    <a class="infoSub" href="<{$link.address}>"
                       title="<{if $link.tooltip}><{$link.tooltip}><{else}><{$link.title}><{/if}>"
                       <{if $link.target}>target=<{$link.target}><{/if}>><{$link.title}></a>
                <{/if}>
            <{/if}>
        <{/if}>
    <{/foreach}>
</div>
