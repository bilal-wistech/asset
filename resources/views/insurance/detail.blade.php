@extends('layouts/default')

{{-- Page title --}}
@section('title')
    @parent
@stop

{{-- Right header --}}
@section('header_right')


@stop

{{-- Page content --}}
@section('content')
<?php
// echo "jdhfehdishd";
?>
    <div class="row">

      

      

        <div class="col-md-12">




            <!-- Custom Tabs -->
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">

                    <li class="active">
                        <a href="#details" data-toggle="tab">
                          <span class="hidden-lg hidden-md">
                          <i class="fas fa-info-circle fa-2x"></i>
                          </span>
                          <span class="hidden-xs hidden-sm">{{ trans('admin/users/general.info') }}</span>
                        </a>
                    </li>

                    <li>
                        <a href="#drivers" data-toggle="tab">
                          <span class="hidden-lg hidden-md">
                            <i class="far fa-save fa-2x" aria-hidden="true"></i>
                          </span>
                          <span class="hidden-xs hidden-sm">{{ trans('general.drivers') }}
                          {!! (count($allowed_drivers)> 0 ) ? '<badge class="badge badge-secondary">'.number_format(count($allowed_drivers)).'</badge>' : '' !!}
                        </a>
                    </li>

           

                    <li>
                        <a href="#files" data-toggle="tab">
                          <span class="hidden-lg hidden-md">
                            <i class="far fa-file fa-2x" aria-hidden="true"></i>
                          </span>
                         
                        </a>
                    </li>

                    <li>
                    <a href="#files" data-toggle="tab">
                        <span class="hidden-lg hidden-md">
                            <i class="far fa-file fa-2x"></i>
                        </span>
                        <span class="hidden-xs hidden-sm">{{ trans('general.file_uploads') }}
                        {!! ($queryToGetUploads->count() > 0 ) ? '<badge class="badge badge-secondary">'.number_format($queryToGetUploads->count()).'</badge>' : '' !!}
                        </span>
                    </a>
                    </li>

                    <li>


                    @can('update', \App\Models\Insurance::class)
                        <li class="pull-right">
                            <a href="#" data-toggle="modal" data-target="#uploadFileModal">
                                <i class="fas fa-paperclip" aria-hidden="true"></i>
                                {{ trans('button.upload') }}
                            </a>
                        </li>
                    @endcan
                   
                  


                </ul>
                
                <div class="tab-content">
                    <div class="tab-pane fade in active" id="details">
                        <div class="row">
                            <div class="col-md-8">

                                <!-- start striped rows -->
                                <div class="container row-striped">

                                   



                                    @if ($ins->insurance_date)
                                  
                                  <div class="row">
                                      <div class="col-md-2">
                                          <strong>{{ trans('general.asset_id') }}</strong>
                                      </div>
                                      <div class="col-md-6">
                                          <a href="">{{ $ins->asset->name }}</a>
                                      </div>
                                  </div>
                              @endif

                              @if ($ins->insurance_from)
                                        <div class="row">
                                            <div class="col-md-2">
                                                <strong>{{ trans('general.vendor_id') }}</strong>
                                            </div>
                                            <div class="col-md-6">
                                                {{ $ins->supplierInfo->name }}
                                            </div>
                                        </div>
                                    @endif
                                   
 
                                    @if ($ins->insurance_date)
                                        <div class="row">
                                            <div class="col-md-2">
                                                <strong>{{ trans('general.insurance_date') }}</strong>
                                            </div>
                                            <div class="col-md-6">
                                                {{ $ins->insurance_date }}
                                            </div>
                                        </div>
                                    @endif
                                      
                                   
                                        
                                    
                                   

                                    @if ($ins->insurance_to)
                                        <div class="row">
                                            <div class="col-md-2">
                                                <strong>{{ trans('general.insurance_to') }}</strong>
                                            </div>
                                            <div class="col-md-6">
                                                {{ $ins->insurance_to }}
                                            </div>
                                        </div>
                                    @endif
                                    @if ($ins->insurance_from)
                                        <div class="row">
                                            <div class="col-md-2">
                                                <strong>{{ trans('general.insurance_from') }}</strong>
                                            </div>
                                            <div class="col-md-6">
                                                {{ $ins->insurance_from }}
                                            </div>
                                        </div>
                                    @endif

                                    @if ($ins->amount)
                                        <div class="row">
                                            <div class="col-md-2">
                                                <strong>Amount Insuared</strong>
                                            </div>
                                            <div class="col-md-6">
                                                {{ $ins->amount }}
                                            </div>
                                        </div>
                                    @endif

                                    @if ($ins->premium_type)
                                        <div class="row">
                                            <div class="col-md-2">
                                                <strong>{{ trans('general.premium_type') }}</strong>
                                            </div>
                                            <div class="col-md-6">
                                                {{ $ins->premium_type }}
                                            </div>
                                        </div>
                                    @endif
                                    @if ($ins->cost)
                                        <div class="row">
                                            <div class="col-md-2">
                                                <strong>Premium Cost</strong>
                                            </div>
                                            <div class="col-md-6">
                                                {{ $ins->cost }}
                                            </div>
                                        </div>
                                    @endif

                   

                                    @if ($ins->driver_cost)
                                        <div class="row">
                                            <div class="col-md-2">
                                                <strong>Add Drivers Amount</strong>
                                            </div>
                                            <div class="col-md-6">
                                                {{ $ins->driver_cost }}
                                            </div>
                                        </div>
                                    @endif

                                    @if ($ins->created_at)
                                        <div class="row">
                                            <div class="col-md-2">
                                                <strong>{{ trans('general.created_at') }}</strong>
                                            </div>
                                            <div class="col-md-6">
                                                {{ $ins->created_at }}
                                            </div>
                                        </div>
                                        @else
                                        <div class="alert alert-info alert-block">
                                    <i class="fas fa-info-circle"></i>
                                {{ trans('general.no_results') }}
                                </div>
                                    @endif




        

                                </div> <!-- end row-striped -->

                            </div><!-- /col-md-8 -->

                        </div><!-- /row -->
                    </div><!-- /.tab-pane asset details -->

                    <div class="tab-pane fade" id="drivers">
                        <div class="row">
                            <div class="col-md-12">
                                <!-- drivers  table -->

                            @if (count($allowed_drivers)> 0 )
                            
                                @foreach($allowed_drivers as $driver)
                                <ul>
                                    <li>{{ $driver }}</li>
                                </ul>

                                @endforeach
                            @endif

                            </div><!-- /col -->
                        </div> <!-- row -->
                    </div> <!-- /.tab-pane software -->

                  

             




                    <div class="tab-pane" id="files">
          <div class="row">

            <div class="col-md-12 col-sm-12">
              <div class="table-responsive">
                  <table
                          data-cookie-id-table="userUploadsTable"
                          data-id-table="userUploadsTable"
                          id="userUploadsTable"
                          data-search="true"
                          data-pagination="true"
                          data-side-pagination="client"
                          data-show-columns="true"
                          data-show-fullscreen="true"
                          data-show-export="true"
                          data-show-footer="true"
                          data-toolbar="#upload-toolbar"
                          data-show-refresh="true"
                          data-sort-order="asc"
                          data-sort-name="name"
                          class="table table-striped snipe-table"
                          data-export-options='{
                    "fileName": "export-license-uploads-{{ str_slug($user->name) }}-{{ date('Y-m-d') }}",
                    "ignoreColumn": ["actions","image","change","checkbox","checkincheckout","delete","download","icon"]
                    }'>

                  <thead>
                    <tr>
                        <th data-visible="true" data-field="icon" data-sortable="true">{{trans('general.file_type')}}</th>
                        <th class="col-md-2" data-searchable="true" data-visible="true" data-field="image">{{ trans('general.image') }}</th>
                        <th class="col-md-2" data-searchable="true" data-visible="true" data-field="name">{{ trans('general.name') }}</th>
                        <th class="col-md-2" data-searchable="true" data-visible="true" data-field="expiry_date">{{ trans('general.expiry_date') }}</th>
                        <th class="col-md-2" data-searchable="true" data-visible="true" data-field="filename" data-sortable="true">{{ trans('general.file_name') }}</th>
                        <th class="col-md-1" data-searchable="true" data-visible="true" data-field="filesize">{{ trans('general.filesize') }}</th>
                        <th class="col-md-2" data-searchable="true" data-visible="true" data-field="notes" data-sortable="true">{{ trans('general.notes') }}</th>
                        <th class="col-md-1" data-searchable="true" data-visible="true" data-field="download">{{ trans('general.download') }}</th>
                        <th class="col-md-2" data-searchable="true" data-visible="true" data-field="created_at" data-sortable="true">{{ trans('general.created_at') }}</th>
                        <th class="col-md-1" data-searchable="true" data-visible="true" data-field="actions">{{ trans('table.actions') }}</th>
                    </tr>
                  </thead>
                  <tbody>
                   
                    @foreach ($queryToGetUploads as $file)
                    <?php
                    //   dd($file)
                    ?>
                   
                        <tr>
                            <td>
                                <i class="{{ Helper::filetype_icon($file->filename) }} icon-med" aria-hidden="true"></i>
                                <span class="sr-only">{{ Helper::filetype_icon($file->filename) }}</span>

                            </td>
                            <td>
                                @if (($file->filename) && (Storage::exists('private_uploads/assets/'.$file->filename)))
                                   @if (Helper::checkUploadIsImage($file->get_src('assets')))
                                        <a href="{{ route('show/assetfile', ['assetId' => $file->item_id, 'fileId' => $file->id, 'download' => 'false']) }}" data-toggle="lightbox" data-type="image"><img src="{{ route('show/assetfile', ['assetId' => $file->item_id, 'fileId' => $file->id]) }}" class="img-thumbnail" style="max-width: 50px;"></a>
                                    @else
                                        {{ trans('general.preview_not_available') }}
                                    @endif
                                @else
                                    <i class="fa fa-times text-danger" aria-hidden="true"></i>
                                        {{ trans('general.file_not_found') }}
                                @endif
                            </td>
                           
                             <td>
                            @if($file->name)
                           
                                {{ $file->name }}
                            
                            @endif
                            </td>
                            <td>
                            @if($file->expiry_date)
                            
                                {{ $file->expiry_date }}
                            
                            @endif
                            </td>
                            <td>
                                {{ $file->filename }}
                            </td>
                            <td data-value="{{ (Storage::exists('private_uploads/assets/'.$file->filename)) ? Storage::size('private_uploads/assets/'.$file->filename) : '' }}">
                                {{ (Storage::exists('private_uploads/assets/'.$file->filename)) ? Helper::formatFilesizeUnits(Storage::size('private_uploads/assets/'.$file->filename)) : '' }}
                            </td>

                            <td>
                                @if ($file->note)
                                    {{ $file->note }}
                                @endif
                            </td>
                            <td>
                                @if ($file->filename)
                                    @if (Storage::exists('private_uploads/assets/'.$file->filename))
                                        <a href="{{ route('show/assetfile', [$user->id, $file->id]) }}" class="btn btn-default">
                                            <i class="fas fa-download" aria-hidden="true"></i>
                                            <span class="sr-only">{{ trans('general.download') }}</span>
                                        </a>
                                    @endif
                                @endif
                            </td>
                            <td>{{ $file->created_at }}</td>

                            <td>
                                <a class="btn delete-asset btn-danger btn-sm hidden-print" href="{{ route('userfile.destroy', [$user->id, $file->id]) }}" data-content="Are you sure you wish to delete this file?" data-title="Delete {{ $file->filename }}?">
                                    <i class="fa fa-trash icon-white" aria-hidden="true"></i>
                                    <span class="sr-only">{{ trans('general.delete') }}</span>
                                </a>
                            </td>



                        </tr>
                    @endforeach

                  </tbody>
                </table>
              </div>
            </div>
          </div> <!--/ROW-->
        </div><!--/FILES-->

                   
                </div> <!-- /. tab-content -->
            </div> <!-- /.nav-tabs-custom -->
        </div> <!-- /. col-md-12 -->
    </div> <!-- /. row -->

    @can('update', \App\Models\Insurance::class)
        @include ('modals.upload-file', ['item_type' => 'asset', 'item_id' => $ins->id])
    @endcan
  

@stop

@section('moar_scripts')
    @include ('partials.bootstrap-table')

@stop
