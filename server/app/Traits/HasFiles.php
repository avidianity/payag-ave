<?php

namespace App\Traits;

use App\Models\File;

trait HasFiles
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }
}
