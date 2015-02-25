<?php
	class Index_x extends CI_Model{
		
		public $ngram_key;
		public $org_key;
	
		public function __construct(){
			parent::__construct();
			$this->load->database();
		}
		
		public function instantiate($records){
			$obj=Array();
			$i=0;
			foreach($records->result() as $row){
				$o=new Index_x();
				
				$o->ngram_key=$row->ngram_key;
				$o->org_key=base64_decode($row->org_key);
				
				
				$obj[$i]=$o;
				$i++;
			}
			return $obj;
		}
		
		
	/*************************************************************
			SEARCH FUNCTION that store query response time
	*************************************************************/
		public function compare($ngram,$tbnm){
			$query="select * from $tbnm where ngram_key='$ngram';";
			//echo $query;die;
			$starttime=microtime(true);
				$records=$this->db->query($query);
			$endtime = microtime(true);
			$time=$endtime - $starttime;
			$this->session->set_userdata(array('time'=>$time));
			if($records!=NULL){
				return Index_x::instantiate($records);
			}
			else{
				return NULL;
			}
		}
		
		public function check_table($tb){
			$query="select 1 from $tb"; // fastest working query to check if table exist or not http://stackoverflow.com/questions/6432178/how-can-i-check-if-a-mysql-table-exists-with-php
			
			if($this->db->query($query)){
				return true;
			}
			else{
				return false;
			}	
		}
		
		public function create_table($tb){
			$query="CREATE TABLE `$tb` (
					  `ngram_key` varchar(100) NOT NULL,
					  `org_key` varchar(300) DEFAULT NULL
					);";
			if($this->db->query($query)){
				return true;
			}
			else{
				return false;
			}
		}
		public function insert_data($ngram,$key,$x){
			$query="insert into index_".$x." values('$ngram','$key');";
			$this->db->query($query);
			return true;
		}
		
}
?>