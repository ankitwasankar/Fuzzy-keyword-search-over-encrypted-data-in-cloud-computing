<!-- main_body  -->
<div class="main_body_wrapper">
	<div class="main_body_block">
		<div class="reg_log">
			<h2>User</h2>
		</div>
		<div class="admin_upper_wrapper">
			<div class="upload_block">
				<form method="post" enctype="multipart/form-data" action="<?php echo base_url(); ?>user/upload">
				<?php echo "<div class='error'>".$message."</div>"; ?>
				<table>
					<tr>
						<td>Select a file
						<td><input name="uploaded" type="file"  >
					<tr>
					<tr>
						<td>Title for file
						<td><input name="title" type="text" class="text_box" >
					<tr>
					<tr>
						<td>Enter Keywords
						<td><textarea name="keys" class="text_area"></textarea>
						<td>Keywords should be saperated by space
					<tr>
					<tr>
						<td>
						<td><input type="submit" class="submit_button" value="upload">
					</tr>
				</table>
				</form>
			</div>	
			<div class="logout_block">
				<div class="logout">
					
				</div>
				<div class="search_box">
				<form action="<?php echo base_url().'user/search';?>" method="post">
					<table>
						<tr>
							<td><input type="text" name="query" value="Enter keywords to search" class="text_box">
							<td><input type="submit" name="submit" value="Search" class="search_button">
						</tr>
					</table>
				</form>
				</div>
			</div>	
		</div>
		
		<div class="admin_lower_wrapper">
			<div class="reg_log">
			<h2>Uploaded files</h2>
			</div>
			
			<table>
				<thead>
					<td style="width:70px;">Sr. No.
					<td style="width:450px;">Title
					<td>upload date
					<td>delete
				</thead>
			<?php 
				$uid=$this->session->userdata('userid');
				$records=Upl_files::get_finfo_by_uid($uid); 
				$i=1; 
				foreach($records as $row){ 
			?>	
				<tr>
					<td style="width:70px;"><?php echo $i++; ?>
					<td style="width:450px;"><?php echo "<a href='".base_url()."user/download/".$row->f_id."'>".base64_decode($row->f_title)."</a>"; ?>  <!--<?php// echo $row->f_title; ?>-->
					<td><?php echo gmdate("d-M-Y",time($row->f_date)); ?> <!-- time converts time to unix timestamp -->
					<td><?php $url=base_url()."user/delete"; echo "<a href='".$url."/$row->f_id'>Delete</a>" ?>
				</tr>
			<?php } ?>
			</table>
		</div>
		
	</div>
</div>	
