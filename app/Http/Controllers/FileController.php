<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use File;
use Response;

class FileController extends Controller{
    function coverBuku($filename){
        Auth::user();
        if (strpos($filename, '.') !== false && Auth::check()) {
            $path = storage_path('app/cover_buku/' . $filename);
            try {
                $response = Response::make(File::get($path), 200);
                return $response->header("Content-Type", File::mimeType($path));
            } catch (FileNotFoundException $exception) {
                abort(404);
            }
        } else {
            return redirect('/');
        }
    }

    function ebook($filename){
        $path = storage_path('app/file_ebook/' . $filename);
        try {
            $response = Response::make(File::get($path), 200);
            return $response->header("Content-Type", 'application/pdf');
        } catch (FileNotFoundException $exception) {
            abort(404);
        }
    }
}
