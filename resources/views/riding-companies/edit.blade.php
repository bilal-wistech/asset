<?php
//dd($fine->type->name);
?>

@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Update Riding Companies
@stop

{{-- Page content --}}
@section('content')

    <div class="row">
        <!-- left column -->
        <div class="col-md-9">
            <div class="box box-default">
                <div class="box-header with-border">
                    <!-- <h2 class="box-title">
                                                                                                                                                                                                                            
                                                                                                                                    
                                                                                                                                </h2> -->
                </div><!-- /.box-header -->
                <div class="box-body">
                    <div class="col-md-12">
                        <form class="form-horizontal" method="POST" enctype="multipart/form-data"
                            action="{{ route('riding-companies.update', $riding_company->id) }}" autocomplete="off">
                            @csrf
                            @method('PUT')
                            {{-- @dd($riding_company) --}}
                            <!-- Name Field -->
                            <div class="form-group row">
                                <label for="name" class="col-sm-2 col-form-label">Name</label>
                                <div class="col-sm-10">
                                    <input type="text" id="name" class="form-control" placeholder="Enter Name"
                                        name="name" value="{{ $riding_company->name ?? '' }}">
                                </div>
                            </div>

                            <!-- Status Dropdown -->
                            <div class="form-group row">
                                <label for="status" class="col-sm-2 col-form-label">Status</label>
                                <div class="col-sm-10">
                                    <select id="status" name="status" class="form-control">
                                        <option value="active"
                                            {{ ($riding_company->status ?? '') == 'active' ? 'selected' : '' }}>Active
                                        </option>
                                        <option value="inactive"
                                            {{ ($riding_company->status ?? '') == 'inactive' ? 'selected' : '' }}>Inactive
                                        </option>
                                        {{-- <option value="active">Active</option>
                                        <option value="inactive">Inactive</option> --}}
                                    </select>
                                </div>
                            </div>



                            <div class="box-footer">
                                <a class="btn btn-link" href="{{ URL::previous() }}"> {{ trans('button.cancel') }}</a>
                                <button type="submit" class="btn btn-primary pull-right"><i class="fas fa-check icon-white"
                                        aria-hidden="true"></i>
                                    {{ trans('Update') }}</button>
                            </div>
                        </form>
                    </div> <!--/.col-md-12-->
                </div> <!--/.box-body-->

            </div> <!--/.box.box-default-->
        </div>
    </div>

@stop


@section('moar_scripts')
@stop
