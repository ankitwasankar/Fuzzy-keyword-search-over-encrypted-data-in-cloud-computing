<!-- main_body  -->
<div class="main_body_wrapper">
	<div class="error">
		<?php echo "<p>$message</p>"; ?> <?php echo validation_errors(); ?>
	</div>
	
	<div class="reg_log">
			<h2>Password Recovery (step 1 of 2):</h2>
	</div>
		<div id="recovery">	
			<form name="frm1" method="post" action="<?php echo base_url();?>guest/recovery2/<?php echo urlencode($username);?>">
				<table>
					<tr>
						<td>Security question
						<td><b><?php echo $sec_qstn; ?></b>
						</tr>
						<td>Your answer to security question:
						<td><input type="text" name="ans" class="text_box">
						</tr>			
						<tr>
						<td>
						<td><input type="submit" name="submit" id="submit" class="submit_button" value="Recover">
						</tr>
				</table>
			</form>	
	</div>
</div>	
