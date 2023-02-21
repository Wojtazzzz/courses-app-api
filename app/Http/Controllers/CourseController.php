<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\Course\BestResource;
use App\Models\Course;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CourseController extends Controller
{
    public function bests(): ResourceCollection
    {
        $courses = Course::withAvg('ratings', 'value')
            ->orderBy('ratings_avg_value', 'desc')
            ->limit(3)
            ->get([
                'id',
                'name',
                'price',
                'thumbnail',
                'sales',
            ]);

        return BestResource::collection($courses);
    }
}
