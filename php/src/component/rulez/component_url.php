<?php
namespace Ipblocker\Rulez;
use Ipblocker\Rulez;

use Ipblocker\Component\ComponentString;
use Ipblocker\Component\ComponentArray;

class ComponentUrl implements IRulez
{

    private string $requrl;
    private array $arrulez;

    public function __construct(string $requrl, array $arrulez)
    {
        $this->requrl = new ComponentString($requrl);
        $this->arrulez = new ComponentArray($arrulez);
    }

    public function get()
    {

        // TODO: Implement validate() method.
    }
}