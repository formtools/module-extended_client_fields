{ft_include file='modules_header.tpl'}

  <table cellpadding="0" cellspacing="0">
  <tr>
    <td width="45"><a href="index.php"><img src="images/icon_extended_client_fields.gif" border="0" width="34" height="34" /></a></td>
    <td class="title">
      <a href="../../admin/modules">{$LANG.word_modules}</a>
      <span class="joiner">&raquo;</span>
      <a href="./">{$L.module_name}</a>
      <span class="joiner">&raquo;</span>
      {$L.phrase_section_titles}
    </td>
  </tr>
  </table>

  {ft_include file='messages.tpl'}

  <div class="margin_bottom_large">
    {$L.text_client_field_titles_intro}
  </div>

  <form action="{$same_page}" method="post">

    <table cellspacing="1" cellpadding="1" border="0">
    <tr>
      <td width="190" class="medium_grey">{$L.phrase_main_account_top}</td>
      <td><input type="text" style="width:200px" name="main_account_page_top_title" value="{$main_account_page_top_title|escape}" /></td>
    </tr>
    <tr>
      <td class="medium_grey">{$L.phrase_main_account_middle}</td>
      <td><input type="text" style="width:200px" name="main_account_page_middle_title" value="{$main_account_page_middle_title|escape}" /></td>
    </tr>
    <tr>
      <td class="medium_grey">{$L.phrase_main_account_bottom}</td>
      <td><input type="text" style="width:200px" name="main_account_page_bottom_title" value="{$main_account_page_bottom_title|escape}" /></td>
    </tr>
    <tr>
      <td class="medium_grey">{$L.phrase_settings_page_top}</td>
      <td><input type="text" style="width:200px" name="settings_page_top_title" value="{$settings_page_top_title|escape}" /></td>
    </tr>
    <tr>
      <td class="medium_grey">{$L.phrase_settings_page_bottom}</td>
      <td><input type="text" style="width:200px" name="settings_page_bottom_title" value="{$settings_page_bottom_title|escape}" /></td>
    </tr>
    </table>

    <p>
      <input type="submit" name="update" value="{$LANG.word_update}" />
    </p>

  </form>

{ft_include file='modules_footer.tpl'}
