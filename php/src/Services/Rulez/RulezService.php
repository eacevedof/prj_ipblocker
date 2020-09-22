<?php
namespace Ipblocker\Rulez;

use Ipblocker\IRulez;

class ComponentRulez
{

    private string $requrl;
    private array $get;
    private array $post;

    public function __construct(string $requrl, array $get=[], array $post=[] )
    {
        $this->requrl = $requrl;
        $this->get = $get;
        $this->post = $post;
    }

    private static function _get_stragety():IRulez
    {

    }

    public function get()
    {

    }
}