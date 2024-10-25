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
				$o->org_key=$row->org_key;
				
				
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
				return (new Index_x())->instantiate($records);
			}
			else{
				return NULL;
			}
		}
		
		public function check_table($tb) {
			$query = "SELECT 1 FROM $tb"; // Fastest working query to check if table exists

			try {
				$this->db->query($query);
				return true; // Table exists
			} catch (Exception $e) {
				return false; // Table does not exist
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