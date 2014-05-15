{include file='modules_header.tpl'}

	<div class="title">{$L.phrase_section_titles|upper}</div>

	{include file='messages.tpl'}
  
  <div class="margin_bottom_large">
    Your client fields can be inserted in different locations on the Edit Client pages, 
		visible by the administrator and client. This page gives you the option of providing a title 
		for each of the fields. These titles are omitted by default.
  </div>

	<form action="{$same_page}" method="post">

	  <table cellspacing="1" cellpadding="1" border="0">
	  <tr>
	    <td width="190" class="medium_grey">Main Account Page - Top</td>
			<td><input type="text" style="width:200px" name="main_account_page_top_title" value="{$main_account_page_top_title|escape}" /></td>
		</tr>
	  <tr>
	    <td width="190" class="medium_grey">Main Account Page - Middle</td>
			<td><input type="text" style="width:200px" name="main_account_page_middle_title" value="{$main_account_page_middle_title|escape}" /></td>
		</tr>
	  <tr>
	    <td width="190" class="medium_grey">Main Account Page - Bottom</td>
			<td><input type="text" style="width:200px" name="main_account_page_bottom_title" value="{$main_account_page_bottom_title|escape}" /></td>
		</tr>
	  <tr>
	    <td width="190" class="medium_grey">Settings Page - Top</td>
			<td><input type="text" style="width:200px" name="settings_page_top_title" value="{$settings_page_top_title|escape}" /></td>
		</tr>
	  <tr>
	    <td width="190" class="medium_grey">Settings Page - Bottom</td>
			<td><input type="text" style="width:200px" name="settings_page_bottom_title" value="{$settings_page_bottom_title|escape}" /></td>
		</tr>
		</table>

		<p>
		  <input type="submit" name="update" value="{$LANG.word_update}" />
		</p>

	</form>
	
{include file='modules_footer.tpl'}
