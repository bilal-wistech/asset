<!-- Location -->
<div id="{{ $fieldname }}" class="form-group{{ $errors->has($fieldname) ? ' has-error' : '' }}"{!! isset($style) ? ' style="' . e($style) . '"' : '' !!}>

    {{ Form::label($fieldname, $translated_name, ['class' => 'col-md-3 control-label']) }}
    <div class="col-md-7{{ isset($required) && $required == 'true' ? ' required' : '' }}">
        <select class="js-data-ajax" data-endpoint="locations" data-placeholder="{{ trans('general.select_location') }}"
            name="{{ $fieldname }}" style="width: 100%" id="{{ $fieldname }}_location_select"
            aria-label="{{ $fieldname }}" {!! isset($item) && Helper::checkIfRequired($item, $fieldname) ? ' data-validation="required" required' : '' !!}>
            <option value="">Select a Location</option>
            @php
                $location_id = old($fieldname, isset($item) ? $item->{$fieldname} : '');
            @endphp

            @foreach (\App\Models\Location::all() as $location)
                <option value="{{ $location->id }}" {{ $location->id == $location_id ? 'selected="selected"' : '' }}
                    role="option" aria-selected="{{ $location->id == $location_id ? 'true' : 'false' }}">
                    {{ $location->name ?? '' }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-1 col-sm-1 text-left">
        @can('create', \App\Models\Location::class)
            @if (!isset($hide_new) || $hide_new != 'true')
                <a href='{{ route('modal.show', 'location') }}' data-toggle="modal" data-target="#createModal"
                    data-select='{{ $fieldname }}_location_select'
                    class="btn btn-sm btn-primary">{{ trans('button.new') }}</a>
            @endif
        @endcan
    </div>

    {!! $errors->first(
        $fieldname,
        '<div class="col-md-8 col-md-offset-3"><span class="alert-msg" aria-hidden="true"><i class="fas fa-times" aria-hidden="true"></i> :message</span></div>',
    ) !!}

    @if (isset($help_text))
        <div class="col-md-7 col-sm-11 col-md-offset-3">
            <p class="help-block">{{ $help_text }}</p>
        </div>
    @endif


</div>
