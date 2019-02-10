{ft_include file='modules_header.tpl'}

<table cellpadding="0" cellspacing="0">
    <tr>
        <td width="45">
            <a href="./"><img src="images/icon_extended_client_fields.gif" border="0" width="34" height="34"/></a>
        </td>
        <td class="title">
            <a href="../../admin/modules">{$LANG.word_modules}</a>
            <span class="joiner">&raquo;</span>
            <a href="./">{$L.module_name}</a>
            <span class="joiner">&raquo;</span>
            {$L.word_placeholders}
        </td>
    </tr>
</table>

{ft_include file='messages.tpl'}

<div class="margin_bottom_large">
    This page lists the available placeholders for use in fields within certain fields within Form Tools.
</div>

<table class="list_table">
<tr>
    <th width="150">{$LANG.word_field}</th>
    <th>{$L.word_placeholder}</th>
</tr>
{foreach from=$results item=field name=row}
    <tr>
        <td class="pad_left_small">
            {$field.field_label}
        </td>
        <td class="pad_left_small grey">
            {literal}{$CLIENT.{/literal}{$field.field_identifier}{literal}}{/literal}
        </td>
    </tr>
{/foreach}
</table>


{ft_include file='modules_footer.tpl'}




