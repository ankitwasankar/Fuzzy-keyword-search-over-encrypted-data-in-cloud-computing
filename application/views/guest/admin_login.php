<!-- main_body  -->
<div class="main_body_wrapper">
	<div class="error">
		<?php echo "<p>$message</p>"; ?> <?php echo validation_errors(); ?>
	</div>
	
	<div class="reg_log">
			<h2>Data Owner Login</h2>
	</div>
		<div id="admin_log">	
			<form name="frm1" method="post" action="<?php echo base_url();?>guest/admin_login">
				<table>
					<tr>
						<td>Username * 
						<td><input type="text" name="username" id="username" value="<?php echo set_value('username'); ?>" class="text_box">
						</tr>		
						<tr>
						<td>Password *
						<td><input type="password" size="16" name="password" id="password" class="text_box"\>
						</tr>	
						<tr>
						<td>
						<td><input type="submit" name="submit" id="submit" class="submit_button" value="LogIn">
						</tr>
				</table>
			</form>	
	</div>
</div>	
