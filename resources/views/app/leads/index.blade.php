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
        .toast {
            position: fixed;
            top: 10%; /* Place the toast at 50% from the top of the viewport */
            left: 55%; /* Place the toast at 50% from the left of the viewport */
            transform: translate(-50%, -50%); /* Center the toast horizontally and vertically */
            z-index: 1000; /* Ensure the toast appears above other elements */
        }
    </style>
@endsection
@section('contents')
    <div class="card">
        <div class="card-body">
            <div class="row form-group">
                <div class="col-md-6">
                    <h4 class="card-title text-uppercase">Leads List</h4>
                </div>
                <div class="col-md-6 text-right">
                @can('Ads-Leads-create')  
                    <button type="button" class="btn btn-sm btn-primary"
                            data-toggle="modal"
                            data-target="#addNewLead">Add<i
                            class="mdi mdi-plus ml-1"></i>
                    </button>
                @endcan
                </div>
            </div>


            <div class="table-responsive">
                <table class="table" id="example1">
                    <thead class="thead-dark">
                    <tr>
                        <th width="5%">#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Bride & Groom</th>
                        <th>Wedding Date</th>
                        <th>Pax</th>
                        <th>Remark</th>
                        <th>Created On</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($leads as $key => $val)
                        @if($val->status == 'new')
                            <tr  id="row_{{ $val->id }}" style="background: whitesmoke">
                        @elseif( $val->status == 'recce_planned' or $val->status == 'potential_recce' or $val->status == 'recce_done')
                            <tr id="row_{{ $val->id }}" style="background: #7cfffc">
                        @elseif( $val->status == 'lost_general_inquiry')
                            <tr id="row_{{ $val->id }}" style="background: #ff8989">
                        @elseif( $val->status == 'under_discussion')
                            <tr id="row_{{ $val->id }}" style="background: #ffea99">
                        @elseif( $val->status == 'call_not_picked')
                            <tr id="row_{{ $val->id }}" style="background: lightsteelblue">
                        @elseif( $val->status == 'call_back' or  $val->status == 'send_to_decor')
                            <tr id="row_{{ $val->id }}" style="background: lightgreen">
                        @else
                            <tr id="row_{{ $val->id }}" style="background: #b9fd84">
                                @endif
                                <th>{{ $loop->index + 1}}</th>
                                <td>{{ $val->name }}</td>
                                <td>{{ $val->email }}</td>
                                <td>{{ $val->mobile }}</td>
                                <td >{{ $val->bride_groom }}</td>
                                <td>{{ $val->wedding_date }}</td>
                                <td>{{ $val->pax }}</td>
                                <td>{{ strlen($val->remarks) > 0 ? $val->remarks: 'No Remarks Found'}}</td>
                                <td>{{ date('d-m-Y', strtotime($val->created_at))}}</td>
                                <td>
                                    @if($val->status == 'new')
                                        <span class="badge badge-light">{{ $val->status }}</span>
                                    @elseif( $val->status == 'recce_planned' or $val->status == 'potential_recce' or $val->status == 'recce_done')
                                        <span class="badge badge-info">{{ $val->status }}</span>
                                    @elseif( $val->status == 'lost_general_inquiry')
                                        <span class="badge badge-danger">{{ $val->status }}</span>
                                    @elseif( $val->status == 'under_discussion')
                                        <span class="badge badge-warning">{{ $val->status }}</span>
                                    @elseif( $val->status == 'call_not_picked')
                                        <span class="badge badge-secondary">{{ $val->status }}</span>
                                    @else
                                        <span class="badge badge-success">{{ $val->status }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group">
                                    @can('Ads-Leads-update') 
                                        @if($val->status != 'lost_general_inquiry')
                                            <button type="button" class="btn btn-sm btn-outline-primary actionbtn"
                                                    data-toggle="modal"
                                                    data-target="#editLead-{{$val->id}}">Action<i
                                                    class="mdi mdi-pencil ml-1"></i>
                                            </button>
                                        @endif
                                    @endcan
                                        <button type="button" class="btn btn-sm btn-primary"
                                                data-toggle="modal"
                                                data-target="#viewRemark-{{$val->id}}">View<i
                                                class="mdi mdi-eye ml-1"></i>
                                        </button>
                                        @if($val->status != 'lost_general_inquiry')
                                        @can('Ads-Leads-delete') 
                                            @if(request()->has('trashed'))
                                                <a href="{{ route('leads.restore', $val->id) }}"
                                                   class="btn btn-success">Restore</a>
                                            @else
                                                <form method="POST" action="{{ route('leads.destroy', $val->id) }}">
                                                    @csrf
                                                    <input name="_method" type="hidden" value="DELETE">
                                                    <button type="submit" class="btn btn-danger delete" title='Delete'>
                                                        Delete
                                                    </button>
                                                </form>
                                            @endif
                                        @endcan
                                        @else
                                        @can('Lost-Leads-delete')
                                        @if(request()->has('trashed'))
                                                <a href="{{ route('leads.restore', $val->id) }}"
                                                   class="btn btn-success">Restore</a>
                                            @else
                                                <form method="POST" action="{{ route('leads.destroy', $val->id) }}">
                                                    @csrf
                                                    <input name="_method" type="hidden" value="DELETE">
                                                    <button type="submit" class="btn btn-danger delete" title='Delete'>
                                                        Delete
                                                    </button>
                                                </form>
                                            @endif
                                        
                                        @endcan
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="fixed-bottom " style="background-color: rgba(0, 0, 0, 0.85); padding: 15px;">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h6 class="mb-2" style="color:white">Status Legend</h6>
                <div class="legend">
                    @foreach($leads_statuses as $status => $options)
                        <span class="badge {{ $options['badge'] }} mr-2"   {{ $options['background'] }}; color: black;">{{ ucwords(str_replace('_', ' ', $status)) }}</span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
    
    
    @foreach($leads as $key => $val)
        <div class="modal fade" id="viewRemark-{{$val->id}}" tabindex="-1" role="dialog" aria-labelledby="ModalLabel"
             style="display: none;" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ModalLabel">View Remark for: {{$val->bride_groom}} </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>{{trim($val->remarks) == "" ? 'No remark found' : $val->remarks}}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    @foreach($leads as $key => $val)
        <div class="modal fade" id="editLead-{{$val->id}}" tabindex="-1" role="dialog" aria-labelledby="ModalLabel"
             style="display: none;" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ModalLabel">Update Lead for: {{$val->bride_groom}} </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="edit-lead-form-{{$val->id}}"
                              action="{{ route('leads.update', ['lead' => $val->id]) }}"
                              method="post" class="update-lead-form">
                            {{ method_field('PATCH') }}
                            {{ csrf_field() }}
                            <label>Current Status: <b>{{ucwords(str_replace('_', ' ', $val->status))}}</b></label>
                            <div class="form-group">
                                <label>Update Status to</label>
                                <select name="lead_status" id="lead_status" class="form-control">
                                    <option value="" disabled selected>Select Status</option>
                                    @foreach($leads_statuses as $status => $options)
                                        <option value="{{ $status }}" data-background="{{ $options['background'] }}"
                                        data-badge="{{ $options['badge'] }}" {{ $val->status == $status ? 'selected' : '' }}>
                                        {{ ucwords(str_replace('_', ' ', $status)) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="message-text" class="col-form-label">Remark:</label>
                                <textarea class="form-control" id="lead_remarks" name="lead_remarks"
                                          rows="8">{{$val->remarks}}</textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <input type="submit" class="btn btn-success" value="Update" form="edit-lead-form-{{$val->id}}">
                        <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach





    <div class="modal fade" id="addNewLead" tabindex="-1" role="dialog" aria-labelledby="ModalLabel"
         style="display: none;" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalLabel">Add New Lead</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="add-new-lead-form"
                          action="{{ route('leads.store') }}"
                          method="post">
                        {{ method_field('POST') }}
                        {{ csrf_field() }}
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="customer_name">Customer Name</label>
                                <input type="text" class="form-control" id="customer_name" name="customer_name"
                                       placeholder="Customer Name" required/>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="customer_email">Email</label>
                                <input type="email" class="form-control" id="customer_email" name="customer_email"
                                       placeholder="Email" required/>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="customer_mobile">Mobile Number</label>
                                <input type="number" class="form-control" maxlength="10" id="customer_mobile"
                                       name="customer_mobile" placeholder="Customer Phone" required/>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="customer_date">Wedding Date</label>
                                <input type="date" class="form-control" id="wedding_date" name="customer_date"
                                       placeholder="Wedding Date" required/>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="bride_groom">Couple Name</label>
                                <input type="text" class="form-control" id="bride_groom" name="bride_groom"
                                       placeholder="Couple Name"/>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="customer_pax">Total Pax</label>
                                <input type="number" class="form-control" name="customer_pax" id="customer_pax"
                                       placeholder="Total Guests" required>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <input type="submit" class="btn btn-success" value="Add New" form="add-new-lead-form">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div class="toast border border-success rounded" role="alert" aria-live="assertive" aria-atomic="true" data-autohide="true" data-delay="1200">
        <div class="toast-header bg-success text-white">
            <strong class="mr-auto">Success</strong>
            <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <div class="toast-body">
        <i class="fas fa-check-circle mr-2"></i> Lead updated successfully.
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
        $(function () {
            $("#example1").DataTable({
                "responsive": true,
                "lengthChange": true,
                "autoWidth": false,
                "paging": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "stateSave": true ,
                "buttons": ["csv", "pdf", "print"],  
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        });

    
    // Event listener for Update

     $(document).ready(function () {
    $('.update-lead-form').submit(function (event) {
        event.preventDefault();
        var formData = $(this).serialize();
        var url = $(this).attr('action');
        
        $.ajax({
            type: 'POST',
            url: url,
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') 
            },
            success: function (response) {
                var updatedLead = response.lead; 
                updateLeadInTable(updatedLead); // Update lead data in the table
                updateRemarkInModal(updatedLead); // Update lead data in the modal
                $('#editLead-' + updatedLead.id).modal('hide');  //Close the modal after editing 
                $('.toast').toast('show'); 
            },
            error: function (xhr, status, error) {
                console.error('Error updating lead:', error);
            }
        });
    });  

    //update remark, badge , status, background color in datatable

    function updateLeadInTable(updatedLead) {
   
    var parentRow = $('#row_' + updatedLead.id);
    var childRow = parentRow.next('.child');
    var dtrDetails = childRow.find('.dtr-details');   
    var remarkSpan = dtrDetails.find('.dtr-title:contains("Remark")').next('.dtr-data');
    var statusSpan = dtrDetails.find('.dtr-title:contains("Status")').next('.dtr-data');
    remarkSpan.attr('data-original', updatedLead.remarks);
    remarkSpan.text(updatedLead.remarks);
  
    statusSpan.find('.badge').text(updatedLead.status);
    parentRow.css('background-color', updatedLead.background);
    statusSpan.find('.badge').removeClass().addClass('badge').addClass(updatedLead.badge);
     // Update remark in Parent Row
    var remarksCell = parentRow.find('td:nth-child(8)');
    remarksCell.text(updatedLead.remarks);
    // Update Status in Parent Row
    var statusCell = parentRow.find('td:nth-child(10)');
    statusCell.html('<span class="badge ' + updatedLead.badge + '">' + updatedLead.status + '</span>');
    }

    //update remark in view remark modal

    function updateRemarkInModal( updatedLead) {

        var modal = $('#viewRemark-' + updatedLead.id);
        var modalBody = modal.find('.modal-body');
        modalBody.find('p').text(updatedLead.remarks);
        
     
    }

    // Event listener for when the child row is expanded

    var table = $('#example1').DataTable();
     
    $('#example1 tbody').on('click', 'th.dtr-control', function() {
        var row = table.row($(this).closest('tr'));
        
        if (row.child.isShown()) {
            // Child row is expanded
            $(document).trigger('childRowExpanded', row.data());
            var parentRow = $(this).closest('tr');
            var remarksCell = parentRow.find('td:nth-child(8)');
            var remarkSpan = row.child().find('.dtr-title:contains("Remark")').next('.dtr-data');
            remarkSpan.text(remarksCell.text());
            var statusCell = parentRow.find('td:nth-child(10)'); // Get the status cell from parent row
            var childStatusSpan = row.child().find('.dtr-title:contains("Status")').next('.dtr-data').find('.badge');
            var childStatus = statusCell.find('.badge').text(); // Get the status from parent row
            childStatusSpan.text(childStatus); // Update the status in the child row
            var childBadge = statusCell.find('.badge').attr('class'); 
            childStatusSpan.removeClass().addClass(childBadge);// Get the badge class from parent row
        } 
    });

    $('#example1 tbody').on('click', '.actionbtn', function () {
        var currentDate = new Date().toLocaleDateString('en-IN');
        var userName = '{{ Auth::user()->name }}'; 
        // Get the logged-in user's name 
        var modalId = $(this).data('target');
        var modal = $(modalId);

        // Find the remark textarea within the modal
        var remarkTextArea = modal.find('textarea[name="lead_remarks"]');
       
        // Append the current date and user's name to the remarks
        var currentRemarks = remarkTextArea.val();
        var updatedRemarks = currentRemarks;
        
        // Check if the current date already exists in the remarks
        if (currentRemarks.trim() !== '' && !updatedRemarks.endsWith(currentDate + ' - ' + userName + ' : ')) {
            updatedRemarks += ' , ' + '\n' + currentDate + ' - ' + userName + ' : ';
        } else if (currentRemarks.trim() === '') {
            // If remarks are empty, append the current date and user's name without starting from the next line
            updatedRemarks = currentDate + ' - ' + userName + ' : ';
        }
            // Update the remarks in the textarea
            remarkTextArea.val(updatedRemarks);
        });
});
</script>
@endsection
