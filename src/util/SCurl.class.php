<?php

class SCurl {
    public $default = array(
                            CURLOPT_SSL_VERIFYPEER=>false,
                            CURLOPT_FOLLOWLOCATION=>1,
                            CURLOPT_TIMEOUT=>1,
                            CURLOPT_USERAGENT       => "goo wikipedia (http://help.goo.ne.jp/contact/)",
                            );
    private $curl = null;
    
    public function __construct($opt=null) {
        $this->curl = curl_init();
        if( $opt ){
            $opt = $opt + $this->default;
            $this->set_opt_array($opt);
        }else{
            $this->set_opt_array($this->default);
        }
    }
    
    public function set_opt_array($opt) {
        curl_setopt_array($this->curl, $opt);
    }
    
    public function get_source($url)
    {
        curl_setopt($this->curl, CURLOPT_URL, $url);
        
        ob_start();
        curl_exec($this->curl);
        $source = ob_get_contents();
        ob_end_clean();
        
        return $source;
    }
    
    public function get_errno() {
        return curl_errno($this->curl);
    }
    
    public function get_info($param = null) {
        if (is_null($param)) {
            return curl_getinfo($this->curl);
        } else {
            return curl_getinfo($this->curl, $param);
        }
    }
    
    public function close() {
        curl_close($this->curl);
    }
    
    public function __destruct() {
        $this->close();
    }
}

?>
