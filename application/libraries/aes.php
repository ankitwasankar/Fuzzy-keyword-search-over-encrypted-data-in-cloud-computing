<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * AES encryption
 */
class AES {
    const M_CBC = 'cbc';
    const M_CFB = 'cfb';
    const M_ECB = 'ecb';
    const M_NOFB = 'nofb';
    const M_OFB = 'ofb';
    const M_STREAM = 'stream';

    protected $key;
    protected $cipher;
    protected $data;
    protected $mode;
    protected $IV;

    /**
     * Constructor
     *
     * @param string|null $data
     * @param string $key
     * @param int $blockSize
     * @param string|null $mode
     */
    function __construct($data = null, $key = 'xbYtKK', $blockSize = 256, $mode = self::M_CBC) {
        $this->setData($data);
        $this->setKey($key);
        $this->setBlockSize($blockSize);
        $this->setMode($mode);
        
        // Set a fixed IV to ensure the same ciphertext for the same plaintext
        $this->setIV(str_repeat("\0", $this->getIvSize())); // Fixed IV (all zeros)
    }

    /**
     * Set data
     *
     * @param string|null $data
     */
    public function setData($data) {
        $this->data = $data;
    }

    /**
     * Set key
     *
     * @param string $key
     */
    public function setKey($key) {
        $this->key = $key;
    }

    /**
     * Set block size and cipher
     *
     * @param int $blockSize
     */
    public function setBlockSize($blockSize) {
        $this->cipher = 'aes-' . $blockSize . '-cbc';
    }

    /**
     * Set mode
     *
     * @param string $mode
     */
    public function setMode($mode) {
        $this->mode = $mode; // OpenSSL mode is always CBC for block ciphers in this implementation
    }

    /**
     * Validate parameters
     *
     * @return bool
     */
    public function validateParams() {
        return !empty($this->data) && !empty($this->key) && !empty($this->cipher);
    }

    /**
     * Set IV
     *
     * @param string $IV
     */
    public function setIV($IV) {
        $this->IV = $IV;
    }

    /**
     * Get IV size
     *
     * @return int
     */
    protected function getIvSize() {
        return openssl_cipher_iv_length($this->cipher);
    }

    /**
     * Encrypt data
     *
     * @return string
     * @throws Exception
     */
    public function encrypt() {
        if ($this->validateParams()) {
            return trim(base64_encode(openssl_encrypt($this->data, $this->cipher, $this->key, OPENSSL_RAW_DATA, $this->IV)));
        } else {
            throw new Exception('Invalid params!');
        }
    }

    /**
     * Decrypt data
     *
     * @return string
     * @throws Exception
     */
    public function decrypt() {
        if ($this->validateParams()) {
            return trim(openssl_decrypt(base64_decode($this->data), $this->cipher, $this->key, OPENSSL_RAW_DATA, $this->IV));
        } else {
            throw new Exception('Invalid params!');
        }
    }
}

/* Location: ./application/controllers/encrypt.php */
