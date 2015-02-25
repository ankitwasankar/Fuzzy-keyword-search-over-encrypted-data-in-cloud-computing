<?php
	class Upl_files extends CI_Model{
		public $f_id;
		public $f_title;
		public $f_date;
		public $f_loc;
		public $f_ext;
		public $f_u_id;
		
	
		public function __construct(){
			parent::__construct();
			$this->load->database();
		}
		
		public function instantiate($records){
			$obj=Array();
			$i=0;
			foreach($records->result() as $row){
				$o=new Upl_files();
				
				$o->f_id=$row->f_id;
				$o->f_title=$row->f_title;
				$o->f_date=$row->f_date;
				$o->f_loc=$row->f_loc;
				$o->f_ext=$row->f_ext;
				$o->f_u_id=$row->f_u_id;
				
				$obj[$i]=$o;
				$i++;
			}
			return $obj;
		}
		
		public function add_upload($title,$loc,$ext,$uid){
			$query="insert into upl_files values(default,'$title',default,'$loc','$ext',$uid);";	
			if($this->db->query($query)){
				return true;
			}
			else{
				return false;
			}
		}

		public function get_finfo($loc){
			$query="select * from upl_files where f_loc='$loc';";
			$records=$this->db->query($query);
			return Upl_files::instantiate($records);
		}
		
		public function get_finfo_by_fid($fid){
			$query="select * from upl_files where f_id=$fid;";
			$records=$this->db->query($query);
			return Upl_files::instantiate($records);
		}
			
		
		public function get_finfo_by_uid($uid){
			$query="select * from upl_files where f_u_id=$uid;";
			$records=$this->db->query($query);
			return Upl_files::instantiate($records);
		}
		
		public function delete_file($fid){
			$query="select * from upl_files where f_id=$fid;";
			$records=$this->db->query($query);
			$objs=Upl_files::instantiate($records);
			$query="delete from upl_files where f_id=$fid;";
			if($this->db->query($query)){
				return true;
			}
			else{
				return false;
			}
		}
	}	
?>