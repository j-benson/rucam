<?  include 'main_page_customization.php'; ?>

<?
		foreach ($classes as $class_key => $class_value)
		{
			if($here == $class_value)
			{
?>

				<div id="div2" class="div_mainpage">

<? 

// let's create a context sensitive sub menu for each class of the system

				if ($mode == "" || $mode == "confirm_update")
				{
					echo "<p class='p1'><span class='this_subpage'>".ucfirst($class_value)."</span>: <a href='". $current_file_name."?here=".$class_value."&mode=create&class_obj=".$class_value."'>Create New ".singularName($class_value, true)."</a> ¦ <a href='". $current_file_name."?here=".$class_value."&mode=search&class_obj=".$class_value."'>Search ".ucfirst($class_value)."</a> ¦ Help</p>";
				}
				else if ($mode == "create" || $mode == "confirm_create" )
				{
					echo "<p class='p1'><a href='". $current_file_name."?here=".$class_value."&class_obj=".$class_value."'>".ucfirst($class_value)."</a>: <span class='this_subpage'>Create New ".singularName($class_value, true)."</span> ¦ <a href='". $current_file_name."?here=".$class_value."&mode=search&class_obj=".$class_value."'>Search ".ucfirst($class_value)."</a> ¦ Help</p>";
				}
				else if ($mode == "search" || $mode == "confirm_search")
				{
					echo "<p class='p1'><a href='". $current_file_name."?here=".$class_value."&class_obj=".$class_value."'>".ucfirst($class_value)."</a>: <a href='". $current_file_name."?here=".$class_value."&mode=create&class_obj=".$class_value."'>Create New ".singularName($class_value, true)."</a> ¦ <span class='this_subpage'>Search ".ucfirst($class_value)."</span> ¦ Help</p>";
				}
				else if ($mode == "update")
				{
					echo "<p class='p1'><a href='". $current_file_name."?here=".$class_value."&class_obj=".$class_value."'>".ucfirst($class_value)."</a>: <a href='". $current_file_name."?here=".$class_value."&mode=create&class_obj=".$class_value."'>Create New ".singularName($class_value, true)."</a> ¦ <a href='". $current_file_name."?here=".$class_value."&mode=search&class_obj=".$class_value."'>Search ".ucfirst($class_value)."</a> ¦ Help</p>";
				}
			}
		}
?>