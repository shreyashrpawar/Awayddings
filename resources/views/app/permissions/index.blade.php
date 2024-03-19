
@extends('layouts.app')
@section('title','Home Page')
@section('css')
    <link rel="stylesheet" href="{{ asset('datatable/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{ asset('datatable/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{ asset('datatable/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
    <style>
        thead.thead-dark {
            color: white;
        }

        .custom-select {
            width: 55px !important;
        }
    </style>
@endsection
@section('contents')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<div class="container mt-4">
    <div class="card">
        <div class="card-body">
        <div class="text-right">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editRolesModal">
               Add/Edit Roles
               <i class="mdi mdi-plus ml-1"></i>       
        </button>
        </div>
            <h5 class="card-title">Permission Settings</h5>
            <form action="{{ route('permissions.updatePermissions') }}"  method="POST">
                @csrf
                <div class="form-group">
                    <label for="page">Select Page:</label>

                    <select name="page" id="pagesel" class="form-control" >
                   
                      @foreach($pages as $page)
                          <option value="{{ $page->name }}" @if($page->name === $selectedPage) selected @endif >{{ $page->name }}</option>
                      @endforeach
                    </select>
                </div>

             

                <div class="table-responsive">
                    <table id="permissionsTable" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>Role</th>
                                <th>Create</th>
                                <th>read</th>
                                <th>Update</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($roles as $role)
                                <tr>
                                <td>{{ $role->name }}</td>
                                <td>
                                    <input type="checkbox" name="permissions[{{ $role->id }}][create]" value="on" 
                                      @if(isset($rolePermissions[$role->id]) && in_array($selectedPage . '-create', $rolePermissions[$role->id])) checked @endif 
                                      onclick="updatePermissions('{{ $role->id }}', 'create', this.checked ? 'on' : 'off')">
                                </td>
                                <td>
                                    <input type="checkbox" name="permissions[{{ $role->id }}][read]" value="on" 
                                        @if(isset($rolePermissions[$role->id]) && in_array($selectedPage . '-read', $rolePermissions[$role->id])) checked @endif 
                                        onclick="updatePermissions('{{ $role->id }}', 'read', this.checked ? 'on' : 'off')">
                                </td>
                                <td>
                                    <input type="checkbox" name="permissions[{{ $role->id }}][update]" value="on" 
                                        @if(isset($rolePermissions[$role->id]) && in_array($selectedPage . '-update', $rolePermissions[$role->id])) checked @endif 
                                        onclick="updatePermissions('{{ $role->id }}', 'update', this.checked ? 'on' : 'off')">
                                </td>
                                <td>
                                    <input type="checkbox" name="permissions[{{ $role->id }}][delete]" value="on" 
                                        @if(isset($rolePermissions[$role->id]) && in_array($selectedPage . '-delete', $rolePermissions[$role->id])) checked @endif 
                                        onclick="updatePermissions('{{ $role->id }}', 'delete', this.checked ? 'on' : 'off')">
                                </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <input type="hidden" id="permissions_updtd" name="permissions_updtd" value="">
                <div class="container mt-3">
                    <div class="text-center">
                        <button type="submit" class="btn btn-sm btn-primary" >Save Permissions</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editRolesModal" tabindex="-1" aria-labelledby="editRolesModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editRolesModalLabel">Edit Roles</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <!-- Add New Role Form -->
        <form action="{{ route('permissions.create-role') }}" method="post">
          @csrf
          <div class="form-group">
            <label for="newRole">New Role Name:</label>
            <input type="text" class="form-control" id="newRole" name="newRole">
          </div>
          <button type="submit" class="btn btn-primary">Add New Role</button>
        </form>
        
        <hr>
        
        
        <!-- Select and Remove Existing Role -->
        <form action="{{ route('permissions.update-role') }}" method="post">
          @csrf
          <div class="form-group">
            <label for="existingRole">Select Role to Edit:</label>
            <select class="form-control" id="existingRole" name="existingRole">
              @foreach($roles as $role)
                @if($role->name !== 'superAdmin')
                <option value="{{ $role->id }}">{{ $role->name }}</option>
                @endif
              @endforeach
            </select>
          </div>
          <div class="form-group">
            <label for="editedRoleName">New Role Name:</label>
            <input type="text" class="form-control" id="editedRoleName" name="editedRoleName">
          </div>
          <button type="submit" class="btn btn-primary">Edit Role</button>
        </form>
      </div>
    </div>
  </div>
</div>

@endsection

@section('js')

    <script src="{{ asset('datatable/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('datatable/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('datatable/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('datatable/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('datatable/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('datatable/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('datatable/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('datatable/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('datatable/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('datatable/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('datatable/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('datatable/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
    <script>
    $(document).ready(function() {
        $('#permissionsTable').DataTable();
        // Listen for change event on the page dropdown
        
        $('#pagesel').change(function() {
       
            // Get the selected page value
            var selectedPage = $(this).val();
            // Reload the page with the selected page value as a query parameter
            window.location.href = '{{ route("permissions.index") }}?page=' + selectedPage;
        });
    });
    </script>

  <script>
    let permissions_updtd = {};

    function updatePermissions(roleId, action, value) {
     
        if (!permissions_updtd.hasOwnProperty(roleId)) {
          permissions_updtd[roleId] = {};
        }
        permissions_updtd[roleId][action] = value;
        document.getElementById('permissions_updtd').value = JSON.stringify(permissions_updtd);
        console.log(permissions_updtd);
    }
  </script>

@endsection