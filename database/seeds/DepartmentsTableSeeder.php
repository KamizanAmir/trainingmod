<?php

use Illuminate\Database\Seeder;

class DepartmentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $departments = ['FOL', 'EOL', 'IT', 'QA', 'FINANCE', 'PLANNER'];
    
        foreach ($departments as $deptName) {
            \App\Department::create([
                'dept_name' => $deptName,
                'created_at' => now(), // Laravel will automatically set created_at and updated_at
            ]);
        }
    }
}
