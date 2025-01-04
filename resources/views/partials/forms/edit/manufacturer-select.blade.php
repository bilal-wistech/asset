<!-- Asset Model -->
<div id="{{ $fieldname }}" class="form-group{{ $errors->has($fieldname) ? ' has-error' : '' }}">

    {{ Form::label($fieldname, $translated_name, ['class' => 'col-md-3 control-label']) }}

    <div class="col-md-7{{ isset($required) && $required == 'true' ? ' required' : '' }}">
        <select class="js-data-ajax" data-endpoint="manufacturers"
            data-placeholder="{{ trans('general.select_manufacturer') }}" name="{{ $fieldname }}" style="width: 100%"
            id="manufacturer_select_id" aria-label="{{ $fieldname }}" {!! isset($item) && Helper::checkIfRequired($item, $fieldname) ? ' data-validation="required" required' : '' !!}>
            <option value="">Select a Manufacturer</option>
            @if ($manufacturer_id = old($fieldname, isset($item) ? $item->{$fieldname} : ''))
                <option value="{{ $manufacturer_id }}" selected="selected" role="option" aria-selected="true"
                    role="option">
                    {{ \App\Models\Manufacturer::find($manufacturer_id) ? \App\Models\Manufacturer::find($manufacturer_id)->name : '' }}
                </option>
            @else
                @foreach (\App\Models\Manufacturer::all() as $manufacturer)
                    <option value="{{ $manufacturer->id }}">
                        {{ $manufacturer->name ?? '' }}
                    </option>
                @endforeach
            @endif

        </select>
    </div>

    <div class="col-md-1 col-sm-1 text-left">
        @can('create', \App\Models\Manufacturer::class)
            @if (!isset($hide_new) || $hide_new != 'true')
                <a href='{{ route('modal.show', 'manufacturer') }}' data-toggle="modal" data-target="#createModal"
                    data-select='manufacturer_select_id' class="btn btn-sm btn-primary">{{ trans('button.new') }}</a>
            @endif
        @endcan
    </div>


    {!! $errors->first(
        $fieldname,
        '<div class="col-md-8 col-md-offset-3"><span class="alert-msg" aria-hidden="true"><i class="fas fa-times" aria-hidden="true"></i> :message</span></div>',
    ) !!}
</div>
