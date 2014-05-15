<?php


/**
 * The installation script for the module.
 */
function extended_client_fields__install($module_id)
{
  global $g_table_prefix, $LANG;

  $queries = array();
  $queries[] = "CREATE TABLE {$g_table_prefix}module_extended_client_fields (
    client_field_id mediumint(8) unsigned NOT NULL auto_increment,
    template_hook varchar(255) default NULL,
    admin_only enum('yes','no') default NULL,
    field_label varchar(255) NOT NULL,
    field_type enum('textbox','textarea','password','radios','checkboxes','select','multi-select') NOT NULL,
    field_orientation enum('horizontal','vertical','na') NOT NULL default 'na',
    default_value varchar(255) default NULL,
    is_required enum('yes','no') default NULL,
    error_string mediumtext,
    field_order smallint(6) NOT NULL,
    PRIMARY KEY  (client_field_id)
  ) ENGINE=InnoDB  DEFAULT CHARSET=utf8";

  $queries[] = "CREATE TABLE {$g_table_prefix}module_extended_client_field_options (
    client_field_id mediumint(9) NOT NULL,
    option_text varchar(255) default NULL,
    field_order smallint(6) NOT NULL,
    PRIMARY KEY (client_field_id, field_order)
  ) TYPE=InnoDB DEFAULT CHARSET=utf8";

  $queries[] = "INSERT INTO {$g_table_prefix}settings (setting_name, setting_value, module) VALUES ('main_account_page_top_title', '', 'extended_client_fields')";
  $queries[] = "INSERT INTO {$g_table_prefix}settings (setting_name, setting_value, module) VALUES ('main_account_page_middle_title', '', 'extended_client_fields')";
  $queries[] = "INSERT INTO {$g_table_prefix}settings (setting_name, setting_value, module) VALUES ('main_account_page_bottom_title', '', 'extended_client_fields')";
  $queries[] = "INSERT INTO {$g_table_prefix}settings (setting_name, setting_value, module) VALUES ('settings_page_top_title', '', 'extended_client_fields')";
  $queries[] = "INSERT INTO {$g_table_prefix}settings (setting_name, setting_value, module) VALUES ('settings_page_bottom_title', '', 'extended_client_fields')";

  $has_problem = false;
  foreach ($queries as $query)
  {
    $result = @mysql_query($query);
    if (!$result)
    {
      $has_problem = true;
      break;
    }
  }

  // if there was a problem, remove all the table and return an error
  $success = true;
  $message = "";
  if ($has_problem)
  {
    $success = false;
    @mysql_query("DROP TABLE {$g_table_prefix}module_extended_client_fields");
    @mysql_query("DROP TABLE {$g_table_prefix}module_extended_client_field_options");
    $mysql_error = mysql_error();
    $message     = ft_eval_smarty_string($LANG["extended_client_fields"]["notify_problem_installing"], array("error" => $mysql_error));
  }

  // ADMIN template and code hooks
  ft_register_hook("template", "extended_client_fields", "admin_edit_client_main_top", "", "ecf_display_fields");
  ft_register_hook("template", "extended_client_fields", "admin_edit_client_main_middle", "", "ecf_display_fields");
  ft_register_hook("template", "extended_client_fields", "admin_edit_client_main_bottom", "", "ecf_display_fields");
  ft_register_hook("template", "extended_client_fields", "admin_edit_client_settings_top", "", "ecf_display_fields");
  ft_register_hook("template", "extended_client_fields", "admin_edit_client_settings_bottom", "", "ecf_display_fields");
  ft_register_hook("template", "extended_client_fields", "admin_edit_view_client_map_filter_dropdown", "", "ecf_display_extended_field_options");
  ft_register_hook("code", "extended_client_fields", "end", "ft_admin_update_client", "ecf_admin_save_extended_client_fields");

  // CLIENT template and code hooks
  ft_register_hook("template", "extended_client_fields", "edit_client_main_top", "", "ecf_display_fields");
  ft_register_hook("template", "extended_client_fields", "edit_client_main_middle", "", "ecf_display_fields");
  ft_register_hook("template", "extended_client_fields", "edit_client_main_bottom", "", "ecf_display_fields");
  ft_register_hook("template", "extended_client_fields", "edit_client_settings_top", "", "ecf_display_fields");
  ft_register_hook("template", "extended_client_fields", "edit_client_settings_bottom", "", "ecf_display_fields");
  ft_register_hook("code", "extended_client_fields", "end", "ft_update_client", "ecf_client_save_extended_fields");

  return array($success, $message);
}


/**
 * The uninstallation script for the module.
 *
 * @return array [0] T/F, [1] success message
 */
function extended_client_fields__uninstall($module_id)
{
  global $g_table_prefix, $LANG;

  // get a list of all client fields
  $client_fields = ecf_get_client_fields(1, "all");

  // delete any data stored for the clients
  foreach ($client_fields["results"] as $client_field_info)
  {
    $client_field_id = $client_field_info["client_field_id"];
    mysql_query("DELETE FROM {$g_table_prefix}account_settings WHERE setting_name = 'ecf_{$client_field_id}'");
  }

  // delete the Pages module table
  $result = mysql_query("DROP TABLE {$g_table_prefix}module_extended_client_fields");
  $result = mysql_query("DROP TABLE {$g_table_prefix}module_extended_client_field_options");
  mysql_query("DELETE FROM {$g_table_prefix}settings WHERE module = 'extended_client_fields'");

  return array(true, "");
}


function extended_client_fields__upgrade($old_version, $new_version)
{
  global $g_table_prefix;

  $old_version_info = ft_get_version_info($old_version);
  //$new_version_info = ft_get_version_info($new_version);

  if ($old_version_info["release_date"] < 20091110)
  {
    ft_register_hook("template", "extended_client_fields", "admin_edit_view_client_map_filter_dropdown", "", "ecf_display_extended_field_options");
  }
}
