<?php
	class User_auth extends CI_Model{
		public $u_id;
		public $u_name;
		public $u_pass;
		public $u_fname;
		public $u_lname;
		public $u_mbno;
		public $u_time;
	
		public function __construct(){
			parent::__construct();
			$this->load->database();
		}
		
		public function instantiate($records){
			$obj=Array();
			$i=0;
			foreach($records->result() as $row){
				$o=new User_auth;
				$o->u_id=$row->u_id;
				$o->u_uname=$row->u_name;
				$o->u_pass=$row->u_pass;
				$o->u_fname=$row->u_fname;
				$o->u_lname=$row->u_lname;
				$o->u_mbno=$row->u_mbno;
				$o->u_time=$row->u_time;
				$obj[$i]=$o;
				$i++;
			}
			return $obj;
		}
		
		public function register($unm,$pwd,$fnm,$lnm,$mb,$s_que, $s_ans){
			$query="insert into user_auth values(default,'$unm','$pwd','$fnm','$lnm','$mb',default,default,'$s_que','$s_ans')";
			if($this->db->query($query))
				return true;
			else
				return false;
		}
		
		public function login($unm,$pwd){
			$query="select * from user_auth where u_name='$unm' and u_pass='$pwd';";
			$records=$this->db->query($query);
			if($records->num_rows>0){
					$row=$records->row();
					$s_data=array(
						'username'=>$row->u_name,
						'fname'=>$row->u_fname,
						'userid'=>$row->u_id,
						'status'=>$row->u_type,
						'download'=>'deactive',
						'time'=>00);
					$this->session->set_userdata($s_data);				
					
					return true;
			}
			else{
				return false;
			}
		}	
		
		public function users($fid){
			$query="select * from user_auth".$fid;
			$records=$this->db->query($query);
			return User_auth::instantiate($records);
		}
		
		public function update_pass($username,$pass){
			$pass=md5($pass);
			$query="update user_auth set u_pass='$pass' where u_name='$username'";
			if($records=$this->db->query($query))
				return true;
			else
				return false;
		}
		
		public function find($username){
			$query="select * from user_auth where u_name='$username'";
			$records=$this->db->query($query);
			if($records->num_rows>0)
				return true;
			else
				return false;
		}
		
		public function match_answer($username,$ans){
			$query="select * from user_auth where u_name='$username' and s_ans='$ans'";
			$records=$this->db->query($query);
			if($records->num_rows>0)
				return true;
			else
				return false;
		}
		public function security_qstn($username){
			$query="select s_que from user_auth where u_name='$username'";
			$records=$this->db->query($query);
			foreach($records->result() as $row){
				$que=$row->s_que;
			}
			return $que;
		}
		
		public function old_pass_exist($fid,$psd){
			$uid=$this->session->userdata('userid');	
			$query="select * from user_auth".$fid." where u_id=$uid and u_pass='$psd';";
			$record=$this->db->query($query);
			$i=0;
			foreach($record->result() as $row){
				$i=1;break;
			}
			if($i==1){
				return true;
			}
			else{
				return false;
			}
		}
		
		public function change_password($fid,$pass_new,$pass_old){
			$uid=$this->session->userdata('userid');
			$query="update user_auth".$fid." set u_pass='$pass_new' where u_pass='$pass_old' and u_id='$uid';";
			if($this->db->query($query)){
				return true;
			}
			else{
				return false;
			}
		}
		
		public function save_info($fid,$mb,$loc,$sp,$gd,$dob,$fb,$ld,$site,$bio){
			$uid=$this->session->userdata('userid');
			$query="update user_auth".$fid." set u_mbno=\"$mb\", u_location=\"$loc\", u_dob=\"$dob\", u_gender=\"$gd\", u_fb=\"$fb\", u_linkden=\"$ld\", u_site=\"$site\", u_bio=\"$bio\" where u_id=$uid;";
			
			if($this->db->query($query)){
				return true;
			}
			else{
				return false;
			}
		}
		
		public function get_info($fid){
			$uid=$this->session->userdata('userid');
			$query="select * from user_auth".$fid." where u_id=$uid;";
			$records=$this->db->query($query);
			return User_auth::instantiate($records);			
		}
		
		public function get_user_info($fid,$uid){
			$query="select * from user_auth".$fid." where u_id=$uid;";
			$records=$this->db->query($query);
			return User_auth::instantiate($records);			
		}
		public function delete_acc($fid){
			$uid=$this->session->userdata('userid');
			$query="update user_auth".$fid." set u_delstat='yes' where u_id=$uid";
			if($this->db->query($query)){
				
				return true;
			}
			else{
				return false;
			}
		}
	}	
?>