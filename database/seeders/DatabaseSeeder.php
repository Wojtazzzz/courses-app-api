<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Course;
use App\Models\Feedback;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Course::truncate();
        Feedback::truncate();

        $this->call([
            CourseSeeder::class,
            FeedbackSeeder::class,
        ]);
    }
}
