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
