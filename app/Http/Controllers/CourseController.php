<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\JsonResponse;

class CourseController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Course::get());
    }

    public function bests(): JsonResponse
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

        return response()->json($courses);
    }
}
