<?php
// テンプレートにデータを渡し、レンダリングするクラス。
class View {

    private $varHolder;
    private $contentType = 'Content-type: text/html; charset=UTF-8';
    private $resCountsHd = "Counts: ";
    private $resCounts = "-";

    public function __construct($templateFile) {
        $this->varHolder = array();

        $this->__set('templateFile', $templateFile);

        $dates = explode('/', date('H/i/s/m/d/Y'));
        $now_array = array('H' => $dates[0], 'i' => $dates[1], 's' => $dates[2], 'm' => $dates[3], 'd' => $dates[4], 'Y' => $dates[5]);
        $this->__set('year', $now_array['Y']);
        $this->__set('month', $now_array['m']);
        $this->__set('day', $now_array['d']);
    }

    public function __set($key, $value) {
        $this->varHolder[$key] = $value;
    }

    public function & __get($key) {
        return $this->varHolder[$key];
    }

    public function render() {
        header($this->contentType);
        header($this->resCountsHd . $this->resCounts);

        extract($this->varHolder);

        ob_start();
        ob_implicit_flush(0);

        $layout = sprintf("%s/%s", TEMPLATE_DIR, LAYOUT_FILE);
        require($layout);

        echo ob_get_clean();
    }

    // テンプレートファイルの存在チェック
    private function createView($viewName) {
    }
}
?>
