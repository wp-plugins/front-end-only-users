<?php 
		$Admin_Approval = get_option("EWD_FEUP_Admin_Approval");
?>
<div id="col-right">
<div class="col-wrap">

<!-- Display a list of the products which have already been created -->
<?php wp_nonce_field(); ?>
<?php wp_referer_field(); ?>

<?php 
			if (isset($_GET['Page'])) {$Page = $_GET['Page'];}
			else {$Page = 1;}
			
			$Fields = $wpdb->get_results("SELECT * FROM $ewd_feup_fields_table_name WHERE Field_Show_In_Admin='Yes'");
			
			$Sql = "SELECT * FROM $ewd_feup_user_table_name ";
				if (isset($_GET['OrderBy'])) {$Sql .= "ORDER BY " . $_GET['OrderBy'] . " " . $_GET['Order'] . " ";}
				else {$Sql .= "ORDER BY User_Date_Created ";}
				$Sql .= "LIMIT " . ($Page - 1)*20 . ",20";
				$myrows = $wpdb->get_results($Sql);
				$num_rows = $wpdb->num_rows; 
				$Number_of_Pages = ceil($num_rows/20);
				echo $Number_Of_Pages;
				$Current_Page_With_Order_By = "admin.php?page=EWD-FEUP-options&DisplayPage=Users";
				if (isset($_GET['OrderBy'])) {$Current_Page_With_Order_By .= "&OrderBy=" .$_GET['OrderBy'] . "&Order=" . $_GET['Order'];}
				$UserCount = $wpdb->num_rows;
				?>

<form action="admin.php?page=EWD-FEUP-options&Action=EWD_FEUP_MassDeleteUsers&DisplayPage=Users" method="post">    
<div class="tablenav top">
		<div class="alignleft actions">
				<select name='action'>
  					<option value='-1' selected='selected'><?php _e("Bulk Actions", 'EWD_FEUP') ?></option>
						<option value='delete'><?php _e("Delete", 'EWD_FEUP') ?></option>
				</select>
				<input type="submit" name="" id="doaction" class="button-secondary action" value="<?php _e('Apply', 'EWD_FEUP') ?>"  />
		</div>
		<div class='tablenav-pages <?php if ($Number_of_Pages == 1) {echo "one-page";} ?>'>
				<span class="displaying-num"><?php echo $UserCount; ?> <?php _e("items", 'EWD_FEUP') ?></span>
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
								<input type="checkbox" />
						</th>
						<?php if ($Admin_Approval == "Yes") { ?>
								<th scope='col' class='manage-column column-cb check-column'  style="">
										<span>Admin Approved</span>
								</th>
						<?php } ?>
						<?php foreach ($Fields as $Field) { ?>
						<?php if ($_GET['OrderBy'] == "Item_Name" and $_GET['Order'] == "ASC") {$Order = "DESC";}
									else {$Order = "ASC";} ?>
									 <th scope='col' class='manage-column column-cb check-column'  style="">
										<a href="admin.php?page=EWD-FEUP-options&DisplayPage=Users&OrderBy=<?php echo $Field->Field_Name; ?>&Order=<?php echo $Order; ?>">
											  <span><?php echo $Field->Field_Name; ?></span>
												<span class="sorting-indicator"></span>
										</a>
									 </th>
						<?php } ?>
				</tr>
		</thead>

		<tfoot>
				<tr>
						<th scope='col' id='cb' class='manage-column column-cb check-column'  style="">
								<input type="checkbox" />
						</th>
						<?php if ($Admin_Approval == "Yes") { ?>
								<th scope='col' class='manage-column column-cb check-column'  style="">
										<span>Admin Approved</span>
								</th>
						<?php } ?>
						<?php foreach ($Fields as $Field) { ?>
						<?php if ($_GET['OrderBy'] == "Item_Name" and $_GET['Order'] == "ASC") {$Order = "DESC";}
									else {$Order = "ASC";} ?>
						<th scope='col' class='manage-column column-cb check-column'  style="">
										<a href="admin.php?page=EWD-FEUP-options&DisplayPage=Users&OrderBy=<?php echo $Field->Field_Name; ?>&Order=<?php echo $Order; ?>">
											  <span><?php echo $Field->Field_Name; ?></span>
												<span class="sorting-indicator"></span>
										</a>
						</th>
						<?php } ?>
				</tr>
		</tfoot>

	<tbody id="the-list" class='list:tag'>
		
		 <?php
				if ($myrows) { 
	  			  foreach ($myrows as $User) {
								$FieldCount = 0;
								echo "<tr id='User" . $User->User_ID ."'>";
								echo "<th scope='row' class='check-column'>";
								echo "<input type='checkbox' name='Users_Bulk[]' value='" . $User->User_ID ."' />";
								echo "</th>";
								if ($Admin_Approval == "Yes") {
									  echo "<td class='name column-name'>";
										echo $User->User_Admin_Approved;
										echo "</td>";
								}
										foreach ($Fields as $Field) { 
												$User_Info = $wpdb->get_row($wpdb->prepare("SELECT Field_Value FROM $ewd_feup_user_fields_table_name WHERE User_ID='%d' and Field_Name='%s'", $User->User_ID, $Field->Field_Name));
												echo "<td class='name column-name'>";
												if ($FieldCount == 0) {
													  echo "<strong>";
													  echo "<a class='row-title' href='admin.php?page=EWD-FEUP-options&Action=EWD_FEUP_User_Details&Selected=User&User_ID=" . $User->User_ID ."' title='Edit User'>";
												}
												echo substr($User_Info->Field_Value, 0, 60);
												if (strlen($User_Info->Field_Value) > 60) {echo "...";}
												if ($FieldCount == 0) {
														echo "</a></strong>";
														echo "<br />";
														echo "<div class='row-actions'>";
														echo "<span class='delete'>";
														echo "<a class='delete-tag' href='admin.php?page=EWD-FEUP-options&Action=EWD_FEUP_DeleteUser&DisplayPage=Users&User_ID=" . $User->User_ID ."'>" . __("Delete", 'EWD_FEUP') . "</a>";
		 												echo "</span>";
														echo "</div>";
														echo "<div class='hidden' id='inline_" . $Item->Item_ID ."'>";
												}												
												echo "</td>";
												$FieldCount++;
										}
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
				<span class="displaying-num"><?php echo $UserCount; ?> <?php _e("items", 'EWD_FEUP') ?></span>
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
</div> <!-- /col-right -->

<!-- Form to upload a list of new products from a spreadsheet -->
<div id="col-left">
<div class="col-wrap">

<div class="form-wrap">
<h2><?php _e("Add New User", 'EWD_FEUP') ?></h2>
<?php $Fields = $wpdb->get_results("SELECT * FROM $ewd_feup_fields_table_name"); ?>
<!-- Form to create a new product -->
<form id="addtag" method="post" action="admin.php?page=EWD-FEUP-options&Action=EWD_FEUP_AddUser&DisplayPage=Users" class="validate" enctype="multipart/form-data">
<input type="hidden" name="action" value="Add_User" />
<?php wp_nonce_field(); ?>
<?php wp_referer_field(); ?>
<label for='Username' id='ewd-feup-register-username-div' class='ewd-feup-field-label'><?php _e('Username', 'EWD_FEUP');?>: </label>
<input type='text' class='ewd-feup-text-input' name='Username'>
<label for='Password' id='ewd-feup-register-password-div' class='ewd-feup-field-label'><?php _e('Password', 'EWD_FEUP')?>: </label>
<input type='password' class='ewd-feup-text-input' name='User_Password'>
<label for='Repeat Password' id='ewd-feup-register-password-confirm-div' class='ewd-feup-field-label'><?php _e('Repeat Password', 'EWD_FEUP');?>: </label>
<input type='password' class='ewd-feup-text-input' name='Confirm_User_Password'>
<?php if ($Admin_Approval == "Yes") { ?>
		<label for='Admin Approved' id='ewd-feup-register-admin-approved-div' class='ewd-feup-field-label'><?php _e('Admin Approved', 'EWD_FEUP');?>: </label>
		<input type='radio' class='ewd-feup-text-input' name='Admin_Approved' value='Yes'>Yes<br />
		<input type='radio' class='ewd-feup-text-input' name='Admin_Approved' value='No'>No<br />
<?php } ?>
<?php foreach ($Fields as $Field) { ?>
<div class="form-field form-required">
	<label for="<?php echo $Field->Field_Name; ?>"><?php echo $Field->Field_Name; ?></label>
	<?php if ($Field->Field_Type == "text" or $Field->Field_Type == "mediumint") {?><input name="<?php echo $Field->Field_Name; ?>" id="<?php echo $Field->Field_Name; ?>" type="text" value="" size="60" />
	<?php } elseif ($Field->Field_Type == "date") {?>
			<input name='<?php echo $Field->Field_Name; ?>' id='ewd-feup-register-input-<?php echo $Field->Field_ID; ?>' class='ewd-feup-date-input pure-input-1-3' type='date' value='' />
	<?php } elseif ($Field->Field_Type == "datetime") { ?>
			<input name='<?php echo $Field->Field_Name; ?>' id='ewd-feup-register-input-<?php echo $Field->Field_ID; ?>' class='ewd-feup-datetime-input pure-input-1-3' type='datetime-local' value='' />
	<?php } elseif ($Field->Field_Type == "textarea") { ?>
			<textarea name="<?php echo $Field->Field_Name; ?>" id="<?php echo $Field->Field_Name; ?>"></textarea>
	<?php } elseif ($Field->Field_Type == "file") {?>
			<input name='<?php echo $Field->Field_Name; ?>' id='ewd-feup-register-input-<?php echo $Field->Field_ID; ?>' class='ewd-feup-file-input pure-input-1-3' type='file' value='' />
	<?php } elseif ($Field->Field_Type == "select") { ?>
			<?php $Options = explode(",", $Field->Field_Options); ?>
			<select name="<?php echo $Field->Field_Name; ?>" id="<?php echo $Field->Field_Name; ?>">
			<?php foreach ($Options as $Option) { ?><option value='<?php echo $Option; ?>'><?php echo $Option; ?></option><?php } ?>
			</select>
	<?php } elseif ($Field->Field_Type == "radio") { ?>
			<?php $Options = explode(",", $Field->Field_Options); ?>
			<?php foreach ($Options as $Option) { ?><input type='radio' class='ewd-admin-small-input' name="<?php echo $Field->Field_Name; ?>" value="<?php echo $Option; ?>"><?php echo $Option ?><br/><?php } ?>
	<?php } elseif ($Field->Field_Type == "checkbox") { ?>
			<?php $Options = explode(",", $Field->Field_Options); ?>
			<?php foreach ($Options as $Option) { ?><input type="checkbox" class='ewd-admin-small-input' name="<?php echo $Field->Field_Name; ?>[]" value="<?php echo $Option; ?>"><?php echo $Option; ?></br><?php } ?>
	<?php } ?>
	<p><?php echo $Field->Field_Description; ?></p>
</div>
<?php } ?>

<p class="submit"><input type="submit" name="submit" id="submit" class="button-primary" value="<?php _e('Add New User', 'EWD_FEUP') ?>"  /></p></form>

</div>
<br class="clear" />
</div>
</div>		