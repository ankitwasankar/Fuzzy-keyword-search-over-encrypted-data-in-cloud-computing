<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Guest extends CI_Controller {

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
    }

    /*************************************************
                        Index Page
    **************************************************/
    public function index() {
        $data['message1'] = "";
        $data['message2'] = "";

        $this->load->view('guest/header/header', $data);
        $this->load->view('guest/index', $data);
        $this->load->view('guest/footer/footer', $data);
    }

    /*************************************************
                        Register
    **************************************************/		
    public function register() {
        $data['message1'] = "";
        $data['message2'] = "";

        $this->form_validation->set_rules('username', 'Username', 'trim|required|valid_email|max_length[60]|xxs_clean'); 
        $this->form_validation->set_rules('password', 'Password', 'trim|required|matches[password_c]|max_length[16]|min_length[6]|md5');
        $this->form_validation->set_rules('password_c', 'Confirm password', 'trim|required|md5');
        $this->form_validation->set_rules('name', 'Name', 'trim|required|max_length[30]|min_length[2]');
        $this->form_validation->set_rules('s_que', 'Security question', 'trim|required|max_length[30]|min_length[2]');
        $this->form_validation->set_rules('s_ans', 'Answer to security question', 'trim|required|max_length[30]|min_length[2]');
        $this->form_validation->set_rules('surname', 'Last name', 'trim|required|max_length[30]|min_length[2]');
        $this->form_validation->set_rules('mb_no', 'Mobile number', 'trim|max_length[10]|min_length[10]');

        if ($this->form_validation->run() == TRUE) {	
            if (ctype_alpha($this->input->post('name')) && ctype_alpha($this->input->post('surname'))) {	
                $unm = $this->input->post('username');
                $pwd = $this->input->post('password');
                $fnm = $this->input->post('name');
                $lnm = $this->input->post('surname');
                $mb_no = $this->input->post('mb_no');
                $s_que = $this->input->post('s_que');
                $s_ans = $this->input->post('s_ans');

                // Create an instance of the User_auth model
                $userAuth = new User_auth();

                if ($userAuth->register($unm, $pwd, $fnm, $lnm, $mb_no, $s_que, $s_ans)) {
                    $data['message1'] = 'Registration successful';
                    if ($userAuth->login($unm, $pwd)) {
                        $url1 = base_url() . "user/index/";
                        redirect($url1, 'Location');
                    } else {
                        $data['message1'] = 'Some Error Occurred';
                    }
                } else {
                    $data['message1'] = 'Some Error Occurred';
                }	
            } else {
                $data['message1'] = 'First Name or Last Name is incorrect';
            }
        }

        $this->load->view('guest/header/header', $data);
        $this->load->view('guest/index', $data);
        $this->load->view('guest/footer/footer', $data);
    }

    /*************************************************
        Login and Login Page
    **************************************************/
    public function login() {
        $data['message1'] = "";
        $data['message2'] = "";

        $this->form_validation->set_rules('username', 'Username', 'trim|required|valid_email|max_length[60]|xxs_clean');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|max_length[16]|min_length[6]|md5');

        if ($this->form_validation->run() == TRUE) {
            $username = $this->input->post('username');
            $password = $this->input->post('password');

            // Create an instance of the User_auth model
            $userAuth = new User_auth();

            if ($userAuth->login($username, $password)) {	
                $data['message2'] = "Success";
                redirect('user/index/', 'Location');
            } else {
                $data['message2'] = 'Username password not matched.';
            }
        } else {
            $data['message2'] = "Some error occurred...";
        }

        $this->load->view('guest/header/header', $data);
        $this->load->view('guest/index', $data);
        $this->load->view('guest/footer/footer', $data);
    }

    /*************************************************
        Admin Login and Login Page
    **************************************************/
    public function admin_login() {
        $data['message'] = "";

        $this->form_validation->set_rules('username', 'Username', 'trim|required|max_length[60]|xxs_clean');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|max_length[16]|min_length[6]|md5');

        if ($this->form_validation->run() == TRUE) {
            $username = $this->input->post('username');
            $password = $this->input->post('password');

            // Create an instance of the User_auth model
            $userAuth = new User_auth();

            if ($userAuth->login($username, $password)) {	
                $data['message'] = "Success";
                redirect('admin/index/', 'Location');
            } else {
                $data['message'] = 'Username password not matched.';
            }
        }

        $this->load->view('guest/header/header', $data);
        $this->load->view('guest/admin_login', $data);
    }

    /*************************************************
        Password Recovery step 1
    **************************************************/
    public function recovery1() {
        $data['message'] = "";
        $this->load->view('guest/header/header', $data);
        $this->load->view('guest/password_recovery_1', $data);

        $this->form_validation->set_rules('username', 'Username', 'trim|required|max_length[60]|xxs_clean');
        if ($this->form_validation->run() == TRUE) {
            $username = $this->input->post('username');

            // Create an instance of the User_auth model
            $userAuth = new User_auth();

            if ($userAuth->find($username)) {	
                $username = urlencode($username);
                redirect('guest/recovery2/' . $username, 'Location');
            } else {
                $data['message'] = "User name does not exist.";
            }
        }
    }

    /*************************************************
        Password Recovery step 2
    **************************************************/
    public function recovery2($username) {
        $data['message'] = "";
        $username = urldecode($username);
        $data['username'] = $username;

        // Create an instance of the User_auth model
        $userAuth = new User_auth();
        $data['sec_qstn'] = $userAuth->security_qstn($username);

        $this->load->view('guest/header/header', $data);
        $this->load->view('guest/password_recovery_2', $data);

        $this->form_validation->set_rules('ans', 'Answer', 'trim|required|max_length[60]|xxs_clean');
        if ($this->form_validation->run() == TRUE) {
            $ans = $this->input->post('ans');
            if ($userAuth->match_answer($username, $ans)) {	
                echo "<center><h2>Password Recovered....</h2><br>";
                $pass = substr(md5(rand(100, 1000)), 1, 6);
                $userAuth->update_pass($username, $pass);
                echo "<b>" . $pass . "</b><br><br>";
                echo "<a href='" . base_url() . "guest/index'>Click to login</a></center>";
                die;
            } else {
                $data['message'] = "Not valid..";
            }
        }
    }
}

/* Location: ./application/controllers/guest.php */
