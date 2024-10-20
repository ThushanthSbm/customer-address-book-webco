@extends('layouts.user_type.auth')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="#" class="btn btn-primary mb-3 custom-margin-right" data-toggle="modal" data-target="#projectModal" style="margin-left: 95%;">
            <i class="fas fa-plus-circle"></i>
        </a>
    </div>
<div class="row">
    <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
      <div class="card">
        <div class="card-body p-3">
          <div class="row">
            <div class="col-8">
              <div class="numbers">
                <p class="text-sm mb-0 text-capitalize font-weight-bold">Projects</p>
                <h5 class="font-weight-bolder mb-0">
                {{ $projects->total() }}
                  <span class="text-success text-sm font-weight-bolder">+{{ $projects->count() }}%</span>
                </h5>
              </div>
            </div>
            <div class="col-4 text-end">
              <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                <i class="ni ni-money-coins text-lg opacity-10" aria-hidden="true"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
      <div class="card">
        <div class="card-body p-3">
          <div class="row">
            <div class="col-8">
              <div class="numbers">
                <p class="text-sm mb-0 text-capitalize font-weight-bold">Active Projects</p>
                <h5 class="font-weight-bolder mb-0">
                {{ $activeProductsCount }}
                  <span class="text-success text-sm font-weight-bolder">+{{ number_format($activePercentageIncrease) }}%</span>
                </h5>
              </div>
            </div>
            <div class="col-4 text-end">
              <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                <i class="ni ni-world text-lg opacity-10" aria-hidden="true"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
      <div class="card">
        <div class="card-body p-3">
          <div class="row">
            <div class="col-8">
              <div class="numbers">
                <p class="text-sm mb-0 text-capitalize font-weight-bold">InActive Projects</p>
                <h5 class="font-weight-bolder mb-0">
                {{ $inactiveProductsCount }}
                  <span class="text-danger text-sm font-weight-bolder">-{{ number_format($inactivePercentageIncrease) }}%</span>
                </h5>
              </div>
            </div>
            <div class="col-4 text-end">
              <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                <i class="ni ni-paper-diploma text-lg opacity-10" aria-hidden="true"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div><br>

    <div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mt-3">
            <h3 class="mb-0">Project List</h3>
            <div class="d-flex">
                <input type="text" id="search" class="form-control" placeholder="Search projects...">&nbsp;&nbsp;
                <select id="sort" class="form-control">
                    <option value="oldestNo">Sort by Oldest No</option>
                    <option value="newestNo">Sort by Newest No</option>
                    <option value="name">Sort by Name</option>
                    <option value="description">Sort by Description</option>
                </select>

            </div>
        </div>
        <div class="mb-3">
        <label for="per_page" class="mr-2">Show:</label>
            <form id="paginationForm" method="GET" action="{{ route('projects.index') }}">
        <select id="pagination" name="perPage" class="form-control d-inline-block w-auto" onchange="document.getElementById('paginationForm').submit();">
            <option value="10" {{ request('perPage') == 10 ? 'selected' : '' }}>10</option>
            <option value="50" {{ request('perPage') == 50 ? 'selected' : '' }}>50</option>
            <option value="100" {{ request('perPage') == 100 ? 'selected' : '' }}>100</option>
            <option value="all" {{ request('perPage') == 'all' ? 'selected' : '' }}>All</option>
        </select>
    </form>

        </div>
            <table class="table table-striped table-hover mt-3">
                <thead class="thead-dark">
                    <tr>
                        <th>No</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Customers</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="projectTable">
                    @foreach($projects as $index => $project)
                        <tr>
                        <td class="mb-0 text-sm">{{ $index + 1 + (($projects->currentPage() - 1) * $projects->perPage()) }}</td>
                            <td class="mb-0 text-sm">{{ $project->name }}</td>
                            <td class="mb-0 text-sm">{{ $project->description }}</td>
                            <td class="mb-0 text-sm">
                                @if($project->customers->isEmpty())
                                    <span class="text-muted">No customers linked</span>
                                @else
                                    <ul class="list-unstyled mb-0">
                                        @foreach($project->customers as $customer)
                                            <li>{{ $customer->name }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                            </td>
                            <td class="mb-0 text-sm">
                                <span class="badge bg-gradient-{{ $project->status == 1 ? 'success' : 'danger' }}">
                                    {{ $project->status == 1 ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="text-center">
                            <a href="#" class="edit-project" 
                                data-id="{{ $project->id }}" 
                                data-name="{{ $project->name }}" 
                                data-description="{{ $project->description }}" 
                                data-customers="{{ implode(',', $project->customers->pluck('id')->toArray()) }}" 
                                data-toggle="modal" 
                                data-target="#editProjectModal">
                                <i class="fas fa-user-edit text-secondary"></i>
                                </a>

                                <form action="{{ route('projects.destroy', $project->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="delete-project" style="border: none; background: none;">
                                        <i class="cursor-pointer fas fa-trash text-secondary"></i>
                                    </button>
                                </form>


                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div>
                    {{ $projects->links() }}
                </div>
</div>


<!-- Project Modal -->
<div class="modal fade" id="projectModal" tabindex="-1" role="dialog" aria-labelledby="projectModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="projectModalLabel">Add Project</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="projectForm" action="{{ route('projects.store') }}" method="POST">
                    @csrf
                    <input type="hidden" id="projectId" name="id">
                    <div class="form-group">
                        <label for="projectName">Project Name</label>
                        <input type="text" class="form-control" id="projectName" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="projectDescription">Project Description</label>
                        <textarea class="form-control" id="projectDescription" name="description" rows="3" required></textarea>
                    </div>
                    <div class="form-group">
                    <label for="projectCustomers">Customers</label>
                        <div id="projectCustomers" style="border: 1px solid #ced4da; border-radius: .25rem; height: 150px; overflow-y: scroll; padding: 10px;">
                            @foreach($customers as $customer)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="customer{{ $customer->id }}" name="customers[]" value="{{ $customer->id }}">
                                    <label class="form-check-label" for="customer{{ $customer->id }}">
                                        {{ $customer->name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>

                    </div>
                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>
</div>

<!-- Edit Project Modal -->
<div class="modal fade" id="editProjectModal" tabindex="-1" role="dialog" aria-labelledby="editProjectModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProjectModalLabel">Edit Project</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editProjectForm" method="POST">
                @csrf
                @method('PUT') <!-- Since it's an update request -->
                    <div class="form-group">
                        <label for="projectName">Project Name</label>
                        <input type="text" class="form-control" id="projectName" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="projectDescription">Project Description</label>
                        <textarea class="form-control" id="projectDescription" name="description" rows="3" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="projectCustomers">Customers</label>
                        <div id="projectCustomers" style="border: 1px solid #ced4da; border-radius: .25rem; height: 150px; overflow-y: scroll; padding: 10px;">
                            @foreach($customers as $customer)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="customer{{ $customer->id }}" name="customers[]" value="{{ $customer->id }}"
                                        @if(in_array($customer->id, $selectedCustomers)) checked @endif>
                                    <label class="form-check-label" for="customer{{ $customer->id }}">
                                        {{ $customer->name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>


                    <input type="hidden" id="projectId" name="id">
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </form>
            </div>
        </div>
    </div>
</div>
 
<script>
    // Search and sort functionality
    document.getElementById('search').addEventListener('input', function () {
        const searchValue = this.value.toLowerCase();
        const rows = document.querySelectorAll('#projectTable tr');
        rows.forEach(row => {
            const name = row.querySelector('td:nth-child(1)').textContent.toLowerCase();
            const description = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
            if (name.includes(searchValue) || description.includes(searchValue)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

    document.getElementById('sort').addEventListener('change', function () {
    const sortValue = this.value;
    const rows = Array.from(document.querySelectorAll('#projectTable tr'));

    rows.sort((a, b) => {
        let aValue, bValue;

        // Sorting logic for "No" field (numeric sorting)
        if (sortValue === 'oldestNo' || sortValue === 'newestNo') {
            aValue = parseInt(a.querySelector('td:nth-child(1)').textContent);
            bValue = parseInt(b.querySelector('td:nth-child(1)').textContent);

            // Ascending order (Oldest No)
            if (sortValue === 'oldestNo') {
                return aValue - bValue;
            }
            // Descending order (Newest No)
            else if (sortValue === 'newestNo') {
                return bValue - aValue;
            }
        } 
        // Sorting logic for "Name" or other fields (text sorting)
        else {
            aValue = a.querySelector(`td:nth-child(${sortValue === 'name' ? 2 : 3})`).textContent.toLowerCase();
            bValue = b.querySelector(`td:nth-child(${sortValue === 'name' ? 2 : 3})`).textContent.toLowerCase();
            return aValue.localeCompare(bValue);
        }
    });

    const tbody = document.querySelector('#projectTable');
    tbody.innerHTML = '';
    rows.forEach(row => tbody.appendChild(row));
});

    // Pagination functionality
    document.getElementById('pagination').addEventListener('change', function () {
        const paginationValue = this.value;
        // Implement pagination logic here based on paginationValue
    });
</script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
   document.addEventListener('DOMContentLoaded', function () {
    const editButtons = document.querySelectorAll('.edit-project');
    
    editButtons.forEach(button => {
        button.addEventListener('click', function () {
            // Get the project data from the button's data attributes
            const projectId = this.getAttribute('data-id');
            const projectName = this.getAttribute('data-name');
            const projectDescription = this.getAttribute('data-description');
            const selectedCustomers = this.getAttribute('data-customers').split(',');

            // Get the modal and its form fields
            const modal = document.getElementById('editProjectModal');
            const form = modal.querySelector('#editProjectForm');
            const projectNameField = modal.querySelector('#projectName');
            const projectDescriptionField = modal.querySelector('#projectDescription');
            const projectCustomersField = modal.querySelectorAll('#projectCustomers input[type="checkbox"]');
            const projectIdField = modal.querySelector('#projectId');

            // Clear the previous customer selections
            projectCustomersField.forEach(customerCheckbox => {
                customerCheckbox.checked = false; // Uncheck all customer checkboxes
            });

            // Populate the modal fields with the correct data
            projectNameField.value = projectName;
            projectDescriptionField.value = projectDescription;
            projectIdField.value = projectId;

            // Mark selected customers as checked
            selectedCustomers.forEach(customerId => {
                const customerCheckbox = modal.querySelector(`#customer${customerId}`);
                if (customerCheckbox) {
                    customerCheckbox.checked = true;
                }
            });

            // Set the form action URL to include the correct project ID
            form.action = `/projects/${projectId}`; // Adjust the action URL for the form
        });
    });
    
    // Reset the modal form when the modal is hidden
    $('#editProjectModal').on('hidden.bs.modal', function () {
        const form = document.getElementById('editProjectForm');
        form.reset(); // Clear form fields when modal is closed
        form.action = ''; // Reset form action
    });
});

</script>


@endsection
