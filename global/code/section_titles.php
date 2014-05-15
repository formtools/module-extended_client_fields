<?php


/**
 * Updates the section titles.
 *
 * @param array $info
 * @return array [0] true/false
 *               [1] message
 */
function ecf_update_section_titles($info)
{
  global $L;

  $settings = array(
	  "main_account_page_top_title"    => $info["main_account_page_top_title"],
	  "main_account_page_middle_title" => $info["main_account_page_middle_title"],
	  "main_account_page_bottom_title" => $info["main_account_page_bottom_title"],
	  "settings_page_top_title"        => $info["settings_page_top_title"],
	  "settings_page_bottom_title"     => $info["settings_page_bottom_title"]								
	);
  ft_set_module_settings($settings);

  return array(true, $L["notify_section_titles_updated"]);
}
