<?php

namespace App\Traits;

use App\Models\File;

trait HasFile
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function file()
    {
        return $this->morphOne(File::class, 'fileable');
    }
}
