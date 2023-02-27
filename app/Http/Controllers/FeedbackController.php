<?php

namespace App\Http\Controllers;

use App\Http\Resources\Feedback\IndexResource;
use App\Models\Feedback;
use Illuminate\Http\Resources\Json\JsonResource;

class FeedbackController extends Controller
{
    public function index(): JsonResource
    {
        $feedback = Feedback::inRandomOrder()
            ->limit(10)
            ->get([
                'id',
                'content',
                'user_id',
            ]);

        return IndexResource::collection($feedback);
    }
}
