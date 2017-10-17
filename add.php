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

$page_vars = array();
$page_vars["head_title"] = $L["phrase_add_field"];
$page_vars["js_messages"] = array("word_delete");
$page_vars["head_js"] =<<< EOF
var rules = [];
rules.push("required,template_hook,{$L["validation_no_template_hook"]}");
rules.push("required,field_label,{$L["validation_no_field_label"]}");
rules.push("required,field_type,{$L["validation_no_field_type"]}");
$(function() {
    ecf_ns.add_field_option(null, null);
    ecf_ns.add_field_option(null, null);
    $("#field_type").val("").bind("change keyup", function() {
        ecf_ns.change_field_type(this.value);
    });
});
EOF;

$module->displayPage("templates/add.tpl", $page_vars);
