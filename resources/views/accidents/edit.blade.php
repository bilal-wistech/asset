<?php
// dd($fine->type->name)
?>

@extends('layouts/default')

{{-- Page title --}}
@section('title')
    @if (Request::is('create-accident*'))
        {{ trans('general.add_accident') }}
    @else
        Edit Accident
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

        .slider-toggle {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            width: 60px;
            height: 34px;
            background-color: #ccc;
            outline: none;
            border-radius: 34px;
            transition: 0.4s;
            position: relative;
        }

        .slider-toggle:checked {
            background-color: #4CAF50;
        }

        .slider-toggle:before {
            content: "";
            position: absolute;
            top: 4px;
            left: 4px;
            width: 26px;
            height: 26px;
            border-radius: 50%;
            background-color: white;
            transition: 0.4s;
        }

        .slider-toggle:checked:before {
            transform: translateX(26px);
        }

        .file-upload-container {
            border: 2px dashed #ccc;
            padding: 20px;
            text-align: center;
        }

        .file-preview-wrapper {
            display: inline-block;
            margin: 10px;
            position: relative;
        }

        .file-preview {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .remove-file {
            position: absolute;
            top: -10px;
            right: -10px;
            background: red;
            color: white;
            border: none;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            cursor: pointer;
        }
    </style>
    <!-- Modal -->
    <div class="modal fade" id="accidentModal" tabindex="-1" role="dialog" aria-labelledby="accidentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="accidentModalLabel">Accident Type</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('api.accident_type') }}" method="POST">
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
    <!-- /.modal-dialog -->

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
                                                                                                                        @if (Request::is('create-accident*'))
    {{ trans('general.add_accident') }}
@else
    Edit Accident
    @endif
                                                                                                                        </h2> -->
                </div><!-- /.box-header -->
                <div class="box-body">
                    <div class="col-md-12">
                        <form class="form-horizontal" method="post" enctype="multipart/form-data"
                            action="{{ isset($fine) ? route('accidents.update', $fine->id) : route('accidents.store') }}"
                            autocomplete="off">
                            @csrf
                            <div class="form-group">
                                {{ Form::label('accident_date', 'Accident Date', ['class' => 'col-md-3 control-label']) }}
                                <div class="col-md-7 date"
                                    style="display: flex; align-items: center; flex-direction: column;">
                                    <div style="width: 100%; display: flex; align-items: center;">
                                        <!-- Date and Time Input (without seconds) -->
                                        <input type="datetime-local" id="accident_date" class="form-control"
                                            style="width: 100%;" placeholder="Select Date and Time (YYYY-MM-DD HH:MM)"
                                            name="accident_date"
                                            value="{{ isset($fine) ? Carbon::parse($fine->created_at)->format('Y-m-d\TH:i') : Carbon::now()->format('Y-m-d\TH:i') }}">
                                    </div>
                                    <!-- Error Message -->
                                    <span id="asset-error" class="text-danger mt-3"
                                        style="display:none; align-self: flex-start;"></span>
                                </div>
                            </div>
                            <!-- asset  -->
                            <div class="form-group">
                                <label for="asset_id"
                                    class="col-md-3 control-label">{{ trans('general.asset_id') }}</label>
                                <div class="col-md-7">
                                    {{ Form::select('asset_id', isset($fine) ? [$fine->asset->id => $fine->asset->asset_tag . '  ' . $fine->asset->name] + $assets : ['' => 'Select'] + $assets, isset($fine) ? $fine->asset->id : null, ['class' => 'form-control select2', 'id' => 'asset_id', 'required']) }}
                                </div>
                            </div>

                            <!-- Users -->
                            @if (Request::is('create-accident*'))
                                <div class="form-group" style="display: none;">
                                    <label for="user_id"
                                        class="col-md-3 control-label">{{ trans('general.users') }}</label>
                                    <div class="col-md-7">
                                        {{ Form::select('user_id', isset($fine) ? [$fine->user->username] + $users : ['' => 'Select'] + $users, isset($fine) ? $fine->user->id : null, ['class' => 'form-control  select2', 'id' => 'user_id', 'required', 'style' => 'width: 100%;']) }}
                                    </div>
                                </div>
                            @else
                                <div class="form-group">
                                    <label for="user_id"
                                        class="col-md-3 control-label">{{ trans('general.users') }}</label>
                                    <div class="col-md-7">
                                        {{ Form::select('user_id', isset($fine) ? [$fine->user->username] + $users : ['' => 'Select'] + $users, isset($fine) ? $fine->user->id : null, ['class' => 'form-control  select2', 'id' => 'user_id', 'required', 'style' => 'width: 100%;']) }}
                                    </div>
                                </div>
                            @endif
                            @if (Request::is('create-accident*'))
                                <!-- Fine Number -->
                                <div style="display: none;"
                                    class="form-group {{ $errors->has('accident_number') ? 'error' : '' }}">
                                    {{ Form::label('accident_number', 'Claim Number', ['class' => 'col-md-3 control-label']) }}
                                    <div class="col-md-7">
                                        <input class="form-control" type="text" name="accident_number"
                                            id="accident_number"
                                            value="{{ isset($fine) ? $fine->accident_number : '' }}" />
                                        {!! $errors->first(
                                            'accident_number',
                                            '<span class="alert-msg" aria-hidden="true"><i class="fas fa-times" aria-hidden="true"></i> :message</span>',
                                        ) !!}
                                    </div>
                                </div>
                            @else
                                <div class="form-group {{ $errors->has('accident_number') ? 'error' : '' }}">
                                    {{ Form::label('accident_number', 'Claim Number', ['class' => 'col-md-3 control-label']) }}
                                    <div class="col-md-7">
                                        <input class="form-control" type="text" name="accident_number"
                                            id="accident_number"
                                            value="{{ isset($fine) ? $fine->accident_number : '' }}" />
                                        {!! $errors->first(
                                            'accident_number',
                                            '<span class="alert-msg" aria-hidden="true"><i class="fas fa-times" aria-hidden="true"></i> :message</span>',
                                        ) !!}
                                    </div>
                                </div>
                            @endif
                            <!-- location       -->
                            @if (Request::is('create-accident*'))
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
                            <!-- Responsibility -->

                            @if (Request::is('create-accident*'))
                                <div style="display: none;" class="form-group">
                                    <label for="responsibility" class="col-md-3 control-label">Responsibility</label>
                                    <div class="col-md-7">
                                        {{ Form::select(
                                            'responsibility',
                                            [
                                                '' => 'Select', // Adding a "Select" option with an empty value
                                                'driver mistake' => 'Driver mistake',
                                                'third party mistake' => 'Third party mistake',
                                            ],
                                            null,
                                            ['class' => 'form-control', 'id' => 'responsibility', 'required'],
                                        ) }}
                                    </div>
                                </div>
                            @else
                                <div class="form-group">
                                    <label for="responsibility" class="col-md-3 control-label">Responsibility</label>
                                    <div class="col-md-7">
                                        {{ Form::select(
                                            'responsibility',
                                            [
                                                '' => 'Select',
                                                'driver mistake' => 'Driver Mistake',
                                                'third party mistake' => 'Third Party Mistake',
                                            ],
                                            old('responsibility', isset($fine) ? $fine->responsibility : null), // This retains the selected value when editing
                                            ['class' => 'form-control', 'id' => 'responsibility', 'required'],
                                        ) }}
                                    </div>
                                </div>
                            @endif

                            <!-- Responsibility Amount-->
                            @if (Request::is('create-accident*'))
                                <div style="display: none;"
                                    class="form-group {{ $errors->has('claim_opening') ? 'error' : '' }}">
                                    <label for="claim_opening" class="col-md-3 control-label">Claim
                                        Opening</label>
                                    <div class="col-md-7">
                                        <input class="form-control" type="number" name="claim_opening"
                                            id="claim_opening" value="{{ $fine->claim_opening ?? 0 }}" />
                                        {!! $errors->first(
                                            'claim_opening',
                                            '<span class="alert-msg" aria-hidden="true"><i class="fas fa-times" aria-hidden="true"></i> :message</span>',
                                        ) !!}
                                    </div>
                                    <span id="claim_opening-error" class="text-danger mt-2" style="display:none;">No
                                        Claim Opening found</span>
                                </div>
                            @else
                                <div class="form-group {{ $errors->has('claim_opening') ? 'error' : '' }}">
                                    <label for="claim_opening" class="col-md-3 control-label">Claim
                                        Opening</label>
                                    <div class="col-md-7">
                                        <input class="form-control" type="number" name="claim_opening"
                                            id="claim_opening" value="{{ $fine->claim_opening ?? 0 }}" />
                                        {!! $errors->first(
                                            'claim_opening',
                                            '<span class="alert-msg" aria-hidden="true"><i class="fas fa-times" aria-hidden="true"></i> :message</span>',
                                        ) !!}
                                    </div>
                                    <span id="claim_opening-error" class="text-danger mt-2" style="display:none;">No
                                        Claim Opening found</span>
                                </div>
                            @endif
                            <!-- Damages Amount-->
                            @if (Request::is('create-accident*'))
                                <div style="display: none;"
                                    class="form-group {{ $errors->has('damages_amount') ? 'error' : '' }}">
                                    <label for="damages_amount" class="col-md-3 control-label">Damages Amount</label>
                                    <div class="col-md-7">
                                        <input class="form-control" type="number" name="damages_amount"
                                            id="damages_amount" value="{{ $fine->damages_amount ?? 0 }}" />
                                        {!! $errors->first(
                                            'damages_amount',
                                            '<span class="alert-msg" aria-hidden="true"><i class="fas fa-times" aria-hidden="true"></i> :message</span>',
                                        ) !!}
                                    </div>
                                    <span id="damages_amount-error" class="text-danger mt-2" style="display:none;">No
                                        Claim Opening found</span>
                                </div>
                            @else
                                <div class="form-group {{ $errors->has('damages_amount') ? 'error' : '' }}">
                                    <label for="damages_amount" class="col-md-3 control-label">Damages Amount</label>
                                    <div class="col-md-7">
                                        <input class="form-control" type="number" name="damages_amount"
                                            id="damages_amount" value="{{ $fine->damages_amount ?? 0 }}" />
                                        {!! $errors->first(
                                            'damages_amount',
                                            '<span class="alert-msg" aria-hidden="true"><i class="fas fa-times" aria-hidden="true"></i> :message</span>',
                                        ) !!}
                                    </div>
                                    <span id="damages_amount-error" class="text-danger mt-2" style="display:none;">No
                                        Damages Amount found</span>
                                </div>
                            @endif
                            @if (Request::is('create-accident*'))
                                <div style="display: none;"
                                    class="form-group {{ $errors->has('claimable') ? 'error' : '' }}">
                                    <label for="claimable" class="col-md-3 control-label">Claimable</label>
                                    <div class="col-md-7">
                                        <!-- Hidden input for unchecked value -->
                                        <input type="hidden" name="claimable" value="0">
                                        <!-- Checkbox for checked value -->
                                        <input class="slider-toggle" type="checkbox" name="claimable" id="claimable"
                                            value="1" {{ isset($fine) && $fine->claimable == 1 ? 'checked' : '' }} />

                                        {!! $errors->first(
                                            'claimable',
                                            '<span class="alert-msg" aria-hidden="true"><i class="fas fa-times" aria-hidden="true"></i> :message</span>',
                                        ) !!}
                                    </div>
                                    <span id="claimable-error" class="text-danger mt-2" style="display:none;">No
                                        Claimable found</span>
                                </div>
                            @else
                                <div class="form-group {{ $errors->has('claimable') ? 'error' : '' }}">
                                    <label for="claimable" class="col-md-3 control-label">Claimable</label>
                                    <div class="col-md-7">
                                        <!-- Hidden input for unchecked value -->
                                        <input type="hidden" name="claimable" value="0">
                                        <!-- Checkbox for checked value -->
                                        <input class="slider-toggle" type="checkbox" name="claimable" id="claimable"
                                            value="1" {{ isset($fine) && $fine->claimable == 1 ? 'checked' : '' }} />

                                        {!! $errors->first(
                                            'claimable',
                                            '<span class="alert-msg" aria-hidden="true"><i class="fas fa-times" aria-hidden="true"></i> :message</span>',
                                        ) !!}
                                    </div>
                                    <span id="claimable-error" class="text-danger mt-2" style="display:none;">No
                                        Claimable found</span>
                                </div>
                            @endif

                            <!-- note -->
                            @if (Request::is('create-accident*'))
                                <div style="display: none;" class="form-group {{ $errors->has('note') ? 'error' : '' }}">
                                    {{ Form::label('note', trans('admin/hardware/form.notes'), ['class' => 'col-md-3 control-label']) }}
                                    <div class="col-md-7">
                                        <textarea class="col-md-6 form-control" id="note" name="note">{{ isset($fine) ? $fine->note : '' }} </textarea>
                                        {!! $errors->first(
                                            'note',
                                            '<span class="alert-msg" aria-hidden="true"><i class="fas fa-times" aria-hidden="true"></i> :message</span>',
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
                                            '<span class="alert-msg" aria-hidden="true"><i class="fa fa-times"                                                       aria-hidden="true"></i> :message</span>',
                                        ) !!}
                                    </div>
                                </div>
                            @endif
                            @if (Request::is('create-accident*'))
                                <div style="display: none;"
                                    class="form-group {{ $errors->has('accident_image') ? 'error' : '' }}">
                                @else
                                    <div class="form-group {{ $errors->has('accident_image') ? 'error' : '' }}">
                            @endif
                            {{ Form::label('Accident Image', 'Accident Images', ['class' => 'col-md-3 control-label']) }}
                            <div class="col-md-7">
                                <div class="image-upload-container">
                                    <!-- Hidden original input -->
                                    <input type="file" name="accident_image[]" id="accident_image" multiple
                                        accept="image/*" style="display: none;">

                                    <!-- Custom upload button -->
                                    <button type="button" class="btn btn-primary" id="uploadButton">
                                        <i class="fas fa-upload"></i> Choose Images
                                    </button>
                                    {{-- @dd($fine->accident_image) --}}
                                    <!-- Preview container -->
                                    <div class="image-preview-container mt-3" id="imagePreviewContainer">
                                        @if (isset($fine) && $fine->accident_image)
                                            <div id="existing-images">
                                                @php
                                                    // Split the comma-separated string into an array
                                                    $fineImages = explode(',', $fine->accident_image);
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
                                    <input type="hidden" name="deleted_accident_images[]" id="deletedImages">

                                    <!-- Error messages -->
                                    @if ($errors->has('accident_image'))
                                        <span class="text-danger">{{ $errors->first('accident_image') }}</span>
                                    @endif
                                </div>
                            </div>
                            @if (Request::is('create-accident*'))
                                <div style="display: none;"
                                    class="form-group {{ $errors->has('relevant_files') ? 'error' : '' }}">
                                @else
                                    <div class="form-group {{ $errors->has('relevant_files') ? 'error' : '' }}">
                            @endif
                            {{ Form::label('Relevant Files', 'Relevant Files', ['class' => 'col-md-3 control-label']) }}
                            <div class="col-md-7">
                                <div class="file-upload-container">
                                    <!-- Hidden original input -->
                                    <input type="file" name="relevant_files[]" id="relevant_files" multiple
                                        style="display: none;">

                                    <!-- Custom upload button -->
                                    <button type="button" class="btn btn-primary" id="relevantFilesUploadButton">
                                        <i class="fas fa-upload"></i> Choose Files
                                    </button>

                                    <!-- Preview container -->
                                    <div class="file-preview-container mt-3" id="filePreviewContainer">
                                        @if (isset($fine) && $fine->relevant_files)
                                            <div id="existing-files">
                                                @php
                                                    $relevantFiles = explode(',', $fine->relevant_files);
                                                @endphp
                                                @foreach ($relevantFiles as $file)
                                                    <div class="file-preview-wrapper">
                                                        <div class="file-preview">
                                                            <i class="fas fa-file"></i>
                                                            <span>{{ basename($file) }}</span>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Hidden input to track deleted files -->
                                    <input type="hidden" name="deleted_relevant_files[]" id="deletedFiles">

                                    <!-- Error messages -->
                                    @if ($errors->has('relevant_files'))
                                        <span class="text-danger">{{ $errors->first('relevant_files') }}</span>
                                    @endif
                                </div>
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
            $('#responsibility').change(function() {
                var responsibilityValue = $(this).val();

                if (responsibilityValue === 'driver mistake') {
                    var assetId = $('#asset_id').val();

                    if (assetId) {
                        $.ajax({
                            url: '/get-accident-minimum-payment',
                            method: 'GET',
                            data: {
                                asset_id: assetId
                            },
                            success: function(response) {
                                if (response.success) {
                                    $('#claim_opening').val(response
                                        .accident_minimum_payment);
                                    console.log(response
                                        .message
                                    ); // This will now show the success message properly
                                } else {
                                    console.log(response
                                        .message); // This will show error message if not found
                                }
                            },
                            error: function() {
                                console.log('Error retrieving Claim Opening.');
                            }
                        });
                    } else {
                        alert('Please select an asset.');
                    }
                }

                if (responsibilityValue === 'third party mistake') {
                    $('#claim_opening').val(0);
                }
            });

        });



        $(document).ready(function() {
            var username = '';
            var userId = '';
            var dateSelected = true;
            var assetSelected = false;
            $('#accident_date').on('change', function() {
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
            function sendAjaxRequest() {
                var selectedDate = $('#accident_date').val(); // Get date and time without seconds
                var selectedAssetId = $('#asset_id').val();

                $.ajax({
                    url: '{{ route('fetch-accidents') }}',
                    type: 'GET',
                    data: {
                        accident_date: selectedDate, // Send formatted date and time (without seconds)
                        asset_id: selectedAssetId
                    },
                    success: function(response) {
                        console.log(response.message);
                        if (response.message === 'There is no user for Selected datetime.') {
                            // Show user modal if no user is found for the selected datetime
                            $('#Usermodal').modal('show');
                            var $select = $('#user_id');
                            $select.empty();
                            $select.append($('<option>', {
                                value: '',
                                text: 'Select a user'
                            }));
                            $.each(response.users, function(id, username) {
                                $select.append($('<option>', {
                                    value: id,
                                    text: username
                                }));
                            });
                        } else {
                            // Show success message in modal
                            username = response.message.username || 'Unknown User';
                            userId = response.message.id || 'Unknown ID';
                            var text = username +
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
                $('#claim_opening,#accident_number, #fine_type, #amount, #location, #accident_image, #note, #user_id,#responsibility,#damages_amount,#claimable,#relevant_files')
                    .closest(
                        '.form-group').css('display', 'block');
                $('.col-md-1.col-sm-1.text-left').css('display', 'block');
            });
            $('#SelecteduserModal').on('click', '#goManuallyButton', function() {
                $('#SelecteduserModal').modal('hide');
                $('#claim_opening,#accident_number,#responsibility, #fine_type, #amount, #location, #accident_image, #note, #user_id,#damages_amount,#claimable,#relevant_files')
                    .closest(
                        '.form-group').css('display', 'block');
                $('.col-md-1.col-sm-1.text-left').css('display', 'block');
            });
            $('#SelecteduserModal').on('click', '#goWithSelectedUser', function() {
                $('#SelecteduserModal').modal('hide');
                $('#user_id').empty();
                $('#user_id').append(new Option(username, userId));
                $('#claim_opening,#accident_number,#responsibility, #fine_type, #amount, #location, #accident_image, #note, #user_id,#damages_amount,#claimable,#relevant_files')
                    .closest(
                        '.form-group').css('display', 'block');
                $('.col-md-1.col-sm-1.text-left').css('display', 'block');
            });
        });
        // Accident type code
        $('#fine_type').change(function() {
            var fineTypeId = $(this).val();
            $.ajax({
                url: '/get-accident-type-amount',
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
                url: "{{ route('api.accident_type') }}",
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
        document.addEventListener('DOMContentLoaded', function() {
            const fileInput = document.getElementById('accident_image');
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
                    console.log('Invalid file type. Please upload images only (JPEG, PNG, GIF).');
                    return false;
                }

                if (file.size > maxFileSize) {
                    console.log('File is too large. Maximum size is 5MB.');
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
        document.addEventListener('DOMContentLoaded', function() {
            const fileInput = document.getElementById('relevant_files');
            const uploadButton = document.getElementById('relevantFilesUploadButton');
            const previewContainer = document.getElementById('filePreviewContainer');
            const deletedFilesInput = document.getElementById('deletedFiles');
            const maxFileSize = 10 * 1024 * 1024; // 10MB
            const deletedFiles = [];

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

            // Handler for existing files
            document.querySelectorAll('.existing-file .remove-file').forEach(button => {
                button.addEventListener('click', function() {
                    const wrapper = this.closest('.file-preview-wrapper');
                    const filePath = this.getAttribute('data-file');

                    deletedFiles.push(filePath);
                    deletedFilesInput.value = JSON.stringify(deletedFiles);

                    wrapper.remove();
                });
            });

            function validateFile(file) {
                if (file.size > maxFileSize) {
                    alert('File is too large. Maximum size is 10MB.');
                    return false;
                }

                return true;
            }

            function createPreview(file, isExisting = false) {
                const wrapper = document.createElement('div');
                wrapper.className = 'file-preview-wrapper' + (isExisting ? ' existing-file' : '');

                const preview = document.createElement('div');
                preview.className = 'file-preview';

                const icon = document.createElement('i');
                icon.className = 'fas fa-file';

                const fileName = document.createElement('span');
                fileName.textContent = file.name;

                const removeButton = document.createElement('button');
                removeButton.className = 'remove-file';
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

                preview.appendChild(icon);
                preview.appendChild(fileName);
                wrapper.appendChild(preview);
                wrapper.appendChild(removeButton);
                previewContainer.appendChild(wrapper);
            }

            // Drag and drop handling
            const uploadContainer = document.querySelector('.file-upload-container');

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
        //end of accident type code
        $('.select2').select2();
    </script>
@stop
