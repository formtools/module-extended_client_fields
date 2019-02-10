<?php


namespace FormTools\Modules\ExtendedClientFields;

use FormTools\Core;
use FormTools\General;
use FormTools\Hooks;
use FormTools\Module as FormToolsModule;
use FormTools\Modules;

use Exception;


class Module extends FormToolsModule
{
    protected $moduleName = "Extended Client Fields";
    protected $moduleDesc = "This module lets you create additional fields for client accounts.";
    protected $author = "Ben Keen";
    protected $authorEmail = "ben.keen@gmail.com";
    protected $authorLink = "https://formtools.org";
    protected $version = "2.1.0";
    protected $date = "2019-01-10";
    protected $originLanguage = "en_us";
    protected $jsFiles = array(
        "{MODULEROOT}/scripts/field_options.js"
    );
	protected $cssFiles = array(
		"{MODULEROOT}/css/styles.css"
	);

    protected $nav = array(
        "module_name"           => array("index.php", false),
        "phrase_add_field"      => array("add.php", true),
        "phrase_section_titles" => array("titles.php", false),
		"word_placeholders"     => array("placeholders.php", false),
        "word_help"             => array("help.php", false)
    );

    public function install($module_id)
    {
        $db = Core::$db;

        $queries = array();
        $queries[] = "CREATE TABLE {PREFIX}module_extended_client_fields (
            client_field_id mediumint(8) unsigned NOT NULL auto_increment,
            template_hook varchar(255) default NULL,
            admin_only enum('yes','no') default NULL,
            field_label varchar(255) NOT NULL,
            field_type enum('textbox','textarea','password','radios','checkboxes','select','multi-select') NOT NULL,
            field_identifier varchar(255) NOT NULL,
            option_source enum('option_list', 'custom_list') NOT NULL DEFAULT 'option_list',
            option_list_id MEDIUMINT NULL,
            field_orientation enum('horizontal','vertical','na') NOT NULL default 'na',
            default_value varchar(255) default NULL,
            is_required enum('yes','no') default NULL,
            error_string mediumtext,
            field_order smallint(6) NOT NULL,
            PRIMARY KEY  (client_field_id)
          ) DEFAULT CHARSET=utf8";

        $queries[] = "CREATE TABLE {PREFIX}module_extended_client_field_options (
            client_field_id mediumint(9) NOT NULL,
            option_text varchar(255) default NULL,
            field_order smallint(6) NOT NULL,
            PRIMARY KEY (client_field_id, field_order)
          ) DEFAULT CHARSET=utf8";

        $queries[] = "INSERT INTO {PREFIX}settings (setting_name, setting_value, module) VALUES ('main_account_page_top_title', '', 'extended_client_fields')";
        $queries[] = "INSERT INTO {PREFIX}settings (setting_name, setting_value, module) VALUES ('main_account_page_middle_title', '', 'extended_client_fields')";
        $queries[] = "INSERT INTO {PREFIX}settings (setting_name, setting_value, module) VALUES ('main_account_page_bottom_title', '', 'extended_client_fields')";
        $queries[] = "INSERT INTO {PREFIX}settings (setting_name, setting_value, module) VALUES ('settings_page_top_title', '', 'extended_client_fields')";
        $queries[] = "INSERT INTO {PREFIX}settings (setting_name, setting_value, module) VALUES ('settings_page_bottom_title', '', 'extended_client_fields')";

        $success = true;
        $message = "";
        try {
            $db->beginTransaction();

            foreach ($queries as $query) {
                $db->query($query);
                $db->execute();
            }

            $this->resetHooks();

        } catch (Exception $e) {
            $success = false;
            $L = $this->getLangStrings();
            $message = General::evalSmartyString($L["notify_problem_installing"], array("error" => $e->getMessage()));
        }

        return array($success, $message);
    }


    public function uninstall($module_id)
    {
        $db = Core::$db;
        $L = $this->getLangStrings();

        // get a list of all client fields
        $client_fields = Fields::getClientFields(1, "all");

        // manually delete all fields. The ecf_delete_field function takes care of various
        // details that we don't want to worry about here
        foreach ($client_fields["results"] as $client_field_info) {
            Fields::deleteField($client_field_info["client_field_id"], $L);
        }

        $db->query("DROP TABLE {PREFIX}module_extended_client_fields");
        $db->execute();

        $db->query("DROP TABLE {PREFIX}module_extended_client_field_options");
        $db->execute();

        $db->query("DELETE FROM {PREFIX}settings WHERE module = 'extended_client_fields'");
        $db->execute();

        return array(true, "");
    }


    public function upgrade($module_id, $old_module_version)
    {
        $this->resetHooks();

		self::addFieldIdentifierFieldIfNotExists();
	}


    public function resetHooks()
    {
        Hooks::unregisterModuleHooks("extended_client_fields");

        // ADMIN template and code hooks
        Hooks::registerHook("template", "extended_client_fields", "admin_edit_client_main_top", "", "displayFields");
        Hooks::registerHook("template", "extended_client_fields", "admin_edit_client_main_middle", "", "displayFields");
        Hooks::registerHook("template", "extended_client_fields", "admin_edit_client_main_bottom", "", "displayFields");
        Hooks::registerHook("template", "extended_client_fields", "admin_edit_client_settings_top", "", "displayFields");
        Hooks::registerHook("template", "extended_client_fields", "admin_edit_client_settings_bottom", "", "displayFields");
        Hooks::registerHook("template", "extended_client_fields", "admin_edit_view_client_map_filter_dropdown", "", "displayExtendedFieldOptions", 50, true);
        Hooks::registerHook("template", "extended_client_fields", "head_bottom", "", "insertHeadJs");
        Hooks::registerHook("code", "extended_client_fields", "end", "FormTools\\Administrator::adminUpdateClient", "adminSaveExtendedClientFields");

        // CLIENT template and code hooks
        Hooks::registerHook("template", "extended_client_fields", "edit_client_main_top", "", "displayFields");
        Hooks::registerHook("template", "extended_client_fields", "edit_client_main_middle", "", "displayFields");
        Hooks::registerHook("template", "extended_client_fields", "edit_client_main_bottom", "", "displayFields");
        Hooks::registerHook("template", "extended_client_fields", "edit_client_settings_top", "", "displayFields");
        Hooks::registerHook("template", "extended_client_fields", "edit_client_settings_bottom", "", "displayFields");
        Hooks::registerHook("code", "extended_client_fields", "end", "FormTools\\Clients::updateClient", "clientSaveExtendedClientFields");

        // general code hooks
        Hooks::registerHook("code", "extended_client_fields", "start", "FormTools\\ViewFilters::getViewFilterSql", "updateViewFilterSqlPlaceholders");
		Hooks::registerHook("code", "extended_client_fields", "main", "FormTools\\User->getAccountPlaceholders", "getExtendedClientFieldPlaceholders");
    }


    /**
     * Updates the section titles.
     *
     * @param array $info
     * @return array [0] true/false
     *               [1] message
     */
    public function updateSectionTitles($info)
    {
        $L = $this->getLangStrings();

        $settings = array(
            "main_account_page_top_title"    => $info["main_account_page_top_title"],
            "main_account_page_middle_title" => $info["main_account_page_middle_title"],
            "main_account_page_bottom_title" => $info["main_account_page_bottom_title"],
            "settings_page_top_title"        => $info["settings_page_top_title"],
            "settings_page_bottom_title"     => $info["settings_page_bottom_title"]
        );
        Modules::setModuleSettings($settings);

        return array(true, $L["notify_section_titles_updated"]);
    }


    /**
     * This content is inserted into the head of the Edit View page. It supplements the list
     * of Extended Client Fields for use by the Client Map Filters JS.
     *
     * @param array $info
     */
    public function insertHeadJs($location, $info)
    {
        if ($info["page"] != "edit_view") {
            return;
        }

        $client_fields = Fields::getClientFields(1, "all");
        $section = $this->getModuleName();

        $js_rows = array();
        foreach ($client_fields["results"] as $client_field) {
            $client_field_id = $client_field["client_field_id"];
            $field_label     = htmlspecialchars($client_field["field_label"]);
            $js_rows[] = "page_ns.clientFields.push({val: \"ecf_{$client_field_id}\", text: \"$field_label\", section: \"$section\"})";
        }

        if (empty($js_rows)) {
            return;
        }

        $js = join(";\n", $js_rows);

        echo "<script>$js</script>";
    }


    // 2.1.0 added a new field_identifier column to allow the extended client fields to be used elsewhere in Form Tools,
	// namely as placeholders in the "Default Values for New Submissions" view setting:
	// https://github.com/formtools/core/issues/472
	// This adds the column to the database. Note: existing users will need to add in the identifiers manually to be
	// able to the user the feature
    public static function addFieldIdentifierFieldIfNotExists() {
		$db = Core::$db;

		if (!General::checkDbTableFieldExists("module_extended_client_fields", "field_identifier")) {
			$db->query("
				ALTER TABLE {PREFIX}module_extended_client_fields
				ADD field_identifier VARCHAR(255) NULL AFTER field_type
			");
			$db->execute();
		}
	}

    // ---------------- wrappers for hook calls ------------------

    public function displayFields($location, $template_vars) {
        Fields::displayFields($location, $template_vars);
    }

    public function displayExtendedFieldOptions($location, $template_vars) {
        Fields::displayExtendedFieldOptions($template_vars, $this->getLangStrings());
    }

    public function adminSaveExtendedClientFields($postdata) {
        Fields::adminSaveExtendedClientFields($postdata);
    }

    public function clientSaveExtendedClientFields($postdata) {
        Fields::clientSaveExtendedClientFields($postdata);
    }

    public function updateViewFilterSqlPlaceholders($info) {
        return Fields::updateViewFilterSqlPlaceholders($info);
    }

    // appends all ECF placeholders for use in the Default values for Submission fields (per View).
    public function getExtendedClientFieldPlaceholders($info) {

    	// we expect the account_id to be passed containing the client/admin account ID
    	if (!isset($info["account_id"]) || empty($info["account_id"])) {
    		return;
		}

    	// in { ecf_1 => "value", "ecf_2" => "value" } format
    	$placeholders = Fields::getClientPlaceholders();
    	if (empty($placeholders)) {
    		return;
		}

    	$keys = array_keys($placeholders);

    	$fields = Fields::getClientFields(1, "all");
    	$found = array();
    	foreach ($fields["results"] as $field_info) {
    		$current_key = "ecf_{$field_info["client_field_id"]}";
    		if (in_array($current_key, $keys) && !empty($field_info["field_identifier"])) {
				$found[$field_info["field_identifier"]] = $placeholders[$current_key];
			}
		}
		$info["placeholders"]["CLIENT"] = $found;

		return $info;
	}
}























