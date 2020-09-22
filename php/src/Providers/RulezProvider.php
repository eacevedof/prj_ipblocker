<?php
namespace Ipblocker\Providers;

use Ipblocker\Components\ConfigComponent as cfg;
use Ipblocker\Helpers\RequestHelper as req;

class RulezProvider
{
    private $data;
    /**
     * @var RequestHelper
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
        if(array_key_exists("post", $config) && $config["post"]===null)
        {
            if($this->req->get_post())
                return false;
        }
        return true;
    }

    private function _is_get_nullok($requri)
    {
        $config = $this->_get_uriconfig($requri);

        if(array_key_exists("get", $config) && $config["get"]===null)
        {
            if($this->req->get_get())
                return false;
        }
        return true;
    }

    private function _get_pub_post($requri)
    {
        $uriconf = $this->_get_uriconfig($requri);
//pp($uriconf,"uriconf post $requri");
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

    private function _is_post_nok($requri)
    {
        if(!$this->req->get_post()) return false;
        $pubpost = $this->_get_pub_post($requri);
        //pp($_POST,"POST");
        //pp($pubpost,"_is_postnok");
        if(!$pubpost) return false;
        foreach ($pubpost as $f) {
            //pp($f,"f");
            //pp($this->req->is_key($f,"post"),"is f in post");
            $inpost = $this->req->is_key($f,"post");
            //pp($inpost,"IN POST");
            if(!$inpost) return $f;

            $valpost = $this->req->get_key($f,"post");
            if(!$valpost) return $f;
        }

        return false;
    }

    private function _is_get_nok($requri)
    {
        if(!$this->req->get_get()) return false;
        $pubget = $this->_get_pub_get($requri);
        if(!$pubget) return false;
        foreach ($pubget as $f) {
            if(!$this->req->is_key($f,"get"))
                return $f;
            if(!$this->req->get_key($f,"get"))
                return $f;
        }
        return false;
    }

    private function _is_files_nok($requri)
    {
        if(!$this->req->get_files()) return false;
        $pubfiles = $this->_get_pub_files($requri);
        if(!$pubfiles) return false;
        foreach ($pubfiles as $f) {
            if(!$this->req->is_key($f,"files"))
                return $f;
            if(!$this->req->get_key($f,"files"))
                return $f;
        }
        return false;
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

    private function _is_req_fields_nok($requri)
    {
        //comprobar campos obligatorios
        $isnok = $this->_is_get_nok($requri);
        if($isnok) return $isnok;
        $isnok = $this->_is_post_nok($requri);
        //pp($isnok,"isnok");die;
        if($isnok) return $isnok;
        $isnok = $this->_is_files_nok($requri);
        if($isnok) return $isnok;
        return false;
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
        return false;
    }

    private function _is_country_nok()
    {
        $country = $this->req->get_whois("country");
        $country = strtoupper($country);
        $countries = $this->data["domains"]["*"]["countries"]["forbidden"] ?? [];
        $countries = array_flip($countries);
        $countries = array_change_key_case($countries, CASE_UPPER);
        $countries = array_flip($countries);
        //pp($countries,"COUNTRIES of $country");die;
        return in_array($country, $countries);
    }

    public function is_forbidden()
    {
        //comprobar bloqueo por pais
        if($this->_is_country_nok())  return "country:".$this->req->get_whois("country");

        //comprobar si requri es aplicable
        $requri = $this->_in_requris();
        if(!$requri)  return false;

        //comprobar obligatoriedad de nulos
        $isok = $this->_are_nulls_ok($requri);
        if(!$isok) return "not null in get or post";

        //comprobar campos obligatorios en post, get y files
        $nok = $this->_is_req_fields_nok($requri);
        if($nok) return "reqfields {$nok}";

        //comprobar reglas en contenido en post, get y files
        $nok = $this->_is_rules_nok();
        if($nok) return "rules: {$nok}";

        return false;
    }

}