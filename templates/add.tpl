{include file='modules_header.tpl'}

  <table cellpadding="0" cellspacing="0">
  <tr>
    <td width="45"><a href="index.php"><img src="images/icon_extended_client_fields.gif" border="0" width="34" height="34" /></a></td>
    <td class="title">
      <a href="../../admin/modules">{$LANG.word_modules}</a>
      <span class="joiner">&raquo;</span>
      <a href="./">{$L.module_name}</a>
      <span class="joiner">&raquo;</span>
      {$LANG.phrase_add_field}
    </td>
  </tr>
  </table>

  {include file='messages.tpl'}

  <form action="{$same_page}" method="post" onsubmit="return rsv.validate(this, rules)">
    <input type="hidden" name="num_rows" id="num_rows" value="0" />

    <table cellspacing="1" cellpadding="1" border="0">
    <tr>
      <td width="150">{$L.phrase_page_and_location}</td>
      <td>
        <select name="template_hook">
          <option value="">{$LANG.phrase_please_select}</option>
          <optgroup label="{$L.phrase_main_account_page}">
            <option value="edit_client_main_top">{$L.word_top}</option>
            <option value="edit_client_main_middle">{$L.word_middle}</option>
            <option value="edit_client_main_bottom">{$L.word_bottom}</option>
          </optgroup>
          <optgroup label="{$L.phrase_settings_page}">
            <option value="edit_client_settings_top">{$L.word_top}</option>
            <option value="edit_client_settings_bottom">{$L.word_bottom}</option>
          </optgroup>
        </select>
      </td>
    </tr>
    <tr>
      <td valign="top">{$LANG.phrase_admin_only}</td>
      <td valign="top">
        <input type="radio" name="admin_only" id="ao1" value="yes" />
          <label for="ao1">{$LANG.word_yes}</label>
        <input type="radio" name="admin_only" id="ao2" value="no" checked />
          <label for="ao2">{$LANG.word_no}</label>
        <div class="medium_grey">
          {$L.notify_admin_only_field_explanation}
        </div>
      </td>
    </tr>
    <tr>
      <td>{$L.phrase_field_label}</td>
      <td><input type="text" name="field_label" style="width:550px" /></td>
    </tr>
    <tr>
      <td>{$L.phrase_field_type}</td>
      <td>
        <select name="field_type" id="field_type">
          <option value="" selected>{$LANG.phrase_please_select}</option>
          <option value="textbox">{$LANG.word_textbox}</option>
          <option value="textarea">{$LANG.word_textarea}</option>
          <option value="password">{$LANG.word_password}</option>
          <option value="radios">{$LANG.phrase_radio_buttons}</option>
          <option value="checkboxes">{$LANG.word_checkboxes}</option>
          <option value="select">{$LANG.word_dropdown}</option>
          <option value="multi-select">{$LANG.phrase_multi_select}</option>
        </select>
      </td>
    </tr>
    <tr>
      <td>{$L.phrase_default_value}</td>
      <td><input type="text" name="default_value" style="width:550px" /></td>
    </tr>
    <tr>
      <td>
        <input type="checkbox" name="is_required" id="is_required" />
          <label for="is_required">{$L.phrase_required_field}</label>
      </td>
      <td>
        <table cellspacing="0" cellpadding="0" width="100%">
        <tr>
          <td class="margin_right_large">{$L.phrase_error_string_c}</td>
          <td align="right"><input type="text" name="error_string" style="width:470px" value="" /></td>
        </tr>
        </table>
      </td>
    </tr>
    </table>

    <div class="box margin_top_large" id="field_options_div" style="display:none; width: 362px">
      <div style="padding: 6px">
        <div class="bold margin_bottom">{$LANG.phrase_field_options}</div>

        <div class="margin_bottom">
          {$L.word_orientation_c}
          <input type="radio" name="field_orientation" id="fo1" value="horizontal" />
            <label for="fo1">{$LANG.word_horizontal}</label>
          <input type="radio" name="field_orientation" id="fo2" value="vertical" />
            <label for="fo2">{$LANG.word_vertical}</label>
          <input type="radio" name="field_orientation" id="fo3" value="na" checked />
            <label for="fo3">{$LANG.word_na}</label>
        </div>

        <table cellspacing="1" cellpadding="0" id="field_options_table" class="list_table margin_bottom_large" style="width: 360px">
        <tbody>
          <tr>
            <th width="40"> </th>
            <th>{$LANG.phrase_display_text}</th>
            <th class="del"></th>
          </tr>
        </tbody>
        </table>

        <div>
          <input type="button" value="{$LANG.phrase_add_row}" onclick="ecf_ns.add_field_option(null, null)" />
        </div>
      </div>
    </div>

    <p>
      <input type="submit" name="add" value="{$L.phrase_add_field}" />
    </p>

  </form>

{include file='modules_footer.tpl'}
