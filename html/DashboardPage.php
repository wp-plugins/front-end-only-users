<?php 
	$Admin_Approval = get_option("EWD_FEUP_Admin_Approval");
?>

<!-- Upgrade to pro link box -->
<?php if ($Full_Version != "Yes") { ?>
<!--<div id="side-sortables" class="metabox-holder ">
<div id="upcp_pro" class="postbox " >
		<div class="handlediv" title="Click to toggle"></div><h3 class='hndle'><span><?php _e("Full Version", 'EWD_FEUP') ?></span></h3>
		<div class="inside">
				<ul><li><a href="http://www.etoilewebdesign.com/ultimate-product-catalogue-plugin/"><?php _e("Upgrade to the full version ", "EWD_FEUP"); ?></a><?php _e("to take advantage of all the available features of the Ultimate Product Catalogue for Wordpress!", 'EWD_FEUP'); ?></li>
				<div class="full-version-form-div">
						<form action="admin.php?page=EWD-FEUP-options" method="post">
								<div class="form-field form-required">
										<label for="Catalogue_Name"><?php _e("Product Key", 'EWD_FEUP') ?></label>
										<input name="Key" type="text" value="" size="40" />
								</div>							
								<input type="submit" name="Upgrade_To_Full" value="<?php _e('Upgrade', 'EWD_FEUP') ?>">
						</form>
				</div>
		</div>
</div>
</div>-->
<?php } ?>

<div id="side-sortables" class="metabox-holder ">
<div id="upcp_pro" class="postbox " >
	<div class="handlediv" title="Click to toggle"></div><h3 class='hndle'><span><?php _e("Thank You!", 'EWD_FEUP') ?></span></h3>
	<div class="inside">
		<ul>
			<li><?php _e("Thanks for being an early adopter! Anyone who installs before January 25th will always have access to new features, updates and full product support.", 'EWD_FEUP'); ?></li>
			<li><a href="https://www.facebook.com/EtoileWebDesign"><?php _e("Follow us on Facebook ", "EWD_FEUP");?></a><?php _e("to stay up to date with new features and plugins.", "EWD_FEUP"); ?></li>
		</ul>
	</div>
</div>
</div>

<?php /* echo get_option('plugin_error');*/ ?>

<!-- List of the catalogues which have already been created -->
<div id="col-right">
<div class="col-wrap">
<?php echo get_option('plugin_error'); ?>
<?php wp_nonce_field(); ?>
<?php wp_referer_field(); ?>

<?php 
	if (isset($_GET['Page'])) {$Page = $_GET['Page'];}
	else {$Page = 1;}
	if (!isset($_GET['OrderBy'])) {$_GET['OrderBy'] = null;}
			
	$Sql = "SELECT * FROM $ewd_feup_fields_table_name ";
	if (isset($_GET['OrderBy']) and $_GET['DisplayPage'] == "Dashboard") {$Sql .= "ORDER BY " . $_GET['OrderBy'] . " " . $_GET['Order'] . " ";}
	else {$Sql .= "ORDER BY Field_Name ";}
	$Sql .= "LIMIT " . ($Page - 1)*20 . ",20";
	$myrows = $wpdb->get_results($Sql);
	$TotalFields = $wpdb->get_results("SELECT Field_ID FROM $ewd_feup_fields_table_name");
	$num_rows = $wpdb->num_rows; 
	$Number_of_Pages = ceil($wpdb->num_rows/20);
	$Current_Page_With_Order_By = "admin.php?page=EWD-FEUP-options&DisplayPage=Dashboard";
	if (isset($_GET['OrderBy'])) {$Current_Page_With_Order_By .= "&OrderBy=" .$_GET['OrderBy'] . "&Order=" . $_GET['Order'];}?>

<form action="admin.php?page=EWD-FEUP-options&Action=EWD_FEUP_MassDeleteUserFields" method="post">    
<div class="tablenav top">
	<div class="alignleft actions">
		<select name='action'>
  			<option value='-1' selected='selected'><?php _e("Bulk Actions", 'EWD_FEUP') ?></option>
			<option value='delete'><?php _e("Delete", 'EWD_FEUP') ?></option>
		</select>
		<input type="submit" name="" id="doaction" class="button-secondary action" value="<?php _e('Apply', 'EWD_FEUP') ?>"  />
	</div>
	<div class='tablenav-pages <?php if ($Number_of_Pages == 1) {echo "one-page";} ?>'>
		<span class="displaying-num"><?php echo $wpdb->num_rows; ?> <?php _e("items", 'EWD_FEUP') ?></span>
		<span class='pagination-links'>
			<a class='first-page <?php if ($Page == 1) {echo "disabled";} ?>' title='Go to the first page' href='<?php echo $Current_Page_With_Order_By; ?>&Page=1'>&laquo;</a>
			<a class='prev-page <?php if ($Page <= 1) {echo "disabled";} ?>' title='Go to the previous page' href='<?php echo $Current_Page_With_Order_By; ?>&Page=<?php echo $Page-1;?>'>&lsaquo;</a>
			<span class="paging-input"><?php echo $Page; ?> <?php _e("of", 'EWD_FEUP') ?> <span class='total-pages'><?php echo $Number_of_Pages; ?></span></span>
			<a class='next-page <?php if ($Page >= $Number_of_Pages) {echo "disabled";} ?>' title='Go to the next page' href='<?php echo $Current_Page_With_Order_By; ?>&Page=<?php echo $Page+1;?>'>&rsaquo;</a>
			<a class='last-page <?php if ($Page == $Number_of_Pages) {echo "disabled";} ?>' title='Go to the last page' href='<?php echo $Current_Page_With_Order_By . "&Page=" . $Number_of_Pages; ?>'>&raquo;</a>
		</span>
	</div>
</div>

<table class="wp-list-table widefat fixed tags sorttable" cellspacing="0">
	<thead>
		<tr>
			<th scope='col' id='cb' class='manage-column column-cb check-column'  style="">
				<input type="checkbox" /></th><th scope='col' id='field-name' class='manage-column column-name sortable desc'  style="">
				<?php if (isset($_GET['OrderBy']) && $_GET['OrderBy'] == "Field_Name" and $_GET['Order'] == "ASC") { echo "<a href='admin.php?page=EWD-FEUP-options&DisplayPage=User_Fields&OrderBy=Field_Name&Order=DESC'>";}
				else {echo "<a href='admin.php?page=EWD-FEUP-options&DisplayPage=User_Fields&OrderBy=Field_Name&Order=ASC'>";} ?>
					<span><?php _e("Field Name", 'EWD_FEUP') ?></span>
					<span class="sorting-indicator"></span>
				</a>
			</th>
			<th scope='col' id='type' class='manage-column column-type sortable desc'  style="">
				<?php if (isset($_GET['OrderBy']) && $_GET['OrderBy'] == "Field_Type" and $_GET['Order'] == "ASC") { echo "<a href='admin.php?page=EWD-FEUP-options&DisplayPage=User_Fields&OrderBy=Field_Type&Order=DESC'>";}
				else {echo "<a href='admin.php?page=EWD-FEUP-options&DisplayPage=User_Fields&OrderBy=Field_Type&Order=ASC'>";} ?>
					<span><?php _e("Type", 'EWD_FEUP') ?></span>
					<span class="sorting-indicator"></span>
				</a>
			</th>
			<th scope='col' id='description' class='manage-column column-description sortable desc'  style="">
				<?php if (isset($_GET['OrderBy']) && $_GET['OrderBy'] == "Field_Description" and $_GET['Order'] == "ASC") { echo "<a href='admin.php?page=EWD-FEUP-options&DisplayPage=User_Fields&OrderBy=Field_Description&Order=DESC'>";}
				else {echo "<a href='admin.php?page=EWD-FEUP-options&DisplayPage=User_Fields&OrderBy=Field_Description&Order=ASC'>";} ?>
					<span><?php _e("Description", 'EWD_FEUP') ?></span>
					<span class="sorting-indicator"></span>
				</a>
			</th>
			<th scope='col' id='required' class='manage-column column-users sortable desc'  style="">
				<?php if (isset($_GET['OrderBy']) && $_GET['OrderBy'] == "Field_Required" and $_GET['Order'] == "ASC") { echo "<a href='admin.php?page=EWD-FEUP-options&DisplayPage=User_Fields&OrderBy=Field_Required&Order=DESC'>";}
				else {echo "<a href='admin.php?page=EWD-FEUP-options&DisplayPage=User_Fields&OrderBy=Field_Required&Order=ASC'>";} ?>
					<span><?php _e("Required?", 'EWD_FEUP') ?></span>
					<span class="sorting-indicator"></span>
				</a>
			</th>
		</tr>
	</thead>

	<tfoot>
		<tr>
			<th scope='col' id='cb' class='manage-column column-cb check-column'  style="">
				<input type="checkbox" /></th><th scope='col' id='field-name' class='manage-column column-name sortable desc'  style="">
				<?php if (isset($_GET['OrderBy']) && $_GET['OrderBy'] == "Field_Name" and $_GET['Order'] == "ASC") { echo "<a href='admin.php?page=EWD-FEUP-options&DisplayPage=User_Fields&OrderBy=Field_Name&Order=DESC'>";}
				else {echo "<a href='admin.php?page=EWD-FEUP-options&DisplayPage=User_Fields&OrderBy=Field_Name&Order=ASC'>";} ?>
					<span><?php _e("Field Name", 'EWD_FEUP') ?></span>
					<span class="sorting-indicator"></span>
				</a>
			</th>
			<th scope='col' id='type' class='manage-column column-type sortable desc'  style="">
				<?php if (isset($_GET['OrderBy']) && $_GET['OrderBy'] == "Field_Type" and $_GET['Order'] == "ASC") { echo "<a href='admin.php?page=EWD-FEUP-options&DisplayPage=User_Fields&OrderBy=Field_Type&Order=DESC'>";}
				else {echo "<a href='admin.php?page=EWD-FEUP-options&DisplayPage=User_Fields&OrderBy=Field_Type&Order=ASC'>";} ?>
					<span><?php _e("Type", 'EWD_FEUP') ?></span>
					<span class="sorting-indicator"></span>
				</a>
			</th>
			<th scope='col' id='description' class='manage-column column-description sortable desc'  style="">
				<?php if (isset($_GET['OrderBy']) && $_GET['OrderBy'] == "Field_Description" and $_GET['Order'] == "ASC") { echo "<a href='admin.php?page=EWD-FEUP-options&DisplayPage=User_Fields&OrderBy=Field_Description&Order=DESC'>";}
				else {echo "<a href='admin.php?page=EWD-FEUP-options&DisplayPage=User_Fields&OrderBy=Field_Description&Order=ASC'>";} ?>
					<span><?php _e("Description", 'EWD_FEUP') ?></span>
					<span class="sorting-indicator"></span>
				</a>
			</th>
			<th scope='col' id='required' class='manage-column column-users sortable desc'  style="">
				<?php if (isset($_GET['OrderBy']) && $_GET['OrderBy'] == "Field_Required" and $_GET['Order'] == "ASC") { echo "<a href='admin.php?page=EWD-FEUP-options&DisplayPage=User_Fields&OrderBy=Field_Required&Order=DESC'>";}
				else {echo "<a href='admin.php?page=EWD-FEUP-options&DisplayPage=User_Fields&OrderBy=Field_Required&Order=ASC'>";} ?>
					<span><?php _e("Required?", 'EWD_FEUP') ?></span>
					<span class="sorting-indicator"></span>
				</a>
			</th>
		</tr>
	</tfoot>

	<tbody id="the-list" class='list:tag'>
		<?php
			if ($myrows) { 
	  			foreach ($myrows as $Field) {
					echo "<tr id='Field" . $Field->Field_ID ."'>";
					echo "<th scope='row' class='check-column'>";
					echo "<input type='checkbox' name='Products_Bulk[]' value='" . $Field->Field_ID ."' />";
					echo "</th>";
					echo "<td class='name column-name'>";
					echo "<strong>";
					echo "<a class='row-title' href='admin.php?page=EWD-FEUP-options&Action=EWD_FEUP_Field_Details&Selected=Product&Field_ID=" . $Field->Field_ID ."' title='Edit " . $Field->Field_Name . "'>" . $Field->Field_Name . "</a></strong>";
					echo "<br />";
					echo "<div class='row-actions'>";
					echo "<span class='delete'>";
					echo "<a class='delete-tag' href='admin.php?page=EWD-FEUP-options&Action=EWD_FEUP_DeleteField&DisplayPage=User_Fields&Field_ID=" . $Field->Field_ID ."'>" . __("Delete", 'EWD_FEUP') . "</a>";
		 			echo "</span>";
					echo "</div>";
					echo "<div class='hidden' id='inline_" . $Field->Field_ID ."'>";
					echo "<div class='name'>" . $Field->Field_Name . "</div>";
					echo "</div>";
					echo "</td>";
					echo "<td class='description column-type'>" . $Field->Field_Type . "</td>";
					echo "<td class='description column-description'>" . substr($Field->Field_Description, 0, 60);
					if (strlen($Field->Field_Description) > 60) {echo "...";}
					echo "</td>";
					echo "<td class='users column-required'>" . $Field->Field_Required . "</td>";
					echo "</tr>";
				}
			}
		?>
	</tbody>
</table>

<div class="tablenav bottom">
	<div class="alignleft actions">
		<select name='action'>
  			<option value='-1' selected='selected'><?php _e("Bulk Actions", 'EWD_FEUP') ?></option>
			<option value='delete'><?php _e("Delete", 'EWD_FEUP') ?></option>
		</select>
		<input type="submit" name="" id="doaction" class="button-secondary action" value="<?php _e('Apply', 'EWD_FEUP') ?>"  />
	</div>
	<div class='tablenav-pages <?php if ($Number_of_Pages == 1) {echo "one-page";} ?>'>
		<span class="displaying-num"><?php echo $wpdb->num_rows; ?> <?php _e("items", 'EWD_FEUP') ?></span>
		<span class='pagination-links'>
			<a class='first-page <?php if ($Page == 1) {echo "disabled";} ?>' title='Go to the first page' href='<?php echo $Current_Page_With_Order_By; ?>&Page=1'>&laquo;</a>
			<a class='prev-page <?php if ($Page <= 1) {echo "disabled";} ?>' title='Go to the previous page' href='<?php echo $Current_Page_With_Order_By; ?>&Page=<?php echo $Page-1;?>'>&lsaquo;</a>
			<span class="paging-input"><?php echo $Page; ?> <?php _e("of", 'EWD_FEUP') ?> <span class='total-pages'><?php echo $Number_of_Pages; ?></span></span>
			<a class='next-page <?php if ($Page >= $Number_of_Pages) {echo "disabled";} ?>' title='Go to the next page' href='<?php echo $Current_Page_With_Order_By; ?>&Page=<?php echo $Page+1;?>'>&rsaquo;</a>
			<a class='last-page <?php if ($Page == $Number_of_Pages) {echo "disabled";} ?>' title='Go to the last page' href='<?php echo $Current_Page_With_Order_By . "&Page=" . $Number_of_Pages; ?>'>&raquo;</a>
		</span>
	</div>
	<br class="clear" />
</div>
</form>

<br class="clear" />
</div>
</div>

<!-- A list of the products in the catalogue -->
<div id="col-left">
<div class="col-wrap">
	<div id="dashboard-total-users">
		<?php if ($Admin_Approval == "Yes") { ?>
			Unapproved Users:
			<?php $TotalUsers = $wpdb->get_results("SELECT User_ID FROM $ewd_feup_user_table_name WHERE User_Admin_Approved!='Yes'");
				echo $wpdb->num_rows;  ?>
		<?php } else { ?>
			Current Users:
			<?php $TotalUsers = $wpdb->get_results("SELECT User_ID FROM $ewd_feup_user_table_name");
				echo $wpdb->num_rows;  ?>
		<?php } ?>
	</div>
	<div id="dashboard-products-column" class="metabox-holder">	
	<div id="side-sortables" class="meta-box-sortables">

	<div id="add-page" class="postbox " >
	<?php if ($Admin_Approval == "Yes") { ?>
		<div class="handlediv" title="Click to toggle"><br /></div><h3 class='hndle'><span><?php _e("Unapproved Users", 'EWD_FEUP') ?></span></h3>
	<?php } else { ?>
		<div class="handlediv" title="Click to toggle"><br /></div><h3 class='hndle'><span><?php _e("Recent Users", 'EWD_FEUP') ?></span></h3>
	<?php } ?>
	<div class="inside">
	<div id="posttype-page" class="posttypediv">
		<ul id="posttype-page-tabs" class="posttype-tabs add-menu-item-tabs">
			<!--<li  class="tabs"><a class="nav-tab-link" href="/wp-admin/nav-menus.php?page-tab=most-recent#tabs-panel-posttype-page-most-recent">Most Recent</a></li>-->
			<li  class="tabs"><!--<a class="nav-tab-link" href="/wp-admin/nav-menus.php?page-tab=all#page-all">--><?php _e("Recent", 'EWD_FEUP') ?><!--</a>--></li>
			<!--<li ><a class="nav-tab-link" href="/wp-admin/nav-menus.php?page-tab=search#tabs-panel-posttype-page-search">Search</a></li>-->
		</ul>

		<div id="tabs-panel-posttype-page-most-recent" class="tabs-panel tabs-panel-active">
			<ul id="pagechecklist-most-recent" class="categorychecklist form-no-clear">
				<?php //$Products = $wpdb->get_results("SELECT Item_ID, Item_Name FROM $items_table_name ORDER BY Item_Views DESC"); 
					if ($Admin_Approval == "Yes") {$Users = $wpdb->get_results("SELECT User_ID, Username FROM $ewd_feup_user_table_name WHERE User_Admin_Approved!='Yes'");}
					else {$Users = $wpdb->get_results("SELECT User_ID, Username FROM $ewd_feup_user_table_name");}
					foreach ($Users as $User) {
						echo "<li><label class='menu-item-title'><a href='/wp-admin/admin.php?page=EWD-FEUP-options&Action=EWD_FEUP_User_Details&Selected=User&User_ID=" . $User->User_ID ."'> " . $User->Username . "</a></label></li>";
					}
				?>
			</ul>
		</div><!-- /.tabs-panel -->

		<div class="tabs-panel tabs-panel-inactive" id="tabs-panel-posttype-page-search">
				<!--<p class="quick-search-wrap">
				<input type="search" class="quick-search input-with-default-title" title="Search" value="" name="quick-search-posttype-page" />
				<img class="waiting" src="http://www.etoilewebdesign.com/wp-admin/images/wpspin_light.gif" alt="" />
				<input type="submit" name="submit" id="submit-quick-search-posttype-page" class="quick-search-submit button-secondary hide-if-js" value="Search"  />			</p>-->

			<ul id="page-search-checklist" class="list:page categorychecklist form-no-clear">
						</ul>
		</div><!-- /.tabs-panel -->

		<div id="page-all" class="tabs-panel tabs-panel-view-all tabs-panel-inactive">

		</div><!-- /.tabs-panel -->

		<p class="button-controls">
			<!--<span class="list-controls">
				<a href="/wp-admin/nav-menus.php?page-tab=all&#038;selectall=1#posttype-page" class="select-all">Select All</a>
			</span>-->

			<!--<span class="add-to-menu">
				<span class="spinner"></span>
				<input type="submit" class="button-secondary submit-add-to-menu" value="Add to Menu" name="add-post-type-menu-item" id="submit-posttype-page" />
			</span>-->
		</p>

	</div><!-- /.posttypediv -->
	</div>
</div>

</div>
</div>
</div>
</div>
