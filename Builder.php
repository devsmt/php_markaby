<?php

namespace Markaby;

class Builder {
    var $lines = array();
    var $head = array();

    function append_tag($tag, $content, array $attr=array() ){
        $this->lines[] = TagBuilder::tag($tag, $content, $attr);
    }

    public function text($text) {
        $this->lines[] = $text;
    }

    public function __call($name, $arguments) {
        $this->lines[] = call_user_func_array("\Markaby\TagBuilder::tag", array_merge(array($name), $arguments ) );
    }

    public function head($content) {
        $this->head[] = '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';

        if( is_array($content)) {
            foreach($content as $k=>$v){
                $this->head[] = TagBuilder::tag($k,$v);
            }
        } else {
            $this->head[] = TagBuilder::ensure_call($content);
        }

        return $this;
    }

    public function serialize_head() {
        $content = implode("\n", array_filter($this->head, function ($s){ return !empty($s); }) );
        return "<head>$content</head>\n";
    }

    public function html5($content) {
        $content = TagBuilder::ensure_call($content);

        $head = $this->serialize_head();

        $this->lines[]= "<!doctype html>\n"
            .$head
            ."<html>\n"
            .$content
            ."\n</html>\n";
        return $this;
    }

    public function xhtml_strict() {
    }
    public function xhtml_transitional() {
    }

    public function __toString(){
        return implode("\n", array_filter($this->lines, function ($s){ return !empty($s); }) );
    }
}

class TagBuilder {
    /**
     * accept as input strings and functions
     */
    public static function ensure_call($content) {
        if ( is_callable($content) ){
            $content = $content();
        } elseif( is_array($content) ) {
            return $content;
        }
        return $content;
    }
    /**
     * generates html tag
     */
    public static function tag($tag, $content=null, array $attr=array() ) {
        $html_attr = '';
        $content = self::ensure_call($content);

        // self-closing tag
        if( empty($content)) {
            $s = "<{$tag}{$html_attr} />\n";
        } else {
            $s = "<{$tag}{$html_attr}>\n$content\n</{$tag}>\n";
        }
        return $s;
    }

    public static function __callStatic($name, $arguments) {
        return call_user_func_array("self::tag", array_merge(array($name),$arguments ) );
    }
}



