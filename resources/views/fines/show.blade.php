@extends('layouts/default')

{{-- Page title --}}
@section('title')
    {{ sprintf('%s %s', trans('Fine Details for Asset '), $fine->asset->asset_tag) }}
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
                                <th style="width: 200px;">Fine Number</th>
                                <td>{{ $fine->fine_number ?? '' }}</td>
                            </tr>
                            <tr>
                                <th style="width: 200px;">Fine Date</th>
                                <td>{{ \Carbon\Carbon::parse($fine->fine_date)->format('d-M-Y') ?? '' }}</td>
                            </tr>
                            <tr>
                                <th style="width: 200px;">Fine Type</th>
                                <td>{{ $fine->type->name ?? '' }}</td>
                            </tr>
                            <tr>
                                <th style="width: 200px;">Fine Amount</th>
                                <td>{{ $fine->amount ?? 0 }}</td>
                            </tr>
                            <tr>
                                <th style="width: 200px;">Asset</th>
                                <td>{{ $fine->asset->asset_tag ?? '' }}</td>
                            </tr>
                            <tr>
                                <th style="width: 200px;">Username</th>
                                <td>{{ $fine->user->username ?? '' }}</td>
                            </tr>
                            <tr>
                                <th style="width: 200px;">Location</th>
                                <td>{{ $fine->findLocation->name ?? '' }}</td>
                            </tr>
                            <tr>
                                <th style="width: 200px;">Notes</th>
                                <td>{{ $fine->note ?? '' }}</td>
                            </tr>
                            <tr>
                                <th style="width: 200px;">Images</th>
                                <td>
                                    @php
                                        $filteredImages = array_filter($fineImages, fn($image) => !empty($image));
                                    @endphp

                                    @if (!empty($filteredImages) && count($filteredImages) > 0)
                                        @foreach ($filteredImages as $image)
                                            <img src="{{ asset($image) }}" alt="Fine Image"
                                                style="width: 100px; height: auto; margin: 5px;">
                                        @endforeach
                                    @else
                                        No images available.
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
