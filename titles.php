<?php

require("../../global/library.php");

use FormTools\Modules;

$module = Modules::initModulePage("admin");
$L = $module->getLangStrings();

$success = true;
$message = "";
if (isset($_POST["update"])) {
    list($success, $message) = $module->updateSectionTitles($_POST);
}

$settings = Modules::getModuleSettings("", "extended_client_fields");

$page_vars = array(
    "g_success" => $success,
    "g_message" => $message,
    "head_title" => "{$L["module_name"]} - {$L["phrase_section_titles"]}",
    "main_account_page_top_title" => $settings["main_account_page_top_title"],
    "main_account_page_middle_title" => $settings["main_account_page_middle_title"],
    "main_account_page_bottom_title" => $settings["main_account_page_bottom_title"],
    "settings_page_top_title" => $settings["settings_page_top_title"],
    "settings_page_bottom_title" => $settings["settings_page_bottom_title"]
);

$module->displayPage("templates/titles.tpl", $page_vars);
