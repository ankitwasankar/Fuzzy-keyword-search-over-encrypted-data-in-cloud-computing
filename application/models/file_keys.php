<?php
	class File_keys extends CI_Model{
		public $f_id;
		public $key;
		
	
		public function __construct(){
			parent::__construct();
			$this->load->database();
		}
		
		public function instantiate($records){
			$obj=Array();
			$i=0;
			foreach($records->result() as $row){
				$o=new File_keys();
				$o->f_id=$row->f_id;
				$o->key=$row->key;
				$obj[$i]=$o;
				$i++;
			}
			return $obj;
		}
		
		public function add_keys($finfo,$arr){
			$fid=$finfo[0]->f_id;
			foreach($arr as $row){	
				$row = base64_encode($row);
				$query="insert into file_keys values($fid,'$row');";	
				$this->db->query($query);
			}
			return true;
		}
		
		public function get_fid_by_key($key){
			$key = base64_encode($key);
			$query="select * from file_keys where `key`='$key';";
			$records=$this->db->query($query);
			return (new File_keys())->instantiate($records);
		}
		
		public function get_fid_by_org_key($key){
			$query="select * from file_keys where `key`='$key';";
			$records=$this->db->query($query);
			return (new File_keys())->instantiate($records);
		}
	}	
?>