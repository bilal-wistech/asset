<!-- uninsured users -->
<div class="form-group">
    <div class="col-sm-offset-3 col-sm-10">
        <label>
        <input type="checkbox" value="1" name="uninsured_users" id="uninsured_users" class="minimal" {{ Request::old('uninsured_users', $item->uninsured_users) == '1' ? ' checked="checked"' : '' }}> {{ $requestable_text }}
        </label>

    </div>
</div>
