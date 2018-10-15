<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         DB:: table('modules') -> insert(array(
          array('id' => 1, 'parent_id' => 0,  'description'=>'Dashboard', 'icon' => 'fa fa-tachometer', 'path' => 'dashboard', 'rank' => 1),
          array('id' => 2, 'parent_id' => 0,  'description'=>'Semester', 'icon' => 'fa fa-sitemap', 'path' => 'semester', 'rank' => 2),
          array('id' => 3, 'parent_id' => 0,  'description'=>'Courses', 'icon' => 'fa fa-clipboard',  'path' => 'courses/default', 'rank' => 3),
          array('id' => 4, 'parent_id' => 0,  'description'=>'Quizzes', 'icon' => 'fa fa-file-text-o',  'path' => 'quizzes/default', 'rank' => 4),
          array('id' => 5, 'parent_id' => 0,  'description'=>'Exams', 'icon' => 'fa fa-file-text-o',  'path' => 'exams/default', 'rank' => 5),
          array('id' => 6, 'parent_id' => 0,  'description'=>'Resources', 'icon' => 'fa fa-file-text-o',  'path' => 'resources/default', 'rank' => 6),
          array('id' => 10, 'parent_id' => 0,  'description'=>'My Account', 'icon' => 'fa fa-cog',  'path' => 'account_settings', 'rank' => 10)
        ));
    }
}
