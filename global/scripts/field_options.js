/**
 * This code is used for managing the Field Options for checkboxes, radio buttons and dropdowns. It's just a slimmed down version
 * of manage_field_option_groups.js from the Core, refactored.
 */

ecf_ns = {};
ecf_ns.num_rows = null; // set by onload function
ecf_ns.tmp_deleted_field_option_rows = [];
ecf_ns.current_field_type = null;

/**
 * This deletes a field option. Note: it doesn't remove the option in memory; that is handled by
 * _update_current_field_settings(), which is called when the user leaves the page & when
 */
ecf_ns.delete_field_option = function(row)
{
  // before we delete the row, get it's Order column value
  var order = parseInt($("field_option_" + row + "_order").innerHTML);

  $("row_" + row).remove();
  ecf_ns.tmp_deleted_field_option_rows.push(row);

  // update the order of all subsequent rows
  for (var i=row+1; i<=ecf_ns.num_rows; i++)
  {
    // if the row has already been deleted, just ignore it
    if (!$("field_option_" + i + "_order"))
      continue;

    $("field_option_" + i + "_order").innerHTML = order;
    order++;
  }

  return false;
}


ecf_ns.delete_all_rows = function()
{
  for (var i=1; i<=ecf_ns.num_rows; i++)
  {
    // if the row has already been deleted, just ignore it
    if (!$("field_option_" + i + "_order"))
      continue;

    $("row_" + i).remove();
  }

  ecf_ns.num_rows = 0;
}


/**
 * Adds a field option for the currently selected field (dropdown, radio or checkbox).
 */
ecf_ns.add_field_option = function(default_val, default_txt)
{
  // find out how many rows there already is
  var next_id = ++ecf_ns.num_rows;

	var row = document.createElement("tr");
	row.setAttribute("id", "row_" + next_id);

	// [1] first cell: row number
	var td1 = document.createElement("td");
	td1.setAttribute("align", "center");
	$(td1).addClassName("medium_grey");
	td1.setAttribute("id", "field_option_" + next_id + "_order");
  var num_deleted_rows = ecf_ns.tmp_deleted_field_option_rows.length;
	var row_num_label = next_id - num_deleted_rows;
	td1.appendChild(document.createTextNode(row_num_label));

	// [2] second <td> cell: "display text" field
	var td2 = document.createElement("td");
	var title = document.createElement("input");
	title.setAttribute("type", "text");
	title.setAttribute("name", "field_option_text_" + next_id);
	title.setAttribute("id", "field_option_text_" + next_id);
	title.style.cssText = "width: 98%";
	if (default_txt != null)
	  title.setAttribute("value", default_txt);
	td2.appendChild(title);

	// [4] delete column
	var td3 = document.createElement("td");
	td3.setAttribute("align", "center");
	td3.className = "del";
	var del_link = document.createElement("a");
	del_link.setAttribute("href", "#");
	del_link.onclick = ecf_ns.delete_field_option.bind(this, next_id);
	del_link.appendChild(document.createTextNode(g.messages["word_delete"].toUpperCase()));
	td3.appendChild(del_link);

	// add the table data cells to the row
	row.appendChild(td1);
	row.appendChild(td2);
	row.appendChild(td3);

	// add the row to the table
	var tbody = $("field_options_table").getElementsByTagName("tbody")[0];
	tbody.appendChild(row);

	$("num_rows").value = ecf_ns.num_rows;
}


/**
 * This relies on the ecf.current_field_type having been set in the page.
 */
ecf_ns.change_field_type = function(choice)
{
  if (choice == ecf_ns.current_field_type)
    return;
  
  if (choice == "radios" || choice == "checkboxes" || choice == "select" || choice == "multi-select")
  {
    if ($("field_options_div").style.display == "none")
      Effect.Appear($("field_options_div"), {duration: 0.5} );
			
		if (choice == "radios" || choice == "checkboxes")
		{
		  $("fo1").disabled = false;
		  $("fo2").disabled = false;
		  $("fo3").disabled = true;
			
			if ($("fo3").checked)
			  $("fo1").checked = true; 
		}
		else
		{
		  $("fo1").disabled = true;
		  $("fo2").disabled = true;
		  $("fo3").disabled = false;
		  $("fo3").checked = true;					
		}
  }
  else
  {
    if ($("field_options_div").style.display != "none")
      Effect.Fade($("field_options_div"), {duration: 0.5} );  
  }

	ecf_ns.current_field_type = choice;
}