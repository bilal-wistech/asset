@extends('layouts/default')

{{-- Page title --}}
@section('title')
{{ trans('admin/hardware/general.bulk_checkout') }}
@parent
@stop

{{-- Page content --}}
@section('content')

<style>
.input-group {
    padding-left: 0px !important;
}
</style>


<div class="row">
    <!-- left column -->
    <div class="col-md-7">
        <div class="box box-default">
            <div class="box-header with-border">
                <h2 class="box-title"> {{ trans('admin/hardware/form.tag') }} </h2>
            </div>
            <div class="box-body">
                <form class="form-horizontal" action="{{route('bulkcheckout.store')}}" method="post" autocomplete="off">
                    {{ csrf_field() }}


                    <div class="form-group ">
                        <label for="asset_id" class="col-md-3 control-label">{{ trans('general.asset_id') }}</label>
                        <div class="col-md-8 col-sm-12">
                            {{ Form::select('asset_id', [''=>'Select']+ $assets ,$item->asset_id, ['class' => 'form-control bulkCheckoutAsset serchable', 'required']) }}
                        </div>
                    </div>


                    <div class="form-group ">
                        <label for="user_id" class="col-md-3 control-label">{{ trans('general.user') }}</label>
                        <div class="col-md-8 col-sm-12">
                            {{ Form::select('user_id', [] ,$item->user_id, ['id' => 'user_id', 'class' => 'form-control userSearchable serchable', 'required']) }}
                        </div>
                    </div>

                    <div class="form-group {{ $errors->has('checkout_date') ? ' has-error' : '' }}">
                        <label for="checkout_date"
                            class="col-md-3 control-label mx-2">{{ trans('admin/hardware/form.checkout_date') }}</label>
                        <div class="input-group col-md-4">
                            <div class="input-group date" data-provide="datepicker" data-date-clear-btn="true"
                                data-date-format="yyyy-mm-dd" data-autoclose="true">
                                {{ Form::text('checkout_date', $item->checkout_date, ['class' => 'form-control', 'id'=>'checkout_date', 'placeholder' => trans('general.select_date'), 'required', 'readonly', 'style'=>'background-color:inherit;margin-left: 14px;']) }}
                                <span class="input-group-addon"><i class="fas fa-calendar"
                                        aria-hidden="true"></i></span>
                            </div>
                            {!! $errors->first('checkout_date', '<span class="alert-msg" aria-hidden="true"><i
                                    class="fas fa-times" aria-hidden="true"></i> :message</span>') !!}
                        </div>
                    </div>

                    <div class="form-group {{ $errors->has('expected_checkin_date') ? ' has-error' : '' }}">
                        <label for="expected_checkin_date"
                            class="col-md-3 control-label">{{ trans('admin/hardware/form.expected_checkin') }}</label>
                        <div class="input-group col-md-4">
                            <div class="input-group date" data-provide="datepicker" data-date-clear-btn="true"
                                data-date-format="yyyy-mm-dd" data-autoclose="true">
                                {{ Form::text('expected_checkin_date', $item->expected_checkin_date, ['class' => 'form-control', 'id'=>'expected_checkin_date', 'placeholder' => trans('select date'), 'required'=>'true', 'readonly', 'style'=>'background-color:inherit;margin-left: 14px;']) }}
                                <span class="input-group-addon"><i class="fas fa-calendar"
                                        aria-hidden="true"></i></span>
                            </div>
                            {!! $errors->first('expected_checkin_date', '<span class="alert-msg" aria-hidden="true"><i
                                    class="fas fa-times" aria-hidden="true"></i> :message</span>') !!}
                        </div>
                    </div>



                    <div class="form-group ">
                        <label for="note" class="col-md-3 control-label">Notes</label>
                        <div class="col-md-8">
                            <textarea class="col-md-6 form-control" id="note" name="note" cols="73"
                                rows="4">{{$item->note}}</textarea>
                        </div>
                    </div>



            </div>
            <!--./box-body-->
            <div class="box-footer">
                <a class="btn btn-link" href="{{ URL::previous() }}"> {{ trans('button.cancel') }}</a>
                <button type="submit" class="btn btn-primary pull-right"><i class="fas fa-check icon-white"
                        aria-hidden="true"></i> {{ trans('general.checkout') }}</button>
            </div>
        </div>
        </form>
    </div>
    <!--/.col-md-7-->

    <!-- right column -->
    <div class="col-md-5" id="current_assets_box" style="display:none;">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h2 class="box-title">{{ trans('admin/users/general.current_assets') }}</h2>
            </div>
            <div class="box-body">
                <div id="current_assets_content">
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('moar_scripts')
@include('partials/assets-assigned')

<script>
$('.serchable').select2({});




$("body").on("change", ".bulkCheckoutAsset", function() {

    var asset_id = $(this).val();

    if (asset_id > 0) {
        $("#user_id").html("");
        data = {
            asset_id: asset_id,
        };

        $.ajax({
            method: 'post',
            url: '{{ url("getAllowedUsers") }}',
            dataType: "JSON",
            data: {
                "_token": "{{ csrf_token() }}",
                "data": data
            },
            success: function(data) {
                // console.log(data);
                if (data != '') {

                    var selectField = $("#user_id");

                    selectField.select2({
                        data: Object.entries(data).map(([id, name]) => ({
                            id,
                            text: name
                        }))
                    });

                    // $(".serchable").select2({});
                }
            },
            error: function(data) {
                console.log("fail");
            }
        });
    } else {
        $("#user_id").html("");
    }
});
</script>

@stop