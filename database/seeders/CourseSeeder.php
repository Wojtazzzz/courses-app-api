<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Rating;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Course::factory(5)
            ->create()
            ->each(function (Course $course) {
                Rating::factory(3)->create([
                    'course_id' => $course->id
                ]);
            });
    }
}
