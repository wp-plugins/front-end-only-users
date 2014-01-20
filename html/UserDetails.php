
<!-- The details of a specific product for editing, based on the product ID -->
		<?php $UserDetails = $wpdb->get_results($wpdb->prepare("SELECT * FROM $ewd_feup_user_fields_table_name WHERE User_ID ='%d'", $_GET['User_ID'])); ?>
		
		<div class="OptionTab ActiveTab" id="EditProduct">
				<div class="form-wrap UserDetail">
						<a href="admin.php?page=EWD-FEUP-options&DisplayPage=Users" class="NoUnderline">&#171; <?php _e("Back", 'EWD_FEUP') ?></a>
						<h2><?php _e("Edit User", 'EWD_FEUP') ?></h2>
						<?php $Fields = $wpdb->get_results("SELECT * FROM $ewd_feup_fields_table_name"); ?>
						<!-- Form to create a new product -->
						<form id="addtag" method="post" action="admin.php?page=EWD-FEUP-options&Action=EditUser&DisplayPage=Users" class="validate" enctype="multipart/form-data">
						<input type="hidden" name="action" value="Edit_User" />
						<?php wp_nonce_field(); ?>
						<?php wp_referer_field(); ?>
						<?php foreach ($Fields as $Field) {
								$Value = "";
								foreach ($UserDetails as $UserField) { 
													if ($Field->Field_Name == $UserField->Field_Name) {$Value = $UserField->Field_Value;}
											}
						?>
								<div class="form-field form-required">
										<label for="<?php echo $Field->Field_Name; ?>"><?php echo $Field->Field_Name; ?></label>
										<?php if ($Field->Field_Type == "text" or $Field->Field_Type == "mediumint") {?><input name="<?php echo $Field->Field_Name; ?>" class='ewd-admin-regular-text' id="<?php echo $Field->Field_Name; ?>" type="text" value="<?php echo $Value;?>" size="60" />
										<?php } elseif ($Field->Field_Type == "textarea") { ?>
												<textarea name="<?php echo $Field->Field_Name; ?>" class='ewd-admin-large-text' id="<?php echo $Field->Field_Name; ?>"><?php echo $Value ?></textarea>
										<?php } elseif ($Field->Field_Type == "select") { ?>
												<?php $Options = explode(",", $Field->Field_Options); ?>
												<select name="<?php echo $Field->Field_Name; ?>" id="<?php echo $Field->Field_Name; ?>">
												<?php foreach ($Options as $Option) { ?><option value='<?php echo $Option; ?>' <?php if ($Value == $Option) {echo "Selected";} ?>><?php echo $Option; ?></option><?php } ?>
												</select>
										<?php } elseif ($Field->Field_Type == "radio") { ?>
												<?php $Options = explode(",", $Field->Field_Options); ?>
												<?php foreach ($Options as $Option) { ?><input type='radio' name="<?php echo $Field->Field_Name; ?>" class='ewd-admin-small-input' value="<?php echo $Option; ?>" <?php if ($Value == $Option) {echo "checked";} ?>><?php echo $Option ?><br/><?php } ?>
										<?php } elseif ($Field->Field_Type == "checkbox") { ?>
												<?php $Options = explode(",", $Field->Field_Options); ?>
												<?php $User_Checkbox = explode(",", $Value); ?>
												<?php foreach ($Options as $Option) { ?><input type="checkbox" class='ewd-admin-small-input' name="<?php echo $Field->Field_Name; ?>[]" value="<?php echo $Option; ?>" <?php if (in_array($Option, $User_Checkbox)) {echo "Selected";} ?>><?php echo $Option; ?></br><?php } ?>
										<?php } ?>
										<p><?php echo $Field->Field_Description; ?></p>
								</div>
						<?php } ?>

<p class="submit"><input type="submit" name="submit" id="submit" class="button-primary" value="<?php _e('Edit User ', 'EWD_FEUP') ?>"  /></p></form>
						
				</div>
		</div>	