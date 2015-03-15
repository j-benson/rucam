<?  include 'main_page_customization.php'; ?>

<?

		foreach ($classes as $class_key => $class_value)
		{
			if($here == $class_value)
			{
?>
				<div id="div2" class="div_mainpage">
<? 
				/// CUSTOM SUB-MENUS ///
				if ($here == T_ACCESS) {
					if ($mode == "") {
						$page = "home";
					} else if ($mode == "search") {
						$page = "search";
					}
					echo "<p class='p1'>";
					echo ($page == "home" ? "<span class='this_subpage'>".ucfirst($class_value)."</span>: " : "<a href='". $current_file_name."?here=".$class_value."&class_obj=".$class_value."'>".ucfirst($class_value)."</a>: ");
					echo ($page == "search" ? "<span class='this_subpage'>Search ".ucfirst($class_value)."<img src='./include/images/MagnifyingGlass-small.png' /></a></span> ¦ " : "<a href='". $current_file_name."?here=".$class_value."&mode=search&class_obj=".$class_value."'>Search ".ucfirst($class_value)."<img src='./include/images/MagnifyingGlass-small.png' /></a></span> ¦ ");
					echo "<a href='help.html'>Help<img src='./include/images/Question-small.png' /></a></span></p>";
					unset($page);
					// ^^ In my opinion a much better way of building a dynamic menu than that horriblness below ^^ 
				} else {

// let's create a context sensitive sub menu for each class of the system

					if ($mode == "" || $mode == "confirm_update")
					{
						echo "<p class='p1'><span class='this_subpage'>".ucfirst($class_value)."</span>: <a href='". $current_file_name."?here=".$class_value."&mode=create&class_obj=".$class_value."'>Create New ".singularName($class_value, true)."<img src='./include/images/pencil-small.png' /></a> ¦ <a href='". $current_file_name."?here=".$class_value."&mode=search&class_obj=".$class_value."'>Search ".ucfirst($class_value)."<img src='./include/images/MagnifyingGlass-small.png' /></a></span> ¦ <a href='help.html'>Help<img src='./include/images/Question-small.png' /></a></span></p>";
						
					}
					else if ($mode == "create" || $mode == "confirm_create" )
					{
						echo "<p class='p1'><a href='". $current_file_name."?here=".$class_value."&class_obj=".$class_value."'>".ucfirst($class_value)."</a>: <span class='this_subpage'>Create New ".singularName($class_value, true)."<img src='./include/images/pencil-small.png' /></a> ¦ <a href='". $current_file_name."?here=".$class_value."&mode=search&class_obj=".$class_value."'>Search ".ucfirst($class_value)."<img src='./include/images/MagnifyingGlass-small.png' /></a></span> ¦ <a href='help.html'>Help<img src='./include/images/Question-small.png' /></a></span></p>";
						
					}
					else if ($mode == "search" || $mode == "confirm_search")
					{
						echo "<p class='p1'><a href='". $current_file_name."?here=".$class_value."&class_obj=".$class_value."'>".ucfirst($class_value)."</a>: <a href='". $current_file_name."?here=".$class_value."&mode=create&class_obj=".$class_value."'>Create New ".singularName($class_value, true)."<img src='./include/images/pencil-small.png' /></a> ¦ <span class='this_subpage'>Search ".ucfirst($class_value)."<img src='./include/images/MagnifyingGlass-small.png' /></a></span> ¦ <a href='help.html'>Help<img src='./include/images/Question-small.png' /></a></span></p>";
					}
					else if ($mode == "update")
					{
						echo "<p class='p1'><a href='". $current_file_name."?here=".$class_value."&class_obj=".$class_value."'>".ucfirst($class_value)."</a>: <a href='". $current_file_name."?here=".$class_value."&mode=create&class_obj=".$class_value."'>Create New ".singularName($class_value, true)."<img src='./include/images/pencil-small.png' /></a> ¦ <a href='". $current_file_name."?here=".$class_value."&mode=search&class_obj=".$class_value."'>Search ".ucfirst($class_value)."<img src='./include/images/MagnifyingGlass-small.png' /></a></span> ¦ <a href='help.html'>Help<img src='./include/images/Question-small.png' /></a></span></p>";
					}
				} // end custon menu
			}
		} // end foreahc class
		
?>