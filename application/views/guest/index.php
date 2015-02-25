<!-- main_body  -->
<div class="main_body_wrapper">
	<div class="error">
		<?php echo "<p>$message1</p>"; ?> <?php echo validation_errors(); ?>
	</div>
	<div class="left_block">
		<div class="reg_log">
			<h2>Register</h2>
		</div>	
			
		<div class="register">	
			<form name="frm1" method="post" action="<?php echo base_url();?>guest/register">
				<table>
					<tr>
						<td>Username * 
						<td><input type="email" name="username" id="username" value="<?php echo set_value('username'); ?>" class="text_box">
						</tr>		
						<tr>
						<td>Password *
						<td><input type="password" size="16" name="password" id="password" class="text_box"\>
						</tr>	
						<tr>
						<td>Confirm password * 
						<td><input type="password" name="password_c" id="password_c" class="text_box">
						</tr>
						<tr>
						<td>First name * 
						<td><input type="text" name="name" id="name" value="<?php echo set_value('name'); ?> "class="text_box">
						</tr>
						<tr>
						<td>Last name *
						<td><input type="text" name="surname" id="surname" value="<?php echo set_value('surname'); ?>" class="text_box">
						</tr>
						<tr>	
						<td>Mobile Number *
						<td><input type="text" name="mb_no" id="mb_no" value="<?php echo set_value('mb_no'); ?>" class="text_box">
						</tr>
						<tr>
						<td>Security Question*
						<td>
						<select class="text_box" name="s_que">
								<option value="What is your favourite pet?">What is your favourite pet?</option>
								<option value="Which is your most liked book?">Which is your most liked book?</option>
								<option value="Your first mobile number?">Your first mobile number?</option>
								<option value="Who is favourite Cricketer?">Who is favourite Cricketer?</option>
							</select>
						</tr>
						<tr>
						<td>Security Answer*
						<td><input class="text_box" type="text area" width="50ps" height="40ps" name="s_ans">
						</tr>
						<tr>
						<td>
						<td><input type="submit" name="submit" id="submit" class="submit_button" value="Register">
						</tr>
				</table>
			</form>	

		</div>	
		</div>	
			
	<div class="right_block">
		<div class="reg_log">
			<h2>Login</h2>
		</div>
		<div class="register">	
			<form name="frm1" method="post" action="<?php echo base_url();?>guest/login">
				<table>
					<tr>
						<td>Username * 
						<td><input type="email" name="username" id="username" value="<?php echo set_value('username'); ?>" class="text_box">
						</tr>		
						<tr>
						<td>Password *
						<td><input type="password" size="16" name="password" id="password" class="text_box"\>
						</tr>	
						<tr>
						<td>
						<td><input type="submit" name="submit" id="submit" class="submit_button" value="LogIn">
						</tr>
						<tr>
						<td>
						<td><a href="<?php echo base_url();?>guest/recovery1">Forgot password?</a>
						</tr>
				</table>
			</form>	
			<div id="owner_login">
				<a href="<?php echo base_url().'guest/admin_login' ?>">Data Owner Login Here!</a>
			</div>
		</div>
	</div>
	</div>