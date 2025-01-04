<!-- Asset Model -->
<div id="{{ $fieldname }}" class="form-group{{ $errors->has($fieldname) ? ' has-error' : '' }}">

    {{ Form::label($fieldname, $translated_name, ['class' => 'col-md-3 control-label']) }}

    <div class="col-md-7{{ isset($required) && $required == 'true' ? ' required' : '' }}">
        <select class="js-data-ajax" data-endpoint="categories/{{ isset($category_type) ? $category_type : 'assets' }}"
            data-placeholder="{{ trans('general.select_category') }}" name="{{ $fieldname }}" style="width: 100%"
            id="category_select_id" aria-label="{{ $fieldname }}" {!! isset($item) && Helper::checkIfRequired($item, $fieldname) ? ' data-validation="required" required' : '' !!}>
            <option value="">Select a Company</option>
            {{-- @if ($category_id = old($fieldname, isset($item) ? $item->{$fieldname} : ''))
                <option value="{{ $category_id }}" selected="selected" role="option" aria-selected="true"
                    role="option">
                    {{ \App\Models\Category::find($category_id) ? \App\Models\Category::find($category_id)->name : '' }}
                </option>
            @else
                @foreach (\App\Models\Category::all() as $category)
                    <option value="{{ $category->id }}">
                        {{ $category->name ?? '' }}
                    </option>
                @endforeach
            @endif --}}
            @php
                $category_id = old($fieldname, isset($item) ? $item->{$fieldname} : '');
            @endphp

            @foreach (\App\Models\Category::all() as $category)
                <option value="{{ $category->id }}" {{ $category->id == $category_id ? 'selected="selected"' : '' }}
                    role="option" aria-selected="{{ $category->id == $category_id ? 'true' : 'false' }}">
                    {{ $category->name ?? '' }}
                </option>
            @endforeach

        </select>
    </div>
    <div class="col-md-1 col-sm-1 text-left">
        @can('create', \App\Models\Category::class)
            @if (!isset($hide_new) || $hide_new != 'true')
                <a href='{{ route('modal.show', ['type' => 'category', 'category_type' => isset($category_type) ? $category_type : 'assets']) }}'
                    data-toggle="modal" data-target="#createModal" data-select='category_select_id'
                    class="btn btn-sm btn-primary">{{ trans('button.new') }}</a>
            @endif
        @endcan
    </div>


    {!! $errors->first(
        $fieldname,
        '<div class="col-md-8 col-md-offset-3"><span class="alert-msg" aria-hidden="true"><i class="fas fa-times" aria-hidden="true"></i> :message</span></div>',
    ) !!}
</div>
