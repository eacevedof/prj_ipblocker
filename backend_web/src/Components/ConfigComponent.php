<?php
namespace Ipblocker\Components;

class ConfigComponent
{
    private const FILES = [
        "contexts" => [
            "local" =>  IPB_PATH_CONFIG."/contexts.local.json",
            "test" =>  IPB_PATH_CONFIG."/contexts.test.json",
            "prod"  =>  IPB_PATH_CONFIG."/contexts.json",
        ],

        "rulez" => [
            "local" =>  IPB_PATH_CONFIG."/rulez.local.json",
            "test" =>  IPB_PATH_CONFIG."/rulez.test.json",
            "prod"  =>  IPB_PATH_CONFIG."/rulez.json",
        ],
    ];

    private static function get_pathjson($type="contexts")
    {
        $env = self::get_env();
        if(is_file(self::FILES[$type][$env])) return self::FILES[$type][$env];
        return self::FILES[$type]["prod"];
    }

    public static function get_env(){return IPB_ENV;}

    private static function _get_array_from_json($type="contexts")
    {
        $pathjson = self::get_pathjson($type);
        $content = file_get_contents($pathjson);
        $array = json_decode($content,true);
        return $array;
    }

    public static function get_schema($id, $database)
    {
        $array = self::_get_array_from_json();
        foreach ($array as $arcfg)
        {
            if($arcfg["id"] == $id) {
                $schemas = $arcfg["schemas"];
                foreach ($schemas as $arschm) {
                    if($arschm["database"] == $database) {
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

    public static function get_rulez()
    {
        return self::_get_array_from_json("rulez");
    }
}