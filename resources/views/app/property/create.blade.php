@extends('layouts.app')
@section('title','Home Page')
@section('contents')
    <div class="content-wrapper">
        <div class="row">
            <div class="col-12 grid-margin">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Property Registration</h4>
                        <form id="propertyRegistrationFrom" action="{{ route('property.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div>
                                <h3>Basic Details</h3>
                                <section>
                                    <h3>Basic Details</h3>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Name</label>
                                                <input type="text" class="form-control"  placeholder="Enter the property name" name="property_name" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Description</label>
                                                <textarea  class="form-control"  cols="30" rows="5" name="property_description"  placeholder="Enter the Property Description" required></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label>Address</label>
                                                <input type="text" class="form-control" placeholder="Enter the Address" name="property_address" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Location</label>
                                                <select class="form-control" name="property_location_id" required>
                                                    <option value="">Select Location</option>
                                                    @foreach($locations as $key => $val)
                                                        <option value="{{ $key }}">{{ $val }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label>Cover Image</label>
                                                <input type="file" class="form-control" name="featured_image" >
                                            </div>

                                            <div class="form-group">
                                                <label>Google Embed Code </label>
                                                <textarea  class="form-control" id="description" cols="30" rows="5" name="property_gmap_embedded_code" placeholder="Enter the Gmap Embedded URL" required></textarea>
                                            </div>
                                        </div>
                                    </div>

                                </section>
                                <h3>Images/Videos</h3>
                                <section>
                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <h3>Images</h3>
                                        </div>
                                        <div class="col-md-6 text-right">

                                        </div>
                                    </div>
                                     <div id="imageTypeBody">
                                         <div class="form-group">
                                             @foreach($images_video_categories as $id => $name)
                                             <div class="row">
                                                 <div class="col-md-1">
                                                     {{ 1 + $loop->index }}.
                                                 </div>
                                                 <div class="col-md-5">
                                                    <h5>{{ $name }}</h5>
                                                 </div>
                                                 <div class="col-md-6">
                                                     <label>Upload File</label>
                                                     <input type="file" class="form-control" name="{{ Str::snake($name, '_') }}_upload[]" multiple>
                                                 </div>

                                             </div>
                                             @endforeach
                                         </div>
                                     </div>
                                </section>
                                <h3>Menu </h3>
                                <section>
                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <h3>Menu </h3>
                                        </div>
                                        <div class="col-md-6 text-right">

                                        </div>
                                    </div>
                                    <div id="MenuTypeBody">
                                        <div class="form-group">
                                            @foreach($menu_sub_categories as $id => $name)
                                            <div class="row">
                                                <div class="col-md-1">
                                                    {{ 1 + $loop->index }}.
                                                </div>
                                                <div class="col-md-5">
                                                    <h5>{{ $name }}</h5>
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Upload File</label>
                                                    <input type="file" class="form-control" name="{{ Str::snake($name,'_').'_menu' }}">
                                                </div>

                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </section>
                                <h3>Room and Amenities</h3>
                                <section>
                                    <h3>Room </h3>
                                    @foreach($hotel_chargable_type as $key => $name)
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                        <input type="checkbox" name="{{ Str::snake($name, '_') }}"
                                                               class="form-check-input hotel_chargable_typeId"
                                                               data="{{ Str::snake($name,'_') }}_section"
                                                               value="{{ $key  }}"
                                                        >
                                                        {{ $name }} Available
                                                        <i class="input-helper"></i><i class="input-helper"></i></label>
                                                </div>
                                            </div>
                                        </div>
                                        <section  style="display: none" id="{{ Str::snake($name, '_') }}_section" class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="">{{ $name }} Rate</label>
                                                        <input type="number" class="form-control"  name="{{ Str::snake($name, '_').'_rate' }}" >
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="">{{ $name }} Count</label>
                                                        <input type="number" class="form-control"  name="{{ Str::snake($name, '_').'_count' }}" >
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="">{{ $name }} Chargable at Occupancy</label>
                                                        <input type="number" class="form-control" name="{{ Str::snake($name, '_').'_occupancy' }}" placeholder="Occupancy in percentage">
                                                    </div>
                                                </div>
                                        </section>
                                    @endforeach



                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Hotel Facilities</label>
                                                @foreach($hotel_facilities as $key => $val)
                                                    <div class="form-check">
                                                        <label class="form-check-label">
                                                        <input type="checkbox" class="form-check-input" value="{{ $key }}" name="{{ Str::snake($val, '_').'_amenities' }}">
                                                            {{ $val }}
                                                        <i class="input-helper"></i></label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Room Inclusion</label>
                                                @foreach($room_inclusions as $key => $val)
                                                    <div class="form-check">
                                                        <label class="form-check-label">
                                                            <input type="checkbox" class="form-check-input" value="{{ $key }}" name="{{ Str::snake($val, '_').'_room_inclusion'}}">
                                                            {{ $val }}
                                                            <i class="input-helper"></i></label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>

                                    </div>
                                </section>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="{{ asset('/assets/vendors/js/vendor.bundle.base.js') }}"></script>
    <script src="{{ asset('/assets/vendors/jquery-steps/jquery.steps.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('assets/js/wizard.js') }}"></script>
    <script>
        $('#addMoreImageType').click(function(){
            $('#imageTypeBody').append(`
                 <div class="form-group">
                <div class="row">
                <div class="col-md-5">
                <label>Type</label>
                <select name="" id="" class="form-control">
                @foreach($images_video_categories as $id => $name)
                <option value="{{ $id }}">{{ $name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6">
            <label>Upload File</label>
            <input type="file" class="form-control" name="">
        </div>
        <div class="col-md-1">
            <button type="button" class="btn btn-danger btn-lg mt-3 deleteImageType">
                <i class="mdi mdi-trash-can"></i>
            </button>
        </div>
    </div>
</div>`)
        });
        $('#addMoreMenuType').click(function(){
          $('#MenuTypeBody').append(` <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-5">
                                                            <label>Type</label>
                                                            <select name="" id="" class="form-control">
                 @foreach($menu_sub_categories as $id => $name)
                  <option value="{{ $id }}">{{ $name }}</option>
                 @endforeach
                  </select>
              </div>
              <div class="col-md-6">
                  <label>Upload File</label>
                  <input type="file" class="form-control" name="">
              </div>
              <div class="col-md-1">
                  <button type="button" class="btn btn-danger btn-lg mt-3 deleteMenuType">
                      <i class="mdi mdi-trash-can"></i>
                  </button>
              </div>
          </div>
        </div>`);
        });

        // $(document).on("click",".deleteImageType",function() {
        //     $(this).parent().parent().remove();
        // });
        //
        // $(document).on("click",".deleteMenuType",function() {
        //     $(this).parent().parent().remove();
        // });
        // $(document).on("click",".deleteMenuType",function() {
        //     $(this).parent().parent().remove();
        // });


        $('.hotel_chargable_typeId').change(function() {
                let sectionDiv = $(this).attr("data");
                $('#'+sectionDiv).toggle();
        });
    </script>
@endsection
