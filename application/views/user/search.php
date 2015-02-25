<!-- main_body  -->
<div class="main_body_wrapper">
	<div class="error">
	</div>
	
	<div class="reg_log">
			<h2>Search Results</h2>
	</div>
	
	<div style="heigth:25px;line-height:24px;vertical-align:middle;color:#111;text-align:center;background-color:#aa5555; font-family:tahoma;">
		<b>Search time :<a style="font-family:arial;"> <?php echo round(($this->session->userdata('time')*1000),3);?></a> ns</b>
	</div>
	
	<!--  ************************************************************************************************************************************
	<div style="heigth:25px;line-height:24px;vertical-align:middle;color:#111;text-align:center;background-color:#aa5555; font-family:tahoma;">
		<b>Non ideal Search time :<a style="font-family:arial;"> <?php // echo round(($total_time*1000),3);?></a> ns</b>
	</div>
	
	*****************************************************************************************************************************************  -->
	<div class="admin_log">	
	
		<div id="search_term">
			<a>Your Search Query :&nbsp;&nbsp;&nbsp; </a>
			<a><?php echo implode(" ", $search_keys) ?></a><br>
			<a>Results found for &nbsp;&nbsp; :&nbsp;&nbsp;&nbsp; </a>
			<a><?php echo implode(" ", $fuzzy_keys) ?></a><br>
		</div>
		<table>
			<thead>
				<td style="width:70px;">Sr No.
				<td style="width:400px;">Title
				<td>Download
			<!--	<td>Date of upload -->
			</thead>
		<?php
			$i=1;
			foreach($f_array as $row){
				$record=Upl_files::get_finfo_by_fid($row);
		?>	
			<tr>
				<td style="width:70px;"><?php echo $i; $i++;?>
				<td style="width:400px;"><?php echo base64_decode($record[0]->f_title); ?>
				<td> <a href="<?php echo base_url().'user/download/'.$record[0]->f_id?>">Download</a>
				<!--<td><?php //echo $record[0]->f_date; ?>-->
			</tr>
		<?php		
			}
		?>
		</table>
	</div>
	
	
</div>	
