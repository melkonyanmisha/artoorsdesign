<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class UploadFileController extends Controller
{

    public function __construct()
    {
        $this->middleware(['maintenance_mode']);
    }

    public function start(Request $request)
    {
        $file = $request->file('file');
        $fileName = $file->getClientOriginalName();
        $filePath = $file->storeAs('temp', $fileName, 'local');

        return response()->json(['file_path' => $filePath]);
    }

    public function uploadChunk(Request $request)
    {
        $filePath = $request->input('file_path');
        $chunkIndex = $request->input('chunk_index');
        $chunk = $request->file('chunk');
        $folderName = $request->input('folder_name');

        $chunk->storeAs('temp/' . $folderName, $chunkIndex, 'local');

        return response()->json(['message' => 'Chunk uploaded successfully']);
    }

    public function complete(Request $request)
    {
        $filePath = $request->input('file_path');
        $folderName = $request->input('folder_name');
        $chunks = Storage::disk('local')->files('temp/' . $folderName);

        usort($chunks, function ($a, $b) {
            $aIndex = (int) pathinfo($a, PATHINFO_FILENAME);
            $bIndex = (int) pathinfo($b, PATHINFO_FILENAME);
            return $aIndex - $bIndex;
        });

        $originalFileName = basename($filePath);
        $fileExtension = pathinfo($originalFileName, PATHINFO_EXTENSION);

        $finalFilePath = 'product/' . $originalFileName;
        $counter = 1;
        while (File::exists(public_path($finalFilePath))) {
            $uniqueFileName = Str::beforeLast($originalFileName,
                                              '.' . $fileExtension) . '_' . $counter . '.' . $fileExtension;
            $finalFilePath = 'product/' . $uniqueFileName;
            $counter++;
        }


        $outputFile = fopen(public_path($finalFilePath), 'wb');

        foreach ($chunks as $chunkPath) {
            $chunkContent = Storage::disk('local')->get($chunkPath);
            fwrite($outputFile, $chunkContent);
            Storage::disk('local')->delete($chunkPath);
        }

        fclose($outputFile);

        Storage::deleteDirectory('temp/' . $folderName);

        return response()->json(['file_path' => "public/$finalFilePath"]);
    }

    public function upload_image(Request $request){

    	$request->validate([
            'files.*' => [
                'required',
                'image',
                'mimes:jpeg,jpg,bmp,png,svg,gif'
            ],
        ], [], [
            'files.*' => 'File'
        ]);
        if (!file_exists(asset_path('uploads/editor-image'))) {
            mkdir(asset_path('uploads/editor-image'), 0777, true);
        }
    	$files = $request->files;
    	$image_url = [];
        foreach ($files as $file) {
        	foreach($file as $k => $f){

	            $fileName = $f->getClientOriginalName() . time() . "." . $f->getClientOriginalExtension();
	            $f->move(asset_path('uploads/editor-image/'), $fileName);
	            $image_url[$k] = asset(asset_path('uploads/editor-image/') . $fileName);

        	}
        }

        return response()->json($image_url);
    }
}
