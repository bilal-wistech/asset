
@extends('layouts/edit-form', [
'createText' => trans('admin/insurance/general.create') ,
'updateText' => trans('admin/insurance/general.update'),
'helpPosition' => 'right',
'helpText' => trans('help.categories'),
'topSubmit' => 'true',
'formAction' => (isset($item->id)) ? route('insurance.update', ['insurance_id' => $item->id]) :
route('insurance.store'),
])
<style>
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
</style>
@section('inputFields')


<div class="form-group ">
    <label for="asset_id" class="col-md-3 control-label">{{ trans('general.asset_id') }}</label>
    <div class="col-md-6 col-sm-12">
        {{ Form::select('asset_id', [''=>'Select']+ $assets ,$item->asset_id, ['id' => 'asset_id', 'class' => 'form-control serchable']) }}
    </div>
</div>
<div class="form-group ">
    <label for="recovery_number" class="col-md-3 control-label">Recovery Number</label>
    <div class="col-md-6 col-sm-12">
        {{ Form::text('recovery_number', $item->recovery_number, ['class' => 'form-control', 'placeholder' => 'Enter Recovery Phone Number', 'required']) }}
    </div>
</div>


<div class="form-group ">
    <label for="vendor_id" class="col-md-3 control-label">{{ trans('general.vendor_id') }}</label>
    <div class="col-md-6 col-sm-12">
        {{ Form::select('vendor_id', [''=>'Select']+$suppliers ,$item->vendor_id, ['id' => 'vendor_id', 'class' => 'form-control serchable']) }}
    </div>
    <div class="text-left">
        @can('create', \App\Models\AssetModel::class)
            @if ((!isset($hide_new)) || ($hide_new!='true'))
                <a href='{{ route('modal.show', 'vendor') }}' data-toggle="modal"  data-target="#createModal" data-select='model_select_id' class="btn btn-sm btn-primary">{{ trans('button.new') }}</a>
                <span class="mac_spinner" style="padding-left: 10px; color: green; display:none; width: 30px;">
                    <i class="fas fa-spinner fa-spin" aria-hidden="true"></i>
                </span>
            @endif
        @endcan
    </div>
</div>

<div class="form-group " style=" display:flex">
    <label for="insurance_date" class="col-md-3 control-label">{{ trans('general.insaurance_id') }}</label>
    <div class="col-md-3 text-left">
        
        {{ Form::text('ins_id', $item->ins_id, ['class' => 'form-control', 'placeholder' => 'Enter Id', 'required']) }}
    
</div>
<label for="insurance_date" class="col-md-1 control-label">Date:</label>
    <div class="input-group col-md-3 {{ $errors->has('insurance_date') ? ' has-error' : '' }}">
        <div class="input-group date" data-provide="datepicker" data-date-clear-btn="true" data-date-format="yyyy-mm-dd"
            data-autoclose="true">
            {{ Form::text('insurance_date', Helper::getDateFormate($item->insurance_date), ['class' => 'form-control', 'id'=>'insurance_date', 'placeholder' => trans('general.select_date'), 'required', 'readonly', 'style'=>'background-color:inherit']) }}
            <span class="input-group-addon"><i class="fas fa-calendar" aria-hidden="true"></i></span>
        </div>
       
        {!! $errors->first('insurance_date', '<span class="alert-msg" aria-hidden="true"><i class="fas fa-times"
                aria-hidden="true"></i> :message</span>') !!}
    </div>
   
</div>

<div class="form-group ">
  
    <label for="user_id" class="col-md-3 control-label">{{ trans('general.insaurance_owner') }}</label>
    <div class="col-md-6 col-sm-12">
	        <select class="form-control select2" id="user_id" name="user_id" style="width:262px !important" required>
	           <option >Select</option> 
             @foreach ($user as $names)
              <option value="{{ $names->id }}">{{ $names->username }}</option>
              @endforeach 
	         
	        </select>
</div>

</div>

<div class="form-group {{ $errors->has('insurance_from') ? ' has-error' : '' }}" style="display:flex">
    <label for="insurance_from" class="col-md-3 control-label">{{ trans('general.insurance_from') }}</label>
    <div class="input-group col-md-3">
        <div class="input-group date" data-provide="datepicker" data-date-clear-btn="true" data-date-format="yyyy-mm-dd"
            data-autoclose="true">
            {{ Form::text('insurance_from', Helper::getDateFormate($item->insurance_from), ['class' => 'form-control', 'id'=>'insurance_from', 'placeholder' => trans('general.select_date'), 'required', 'readonly', 'style'=>'background-color:inherit']) }}
            <span class="input-group-addon"><i class="fas fa-calendar" aria-hidden="true"></i></span>
        </div>
        {!! $errors->first('insurance_from', '<span class="alert-msg" aria-hidden="true"><i class="fas fa-times"
                aria-hidden="true"></i> :message</span>') !!}
    </div>
    <label for="insurance_to" class="col-md-1 control-label">To:</label>
    <div class="input-group col-md-3">
        <div class="input-group date" data-provide="datepicker" data-date-clear-btn="true" data-date-format="yyyy-mm-dd"
            data-autoclose="true">
            {{ Form::text('insurance_to', Helper::getDateFormate($item->insurance_to), ['class' => 'form-control', 'id'=>'insurance_to', 'placeholder' => trans('general.select_date'), 'required', 'readonly', 'style'=>'background-color:inherit']) }}
            <span class="input-group-addon"><i class="fas fa-calendar" aria-hidden="true"></i></span>
        </div>
        {!! $errors->first('insurance_to', '<span class="alert-msg" aria-hidden="true"><i class="fas fa-times"
                aria-hidden="true"></i> :message</span>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('insurance_to') ? ' has-error' : '' }}">
  
</div>


<div class="form-group {{ $errors->has('amount') ? ' has-error' : '' }}">
    <label for="amount" class="col-md-3 control-label">Amount Insured</label>
    <div class="col-md-7">
        <div class="col-md-7" style="padding-left:0px">
            {{ Form::text('amount', $item->amount, ['class' => 'form-control', 'placeholder' => 'Enter Amount', 'required']) }}
        </div>
    </div>
</div>
<div class="form-group {{ $errors->has('towingsavailable') ? ' has-error' : '' }}">
    <label for="towingsavailable" class="col-md-3 control-label">Towing Service</label>
    <div class="col-md-7">
        <div class="col-md-7" style="padding-left:0px">
            {{ Form::number('towingsavailable', $item->towingsavailable, ['class' => 'form-control', 'placeholder' => 'Enter Towing Service', 'required']) }}
        </div>
    </div>
</div>

<div class="form-group  {{ $errors->has('premium_type') ? ' has-error' : '' }}">
    <label for="premium_type" class="col-md-3 control-label">{{ trans('general.premium_type') }}</label>
    <div class="col-md-4 col-sm-12">
        {{ Form::select('premium_type', [''=>'Select']+['per_month'=>'Per Month','per_year'=>'Per Year'] ,$item->premium_type, ['id' => 'premium_type', 'class' => 'form-control serchable', 'required']) }}
    </div>
</div>

<div class="form-group {{ $errors->has('cost') ? ' has-error' : '' }}">
    <label for="cost" class="col-md-3 control-label">Premium Amount</label>
    <div class="col-md-7">
        <div class="col-md-7" style="padding-left:0px">
            {{ Form::text('cost', $item->cost, ['class' => 'form-control', 'placeholder' => 'Enter cost', 'required']) }}
        </div>
    </div>
</div>

<div class="form-group {{ $errors->has('no_of_drivers_allowed') ? ' has-error' : '' }}">
    <label for="no_of_drivers_allowed"
        class="col-md-3 control-label">{{ trans('general.no_of_drivers_allowed') }}</label>
    <div class="col-md-7">
        <div class="col-md-7" style="padding-left:0px">
            {{ Form::text('no_of_drivers_allowed', $item->no_of_drivers_allowed, ['class' => 'form-control num_of_drivers', 'placeholder' => 'Enter no of drivers allowed', 'required']) }}
        </div>
    </div>
</div>

<div class="form-group {{ $errors->has('driver_cost') ? ' has-error' : '' }}">
    <label for="driver_cost" class="col-md-3 control-label">Add Driver Cost</label>
    <div class="col-md-7">
        <div class="col-md-7" style="padding-left:0px">
            {{ Form::text('driver_cost', $item->driver_cost, ['class' => 'form-control', 'placeholder' => 'Enter driver cost', 'required']) }}
        </div>
    </div>
</div>
<!-- Add this above the Drivers section -->
<div class="form-group">
    <label for="all_drivers_toggle" class="col-md-3 control-label">Select All Drivers</label>
    <div class="col-md-6">
        <input type="checkbox" id="all_drivers_toggle" name="all_drivers_toggle" 
               data-insurance-id="{{ $item->id }}" 
               {{ $toggle_value  ? 'checked' : '' }} class="slider-toggle">
        
    </div>
    <div class="col-md-6 col-md-offset-3" id="success-message" style="display: none; color: green; margin-top: 10px;">
        All users added successfully Please Check!
    </div>
</div>



<?php $driver_row = 1; ?>
<div class="box box-default"id="drivers-section" style="{{ $toggle_value  ? 'display: none;' : '' }}">
    <div class="box-header with-border" style="border-top: 3px solid blue;">


        <div class="col-md-12 box-title text-right" style="padding: 0px; margin: 0px;">
            <div class="col-md-9 text-left">
                Drivers
            </div>
            <div class="col-md-3 text-right" style="padding-right: 10px;">
                <button type="button" id="add-driver-btn" class="btn btn-primary pull-right add-driver">
                    <i class="fas fa-icon icon-white" aria-hidden="true"></i>
                    Add Driver
                </button>
            </div>
        </div>
    </div>

    <div class="box-body">
        <div style="padding-top: 30px;">

            <table class="table" id="drivers-table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Driver Name</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody id="drivers-tbody">
                    <?php if(isset($item->drivers) && $item->drivers <>null){
                    foreach($item->drivers as $key => $driver){

                                 ?>

                    <tr id="driver-row-<?=$driver_row?>">

                        <th scope="row"><?=$driver_row?></th>

                        <td>
                            <div class="form-group ">
                                <div class="col-md-12">
                                    <div class="col-md-12" style="padding-left:0px">
                                        <?= Form::select('drivers['.$driver_row.'][name]' , Helper::getDriverNamesArr(), $driver->driver_name, ['class' => 'form-control search-existing', 'id' => 'serchable-p'.$driver_row.'', 'placeholder' => 'select driver']) ?>
                                    </div>
                                </div>
                            </div>
                        </td>


                        <td>
                            <button type="button" data-rid="<?=$driver_row?>"
                                class="btn btn-danger pull-right remove-driver"><i class="fas fa-icon icon-white"
                                    aria-hidden="true"></i>Remove</button>
                        </td>

                    </tr>

                    <?php
                    $driver_row++;
                    }
                } ?>
                </tbody>
            </table>

        </div>
    </div>
</div>

<style>
    .select2.select2-container.select2-container--default{
        width: 100% !important;
    }
</style>

@stop

@section('content')

@parent

@stop



@section('moar_scripts')
<!-- @include('partials/assets-assigned') -->

<script>
     $('.serchable').select2({});

$(".search-existing").select2({});

var driver_row = {{$driver_row}};

$("body").on("click", ".add-driver", function() {

    var driver_allowed = $(".num_of_drivers").val();

    if (driver_allowed != '') {
        if (driver_row <= driver_allowed) {
            if(driver_row == driver_allowed){
                const button = document.getElementById('add-driver-btn');
                button.disabled = true;
            }

            data = {
                driver_row: driver_row,
            };

            $.ajax({
            method: 'post',
            url: '{{ url("getDriverField") }}',
            dataType: "JSON",
            data: {
                "_token": "{{ csrf_token() }}",
                "data": data
            },
            success: function(data) {
                // console.log(data);
                if (data != '') {
                    $("#drivers-table #drivers-tbody").append(data);
                    $('#serchable-'+driver_row).select2({});
                    driver_row++;
                }
            },
            error: function(data) {
                console.log("fail");
            }
        });



            // html = '';
            // html += '<tr id="driver-row-' + driver_row + '">';

            // html += '<th scope="row">' + driver_row + '</th>';

            // html += '<td>';
            // html += '<div class="form-group ">';
            // html += '<div class="col-md-12">';
            // html += '<div class="col-md-12" style="padding-left:0px">';
            // html += '<input class="form-control" placeholder="Enter Driver Name" required="" name="drivers[' +
            //     driver_row + '][name]" type="text">';
            // html += '</div>';
            // html += '</div>';
            // html += '</div>';
            // html += '</td>';

            // html += '<td>';
            // html += '<button type="button" data-rid="' + driver_row +
            //     '" class="btn btn-danger pull-right remove-driver"><i class="fas fa-icon icon-white" aria-hidden="true"></i>Remove</button>';
            // html += '</td>';

            // html += '</tr>';

            // $("#drivers-table #drivers-tbody").append(html);

            // driver_row++;

        }else{
        }
    } else {
        alert("Please enter total number of drivers first");
    }

});



$("body").on("click", ".remove-driver", function() {
    $("#driver-row-" + $(this).attr("data-rid")).remove();
    driver_row--;
    const button = document.getElementById('add-driver-btn');
    button.disabled = false;
});


$('.serchable').select2({});
$(document).ready(function() {
    toggleDriverSection($("#all_drivers_toggle").is(":checked"));
    $("#all_drivers_toggle").on("change", function() {
        let toggleState = $(this).is(":checked") ? 1 : 0;
        let AssetId = $('#asset_id').val();
        
       
        $.ajax({
            method: 'POST',
            url: '{{ url("insurance/toggleAllDrivers") }}',
            data: {
                "_token": "{{ csrf_token() }}",
                "asset_id": AssetId,
                "all_drivers_toggle": toggleState
            },
            success: function(response) {
                toggleDriverSection(toggleState === 1);
               
                
            },
            error: function() {
                console.log("Failed to update toggle state.");
            }
        });
    });
});
function toggleDriverSection(show) {
    if (show) {
        $("#drivers-table").closest(".box").hide(); 
    } else {
        $("#drivers-table").closest(".box").show(); 
    }
}

</script>
@stop