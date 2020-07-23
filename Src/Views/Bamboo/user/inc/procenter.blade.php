


<div class="form-group  @error('pro_id') has-error @enderror">
    <label for="password" class="col-sm-2 control-label">请选择省区<span class="required">*</span> </label>
    <div class="col-sm-10">
        <select class="form-control" name="pro_id" style="width: 300px">
            <option value="" >--请选择省区--</option>
            @foreach($proInfos as $pro)
                <option value="{{$pro['id']}}" @if(isset($info['pro_id']) && $info['pro_id'] == $pro['id']) selected="selected" @endif>{{$pro['title']}}</option>
            @endforeach
        </select>
    </div>
</div>



<div class="form-group  @error('center_id') has-error @enderror">
    <label for="password" class="col-sm-2 control-label">请选中心<span class="required">*</span> </label>
    <div class="col-sm-10">
        <select class="form-control" name="center_id" style="width: 300px">
            <option value="" >--请选中心--</option>
            @foreach($centerInfos as $center)
                <option value="{{$center['id']}}" @if(isset($info['pro_id']) && $info['center_id'] == $center['id']) selected="selected" @endif>{{$center['title']}}</option>
            @endforeach
        </select>
    </div>
</div>
