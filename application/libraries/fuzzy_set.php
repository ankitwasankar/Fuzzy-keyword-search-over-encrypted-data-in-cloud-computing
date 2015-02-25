<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Fuzzy_set{

	public $fuzzy_key;
	public $org_key;
	
	public function get_proper_results($result){
		
		return array_unique($result, SORT_REGULAR); // return array without duplicate objects..
		
		/*
		$f_k=array();
		$o_k=array();
		$c_arr=array();
		foreach($result as $row){
			$o_k[$i++]=$row->org_key;
			$f_k[$i++]=$row->fuzzy_key;
		}
		$c_arr=array_count_values($o_k);
		var_dump($o_k);var_dump($f_k);var_dump($c_arr);die;
		
		*/
	}
}




/* Location: ./application/controllers/fuzzy_set.php */