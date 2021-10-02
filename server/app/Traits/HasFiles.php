<?php

namespace App\Traits;

use App\Models\File;

trait HasFiles
{
    public $hasFilesName = 'files';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }

    public static function bootHasFiles()
    {
        static::deleting(function (HasFiles $model) {
            $model->{$model->hasFilesName}->delete();
        });
    }
}
