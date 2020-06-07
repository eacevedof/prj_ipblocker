<?php
namespace Theframework\Providers;

use TheFramework\Components\ComponentConfig as cfg;
use TheFramework\Helpers\HelperRequest as req;

class ProviderKeywords
{
    private $data;
    /**
     * @var HelperRequest
     */
    private $req;

    public function __construct()
    {
        $this->data = cfg::get_keywords();
        $this->req = req::getInstance();
    }

    private function _get_uris_by_domain($domain)
    {
        $domains = array_keys($this->data["domains"]);
//pp($domains,"domains");die;
        if(!in_array($domain, $domains)) return [];
        $urispublic = $this->data["domains"][$domain]["public"] ?? [];
 //pp($urispublic,"urispublic");die;
        return $urispublic;
    }

    private function _get_requris_by_domain()
    {
        $domain = $this->req->get_domain();
//pp($domain,"domain");die;
        if($domain==="*") return [];
        $urispublic = $this->_get_uris_by_domain($domain);
//pp($urispublic,"urispublic");die;
        if(!$urispublic) return [];
        $requris =  array_column($urispublic,"requri");
        rsort($requris);
        return  $requris;
    }

    private function _get_exact_uri($requri, $urisbydom)
    {
        foreach ($urisbydom as $puburi)
            if($requri===$puburi)
                return $puburi;
        return "";
    }

    private function _in_requris()
    {
        $requri = $this->req->get_requri();
//pp($requri,"requri");
        $urisbydom = $this->_get_requris_by_domain();
//pp($urisbydom,"urisbydom");
        $exact = $this->_get_exact_uri($requri, $urisbydom);
//pp($exact,"exact");die;
        return $exact;
    }

    private function _get_uriconfig($requri)
    {
        $domain = $this->req->get_domain();
        $urisbydom = $this->_get_uris_by_domain($domain);
        //pp($urisbydom,"urisbydom uris of $requri");die;
        foreach ($urisbydom as $arrequri)
        {
            if($arrequri["requri"]==$requri)
                return $arrequri;
        }
        return [];
    }

    private function _is_post_nullok($requri)
    {
        $config = $this->_get_uriconfig($requri);
        if(isset($config["post"]) && $config["post"]===null)
        {
            if($this->req->get_post())
                return false;
        }
        return true;
    }

    private function _is_get_nullok($requri)
    {
        $config = $this->_get_uriconfig($requri);
        if(isset($config["get"]) && $config["get"]===null)
        {
            if($this->req->get_get())
                return false;
        }
        return true;
    }

    private function _get_pub_post($requri)
    {
        $uriconf = $this->_get_uriconfig($requri);
        pp($uriconf,"uriconf post $requri");
        return $uriconf["post"] ?? [];
    }

    private function _get_pub_get($requri)
    {
        $uriconf = $this->_get_uriconfig($requri);
        return $uriconf["get"] ?? [];
    }

    private function _get_pub_files($requri)
    {
        $uriconf = $this->_get_uriconfig($requri);
        return $uriconf["files"] ?? [];
    }

    private function _is_post_ok($requri)
    {
        $pubpost = $this->_get_pub_post($requri);
//pp($pubpost,"pubpost");die;
        if(!$pubpost) return true;
        foreach ($pubpost as  $f) {
            //pp($this->req->get_post(),"POST");
            //pp($this->req->is_key($f,"post"),"$f in post");die;
            if(!$this->req->is_key($f,"post"))
                return false;
            if(!$this->req->get_key($f,"post"))
                return false;
        }
        return true;
    }

    private function _is_get_ok($requri)
    {
        $pubget = $this->_get_pub_get($requri);
        if(!$pubget) return true;
        foreach ($pubget as $f => $v) {
            if(!$this->req->is_key($f,"get"))
                return false;
            if(!$this->req->get_key($f,"get"))
                return false;
        }
        return true;
    }

    private function _is_files_ok($requri)
    {
        $pubfiles = $this->_get_pub_files($requri);
        if(!$pubfiles) return true;
        foreach ($pubfiles as $f => $v) {
            if(!$this->req->is_key($f,"files"))
                return false;
            if(!$this->req->get_key($f,"files"))
                return false;
        }
        return true;
    }

    private function _is_and($array,$string)
    {
        foreach ($array as $str)
            if(!strstr($string,$str))
                return false;
        return true;
    }

    private function _is_and_string($arsearch,$string)
    {
//pp($arsearch,"arsearch");die;
        foreach ($arsearch as $arwords)
            if($this->_is_and($arwords,$string))
                return implode("|",$arwords);
        return false;
    }

    private function _is_or_string($arsearch,$string)
    {
        foreach ($arsearch as $search)
            if(strstr($string,$search))
                return $search;
        return false;
    }

    private function _get_rules($andor,$method)
    {
        $rules = $this->data["domains"]["*"]["rules"] ?? [];
        return $rules[$andor][$method] ?? [];
    }

    private function _get_rules_scan($method="post")
    {
        $toscan = [];
        if($method=="post") $toscan = $this->req->get_post();
        elseif($method=="get") $toscan = $this->req->get_get();
        elseif($method=="files")
            $toscan = $this->req->get_files();

        foreach ($toscan as $k => $fieldval)
        {
            if(!is_string($fieldval)) continue;

            $rules = $this->_get_rules("or",$method);
            $mxfound = $this->_is_or_string($rules,$fieldval);
            if($mxfound) return $mxfound;

            $rules = $this->_get_rules("and",$method);
            $mxfound = $this->_is_and_string($rules,$fieldval);
            if($mxfound) return $mxfound;
        }
        //no ha cumplido ninguna regla
        return "";
    }

    private function _are_nulls_ok($requri)
    {
        $isok = $this->_is_get_nullok($requri);
        if(!$isok) return false;
        $isok = $this->_is_post_nullok($requri);
        if(!$isok) return false;
        return true;
    }

    private function _is_req_fields_ok($requri)
    {
        //comprobar campos obligatorios
        $isok = $this->_is_get_ok($requri);
        if(!$isok) return false;
        $isok = $this->_is_post_ok($requri);
//pp($isok,"is_postok");die;
        if(!$isok) return false;
        $isok = $this->_is_files_ok($requri);
        if(!$isok) return false;
        return true;
    }

    private function _is_rules_nok()
    {
        //comprobar reglas en los valores de los campos
        $mxnok = $this->_get_rules_scan("post");
        if($mxnok) return $mxnok;

        $mxnok = $this->_get_rules_scan("get");
        if($mxnok) return $mxnok;

        $mxnok = $this->_get_rules_scan("files");
        if($mxnok) return $mxnok;
    }

    private function _is_country_nok()
    {
        $country = $this->req->get_whois("country");
        $countries = $this->data["domains"]["*"]["countries"]["forbidden"] ?? [];
        return in_array($country, $countries);
    }

    public function is_forbidden()
    {

        //comprobar bloqueo por pais
        if($this->_is_country_nok())  return "country:".$this->req->get_whois("country");

        //comprobar si requri es palicable
        $requri = $this->_in_requris();
        if(!$requri)  return false;

        //comprobar obligatoriedad de nulos
        $isok = $this->_are_nulls_ok($requri);
        if(!$isok) return "not null in get or post";

        //comprobar campos obligatorios en post, get y files
        $mxresult = $this->_is_req_fields_ok($requri);
        if(!$mxresult) return "reqfields";
pp("obligatorios");die;

        //comprobar reglas en contenido en post, get y files
        $mxresult = $this->_is_rules_nok();
        if(!$mxresult) return "rules:".$mxresult;

        return false;
    }

}