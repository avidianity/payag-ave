<?php

namespace App\Http\Controllers;

use App\Http\Resources\FileResource;
use App\Models\File;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return FileResource::collection(File::with('fileable')->get());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\File  $file
     * @return \Illuminate\Http\Response
     */
    public function show(File $file)
    {
        $content = Storage::get($file->url);

        $headers = [
            'Content-Type' => $file->type,
            'Content-Length' => $file->size,
        ];

        if ($file->fileable !== null) {
            $headers['Fileable-ID'] = $file->fileable_id;
            $headers['Fileable-Type'] = $file->fileable_type;
        }

        return response($content, 200, $headers);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\File  $file
     * @return \Illuminate\Http\Response
     */
    public function destroy(File $file)
    {
        $file->delete();

        return response('', 204);
    }
}
