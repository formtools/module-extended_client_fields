<?php

require("../../global/library.php");

use FormTools\Core;
use FormTools\Modules;
use FormTools\Modules\ExtendedClientFields\Fields;

$module = Modules::initModulePage("admin");

$LANG = Core::$L;
$L = $module->getLangStrings();

$field_id = Modules::loadModuleField("extended_client_fields", "id", "field_id");

$success = true;
$message = "";
if (isset($_POST["update"])) {
    list($success, $message) = Fields::updateField($field_id, $_POST, $L);
}

$field_info = Fields::getField($field_id);
$num_options = count($field_info["options"]);

$page_vars = array(
    "g_success" => $success,
    "g_message" => $message,
    "head_title" => $L["phrase_edit_field"],
    "field_info" => $field_info,
    "js_messages" => array("word_delete")
);

$page_vars["head_js"] =<<< END
var rules = [];
rules.push("required,template_hook,{$L["validation_no_template_hook"]}");
rules.push("required,field_label,{$L["validation_no_field_label"]}");
rules.push("required,field_type,{$L["validation_no_field_type"]}");

$(function() { ecf_ns.num_rows = $num_options; });

var page_ns = {};
page_ns.delete_field = function(client_field_id) {
  if (confirm("{$L["confirm_delete_field"]}")) {
    window.location = 'index.php?delete=' + client_field_id;
  }
}
END;

$module->displayPage("templates/edit.tpl", $page_vars);
