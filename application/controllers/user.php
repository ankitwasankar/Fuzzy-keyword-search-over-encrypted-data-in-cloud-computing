<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {

    /*************************************************
                        Constructor 
    **************************************************/
    public function __construct() {
        parent::__construct();
        /********* helper **********/
        $this->load->helper('form');
        $this->load->helper('url');
        /********* Library **********/
        $this->load->library('form_validation');
        $this->load->library('encrypt');
        $this->load->library('session');
        $this->load->library('pagination');
        $this->load->library('user_agent');
        /********* model **********/
        $this->load->model('user_auth');
        $this->load->model('upl_files');
        $this->load->model('file_keys');
        $this->load->model('index_x');
        /***********  delete all tmp files *******************/
        $files = glob(TEMP_FILE_LOCATION . '*'); // get all file names
        foreach ($files as $file) { // iterate files
            if (is_file($file))
                unlink($file); // delete file
        }
        /************************************/
        if ($this->session->userdata('status') != 'user')
            die('Session Expired..');
    }

    /*************************************************
                        dashboard
    **************************************************/
    public function index() {
        $data['message'] = "";
        $this->load->view('user/header/header', $data);
        $this->load->view('user/dashboard', $data);
        $this->load->view('user/footer/footer', $data);
    }

    /***************************************************************************
                Upload File
    **************************************************************************/
    public function upload() {
        $data['message'] = "";
        $title = $this->input->post('title');
        $title = base64_encode($title);
        $keys = $this->input->post('keys');
        if (!$title == "" and !$keys == "") {
            $image = $_FILES["uploaded"]["tmp_name"];
            $original_image = $_FILES["uploaded"]["name"];
            $ext = pathinfo($original_image, PATHINFO_EXTENSION); // extension of uploaded file
            $enm = rand(1000000, 100000000000);    
            $enm = md5($enm);
            
            $path1 = ORG_FILE_LOCATION . $enm;    
            
            $fh = fopen($image, 'rb'); // handle for file from form
            $fh1 = fopen($path1, 'wb'); // handle for encrypted file on server

            /********** Encrypting the received file from form ************/
            $cache = '';
            $eof = false;

            while (1) {
                if (!$eof) {
                    if (!feof($fh)) {
                        $row = fgets($fh, 4096);
                    } else {
                        $row = '';
                        $eof = true;
                    }
                }
                if ($cache !== '')
                    $row = $cache . $row;
                elseif ($eof)
                    break;

                $b64 = base64_encode($row);
                $put = '';

                if (strlen($b64) < 76) {
                    if ($eof) {
                        $put = $b64 . "\n";
                        $cache = '';
                    } else {
                        $cache = $row;
                    }

                } elseif (strlen($b64) > 76) {
                    do {
                        $put .= substr($b64, 0, 76) . "\n";
                        $b64 = substr($b64, 76);
                    } while (strlen($b64) > 76);

                    $cache = base64_decode($b64);

                } else {
                    if (!$eof && $b64[75] == '=') {
                        $cache = $row;
                    } else {
                        $put = $b64 . "\n";
                        $cache = '';
                    }
                }
                
                /********** Uploading encrypted file on server ************/
                if ($put !== '') {
                    fputs($fh1, $put); 
                }
            }

            fclose($fh1); 
            fclose($fh); 
            
            /********** Now adding database information for new upload ************/
            $keys = explode(' ', $this->input->post('keys')); // retrieve string and separate words and put in array $keys
            // Create an instance of the Ngram class
            $ngram_instance = new Ngram();
            foreach ($keys as $key) {
                $key = strtolower($key); // keep unencrypted key
                // Call the non-static method on the instance
                $enc_ngrams = $ngram_instance->create_enc_ngrams($key); // create unencrypted ngrams
                $ngram_instance->add_ngrams_to_db($enc_ngrams, base64_encode($key));
            }    
            
            /***** Store Uploaded file details like date of upload, name of file (encrypted) etc ******/
            $uid = $this->session->userdata('userid');
            $keys = explode(' ', $this->input->post('keys')); // retrieve string and separate words and put in array $keys
            if ((new Upl_files())->add_upload($title, $enm, $ext, $uid)) {
                $f_info = (new Upl_files())->get_finfo($enm);
                if ((new File_Keys())->add_keys($f_info, $keys)) {
                    $data['message'] = "File uploaded successfully";
                }
            } else {
                $data['message'] = "Some Error occurred..";
            }
            /******************************************************/
        } else {
            $data['message'] = "Please enter valid details..";
        }
        
        $this->load->view('user/header/header', $data);
        $this->load->view('user/dashboard', $data);
        $this->load->view('user/footer/footer', $data);
    }    

    /*************************************************
        Delete function
    **************************************************/
    public function delete($fid) {
        $data['message'] = "";
        /** deleting file from server **************/
        $row = (new Upl_files())->get_finfo_by_fid($fid);
        $filename = $row[0]->f_loc;  //encrypted filename on server
        $target_path = ORG_FILE_LOCATION . $filename;
        if (file_exists($target_path)) {
            chmod($target_path, 0755); //Change the file permissions if allowed read and write
            unlink($target_path); //remove the file
        }
        /** deleting information from database *****/
        (new Upl_files())->delete_file($fid); // delete file on server and delete data from upl_files and file_keys 
        
        redirect('user/index', 'Location');
    }
    
    /*************************************************
            Logout 
    **************************************************/
    public function logout() {
        $this->session->sess_destroy();
        $this->load->view('user/header/header');
        $this->load->view('logout');
    }

    /*************************************************
            Download file securely
    **************************************************/
    public function download($fid) {
        $row = (new Upl_files())->get_finfo_by_fid($fid);
        $original_filename = ORG_FILE_LOCATION . $row[0]->f_loc;
        $ext = $row[0]->f_ext;
        $new_filename = TEMP_FILE_LOCATION . "file_" . rand(100, 1000000) . "." . $ext;

        /*****************decoding file to $newfilename *********************/
        $fp2 = fopen($new_filename, 'wb');

        // Check if the file pointer was successfully created
        if ($fp2 === false) {
            // Handle the error, e.g., log it or show a message
            die('Could not open file for writing: ' . $new_filename);
        }

        fputs($fp2, base64_decode(file_get_contents($original_filename)));
        header('Content-Description: File Transfer');
        header("Content-Type: application/" . $ext);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Disposition: attachment; filename="' . $new_filename . '"');
        header("Content-Length: " . filesize($new_filename));
        
        ob_clean();
        flush();
        readfile($new_filename);
        fclose($fp2);
        /********************************************/
    }

    /*************************************************
            Search
    **************************************************/
    public function search_backup() {
        $data['message'] = "";
        $starttime = microtime(true);
        $query = strtolower($this->input->post('query'));
        $keys = explode(' ', $query);
        $no_of_search_keys = count($keys);
    
		
        /**************    Obtain fuzzy set    ****************/
        $final_fuzzy_set = array(); 
        $i = 0;
        foreach ($keys as $row) {
            $count = 0; // index_x where x=count
            $ngrams = (new Ngram())->create_enc_ngrams($row);  // No database query
            foreach ($ngrams as $row1) {
                $tbnm = "index_" . $count;
                $count++;
                $tmp_fuzzy_set = (new Index_x())->compare($row1, $tbnm); // database query
                $final_fuzzy_set = array_merge($final_fuzzy_set, $tmp_fuzzy_set);
            }
            $i++;
        }
		
        foreach ($final_fuzzy_set as $row) {
            $info = (new File_keys())->get_fid_by_org_key($row->org_key);
            if ($info) {
                $data['files'][] = $info[0]; 
            }
        }
        
		var_dump($data);
		die('');
        $endtime = microtime(true);
        $data['time'] = round($endtime - $starttime, 2);
        
        $this->load->view('user/header/header', $data);
        $this->load->view('user/search_result', $data);
        $this->load->view('user/footer/footer', $data);
    }
	
	/*************************************************
			Search
	**************************************************/
		public function search(){
			$data['message']="";
			$query=strtolower($this->input->post('query'));
			$keys=explode(' ',$query);
			$no_of_search_keys=count($keys);
		
		/**************	Obtain fuzzy set	****************/
			$final_fuzzy_set=array(); 
			$i=0;
			foreach($keys as $row){
				//$ngrams=Ngram::create_enc_ngrams($row);
				$count=0; // index_x where x=count
				//$ngrams=Ngram::create_ngrams($row); 
				$ngrams= (new Ngram())->create_enc_ngrams($row); 
				foreach($ngrams as $row1){
					$tbnm="index_".$count; // table name
					$count++; // 
					$tmp_fuzzy_set= (new Index_x())->compare($row1,$tbnm);
					if($tmp_fuzzy_set==NULL){continue;}
					foreach($tmp_fuzzy_set as $row2){	
						$fuzzy_set=new Fuzzy_set(); // object of Fuzzy_set defined in library filename: fuzzy_set.php
						$fuzzy_set->org_key=$row;
						$fuzzy_set->fuzzy_key=$row2->org_key;
						$final_fuzzy_set[$i++]=$fuzzy_set;
					}
					
				}	
			
			}
			/* remove duplicate objects in array */
			$final_fuzzy_set=(new Fuzzy_set())->get_proper_results($final_fuzzy_set);
				
		/**************	Jacard calculation	****************/
			$i=0;$arr=array();
			foreach($final_fuzzy_set as $row){
				$arr[$i++]= (new Ngram())->jaccard_coefficient($row->fuzzy_key,$row->org_key);
			}				
			$f_set=array();
			$o_set=array();
			$i=0;
			foreach($final_fuzzy_set as $row){
				$f_set[$i]=$row->fuzzy_key;
				$o_set[$i]=$row->org_key;
				$i++;
			}
			
		/***** obtaining final suggested keywords according to jaccard coeeficient ****/
			
			$corrected_keys=array();
			$len=count($arr);
			for($i=0;$i<$no_of_search_keys;$i++){
				$max=-1;
				for($j=0;$j<$len;$j++){
					if($keys[$i]===$o_set[$j])
						if($max<$arr[$j]){
							$max=$arr[$j];
							$corrected_keys[$i]= base64_decode($f_set[$j]);
						}

				}
				$max=-1;
			}
		/********* Now $corrected_keyword array contains corrected keywords to search ***********************/
		/********* obtain the fid's to display ***********************/
			$fid_arr=array();
			$i=0;
			
			foreach($corrected_keys as $row){
				$records= (new File_keys())->get_fid_by_org_key(base64_encode($row));
				foreach($records as $row1){
					$fid_arr[$i++]=$row1->f_id;
				}
			}
		/********** Ranking the results ***********************/
			$count_arr=array_count_values($fid_arr);
			arsort($count_arr);
			$fid_arr=array_keys($count_arr);
		/****** sending list of final file_ids after ranking to view to display result	********/
			
			$data['f_array']=$fid_arr;
			$data['search_keys']=$keys;
			$data['fuzzy_keys']= $corrected_keys;
			$this->load->view('user/header/header', $data);
			$this->load->view('user/search', $data);
			$this->load->view('user/footer/footer', $data);
		}
		
}
