<?php

use Illuminate\Database\Seeder;

class ModulesTableSeeder extends Seeder
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
        array('id' => 2, 'parent_id' => 0,  'description'=>'Form Management', 'icon' => 'fa fa-address-card-o', 'path' => 'queue_form_management', 'rank' => 2),
        array('id' => 3, 'parent_id' => 0,  'description'=>'Counter', 'icon' => 'fa fa-bell',  'path' => 'counter', 'rank' => 3),
        array('id' => 4, 'parent_id' => 0,  'description'=>'Account Management', 'icon' => 'fa fa-users',  'path' => 'account_management', 'rank' => 4)
      ));
    }
}
