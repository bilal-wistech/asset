@extends('layouts/default')

{{-- Page title --}}
@section('title')
    {{ sprintf('%s %s', trans('Accident Details for Asset '), ' ') }}
    @parent
@stop

{{-- Page content --}}
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-default">
                <div class="box-body">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th style="width: 200px;">Accident Name</th>
                                <td>{{ $data->accident_name ?? '' }}</td>
                            </tr>
                            <tr>
                                <th style="width: 200px;">Accident Number</th>
                                <td>{{ $data->accident_number ?? '' }}</td>
                            </tr>

                            <tr>
                                <th style="width: 200px;"> Accident Images</th>
                                <td>
                                    @php
                                        $filteredImages = array_filter($AccidentImages, fn($image) => !empty($image));
                                    @endphp

                                    @if (!empty($filteredImages) && count($filteredImages) > 0)
                                        @foreach ($filteredImages as $image)
                                            <img src="{{ asset($image) }}" alt="Accident Image"
                                                style="width: 100px; height: auto; margin: 5px;">
                                        @endforeach
                                    @else
                                        No images available.
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th style="width: 200px;">Username</th>
                                <td>{{ $data->user->username ?? '' }}</td>
                            </tr>
                            <tr>
                                <th style="width: 200px;">Asset</th>
                                <td>{{ $data->asset_id ?? '' }}</td>
                            </tr>

                            <tr>
                                <th style="width: 200px;">Accident Date</th>
                                <td>{{ \Carbon\Carbon::parse($data->accident_date)->format('d-M-Y') ?? '' }}</td>
                            </tr>

                            <tr>
                                <th style="width: 200px;">Location</th>
                                <td>{{ $data->findLocation->name ?? '' }}</td>
                            </tr>
                            <tr>
                                <th style="width: 200px;">Notes</th>
                                <td>{{ $data->note ?? '' }}</td>
                            </tr>
                            <tr>
                                <th style="width: 200px;">Recived by user</th>
                                <td>{{ $data->received_by_user ?? '' }}</td>
                            </tr>
                            <tr>
                                <th style="width: 200px;">Claim Opening</th>
                                <td>{{ $data->claim_opening ?? '' }}</td>
                            </tr>
                            <tr>
                                <th style="width: 200px;">Damage Amount</th>
                                <td>{{ $data->damage_amount ?? '' }}</td>
                            </tr>
                            <tr>
                                <th style="width: 200px;">Claimable</th>
                                <td>{{ $data->claimable ?? '' }}</td>
                            </tr>
                            <tr>
                                <th style="width: 200px;">Responsibility</th>
                                <td>{{ $data->responsibility ?? '' }}</td>
                            </tr>
                            <tr>
                                <th style="width: 200px;">Responsibility Amount</th>
                                <td>{{ $data->responsibility_amount ?? '' }}</td>
                            </tr>
                            <tr>
                                <th style="width: 200px;">Previous Total</th>
                                <td>{{ $data->previous_total ?? '' }}</td>
                            </tr>
                            <tr>
                                <th style="width: 200px;">Relevant Files</th>
                                <td>
                                    @php
                                        $relevantfiles = array_filter($RelevantFiles, fn($file) => !empty($file));
                                    @endphp
                                    @if (!empty($relevantfiles) && count($relevantfiles) > 0 && !empty($RelevantFileNumbers))
                                        @foreach ($relevantfiles as $index => $file)
                                            <a href="{{ asset($file) }}"
                                                target="_blank">{{ $RelevantFileNumbers[$index] }}</a><br>
                                        @endforeach
                                    @else
                                        No relevant files available.
                                    @endif
                                </td>
                            </tr>

                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
@stop

@section('moar_scripts')
    @include ('partials.bootstrap-table')
@stop
