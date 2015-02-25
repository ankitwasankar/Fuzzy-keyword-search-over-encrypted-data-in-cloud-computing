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
				$row=strtolower($row);
				/*	
					-- Encrypting to aes --
						$key = 'xbYtKK'
						$blockSize = 256
						$mode = MCRYPT_MODE_ECB
				*/
				$aes = new AES($row);
				$row = $aes->encrypt();
				$query="insert into file_keys values($fid,'$row');";	
				$this->db->query($query);
			}
			return true;
		}
		
		public function get_fid_by_key($key){
			$aes = new AES($key);
			$key = $aes->encrypt();
			$query="select * from file_keys where `key`='$key';";
			$records=$this->db->query($query);
			return File_keys::instantiate($records);
		}
	}	
?>