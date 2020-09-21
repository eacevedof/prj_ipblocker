<?php
namespace TheFramework\Components;

class ComponentString
{
    private string $string;

    public function __construct(string $string)
    {
        $this->string = $string;
    }

    private function _in_string($string, $search){
        if(strstr($string, $search))
            return true;
        return false;
    }

    private function _ar_in_string($string, $array){
        foreach ($array as $search)
            if(strstr($string, $search))
                return true;
        return false;
    }


    public function contains(array $substr=[])
    {
        if(!$substr) return true;

    }
}