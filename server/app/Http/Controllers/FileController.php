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
        return FileResource::collection(File::all());
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

        return response($content, 200, [
            'Content-Type' => $file->type,
            'Content-Length' => $file->size,
            'Content-Disposition' => "attachment; filename='{$file->name}'",
        ]);
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
