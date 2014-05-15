<?php

require_once("../../global/library.php");
ft_init_module_page();

$folder = dirname(__FILE__);
require_once("$folder/library.php");

if (isset($_GET["delete"]))
  list($g_success, $g_message) = ecf_delete_field($_GET["delete"]);

if (isset($_POST["add_field"]))
{
  header("location: add.php");
  exit;
}
else if (isset($_POST["update_order"]))
{
  list($g_success, $g_message) = ecf_update_field_order($_POST);
}

$num_fields_per_page = 10;

$page = ft_load_module_field("extended_client_fields", "page", "extended_client_fields_page", 1);
$info = ecf_get_client_fields($page);
$results     = $info["results"];
$num_results = $info["num_results"];

// ------------------------------------------------------------------------------------------------

$page_vars = array();
$page_vars["results"] = $results;
$page_vars["head_title"] = $L["module_name"];
$page_vars["pagination"] = ft_get_page_nav($num_results, $num_fields_per_page, $page, "");
$page_vars["head_js"] =<<< EOF
var page_ns = {};
page_ns.delete_field = function(client_field_id)
{
  if (confirm("{$L["confirm_delete_field"]}"))
    window.location = 'index.php?delete=' + client_field_id;
}
EOF;

ft_display_module_page("templates/index.tpl", $page_vars);
