<?php

require_once("../../global/library.php");

use FormTools\Core;
use FormTools\General;
use FormTools\Modules;
use FormTools\Modules\ExtendedClientFields\Fields;

$module = Modules::initModulePage("admin");

$LANG = Core::$L;
$L = $module->getLangStrings();

$success = true;
$message = "";
if (isset($_GET["delete"])) {
    list($success, $message) = Fields::deleteField($_GET["delete"], $L);
}

if (isset($_POST["add_field"])) {
    header("location: add.php");
    exit;
} else if (isset($_POST["update_order"])) {
    list($success, $message) = Fields::updateFieldOrder($_POST, $L);
}

$num_fields_per_page = 10;

$page = Modules::loadModuleField("extended_client_fields", "page", "extended_client_fields_page", 1);
$info = Fields::getClientFields($page);
$results     = $info["results"];
$num_results = $info["num_results"];

$page_vars = array(
    "results" => $results,
    "head_title" => $module->getModuleName(),
    "pagination" => General::getPageNav($num_results, $num_fields_per_page, $page, ""),
    "js_messages" => array("word_edit")
);

$page_vars["head_js"] =<<< END
var page_ns = {};
page_ns.delete_dialog = $("<div></div>");
page_ns.delete_field = function(client_field_id) {
  ft.create_dialog({
    dialog:      page_ns.delete_dialog,
    title:      "{$LANG["phrase_please_confirm"]}",
    content:    "{$L["confirm_delete_field"]}",
    popup_type: "warning",
    buttons: [{
      text: "{$LANG["word_yes"]}",
      click: function() {
        window.location = 'index.php?delete=' + client_field_id;
      }
    },
    {
      text: "{$LANG["word_no"]}",
      click: function() {
        $(this).dialog("close");
      }
    }]
  });
}
END;

$module->displayPage("templates/index.tpl", $page_vars);
