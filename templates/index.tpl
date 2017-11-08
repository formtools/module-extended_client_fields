{ft_include file='modules_header.tpl'}

  <table cellpadding="0" cellspacing="0">
  <tr>
    <td width="45"><a href="index.php"><img src="images/icon_extended_client_fields.gif" border="0" width="34" height="34" /></a></td>
    <td class="title">
      <a href="../../admin/modules">{$LANG.word_modules}</a>
      <span class="joiner">&raquo;</span>
      {$L.module_name}
    </td>
  </tr>
  </table>

  {ft_include file='messages.tpl'}

  <form action="index.php" method="post">

  {if $results|@count == 0}

    <div class="notify yellow_bg" class="margin_bottom_large">
      <div style="padding:8px">
        {$L.notify_no_fields}
      </div>
    </div>

  {else}

    {$pagination}

    <table class="list_table" style="width:100%" cellpadding="1" cellspacing="1">
    <tr style="height: 20px;">
      {if $results|@count > 1}<th width="40">{$LANG.word_order}</th>{/if}
      <th>{$LANG.phrase_field_label}</th>
      <th>{$LANG.phrase_field_type}</th>
      <th width="160" nowrap>{$LANG.phrase_admin_only}</th>
      <th class="edit"></th>
      <th class="del"></th>
    </tr>

    {foreach from=$results item=field name=row}
      {assign var='index' value=$smarty.foreach.row.index}
      {assign var='count' value=$smarty.foreach.row.iteration}
      {assign var='id' value=$field.client_field_id}

       <tr>
         {if $results|@count > 1}<td align="center"><input type="text" name="field_{$id}_order" size="3" value="{$field.field_order}" /></td>{/if}
         <td class="pad_left_small">{$field.field_label}</td>
         <td class="pad_left_small">{$field.field_type|ucwords}</td>
         <td align="center">
           {if $field.admin_only == "yes"}
             <span class="green">{$LANG.word_yes}</span>
           {else}
             <span class="blue">{$LANG.word_no}</span>
           {/if}
         </td>
        <td class="edit"><a href="edit.php?id={$id}"></a></td>
        <td class="del"><a href="#" onclick="return page_ns.delete_field({$id})"></a></td>
      </tr>
    {/foreach}
    </table>

  {/if}

    <p>
      {if $results|@count > 1}
        <input type="submit" name="update_order" value="{$L.phrase_update_order}" />
      {/if}
      <input type="submit" name="add_field" value="{$L.phrase_add_new_field}" />
    </p>
  </form>

{ft_include file='modules_footer.tpl'}
