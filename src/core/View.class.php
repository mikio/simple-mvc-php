<?php
// テンプレートにデータを渡し、レンダリングするクラス。
class View {
    private $outputEncoding = DEFAULT_ENCODING;
    private $varHolder;
    private $contentType = 'Content-type: text/html; charset=';
    private $resCountsHd = "Counts: ";
    private $resCounts = "-";
    private $defaultPath;

    private function templateFile($path) {
        $templateFile = sprintf(ROOT."src/%s%s.tmpl.php", TEMPLATE_DIR, $path);
        get_log()->debug("templateFile:".$templateFile);
        if (!file_exists($templateFile)) {
            $templateFile = sprintf(ROOT."src/%s/%s", TEMPLATE_DIR, DUMMY_TEMPLATE_FILE);
        }
        return $templateFile;
    }

    public function __construct($controllerName, $actionName) {
        $this->defaultPath = sprintf('/%s/%s', $controllerName, $actionName);
        $this->varHolder = array();
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

    /**
     * templateファイルをレンダリングする。
     * 通常は、controllerName, actionNameにもとづき、自動的にテンプレートファイルを選択する。
     * これを変更したいときは、$pathを指定する。
     * 例えば下記のテンプレートファイルをレンダリングしたいときは
     * {TEMPLATE_DIR}/hoge/fuga.tmpl.php
     * => 'hoge/fuga' をpathとして渡す(/から始めることに注意)。
     * また、レイアウトファイルを変更したいときは、最後の引数にファイル名のみ指定する。
     * そのファイルは、TEMPLATE_DIR直下にあること。
     *
     * @param string $path テンプレートのパス
     * @param array $data テンプレートに渡す変数の連想配列
     * @return string レンダリングした内容
     */
    public function render($path = null, $layoutFile = LAYOUT_FILE) {
        // header($this->contentType.$this->outputEncoding);
        // header($this->resCountsHd . $this->resCounts);

        if ($path != null) {
            $templateFile = $this->templateFile($path);
        } else {
            $templateFile = $this->templateFile($this->defaultPath);
        }
        $content = $this->renderTemplate($templateFile);
        $this->__set('content', $content);

        $layoutPath = sprintf("%s/%s", TEMPLATE_DIR, $layoutFile);
        $content = $this->renderTemplate($layoutPath);
        echo $content;
    }

    /**
     * templateファイル内で別ファイルをインクルードしたいときに使用する。
     * @param string $path テンプレートのパス
     * @param array $data テンプレートに渡す変数の連想配列
     * @return string レンダリングした内容
     */
    public function inc($path, $data = array()) {
        if ($path == null) return '';
        $templateFile = $this->templateFile($path);
        echo $this->renderTemplate($templateFile, $data);
    }

    public function renderTemplate($templateFile, $data = array()) {
        extract(array_merge($this->varHolder, $data));

        ob_start();
        ob_implicit_flush(0);

        require $templateFile;

        return ob_get_clean();
    }
}
?>
