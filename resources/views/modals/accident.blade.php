{{-- See snipeit_modals.js for what powers this --}}
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h2 class="modal-title">Accident Type</h2>
        </div>
        <div class="modal-body">
            <form action="{{ route('accident_type') }}" method="POST">
                @csrf
                <div class="alert alert-danger" id="modal_error_msg" style="display:none"></div>
                <div class="dynamic-form-row">
                    <div class="col-md-4 col-xs-12">
                        <label for="modal-name">{{ trans('general.name') }}:</label>
                    </div>
                    <div class="col-md-8 col-xs-12 required">
                        <input type='text' name="name" id='modal-name' class="form-control">
                    </div>
                </div>
            
                <div class="dynamic-form-row">
                    <div class="col-md-4 col-xs-12">
                        <label for="modal-amount">{{ trans('general.amount') }}:</label>
                    </div>
                    <div class="col-md-8 col-xs-12 required">
                        <input type='text' name="amount" id='modal-amount' class="form-control" required>
                    </div>
                </div>
            
            </form>
            
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('button.cancel') }}</button>
            <button type="button" class="btn btn-primary" id="modal-save">{{ trans('general.save') }}</button>
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
