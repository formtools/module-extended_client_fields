<?php

require("../../global/library.php");
$folder = dirname(__FILE__);
require_once("$folder/library.php");
ft_init_module_page();

if (isset($_POST["update"]))
	list($g_success, $g_message) = ecf_update_section_titles($_POST);

$page_vars = array();
$page_vars["head_title"] = "{$L["module_name"]} - {$L["phrase_section_titles"]}";
$page_vars["main_account_page_top_title"] = ft_get_module_settings("main_account_page_top_title");
$page_vars["main_account_page_middle_title"] = ft_get_module_settings("main_account_page_middle_title");
$page_vars["main_account_page_bottom_title"] = ft_get_module_settings("main_account_page_bottom_title");
$page_vars["settings_page_top_title"] = ft_get_module_settings("settings_page_top_title");
$page_vars["settings_page_bottom_title"] = ft_get_module_settings("settings_page_bottom_title");


ft_display_module_page("templates/titles.tpl", $page_vars);
