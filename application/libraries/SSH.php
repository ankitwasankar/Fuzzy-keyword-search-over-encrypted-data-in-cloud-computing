<?php if (!defined('BASEPATH')) {exit('No direct script access allowed');}

/**
 * SSH class using ssh2 extention
 * for connecting and executing commands for linux server
 *
 * @author Shuky Dvir <shuky@quick-tips.net>
 * 
 *
 */
class CI_SSH {

    var $hostname = '';
    var $username = '';
    var $password = '';
    var $port = 22;
    var $debug = FALSE;
    var $conn_id = FALSE;
    var $data = '';


    /**
     * Constructor - Sets Preferences
     *
     * The constructor can be passed an array of config values
     */
    function  CI_SSH() {
        log_message('debug', "SSH Class Initialized");
    }

    /**
     * Initialize preferences
     *
     * @access	public
     * @param	array
     * @return	void
     */
    function initialize($config = array()) {
        foreach ($config as $key => $val) {
            if (isset($this->$key)) {
                $this->$key = $val;
            }
        }

        // Prep the hostname
        $this->hostname = preg_replace('|.+?://|', '', $this->hostname);
    }

    /**
     * SSH Connect
     *
     * @access	public
     * @param	array the connection values
     * @return	bool
     */
    function connect($config = array()) {
        if (count($config) > 0) {
            $this->initialize($config);
        }

        if (FALSE === ($this->conn_id = @ssh2_connect($this->hostname, $this->port))) {
            if ($this->debug == TRUE) {
                $this->_error('ssh_unable_to_connect');
            }
            return FALSE;
        }

        if ( ! $this->_login()) {
            if ($this->debug == TRUE) {
                $this->_error('ssh_unable_to_login');
            }
            return FALSE;
        }

        return TRUE;
    }

    /**
     * SSH Login
     *
     * @access	private
     * @return	bool
     */
    function _login() {
        return @ssh2_auth_password($this->conn_id, $this->username, $this->password);
    }

    /**
     * Validates the connection ID
     *
     * @access	private
     * @return	bool
     */
    function _is_conn() {
        if ( ! is_resource($this->conn_id)) {
            if ($this->debug == TRUE) {
                $this->_error('ssh_no_connection');
            }
            return FALSE;
        }
        return TRUE;
    }

    /**
     * Execute a command
     *
     * @access public
     * @return stream
     */
    function execute($command = "") {
        if($this->_is_conn()) {
            $stream = @ssh2_exec($this->conn_id, $command);
            return $this->_get_stream_data($stream);
        }
        else {
            $this->_error('fail: unable to execute command\n');
            return FALSE;
        }
    }

    /**
     * Get stream data
     *
     * @access privte
     * @return bool
     */
    function _get_stream_data($stream) {
        stream_set_blocking( $stream, true );
        while( $buf = fread($stream,4096) ) {
            $this->data .= $buf.'~';
        }
        return TRUE;
    }

    /**
     * rename file or directory
     */
    function rename($old_name , $new_name) {
        if($this->_is_conn()) {
            return @ssh2_sftp_rename($this->conn_id, $old_name , $new_name);
        }
        else return FALSE;
    }

    /**
     * copy file from remote
     */
    function recive($remote_file , $local_file) {
        if($this->_is_conn()) {
            return ssh2_scp_recv($this->conn_id, $remote_file , $local_file);
        }
        else {
            $this->_error('fail: unable to execute command\n');
            return FALSE;
        }
    }


    /**
     * Display error message
     *
     * @access	private
     * @param	string
     * @return	bool
     */
    function _error($line) {
        $CI =& get_instance();
        $CI->lang->load('ftp');
        show_error($CI->lang->line($line));
    }
}

// END FTP Class

/* End of file SSH.php */
/* Location: ./system/aplication/libraries/SSH.php */
