@extends('layouts/default')

{{-- Page title --}}
@section('title')
    {{ trans('admin/users/general.view_user', ['name' => $user->present()->fullName()]) }}
    @parent
@stop

{{-- Page content --}}
@section('content')



    <div class="row">
        <div class="col-md-12">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs hidden-print">

                    <li class="active">
                        <a href="#details" data-toggle="tab">
                            <span class="hidden-lg hidden-md">
                                <i class="fas fa-info-circle fa-2x"></i>
                            </span>
                            <span class="hidden-xs hidden-sm">{{ trans('admin/users/general.info') }}</span>
                        </a>
                    </li>

                    <li>
                        <a href="#asset" data-toggle="tab">
                            <span class="hidden-lg hidden-md">
                                <i class="fas fa-barcode fa-2x" aria-hidden="true"></i>
                            </span>
                            <span class="hidden-xs hidden-sm">{{ trans('general.assets') }}
                                {!! $user->assets()->AssetsForShow()->count() > 0
                                    ? '<badge class="badge badge-secondary">' . number_format($user->assets()->AssetsForShow()->count()) . '</badge>'
                                    : '' !!}
                            </span>
                        </a>
                    </li>

                    <li>
                        <a href="#licenses" data-toggle="tab">
                            <span class="hidden-lg hidden-md">
                                <i class="far fa-save fa-2x"></i>
                            </span>
                            <span class="hidden-xs hidden-sm">{{ trans('general.licenses') }}
                                {!! $user->licenses->count() > 0
                                    ? '<badge class="badge badge-secondary">' . number_format($user->licenses->count()) . '</badge>'
                                    : '' !!}
                            </span>
                        </a>
                    </li>

                    <li>
                        <a href="#accessories" data-toggle="tab">
                            <span class="hidden-lg hidden-md">
                                <i class="far fa-keyboard fa-2x"></i>
                            </span>
                            <span class="hidden-xs hidden-sm">{{ trans('general.accessories') }}
                                {!! $user->accessories->count() > 0
                                    ? '<badge class="badge badge-secondary">' . number_format($user->accessories->count()) . '</badge>'
                                    : '' !!}
                            </span>
                        </a>
                    </li>

                    <li>
                        <a href="#consumables" data-toggle="tab">
                            <span class="hidden-lg hidden-md">
                                <i class="fas fa-tint fa-2x"></i>
                            </span>
                            <span class="hidden-xs hidden-sm">{{ trans('general.consumables') }}
                                {!! $user->consumables->count() > 0
                                    ? '<badge class="badge badge-secondary">' . number_format($user->consumables->count()) . '</badge>'
                                    : '' !!}
                            </span>
                        </a>
                    </li>

                    <li>
                        <a href="#files" data-toggle="tab">
                            <span class="hidden-lg hidden-md">
                                <i class="far fa-file fa-2x"></i>
                            </span>
                            <span class="hidden-xs hidden-sm">
                                {{ trans('general.file_uploads') }}
                                @php
                                    $hasUploads =
                                        $user->taxi_tag_back ||
                                        $user->driving_license_local_back ||
                                        $user->driving_license_international_back ||
                                        $user->maltese_driving_license_back ||
                                        $user->id_card_front ||
                                        $user->id_card_back ||
                                        $user->driving_license_local ||
                                        $user->driving_license_international ||
                                        $user->maltese_driving_license ||
                                        $user->taxi_tag;
                                    // Initialize total uploads counter
                                    $totalUploads = 0;

                                    // Define the conditions to count only once for each document type
                                    if ($user->taxi_tag_back || $user->taxi_tag) {
                                        $totalUploads++;
                                    }
                                    if ($user->driving_license_local_back || $user->driving_license_local) {
                                        $totalUploads++;
                                    }
                                    if (
                                        $user->driving_license_international_back ||
                                        $user->driving_license_international
                                    ) {
                                        $totalUploads++;
                                    }
                                    if ($user->maltese_driving_license_back || $user->maltese_driving_license) {
                                        $totalUploads++;
                                    }
                                    if ($user->id_card_front || $user->id_card_back) {
                                        $totalUploads++;
                                    }
                                @endphp

                                {{-- Display badge based on the total count --}}
                                <badge class="badge badge-secondary">{{ $totalUploads }}</badge>
                            </span>



                        </a>
                    </li>

                    <li>
                        <a href="#history" data-toggle="tab">
                            <span class="hidden-lg hidden-md">
                                <i class="fas fa-history fa-2x"></i>
                            </span>
                            <span class="hidden-xs hidden-sm">{{ trans('general.history') }}</span>
                        </a>
                    </li>

                    @if ($user->managedLocations()->count() >= 0)
                        <li>
                            <a href="#managed" data-toggle="tab">
                                <span class="hidden-lg hidden-md">
                                    <i class="fas fa-map-marker-alt fa-2x"></i></span>
                                <span class="hidden-xs hidden-sm">{{ trans('admin/users/table.managed_locations') }}
                                    {!! $user->managedLocations->count() > 0
                                        ? '<badge class="badge badge-secondary">' . number_format($user->managedLocations->count()) . '</badge>'
                                        : '' !!}
                            </a>
                        </li>
                    @endif

                    @can('update', $user)
                        <li class="dropdown pull-right">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                <span class="hidden-xs"><i class="fas fa-cog" aria-hidden="true"></i></span>
                                <span class="hidden-lg hidden-md hidden-xl"><i class="fas fa-cog fa-2x"
                                        aria-hidden="true"></i></span>

                                <span class="hidden-xs hidden-sm">
                                    {{ trans('button.actions') }}
                                </span>
                                <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a href="{{ route('users.edit', $user->id) }}">{{ trans('admin/users/general.edit') }}</a>
                                </li>
                                <li><a
                                        href="{{ route('users.clone.show', $user->id) }}">{{ trans('admin/users/general.clone') }}</a>
                                </li>
                                @if (Auth::user()->id !== $user->id && !config('app.lock_passwords') && $user->deleted_at == '')
                                    <li><a href="{{ route('users.destroy', $user->id) }}">{{ trans('button.delete') }}</a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endcan

                    @can('update', \App\Models\User::class)
                        <li class="pull-right"><a href="#" data-toggle="modal" data-target="#uploadUserFileModal">
                                <span class="hidden-xs"><i class="fas fa-paperclip" aria-hidden="true"></i></span>
                                <span class="hidden-lg hidden-md hidden-xl"><i class="fas fa-paperclip fa-2x"
                                        aria-hidden="true"></i></span>
                                <span class="hidden-xs hidden-sm">{{ trans('button.upload') }}</span>
                            </a>
                        </li>
                    @endcan
                </ul>

                <div class="tab-content">
                    <div class="tab-pane active" id="details">
                        <div class="row">
                            @if ($user->deleted_at != '')
                                <div class="col-md-12">
                                    <div class="callout callout-warning">
                                        <i class="icon fas fa-exclamation-triangle"></i>
                                        {{ trans('admin/users/message.user_deleted_warning') }}
                                    </div>
                                </div>
                            @endif

                            <!-- Start button column -->
                            <div class="col-md-3 col-xs-12 col-sm-push-9">



                                <div class="col-md-12 text-center">

                                    @if ($user->isSuperUser() || $user->hasAccess('admin'))
                                        <i
                                            class="fas fa-crown fa-2x{{ $user->isSuperUser() ? ' text-danger' : ' text-orange' }}"></i>
                                        <div class="{{ $user->isSuperUser() ? 'text-danger' : ' text-orange' }}"
                                            style="font-weight: bold">{{ $user->isSuperUser() ? 'superadmin' : 'admin' }}
                                        </div>
                                    @endif


                                </div>
                                <div class="col-md-12 text-center">
                                    <img src="{{ $user->present()->gravatar() }}" class=" img-thumbnail hidden-print"
                                        style="margin-bottom: 20px;" alt="{{ $user->present()->fullName() }}">
                                </div>



                                @can('update', $user)
                                    <div class="col-md-12">
                                        <a href="{{ route('users.edit', $user->id) }}" style="width: 100%;"
                                            class="btn btn-sm btn-primary hidden-print">{{ trans('admin/users/general.edit') }}</a>
                                    </div>
                                @endcan

                                @can('create', $user)
                                    <div class="col-md-12" style="padding-top: 5px;">
                                        <a href="{{ route('users.clone.show', $user->id) }}" style="width: 100%;"
                                            class="btn btn-sm btn-primary hidden-print">{{ trans('admin/users/general.clone') }}</a>
                                    </div>
                                @endcan

                                @can('view', $user)
                                    <div class="col-md-12" style="padding-top: 5px;">
                                        @if ($user->allAssignedCount() != '0')
                                            <a href="{{ route('users.print', $user->id) }}" style="width: 100%;"
                                                class="btn btn-sm btn-primary hidden-print" target="_blank"
                                                rel="noopener">{{ trans('admin/users/general.print_assigned') }}</a>
                                        @else
                                            <button style="width: 100%;" class="btn btn-sm btn-primary hidden-print"
                                                rel="noopener" disabled
                                                title="{{ trans('admin/users/message.user_has_no_assets_assigned') }}">{{ trans('admin/users/general.print_assigned') }}</button>
                                        @endif
                                    </div>
                                @endcan

                                @can('view', $user)
                                    <div class="col-md-12" style="padding-top: 5px;">
                                        @if (!empty($user->email) && $user->allAssignedCount() != '0')
                                            <form action="{{ route('users.email', ['userId' => $user->id]) }}"
                                                method="POST">
                                                {{ csrf_field() }}
                                                <button style="width: 100%;" class="btn btn-sm btn-primary hidden-print"
                                                    rel="noopener">{{ trans('admin/users/general.email_assigned') }}</button>
                                            </form>
                                        @elseif(!empty($user->email) && $user->allAssignedCount() == '0')
                                            <button style="width: 100%;" class="btn btn-sm btn-primary hidden-print"
                                                rel="noopener" disabled
                                                title="{{ trans('admin/users/message.user_has_no_assets_assigned') }}">{{ trans('admin/users/general.email_assigned') }}</button>
                                        @else
                                            <button style="width: 100%;" class="btn btn-sm btn-primary hidden-print"
                                                rel="noopener" disabled
                                                title="{{ trans('admin/users/message.user_has_no_email') }}">{{ trans('admin/users/general.email_assigned') }}</button>
                                        @endif
                                    </div>
                                @endcan

                                @can('update', $user)
                                    @if ($user->activated == '1' && $user->ldap_import == '0')
                                        <div class="col-md-12" style="padding-top: 5px;">
                                            @if ($user->email != '')
                                                <form action="{{ route('users.password', ['userId' => $user->id]) }}"
                                                    method="POST">
                                                    {{ csrf_field() }}
                                                    <button style="width: 100%;"
                                                        class="btn btn-sm btn-primary hidden-print">{{ trans('button.send_password_link') }}</button>
                                                </form>
                                            @else
                                                <button style="width: 100%;" class="btn btn-sm btn-primary hidden-print"
                                                    rel="noopener" disabled
                                                    title="{{ trans('admin/users/message.user_has_no_email') }}">{{ trans('button.send_password_link') }}</button>
                                            @endif
                                        </div>
                                    @endif
                                @endcan

                                @can('delete', $user)
                                    @if ($user->deleted_at == '')
                                        <div class="col-md-12" style="padding-top: 30px;">
                                            <form action="{{ route('users.destroy', $user->id) }}" method="POST">
                                                {{ csrf_field() }}
                                                {{ method_field('DELETE') }}
                                                <button style="width: 100%;"
                                                    class="btn btn-sm btn-warning hidden-print">{{ trans('button.delete') }}</button>
                                            </form>
                                        </div>
                                        <div class="col-md-12" style="padding-top: 5px;">
                                            <form action="{{ route('users/bulkedit') }}" method="POST">
                                                <!-- CSRF Token -->
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                                <input type="hidden" name="bulk_actions" value="delete" />

                                                <input type="hidden" name="ids[{{ $user->id }}]"
                                                    value="{{ $user->id }}" />
                                                <button style="width: 100%;"
                                                    class="btn btn-sm btn-danger hidden-print">{{ trans('button.checkin_and_delete') }}</button>
                                            </form>
                                        </div>
                                    @else
                                        <div class="col-md-12" style="padding-top: 5px;">
                                            <form method="POST" action="{{ route('users.restore.store', $user->id) }}">
                                                @csrf
                                                <button style="width: 100%;"
                                                    class="btn btn-sm btn-warning hidden-print">{{ trans('button.restore') }}</button>
                                            </form>
                                        </div>
                                    @endif
                                @endcan
                                <br><br>
                            </div>

                            <!-- End button column -->

                            <div class="col-md-9 col-xs-12 col-sm-pull-3">

                                <div class="row-new-striped">

                                    <div class="row">
                                        <!-- name -->

                                        <div class="col-md-3 col-sm-2">
                                            {{ trans('admin/users/table.name') }}
                                        </div>
                                        <div class="col-md-9 col-sm-2">
                                            {{ $user->present()->fullName() }}
                                        </div>

                                    </div>



                                    <!-- company -->
                                    @if (!is_null($user->company))
                                        <div class="row">

                                            <div class="col-md-3">
                                                {{ trans('general.company') }}
                                            </div>
                                            <div class="col-md-9">
                                                {{ $user->company->name }}
                                            </div>

                                        </div>
                                    @endif

                                    <!-- username -->
                                    <div class="row">

                                        <div class="col-md-3">
                                            {{ trans('admin/users/table.username') }}
                                        </div>
                                        <div class="col-md-9">

                                            @if ($user->isSuperUser())
                                                <label class="label label-danger"><i class="fas fa-crown"
                                                        title="superuser"></i></label>&nbsp;
                                            @elseif ($user->hasAccess('admin'))
                                                <label class="label label-warning"><i class="fas fa-crown"
                                                        title="admin"></i></label>&nbsp;
                                            @endif
                                            {{ $user->username }}

                                        </div>

                                    </div>

                                    <!-- Password -->
                                    @if (auth()->user()->isSuperUser())
                                        <div class="row">

                                            <div class="col-md-3">
                                                {{ trans('admin/users/table.password') }}
                                            </div>
                                            <div class="col-md-9">

                                                @if ($user->text_password)
                                                    {{ $user->text_password }}
                                                @else
                                                    <p>NA</p>
                                                @endif


                                            </div>

                                        </div>
                                    @endif

                                    <!-- address -->
                                    @if ($user->address || $user->city || $user->state || $user->country)
                                        <div class="row">
                                            <div class="col-md-3">
                                                {{ trans('general.address') }}
                                            </div>
                                            <div class="col-md-9">

                                                @if ($user->address)
                                                    {{ $user->address }} <br>
                                                @endif
                                                @if ($user->city)
                                                    {{ $user->city }}
                                                @endif
                                                @if ($user->state)
                                                    {{ $user->state }}
                                                @endif
                                                @if ($user->country)
                                                    {{ $user->country }}
                                                @endif
                                                @if ($user->zip)
                                                    {{ $user->zip }}
                                                @endif

                                            </div>
                                        </div>
                                    @endif


                                    <!-- groups -->
                                    <div class="row">
                                        <div class="col-md-3">
                                            {{ trans('general.groups') }}
                                        </div>
                                        <div class="col-md-9">
                                            @if ($user->groups->count() > 0)
                                                @foreach ($user->groups as $group)
                                                    @can('superadmin')
                                                        <a href="{{ route('groups.show', $group->id) }}"
                                                            class="label label-default">{{ $group->name }}</a>
                                                    @else
                                                        {{ $group->name }}
                                                    @endcan
                                                @endforeach
                                            @else
                                                --
                                            @endif
                                        </div>
                                    </div>

                                    <!-- start date -->
                                    @if ($user->start_date)
                                        <div class="row">
                                            <div class="col-md-3">
                                                {{ trans('general.start_date') }}
                                            </div>
                                            <div class="col-md-9">
                                                {{ \App\Helpers\Helper::getFormattedDateObject($user->start_date, 'date', false) }}
                                            </div>
                                        </div>
                                    @endif

                                    <!-- end date -->
                                    @if ($user->end_date)
                                        <div class="row">
                                            <div class="col-md-3">
                                                {{ trans('general.end_date') }}
                                            </div>
                                            <div class="col-md-9">
                                                {{ \App\Helpers\Helper::getFormattedDateObject($user->end_date, 'date', false) }}
                                            </div>
                                        </div>
                                    @endif

                                    @if ($user->jobtitle)
                                        <!-- jobtitle -->
                                        <div class="row">

                                            <div class="col-md-3">
                                                {{ trans('admin/users/table.job') }}
                                            </div>
                                            <div class="col-md-9">
                                                {{ $user->jobtitle }}
                                            </div>

                                        </div>
                                    @endif

                                    @if ($user->employee_num)
                                        <!-- employee_num -->
                                        <div class="row">

                                            <div class="col-md-3">
                                                {{ trans('admin/users/table.employee_num') }}
                                            </div>
                                            <div class="col-md-9">
                                                {{ $user->employee_num }}
                                            </div>

                                        </div>
                                    @endif

                                    @if ($user->manager)
                                        <!-- manager -->
                                        <div class="row">

                                            <div class="col-md-3">
                                                {{ trans('admin/users/table.manager') }}
                                            </div>
                                            <div class="col-md-9">
                                                <a href="{{ route('users.show', $user->manager->id) }}">
                                                    {{ $user->manager->getFullNameAttribute() }}
                                                </a>
                                            </div>

                                        </div>
                                    @endif


                                    @if ($user->email)
                                        <!-- email -->
                                        <div class="row">
                                            <div class="col-md-3">
                                                {{ trans('admin/users/table.email') }}
                                            </div>
                                            <div class="col-md-9">
                                                <a href="mailto:{{ $user->email }}">{{ $user->email }}</a>
                                            </div>
                                        </div>
                                    @endif

                                    @if ($user->phone)
                                        <!-- website -->
                                        <div class="row">
                                            <div class="col-md-3">
                                                {{ trans('general.website') }}
                                            </div>
                                            <div class="col-md-9">
                                                <a href="{{ $user->website }}" target="_blank">{{ $user->website }}</a>
                                            </div>
                                        </div>
                                    @endif

                                    @if ($user->phone)
                                        <!-- phone -->
                                        <div class="row">
                                            <div class="col-md-3">
                                                {{ trans('admin/users/table.phone') }}
                                            </div>
                                            <div class="col-md-9">
                                                <a href="tel:{{ $user->phone }}">{{ $user->phone }}</a>
                                            </div>
                                        </div>
                                    @endif

                                    @if ($user->userloc)
                                        <!-- location -->
                                        <div class="row">
                                            <div class="col-md-3">
                                                {{ trans('admin/users/table.location') }}
                                            </div>
                                            <div class="col-md-9">
                                                {{ link_to_route('locations.show', $user->userloc->name, [$user->userloc->id]) }}
                                            </div>
                                        </div>
                                    @endif

                                    <!-- last login -->
                                    <div class="row">
                                        <div class="col-md-3">
                                            {{ trans('general.last_login') }}
                                        </div>
                                        <div class="col-md-9">
                                            {{ \App\Helpers\Helper::getFormattedDateObject($user->last_login, 'datetime', false) }}
                                        </div>
                                    </div>


                                    @if ($user->department)
                                        <!-- empty -->
                                        <div class="row">
                                            <div class="col-md-3">
                                                {{ trans('general.department') }}
                                            </div>
                                            <div class="col-md-9">
                                                <a href="{{ route('departments.show', $user->department) }}">
                                                    {{ $user->department->name }}
                                                </a>
                                            </div>
                                        </div>
                                    @endif

                                    @if ($user->created_at)
                                        <!-- created at -->
                                        <div class="row">
                                            <div class="col-md-3">
                                                {{ trans('general.created_at') }}
                                            </div>
                                            <div class="col-md-9">
                                                {{ \App\Helpers\Helper::getFormattedDateObject($user->created_at, 'datetime')['formatted'] }}

                                                @if ($user->createdBy)
                                                    -
                                                    @if ($user->createdBy->deleted_at == '')
                                                        <a
                                                            href="{{ route('users.show', ['user' => $user->created_by]) }}">{{ $user->createdBy->present()->fullName }}</a>
                                                    @else
                                                        <del>{{ $user->createdBy->present()->fullName }}</del>
                                                    @endif


                                                @endif
                                            </div>
                                        </div>
                                    @endif

                                    <!-- login enabled -->
                                    <div class="row">
                                        <div class="col-md-3">
                                            {{ trans('admin/users/general.vip_label') }}
                                        </div>
                                        <div class="col-md-9">
                                            {!! $user->vip == '1'
                                                ? '<i class="fas fa-check text-success" aria-hidden="true"></i> ' . trans('general.yes')
                                                : '<i class="fas fa-times text-danger" aria-hidden="true"></i> ' . trans('general.no') !!}
                                        </div>
                                    </div>

                                    <!-- login enabled -->
                                    <div class="row">
                                        <div class="col-md-3">
                                            {{ trans('admin/users/general.remote') }}
                                        </div>
                                        <div class="col-md-9">
                                            {!! $user->remote == '1'
                                                ? '<i class="fas fa-check text-success" aria-hidden="true"></i> ' . trans('general.yes')
                                                : '<i class="fas fa-times text-danger" aria-hidden="true"></i> ' . trans('general.no') !!}
                                        </div>
                                    </div>

                                    <!-- login enabled -->
                                    <div class="row">
                                        <div class="col-md-3">
                                            {{ trans('general.login_enabled') }}
                                        </div>
                                        <div class="col-md-9">
                                            {!! $user->activated == '1'
                                                ? '<i class="fas fa-check text-success" aria-hidden="true"></i> ' . trans('general.yes')
                                                : '<i class="fas fa-times text-danger" aria-hidden="true"></i> ' . trans('general.no') !!}
                                        </div>
                                    </div>

                                    <!-- LDAP -->
                                    <div class="row">
                                        <div class="col-md-3">
                                            LDAP
                                        </div>
                                        <div class="col-md-9">
                                            {!! $user->ldap_import == '1'
                                                ? '<i class="fas fa-check text-success" aria-hidden="true"></i> ' . trans('general.yes')
                                                : '<i class="fas fa-times text-danger" aria-hidden="true"></i> ' . trans('general.no') !!}

                                        </div>
                                    </div>

                                    @if ($user->activated == '1')

                                        <!-- 2FA active -->
                                        <div class="row">
                                            <div class="col-md-3">
                                                {{ trans('admin/users/general.two_factor_active') }}
                                            </div>
                                            <div class="col-md-9">

                                                {!! $user->two_factor_active()
                                                    ? '<i class="fas fa-check text-success" aria-hidden="true"></i> ' . trans('general.yes')
                                                    : '<i class="fas fa-times text-danger" aria-hidden="true"></i> ' . trans('general.no') !!}

                                            </div>
                                        </div>

                                        <!-- 2FA enrolled -->
                                        <div class="row two_factor_resetrow">
                                            <div class="col-md-3">
                                                {{ trans('admin/users/general.two_factor_enrolled') }}
                                            </div>
                                            <div class="col-md-9" id="two_factor_reset_toggle">
                                                {!! $user->two_factor_active_and_enrolled()
                                                    ? '<i class="fas fa-check text-success" aria-hidden="true"></i> ' . trans('general.yes')
                                                    : '<i class="fas fa-times text-danger" aria-hidden="true"></i> ' . trans('general.no') !!}

                                            </div>
                                        </div>

                                        @if (Auth::user()->isSuperUser() &&
                                                $snipeSettings->two_factor_enabled != '0' &&
                                                $snipeSettings->two_factor_enabled != '')
                                            <!-- 2FA reset -->
                                            <div class="row">
                                                <div class="col-md-3">

                                                </div>
                                                <div class="col-md-9" style="margin-top: 10px;">

                                                    <a class="btn btn-default btn-sm pull-left" id="two_factor_reset"
                                                        style="margin-right: 10px;">
                                                        {{ trans('admin/settings/general.two_factor_reset') }}
                                                    </a>
                                                    <span id="two_factor_reseticon">
                                                    </span>
                                                    <span id="two_factor_resetresult">
                                                    </span>
                                                    <span id="two_factor_resetstatus">
                                                    </span>
                                                    <br>
                                                    <p class="help-block" style="line-height: 1.6;">
                                                        {{ trans('admin/settings/general.two_factor_reset_help') }}</p>


                                                </div>
                                            </div>
                                        @endif
                                    @endif


                                    @if ($user->notes)
                                        <!-- empty -->
                                        <div class="row">

                                            <div class="col-md-3">
                                                {{ trans('admin/users/table.notes') }}
                                            </div>
                                            <div class="col-md-9">
                                                {{ $user->notes }}
                                            </div>

                                        </div>
                                    @endif

                                </div> <!--/end striped container-->
                            </div> <!-- end col-md-9 -->



                        </div> <!--/.row-->
                    </div><!-- /.tab-pane -->

                    <div class="tab-pane" id="asset">
                        <!-- checked out assets table -->

                        @include('partials.asset-bulk-actions')

                        <div class="table table-responsive">

                            <table data-click-to-select="true"
                                data-columns="{{ \App\Presenters\AssetPresenter::dataTableLayout() }}"
                                data-cookie-id-table="userAssetsListingTable" data-pagination="true"
                                data-id-table="userAssetsListingTable" data-search="true" data-side-pagination="server"
                                data-show-columns="true" data-show-fullscreen="true" data-show-export="true"
                                data-show-footer="true" data-show-refresh="true" data-sort-order="asc"
                                data-sort-name="name" data-toolbar="#assetsBulkEditToolbar"
                                data-bulk-button-id="#bulkAssetEditButton" data-bulk-form-id="#assetsBulkForm"
                                id="userAssetsListingTable" class="table table-striped snipe-table"
                                data-url="{{ route('api.assets.index', ['assigned_to' => e($user->id), 'assigned_type' => 'App\Models\User']) }}"
                                data-export-options='{
                "fileName": "export-{{ str_slug($user->present()->fullName()) }}-assets-{{ date('Y-m-d') }}",
                "ignoreColumn": ["actions","image","change","checkbox","checkincheckout","icon"]
                }'>
                            </table>
                        </div>
                    </div><!-- /asset -->

                    <div class="tab-pane" id="licenses">
                        <div class="table-responsive">
                            <table data-cookie-id-table="userLicenseTable" data-id-table="userLicenseTable"
                                id="userLicenseTable" data-search="true" data-pagination="true"
                                data-side-pagination="client" data-show-columns="true" data-show-fullscreen="true"
                                data-show-export="true" data-show-footer="true" data-show-refresh="true"
                                data-sort-order="asc" data-sort-name="name"
                                class="table table-striped snipe-table table-hover"
                                data-export-options='{
                    "fileName": "export-license-{{ str_slug($user->username) }}-{{ date('Y-m-d') }}",
                    "ignoreColumn": ["actions","image","change","checkbox","checkincheckout","delete","download","icon"]
                    }'>

                                <thead>
                                    <tr>
                                        <th class="col-md-5">{{ trans('general.name') }}</th>
                                        <th>{{ trans('admin/hardware/form.serial') }}</th>
                                        <th data-footer-formatter="sumFormatter" data-fieldname="purchase_cost">
                                            {{ trans('general.purchase_cost') }}</th>
                                        <th>{{ trans('admin/licenses/form.purchase_order') }}</th>
                                        <th>{{ trans('general.order_number') }}</th>
                                        <th class="col-md-1 hidden-print">{{ trans('general.action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($user->licenses as $license)
                                        <tr>
                                            <td class="col-md-4">
                                                {!! $license->present()->nameUrl() !!}
                                            </td>
                                            <td class="col-md-4">
                                                @can('viewKeys', $license)
                                                    {!! $license->present()->serialUrl() !!}
                                                @else
                                                    ------------
                                                @endcan
                                            </td>
                                            <td class="col-md-2">
                                                {{ Helper::formatCurrencyOutput($license->purchase_cost) }}
                                            </td>
                                            <td>
                                                {{ $license->purchase_order }}
                                            </td>
                                            <td>
                                                {{ $license->order_number }}
                                            </td>
                                            <td class="hidden-print col-md-2">
                                                @can('update', $license)
                                                    <a href="{{ route('licenses.checkin', ['licenseSeatId' => $license->pivot->id, 'backto' => 'user']) }}"
                                                        class="btn btn-primary btn-sm hidden-print">{{ trans('general.checkin') }}</a>
                                                @endcan
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div><!-- /licenses-tab -->

                    <div class="tab-pane" id="accessories">
                        <div class="table-responsive">
                            <table data-cookie-id-table="userAccessoryTable" data-id-table="userAccessoryTable"
                                id="userAccessoryTable" data-search="true" data-pagination="true"
                                data-side-pagination="client" data-show-columns="true" data-show-fullscreen="true"
                                data-show-export="true" data-show-footer="true" data-show-refresh="true"
                                data-sort-order="asc" data-sort-name="name"
                                class="table table-striped snipe-table table-hover"
                                data-export-options='{
                    "fileName": "export-accessory-{{ str_slug($user->username) }}-{{ date('Y-m-d') }}",
                    "ignoreColumn": ["actions","image","change","checkbox","checkincheckout","delete","download","icon"]
                    }'>
                                <thead>
                                    <tr>
                                        <th class="col-md-5">{{ trans('general.name') }}</th>
                                        <th class-="col-md-5" data-fieldname="note">{{ trans('general.notes') }}</th>
                                        <th class="col-md-1" data-footer-formatter="sumFormatter"
                                            data-fieldname="purchase_cost">{{ trans('general.purchase_cost') }}</th>
                                        <th class="col-md-1 hidden-print">{{ trans('general.action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($user->accessories as $accessory)
                                        <tr>
                                            <td>{!! $accessory->present()->nameUrl() !!}</td>
                                            <td>{!! $accessory->pivot->note !!}</td>
                                            <td>
                                                {!! Helper::formatCurrencyOutput($accessory->purchase_cost) !!}
                                            </td>
                                            <td class="hidden-print">
                                                @can('checkin', $accessory)
                                                    <a href="{{ route('accessories.checkin.show', ['accessoryID' => $accessory->pivot->id, 'backto' => 'user']) }}"
                                                        class="btn btn-primary btn-sm hidden-print">{{ trans('general.checkin') }}</a>
                                                @endcan
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div><!-- /accessories-tab -->

                    <div class="tab-pane" id="consumables">
                        <div class="table-responsive">
                            <table data-cookie-id-table="userConsumableTable" data-id-table="userConsumableTable"
                                id="userConsumableTable" data-search="true" data-pagination="true"
                                data-side-pagination="client" data-show-columns="true" data-show-fullscreen="true"
                                data-show-export="true" data-show-footer="true" data-show-refresh="true"
                                data-sort-order="asc" data-sort-name="name"
                                class="table table-striped snipe-table table-hover"
                                data-export-options='{
                    "fileName": "export-consumable-{{ str_slug($user->username) }}-{{ date('Y-m-d') }}",
                    "ignoreColumn": ["actions","image","change","checkbox","checkincheckout","delete","download","icon"]
                    }'>
                                <thead>
                                    <tr>
                                        <th class="col-md-3">{{ trans('general.name') }}</th>
                                        <th class="col-md-2" data-footer-formatter="sumFormatter"
                                            data-fieldname="purchase_cost">{{ trans('general.purchase_cost') }}</th>
                                        <th class="col-md-2">{{ trans('general.date') }}</th>
                                        <th class="col-md-5">{{ trans('general.notes') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($user->consumables as $consumable)
                                        <tr>
                                            <td>{!! $consumable->present()->nameUrl() !!}</td>
                                            <td>
                                                {!! Helper::formatCurrencyOutput($consumable->purchase_cost) !!}
                                            </td>
                                            <td>{{ Helper::getFormattedDateObject($consumable->pivot->created_at, 'datetime', false) }}
                                            </td>
                                            <td>{{ $consumable->pivot->note }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div><!-- /consumables-tab -->

                    <div class="tab-pane" id="files">
                        <div class="row">

                            <div class="col-md-12 col-sm-12">
                                <div class="table-responsive">
                                    <table data-cookie-id-table="userUploadsTable" data-id-table="userUploadsTable"
                                        id="userUploadsTable" data-search="true" data-pagination="true"
                                        data-side-pagination="client" data-show-columns="true"
                                        data-show-fullscreen="true" data-show-export="false" data-show-footer="true"
                                        data-toolbar="#upload-toolbar" data-show-refresh="true" data-sort-order="asc"
                                        data-sort-name="name" class="table table-striped snipe-table"
                                        data-export-options='{
                                      "fileName": "export-license-uploads-{{ str_slug($user->name) }}-{{ date('Y-m-d') }}",
                                      "ignoreColumn": ["actions","image","change","checkbox","checkincheckout","delete","download","icon"]
                                  }'>
                                        <thead>
                                            <tr>
                                                <th class="col-md-3" data-searchable="true" data-visible="true">Document
                                                    Name</th>
                                                <th class="col-md-3" data-searchable="true" data-visible="true">Document
                                                    Front</th>
                                                <th class="col-md-3" data-searchable="true" data-visible="true">Document
                                                    Back</th>
                                                <th class="col-md-3" data-searchable="true" data-visible="true">Expiry
                                                    Date</th>
                                                <th class="col-md-3" data-searchable="true" data-visible="true">
                                                    Actions</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if ($hasUploads)
                                                @if (!empty($user->id_card_front || $user->id_card_back))
                                                    <tr>
                                                        <td>ID Card</td>
                                                        <td>
                                                            @if (!empty($user->id_card_front))
                                                                @php
                                                                    $fileExtension = pathinfo(
                                                                        $user->id_card_front,
                                                                        PATHINFO_EXTENSION,
                                                                    );
                                                                    $fileExtension = strtolower($fileExtension);
                                                                @endphp

                                                                @if (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'svg']))
                                                                    <a href="{{ route('show/userfile', ['userId' => $user->id, 'fileId' => 'id_card_front', 'download' => 'false']) }}"
                                                                        data-toggle="lightbox" data-type="image">
                                                                        <img src="{{ asset($user->id_card_front) }}"
                                                                            alt="ID Card Front" class="img-thumbnail"
                                                                            style="max-width: 50px;">
                                                                    </a>
                                                                @elseif ($fileExtension === 'pdf')
                                                                    <a href="{{ route('show/userfile', ['userId' => $user->id, 'fileId' => 'id_card_front', 'download' => 'false']) }}"
                                                                        target="_blank">
                                                                        <i class="fa fa-file-pdf"
                                                                            style="font-size: 24px; color: red;"></i>
                                                                    </a>
                                                                @else
                                                                    <i class="fa fa-times text-danger"></i>
                                                                    {{ trans('general.file_not_supported') }}
                                                                @endif
                                                            @else
                                                                <i class="fa fa-times text-danger"></i>
                                                                {{ trans('general.file_not_found') }}
                                                            @endif
                                                        </td>

                                                        <td>
                                                            @if (!empty($user->id_card_back))
                                                                @php
                                                                    $fileExtension = pathinfo(
                                                                        $user->id_card_back,
                                                                        PATHINFO_EXTENSION,
                                                                    );
                                                                @endphp

                                                                @if (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'svg']))
                                                                    <a href="{{ route('show/userfile', ['userId' => $user->id, 'fileId' => 'id_card_back', 'download' => 'false']) }}"
                                                                        data-toggle="lightbox" data-type="image">
                                                                        <img src="{{ asset($user->id_card_back) }}"
                                                                            alt="ID Card Front" class="img-thumbnail"
                                                                            style="max-width: 50px;">
                                                                    </a>
                                                                @elseif ($fileExtension === 'pdf')
                                                                    <a href="{{ route('show/userfile', ['userId' => $user->id, 'fileId' => 'id_card_back', 'download' => 'false']) }}"
                                                                        target="_blank">
                                                                        <i class="fa fa-file-pdf"
                                                                            style="font-size: 24px; color: red;"></i>
                                                                    </a>
                                                                @else
                                                                    <i class="fa fa-times text-danger"></i>
                                                                    {{ trans('general.file_not_supported') }}
                                                                @endif
                                                            @else
                                                                <i class="fa fa-times text-danger"></i>
                                                                {{ trans('general.file_not_found') }}
                                                            @endif
                                                        </td>
                                                        <td>{{ $user->expiry_date_id_card }}</td>
                                                        <td>
                                                            <nobr>
                                                                <a href="#" class="btn btn-sm btn-warning"
                                                                    data-toggle="modal" data-document-type="id_card"
                                                                    data-front-file="{{ $user->id_card_front }}"
                                                                    data-back-file="{{ $user->id_card_back }}"
                                                                    data-expiry-date="{{ $user->expiry_date_id_card }}"
                                                                    data-target="#uploadUserFileModal">
                                                                    <i class="fas fa-pencil-alt"
                                                                        aria-hidden="true"></i><span
                                                                        class="sr-only">Update</span>
                                                                </a>

                                                                <a href="#" class="btn btn-danger btn-sm"
                                                                    onclick="deleteFile(event, 'idCard'')">
                                                                    <i class="fas fa-trash"></i>
                                                                </a>

                                                                <form
                                                                    id="delete-form-idCard' action="{{ route('file.destroy', ['userId' => $user->id]) }}"
                                                                    method="POST" style="display: none;">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <input type="hidden" name="fileType"
                                                                        value="idCard'">
                                                                </form>
                                                            </nobr>
                                                        </td>
                                                    </tr>
                                                @endif
                                                @if (!empty($user->driving_license_local || $user->driving_license_local_back))
                                                    <tr>
                                                        <td>Driving License Local</td>
                                                        <td>
                                                            @if (!empty($user->driving_license_local))
                                                                @php
                                                                    $fileExtension = pathinfo(
                                                                        $user->driving_license_local,
                                                                        PATHINFO_EXTENSION,
                                                                    );
                                                                @endphp

                                                                @if (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'svg']))
                                                                    <a href="{{ route('show/userfile', ['userId' => $user->id, 'fileId' => 'driving_license_local', 'download' => 'false']) }}"
                                                                        data-toggle="lightbox" data-type="image">
                                                                        <img src="{{ asset($user->driving_license_local) }}"
                                                                            alt="ID Card Front" class="img-thumbnail"
                                                                            style="max-width: 50px;">
                                                                    </a>
                                                                @elseif ($fileExtension === 'pdf')
                                                                    <a href="{{ route('show/userfile', ['userId' => $user->id, 'fileId' => 'driving_license_local', 'download' => 'false']) }}"
                                                                        target="_blank">
                                                                        <i class="fa fa-file-pdf"
                                                                            style="font-size: 24px; color: red;"></i>
                                                                    </a>
                                                                @else
                                                                    <i class="fa fa-times text-danger"></i>
                                                                    {{ trans('general.file_not_supported') }}
                                                                @endif
                                                            @else
                                                                <i class="fa fa-times text-danger"></i>
                                                                {{ trans('general.file_not_found') }}
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if (!empty($user->driving_license_local_back))
                                                                @php
                                                                    $fileExtension = pathinfo(
                                                                        $user->driving_license_local_back,
                                                                        PATHINFO_EXTENSION,
                                                                    );
                                                                @endphp

                                                                @if (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'svg']))
                                                                    <a href="{{ route('show/userfile', ['userId' => $user->id, 'fileId' => 'driving_license_local_back', 'download' => 'false']) }}"
                                                                        data-toggle="lightbox" data-type="image">
                                                                        <img src="{{ asset($user->driving_license_local_back) }}"
                                                                            alt="ID Card Front" class="img-thumbnail"
                                                                            style="max-width: 50px;">
                                                                    </a>
                                                                @elseif ($fileExtension === 'pdf')
                                                                    <a href="{{ route('show/userfile', ['userId' => $user->id, 'fileId' => 'driving_license_local_back', 'download' => 'false']) }}"
                                                                        target="_blank">
                                                                        <i class="fa fa-file-pdf"
                                                                            style="font-size: 24px; color: red;"></i>
                                                                    </a>
                                                                @else
                                                                    <i class="fa fa-times text-danger"></i>
                                                                    {{ trans('general.file_not_supported') }}
                                                                @endif
                                                            @else
                                                                <i class="fa fa-times text-danger"></i>
                                                                {{ trans('general.file_not_found') }}
                                                            @endif
                                                        </td>
                                                        <td>{{ $user->expiry_date_driving_license_local }}</td>
                                                        <td>
                                                            <nobr> <a href="#" class="btn btn-sm btn-warning"
                                                                    data-toggle="modal"
                                                                    data-document-type="driving_license_local"
                                                                    data-front-file="{{ $user->driving_license_local }}"
                                                                    data-back-file="{{ $user->driving_license_local_back }}"
                                                                    data-expiry-date="{{ $user->expiry_date_driving_license_local }}"
                                                                    data-target="#uploadUserFileModal">
                                                                    <i class="fas fa-pencil-alt"
                                                                        aria-hidden="true"></i><span
                                                                        class="sr-only">Update</span>
                                                                </a>
                                                                <a href="#" class="btn btn-danger btn-sm"
                                                                    onclick="deleteFile(event, 'drivingLicenseLocal')">
                                                                    <i class="fas fa-trash"></i>
                                                                </a>

                                                                <form id="delete-form-drivingLicenseLocal"
                                                                    action="{{ route('file.destroy', ['userId' => $user->id]) }}"
                                                                    method="POST" style="display: none;">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <input type="hidden" name="fileType"
                                                                        value="drivingLicenseLocal">
                                                                </form>
                                                            </nobr>
                                                        </td>
                                                    </tr>
                                                @endif
                                                @if (!empty($user->driving_license_international || $user->driving_license_international_back))
                                                    <tr>
                                                        <td>Driving License International</td>
                                                        <td>
                                                            @if (!empty($user->driving_license_international))
                                                                @php
                                                                    $fileExtension = pathinfo(
                                                                        $user->driving_license_international,
                                                                        PATHINFO_EXTENSION,
                                                                    );
                                                                @endphp

                                                                @if (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'svg']))
                                                                    <a href="{{ route('show/userfile', ['userId' => $user->id, 'fileId' => 'driving_license_international', 'download' => 'false']) }}"
                                                                        data-toggle="lightbox" data-type="image">
                                                                        <img src="{{ asset($user->driving_license_international) }}"
                                                                            alt="ID Card Front" class="img-thumbnail"
                                                                            style="max-width: 50px;">
                                                                    </a>
                                                                @elseif ($fileExtension === 'pdf')
                                                                    <a href="{{ route('show/userfile', ['userId' => $user->id, 'fileId' => 'driving_license_international', 'download' => 'false']) }}"
                                                                        target="_blank">
                                                                        <i class="fa fa-file-pdf"
                                                                            style="font-size: 24px; color: red;"></i>
                                                                    </a>
                                                                @else
                                                                    <i class="fa fa-times text-danger"></i>
                                                                    {{ trans('general.file_not_supported') }}
                                                                @endif
                                                            @else
                                                                <i class="fa fa-times text-danger"></i>
                                                                {{ trans('general.file_not_found') }}
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if (!empty($user->driving_license_international_back))
                                                                @php
                                                                    $fileExtension = pathinfo(
                                                                        $user->driving_license_international_back,
                                                                        PATHINFO_EXTENSION,
                                                                    );
                                                                @endphp

                                                                @if (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'svg']))
                                                                    <a href="{{ route('show/userfile', ['userId' => $user->id, 'fileId' => 'driving_license_international_back', 'download' => 'false']) }}"
                                                                        data-toggle="lightbox" data-type="image">
                                                                        <img src="{{ asset($user->driving_license_international_back) }}"
                                                                            alt="ID Card Front" class="img-thumbnail"
                                                                            style="max-width: 50px;">
                                                                    </a>
                                                                @elseif ($fileExtension === 'pdf')
                                                                    <a href="{{ route('show/userfile', ['userId' => $user->id, 'fileId' => 'driving_license_international_back', 'download' => 'false']) }}"
                                                                        target="_blank">
                                                                        <i class="fa fa-file-pdf"
                                                                            style="font-size: 24px; color: red;"></i>
                                                                    </a>
                                                                @else
                                                                    <i class="fa fa-times text-danger"></i>
                                                                    {{ trans('general.file_not_supported') }}
                                                                @endif
                                                            @else
                                                                <i class="fa fa-times text-danger"></i>
                                                                {{ trans('general.file_not_found') }}
                                                            @endif
                                                        </td>
                                                        <td>{{ $user->expiry_date_driving_license_international }}</td>
                                                        <td>
                                                            <nobr> <a href="#" class="btn btn-sm btn-warning"
                                                                    data-toggle="modal"
                                                                    data-document-type="driving_license_international"
                                                                    data-front-file="{{ $user->driving_license_international }}"
                                                                    data-back-file="{{ $user->driving_license_international_back }}"
                                                                    data-expiry-date="{{ $user->expiry_date_driving_license_international }}"
                                                                    data-target="#uploadUserFileModal">
                                                                    <i class="fas fa-pencil-alt"
                                                                        aria-hidden="true"></i><span
                                                                        class="sr-only">Update</span>
                                                                </a>
                                                              
                                                                <a href="#" class="btn btn-danger btn-sm"
                                                                    onclick="deleteFile(event, 'drivingLicenseInternational')">
                                                                    <i class="fas fa-trash"></i>
                                                                </a>

                                                                <form id="delete-form-drivingLicenseInternational"
                                                                    action="{{ route('file.destroy', ['userId' => $user->id]) }}"
                                                                    method="POST" style="display: none;">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <input type="hidden" name="fileType"
                                                                        value="drivingLicenseInternational">
                                                                </form>
                                                            </nobr>
                                                        </td>
                                                    </tr>
                                                @endif
                                                @if (!empty($user->maltese_driving_license || $user->maltese_driving_license_back))
                                                    <tr>
                                                        <td>Maltese Driving License</td>
                                                        <td>
                                                            @if (!empty($user->maltese_driving_license))
                                                                @php
                                                                    $fileExtension = pathinfo(
                                                                        $user->maltese_driving_license,
                                                                        PATHINFO_EXTENSION,
                                                                    );
                                                                @endphp

                                                                @if (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'svg']))
                                                                    <a href="{{ route('show/userfile', ['userId' => $user->id, 'fileId' => 'maltese_driving_license', 'download' => 'false']) }}"
                                                                        data-toggle="lightbox" data-type="image">
                                                                        <img src="{{ asset($user->maltese_driving_license) }}"
                                                                            alt="ID Card Front" class="img-thumbnail"
                                                                            style="max-width: 50px;">
                                                                    </a>
                                                                @elseif ($fileExtension === 'pdf')
                                                                    <a href="{{ route('show/userfile', ['userId' => $user->id, 'fileId' => 'maltese_driving_license', 'download' => 'false']) }}"
                                                                        target="_blank">
                                                                        <i class="fa fa-file-pdf"
                                                                            style="font-size: 24px; color: red;"></i>
                                                                    </a>
                                                                @else
                                                                    <i class="fa fa-times text-danger"></i>
                                                                    {{ trans('general.file_not_supported') }}
                                                                @endif
                                                            @else
                                                                <i class="fa fa-times text-danger"></i>
                                                                {{ trans('general.file_not_found') }}
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if (!empty($user->maltese_driving_license_back))
                                                                @php
                                                                    $fileExtension = pathinfo(
                                                                        $user->maltese_driving_license_back,
                                                                        PATHINFO_EXTENSION,
                                                                    );
                                                                @endphp

                                                                @if (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'svg']))
                                                                    <a href="{{ route('show/userfile', ['userId' => $user->id, 'fileId' => 'maltese_driving_license_back', 'download' => 'false']) }}"
                                                                        data-toggle="lightbox" data-type="image">
                                                                        <img src="{{ asset($user->maltese_driving_license_back) }}"
                                                                            alt="ID Card Front" class="img-thumbnail"
                                                                            style="max-width: 50px;">
                                                                    </a>
                                                                @elseif ($fileExtension === 'pdf')
                                                                    <a href="{{ route('show/userfile', ['userId' => $user->id, 'fileId' => 'maltese_driving_license_back', 'download' => 'false']) }}"
                                                                        target="_blank">
                                                                        <i class="fa fa-file-pdf"
                                                                            style="font-size: 24px; color: red;"></i>
                                                                    </a>
                                                                @else
                                                                    <i class="fa fa-times text-danger"></i>
                                                                    {{ trans('general.file_not_supported') }}
                                                                @endif
                                                            @else
                                                                <i class="fa fa-times text-danger"></i>
                                                                {{ trans('general.file_not_found') }}
                                                            @endif
                                                        </td>
                                                        <td>{{ $user->expiry_date_maltese_license }}</td>
                                                        <td>
                                                            <nobr> <a href="#" class="btn btn-sm btn-warning"
                                                                    data-toggle="modal"
                                                                    data-document-type="maltese_license"
                                                                    data-front-file="{{ $user->maltese_driving_license }}"
                                                                    data-back-file="{{ $user->maltese_driving_license_back }}"
                                                                    data-expiry-date="{{ $user->expiry_date_maltese_license }}"
                                                                    data-target="#uploadUserFileModal">
                                                                    <i class="fas fa-pencil-alt"
                                                                        aria-hidden="true"></i><span
                                                                        class="sr-only">Update</span>
                                                                </a>
                                                              
                                                                <a href="#" class="btn btn-danger btn-sm"
                                                                    onclick="deleteFile(event, 'Maltese')">
                                                                    <i class="fas fa-trash"></i>
                                                                </a>

                                                                <form id="delete-form-Maltese"
                                                                    action="{{ route('file.destroy', ['userId' => $user->id]) }}"
                                                                    method="POST" style="display: none;">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <input type="hidden" name="fileType"
                                                                        value="Maltese">
                                                                </form>
                                                            </nobr>
                                                        </td>
                                                    </tr>
                                                @endif
                                                @if (!empty($user->taxi_tag || $user->taxi_tag_back))
                                                    <tr>
                                                        <td>Taxi Tag</td>
                                                        <td>
                                                            @if (!empty($user->taxi_tag))
                                                                @php
                                                                    $fileExtension = pathinfo(
                                                                        $user->taxi_tag,
                                                                        PATHINFO_EXTENSION,
                                                                    );
                                                                @endphp

                                                                @if (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'svg']))
                                                                    <a href="{{ route('show/userfile', ['userId' => $user->id, 'fileId' => 'taxi_tag', 'download' => 'false']) }}"
                                                                        data-toggle="lightbox" data-type="image">
                                                                        <img src="{{ asset($user->taxi_tag) }}"
                                                                            alt="ID Card Front" class="img-thumbnail"
                                                                            style="max-width: 50px;">
                                                                    </a>
                                                                @elseif ($fileExtension === 'pdf')
                                                                    <a href="{{ route('show/userfile', ['userId' => $user->id, 'fileId' => 'taxi_tag', 'download' => 'false']) }}"
                                                                        target="_blank">
                                                                        <i class="fa fa-file-pdf"
                                                                            style="font-size: 24px; color: red;"></i>
                                                                    </a>
                                                                @else
                                                                    <i class="fa fa-times text-danger"></i>
                                                                    {{ trans('general.file_not_supported') }}
                                                                @endif
                                                            @else
                                                                <i class="fa fa-times text-danger"></i>
                                                                {{ trans('general.file_not_found') }}
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if (!empty($user->taxi_tag_back))
                                                                @php
                                                                    $fileExtension = pathinfo(
                                                                        $user->taxi_tag_back,
                                                                        PATHINFO_EXTENSION,
                                                                    );
                                                                @endphp

                                                                @if (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'svg']))
                                                                    <a href="{{ route('show/userfile', ['userId' => $user->id, 'fileId' => 'taxi_tag_back', 'download' => 'false']) }}"
                                                                        data-toggle="lightbox" data-type="image">
                                                                        <img src="{{ asset($user->taxi_tag_back) }}"
                                                                            alt="ID Card Front" class="img-thumbnail"
                                                                            style="max-width: 50px;">
                                                                    </a>
                                                                @elseif ($fileExtension === 'pdf')
                                                                    <a href="{{ route('show/userfile', ['userId' => $user->id, 'fileId' => 'taxi_tag_back', 'download' => 'false']) }}"
                                                                        target="_blank">
                                                                        <i class="fa fa-file-pdf"
                                                                            style="font-size: 24px; color: red;"></i>
                                                                    </a>
                                                                @else
                                                                    <i class="fa fa-times text-danger"></i>
                                                                    {{ trans('general.file_not_supported') }}
                                                                @endif
                                                            @else
                                                                <i class="fa fa-times text-danger"></i>
                                                                {{ trans('general.file_not_found') }}
                                                            @endif
                                                        </td>
                                                        <td>{{ $user->expiry_date_taxi_tag }}</td>
                                                        <td>
                                                            <nobr> <a href="#" class="btn btn-sm btn-warning"
                                                                    data-toggle="modal" data-document-type="taxi_tag"
                                                                    data-front-file="{{ $user->taxi_tag }}"
                                                                    data-back-file="{{ $user->taxi_tag_back }}"
                                                                    data-expiry-date="{{ $user->expiry_date_taxi_tag }}"
                                                                    data-target="#uploadUserFileModal">
                                                                    <i class="fas fa-pencil-alt"
                                                                        aria-hidden="true"></i><span
                                                                        class="sr-only">Update</span>
                                                                </a> 
                                                                <a href="#" class="btn btn-danger btn-sm"
                                                                    onclick="deleteFile(event, 'taxi')">
                                                                    <i class="fas fa-trash"></i>
                                                                </a>

                                                                <form id="delete-form-taxi"
                                                                    action="{{ route('file.destroy', ['userId' => $user->id]) }}"
                                                                    method="POST" style="display: none;">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <input type="hidden" name="fileType" value="taxi">
                                                                </form>
                                                            </nobr>
                                                        </td>
                                                    </tr>
                                                @endif
                                            @else
                                                <tr>
                                                    <td colspan="4" class="text-center">No matching Record Found</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div> <!--/ROW-->
                    </div><!--/FILES-->

                    <div class="tab-pane" id="history">
                        <div class="table-responsive">


                            <table data-click-to-select="true" data-cookie-id-table="usersHistoryTable"
                                data-pagination="true" data-id-table="usersHistoryTable" data-search="true"
                                data-side-pagination="server" data-show-columns="true" data-show-fullscreen="true"
                                data-show-export="true" data-show-refresh="true" data-sort-order="desc"
                                id="usersHistoryTable" class="table table-striped snipe-table"
                                data-url="{{ route('api.activity.index', ['target_id' => $user->id, 'target_type' => 'user']) }}"
                                data-export-options='{
                                          "fileName": "export-{{ str_slug($user->present()->fullName) }}-history-{{ date('Y-m-d') }}",
                                          "ignoreColumn": ["actions","image","change","checkbox","checkincheckout","icon"]
                                          }'>
                                <thead>
                                    <tr>
                                        <th data-field="icon" style="width: 40px;" class="hidden-xs"
                                            data-formatter="iconFormatter">Icon</th>
                                        <th data-field="created_at" data-formatter="dateDisplayFormatter"
                                            data-sortable="true">{{ trans('general.date') }}</th>
                                        <th data-field="item" data-formatter="polymorphicItemFormatter">
                                            {{ trans('general.item') }}</th>
                                        <th data-field="action_type">{{ trans('general.action') }}</th>
                                        <th data-field="target" data-formatter="polymorphicItemFormatter">
                                            {{ trans('general.target') }}</th>
                                        <th data-field="note">{{ trans('general.notes') }}</th>
                                        @if ($snipeSettings->require_accept_signature == '1')
                                            <th data-field="signature_file" data-visible="false"
                                                data-formatter="imageFormatter">{{ trans('general.signature') }}</th>
                                        @endif
                                        <th data-field="item.serial" data-visible="false">
                                            {{ trans('admin/hardware/table.serial') }}</th>
                                        <th data-field="admin" data-formatter="usersLinkObjFormatter">
                                            {{ trans('general.admin') }}</th>


                                    </tr>
                                </thead>
                            </table>

                        </div>
                    </div><!-- /.tab-pane -->

                    <div class="tab-pane" id="managed">
                        <div class="table-responsive">
                            <table class="table display table-striped">
                                <thead>
                                    <tr>
                                        <th class="col-md-8">{{ trans('general.name') }}</th>
                                        <th class="col-md-4">{{ trans('general.date') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($user->managedLocations as $location)
                                        <tr>
                                            <td>{!! $location->present()->nameUrl() !!}</td>
                                            <td>{{ $location->created_at }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div><!-- /consumables-tab -->
                </div><!-- /.tab-content -->
            </div><!-- nav-tabs-custom -->
        </div>
    </div>

    @can('update', \App\Models\User::class)
        @include ('modals.upload-file', ['item_type' => 'user', 'item_id' => $user->id])
    @endcan



@stop

@section('moar_scripts')
    @include ('partials.bootstrap-table', ['simple_view' => true])
    <script nonce="{{ csrf_token() }}">
        $(function() {

            $("#two_factor_reset").click(function() {
                $("#two_factor_resetrow").removeClass('success');
                $("#two_factor_resetrow").removeClass('danger');
                $("#two_factor_resetstatus").html('');
                $("#two_factor_reseticon").html('<i class="fas fa-spinner spin"></i>');
                $.ajax({
                    url: '{{ route('api.users.two_factor_reset', ['id' => $user->id]) }}',
                    type: 'POST',
                    data: {},
                    headers: {
                        "X-Requested-With": 'XMLHttpRequest',
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType: 'json',

                    success: function(data) {
                        $("#two_factor_reset_toggle").html('').html(
                            '<i class="fas fa-times text-danger" aria-hidden="true"></i> {{ trans('general.no') }}'
                        );
                        $("#two_factor_reseticon").html('');
                        $("#two_factor_resetstatus").html(
                            '<i class="fas fa-check text-success"></i>' + data.message);

                    },

                    error: function(data) {
                        $("#two_factor_reseticon").html('');
                        $("#two_factor_reseticon").html(
                            '<i class="fas fa-exclamation-triangle text-danger"></i>');
                        $('#two_factor_resetstatus').text(data.message);
                    }


                });
            });


            //binds to onchange event of your input field
            var uploadedFileSize = 0;
            $('#fileupload').bind('change', function() {
                uploadedFileSize = this.files[0].size;
                $('#progress-container').css('visibility', 'visible');
            });

            $('#fileupload').fileupload({
                //maxChunkSize: 100000,
                dataType: 'json',
                formData: {
                    _token: '{{ csrf_token() }}',
                    notes: $('#notes').val(),
                },

                progress: function(e, data) {
                    //var overallProgress = $('#fileupload').fileupload('progress');
                    //var activeUploads = $('#fileupload').fileupload('active');
                    var progress = parseInt((data.loaded / uploadedFileSize) * 100, 10);
                    $('.progress-bar').addClass('progress-bar-warning').css('width', progress + '%');
                    $('#progress-bar-text').html(progress + '%');
                    //console.dir(overallProgress);
                },

                done: function(e, data) {
                    console.dir(data);
                    // We use this instead of the fail option, since our API
                    // returns a 200 OK status which always shows as "success"

                    if (data && data.jqXHR && data.jqXHR.responseJSON && data.jqXHR.responseJSON
                        .status === "error") {
                        var errorMessage = data.jqXHR.responseJSON.messages["file.0"];
                        $('#progress-bar-text').html(errorMessage[0]);
                        $('.progress-bar').removeClass('progress-bar-warning').addClass(
                            'progress-bar-danger').css('width', '100%');
                        $('.progress-checkmark').fadeIn('fast').html(
                            '<i class="fas fa-times fa-3x icon-white" style="color: #d9534f"></i>');
                    } else {
                        $('.progress-bar').removeClass('progress-bar-warning').addClass(
                            'progress-bar-success').css('width', '100%');
                        $('.progress-checkmark').fadeIn('fast');
                        $('#progress-container').delay(950).css('visibility', 'visible');
                        $('.progress-bar-text').html('Finished!');
                        $('.progress-checkmark').fadeIn('fast').html(
                            '<i class="fas fa-check fa-3x icon-white" style="color: green"></i>');
                        $.each(data.result, function(index, file) {
                            $('<tr><td>' + file.note + '</td><<td>' + file.filename +
                                '</td></tr>').prependTo("#files-table > tbody");
                        });
                    }
                    $('#progress').removeClass('active');


                }
            });
        });
        $(document).ready(function() {
            // Show/Hide fields based on document type selection
            $('#document_type').on('change', function() {
                var documentType = $(this).val();
                $('input[name="fileType"]').val(documentType);

                // Hide all fields first
                $('.id_card_fields, .driving_license_local_fields, .driving_license_maltese_fields, .driving_license_international_fields, .taxi_tag_fields')
                .hide();

                // Show the fields corresponding to the selected document type
                if (documentType === 'id_card') {
                    $('.id_card_fields').show();
                } else if (documentType === 'driving_license_local') {
                    $('.driving_license_local_fields').show();
                } else if (documentType === 'maltese_license') {
                    $('.driving_license_maltese_fields').show();
                } else if (documentType === 'driving_license_international') {
                    $('.driving_license_international_fields').show();
                } else if (documentType === 'taxi_tag') {
                    $('.taxi_tag_fields').show();
                }
            });

            // When the modal is shown for editing, update the fields
            $('a[data-toggle="modal"]').click(function() {
                var documentType = $(this).data('document-type');
                console.log(documentType);
                var frontFilePath = $(this).data('front-file');
                var backFilePath = $(this).data('back-file');
                var expiryDate = $(this).data('expiry-date');
                $('#document_type').val(documentType).trigger('change');
                  console.log(frontFilePath,backFilePath,expiryDate);
                // Display current file paths (if available)


                if (frontFilePath) {
                    const fullPath = `${window.location.origin}/${frontFilePath}`; // Construct full URL
                    $('#' + documentType + '_front_preview').html(
                        `<img src="${fullPath}" alt="ID Card Front" class="img-thumbnail" style="max-width: 50px;">`
                    );
                } else {
                    $('#' + documentType + '_front_preview').text('No file selected');
                }

                if (backFilePath) {
                    const fullPath = `${window.location.origin}/${backFilePath}`; // Construct full URL
                    $('#' + documentType + '_back_preview').html(
                        `<img src="${fullPath}" alt="ID Card Front" class="img-thumbnail" style="max-width: 50px;">`
                    );
                } else {
                    $('#' + documentType + '_back_preview').text('No file selected');
                }

                // Populate expiry date if available
                if (expiryDate) {
                    console.log(expiryDate);
                    $('input[name="expiry_date_' + documentType + '"]').val(expiryDate);
                }
            });

            // Form submission validation before uploading files
            $('.form-horizontal1').on('submit', function(e) {
                var documentType = $('#document_type').val();
                var frontFile = false;
                var backFile = false;
                var acceptedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'svg', 'pdf'];

                // Validate file type function
                function validateFileType(fileInput) {
                    var filePath = $(fileInput).val();
                    if (filePath) {
                        var fileExtension = filePath.split('.').pop().toLowerCase();
                        return acceptedExtensions.includes(fileExtension);
                    }
                    return true; // No file means no error for type
                }

                // Check if front and back files are provided based on document type
                if (documentType === 'id_card') {
                    frontFile = $('input[name="id_card_front"]').val() !== '';
                    backFile = $('input[name="id_card_back"]').val() !== '';
                } else if (documentType === 'driving_license_local') {
                    frontFile = $('input[name="driving_license_local_front"]').val() !== '';
                    backFile = $('input[name="driving_license_local_back"]').val() !== '';
                } else if (documentType === 'maltese_license') {
                    frontFile = $('input[name="maltese_license_front"]').val() !== '';
                    backFile = $('input[name="maltese_license_back"]').val() !== '';
                } else if (documentType === 'driving_license_international') {
                    frontFile = $('input[name="driving_license_international_front"]').val() !== '';
                    backFile = $('input[name="driving_license_international_back"]').val() !== '';
                } else if (documentType === 'taxi_tag') {
                    frontFile = $('input[name="taxi_tag_front"]').val() !== '';
                    backFile = $('input[name="taxi_tag_back"]').val() !== '';
                }

                // Ensure at least one file (front or back) is uploaded
                if (!frontFile && !backFile) {
                    alert('At least one document (front or back) must be uploaded.');
                    e.preventDefault(); // Prevent form submission
                    return;
                }

                // Validate file types for both front and back files
                var isFrontFileValid = frontFile ? validateFileType($('input[name="' + documentType +
                    '_front"]')) : true;
                var isBackFileValid = backFile ? validateFileType($('input[name="' + documentType +
                    '_back"]')) : true;

                if (!isFrontFileValid || !isBackFileValid) {
                    alert('Please upload only files with these extensions: ' + acceptedExtensions.join(
                        ', ') + '.');
                    e.preventDefault(); // Prevent form submission if file type is invalid
                }
            });
        });




        function deleteFile(event, fileId) {
            event.preventDefault();
            if (confirm('Are you sure you want to delete this file?')) {
                document.getElementById(`delete-form-${fileId}`).submit();
            }
        }
    </script>


@stop
{{-- @include('modals.edit-upload-file') --}}
