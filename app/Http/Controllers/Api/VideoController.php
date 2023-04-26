<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Videos;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class VideoController extends Controller
{
    public function index(): JsonResponse
    {

        $videos = Videos::with('user')->paginate(30);

        if (count($videos) == 0) {
            return response()->json([
                'message' => 'Videos not found'
            ], 404);
        }

        return response()->json([
            'videos' => $videos,
            'message' => 'Videos retrieved successfully'
        ], 200);
    }

    public function store(Request $request)
    {
        //valida los datos que llegan
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'hashtag' => 'required',
        ]);

        $video = $request->file('video')->storeAs('public/videos', time(). '_' . Auth::user()->id . '_' . $request->file('video')->getClientOriginalName());
        //obtiene el video y lo renombra para despues guardarlos
        //$video = Storage::disk('public')->put('videos', $video);
        // quita la palabra public del path
        $video = str_replace('public/', '', $video);


        $create= Videos::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'hashtag' => $request->hashtag,
            'video' => $video,
        ]);

        if (!$create) {
            return response()->json([
                'message' => 'Video not created'
            ], 500);
        }

        return response()->json([
            'video' => $create,
            'message' => 'Video created successfully'
        ], 201);
    }


    public function show(Videos $videos)
    {
        //
    }

    public function update(Request $request, Videos $videos)
    {
        //
    }

    public function destroy(Videos $videos)
    {
        //
    }
}
