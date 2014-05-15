{* This template is used to generate the HTML to insert into the Edit Client pages *}

{if $title}
  <p class="subtitle margin_bottom_large">{$title}</p>
{else}
  <div class="margin_bottom_large"> </div>
{/if}

<table class="list_table margin_bottom_large" cellpadding="0" cellspacing="1">
{foreach from=$fields key=k item=extended_field}
  {assign var=client_field_id value=$extended_field.client_field_id}
  <tr>
    <td class="red" align="center" width="15">
      {if $extended_field.is_required == "yes"}
        *
        <script type="text/javascript">
        {if $extended_field.field_type == "checkboxes" || $extended_field.field_type == "multi-select"}
          rules.push("required,ecf_{$client_field_id}[],{$extended_field.error_string|escape}");
        {else}
          rules.push("required,ecf_{$client_field_id},{$extended_field.error_string|escape}");
        {/if}
        </script>
      {/if}
    </td>
    <td class="pad_left_small" width="180">{$extended_field.field_label}</td>
    <td>
      {if $extended_field.field_type == "textbox"}
        <input type="text" name="ecf_{$client_field_id}" value="{$extended_field.content|escape}" size="50" />
      {elseif $extended_field.field_type == "textarea"}
        <textarea name="ecf_{$client_field_id}" style="width:98%; height: 60px">{$extended_field.content}</textarea>
      {elseif $extended_field.field_type == "password"}
        <input type="password" name="ecf_{$client_field_id}" value="{$extended_field.content|escape}" size="20" />
      {elseif $extended_field.field_type == "radios"}

        {foreach from=$extended_field.options key=k2 item=option name=row}
          {assign var="count" value=$smarty.foreach.row.iteration}
          {assign var="escaped_value" value=$option.option_text}
          <input type="radio" name="ecf_{$client_field_id}" id="eft_{$client_field_id}_{$count}" value="{$option.option_text|escape}"
            {if $escaped_value == $extended_field.content}checked{/if} />
            <label for="eft_{$client_field_id}_{$count}">{$option.option_text|escape}</label>
            {if $extended_field.field_orientation == "vertical"}<br />{/if}
        {/foreach}

      {elseif $extended_field.field_type == "checkboxes"}

        {foreach from=$extended_field.options key=k2 item=option name="row"}
          {assign var="count" value=$smarty.foreach.row.iteration}
          {assign var="escaped_value" value=$option.option_text|escape}
          <input type="checkbox" name="ecf_{$client_field_id}[]" id="eft_{$client_field_id}_{$count}" value="{$option.option_text|escape}"
            {if $escaped_value|in_array:$extended_field.content}checked{/if} />
            <label for="eft_{$client_field_id}_{$count}">{$option.option_text|escape}</label>
            {if $extended_field.field_orientation == "vertical"}<br />{/if}
        {/foreach}

      {elseif $extended_field.field_type == "select"}

        <select name="ecf_{$client_field_id}">
          {foreach from=$extended_field.options key=k2 item=option}
            {assign var="escaped_value" value=$option.option_text|escape}
            <option value="{$option.option_text|escape}" {if $escaped_value == $extended_field.content}selected{/if}>{$option.option_text}</option>
           {/foreach}
        </select>

      {elseif $extended_field.field_type == "multi-select"}

        <select name="ecf_{$client_field_id}[]" multiple size="4">
          {foreach from=$extended_field.options key=k2 item=option}
            {assign var="escaped_value" value=$option.option_text|escape}
            <option value="{$option.option_text|escape}"
              {if $escaped_value|in_array:$extended_field.content}selected{/if}>{$option.option_text}</option>
           {/foreach}
        </select>

      {/if}
    </td>
  </tr>
{/foreach}
</table>
