<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\File;
use Illuminate\Support\Facades\Storage;

class FileUploadController extends Controller
{
    public function upload(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|file|mimes:pdf,doc,docx,ppt,pptx',
        ]);

        $file = $request->file('file');
        $path = $file->store('public/files');
        $fileModel = new File;
        $fileModel->name = $file->getClientOriginalName();
        $fileModel->path = $path;
        $fileModel->mime = $file->getClientMimeType();
        $fileModel->size = $file->getClientSize();
        $fileModel->save();

        return response()->json(['message' => 'File uploaded successfully', 'file' => $fileModel]);
    }
    public function listFiles()
    {
        $files = File::all(); // Fetch all files
        return response()->json($files);
    }

    // Optionally, add a method to get a single file by id
    public function getFile($id)
    {
        $file = File::findOrFail($id); // Fetch file by id
        return response()->json($file);
    }
}