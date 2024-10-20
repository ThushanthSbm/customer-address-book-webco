@extends('layouts.user_type.auth')

@section('content')
<div class="container">
    <a href="#" class="btn btn-primary mb-3 custom-margin-right" data-toggle="modal" data-target="#addCustomerModal" style="margin-left: 95%;">
        <i class="fas fa-plus-circle"></i>
    </a>
    <div class="row">
    <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
      <div class="card">
        <div class="card-body p-3">
          <div class="row">
            <div class="col-8">
              <div class="numbers">
                <p class="text-sm mb-0 text-capitalize font-weight-bold">Customers</p>
                <h5 class="font-weight-bolder mb-0">
                {{ $customers->total() }}
                  <span class="text-success text-sm font-weight-bolder">+{{ $customers->count() }}%</span>
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
                <p class="text-sm mb-0 text-capitalize font-weight-bold">Active Customers</p>
                <h5 class="font-weight-bolder mb-0">
                {{ $activeCustomers }}
                  <span class="text-success text-sm font-weight-bolder">+{{ number_format($activePercentage, 2) }}%</span>
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
                <p class="text-sm mb-0 text-capitalize font-weight-bold">InActive Customers</p>
                <h5 class="font-weight-bolder mb-0">
                {{ $inactiveCustomers }}
                  <span class="text-danger text-sm font-weight-bolder">-{{ number_format($inactivePercentage, 2) }}%</span>
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
    <div class="d-flex justify-content-between align-items-center mb-3">  
        <h3 class="mb-0">Customer List</h3>
        <div class="d-flex">
            <input type="text" class="form-control mr-2" id="search" placeholder="Search...">&nbsp;&nbsp;
            <select class="form-control" id="sortBy">
                <option value="oldest">Sort by Oldest</option>
                <option value="newest">Sort by Newest</option>
            </select>
        </div>
    </div>

    <div class="mb-3">
        <label for="per_page" class="mr-2">Show:</label>
        <select id="per_page" class="form-control d-inline-block w-auto" onchange="changePerPage(this.value)">
            <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
            <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
            <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
            <option value="all" {{ $perPage == 'all' ? 'selected' : '' }}>All</option>
        </select>
    </div>
    <table class="table table-striped table-hover mt-3">
    <thead class="thead-dark">
                    <tr>
                        <th>No</th>
                        <th>Name</th>
                        <th>Company</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Country</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($customers as $index => $customer)
                        <tr>
                            <td class="mb-0 text-sm">{{ $index + 1 + (($customers->currentPage() - 1) * $customers->perPage()) }}</td>
                            <td class="mb-0 text-sm">{{ $customer->name }}</td>
                            <td class="mb-0 text-sm">{{ $customer->company }}</td>
                            <td class="mb-0 text-sm">{{ $customer->phone }}</td>
                            <td class="mb-0 text-sm">{{ $customer->email }}</td>
                            <td class="mb-0 text-sm">{{ $customer->country }}</td>
                            <td class="mb-0 text-sm">
                                <span class="badge bg-gradient-{{ $customer->status == 1 ? 'success' : 'danger' }}">
                                    {{ $customer->status == 1 ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="text-center">
                            <a href="#" class="edit-customer" 
                                data-id="{{ $customer->id }}" 
                                data-name="{{ $customer->name }}" 
                                data-company="{{ $customer->company }}" 
                                data-phone="{{ $customer->phone }}" 
                                data-email="{{ $customer->email }}" 
                                data-country="{{ $customer->country }}" 
                                data-addresses='{{ json_encode($customer->addresses) }}' 
                                data-toggle="modal" 
                                data-target="#editCustomerModal">
                                <i class="fas fa-user-edit text-secondary"></i>
                                </a>

                                <form action="{{ route('customers.destroy', $customer->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="delete-customer" style="border: none; background: none;">
                                        <i class="cursor-pointer fas fa-trash text-secondary"></i>
                                    </button>
                                </form>

                            </td>

                        </tr>
                        <tr>
                            <td colspan="7">
                                <table class="table mb-0">
                                    <thead></thead>
                                    <tbody>
                                        @foreach($customer->addresses as $index => $address)
                                            <tr>
                                                <td></td>
                                                <td class="mb-0 text-sm">{{ 'Address' . ($index + 1) }}</td>
                                                <td class="mb-0 text-sm">{{ $address->number }}</td>
                                                <td class="mb-0 text-sm">{{ $address->street }}</td>
                                                <td class="mb-0 text-sm">{{ $address->city }}</td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination Links -->
    <div class="d-flex justify-content-between align-items-center mt-3">
        <div>
            {{ $customers->links() }}
        </div>
    </div>
</div>
    <!-- Modal for adding new customer -->
    <div class="modal fade" id="addCustomerModal" tabindex="-1" role="dialog" aria-labelledby="addCustomerModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="addCustomerModalLabel">Add New Customer</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('customers.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="company">Company</label>
                            <input type="text" class="form-control" id="company" name="company" required>
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone</label>
                            <input type="text" class="form-control" id="phone" name="phone" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="country">Country</label>
                            <input type="text" class="form-control" id="country" name="country" required>
                        </div>
                        <!-- Dynamic Address Section -->
                        <div id="addressSection">
                            <h5>Address Details</h5>
                            <div class="address-entry">
                            <h6 class="address-title">Address 1</h6>
                                <div class="form-group">
                                    <label for="addresses[0][number]">Number</label>
                                    <input type="text" class="form-control" name="addresses[0][number]" required>
                                </div>
                                <div class="form-group">
                                    <label for="addresses[0][street]">Street</label>
                                    <input type="text" class="form-control" name="addresses[0][street]" required>
                                </div>
                                <div class="form-group">
                                    <label for="addresses[0][city]">City</label>
                                    <input type="text" class="form-control" name="addresses[0][city]" required>
                                </div>
                                <button type="button" class="btn btn-danger remove-address-btn" style="display:none;">Remove</button>
                            </div>
                        </div>
                        <button type="button" class="btn btn-secondary mt-2" id="addAddressBtn">Add Another Address</button>

                        <button type="submit" class="btn btn-primary mt-3">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Edit Modal for updating customer -->
<div class="modal fade" id="editCustomerModal" tabindex="-1" role="dialog" aria-labelledby="editCustomerModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="editCustomerModalLabel">Edit Customer</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editCustomerForm" method="POST">
                    @csrf
                    @method('PUT') <!-- Since it's an update request -->

                    <div class="form-group">
                        <label for="editName">Name</label>
                        <input type="text" class="form-control" id="editName" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="editCompany">Company</label>
                        <input type="text" class="form-control" id="editCompany" name="company" required>
                    </div>
                    <div class="form-group">
                        <label for="editPhone">Phone</label>
                        <input type="text" class="form-control" id="editPhone" name="phone" required>
                    </div>
                    <div class="form-group">
                        <label for="editEmail">Email</label>
                        <input type="email" class="form-control" id="editEmail" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="editCountry">Country</label>
                        <input type="text" class="form-control" id="editCountry" name="country" required>
                    </div>

                    <!-- Dynamic Address Section for Edit -->
                    <div id="editAddressSection">
                        <h5>Address Details</h5>
                        <!-- Addresses will be appended here dynamically using JavaScript -->
                         <!-- Addresses will be populated dynamically -->
                        <div class="edit-address-entry">
                        <h6 class="address-title">Address 1</h6>
                            <div class="form-group">
                                <label for="editAddresses[0][number]">Number</label>
                                <input type="text" class="form-control" name="addresses[0][number]" required>
                            </div>
                            <div class="form-group">
                                <label for="editAddresses[0][street]">Street</label>
                                <input type="text" class="form-control" name="addresses[0][street]" required>
                            </div>
                            <div class="form-group">
                                <label for="editAddresses[0][city]">City</label>
                                <input type="text" class="form-control" name="addresses[0][city]" required>
                            </div>
                            <button type="button" class="btn btn-danger remove-edit-address-btn" style="display:none;">Remove</button>
                        </div>
                    </div>
                    <button type="button" class="btn btn-secondary mt-2" id="addEditAddressBtn">Add Another Address</button>

                    <button type="submit" class="btn btn-primary mt-3">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Edit customer click event
        document.querySelectorAll('.edit-customer').forEach(function(editButton) {
            editButton.addEventListener('click', function() {
                var customerId = this.dataset.id;
                var customerName = this.dataset.name;
                var customerCompany = this.dataset.company;
                var customerPhone = this.dataset.phone;
                var customerEmail = this.dataset.email;
                var customerCountry = this.dataset.country;
                var customerAddresses = JSON.parse(this.dataset.addresses);

                // Populate the form fields
                document.getElementById('editName').value = customerName;
                document.getElementById('editCompany').value = customerCompany;
                document.getElementById('editPhone').value = customerPhone;
                document.getElementById('editEmail').value = customerEmail;
                document.getElementById('editCountry').value = customerCountry;

                // Clear existing address fields
                document.querySelectorAll('#editAddressSection .edit-address-entry:not(:first-of-type)').forEach(function(entry) {
                    entry.remove();
                });

                // Populate address fields
                if (customerAddresses.length > 0) {
                    var firstAddressEntry = document.querySelector('#editAddressSection .edit-address-entry:first-of-type');
                    firstAddressEntry.querySelectorAll('input').forEach(function(element) {
                        var name = element.getAttribute('name');
                        var fieldName = name.split('[')[2].split(']')[0];
                        element.value = customerAddresses[0][fieldName];
                    });

                    for (var i = 1; i < customerAddresses.length; i++) {
                        var newAddressEntry = firstAddressEntry.cloneNode(true);
                        newAddressEntry.querySelectorAll('input').forEach(function(element) {
                            var name = element.getAttribute('name');
                            var newName = name.replace(/\d+/, i);
                            element.setAttribute('name', newName);
                            var fieldName = newName.split('[')[2].split(']')[0];
                            element.value = customerAddresses[i][fieldName];
                        });
                        newAddressEntry.querySelector('.remove-edit-address-btn').style.display = 'inline';
                        newAddressEntry.querySelector('.address-title').textContent = 'Address ' + (i + 1);
                        document.getElementById('editAddressSection').appendChild(newAddressEntry);
                    }
                }

                // Set the form action URL
                var updateCustomerUrl = '{{ route("customers.update", ":id") }}';
                var updateUrl = updateCustomerUrl.replace(':id', customerId);
                document.getElementById('editCustomerForm').setAttribute('action', updateUrl);
            });
        });

        // Add new address field
        document.getElementById('addEditAddressBtn').addEventListener('click', function() {
            var firstAddressEntry = document.querySelector('#editAddressSection .edit-address-entry:first-of-type');
            var newAddressEntry = firstAddressEntry.cloneNode(true);
            var newIndex = document.querySelectorAll('#editAddressSection .edit-address-entry').length;
            newAddressEntry.querySelectorAll('input').forEach(function(element) {
                var name = element.getAttribute('name');
                var newName = name.replace(/\d+/, newIndex);
                element.setAttribute('name', newName);
                element.value = '';
            });
            newAddressEntry.querySelector('.remove-edit-address-btn').style.display = 'inline';
            newAddressEntry.querySelector('.address-title').textContent = 'Address ' + (newIndex + 1);
            document.getElementById('editAddressSection').appendChild(newAddressEntry);
        });

        // Remove address field
        document.addEventListener('click', function(event) {
            if (event.target.classList.contains('remove-edit-address-btn')) {
                event.target.closest('.edit-address-entry').remove();
                // Update address titles
                document.querySelectorAll('#editAddressSection .edit-address-entry').forEach(function(entry, index) {
                    entry.querySelector('.address-title').textContent = 'Address ' + (index + 1);
                });
            }
        });

        // Handle form submission
        document.getElementById('editCustomerForm').addEventListener('submit', function(event) {
            event.preventDefault();
            var formData = new FormData(this);
            for (var pair of formData.entries()) {
                console.log(pair[0] + ': ' + pair[1]);
            }

            // Show loading indicator
            //document.getElementById('loadingIndicator').style.display = 'block';
           // Inside the form submission handling
            fetch(this.getAttribute('action'), {
                method: 'PUT',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json' // Ensure the server returns JSON
                }
            })
            .then(function(response) {
                if (!response.ok) {
                    return response.json().then(function(errorData) {
                        throw new Error('Network response was not ok: ' + JSON.stringify(errorData));
                    });
                }
                return response.json();
            })
            .then(function(data) {
                console.log('Customer updated successfully:', data);

                // Hide loading indicator
                document.getElementById('loadingIndicator').style.display = 'none';

                // Close the modal
                $('#editCustomerModal').modal('hide');

                // Optionally refresh the customer list or update the UI
                // refreshCustomerList();

                // Show success message
                alert('Customer updated successfully!');
            })
            .catch(function(error) {
                console.error('Error updating customer:', error);

                // Hide loading indicator
                document.getElementById('loadingIndicator').style.display = 'none';

                // Show error message to the user
                alert('Failed to update customer. Please try again.');
            });

        });
    });
</script>


<!-- <script>
  $(document).ready(function() {
    $('.edit-customer').on('click', function() {
        var customerId = $(this).data('id');
        var customerName = $(this).data('name');
        var customerCompany = $(this).data('company');
        var customerPhone = $(this).data('phone');
        var customerEmail = $(this).data('email');
        var customerCountry = $(this).data('country');
        var customerAddresses = $(this).data('addresses');

        // Populate the form fields
        $('#editName').val(customerName);
        $('#editCompany').val(customerCompany);
        $('#editPhone').val(customerPhone);
        $('#editEmail').val(customerEmail);
        $('#editCountry').val(customerCountry);

        // Clear existing address fields
        $('#editAddressSection .edit-address-entry').not(':first').remove();

        // Populate address fields
        if (customerAddresses.length > 0) {
            $('#editAddressSection .edit-address-entry:first').find('input').each(function(index, element) {
                var name = $(element).attr('name');
                var addressIndex = name.match(/\d+/)[0];
                $(element).val(customerAddresses[0][name.split('[')[2].split(']')[0]]);
            });

            for (var i = 1; i < customerAddresses.length; i++) {
                var newAddressEntry = $('#editAddressSection .edit-address-entry:first').clone();
                newAddressEntry.find('input').each(function(index, element) {
                    var name = $(element).attr('name');
                    var newName = name.replace(/\d+/, i);
                    $(element).attr('name', newName);
                    $(element).val(customerAddresses[i][newName.split('[')[2].split(']')[0]]);
                });
                newAddressEntry.find('.remove-edit-address-btn').show();
                newAddressEntry.find('.address-title').text('Address ' + (i + 1));
                $('#editAddressSection').append(newAddressEntry);
            }
        }
        var updateCustomerUrl = '{{ route("customers.update", ":id") }}'; 
        // Update the form action URL
        var updateUrl = updateCustomerUrl.replace(':id', customerId);
        $('#editCustomerForm').attr('action', updateUrl);
    });

    // Add new address field
    $('#addEditAddressBtn').on('click', function() {
        var newAddressEntry = $('#editAddressSection .edit-address-entry:first').clone();
        var newIndex = $('#editAddressSection .edit-address-entry').length;
        newAddressEntry.find('input').each(function(index, element) {
            var name = $(element).attr('name');
            var newName = name.replace(/\d+/, newIndex);
            $(element).attr('name', newName);
            $(element).val('');
        });
        newAddressEntry.find('.remove-edit-address-btn').show();
        newAddressEntry.find('.address-title').text('Address ' + (newIndex + 1));
        $('#editAddressSection').append(newAddressEntry);
    });

    // Remove address field
    $(document).on('click', '.remove-edit-address-btn', function() {
        $(this).closest('.edit-address-entry').remove();
        // Update address titles
        $('#editAddressSection .edit-address-entry').each(function(index, element) {
            $(element).find('.address-title').text('Address ' + (index + 1));
        });
    });

    // Handle form submission
    $('#editCustomerForm').on('submit', function(event) {
        event.preventDefault();
        var formData = $(this).serialize();
        $.ajax({
            url: $(this).attr('action'), // Use the dynamically set action URL
            method: 'PUT',
            data: formData,
            success: function(response) {
                // Handle success
                console.log('Customer updated successfully:', response);
                // Close the modal
                $('#editCustomerModal').modal('hide');
            },
            error: function(error) {
                // Handle error
                console.error('Error updating customer:', error);
            }
        });
    });
});
</script> -->

<script>
   $(document).ready(function() {
    var addressIndex = 1; // Start from 1 since Address 1 is already present

    // Add new address fields
    $('#addAddressBtn').on('click', function() {
        var newAddress = `
            <div class="address-entry">
                <h6 class="address-title">Address ${addressIndex + 1}</h6>
                <div class="form-group">
                    <label for="addresses[${addressIndex}][number]">Number</label>
                    <input type="text" class="form-control" name="addresses[${addressIndex}][number]" required>
                </div>
                <div class="form-group">
                    <label for="addresses[${addressIndex}][street]">Street</label>
                    <input type="text" class="form-control" name="addresses[${addressIndex}][street]" required>
                </div>
                <div class="form-group">
                    <label for="addresses[${addressIndex}][city]">City</label>
                    <input type="text" class="form-control" name="addresses[${addressIndex}][city]" required>
                </div>
                <button type="button" class="btn btn-danger remove-address-btn">Remove</button>
            </div>`;
        $('#addressSection').append(newAddress);
        addressIndex++;

        // Show the remove button for the last added address
        $('#addressSection .remove-address-btn').last().show();
    });

    // Remove address fields
    $(document).on('click', '.remove-address-btn', function() {
        $(this).closest('.address-entry').remove();
        // Hide all remove buttons except the last one
        $('#addressSection .remove-address-btn').hide();
        $('#addressSection .remove-address-btn').last().show();
    });
});

 // Search functionality
 $('#search').on('keyup', function() {
            var value = $(this).val().toLowerCase();
            $('table tbody tr').filter(function() {
                var rowText = $(this).text().toLowerCase();
                var addressText = $(this).next('tr').find('table').text().toLowerCase();
                $(this).toggle(rowText.indexOf(value) > -1 || addressText.indexOf(value) > -1);
            });
        });

        // Sort functionality
        $('#sortBy').on('change', function() {
            var rows = $('table tbody tr:has(td)').get();
            var sortBy = $(this).val();

            rows.sort(function(a, b) {
                var keyA = $(a).children('td').eq(0).text();
                var keyB = $(b).children('td').eq(0).text();

                if (sortBy === 'newest') {
                    return (keyA < keyB) ? 1 : (keyA > keyB) ? -1 : 0;
                } else {
                    return (keyA < keyB) ? -1 : (keyA > keyB) ? 1 : 0;
                }
            });

            $.each(rows, function(index, row) {
                $('table').children('tbody').append(row);
                $(row).next('tr').insertAfter(row); // Ensure address rows stay with their parent rows
            });
        });

    // Function to change per page items
    function changePerPage(perPage) {
        const search = document.getElementById('search').value;
        const sortBy = document.getElementById('sortBy').value;
        window.location.href = `?per_page=${perPage}&search=${search}&sortBy=${sortBy}`;
    }
</script>
@endsection
