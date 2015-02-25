<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ngram{

	/*************************************************
						Constructor 
	**************************************************/
		public function __construct(){
			$CI =& get_instance();
			/********* helper **********/
			$CI->load->helper('form');
			$CI->load->helper('url');
			$CI->load->helper('file');
			/********* Library **********/
			$CI->load->library('form_validation');
			$CI->load->library('encrypt');
			$CI->load->library('session');
			$CI->load->library('pagination');
			$CI->load->library('user_agent');
			/********* model **********/
			$CI->load->model('user_auth');
			$CI->load->model('upl_files');
			$CI->load->model('file_keys');	
			$CI->load->model('index_x');	
		}
	
	/*************************************************
					create Ngrams return as array
	**************************************************/
		public function create_ngrams($word,$n=2){
		    $ngrams=array();
			$len=strlen($word);
			for($i=0;$i<$len;$i++){
				if($i> ($n - 2)){
					$ng= '';
					for($j=$n-1;$j>=0;$j--){
						$ng.=$word[$i-$j];
					}
					$ngrams[]=$ng;
				}
			}	
			
			return $ngrams;
		}
		
	/*************************************************
			create Encrypted Ngrams
	**************************************************/
		public function create_enc_ngrams($word,$n=2){
		    $ngrams=array();
			$len=strlen($word);
			for($i=0;$i<$len;$i++){
				if($i> ($n - 2)){
					$ng= '';
					for($j=$n-1;$j>=0;$j--){
						$ng.=$word[$i-$j];
					}
					$ngrams[]=$ng;
				}
			}		
			
			$enc_ngrams=array();
			$i=0;
			foreach($ngrams as $row){
				$aes = new AES($row);
				$enc_ngrams[$i++] = $aes->encrypt();
			}
			return $enc_ngrams;
		}
		
	/*************************************************
			insert encrypted n-grams
	**************************************************/
		public function add_ngrams_to_db($ngrams,$key){
			
			$len=sizeof($ngrams); // get number of n-grams created
			/* check if all required tables are available or not */
			for($i=0;$i<$len;$i++){
				$tbl_name="index_".$i;
				if(!Index_x::check_table($tbl_name)){ //check if table exists or not
					Index_x::create_table($tbl_name); // create table if not exists
				}
			}
			$i=0;
			foreach($ngrams as $row){
				Index_x::insert_data($row,$key,$i);
				$i++;
			}
		}
		
	/*************************************************
			Return jaccard coefficient
	**************************************************/
		public function jaccard_coefficient($str1,$str2){
			$str1=  array_unique(str_split($str1));
			$str2=  array_unique(str_split($str2));
			
			$union= array_unique(array_merge((array)$str1,(array)$str2));
			$intersect=array_intersect((array)$str1,(array)$str2);
			
			$ucount=count($union);
			$icount=count($intersect);
			
			return ($icount/$ucount);
		}
}




/* Location: ./application/controllers/welcome.php */