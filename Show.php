<?php

namespace Tvmaze;

class Show
{
    public $id;
    public $name;
    public $type;
    public $genres = [];
    public $runtime;
    public $image = [];
    public $summary;

    public static function apiConnect($response)
    {
        $data_object = new static();

        $data_object->id           = $response["id"];
        $data_object->name         = $response["name"];
        $data_object->type         = $response["type"];
        $data_object->genres       = implode(',',$response["genres"]);
        $data_object->runtime      = $response["runtime"];
        $data_object->image        = $response["image"]["medium"];
        $data_object->summary      = $response["summary"];

        return $data_object;
    }
}
?>