<!-- Improvement Type -->
{{-- @dd($item->asset_maintenance_type) --}}
<div class="form-group {{ $errors->has('asset_maintenance_type') ? 'has-error' : '' }}">
    <label for="asset_maintenance_type" class="col-md-3 control-label">
        {{ trans('admin/asset_maintenances/form.asset_maintenance_type') }}
    </label>
    <div class="col-md-7 {{ Helper::checkIfRequired($item, 'asset_maintenance_type') ? 'required' : '' }}">
        <select id="asset_maintenance_type" name="asset_maintenance_type" class="select2" style="min-width:350px"
            aria-label="asset_maintenance_type">
            @foreach ($assetMaintenanceType as $key => $value)
                <option value="{{ $key }}"
                    {{ old('asset_maintenance_type', $item->asset_maintenance_type) == $key || old('asset_maintenance_type', $item->asset_maintenance_type) == $value ? 'selected' : '' }}>
                    {{ $value }}
                </option>
            @endforeach

        </select>
        {!! $errors->first(
            'asset_maintenance_type',
            '<span class="alert-msg" aria-hidden="true"><i class="fas fa-times" aria-hidden="true"></i> :message</span>',
        ) !!}
    </div>
    <div class="col-md-1 col-sm-1 text-left">
        <a href="#" data-toggle="modal" data-target="#createModal2" data-dependency="supplier"
            data-select="supplier_select_id" class="btn btn-sm btn-primary">
            {{ trans('button.new') }}
        </a>
    </div>
</div>
