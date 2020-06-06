<?php
namespace TheFramework\Components;

class ComponentConfig
{
    private static  function get_pathjson($type="contexts")
    {
        $files["contexts"]["local"] = IPB_PATH_CONFIG."/contexts.local.json";
        $files["contexts"]["prod"] = IPB_PATH_CONFIG."/contexts.json";
        $files["keywords"]["local"] = IPB_PATH_CONFIG."/keywords.local.json";
        $files["keywords"]["prod"] = IPB_PATH_CONFIG."/keywords.json";
        if(is_file($files[$type]["local"]))
            return $files[$type]["local"];
        return $files[$type]["prod"];
    }

    public static function get_env($type="contexts")
    {
        $pathjson = self::get_pathjson($type);
        if(strstr($pathjson,"contexts.local.json"))
            return "dev";
        return "prod";
    }

    private  static function _get_array_from_json($type="contexts")
    {
        $pathjson = self::get_pathjson($type);
        $content = file_get_contents($pathjson);
        $array = json_decode($content,true);
        return $array;
    }

    public static function get_schema($id,$database)
    {
        $array = self::_get_array_from_json();
        foreach ($array as $arcfg)
        {
            if($arcfg["id"] == $id) {
                $schemas = $arcfg["schemas"];
                foreach ($schemas as $arschm)
                {
                    if($arschm["database"] == $database)
                    {
                        return [
                            "server" => $arcfg["server"],
                            "port" => $arcfg["port"] ?? "3306",
                            "database" => $database,
                            "user"=>$arschm["user"],
                            "password"=>$arschm["password"],
                        ];
                    }
                }
            }
        }
        return [];
    }

    public static function get_keywords($andor="or", $method="post")
    {
        $array = self::_get_array_from_json("keywords");
        $array = $array[$andor][$method] ?? [];
        return $array;
    }
}