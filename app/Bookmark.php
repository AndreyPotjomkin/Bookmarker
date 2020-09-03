<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bookmark extends Model
{
    public static function create($fields)
    {
        $bookmark = new Bookmark();
        $bookmark->favicon = $fields['favicon'];
        $bookmark->url = $fields['url'];
        $bookmark->title = $fields['title'];
        $bookmark->desc = $fields['desc'];
        $bookmark->keywords = $fields['keywords'];
        $bookmark->password = $fields['password'];
        $bookmark->save();

        return $bookmark;
    }
}
