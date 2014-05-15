<?php

$STRUCTURE = array();
$STRUCTURE["tables"] = array();
$STRUCTURE["tables"]["module_extended_client_fields"] = array(
  array(
    "Field"   => "client_field_id",
    "Type"    => "mediumint(8) unsigned",
    "Null"    => "NO",
    "Key"     => "PRI",
    "Default" => ""
  ),
  array(
    "Field"   => "template_hook",
    "Type"    => "varchar(255)",
    "Null"    => "YES",
    "Key"     => "",
    "Default" => ""
  ),
  array(
    "Field"   => "admin_only",
    "Type"    => "enum('yes','no')",
    "Null"    => "YES",
    "Key"     => "",
    "Default" => ""
  ),
  array(
    "Field"   => "field_label",
    "Type"    => "varchar(255)",
    "Null"    => "NO",
    "Key"     => "",
    "Default" => ""
  ),
  array(
    "Field"   => "field_type",
    "Type"    => "enum('textbox','textarea','password','radios','checkboxes','select','multi-select')",
    "Null"    => "NO",
    "Key"     => "",
    "Default" => ""
  ),
  array(
    "Field"   => "field_orientation",
    "Type"    => "enum('horizontal','vertical','na')",
    "Null"    => "NO",
    "Key"     => "",
    "Default" => "na"
  ),
  array(
    "Field"   => "default_value",
    "Type"    => "varchar(255)",
    "Null"    => "YES",
    "Key"     => "",
    "Default" => ""
  ),
  array(
    "Field"   => "is_required",
    "Type"    => "enum('yes','no')",
    "Null"    => "YES",
    "Key"     => "",
    "Default" => ""
  ),
  array(
    "Field"   => "error_string",
    "Type"    => "mediumtext",
    "Null"    => "YES",
    "Key"     => "",
    "Default" => ""
  ),
  array(
    "Field"   => "field_order",
    "Type"    => "smallint(6)",
    "Null"    => "NO",
    "Key"     => "",
    "Default" => ""
  )
);
$STRUCTURE["tables"]["module_extended_client_field_options"] = array(
  array(
    "Field"   => "client_field_id",
    "Type"    => "mediumint(9)",
    "Null"    => "NO",
    "Key"     => "PRI",
    "Default" => ""
  ),
  array(
    "Field"   => "option_text",
    "Type"    => "varchar(255)",
    "Null"    => "YES",
    "Key"     => "",
    "Default" => ""
  ),
  array(
    "Field"   => "field_order",
    "Type"    => "smallint(6)",
    "Null"    => "NO",
    "Key"     => "PRI",
    "Default" => ""
  )
);

$HOOKS = array(
  array(
    "hook_type"       => "template",
    "action_location" => "admin_edit_client_main_top",
    "function_name"   => "",
    "hook_function"   => "ecf_display_fields",
    "priority"        => "50"
  ),
  array(
    "hook_type"       => "template",
    "action_location" => "admin_edit_client_main_middle",
    "function_name"   => "",
    "hook_function"   => "ecf_display_fields",
    "priority"        => "50"
  ),
  array(
    "hook_type"       => "template",
    "action_location" => "admin_edit_client_main_bottom",
    "function_name"   => "",
    "hook_function"   => "ecf_display_fields",
    "priority"        => "50"
  ),
  array(
    "hook_type"       => "template",
    "action_location" => "admin_edit_client_settings_top",
    "function_name"   => "",
    "hook_function"   => "ecf_display_fields",
    "priority"        => "50"
  ),
  array(
    "hook_type"       => "template",
    "action_location" => "admin_edit_client_settings_bottom",
    "function_name"   => "",
    "hook_function"   => "ecf_display_fields",
    "priority"        => "50"
  ),
  array(
    "hook_type"       => "template",
    "action_location" => "admin_edit_view_client_map_filter_dropdown",
    "function_name"   => "",
    "hook_function"   => "ecf_display_extended_field_options",
    "priority"        => "50"
  ),
  array(
    "hook_type"       => "template",
    "action_location" => "head_bottom",
    "function_name"   => "",
    "hook_function"   => "ecf_insert_head_js",
    "priority"        => "50"
  ),
  array(
    "hook_type"       => "code",
    "action_location" => "end",
    "function_name"   => "ft_admin_update_client",
    "hook_function"   => "ecf_admin_save_extended_client_fields",
    "priority"        => "50"
  ),
  array(
    "hook_type"       => "template",
    "action_location" => "edit_client_main_top",
    "function_name"   => "",
    "hook_function"   => "ecf_display_fields",
    "priority"        => "50"
  ),
  array(
    "hook_type"       => "template",
    "action_location" => "edit_client_main_middle",
    "function_name"   => "",
    "hook_function"   => "ecf_display_fields",
    "priority"        => "50"
  ),
  array(
    "hook_type"       => "template",
    "action_location" => "edit_client_main_bottom",
    "function_name"   => "",
    "hook_function"   => "ecf_display_fields",
    "priority"        => "50"
  ),
  array(
    "hook_type"       => "template",
    "action_location" => "edit_client_settings_top",
    "function_name"   => "",
    "hook_function"   => "ecf_display_fields",
    "priority"        => "50"
  ),
  array(
    "hook_type"       => "template",
    "action_location" => "edit_client_settings_bottom",
    "function_name"   => "",
    "hook_function"   => "ecf_display_fields",
    "priority"        => "50"
  ),
  array(
    "hook_type"       => "code",
    "action_location" => "end",
    "function_name"   => "ft_update_client",
    "hook_function"   => "ecf_client_save_extended_client_fields",
    "priority"        => "50"
  ),
  array(
    "hook_type"       => "code",
    "action_location" => "start",
    "function_name"   => "ft_get_view_filter_sql",
    "hook_function"   => "ecf_update_view_filter_sql_placeholders",
    "priority"        => "50"
  )
);


$FILES = array(
  "add.php",
  "database_integrity.php",
  "edit.php",
  "global/",
  "global/code/",
  "global/code/fields.php",
  "global/code/module.php",
  "global/code/section_titles.php",
  "global/scripts/",
  "global/scripts/field_options.js",
  "help.php",
  "images/",
  "images/icon_extended_client_fields.gif",
  "index.php",
  "lang/",
  "lang/en_us.php",
  "library.php",
  "module.php",
  "module_config.php",
  "smarty/",
  "smarty/section_html.tpl",
  "templates/",
  "templates/add.tpl",
  "templates/edit.tpl",
  "templates/help.tpl",
  "templates/index.tpl",
  "templates/titles.tpl",
  "titles.php"
);