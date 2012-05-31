<?php


namespace Markaby;

class Builder{
    var $lines = array();
    var $head = array();

    function append_tag($tag, $content, $attr=array() ){
        $html_attr = '';

        $content = TagBuilder::ensure_call($content);

        $this->lines[]="<{$tag}{$html_attr}>$content</{$tag}>";
    }

    public function __call($name, $arguments)
    {
        $this->lines[]= call_user_func_array("\Markaby\TagBuilder::tag", array_merge(array($name),$arguments ) );
    }

    public function head($content){
        $this->head[]= '<!-- todo head -->';
        return $this;
    }

    public function serialize_head(){
        $content = implode("\n", array_filter($this->head, function ($s){ return !empty($s); }) );
        return "<head>$content</head>\n";
    }

    public function html5($content){
        $content = TagBuilder::ensure_call($content);

        $head = $this->serialize_head();

        $this->lines[]= "<!doctype html>\n"
            .$head
            ."<html>\n"
            .$content
            ."\n</html>\n";
        return $this;
    }


    public function __toString(){
        return implode("\n", array_filter($this->lines, function ($s){ return !empty($s); }) );
    }
}

class TagBuilder {


    public static function ensure_call($content){
        if (is_callable($content)){
            $content = $content();
        }
        return $content;
    }

    public static function tag($tag, $content, $attr=array() ){
        $html_attr = '';
        $content = self::ensure_call($content);

        $s = "<{$tag}{$html_attr}>\n$content\n</{$tag}>\n";
        return $s;
    }

    public static function __callStatic($name, $arguments)
    {
        return call_user_func_array("self::tag", array_merge(array($name),$arguments ) );
    }

}


/*
require 'markaby'

mab = Markaby::Builder.new
mab.html do
  head { title "Boats.com"
  body do
    h1 "Boats.com has great deals"
    ul do
      li "$49 for a canoe"
      li "$39 for a raft"
      li "$29 for a huge boot that floats and can fit 5 people"
    end
  end
end
puts mab.to_s
*/
use \Markaby\TagBuilder as T;

$m = new \Markaby\Builder();
$m->head( array('title' => "Boats.com") )
    ->html5(function(){
      return
      T::body(function(){
        return
        T::h1("Boats.com has great deals").
        T::ul(function(){
          return
          T::li("$49 for a canoe").
          T::li("$39 for a raft").
          T::li("$29 for a huge boot that floats and can fit 5 people");
        });
      });
    });
echo $m;