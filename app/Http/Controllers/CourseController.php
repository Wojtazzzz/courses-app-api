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
        return response()->json(Course::limit(3)->get());
    }
}
