<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Project;

class ProjectSeeder extends Seeder
{
    public function run()
    {
        $projects = [
            ['name' => 'Project Alpha', 'description' => 'First project description', 'status' => 1],
            ['name' => 'Project Beta', 'description' => 'Second project description', 'status' => 1],
            ['name' => 'Project Gamma', 'description' => 'Third project description', 'status' => 1],
            ['name' => 'Project Delta', 'description' => 'Fourth project description', 'status' => 1],
            ['name' => 'Project Epsilon', 'description' => 'Fifth project description', 'status' => 1],
            ['name' => 'Project Zeta', 'description' => 'Sixth project description', 'status' => 1],
            ['name' => 'Project Eta', 'description' => 'Seventh project description', 'status' => 1],
            ['name' => 'Project Theta', 'description' => 'Eighth project description', 'status' => 1],
            ['name' => 'Project Iota', 'description' => 'Ninth project description', 'status' => 1],
            ['name' => 'Project Kappa', 'description' => 'Tenth project description', 'status' => 1],
            ['name' => 'Project Lambda', 'description' => 'Eleventh project description', 'status' => 1],
            ['name' => 'Project Mu', 'description' => 'Twelfth project description', 'status' => 1],
            ['name' => 'Project Nu', 'description' => 'Thirteenth project description', 'status' => 1],
        ];

        foreach ($projects as $project) {
            Project::create($project);
        }
    }
}
