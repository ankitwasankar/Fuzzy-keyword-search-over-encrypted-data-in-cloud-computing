<!-- main_body  -->
<div class="main_body_wrapper">
    <div class="error">
    </div>
    
    <div class="reg_log">
        <h2>Search Results</h2>
    </div>
    
    <div style="height:25px; line-height:24px; vertical-align:middle; color:#111; text-align:center; background-color:#aa5555; font-family:tahoma;">
        <b>Search time :<a style="font-family:arial;"> <?php echo round(($this->session->userdata('time') * 1000), 3); ?></a> ns</b>
    </div>
    
    <div class="admin_log">    
        
        <table>
            <thead>
                <tr>
                    <td style="width:70px;">Sr No.</td>
                    <td style="width:400px;">Title</td>
                    <td>Download</td>
                </tr>
            </thead>
            <tbody>
            <?php
                $i = 1;
                foreach ($files as $record->files) {
            ?>    
                <tr>
                    <td style="width:70px;"><?php echo $i; $i++; ?></td>
                    <td style="width:400px;"><?php echo base64_decode($record->f_title); ?></td>
                    <td><a href="<?php echo base_url() . 'user/download/' . $record->f_id; ?>">Download</a></td>
                </tr>
            <?php        
                }
            ?>
            </tbody>
        </table>
    </div>
</div>
