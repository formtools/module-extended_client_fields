<?php

require("../../global/library.php");

use FormTools\Core;
use FormTools\Modules;
use FormTools\Modules\ExtendedClientFields\Fields;

$module = Modules::initModulePage("admin");

$LANG = Core::$L;
$L = $module->getLangStrings();

$success = true;
$message = "";
if (isset($_POST["add"])) {
    list($success, $message) = Fields::addField($_POST, $L);
}

$page_vars = array(
    "g_success" => $success,
    "g_message" => $message,
    "head_title" => $L["phrase_add_field"],
    "js_messages" => array("word_delete")
);

$page_vars["head_js"] =<<< EOF
var check_field_identifier = function () {
	var field = $("#field_identifier");
	if (/[^a-zA-Z0-9_]/g.test(field.val())) {
		return [[field, "{$L["validation_invalid_field_identifier"]}"]]; 
	}
	return true;
}

var rules = [];
rules.push("required,template_hook,{$L["validation_no_template_hook"]}");
rules.push("required,field_label,{$L["validation_no_field_label"]}");
rules.push("required,field_type,{$L["validation_no_field_type"]}");
rules.push("function,check_field_identifier");
$(function() {
    ecf_ns.add_field_option(null, null);
    ecf_ns.add_field_option(null, null);
    $("#field_type").val("").bind("change keyup", function() {
        ecf_ns.change_field_type(this.value);
    });
});
EOF;

$module->displayPage("templates/add.tpl", $page_vars);
