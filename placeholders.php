<?php

require("../../global/library.php");

use FormTools\Modules;
use FormTools\Modules\ExtendedClientFields\Fields;

$module = Modules::initModulePage("admin");

$info = Fields::getClientFields(1, "all");

$results = array();
foreach ($info["results"] as $row) {
	if (empty($row["field_identifier"])) {
		continue;
	}
	$results[] = $row;
}

$page_info = array(
	"results" => $results
);

$module->displayPage("templates/placeholders.tpl", $page_info);
