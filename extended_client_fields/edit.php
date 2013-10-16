<?php

require("../../global/library.php");
ft_init_module_page();
$field_id = ft_load_module_field("extended_client_fields", "id", "field_id");

if (isset($_POST["update"]))
  list($g_success, $g_message) = ecf_update_field($field_id, $_POST);

$field_info = ecf_get_field($field_id);
$num_options = count($field_info["options"]);

// ------------------------------------------------------------------------------------------------

$page_vars = array();
$page_vars["head_title"] = $L["phrase_edit_field"];
$page_vars["head_string"] = "<script type=\"text/javascript\" src=\"global/scripts/field_options.js\"></script>";
$page_vars["field_info"] = $field_info;
$page_vars["js_messages"] = array("word_delete");
$page_vars["head_js"] =<<< EOF
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
EOF;


ft_display_module_page("templates/edit.tpl", $page_vars);
