<?php

require("../../global/library.php");
ft_init_module_page();

if (isset($_POST["add"]))
  list($g_success, $g_message) = ecf_add_field($_POST);

$page_vars = array();
$page_vars["head_title"] = $L["phrase_add_field"];
$page_vars["head_string"] = "<script src=\"global/scripts/field_options.js\"></script>";
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

ft_display_module_page("templates/add.tpl", $page_vars);
