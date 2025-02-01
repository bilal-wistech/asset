<?php
// dd($fine->type->name)
?>

@extends('layouts/default')

{{-- Page title --}}
@section('title')
    @if (Request::is('create'))
        {{ trans('general.add_fine') }}
    @else
        Edit Fine
    @endif

    @parent
@stop

{{-- Page content --}}
@section('content')
    <style>
        .input-group {
            padding-left: 0px !important;
        }

        .image-upload-container {
            padding: 20px;
            border: 2px dashed #ccc;
            border-radius: 5px;
            background-color: #f8f9fa;
        }

        .image-preview-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 15px;
        }

        .image-preview-wrapper {
            position: relative;
            width: 150px;
            height: 150px;
            border: 1px solid #ddd;
            border-radius: 4px;
            overflow: hidden;
        }

        .image-preview {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .remove-image {
            position: absolute;
            top: 5px;
            right: 5px;
            background: rgba(255, 0, 0, 0.7);
            color: white;
            border: none;
            border-radius: 50%;
            width: 25px;
            height: 25px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .remove-image:hover {
            background: rgba(255, 0, 0, 0.9);
        }

        .existing-image {
            border: 2px solid #28a745;
        }
    </style>
    <div class="modal fade" id="accidentModal" tabindex="-1" role="dialog" aria-labelledby="accidentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="accidentModalLabel">Fine Type</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('api.fine_type') }}" method="POST">
                        @csrf
                        <div class="alert alert-danger" id="modal_error_msg" style="display:none"></div>
                        <div class="form-group">
                            <label for="modal-name">{{ trans('general.name') }}:</label>
                            <input type="text" name="name" id="modal-name" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="modal-amount">{{ trans('general.amount') }}:</label>
                            <input type="text" name="amount" id="modal-amount" class="form-control" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        data-dismiss="modal">{{ trans('button.cancel') }}</button>
                    <button type="button" class="btn btn-primary" id="modal-save">{{ trans('general.save') }}</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Selected user Modal -->
    <div class="modal fade" id="SelecteduserModal" tabindex="-1" role="dialog" aria-labelledby="SelecteduserModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title text-center font-weight-bold" id="SelecteduserModalLabel">Driver For Selected
                        Asset</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p id="myselecteduser" class="text-center"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" id="goManuallyButton" class="btn btn-secondary" data-dismiss="modal">Go
                        Manually</button>
                    <button type="button" id="goWithSelectedUser" class="btn btn-primary">Go With Selected User</button>
                </div>
            </div>
        </div>
    </div>
    <!-- when no user found Modal -->
    <div style="display: none" class="modal fade" id="Usermodal" tabindex="-1" role="dialog"
        aria-labelledby="UsermodalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title text-center font-weight-bold" id="UsermodalLabel">Driver For Selected Asset</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="text-center text-danger">There is no user found for selected date and time.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" id="goManuallyButton" class="btn btn-primary" data-dismiss="modal">Go
                        Manually</button>

                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- left column -->
        <div class="col-md-9">
            <div class="box box-default">
                <div class="box-header with-border">
                    <!-- <h2 class="box-title">
                                                                                    @if (Request::is('create'))
    {{ trans('general.add_fine') }}
@else
    Edit Fine
    @endif
                                                                                    </h2> -->
                </div><!-- /.box-header -->
                <div class="box-body">
                    <div class="col-md-12">
                        <form class="form-horizontal" method="post" enctype="multipart/form-data"
                            action="{{ isset($fine) ? route('fines.update', $fine->id) : route('fines.store') }}"
                            autocomplete="off">
                            @csrf
                            <!-- Date/Time -->
                            {{-- <div class="form-group">
                                {{ Form::label('fine_date', trans('general.fine_date'), ['class' => 'col-md-3 control-label']) }}
                                <div class="col-md-7" style="display: flex; align-items: center;">
                                    <!-- Date and Time Input -->
                                    <input type="datetime-local" id="fine_date" class="form-control"
                                        placeholder="Select Date and Time (YYYY-MM-DD HH:MM)" name="fine_date"
                                        value="{{ isset($fine) ? Carbon::parse($fine->created_at)->format('Y-m-d\TH:i') : Carbon::now()->format('Y-m-d\TH:i') }}"
                                        style="flex: 1;">

                                    <!-- Seconds Dropdown -->
                                    <select id="fine_seconds" class="form-control mt-2 ml-2" name="fine_seconds"
                                        style="width: auto;">
                                        @for ($i = 0; $i < 60; $i++)
                                            <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}"
                                                {{ isset($fine) && Carbon::parse($fine->created_at)->format('s') == str_pad($i, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>
                                                {{ str_pad($i, 2, '0', STR_PAD_LEFT) }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>

                                <span id="asset-error" class="text-danger mt-2"
                                    style="display:block; width: 100%;"></span>
                            </div> --}}

                            <div class="form-group">
                                {{ Form::label('fine_date', 'Fine Date', ['class' => 'col-md-3 control-label']) }}
                                <div class="col-md-7 date"
                                    style="display: flex; align-items: center; flex-direction: column;">
                                    <div style="width: 100%; display: flex; align-items: center;">
                                        <!-- Date and Time Input (without seconds) -->
                                        <input type="datetime-local" id="fine_date" class="form-control"
                                            style="width: 100%;" placeholder="Select Date and Time (YYYY-MM-DD HH:MM)"
                                            name="fine_date"
                                            value="{{ isset($fine) ? Carbon::parse($fine->created_at)->format('Y-m-d\TH:i') : Carbon::now()->format('Y-m-d\TH:i') }}">
                                    </div>
                                    <!-- Error Message -->
                                    <span id="asset-error" class="text-danger mt-3"
                                        style="display:none; align-self: flex-start;"></span>
                                </div>
                            </div>

                            <!-- asset  -->
                            {{-- <div class="form-group">
                                <label for="asset_id"
                                    class="col-md-3 control-label">{{ trans('general.asset_id') }}</label>
                                <div class="col-md-7">
                                    <select name="asset_id" id="asset_id" class="form-control select2" required>
                                        <option value="">Select</option>
                                        @foreach ($assets as $asset)
                                            <option value="{{ $asset['id'] }}"
                                                {{ isset($fine) && $fine->asset && $fine->asset->id == $asset['id'] ? 'selected' : '' }}>
                                                {{ $asset['asset_tag'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div> --}}
                            <div class="form-group">
                                <label for="asset_id"
                                    class="col-md-3 control-label">{{ trans('general.asset_id') }}</label>
                                <div class="col-md-7">
                                    <select name="asset_id" id="asset_id" class="form-control select2" required>
                                        <option value="">Select</option>
                                        @foreach ($assets as $asset)
                                            <option value="{{ $asset->id }}"
                                                {{ isset($fine) && $fine->asset && $fine->asset->id == $asset->id ? 'selected' : '' }}>
                                                {{ $asset->asset_tag }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>


                            <!-- Users -->
                            <div class="form-group" @if(Request::is('create*')) style="display: none;" @endif>
                                <label for="user_id" class="col-md-3 control-label">{{ trans('general.users') }}</label>
                                <div class="col-md-7">
                                    {{ Form::select('user_id',
                                        [''=>'Select'] + $users,
                                        isset($fine) ? $fine->user_id : null,
                                        ['class' => 'form-control select2',
                                         'id' => 'user_id',
                                         'required',
                                         'style' => 'width: 100%;'
                                        ]
                                    ) }}
                                </div>
                            </div>

                            <!-- Fine Number -->
                            @if (Request::is('create*'))
                                <div style="display: none;"
                                    class="form-group {{ $errors->has('fine_number') ? 'error' : '' }}">
                                    {{ Form::label('fine_number', 'Fine Number', ['class' => 'col-md-3 control-label']) }}
                                    <div class="col-md-7">
                                        <input class="form-control" type="text" name="fine_number" id="fine_number"
                                            value="{{ isset($fine) ? $fine->fine_number : '' }}" />
                                        {!! $errors->first(
                                            'fine_number',
                                            '<span class="alert-msg" aria-hidden="true"><i class="fas fa-times" aria-hidden="true"></i> :message</span>',
                                        ) !!}
                                    </div>
                                </div>
                            @else
                                <div class="form-group {{ $errors->has('fine_number') ? 'error' : '' }}">
                                    {{ Form::label('fine_number', 'Fine Number', ['class' => 'col-md-3 control-label']) }}
                                    <div class="col-md-7">
                                        <input class="form-control" type="text" name="fine_number" id="fine_number"
                                            value="{{ isset($fine) ? $fine->fine_number : '' }}" />
                                        {!! $errors->first(
                                            'fine_number',
                                            '<span class="alert-msg" aria-hidden="true"><i class="fas fa-times" aria-hidden="true"></i> :message</span>',
                                        ) !!}
                                    </div>
                                </div>
                            @endif

                            <!-- fine type -->
                            @if (Request::is('create*'))
                                <div style="display: none;" class="form-group">
                                    <label for="fine_type"
                                        class="col-md-3 control-label">{{ trans('general.fine_type') }}
                                    </label>
                                    <div class="col-md-7 required">
                                        {{ Form::select('fine_type', isset($fine) ? [$fine->type->name] + $fine_type : ['' => 'Select'] + $fine_type, isset($fine) ? $fine->type->id : null, ['class' => 'form-control', 'id' => 'fine_type', 'required']) }}
                                        {!! $errors->first(
                                            'fine_type',
                                            '<span class="alert-msg" aria-hidden="true"><i class="fas fa-times" aria-hidden="true"></i> :message</span>',
                                        ) !!}
                                    </div>
                                    <div style="display: none;" class="col-md-1 col-sm-1 text-left">
                                        <button type="button" id="accidentmodel" class="btn btn-primary"
                                            data-toggle="modal" data-target="#accidentModal">
                                            New
                                        </button>
                                    </div>
                                </div>
                            @else
                                <div class="form-group">
                                    <label for="fine_type"
                                        class="col-md-3 control-label">{{ trans('general.fine_type') }}
                                    </label>
                                    <div class="col-md-7 required">
                                        {{ Form::select('fine_type', isset($fine) ? [$fine->type->name] + $fine_type : ['' => 'Select'] + $fine_type, isset($fine) ? $fine->type->id : null, ['class' => 'form-control', 'id' => 'fine_type', 'required']) }}
                                        {!! $errors->first(
                                            'fine_type',
                                            '<span class="alert-msg" aria-hidden="true"><i class="fas fa-times" aria-hidden="true"></i> :message</span>',
                                        ) !!}
                                    </div>
                                    <div class="col-md-1 col-sm-1 text-left">
                                        <button type="button" id="accidentmodel" class="btn btn-primary"
                                            data-toggle="modal" data-target="#accidentModal">
                                            New
                                        </button>
                                    </div>
                                </div>
                            @endif
                            <!-- note -->
                            @if (Request::is('create*'))
                                <div style="display: none;" class="form-group {{ $errors->has('note') ? 'error' : '' }}">
                                    {{ Form::label('note', trans('admin/hardware/form.notes'), ['class' => 'col-md-3 control-label']) }}
                                    <div class="col-md-7">
                                        <textarea class="col-md-6 form-control" id="note" name="note">{{ isset($fine) ? $fine->note : '' }} </textarea>
                                        {!! $errors->first(
                                            'note',
                                            '<span class="alert-msg" aria-hidden="true"><i class="fas fa-times"
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 aria-hidden="true"></i> :message</span>',
                                        ) !!}
                                    </div>
                                </div>
                            @else
                                <div class="form-group {{ $errors->has('note') ? 'error' : '' }}">
                                    {{ Form::label('note', trans('admin/hardware/form.notes'), ['class' => 'col-md-3 control-label']) }}
                                    <div class="col-md-7">
                                        <textarea class="col-md-6 form-control" id="note" name="note">{{ isset($fine) ? $fine->note : '' }} </textarea>
                                        {!! $errors->first(
                                            'note',
                                            '<span class="alert-msg" aria-hidden="true"><i class="fas fa-times"
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 aria-hidden="true"></i> :message</span>',
                                        ) !!}
                                    </div>
                                </div>
                            @endif


                            <!-- Amount -->
                            @if (Request::is('create*'))
                                <div style="display: none;"
                                    class="form-group {{ $errors->has('amount') ? 'error' : '' }}">
                                    {{ Form::label('amount', trans('general.amount'), ['class' => 'col-md-3 control-label']) }}
                                    <div class="col-md-7">
                                        <input class="form-control" type="number" name="amount" id="amount"
                                            value="{{ isset($fine) ? $fine->amount : '0' }}"
                                            {{ isset($fine) && $fine->amount != 0 ? 'readonly' : '' }} />

                                        {!! $errors->first(
                                            'amount',
                                            '<span class="alert-msg" aria-hidden="true"><i class="fas fa-times" aria-hidden="true"></i> :message</span>',
                                        ) !!}

                                    </div>
                                    <span id="amount-error" class="text-danger mt-2" style="display:none;">No amount
                                        found</span>
                                </div>
                            @else
                                <div class="form-group {{ $errors->has('amount') ? 'error' : '' }}">
                                    {{ Form::label('amount', trans('general.amount'), ['class' => 'col-md-3 control-label']) }}
                                    <div class="col-md-7">
                                        <input class="form-control" type="number" name="amount" id="amount"
                                            value="{{ isset($fine) ? $fine->amount : '0' }}"
                                            {{ isset($fine) && $fine->amount != 0 ? 'readonly' : '' }} />

                                        {!! $errors->first(
                                            'amount',
                                            '<span class="alert-msg" aria-hidden="true"><i class="fas fa-times" aria-hidden="true"></i> :message</span>',
                                        ) !!}

                                    </div>
                                    <span id="amount-error" class="text-danger mt-2" style="display:none;">No amount
                                        found</span>
                                </div>
                            @endif


                            <!-- location       -->
                            @if (Request::is('create*'))
                                <div style="display: none;" class="form-group">
                                    <label for="location"
                                        class="col-md-3 control-label">{{ trans('general.location') }}</label>
                                    <div class="col-md-7">
                                        {{ Form::select('location', isset($fine) ? [$fine->findLocation->name] + $location : ['' => 'Select'] + $location, isset($fine) ? $fine->findLocation->id : null, ['class' => 'form-control', 'id' => 'location', 'required']) }}
                                    </div>
                                </div>
                            @else
                                <div class="form-group">
                                    <label for="location"
                                        class="col-md-3 control-label">{{ trans('general.location') }}</label>
                                    <div class="col-md-7">
                                        {{ Form::select('location', isset($fine) ? [$fine->findLocation->name] + $location : ['' => 'Select'] + $location, isset($fine) ? $fine->findLocation->id : null, ['class' => 'form-control', 'id' => 'location', 'required']) }}
                                    </div>
                                </div>
                            @endif

                            <!-- image -->
                            {{-- @if (Request::is('create*'))
                                <div style="display: none;" class="form-group {{ $errors->has('fine_image') ? 'error' : '' }}">
                                    {{ Form::label('Fine Image', 'Fine Image', ['class' => 'col-md-3 control-label']) }}
                                    <div class="col-md-7">
                                        <input type="file" name="fine_image" id="fine_image">
                                    </div>
                                </div>
                            @else
                                <div class="form-group {{ $errors->has('fine_image') ? 'error' : '' }}">
                                    {{ Form::label('Fine Image', 'Fine Image', ['class' => 'col-md-3 control-label']) }}
                                    <div class="col-md-7">
                                        <input type="file" name="fine_image" id="fine_image">
                                    </div>
                                </div>
                            @endif --}}
                            @if (Request::is('create*'))
                                <div style="display: none;"
                                    class="form-group {{ $errors->has('fine_image') ? 'error' : '' }}">
                                @else
                                    <div class="form-group {{ $errors->has('fine_image') ? 'error' : '' }}">
                            @endif
                            {{ Form::label('Fine Image', 'Fine Images', ['class' => 'col-md-3 control-label']) }}
                            <div class="col-md-7">
                                <div class="image-upload-container">
                                    <!-- Hidden original input -->
                                    <input type="file" name="fine_image[]" id="fine_image" multiple accept="image/*"
                                        style="display: none;">

                                    <!-- Custom upload button -->
                                    <button type="button" class="btn btn-primary" id="uploadButton">
                                        <i class="fas fa-upload"></i> Choose Images
                                    </button>
                                    {{-- @dd($fine->fine_image) --}}
                                    <!-- Preview container -->
                                    <div class="image-preview-container mt-3" id="imagePreviewContainer">
                                        @if (isset($fine) && $fine->fine_image)
                                            <div id="existing-images">
                                                @php
                                                    // Split the comma-separated string into an array
                                                    $fineImages = explode(',', $fine->fine_image);
                                                @endphp
                                                @foreach ($fineImages as $image)
                                                    <div class="image-preview-wrapper">
                                                        <img src="{{ asset($image) }}" class="image-preview"
                                                            alt="Fine Image">
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Hidden input to track deleted images -->
                                    <input type="hidden" name="deleted_fine_images[]" id="deletedImages">

                                    <!-- Error messages -->
                                    @if ($errors->has('fine_image'))
                                        <span class="text-danger">{{ $errors->first('fine_image') }}</span>
                                    @endif
                                </div>
                            </div>
                    </div>
                    <div class="box-footer">
                        <a class="btn btn-link" href="{{ URL::previous() }}"> {{ trans('button.cancel') }}</a>
                        <button type="submit" class="btn btn-primary pull-right"><i class="fas fa-check icon-white"
                                aria-hidden="true"></i>
                            {{ trans('general.save') }}</button>
                    </div>
                    </form>
                </div> <!--/.col-md-12-->
            </div> <!--/.box-body-->

        </div> <!--/.box.box-default-->
    </div>
    </div>


@stop


@section('moar_scripts')

    <script>
        $(document).ready(function() {
            var username = '';
            var userId = '';
            var dateSelected = true;
            var assetSelected = false;
            $('#fine_date').on('change', function() {
                dateSelected = true;
                $('#asset-error').hide();
                if (assetSelected) {
                    sendAjaxRequest();
                }
            });
            $('#asset_id').on('change', function() {
                assetSelected = true;
                if (dateSelected) {
                    sendAjaxRequest();
                } else {
                    $('#asset-error').text('Please select a date and time first.').show();
                }
            });

            // function sendAjaxRequest() {
            //     var selectedDate = $('#fine_date').val();
            //     var selectedSeconds = $('#fine_seconds').val();
            //     var formattedDateTime = selectedDate + ':' + selectedSeconds;
            //     //alert(formattedDateTime);
            //     //return false;
            //     var selectedAssetId = $('#asset_id').val();
            //     $.ajax({
            //         url: '{{ route('fetch-fines') }}',
            //         type: 'GET',
            //         data: {
            //             fine_date: formattedDateTime,
            //             asset_id: selectedAssetId
            //         },
            //         success: function(response) {
            //             console.log(response.message);
            //             if (response.message === 'There is no user for Selected datetime.') {
            //                 $('#Usermodal').modal('show');
            //                 var $select = $('#user_id');
            //                 $select.empty();
            //                 $select.append($('<option>', {
            //                     value: '',
            //                     text: 'Select a user'
            //                 }));
            //                 $.each(response.users, function(id, username) {
            //                     $select.append($('<option>', {
            //                         value: id,
            //                         text: username
            //                     }));
            //                 });
            //             } else {
            //                 username = response.message.username || 'Unknown User';
            //                 userId = response.message.id || 'Unknown ID';
            //                 var text = username +
            //                     ' is the assigned driver for the selected asset on the chosen date and time.';
            //                 $('#myselecteduser').text(text);
            //                 $('#SelecteduserModal').modal('show');

            //             }
            //         },
            //         error: function(xhr) {
            //             console.log(xhr.responseText);
            //         }
            //     });
            // }
            function sendAjaxRequest() {
                var selectedDate = $('#fine_date').val(); // Get date and time without seconds
                var selectedAssetId = $('#asset_id').val();

                $.ajax({
                    url: '{{ route('fetch-fines') }}',
                    type: 'GET',
                    data: {
                        fine_date: selectedDate, // Send formatted date and time (without seconds)
                        asset_id: selectedAssetId
                    },
                    success: function(response) {
                        if (response.message === 'There is no user for Selected datetime.') {
                            // Show user modal if no user is found for the selected datetime
                            $('#Usermodal').modal('show');

                            var $select = $('#user_id');
                            $select.empty();
                            $select.append($('<option>', {
                                value: '',
                                text: 'Select a user'
                            }));

                            // Loop through response.users properly
                            $.each(response.users, function (id, user) {
                                $select.append($('<option>', {
                                    value: user.id, // Ensure 'id' is correctly referenced
                                    text: user.first_name + ' ' + user.last_name + ' - ' + user.username
                                }));
                            });
                        } else {
                            // Show success message in modal
                            fullName = response.users.first_name + ' ' + response.users.last_name;
                            username = response.message.username || 'Unknown User';
                            userId = response.message.id || 'Unknown ID';
                            var text = fullName + ' - ' + username +
                                ' is the assigned driver for the selected asset on the chosen date and time.';
                            $('#myselecteduser').text(text);
                            $('#SelecteduserModal').modal('show');
                        }
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });
            }
            $('#Usermodal').on('click', '#goManuallyButton', function() {
                $('#Usermodal').modal('hide');
                $('#fine_number, #fine_type, #amount, #location, #fine_image, #note, #user_id').closest(
                    '.form-group').css('display', 'block');
                $('.col-md-1.col-sm-1.text-left').css('display', 'block');
            });
            $('#SelecteduserModal').on('click', '#goManuallyButton', function() {
                $('#SelecteduserModal').modal('hide');
                $('#fine_number, #fine_type, #amount, #location, #fine_image, #note, #user_id').closest(
                    '.form-group').css('display', 'block');
                $('.col-md-1.col-sm-1.text-left').css('display', 'block');
            });
            $('#SelecteduserModal').on('click', '#goWithSelectedUser', function() {
                $('#SelecteduserModal').modal('hide');
                $('#user_id').empty();
                $('#user_id').append(new Option(username, userId));
                $('#fine_number, #fine_type, #amount, #location, #fine_image, #note, #user_id').closest(
                    '.form-group').css('display', 'block');
                $('.col-md-1.col-sm-1.text-left').css('display', 'block');
            });
        });
        //fine model for amont and name 
        //Accident Modal code
        $('#accidentmodel').on('click', function() {
            $('#accidentModal').css('display', 'block');
        });
        // Handle save button click
        $('#modal-save').on('click', function() {
            // Serialize form data
            var formData = {
                name: $('#modal-name').val(),
                amount: $('#modal-amount').val(),
                _token: $('input[name="_token"]').val()
            };

            // AJAX request to save data
            $.ajax({
                url: "{{ route('api.fine_type') }}",
                type: "POST",
                data: formData,
                success: function(response) {
                    if (response.status === 'success') {
                        //alert('Data saved successfully!');
                        var newOption = new Option(response.data.name, response.data.id, true, true);
                        $('#fine_type').append(newOption).trigger('change');

                        // Clear the modal fields
                        $('#modal-name').val('');
                        $('#modal-amount').val('');
                        $('#accidentModal').modal('hide');
                    } else {
                        $('#modal_error_msg').text(response.message).show();
                    }
                },
                error: function(xhr) {
                    var errors = xhr.responseJSON.errors;
                    var errorMessages = '';
                    $.each(errors, function(key, value) {
                        errorMessages += value[0] + '<br>';
                    });
                    $('#modal_error_msg').html(errorMessages).show();
                }
            });
        });
        $('.close, .btn-secondary').on('click', function() {
            $('#accidentModal').modal('hide');
        });
        // fine type code
        $('#fine_type').change(function() {
            var fineTypeId = $(this).val();
            $.ajax({
                url: '/get-fine-type-amount',
                type: 'GET',
                data: {
                    fine_type_id: fineTypeId
                },
                success: function(response) {
                    if (response.amount !== undefined) {
                        $('#amount').val(response.amount);
                        $('#amount-error').hide();
                    } else {
                        $('#amount').val(0);
                        $('#amount-error').show();
                    }
                },
                error: function() {
                    $('#amount').val(0);
                    $('#amount-error').text('Error occurred while fetching the amount').show();
                }
            });

        });
        document.addEventListener('DOMContentLoaded', function() {
            const fileInput = document.getElementById('fine_image');
            const uploadButton = document.getElementById('uploadButton');
            const previewContainer = document.getElementById('imagePreviewContainer');
            const deletedImagesInput = document.getElementById('deletedImages');
            const maxFileSize = 5 * 1024 * 1024; // 5MB
            const allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            const deletedImages = [];

            // Store files in a separate array to maintain state
            let currentFiles = new DataTransfer();

            // Initialize with existing files if any
            if (fileInput.files.length > 0) {
                Array.from(fileInput.files).forEach(file => {
                    currentFiles.items.add(file);
                });
            }

            uploadButton.addEventListener('click', () => {
                fileInput.click();
            });

            fileInput.addEventListener('change', function(e) {
                const files = Array.from(e.target.files);

                files.forEach(file => {
                    if (validateFile(file)) {
                        currentFiles.items.add(file);
                        createPreview(file, false);
                    }
                });

                // Update the file input with all current files
                fileInput.files = currentFiles.files;
            });

            // Modified handler for existing images
            document.querySelectorAll('.existing-image .remove-image').forEach(button => {
                button.addEventListener('click', function() {
                    const wrapper = this.closest('.image-preview-wrapper');
                    const imagePath = this.getAttribute('data-image');

                    deletedImages.push(imagePath);
                    deletedImagesInput.value = JSON.stringify(deletedImages);

                    wrapper.remove();
                });
            });

            function validateFile(file) {
                if (!allowedTypes.includes(file.type)) {
                    alert('Invalid file type. Please upload images only (JPEG, PNG, GIF).');
                    return false;
                }

                if (file.size > maxFileSize) {
                    alert('File is too large. Maximum size is 5MB.');
                    return false;
                }

                return true;
            }

            function createPreview(file, isExisting = false) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    const wrapper = document.createElement('div');
                    wrapper.className = 'image-preview-wrapper' + (isExisting ? ' existing-image' : '');

                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'image-preview';

                    const removeButton = document.createElement('button');
                    removeButton.className = 'remove-image';
                    removeButton.innerHTML = '×';
                    removeButton.onclick = function() {
                        wrapper.remove();

                        // Remove file from currentFiles
                        const updatedFiles = new DataTransfer();
                        Array.from(currentFiles.files)
                            .filter(f => f !== file)
                            .forEach(f => updatedFiles.items.add(f));

                        currentFiles = updatedFiles;
                        fileInput.files = currentFiles.files;
                    };

                    wrapper.appendChild(img);
                    wrapper.appendChild(removeButton);
                    previewContainer.appendChild(wrapper);
                };

                reader.readAsDataURL(file);
            }

            // Modified drag and drop handling
            const uploadContainer = document.querySelector('.image-upload-container');

            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                uploadContainer.addEventListener(eventName, preventDefaults, false);
            });

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            ['dragenter', 'dragover'].forEach(eventName => {
                uploadContainer.addEventListener(eventName, highlight, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                uploadContainer.addEventListener(eventName, unhighlight, false);
            });

            function highlight(e) {
                uploadContainer.style.border = '2px dashed #000';
            }

            function unhighlight(e) {
                uploadContainer.style.border = '2px dashed #ccc';
            }

            uploadContainer.addEventListener('drop', handleDrop, false);

            function handleDrop(e) {
                const dt = e.dataTransfer;
                const files = Array.from(dt.files);

                files.forEach(file => {
                    if (validateFile(file)) {
                        currentFiles.items.add(file);
                        createPreview(file, false);
                    }
                });

                fileInput.files = currentFiles.files;
            }
        });
        //end of fine type code
        $('.select2').select2();
    </script>
@stop
