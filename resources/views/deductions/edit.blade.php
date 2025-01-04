<?php
// dd($fine->type->name)
?>

@extends('layouts/default')

{{-- Page title --}}
@section('title')
    @if (isset($fine) && $fine->exists)
        Edit Deduction
    @else
        {{ trans('Add Deduction') }}
    @endif
    @parent
@stop

{{-- Page content --}}
@section('content')
    <style>
        .input-group {
            padding-left: 0px !important;
        }
    </style>
    <div class="modal fade" id="accidentModal" tabindex="-1" role="dialog" aria-labelledby="accidentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="accidentModalLabel">Deduction Reason</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('api.deduction_type') }}" method="POST">
                        @csrf
                        <div class="alert alert-danger" id="modal_error_msg" style="display:none"></div>
                        <div class="form-group">
                            <label for="modal-name">{{ trans('general.name') }}:</label>
                            <input type="text" name="name" id="modal-name" class="form-control">
                        </div>
                        {{-- <div class="form-group">
                            <label for="modal-amount">{{ trans('general.amount') }}:</label>
                    <input type="text" name="amount" id="modal-amount" class="form-control" required>
            </div> --}}
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
    <div class="row">
        <!-- left column -->
        <div class="col-md-9">
            <div class="box box-default">
                <div class="box-header with-border">
                    <!-- <h2 class="box-title">{{ trans('general.add_fine') }} </h2> -->
                </div><!-- /.box-header -->

                <div class="box-body">
                    <div class="col-md-12">

                        <form class="form-horizontal" method="post"
                            action="{{ isset($fine) ? route('deductions.update', $fine->id) : route('deductions.store') }}"
                            autocomplete="off">
                            @csrf

                            <!-- Date/Time -->
                            <div class="form-group">
                                {{ Form::label('fine_date', trans('Deduction Date'), ['class' => 'col-md-3 control-label']) }}
                                <div class="col-md-7 date" style="display: table" data-provide="datepicker"
                                    data-date-format="dd-mm-yyyy" data-autoclose="true">
                                    <input type="text" class="form-control" placeholder="Select Date (DD-MM-YYYY)"
                                        name="fine_date"
                                        value="{{ isset($fine) ? \Carbon\Carbon::parse($fine->deduction_date)->format('d-m-Y') : \Carbon\Carbon::now()->format('d-m-Y') }}">
                                    <span class="input-group-addon"><i class="fas fa-calendar"
                                            aria-hidden="true"></i></span>
                                </div>
                            </div>

                            <!-- fine type -->

                            <div class="form-group">
                                <label for="fine_type" class="col-md-3 control-label">{{ trans('Reasons') }}
                                </label>
                                <div class="col-md-7 required">
                                    {{ Form::select('fine_type', isset($fine) ? [$fine->type->name] + $fine_type : ['' => 'Select'] + $fine_type, isset($fine) ? $fine->type->id : null, ['class' => 'form-control', 'required', 'id' => 'fine_type']) }}
                                    {!! $errors->first(
                                        'fine_type',
                                        '<span class="alert-msg" aria-hidden="true"><i
                                                                                                                                                    class="fas fa-times" aria-hidden="true"></i> :message</span>',
                                    ) !!}
                                </div>
                                <div class="col-md-1 col-sm-1 text-left">
                                    <button type="button" id="accidentmodel" class="btn btn-primary" data-toggle="modal"
                                        data-target="#accidentModal">
                                        New
                                    </button>
                                </div>
                            </div>

                            <!-- Users -->

                            <div class="form-group">
                                <label for="user_id" class="col-md-3 control-label">{{ trans('general.users') }}</label>
                                <div class="col-md-7">
                                    {{ Form::select('user_id', isset($fine) ? [$fine->user->id => $fine->user->username] + $users : ['' => 'Select'] + $users, isset($fine) ? $fine->user->id : null, ['class' => 'form-control select2', 'id' => 'user_id', 'required', 'style' => 'width: 100%;']) }}
                                </div>
                            </div>



                            <!-- Amount -->
                            <div class="form-group {{ $errors->has('amount') ? 'error' : '' }}">
                                {{ Form::label('amount', trans('general.amount'), ['class' => 'col-md-3 control-label']) }}
                                <div class="col-md-7">
                                    <input class="form-control" type="number" name="amount" id="amount"
                                        value="{{ isset($fine) ? $fine->amount : '' }}" step="0.01" />
                                    {!! $errors->first(
                                        'amount',
                                        '<span class="alert-msg" aria-hidden="true"><i
                                                                                                                                                    class="fas fa-times" aria-hidden="true"></i> :message</span>',
                                    ) !!}
                                </div>
                            </div>

                            <!-- location       -->

                            {{-- <div class="form-group">
                            <label for="location" class="col-md-3 control-label">{{ trans('general.location') }}</label>
                        <div class="col-md-7">
                            {{ Form::select('location', isset($fine) ? array($fine->findLocation->name)  + $location : ['' => 'Select'] + $location, isset($fine) ? $fine->findLocation->id : null , ['class' => 'form-control', 'required']) }}
                        </div>
                </div> --}}

                            <!-- image -->
                            {{-- <div class="form-group {{ $errors->has('note') ? 'error' : '' }}">
                {{ Form::label('Fine Image', 'Fine Image', array('class' => 'col-md-3 control-label')) }}
                <div class="col-md-7">
                    <input type="file" name="fine_image" id="fine_image">
                </div>
            </div> --}}

                            <!-- note -->

                            <div class="form-group {{ $errors->has('note') ? 'error' : '' }}">
                                {{ Form::label('Description', trans('admin/hardware/form.notes'), ['class' => 'col-md-3 control-label']) }}
                                <div class="col-md-7">
                                    <textarea class="col-md-6 form-control" id="note" name="note">{{ isset($fine) ? $fine->note : '' }}
                    </textarea>
                                    {!! $errors->first(
                                        'note',
                                        '<span class="alert-msg" aria-hidden="true"><i class="fas fa-times"
                                                                                                                                        aria-hidden="true"></i> :message</span>',
                                    ) !!}
                                </div>
                            </div>

                            <div class="box-footer">
                                <a class="btn btn-link" href="{{ URL::previous() }}"> {{ trans('button.cancel') }}</a>
                                <button type="submit" class="btn btn-primary pull-right"><i class="fas fa-check icon-white"
                                        aria-hidden="true"></i> {{ trans('general.save') }}</button>
                            </div>
                        </form>
                    </div>
                    <!--/.col-md-12-->
                </div>
                <!--/.box-body-->

            </div>
            <!--/.box.box-default-->
        </div>
    </div>

@stop

@section('moar_scripts')

    <script>
        $('.select2').select2();
        $('#modal-save').on('click', function() {

            // Serialize form data
            var formData = {
                name: $('#modal-name').val(),
                _token: $('input[name="_token"]').val()
            };

            // AJAX request to save data
            $.ajax({
                url: "{{ route('api.deduction_type') }}",
                type: "POST",
                data: formData,
                success: function(response) {
                    if (response.status === 'success') {
                        //console.log(response.data);
                        //alert('Data saved successfully!');
                        var newOption = new Option(response.data.name, response.data.id, true, true);
                        $('#fine_type').append(newOption).trigger('change');
                        // Clear the modal fields
                        $('#modal-name').val('');
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
    </script>

@stop
