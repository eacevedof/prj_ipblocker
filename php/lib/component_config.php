<?php
namespace TheFramework\Components;

class ComponentConfig
{

    private static  function get_pathjson()
    {
        $files["local"] = "../../config/contexts.local.json";
        $files["prod"] = "../../config/contexts.json";
        if(is_file($files["local"]))
            return $files["local"];
        return $files["prod"];
    }

    public static function get_schema($id,$database)
    {
        $pathjson = self::get_pathjson();
        $content = file_get_contents($pathjson);
        $array = json_decode($content,true);
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
}