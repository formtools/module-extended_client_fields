{include file='modules_header.tpl'}

  <table cellpadding="0" cellspacing="0">
  <tr>
    <td width="45"><a href="index.php"><img src="images/icon_extended_client_fields.gif" border="0" width="34" height="34" /></a></td>
    <td class="title">
      <a href="../../admin/modules">{$LANG.word_modules}</a>
      <span class="joiner">&raquo;</span>
      <a href="./">{$L.module_name}</a>
      <span class="joiner">&raquo;</span>
      {$LANG.phrase_edit_field}
    </td>
  </tr>
  </table>

  {include file='messages.tpl'}

  <form action="{$same_page}" method="post" onsubmit="return rsv.validate(this, rules)">
    <input type="hidden" name="num_rows" id="num_rows" value="{$field_info.options|@count}" />
    <input type="hidden" name="field_order" id="field_order" value="{$field_info.field_order}" />

    <table cellspacing="1" cellpadding="1" border="0">
    <tr>
      <td width="150">{$L.phrase_page_and_location}</td>
      <td>
        <select name="template_hook">
          <option value="">{$LANG.phrase_please_select}</option>
          <optgroup label="{$L.phrase_main_account_page}">
            <option value="edit_client_main_top" {if $field_info.template_hook == "edit_client_main_top"}selected{/if}>{$L.word_top}</option>
            <option value="edit_client_main_middle" {if $field_info.template_hook == "edit_client_main_middle"}selected{/if}>{$L.word_middle}</option>
            <option value="edit_client_main_bottom" {if $field_info.template_hook == "edit_client_main_bottom"}selected{/if}>{$L.word_bottom}</option>
          </optgroup>
          <optgroup label="{$L.phrase_settings_page}">
            <option value="edit_client_settings_top" {if $field_info.template_hook == "edit_client_settings_top"}selected{/if}>{$L.word_top}</option>
            <option value="edit_client_settings_bottom" {if $field_info.template_hook == "edit_client_settings_bottom"}selected{/if}>{$L.word_bottom}</option>
          </optgroup>
        </select>
      </td>
    </tr>
    <tr>
      <td valign="top">{$LANG.phrase_admin_only}</td>
      <td valign="top">
        <input type="radio" name="admin_only" id="ao1" value="yes" {if $field_info.admin_only == "yes"}checked{/if} />
          <label for="ao1">{$LANG.word_yes}</label>
        <input type="radio" name="admin_only" id="ao2" value="no" {if $field_info.admin_only == "no"}checked{/if} />
          <label for="ao2">{$LANG.word_no}</label>
        <div class="medium_grey">
          {$L.notify_admin_only_field_explanation}
        </div>
      </td>
    </tr>
    <tr>
      <td>{$L.phrase_field_label}</td>
      <td><input type="text" name="field_label" style="width:550px" value="{$field_info.field_label|escape}" /></td>
    </tr>
    <tr>
      <td>{$L.phrase_field_type}</td>
      <td>
        <select name="field_type" id="field_type" onchange="ecf_ns.change_field_type(this.value)">
          <option value="" {if $field_info.field_type == ""}selected{/if}>{$LANG.phrase_please_select}</option>
          <option value="textbox" {if $field_info.field_type == "textbox"}selected{/if}>{$LANG.word_textbox}</option>
          <option value="textarea" {if $field_info.field_type == "textarea"}selected{/if}>{$LANG.word_textarea}</option>
          <option value="password" {if $field_info.field_type == "password"}selected{/if}>{$LANG.word_password}</option>
          <option value="radios" {if $field_info.field_type == "radios"}selected{/if}>{$LANG.phrase_radio_buttons}</option>
          <option value="checkboxes" {if $field_info.field_type == "checkboxes"}selected{/if}>{$LANG.word_checkboxes}</option>
          <option value="select" {if $field_info.field_type == "select"}selected{/if}>{$LANG.word_dropdown}</option>
          <option value="multi-select" {if $field_info.field_type == "multi-select"}selected{/if}>{$LANG.phrase_multi_select}</option>
        </select>
      </td>
    </tr>
    <tr>
      <td>{$L.phrase_default_value}</td>
      <td><input type="text" name="default_value" style="width:550px" value="{$field_info.default_value|escape}" /></td>
    </tr>
    <tr>
      <td>
        <input type="checkbox" name="is_required" id="is_required" {if $field_info.is_required == "yes"}checked{/if} />
          <label for="is_required">{$L.phrase_required_field}</label>
      </td>
      <td>
        <table cellspacing="0" cellpadding="0" width="100%">
        <tr>
          <td class="margin_right_large">{$L.phrase_error_string_c}</td>
          <td align="right"><input type="text" name="error_string" style="width:470px" value="{$field_info.error_string|escape}" /></td>
        </tr>
        </table>
      </td>
    </tr>
    </table>

    <div class="box margin_top_large" id="field_options_div"
      {if $field_info.field_type == "textbox" || $field_info.field_type == "textarea" || $field_info.field_type == "wysiwyg"
        || $field_info.field_type == "password"}
          style="display:none; width: 362px"
      {else}
        style="width: 362px"
      {/if}>
      <div style="padding: 6px">
        <div class="bold margin_bottom">{$LANG.phrase_field_options}</div>

        <div class="margin_bottom">
          {$L.word_orientation_c}
          <input type="radio" name="field_orientation" id="fo1" value="horizontal"
            {if $field_info.field_orientation == "horizontal"}checked{/if}
            {if $field_info.field_type == "select" || $field_info.field_type == "multi-select"}disabled{/if} />
            <label for="fo1">{$LANG.word_horizontal}</label>
          <input type="radio" name="field_orientation" id="fo2" value="vertical"
            {if $field_info.field_orientation == "vertical"}checked{/if}
            {if $field_info.field_type == "select" || $field_info.field_type == "multi-select"}disabled{/if} />
            <label for="fo2">{$LANG.word_vertical}</label>
          <input type="radio" name="field_orientation" id="fo3" value="na"
            {if $field_info.field_orientation == "na"}checked{/if}
            {if $field_info.field_type == "radios" || $field_info.field_type == "checkboxes"}disabled{/if} />
            <label for="fo3">{$LANG.word_na}</label>
        </div>

        <table cellspacing="1" cellpadding="0" id="field_options_table" class="list_table margin_bottom_large" style="width: 360px">
        <tbody>
          <tr>
            <th width="40"> </th>
            <th>{$LANG.phrase_display_text}</th>
            <th class="del"></th>
          </tr>
          {foreach from=$field_info.options item=option name=row}
            {assign var=count value=$smarty.foreach.row.iteration}
              <tr id="row_{$count}">
                <td class="medium_grey" align="center" id="field_option_{$count}_order">{$count}</td>
                <td><input type="text" style="width:98%" name="field_option_text_{$count}" value="{$option.option_text|escape}" /></td>
                <td class="del"><a href="#" onclick="ecf_ns.delete_field_option({$count})"></a></td>
              </tr>
            {/foreach}
          </tbody>
        </table>

        <div>
          <input type="button" value="{$LANG.phrase_add_row}" onclick="ecf_ns.add_field_option(null, null)" />
        </div>
      </div>
    </div>

    <p>
      <input type="submit" name="update" value="{$LANG.word_update}" />
      <input type="button" name="delete" value="{$LANG.word_delete}" class="red" onclick="page_ns.delete_field({$field_info.client_field_id})" />
    </p>

  </form>

{include file='modules_footer.tpl'}
