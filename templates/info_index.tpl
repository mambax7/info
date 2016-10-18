<{if $title}>
    <div class="infotitle">
        <{$title}>
    </div>
<{/if}>

<div class="infocontent">
    <{$content}>
    <br><br>
</div>

<{if $pagenav}>
    <br>
    <div class="infonavigation">
        <{$pagenavText}> <{$pagenav}>
    </div>
    <br>
<{/if}>

<div class="infofooter">
    <{if $footersicht || $xoops_isadmin || $info_contedit}>
        <{if $footersicht}>
            <div style="float:left; text-align:left; width:24%;">
                &nbsp;&nbsp;
                <{if $print==1}>
                    <a href="<{$modules_url}>/print.php?content=<{$id}>&amp;page=<{$page}>" target="_blank"
                       alt="<{$print_title}>" title="<{$print_title}>"><img src="<{$modules_url}>/assets/images/print.png"
                                                                            alt="<{$print_title}>"
                                                                            title="<{$print_title}>" width="22"
                                                                            height="22" border="0"/></a>
                    &nbsp;&nbsp;
                <{/if}>
                <a href="<{$email_link}>" target="_blank" alt="<{$email_title}>" title="<{$email_title}>"><img
                            src="<{$modules_url}>/assets/images/email.png" alt="<{$email_title}>" title="<{$email_title}>"
                            width="22" height="22" border="0"/></a>
                &nbsp;&nbsp;
            </div>
            <{if $last_update}>
                <div style="float:left; text-align:center;width:50%;">
                    &nbsp;&nbsp;
                    <{$last}> <{$last_update}>&nbsp;&nbsp;
                </div>
            <{/if}>
        <{/if}>
        <div style="float:right; text-align:right; width:24%;">
            <{if $xoops_isadmin || $info_contedit}>
                &nbsp;&nbsp;
                <a href="<{$modules_url}>/submit.php?op=edit&amp;id=<{$id}>" alt="<{$info_edit}>"
                   title="<{$info_edit}>"><img src="<{$modules_url}>/assets/images/edit.png" title="<{$info_edit}>"
                                               alt="<{$info_edit}>" width="16" height="16" border="0"/></a>
            <{/if}>
            <{if $xoops_isadmin || $info_contdel}>
                &nbsp;&nbsp;
                <a href="<{$modules_url}>/submit.php?op=delete&amp;id=<{$id}>" alt="<{$info_delete}>"
                   title="<{$info_delete}>"><img src="<{$modules_url}>/assets/images/delete.png" title="<{$info_delete}>"
                                                 alt="<{$info_delete}>" width="16" height="16" border="0"/></a>
            <{/if}>
            <{if $footersicht}>
                &nbsp;&nbsp;
                <a href="#top" title="<{$info_totop}>"><img src="<{$modules_url}>/assets/images/top.png"
                                                            title="<{$info_totop}>" alt="<{$info_totop}>" width="16"
                                                            height="16" border="0"/></a>
            <{/if}>
            &nbsp;&nbsp;
        </div>
        <div style="clear:both;"></div>
    <{/if}>
</div>


<{if $nocomments==0 && $comments}>
    <br>
    <div style="text-align: center; padding: 3px; margin: 3px;">
        <{$commentsnav}>
        <{$lang_notice}>
    </div>
    <div style="margin: 3px; padding: 3px;">
        <!-- start comments loop -->
        <{if $comment_mode == "flat"}>
            <{include file="db:system_comments_flat.tpl"}>
        <{elseif $comment_mode == "thread"}>
            <{include file="db:system_comments_thread.tpl"}>
        <{elseif $comment_mode == "nest"}>
            <{include file="db:system_comments_nest.tpl"}>
        <{/if}>
        <!-- end comments loop -->
    </div>
<{/if}>
