<?php
class Response {
    protected $content;
    protected $statusCode = 200;
    protected $statusText = 'OK';
    protected $httpHeaders = array();

    public function send() {
        header('HTTP/1.1 ' . $this->statusCode . ' ' . $this->statusText);

        foreach ($this->httpHeaders as $name => $value) {
            header($name . ': ' . $value);
        }

        echo $this->content;
    }

    public function content($content) {
        $this->content = $content;
    }

    public function statusCode($statusCode, $statusText = '') {
        $this->statusCode = $statusCode;
        $this->statusText = $statusText;
    }

    public function header($name, $value) {
        $this->httpHeaders[$name] = $value;
    }
}
?>