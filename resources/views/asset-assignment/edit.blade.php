@extends('layouts/edit-form-for-assetassignment', [
'createText' => 'Asset Assignment' ,
'updateText' => 'Edit Asset Assignment',
'helpPosition' => 'right',
'helpText' => trans('help.categories'),
'topSubmit' => 'false',
'formAction' => (isset($item->id)) ? route('asset-assignment.update', ['id' => $item->id]) :
route('asset-assignment.store'),
])

@section('inputFields')


<div class="form-group ">
    <label for="asset_id" class="col-md-3 control-label">{{ trans('general.asset_id') }}</label>
    <div class="col-md-8 col-sm-12">
        {{ Form::select('asset_id', [''=>'Select']+ $assets ,$item->asset_id, ['class' => 'form-control bulkCheckoutAsset dynamicdatatransfertobtn searchable', 'required']) }}
    </div>
</div>



<div class="form-group ">
    <label for="user_id" class="col-md-3 control-label">{{ trans('general.user') }}</label>
    <div class="col-md-8 col-sm-12">
        {{ Form::select('user_id[]', isset($users) ? $users : [], $assigned_ids, ['id' => 'user_id', 'class' => 'form-control userSearchable  searchable', 'multiple' => true, 'required']) }}
    </div>
</div> 

<div class="form-group">
        <div class="col-md-offset-3 col-md-8 col-sm-12">
            <div class="row">
                <div class="col-md-4">
                    <button style="width:100%" class="btn btn-primary" type="submit">Add Assignment</button>
                </div>
                <div class="col-md-4">
                    <a href="#" id="checkoutLink" class="btn btn-primary" style="width:100%">
                        {{ trans('admin/hardware/general.checkout') }}
                    </a>
                </div>
            </div>
            
        </div>
    </div>




@stop

@section('content')

@parent

@stop



@section('moar_scripts')
<script>
    $(document).ready(function() {
        // Update checkout link based on selected asset
        function updateCheckoutLink(assetId) {
            let checkoutLink = "{{ route('hardware.createcheckout.createCheckout', ':assetId') }}";
            checkoutLink = checkoutLink.replace(':assetId', assetId);
            $('#checkoutLink').attr('href', checkoutLink);
        }

        // Update checkout link based on selected asset
        $('.dynamicdatatransfertobtn').change(function() {
            var assetId = $(this).val();
            if (assetId) {
                updateCheckoutLink(assetId);
            }
        });

        // Update the link on page load based on the selected asset
        const assetIdOnEdit = $('select[name="asset_id"]').val();
        if (assetIdOnEdit) {
            updateCheckoutLink(assetIdOnEdit);
        }
    });
</script>
<script>
$('.searchable').select2({});




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

                    // $(".searchable").select2({});
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