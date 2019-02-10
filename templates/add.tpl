{ft_include file='modules_header.tpl'}

<table cellpadding="0" cellspacing="0">
    <tr>
        <td width="45"><a href="index.php"><img src="images/icon_extended_client_fields.gif" border="0" width="34"
                                                height="34"/></a></td>
        <td class="title">
            <a href="../../admin/modules">{$LANG.word_modules}</a>
            <span class="joiner">&raquo;</span>
            <a href="./">{$L.module_name}</a>
            <span class="joiner">&raquo;</span>
            {$LANG.phrase_add_field}
        </td>
    </tr>
</table>

{ft_include file='messages.tpl'}

<form action="{$same_page}" method="post" onsubmit="return rsv.validate(this, rules)">
    <input type="hidden" name="num_rows" id="num_rows" value="0"/>

    <table cellspacing="1" cellpadding="1" border="0" class="margin_bottom_large">
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
                <input type="radio" name="admin_only" id="ao1" value="yes"/>
                <label for="ao1">{$LANG.word_yes}</label>
                <input type="radio" name="admin_only" id="ao2" value="no" checked/>
                <label for="ao2">{$LANG.word_no}</label>
                <div class="medium_grey">
                    {$L.notify_admin_only_field_explanation}
                </div>
            </td>
        </tr>
        <tr>
            <td>{$L.phrase_field_label}</td>
            <td><input type="text" name="field_label" style="width:550px"/></td>
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
            <td valign="top">{$L.phrase_field_identifier}</td>
            <td>
                <input type="text" name="field_identifier" id="field_identifier" style="width:200px" />
                <div class="hint">
                    This field allows you to use this field with certain Form Tools core fields. It can contain
                    alphanumeric (a-Z) and underscore characters only. For more information see the
                    <a href="https://docs.formtools.org/modules/extended_client_fields/adding_fields/" target="_blank">help documentation</a>.
                </div>
            </td>
        </tr>
        <tr>
            <td>{$L.phrase_default_value}</td>
            <td><input type="text" name="default_value" style="width:550px"/></td>
        </tr>
        <tr>
            <td>
                <input type="checkbox" name="is_required" id="is_required"/>
                <label for="is_required">{$L.phrase_required_field}</label>
            </td>
            <td>
                <table cellspacing="0" cellpadding="0" width="100%">
                    <tr>
                        <td class="margin_right_large">{$L.phrase_error_string_c}</td>
                        <td align="right"><input type="text" name="error_string" style="width:470px" value=""/></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <div id="field_options_div" style="display:none">
        <div class="margin_bottom_large subtitle underline">{$LANG.phrase_field_options|upper}</div>
        <table>
            <tr>
                <td width="140">{$L.word_orientation}</td>
                <td>
                    <input type="radio" name="field_orientation" id="fo1" value="horizontal" checked/>
                    <label for="fo1">{$LANG.word_horizontal}</label>
                    <input type="radio" name="field_orientation" id="fo2" value="vertical"/>
                    <label for="fo2">{$LANG.word_vertical}</label>
                    <input type="radio" name="field_orientation" id="fo3" value="na"/>
                    <label for="fo3">{$LANG.word_na}</label>
                </td>
            </tr>
            <tr>
                <td width="140" valign="top">{$L.phrase_field_option_source}</td>
                <td>
                    <table class="list_table">
                        <tr>
                            <td width="140">
                                <input type="radio" name="option_source" id="os1" value="option_list" checked/>
                                <label for="os1">{$LANG.phrase_option_list}</label>
                            </td>
                            <td>
                                {option_list_dropdown name_id="option_list_id"}
                            </td>
                        </tr>
                        <tr>
                            <td valign="top">
                                <input type="radio" name="option_source" id="os2" value="custom_list"/>
                                <label for="os2">{$L.phrase_custom_list}</label>
                            </td>
                            <td>
                                <table cellspacing="1" cellpadding="0" id="field_options_table" class="list_table"
                                       style="width: 448px">
                                    <tbody>
                                    <tr>
                                        <th width="40"></th>
                                        <th>{$LANG.phrase_display_text}</th>
                                        <th class="del"></th>
                                    </tr>
                                    </tbody>
                                </table>

                                <div>
                                    <a href="#" onclick="ecf_ns.add_field_option(null, null)">{$LANG.phrase_add_row}</a>
                                </div>
                            </td>
                        </tr>
                    </table>

                </td>
            </tr>
        </table>
    </div>

    <p>
        <input type="submit" name="add" value="{$L.phrase_add_field}"/>
    </p>

</form>

{ft_include file='modules_footer.tpl'}
