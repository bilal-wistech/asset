<!-- Modal -->
<div class="modal fade" id="uploadFileModal" tabindex="-1" role="dialog" aria-labelledby="uploadFileModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h2 class="modal-title" id="uploadFileModalLabel">{{ trans('general.file_upload') }}</h4>
            </div>
            {{ Form::open([
            'method' => 'POST',
            'route' => ['upload/'.$item_type, $item_id],
            'files' => true,
            'class' => 'form-horizontal' ]) }}
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
            <div class="modal-body">
                <div class="row">
                <div class="col-md-3">
                    <label for="name" class="control-label">Document Name:</label>
                </div>
                <div class="col-md-9">
                    <input type="text" name="name" class="form-control">
                </div>
                </div>
                <br>
                <div class="row">
                <div class="col-md-3">
                    <label for="expiry_date" class="control-label">Expiry Date:</label>
                </div>
                <div class="col-md-9 ">
                <div class="input-group date" data-provide="datepicker" data-date-clear-btn="true" data-date-format="yyyy-mm-dd"
                data-autoclose="true">
                <input type="text" name="expiry_date" class="form-control" required>
                <span class="input-group-addon"><i class="fas fa-calendar" aria-hidden="true"></i></span>
                </div>                                                                  
      
                   
                </div>
                </div>

                <div class="row">
                    <div class="col-md-3">

                        <label class="btn btn-default">
                            {{ trans('button.select_file')  }}
                            <input type="file" name="file[]" multiple="true" class="js-uploadFile" id="uploadFile" data-maxsize="{{ Helper::file_upload_max_size() }}" accept="image/*,.csv,.zip,.rar,.doc,.docx,.xls,.xlsx,.xml,.lic,.xlsx,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel,text/plain,.pdf,application/rtf,application/json" style="display:none" required>
                        </label>

                    </div>
                    <div class="col-md-9">
                        <span id="uploadFile-info"></span>
                    </div>
                    <div class="col-md-12">
                        <p class="help-block" id="uploadFile-status">{{ trans('general.upload_filetypes_help', ['size' => Helper::file_upload_max_size_readable()]) }}</p>
                    </div>

                    <div class="col-md-12">
                        {{ Form::textarea('notes', old('notes', old('notes')), ['class' => 'form-control','placeholder' => 'Notes (Optional)', 'rows'=>3, 'aria-label' => 'file']) }}
                    </div>
                </div>

            </div> <!-- /.modal-body-->
            <div class="modal-footer">
                <a href="#" class="pull-left" data-dismiss="modal">{{ trans('button.cancel') }}</a>
                <button type="submit" class="btn btn-primary">{{ trans('button.upload') }}</button>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
