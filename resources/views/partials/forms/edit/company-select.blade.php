<!-- Company -->
@if ($snipeSettings->full_multiple_companies_support == '1' && !Auth::user()->isSuperUser())
    <!-- full company support is enabled and this user isn't a superadmin -->
    <div class="form-group">
        {{ Form::label($fieldname, $translated_name, ['class' => 'col-md-3 control-label']) }}
        <div class="col-md-6">
            <select class="js-data-ajax" disabled="true" data-endpoint="companies"
                data-placeholder="{{ trans('general.select_company') }}" name="{{ $fieldname }}" style="width: 100%"
                id="company_select" aria-label="{{ $fieldname }}">
                <option value="">Select a Company</option>
                {{-- @if ($company_id = Request::old($fieldname, isset($item) ? $item->{$fieldname} : ''))
                    <option value="{{ $company_id }}" selected="selected">
                        {{ \App\Models\Company::find($company_id) ? \App\Models\Company::find($company_id)->name : '' }}
                    </option>
                @else
                    @foreach (\App\Models\Company::all() as $company)
                        <option value="{{ $company->id }}">
                            {{ $company->name ?? '' }}
                        </option>
                    @endforeach
                @endif --}}
                @php
                    $company_id = old($fieldname, isset($item) ? $item->{$fieldname} : '');
                @endphp

                @foreach (\App\Models\Company::all() as $company)
                    <option value="{{ $company->id }}" {{ $company->id == $company_id ? 'selected="selected"' : '' }}
                        role="option" aria-selected="{{ $company->id == $company_id ? 'true' : 'false' }}">
                        {{ $company->name ?? '' }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
@else
    <!-- full company support is enabled or this user is a superadmin -->
    <div id="{{ $fieldname }}" class="form-group{{ $errors->has($fieldname) ? ' has-error' : '' }}">
        {{ Form::label($fieldname, $translated_name, ['class' => 'col-md-3 control-label']) }}
        <div class="col-md-6">
            <select class="js-data-ajax" data-endpoint="companies"
                data-placeholder="{{ trans('general.select_company') }}" name="{{ $fieldname }}"
                style="width: 100%" id="company_select">
                <option value="">Select a Company</option>
                {{-- @if ($company_id = Request::old($fieldname, isset($item) ? $item->{$fieldname} : ''))
                    <option value="{{ $company_id }}" selected="selected">
                        {{ \App\Models\Company::find($company_id) ? \App\Models\Company::find($company_id)->name : '' }}
                    </option>
                @else
                    @foreach (\App\Models\Company::all() as $company)
                        <option value="{{ $company->id }}">
                            {{ $company->name ?? '' }}
                        </option>
                    @endforeach
                @endif --}}
                @php
                    $company_id = old($fieldname, isset($item) ? $item->{$fieldname} : '');
                @endphp

                @foreach (\App\Models\Company::all() as $company)
                    <option value="{{ $company->id }}" {{ $company->id == $company_id ? 'selected="selected"' : '' }}
                        role="option" aria-selected="{{ $company->id == $company_id ? 'true' : 'false' }}">
                        {{ $company->name ?? '' }}
                    </option>
                @endforeach
            </select>
        </div>
        {!! $errors->first(
            $fieldname,
            '<div class="col-md-8 col-md-offset-3"><span class="alert-msg"><i class="fas fa-times"></i> :message</span></div>',
        ) !!}

        {!! $errors->first(
            $fieldname,
            '<div class="col-md-8 col-md-offset-3"><span class="alert-msg" aria-hidden="true"><i class="fas fa-times" aria-hidden="true"></i> :message</span></div>',
        ) !!}
    </div>

@endif
