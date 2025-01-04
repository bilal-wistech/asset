@php
    // Fetch suppliers from the database
    $suppliers = \App\Models\Supplier::pluck('name', 'id')->toArray();
@endphp

<div id="assigned_user" class="form-group{{ $errors->has($fieldname) ? ' has-error' : '' }}">
    {{ Form::label($fieldname, $translated_name, ['class' => 'col-md-3 control-label']) }}
    <div class="col-md-7{{  ((isset($required)) && ($required == 'true')) ? ' required' : '' }}">
        <select name="supplier_id" id="supplier_id" class="select2">
            <option value="">Select a Supplier</option>
            @foreach($suppliers as $id => $name)
                <option value="{{ $id }}" {{ (old('supplier_id', isset($item) && $item->supplier_id == $id) ? 'selected' : '') }}>
                    {{ $name }}
                </option>
                
            @endforeach
        </select>
    </div>
    <div class="col-md-1 col-sm-1 text-left">
        @can('create', \App\Models\Supplier::class)
        @if (!isset($hide_new) || $hide_new != 'true')
            <a href="{{ route('modal.show','supplier') }}" data-toggle="modal" data-target="#createModal" data-select='supplier_id'
            class="btn btn-sm btn-primary">{{ trans('button.new') }}</a>
        @endif
    @endcan
    </div>
    {!! $errors->first($fieldname, '<div class="col-md-8 col-md-offset-3"><span class="alert-msg" aria-hidden="true"><i class="fas fa-times" aria-hidden="true"></i> :message</span></div>') !!}
</div>
