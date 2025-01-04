{{-- @dd($asset) --}}
<!-- Asset -->
@php
    $assets = \App\Models\Asset::all();
@endphp
<div id="assigned_asset" class="form-group{{ $errors->has($fieldname) ? ' has-error' : '' }}"{!! isset($style) ? ' style="' . e($style) . '"' : '' !!}>
    {{ Form::label($fieldname, $translated_name, ['class' => 'col-md-3 control-label']) }}
    <div class="col-md-8{{ isset($required) && $required == 'true' ? ' required' : '' }}">
        <select class="js-data-ajax select2" data-endpoint="hardware"
            data-placeholder="{{ trans('general.select_asset') }}" aria-label="{{ $fieldname }}"
            name="{{ $fieldname }}" style="width: 100%"
            id="{{ isset($select_id) ? $select_id : 'assigned_asset_select' }}"{{ isset($multiple) ? ' multiple' : '' }}
            {!! !empty($asset_status_type) ? ' data-asset-status-type="' . $asset_status_type . '"' : '' !!}>

            <option value="" role="option">{{ trans('general.select_asset') }}</option>

            @foreach ($assets as $singleAsset)
                <option value="{{ $singleAsset->id }}"
                    {{ isset($selectedAsset) && $selectedAsset->id == $singleAsset->id ? 'selected' : '' }}>
                    {{ $singleAsset->present()->fullName }}
                </option>
            @endforeach
        </select>
    </div>
    {!! $errors->first(
        $fieldname,
        '<div class="col-md-8 col-md-offset-3"><span class="alert-msg" aria-hidden="true"><i class="fas fa-times" aria-hidden="true"></i> :message</span></div>',
    ) !!}

</div>
