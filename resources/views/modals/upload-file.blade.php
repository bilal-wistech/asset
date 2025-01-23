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

<!-- Modal -->
<!-- Modal -->
<div class="modal fade" id="uploadUserFileModal" tabindex="-1" role="dialog" aria-labelledby="uploadFileModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h2 class="modal-title" id="uploadFileModalLabel">{{ trans('general.file_upload') }}</h2>
            </div>
            {{ Form::open([
                'method' => 'POST',
                'route' => ['upload/' . $item_type, $item_id],
                'files' => true,
                'class' => 'form-horizontal1',
            ]) }}
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
           
            <div class="modal-body">
                <!-- Document Type -->
                <div class="row">
                    <div class="col-md-3">
                        <label for="document_type" class="control-label">Document Type:</label>
                    </div>
                    <div class="col-md-9">
                        <select id="document_type" name="document_type" class="form-control" required>
                            <option value="">Select Document Type</option>
                            <option value="id_card">ID Card</option>
                            <option value="driving_license_local">Driving License (Local)</option>
                            <option value="driving_license_international">Driving License (International)</option>
                            <option value="maltese_license">Maltese Driving License</option>
                            <option value="taxi_tag">Taxi Tag</option>
                        </select>
                    </div>
                </div>
             
                <!-- Dynamic Fields for Document Types -->
                <!-- ID Card Fields -->
                <div class="id_card_fields dynamic-fields" style="display: none;">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="id_card_front" class="control-label">ID Card (Front):</label>
                        </div>
                        <div class="col-md-9">
                            <input type="file" name="id_card_front" class="form-control" accept="image/*,.pdf,.doc,.docx">
                            <!-- Preview Span -->
                            <span id="id_card_front_preview"></span>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-3">
                            <label for="id_card_back" class="control-label">ID Card (Back):</label>
                        </div>
                        <div class="col-md-9">
                            <input type="file" name="id_card_back" class="form-control" accept="image/*,.pdf,.doc,.docx">
                            <!-- Preview Span -->
                            <span id="id_card_back_preview"></span>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-3">
                            <label for="expiry_date_id_card" class="control-label">Expiry Date:</label>
                        </div>
                        <div class="col-md-9">
                            <input type="date" name="expiry_date_id_card" class="form-control">
                        </div>
                    </div>
                </div>

                <!-- Local License Fields -->
                <div class="driving_license_local_fields dynamic-fields" style="display: none;">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="driving_license_local" class="control-label">Driving License (Local) Front:</label>
                        </div>
                        <div class="col-md-9">
                            <input type="file" name="driving_license_local" class="form-control" accept="image/*,.pdf,.doc,.docx">
                            <!-- Preview Span -->
                            <span id="driving_license_local_front_preview"></span>
                        </div>
                    </div>
                    <br>

                    <div class="row">
                        <div class="col-md-3">
                            <label for="driving_license_local_back" class="control-label">Driving License (Local) Back:</label>
                        </div>
                        <div class="col-md-9">
                            <input type="file" name="driving_license_local_back" class="form-control" accept="image/*,.pdf,.doc,.docx">
                            <!-- Preview Span -->
                            <span id="driving_license_local_back_preview"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <label for="expiry_date_driving_license_local" class="control-label">Expiry Date:</label>
                        </div>
                        <div class="col-md-9">
                            <input type="date" name="expiry_date_driving_license_local" class="form-control">
                        </div>
                    </div>
                </div>

                <!-- International License Fields -->
                <div class="driving_license_international_fields dynamic-fields" style="display: none;">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="driving_license_international" class="control-label">Driving License (International) Front:</label>
                        </div>
                        <div class="col-md-9">
                            <input type="file" name="driving_license_international" class="form-control" accept="image/*,.pdf,.doc,.docx">
                            <!-- Preview Span -->
                            <span id="driving_license_international_front_preview"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <label for="driving_license_international_back" class="control-label">Driving License (International) Back:</label>
                        </div>
                        <div class="col-md-9">
                            <input type="file" name="driving_license_international_back" class="form-control" accept="image/*,.pdf,.doc,.docx">
                            <!-- Preview Span -->
                            <span id="driving_license_international_back_preview"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <label for="expiry_date_driving_license_international" class="control-label">Expiry Date:</label>
                        </div>
                        <div class="col-md-9">
                            <input type="date" name="expiry_date_driving_license_international" class="form-control">
                        </div>
                    </div>
                </div>

                <!-- Maltese License Fields -->
                <div class="driving_license_maltese_fields dynamic-fields" style="display: none;"> 
                    
                 <div class="row">
                        <div class="col-md-3">
                            <label for="maltese_driving_license" class="control-label">Driving License (Maltese):</label>
                        </div>
                        <div class="col-md-9">
                            <input type="file" name="maltese_driving_license" class="form-control" accept="image/*,.pdf,.doc,.docx">
                            <!-- Preview Span -->
                            <span id="maltese_license_front_preview"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <label for="maltese_driving_license_back" class="control-label">Driving License (Maltese) Back:</label>
                        </div>
                        <div class="col-md-9">
                            <input type="file" name="maltese_driving_license_back" class="form-control" accept="image/*,.pdf,.doc,.docx">
                            <!-- Preview Span -->
                            <span id="maltese_license_back_preview"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <label for="expiry_date_maltese_license" class="control-label">Expiry Date:</label>
                        </div>
                        <div class="col-md-9">
                            <input type="date" name="expiry_date_maltese_license" class="form-control">
                        </div> 
                    </div> 
                 </div>
               


                <!-- Taxi Tag Fields -->
                <div class="row taxi_tag_fields" style="display: none;">
                    <div class="col-md-3">
                        <label for="taxi_tag" class="control-label">Taxi Tag Front:</label>
                    </div>
                    <div class="col-md-9">
                        <input type="file" name="taxi_tag" class="form-control" accept="image/*,.pdf,.doc,.docx">
                        <!-- Preview Span -->
                        <span id="taxi_tag_front_preview"></span>
                    </div>
                    <br>
                    <br>
                    <div class="col-md-3">
                        <label for="taxi_tag_back" class="control-label">Taxi Tag Back:</label>
                    </div>
                    <div class="col-md-9">
                        <input type="file" name="taxi_tag_back" class="form-control" accept="image/*,.pdf,.doc,.docx">
                        <!-- Preview Span -->
                        <span id="taxi_tag_back_preview"></span>
                    </div>
                    <br>
                    <br>
                    <div class="col-md-3">
                        <label for="expiry_date_taxi_tag" class="control-label">Expiry Date:</label>
                    </div>
                    <div class="col-md-9">
                        <input type="date" name="expiry_date_taxi_tag" class="form-control">
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Save changes</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
