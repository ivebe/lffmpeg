<?php

namespace Ivebe\Lffmpeg\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Ivebe\Lffmpeg\Repositories\VideoRepository;

class VideoController extends Controller
{

    /**
     * @param Request $request
     * @param VideoRepository $repo
     * @return \Illuminate\Http\JsonResponse
     */
    public function progressAll(Request $request, VideoRepository $repo)
    {
        //list of IDs for which we want to get progress
        $videos = $request->get('videos');

        if(!is_array($videos))
            return response()->json([]);

        $answer = [];

        foreach($videos as $vid) {

            $answer[$vid]['encoding_progress'] = $repo->getProgress($vid);
            $answer[$vid]['video_status']      = $repo->status($vid);
        }

        return response()->json($answer);
    }

}