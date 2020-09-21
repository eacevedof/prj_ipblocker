<?php
namespace TheFramework\Components;

class ComponentString
{
    private string $string;
    private string $matches;

    public function __construct(string $string)
    {
        $this->string = strtolower(trim($string));
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
            if($this->_in_string($search, $this->string))
                return true;
        return false;
    }

    //!has_all => is_none
    public function has_all(array $substr=[])
    {
        if(!$substr) return true;
        foreach ($substr as $search)
            if(!$this->_in_string($search, $this->string))
                return false;
        return true;
    }

    public function is_empty(){ return $this->string==="" || $this->string===null;}

    public function match($pattern)
    {
        $pattern = "/$pattern/im";
        $matches = [];
        $r = preg_match($pattern, $this->string, $matches);
        if($r) $this->matches[][$pattern] = $matches;
        return $r;
    }

    public function is_equal($string) {return $this->string === $string;}

    public function get_matches(){return $this->matches;}
}