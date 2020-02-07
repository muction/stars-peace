<div class="form-group @error('role') has-error @enderror">
    <label for="textarea" class="col-sm-2 control-label">选择角色</label>
    <div class="col-sm-10">
        @foreach($roles as $role)
            <label class="lyear-checkbox checkbox-inline checkbox-primary">
                <input type="checkbox" name="role[]" value="{{ $role['id'] }}" @if( isset($hasRoles) && in_array( $role['id'] , $hasRoles )) checked="checked" @endif >
                <span> {{ $role['display_name'] }} </span>
            </label>
        @endforeach
    </div>
</div>

<div class="form-group @error('status') has-error @enderror">
    <label for="textarea" class="col-sm-2 control-label">状态 <span class="required">*</span></label>
    <div class="col-sm-10">
        <label class="lyear-radio radio-inline radio-primary">
            <input type="radio" name="status" value="1"
                   @if( old('status' , isset($info['status']) ? $info['status'] : 1 ) == 1  ) checked="checked" @endif >
            <span>启用</span>
        </label>
        <label class="lyear-radio radio-inline radio-primary">
            <input type="radio" name="status" value="0"
                   @if( old('status' , isset($info['status']) ? $info['status'] : 900 ) == 0  ) checked="checked" @endif
            >
            <span>关闭</span>
        </label>
    </div>
</div>
