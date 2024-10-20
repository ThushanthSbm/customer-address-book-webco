<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Http\Resources\CustomerResource;

class ProjectController extends Controller
{
    
    public function index(Request $request)
{
    // Get the selected pagination value, default to 10 if not set
    $perPage = $request->input('perPage', 10);

    // If 'all' is selected, fetch all records without pagination
    if ($perPage == 'all') {
        $projects = Project::with('customers')->get(); // Fetch all projects with related customers
        $customers = Customer::where('status', 1)->get(); // Fetch all active customers
    } else {
        // Fetch projects and customers with pagination
        $projects = Project::with('customers')->paginate($perPage); // Paginate based on selected value
        $customers = Customer::where('status', 1)->paginate($perPage); // Paginate customers as well
    }

    // Collect selected customer IDs from the projects
    $selectedCustomers = [];
    foreach ($projects as $project) {
        $selectedCustomers = array_merge($selectedCustomers, $project->customers->pluck('id')->toArray());
    }
    $selectedCustomers = array_unique($selectedCustomers); // Ensure unique customer IDs

    // Fetch active/inactive products for statistics
    $totalProducts = Project::count();
    $activeProductsCount = Project::where('status', 1)->count();
    $inactiveProductsCount = Project::where('status', -2)->count();

    // Calculate percentages
    $activePercentageIncrease = ($totalProducts > 0) ? ($activeProductsCount / $totalProducts) * 100 : 0;
    $inactivePercentageIncrease = ($totalProducts > 0) ? ($inactiveProductsCount / $totalProducts) * 100 : 0;

    return view('projects.index', compact(
        'projects',
        'customers',
        'selectedCustomers',
        'activeProductsCount',
        'activePercentageIncrease',
        'inactiveProductsCount',
        'inactivePercentageIncrease',
        'perPage' // Pass the perPage value to the view
    ));
}

public function indexApi(Request $request)
{
    // Fetch all projects with their customers
    $projects = Project::with('customers')->get();
    $customers = Customer::where('status', 1)->get();

    // Collect unique customer IDs from the projects
    $selectedCustomers = [];
    foreach ($projects as $project) {
        $selectedCustomers = array_merge($selectedCustomers, $project->customers->pluck('id')->toArray());
    }
    $selectedCustomers = array_unique($selectedCustomers);

    // Fetch active and inactive project counts
    $totalProjects = Project::count();
    $activeProjectsCount = Project::where('status', 1)->count();
    $inactiveProjectsCount = Project::where('status', -2)->count();

    // Calculate percentages
    $activePercentage = ($totalProjects > 0) ? ($activeProjectsCount / $totalProjects) * 100 : 0;
    $inactivePercentage = ($totalProjects > 0) ? ($inactiveProjectsCount / $totalProjects) * 100 : 0;

    return response()->json([
        'projects' => ProjectResource::collection($projects),
        'customers' => CustomerResource::collection($customers),
    ]);
}

    // Show the form for creating a new project
    public function create()
    {
        $customers = Customer::where('status', 1)->paginate(10); // Paginate active customers
        return view('projects.create', compact('customers'));
    }

    // Store a newly created project in storage
    public function store(StoreProjectRequest $request)
    {
        // Create the project with validated data
        $project = Project::create($request->validated());

        // Sync customers with the project
        $project->customers()->sync($request->customers);

        return redirect()->route('projects.index')->with('success', 'Project added successfully.');
    }

    public function storeApi(StoreProjectRequest $request)
    {
        // Create the project with validated data
        $project = Project::create($request->validated());

        // Sync customers with the project
        $project->customers()->sync($request->customers);

        return response()->json([
            'message' => 'Project added successfully.',
            'project' => $project->load('customers'),
        ], 201);
    }

    // Display the specified project
    public function show(Project $project)
    {
        return view('projects.show', compact('project'));
    }

    public function showApi(Project $project)
    {
        $project->load('customers'); // Load the project's customers

        return response()->json([
            'project' => $project,
        ]);
    }
    // Show the form for editing the specified project
//     public function edit(Project $project)
// {
//     $customers = Customer::where('status', 1)->get(); // Get all active customers
//     return response()->json(['project' => $project, 'customers' => $customers]);
// }

public function edit(Project $project)
{
    $customers = Customer::where('status', 1)->get(); // Get all active customers
    $project->load('customers'); // Load the project's customers
    return response()->json(['project' => $project, 'customers' => $customers]);
}

public function editApi(Project $project)
    {
        $customers = Customer::where('status', 1)->get(); // Get all active customers
        $project->load('customers'); // Load the project's customers

        return response()->json([
            'project' => $project,
            'customers' => $customers,
        ]);
    }

public function update(UpdateProjectRequest $request, Project $project)
{
    // Update the project with validated data
    $project->update($request->validated());

    // Sync customers with the project
    if ($request->has('customers')) {
        $project->customers()->sync($request->customers);
    } else {
        $project->customers()->sync([]);
    }

    // Redirect to the projects index page with a success message
    return redirect()->route('projects.index')->with('success', 'Project updated successfully');
}

// API method to update a project
public function updateApi(UpdateProjectRequest $request, Project $project)
{
    DB::beginTransaction();

    try {
        // Update the project with validated data
        $project->update($request->validated());

        // Sync customers with the project
        if ($request->has('customers')) {
            $project->customers()->sync($request->customers);
        } else {
            $project->customers()->sync([]);
        }

        DB::commit();

        return response()->json([
            'message' => 'Project updated successfully.',
            'project' => $project->load('customers'),
        ], 200);
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error updating project: ' . $e->getMessage());

        return response()->json([
            'message' => 'Failed to update project.',
            'error' => $e->getMessage(),
        ], 422);
    }
}

    // Remove the specified project from storage
    public function destroy(Project $project)
{
    $project->update(['status' => -2]);

    return redirect()->route('projects.index')->with('success', 'Project marked as inactive.');
}

// API method to delete a project
public function destroyApi(Project $project)
{
    $project->update(['status' => -2]);

    return response()->json([
        'message' => 'Project marked as inactive.',
    ], 200);
}

}
