<?php



/* ruby version:
require 'markaby'

mab = Markaby::Builder.new
mab.html do
  head { title "Boats.com" }
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
require 'Builder.php';
use \Markaby\TagBuilder as T;

$m = new \Markaby\Builder();
$m->head(['title' => "Boats.com"])
->html5(function(){
  return T::body(function(){
    return T::h1("Boats.com has great deals").
        T::ul(function(){
          return T::li("$49 for a canoe").
          T::li("$39 for a raft").
          T::li("$29 for a huge boot that floats and can fit 5 people");
        });
  });
});
echo $m;
