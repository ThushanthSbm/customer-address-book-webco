<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Address;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CustomerController extends Controller
{

    public function index(Request $request)
{
    // Get the number of items to display per page
    $perPage = $request->input('per_page', 10); // Default to 10
    $perPageOptions = [10, 50, 100, 'All'];

    // Fetch customers with addresses, apply pagination
    $customersQuery = Customer::with('addresses');
    
    // If 'All' is selected, don't paginate
    if ($perPage === 'All') {
        $customers = $customersQuery->get();
    } else {
        $customers = $customersQuery->paginate($perPage);
    }

    // Fetch active and inactive customers
    $totalCustomers = Customer::count();
    $activeCustomers = Customer::where('status', 1)->count();
    $inactiveCustomers = Customer::where('status', -2)->count();

    // Calculate percentages
    $activePercentage = ($totalCustomers > 0) ? ($activeCustomers / $totalCustomers) * 100 : 0;
    $inactivePercentage = ($totalCustomers > 0) ? ($inactiveCustomers / $totalCustomers) * 100 : 0;

    return view('customers.index', compact('customers', 'perPageOptions', 'perPage', 'activeCustomers', 'inactiveCustomers', 'activePercentage', 'inactivePercentage'));
}

public function indexApi(Request $request)
{
    // Fetch all customers with their addresses
    $customers = Customer::with('addresses')->get();

    // Fetch active and inactive customers
    $totalCustomers = Customer::count();
    $activeCustomers = Customer::where('status', 1)->count();
    $inactiveCustomers = Customer::where('status', -2)->count();

    // Calculate percentages
    $activePercentage = ($totalCustomers > 0) ? ($activeCustomers / $totalCustomers) * 100 : 0;
    $inactivePercentage = ($totalCustomers > 0) ? ($inactiveCustomers / $totalCustomers) * 100 : 0;

    return response()->json([
        'customers' => $customers,
    ]);
}


    // Show the form for creating a new customer
    public function create()
    {
        return view('customers.create');
    }

    // Store a newly created customer in storage
    public function store(StoreCustomerRequest $request)
    {
        $customer = Customer::create($request->validated());

        // Add addresses to the customer
        $customer->addresses()->createMany($request->addresses);

        return redirect()->route('customers.index')->with('success', 'Customer added successfully.');
    }

    public function storeApi(StoreCustomerRequest $request)
{
    // Create the customer with validated data
    $customer = Customer::create($request->validated());

    // Add addresses to the customer
    $customer->addresses()->createMany($request->addresses);

    // Return the customer data as JSON
    return response()->json([
        'success' => true,
        'message' => 'Customer added successfully.',
        'customer' => $customer->load('addresses'),
    ]);
}

    // Display the specified customer
    public function show(Customer $customer)
    {
        return view('customers.show', compact('customer'));
    }

    // Show the form for editing the specified customer
    public function edit(Customer $customer)
{
    // Load addresses with the customer
    $customer->load('addresses');
    
    return view('customers.edit', compact('customer'));
}

public function editApi(Customer $customer)
    {
        // Load addresses with the customer
        $customer->load('addresses');

        return response()->json([
            'customer' => $customer,
        ]);
    }

public function update(UpdateCustomerRequest $request, Customer $customer)
{
    DB::beginTransaction();

    try {
        $customer->update($request->validated());

        if ($request->has('addresses')) {
            $addresses = $request->input('addresses');

            foreach ($addresses as $addressData) {
                $address = $customer->addresses()->updateOrCreate(
                    ['id' => $addressData['id'] ?? null], // Match by ID if provided
                    $addressData
                );
            }
        }

        DB::commit();

        return response()->json([
            'message' => 'Customer updated successfully.',
            'customer' => $customer,
        ], 200);
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error updating customer: ' . $e->getMessage());

        return response()->json([
            'message' => 'Failed to update customer.',
            'error' => $e->getMessage(),
        ], 422);
    }
}
// API method to update a customer
    public function updateApi(UpdateCustomerRequest $request, Customer $customer)
    {
        DB::beginTransaction();

        try {
            $customer->update($request->validated());

            if ($request->has('addresses')) {
                $addresses = $request->input('addresses');

                foreach ($addresses as $addressData) {
                    $address = $customer->addresses()->updateOrCreate(
                        ['id' => $addressData['id'] ?? null], // Match by ID if provided
                        $addressData
                    );
                }
            }

            DB::commit();

            return response()->json([
                'message' => 'Customer updated successfully.',
                'customer' => $customer->load('addresses'),
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating customer: ' . $e->getMessage());

            return response()->json([
                'message' => 'Failed to update customer.',
                'error' => $e->getMessage(),
            ], 422);
        }
    }

    // Remove the specified customer from storage
    public function destroy(Customer $customer)
    {
        // Instead of hard-deleting, mark the customer as inactive (status = -2)
        $customer->update(['status' => -2]);

        return redirect()->route('customers.index')->with('success', 'Customer marked as inactive.');
    }

    // API method to delete a customer
    public function destroyApi(Customer $customer)
    {
        // Instead of hard-deleting, mark the customer as inactive (status = -2)
        $customer->update(['status' => -2]);

        return response()->json([
            'message' => 'Customer marked as inactive.',
        ], 200);
    }

}
