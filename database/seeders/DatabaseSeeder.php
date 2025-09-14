<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Category;
use App\Models\Course;
use App\Models\Comment;
use App\Models\Lesson;
use App\Models\Payment;
use App\Models\CourseUser;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
       Role::factory(5)->create();
        User::factory(5)->create();
        Category::factory(10)->create();
        Course::factory(5)->create();
        Lesson::factory(20)->create();
        Comment::factory(10)->create();
        Payment::factory(20)->create();
        CourseUser::factory(20)->create();
    }
}
