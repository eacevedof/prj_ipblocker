<?php
namespace TheFramework\Components;

class ComponentArray
{
    private string $array;
    private string $matches;

    public function __construct(array $array)
    {
        $this->array = $array;
    }

    private function _in_string($search, $string){
        if(strstr($string, $search))
            return true;
        return false;
    }

    public function has_some(array $substr=[])
    {
        if(!$substr) return true;
        foreach ($substr as $search)
            if($this->_in_string($search, $this->array))
                return true;
        return false;
    }

    //!has_all => is_none
    public function has_all(array $substr=[])
    {
        if(!$substr) return true;
        foreach ($substr as $search)
            if(!$this->_in_string($search, $this->array))
                return false;
        return true;
    }

    public function is_empty(){ return $this->array==="" || $this->array===null;}

    public function match($pattern)
    {
        $pattern = "/$pattern/im";
        $matches = [];
        $r = preg_match($pattern, $this->array, $matches);
        if($r) $this->matches[][$pattern] = $matches;
        return $r;
    }

    public function is_equal($string) {return $this->array === $string;}

    public function get_matches(){return $this->matches;}

    public function starts_with($string)
    {
        if(strpos($this->array, $string)===0) return true;
        return false;
    }

    public function is_larger($string){return strlen($this->array)>strlen($string);}

    public function is_shorter($string){return strlen($this->array)<strlen($string);}

    public function samelen($string){return strlen($this->array)==strlen($string);}

    public function ends_with($string)
    {
        if($this->is_shorter($string) || $this->samelen($string)) return false;
        $ipos = strpos($this->array, $string);
        if(!$ipos) return false;
        return (($ipos + strlen($string)) === strlen($this->array));
    }
}