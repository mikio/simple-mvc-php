<?php

class pager {
    private $link_url_ = null;
    private $total_ = 0;
    private $start_ = 0;
    private $show_data_count_ = 10;
    private $show_page_range_ = 15;
    private $page_key_ = 'p';
    private $lang_key_ = 'lang';
    // straight: 1ページが内部的に0から始まる1(0),2(1),3(2),..
    // count_up: 2ページ目が表示したデータ件数を加算して表示 1(0),2(15),3(30),..
    // same: 1ページから始まり、内部的に1から始まる 1(1),2(2),3(3)..
    private $mode_ = 'straight'; // straight,count_up,same
    private $half_range_ = false;
    private $query_separator_ = '&amp;';

    public $max_page = 0;
    public $current_page = 0;
    public $previous_text = '前へ';
    public $next_text = '次へ';
    public $separator = "|";
    public $from_data = "";

    public function __construct($params)
    {
        //print_r($params);
        if( !empty($params['sep']) ){
            $this->query_separator_ = $params['sep'];
        }
        $start = (int)$params['start'];
        $this->link_url_ = $params['page'];
        $this->total_ = $params['total'];
        if( !empty($params['display_count']) ){
            $this->show_data_count_ = $params['display_count'];
        }
        if( !empty($params['page_range']) ){
            $this->show_page_range_ = $params['page_range'];
        }
        if( !empty($params['page_key']) ){
            $this->page_key_ = $params['page_key'];
        }
        if( !empty($params['mode']) ){
            $this->mode_ = $params['mode'];
        }
        if( !empty($params['wrap']) ){
            $this->half_range_ = true;
        }


        $this->max_page = ceil($this->total_/$this->show_data_count_);
        // 仕様： max 1000件まで表示なので、100ページまで。
        if($this->max_page > 100){
        	 $this->max_page = 100;
        }
        /*
          if( $this->total_%$this->show_data_count_ == 0 ){
          $this->max_page--;
          }
        */

        $half_range = floor($this->show_page_range_/2);
        switch( $this->mode_ ){
        case 'straight':
            $this->current_page = $start+1;
            if( $this->half_range_ && $this->current_page > $half_range ){
                $this->start_page = ($this->current_page-$half_range);
                $this->stop_page = min($this->max_page,$this->current_page+$half_range);
            }else{
                $this->start_page = floor($this->calc_page_index($this->current_page)/$this->show_page_range_)*$this->show_page_range_+1;
                $this->stop_page = min($this->max_page,$this->start_page+$this->show_page_range_-1);
            }
            break;
        case 'count_up':
            $this->current_page = ($start/$this->show_data_count_)+1;
            if( $this->half_range_ && $this->current_page > $half_range ){
                $this->start_page = $this->current_page-$half_range;
                $this->stop_page = min($this->max_page,$this->current_page+$half_range);
            }else{
                $this->start_page = floor(($this->current_page-1)/$this->show_page_range_)*$this->show_page_range_+1;
                $this->stop_page = min($this->max_page,$this->start_page+$this->show_page_range_-1);
            }
            break;
        case 'same':
            $this->current_page = $start==0?1:$start;
            if( $this->half_range_ && $this->current_page > $half_range ){
                $this->start_page = $this->current_page-$half_range;
                $this->stop_page = min($this->max_page,$this->current_page+$half_range);
            }else{
                $this->start_page = floor(($this->current_page-1)/$this->show_page_range_)*$this->show_page_range_+1;
                $this->stop_page = min($this->max_page,$this->start_page+$this->show_page_range_-1);
            }
            break;
        default:
            trigger_error("[pager]no such mode:{$this->mode_}");
        }


    }

    // 2007/5/8版
    public function display() {
        $links = '';
        global $g_lang;
        if( $this->max_page>0 ){
            $links = '<div class="paging">';

            // Previous
            if( $this->current_page>1 ){
                $previous_page = $this->calc_page_index($this->current_page-1);
                $links .= sprintf("<a href=\"%s%s%s=%d%s%s=%s\">%s</a> %s ",$this->link_url_,$this->query_separator_,$this->page_key_,$previous_page,$this->query_separator_,$this->lang_key_,$g_lang,$this->previous_text,$this->separator);
            }

            // Page Listing
            for($i=$this->start_page;$i<=$this->stop_page;$i++){
                if( $i == $this->current_page ){
                    $links.=sprintf("<span class=\"cur\">%d</span>&nbsp;\n",$i);
                }else{
                    $page_index = $this->calc_page_index($i);
                    $links .= sprintf(" <span class=\"blk\"><a href=\"%s%s%s=%d%s%s=%s\">%d</a></span>&nbsp;\n",$this->link_url_,$this->query_separator_,$this->page_key_,$page_index,$this->query_separator_,$this->lang_key_,$g_lang,$i);
                }
            }

            // Next
            if( $this->current_page < $this->max_page ){
                $next_page = $this->calc_page_index($this->current_page+1);
                $links .= sprintf(" %s <a href=\"%s%s%s=%d%s%s=%s\">%s</a>",$this->separator,$this->link_url_,$this->query_separator_,$this->page_key_,$next_page,$this->query_separator_,$this->lang_key_,$g_lang,$this->next_text);
            }
            $links .= '</div>';
        }
        //$this->debug();
        //    echo $links;
        return $links;
    }

    // 2011/6/1版
    public function smpDisplay() {
        $links = '';
        global $g_lang;
        if( $this->max_page>0 ){
            $links = '<div class="paging">';

            // Previous
            if( $this->current_page>1 ){
                $previous_page = $this->calc_page_index($this->current_page-1);
                if ($this->from_data == "") {
                    $links .= sprintf("<a href=\"%s%s%s=%d%s%s=%s\" class=\"glc\">%s</a> %s ",$this->link_url_,$this->query_separator_,$this->page_key_,$previous_page,$this->query_separator_,$this->lang_key_,$g_lang,$this->previous_text,$this->separator);
                } else {
                    $links .= sprintf("<a href=\"%s%s%s=%d%s%s=%s&from=%s\" class=\"glc\">%s</a> %s ",$this->link_url_,$this->query_separator_,$this->page_key_,$previous_page,$this->query_separator_,$this->lang_key_,$g_lang,$this->from_data,$this->previous_text,$this->separator);
                }
            }

            // Next
            if( $this->current_page < $this->max_page ){
                $next_page = $this->calc_page_index($this->current_page+1);
                if ($this->from_data == "") {
                    $links .= sprintf(" %s <a href=\"%s%s%s=%d%s%s=%s\" class=\"grc\">%s</a>",$this->separator,$this->link_url_,$this->query_separator_,$this->page_key_,$next_page,$this->query_separator_,$this->lang_key_,$g_lang,$this->next_text);
                } else {
                    $links .= sprintf(" %s <a href=\"%s%s%s=%d%s%s=%s&from=%s\" class=\"grc\">%s</a>",$this->separator,$this->link_url_,$this->query_separator_,$this->page_key_,$next_page,$this->query_separator_,$this->lang_key_,$g_lang,$this->from_data,$this->next_text);
                }
            }
            // Page Listing
            for($i=$this->start_page;$i<=$this->stop_page;$i++){
                if( $i == $this->current_page ){
                    $links.=sprintf("<span class=\"cur\">%d</span>\n",$i);
                }else{
                    $page_index = $this->calc_page_index($i);
                    if ($this->from_data == "") {
                        $links .= sprintf(" <a href=\"%s%s%s=%d%s%s=%s\" class=\"blk\">%d</a>\n",$this->link_url_,$this->query_separator_,$this->page_key_,$page_index,$this->query_separator_,$this->lang_key_,$g_lang,$i);
                    } else {
                        $links .= sprintf(" <a href=\"%s%s%s=%d%s%s=%s&from=%s\" class=\"blk\">%d</a>\n",$this->link_url_,$this->query_separator_,$this->page_key_,$page_index,$this->query_separator_,$this->lang_key_,$g_lang,$this->from_data,$i);
                    }
                }
            }

            $links .= '</div>';
        }
        //$this->debug();
        //    echo $links;
        return $links;
    }

    private function calc_page_index($i) {
        $page_index = 0;
        switch( $this->mode_ ){
        case 'straight':
            $page_index = $i-1;
            break;
        case 'count_up':
            $page_index = ($i-1)*$this->show_data_count_;
            break;
        case 'same':
            $page_index = $i;
            break;
        }
        return $page_index;
    }

    public function debug() {
        echo <<<EOT
        <pre>
        Max Page:{$this->max_page}
        Current Page:{$this->current_page}
        Start Page:{$this->start_page}
        Stop Page:{$this->stop_page}
        </pre>
EOT;
    }
}

?>
