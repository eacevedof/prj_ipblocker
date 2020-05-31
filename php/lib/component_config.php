<?php
namespace TheFramework\Components;

class ComponentConfig
{
    private CONST pathcfg = "../../config/dbconfig.json";

    public static function get_schema($id,$database)
    {
        $content = file_get_contents(self::pathcfg);
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
                            "database" => $database,
                            "user"=>$arcfg["user"],
                            "password"=>$arcfg["password"],
                        ];
                    }
                }
            }
        }
        return [];
    }
}