<?php


namespace FormTools\Modules\ExtendedClientFields;

use FormTools\Accounts;
use FormTools\Core;
use FormTools\General;
use FormTools\Modules;
use FormTools\Sessions;
use PDO, PDOException;
use Smarty;



class Fields
{
    /**
     * Adds a new field to the database.
     */
    public static function addField($info, $L)
    {
        $db = Core::$db;

        $next_order = self::getNextFieldOrder();

        $is_required = (isset($info["is_required"])) ? "yes" : "no";
        $option_list_id = (isset($info["option_list_id"])) ? $info["option_list_id"] : null;

        $db->query("
            INSERT INTO {PREFIX}module_extended_client_fields (template_hook, admin_only, field_label, field_type,
                option_source, option_list_id, field_orientation, default_value, is_required, error_string, field_order)
            VALUES (:template_hook, :admin_only, :field_label, :field_type, :option_source, :option_list_id, :field_orientation,
                :default_value, :is_required, :error_string, :list_order)
        ");
        $db->bindAll(array(
            "template_hook" => $info["template_hook"],
            "admin_only" => $info["admin_only"],
            "field_label" => $info["field_label"],
            "field_type" => $info["field_type"],
            "option_source" => $info["option_source"],
            "option_list_id" => $option_list_id,
            "field_orientation" => $info["field_orientation"],
            "default_value" => $info["default_value"],
            "is_required" => $is_required,
            "error_string" => $info["error_string"],
            "list_order" => $next_order
        ));
        $db->execute();

        $client_field_id = $db->getInsertId();

        // if this field had multiple options, add them too
        if ($info["field_type"] == "select" || $info["field_type"] == "multi-select" ||
            $info["field_type"] == "radios" || $info["field_type"] == "checkboxes") {
            for ($i=1; $i<=$info["num_rows"]; $i++) {
                if (!isset($info["field_option_text_$i"]) || empty($info["field_option_text_$i"])) {
                    continue;
                }
                $option_text = $info["field_option_text_$i"];

                $db->query("
                    INSERT INTO {PREFIX}module_extended_client_field_options (client_field_id, option_text, field_order)
                    VALUES (:client_field_id, :option_text, :field_order)
                ");
                $db->bindAll(array(
                    "client_field_id" => $client_field_id,
                    "option_text" => $option_text,
                    "field_order" => $i
                ));
                $db->execute();
            }
        }

        $message = General::evalSmartyString($L["notify_field_added"], array("client_field_id" => $client_field_id));
        return array(true, $message);
    }


    /**
     * Updates an extended field in the database.
     */
    public static function updateField($client_field_id, $info, $L)
    {
        $db = Core::$db;

        $is_required = (isset($info["is_required"])) ? "yes" : "no";
        $option_source  = (isset($info["option_source"])) ? $info["option_source"] : "option_list";
        $option_list_id = (isset($info["option_list_id"])) ? $info["option_list_id"] : null;

        try {
            $db->query("
                UPDATE {PREFIX}module_extended_client_fields
                SET     template_hook = :template_hook,
                        admin_only = :admin_only,
                        field_label = :field_label,
                        field_type = :field_type,
                        option_source = :option_source,
                        option_list_id = :option_list_id,
                        field_orientation = :field_orientation,
                        default_value = :default_value,
                        field_order = :field_order,
                        is_required = :is_required,
                        error_string = :error_string
                WHERE   client_field_id = :client_field_id
            ");
            $db->bindAll(array(
                "template_hook" => $info["template_hook"],
                "admin_only" => $info["admin_only"],
                "field_label" => $info["field_label"],
                "field_type" => $info["field_type"],
                "option_source" => $option_source,
                "option_list_id" => $option_list_id,
                "field_orientation" => $info["field_orientation"],
                "default_value" => $info["default_value"],
                "field_order" => $info["field_order"],
                "is_required" => $is_required,
                "error_string" => $info["error_string"],
                "client_field_id" => $client_field_id
            ));
            $db->execute();
        } catch (PDOException $e) {
            return array(false, $L["notify_field_not_updated"] . $e->getMessage());
        }

        $db->query("
            DELETE FROM {PREFIX}module_extended_client_field_options
            WHERE client_field_id = :client_field_id
        ");
        $db->bind("client_field_id", $client_field_id);
        $db->execute();

        // if this field had multiple options, add them too
        if ($info["field_type"] == "select" || $info["field_type"] == "multi-select" ||
            $info["field_type"] == "radios" || $info["field_type"] == "checkboxes") {
            for ($i=1; $i<=$info["num_rows"]; $i++) {
                if (!isset($info["field_option_text_$i"]) || empty($info["field_option_text_$i"])) {
                    continue;
                }

                $option_text = $info["field_option_text_$i"];
                $db->query("
                    INSERT INTO {PREFIX}module_extended_client_field_options (client_field_id, option_text, field_order)
                    VALUES (:client_field_id, :option_text, :field_order)
                ");
                $db->bindAll(array(
                    "client_field_id" => $client_field_id,
                    "option_text" => $option_text,
                    "field_order" => $i
                ));
                $db->execute();
            }
        }

        return array(true, $L["notify_field_updated"]);
    }


    /**
     * Returns a page (or all) client fields.
     *
     * @param integer $page_num
     * @param array $search a hash whose keys correspond to database column names
     * @return array
     */
    public static function getClientFields($page_num = 1, $num_per_page = 10, $search = array())
    {
        $db = Core::$db;

        $where_clause = "";
        if (!empty($search)) {
            $clauses = array();
            while (list($key, $value) = each($search)) {
                $clauses[] = "$key = '$value'";
            }
            if (!empty($clauses)) {
                $where_clause = "WHERE " . join(" AND ", $clauses);
            }
        }

        if ($num_per_page == "all") {
            $db->query("
                SELECT client_field_id
                FROM   {PREFIX}module_extended_client_fields
                $where_clause
                ORDER BY field_order
            ");
            $db->execute();
        } else {
            if (empty($page_num)) {
                $page_num = 1;
            }
            $first_item = ($page_num - 1) * $num_per_page;

            $db->query("
                SELECT client_field_id
                FROM   {PREFIX}module_extended_client_fields
                $where_clause
                ORDER BY field_order
                LIMIT $first_item, $num_per_page
            ");
            $db->execute();
        }

        $infohash = array();
        foreach ($db->fetchAll(PDO::FETCH_COLUMN) as $client_field_id) {
            $infohash[] = self::getField($client_field_id);
        }

        return array(
            "results" => $infohash,
            "num_results" => self::getNumClientFields()
        );
    }


    /**
     * Deletes an extended client field. This also has various ramifications throughout the
     * rest of the script, so it tidies up them all. Namely:
     *  -- it deletes any data added for clients in the removed field
     *  -- it deletes any Client Map View filters that map to this field
     */
    public static function deleteField($client_field_id, $L)
    {
        $db = Core::$db;

        $db->query("
            DELETE FROM {PREFIX}module_extended_client_field_options
            WHERE client_field_id = :client_field_id
        ");
        $db->bind("client_field_id", $client_field_id);
        $db->execute();

        $db->query("
            DELETE FROM {PREFIX}module_extended_client_fields
            WHERE client_field_id = :client_field_id
        ");
        $db->bind("client_field_id", $client_field_id);
        $db->execute();

        $db->query("
            DELETE FROM {PREFIX}account_settings
            WHERE setting_name = :setting_name
        ");
        $db->bind("setting_name", "ecf_{$client_field_id}");
        $db->execute();

        self::reorderFields();

        // delete any View filters that have this as a client map. We do a little extra legwork
        // here just to keep the database clean. Namely, Views have a "has_client_map_filter" flag
        // that may need to be updated after we delete the client map filters. So, we log the affected
        // View IDs and examine each. If there are no client map filters left after deleting the
        // filter, we update the "has_client_map_filter" value to "no".
        $db->query("
            SELECT view_id
            FROM {PREFIX}view_filters
            WHERE filter_values = :filter_values
        ");
        $db->bind("filter_values", "ecf_{$client_field_id}");
        $db->execute();

        $affected_view_ids = $db->fetchAll(PDO::FETCH_COLUMN);

        // delete the filters
        $db->query("DELETE FROM {PREFIX}view_filters WHERE filter_values = :filter_values");
        $db->bind("filter_values", "ecf_{$client_field_id}");
        $db->execute();

        foreach ($affected_view_ids as $view_id) {
            $db->query("
                SELECT count(*)
                FROM   {PREFIX}view_filters
                WHERE  view_id = :view_id
                AND    filter_type = 'client_map'
            ");
            $db->bind("view_id", $view_id);
            $db->execute();

            // if there are no results found, update the has_client_map_filter value
            if ($db->fetch(PDO::FETCH_COLUMN)) {
                $db->query("
                    UPDATE {PREFIX}views
                    SET    has_client_map_filter = 'no'
                    WHERE  view_id = :view_id
                ");
                $db->bind("view_id", $view_id);
                $db->execute();
            }
        }

        return array(true, $L["notify_field_deleted"]);
    }


    /**
     * Returns all information about a field.
     */
    public static function getField($field_id)
    {
        $db = Core::$db;

        $db->query("
            SELECT *
            FROM   {PREFIX}module_extended_client_fields
            WHERE  client_field_id = :field_id
        ");
        $db->bind("field_id", $field_id);
        $db->execute();

        $info = $db->fetch();
        $info["options"] = array();

        if ($info["field_type"] == "select" || $info["field_type"] == "multi-select" ||
            $info["field_type"] == "radios" || $info["field_type"] == "checkboxes") {
            $db->query("
                SELECT *
                FROM   {PREFIX}module_extended_client_field_options
                WHERE  client_field_id = :field_id
                ORDER BY field_order ASC
            ");
            $db->bind("field_id", $field_id);
            $db->execute();

            $info["options"] = $db->fetchAll();
        }

        return $info;
    }


    /**
     * This function handles the actual field generation for the form.
     */
    public static function displayFields($location, $template_vars)
    {
        $root_dir = Core::getRootDir();

        // okay! We have some stuff to show. Grab the section title, then
        $settings = Modules::getModuleSettings("", "extended_client_fields");

        $title = "";
        $is_admin = false;
        switch ($location) {
            case "admin_edit_client_main_top":
                $location = "edit_client_main_top";
                $is_admin = true;
                $title = $settings["main_account_page_top_title"];
                break;
            case "edit_client_main_top":
                $title = $settings["main_account_page_top_title"];
                break;
            case "admin_edit_client_main_middle":
                $location = "edit_client_main_middle";
                $is_admin = true;
                $title = $settings["main_account_page_middle_title"];
                break;
            case "edit_client_main_middle":
                $title = $settings["main_account_page_middle_title"];
                break;
            case "admin_edit_client_main_bottom":
                $location = "edit_client_main_bottom";
                $is_admin = true;
                $title = $settings["main_account_page_bottom_title"];
                break;
            case "edit_client_main_bottom":
                $title = $settings["main_account_page_bottom_title"];
                break;
            case "admin_edit_client_settings_top":
                $location = "edit_client_settings_top";
                $is_admin = true;
                $title = $settings["settings_page_top_title"];
                break;
            case "edit_client_settings_top":
                $title = $settings["settings_page_top_title"];
                break;
            case "admin_edit_client_settings_bottom":
                $location = "edit_client_settings_bottom";
                $is_admin = true;
                $title = $settings["settings_page_bottom_title"];
                break;
            case "edit_client_settings_bottom":
                $title = $settings["settings_page_bottom_title"];
                break;
        }

        $fields = self::getClientFields(1, "all", array("template_hook" => $location));

        if (empty($fields["results"])) {
            return "";
        }

        $smarty = new Smarty();
        $smarty->setCompileDir("$root_dir/themes/default/cache/");
        $smarty->addPluginsDir(array(
            "$root_dir/global/smarty_plugins",
            "$root_dir/modules/extended_client_fields/smarty_plugins/"
        ));

        // now look through the incoming client settings, passed through $template_vars and determine
        // the selected value for each field
        $field_info = array();
        foreach ($fields["results"] as $info) {
            if ($info["admin_only"] == "yes" && !$is_admin) {
                continue;
            }

            $client_field_id = $info["client_field_id"];

            if (!isset($template_vars["client_info"]["settings"]["ecf_{$client_field_id}"])) {
                $info["content"] = $info["default_value"];
            } else {
                $info["content"] = $template_vars["client_info"]["settings"]["ecf_{$client_field_id}"];
            }

            // if this was a checkbox group or multi-select dropdown, split the selected item(s) into an array
            if ($info["field_type"] == "checkboxes" || $info["field_type"] == "multi-select") {
                $info["content"] = explode("|", $info["content"]);
            }

            $field_info[] = $info;
        }

        if (empty($field_info)) {
            return "";
        }

        $smarty->assign("title", $title);
        $smarty->assign("fields", $field_info);

        // tack on all the template vars passed by the page
        while (list($key, $value) = each($template_vars)) {
            $smarty->assign($key, $value);
        }

        echo $smarty->fetch("$root_dir/modules/extended_client_fields/smarty_plugins/section_html.tpl");
    }


    /**
     * This function is called whenever the administrator updates the client, for either of the
     * main or settings tabs.
     */
    public static function adminSaveExtendedClientFields($postdata)
    {
        $db = Core::$db;
        $client_id = $postdata["infohash"]["client_id"];

        // Main tab
        if ($postdata["tab_num"] == 1) {
            // find out what (if any) extended fields have been created for this tab
            $db->query("
                SELECT client_field_id
                FROM   {PREFIX}module_extended_client_fields
                WHERE  template_hook = 'edit_client_main_top' OR
                        template_hook = 'edit_client_main_middle' OR
                        template_hook = 'edit_client_main_bottom'
            ");
            $db->execute();

            // this just standardizes the info for use by updateClientFields
            $postdata["info"] = $postdata["infohash"];

            self::updateClientFields($db->fetchAll(PDO::FETCH_COLUMN), $client_id, $postdata);
        }

        // Settings tab
        if ($postdata["tab_num"] == 2) {

            // find out what (if any) extended fields have been created for this tab
            $db->query("
                SELECT client_field_id
                FROM   {PREFIX}module_extended_client_fields
                WHERE  template_hook = 'edit_client_settings_top' OR
                       template_hook = 'edit_client_settings_bottom'
            ");
            $db->execute();

            $postdata["info"] = $postdata["infohash"];
            self::updateClientFields($db->fetchAll(PDO::FETCH_COLUMN), $client_id, $postdata);
        }
    }


    public static function clientSaveExtendedClientFields($postdata)
    {
        $db = Core::$db;

        $client_id = $postdata["account_id"];

        // Main tab
        if ($postdata["info"]["page"] == "main") {
            // find out what (if any) extended fields have been created for this tab
            $db->query("
                SELECT client_field_id
                FROM   {PREFIX}module_extended_client_fields
                WHERE  (template_hook = 'edit_client_main_top' OR
                       template_hook = 'edit_client_main_middle' OR
                       template_hook = 'edit_client_main_bottom') AND
                       admin_only = 'no'
            ");
            $db->execute();
            self::updateClientFields($db->fetchAll(PDO::FETCH_COLUMN), $client_id, $postdata);
        }

        // Settings tab
        if ($postdata["info"]["page"] == "settings") {

            // find out what (if any) extended fields have been created for this tab
            $db->query("
                SELECT client_field_id
                FROM   {PREFIX}module_extended_client_fields
                WHERE  (template_hook = 'edit_client_settings_top' OR
                       template_hook = 'edit_client_settings_bottom') AND
                       admin_only = 'no'
            ");
            $db->execute();
            self::updateClientFields($db->fetchAll(PDO::FETCH_COLUMN), $client_id, $postdata);
        }
    }


    /**
     * Called on the main fields page. This updates the orders of the entire list of
     * Extended Client Fields. Note: the option to sort the Fields only appears if there is
     * 2 or more fields.
     *
     * @param array $info the form contents
     * @return array Returns array with indexes:<br/>
     *               [0]: true/false (success / failure)<br/>
     *               [1]: message string<br/>
     */
    public static function updateFieldOrder($info, $L)
    {
        $db = Core::$db;

        // loop through all the fields in $info that are being re-sorted and compile a list of view_id => order pairs.
        $new_field_orders = array();
        foreach ($info as $key => $value) {
            if (preg_match("/^field_(\d+)_order$/", $key, $match)) {
                $client_field_id = $match[1];
                $new_field_orders[$client_field_id] = $value;
            }
        }

        // okay! Since we may have only updated a *subset* of all fields (the fields page is
        // arranged in pages), get a list of ALL extended client fields, add them to
        // $new_field_orders and sort the entire lot of them in one go
        $db->query("
            SELECT client_field_id, field_order
            FROM   {PREFIX}module_extended_client_fields
        ");
        $db->execute();

        foreach ($db->fetchAll() as $row) {
            if (!array_key_exists($row["client_field_id"], $new_field_orders)) {
                $new_field_orders[$row["client_field_id"]] = $row["field_order"];
            }
        }

        // sort by the ORDER (the value - non-key - of the hash)
        asort($new_field_orders);

        $count = 1;
        foreach ($new_field_orders as $client_field_id => $order) {
            $db->query("
                UPDATE {PREFIX}module_extended_client_fields
                SET	   field_order = :field_order
                WHERE  client_field_id = :client_field_id
            ");
            $db->bindAll(array(
                "field_order" => $count,
                "client_field_id" => $client_field_id
            ));
            $db->execute();
            $count++;
        }

        // return success
        return array(true, $L["notify_field_order_updated"]);
    }


    /**
     * This function is attached to the admin_edit_view_client_map_filter_dropdown hook. It populates the
     * Edit View -> Client Map Filters -> client fields dropdown with any additional fields defined in this module.
     */
    public static function displayExtendedFieldOptions($location, $template_vars)
    {
        $LANG = Core::$L;

        Modules::getModuleInstance("extended_client_fields");

        $client_fields = self::getClientFields(1, "all");
        if ($client_fields["num_results"] == 0) {
            return;
        }

        $current_row        = $template_vars["count"];
        $client_map_filters = $template_vars["client_map_filters"];
        $selected_value     = $client_map_filters[$current_row-1]["filter_values"];

        echo "<optgroup label=\"{$LANG["extended_client_fields"]["module_name"]}\">\n";
        foreach ($client_fields["results"] as $field_info) {
            $field_label = htmlspecialchars($field_info["field_label"]);
            $selected    = ($selected_value == "ecf_" . $field_info["client_field_id"]) ? "selected" : "";
            echo "<option value=\"ecf_{$field_info["client_field_id"]}\" {$selected}>{$field_label}</option>\n";
        }

        echo "</optgroup>\n";
    }


    /**
     * Called whenever a user deletes a field. This updates the field order.
     */
    public static function reorderFields()
    {
        $db = Core::$db;

        $db->query("
            SELECT client_field_id
            FROM {PREFIX}module_extended_client_fields
            ORDER BY field_order ASC
        ");
        $db->execute();

        $order = 1;
        foreach ($db->fetchAll() as $row) {
            $client_field_id = $row["client_field_id"];
            $db->query("
                UPDATE {PREFIX}module_extended_client_fields
                SET    field_order = :field_order
                WHERE  client_field_id = :client_field_id
            ");
            $db->bindAll(array(
                "field_order" => $order,
                "client_field_id" => $client_field_id
            ));
            $db->execute();
            $order++;
        }
    }


    /**
     * This is called by the "start" hook in the ft_get_view_filter_sql function. It
     * adds the extended client field placeholder variable with the e
     */
    public static function updateViewFilterSqlPlaceholders($info)
    {
        $is_client_account = Core::$user->getAccountType() == "client";

        if ($is_client_account) {
            $settings = Sessions::get("account.settings");
            if (is_array($settings)) {
                foreach ($settings as $key => $value) {
                    if (preg_match("/^ecf_(\d)+$/", $key, $matches)) {
                        $info["placeholders"][$key] = $value;
                    }
                }
            }
        }

        return $info;
    }


    private static function getNextFieldOrder ()
    {
        $db = Core::$db;

        $db->query("
            SELECT field_order
            FROM {PREFIX}module_extended_client_fields
            ORDER BY field_order DESC LIMIT 1
        ");
        $db->execute();

        $count = $db->fetch(PDO::FETCH_COLUMN);

        $next_order = 1;
        if (!empty($count)) {
            $next_order = $count + 1;
        }

        return $next_order;
    }


    private static function getNumClientFields ()
    {
        $db = Core::$db;

        $db->query("SELECT count(*) FROM {PREFIX}module_extended_client_fields");
        $db->execute();

        return $db->fetch(PDO::FETCH_COLUMN);
    }


    private static function updateClientFields ($client_field_ids, $client_id, $postdata)
    {
        if (empty($client_field_ids)) {
            return;
        }

        $settings = array();
        foreach ($client_field_ids as $id) {
            $settings["ecf_{$id}"] = "";
            if (isset($postdata["info"]["ecf_{$id}"])) {
                if (is_array($postdata["info"]["ecf_{$id}"])) {
                    $settings["ecf_{$id}"] = join("|", $postdata["info"]["ecf_{$id}"]);
                } else {
                    $settings["ecf_{$id}"] = $postdata["info"]["ecf_{$id}"];
                }
            }
        }

        Accounts::setAccountSettings($client_id, $settings);
    }
}
