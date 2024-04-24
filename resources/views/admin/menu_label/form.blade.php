<div class="row">
    <div class="col-lg-3 col-md-3 col-12 mt-2">
        <label for="type">@lang('menu_label.type')</label><span class="text-danger">*</span>
        <select class="form-select form-select-sm @error('type') is-invalid @enderror" name="type" id="type">
            <option @if(@$data['data']->type == 'cms') selected @endif value="cms">@lang('common.cms')</option>
            <option @if(@$data['data']->type == 'pos') selected @endif value="pos">@lang('common.pos')</option>
        </select>
        @error('type')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-lg-3 col-md-3 col-12 mt-2">
        <label for="label_name">@lang('menu_label.label_name')</label><span class="text-danger">*</span>
        <input type="text" class="form-control form-control-sm @error('label_name') is-invalid @enderror" name="label_name" id="label_name" value="{{ @$data['data']->label_name }}">
        @error('label_name')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-lg-3 col-md-3 col-12 mt-2">
        <label for="label_name_bn">@lang('menu_label.label_name_bn')</label>
        <input type="text" class="form-control form-control-sm @error('label_name_bn') is-invalid @enderror" name="label_name_bn" id="label_name_bn" value="{{ @$data['data']->label_name_bn }}">
        @error('label_name_bn')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-lg-3 col-md-3 col-12 mt-2">
        <label for="status">@lang('common.status')</label><span class="text-danger">*</span>
        <select class="form-select form-select-sm @error('status') is-invalid @enderror" name="status" id="status">
            <option @if(@$data['data']->status == '1') selected @endif value="1">@lang('common.active')</option>
            <option @if(@$data['data']->status == '0') selected @endif value="0">@lang('common.inactive')</option>
        </select>
        @error('status')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12 mt-2" style="text-align: right">
        <button type="submit" class="btn btn-sm btn-success">
            <i class="fa fa-save"></i> @lang('common.submit')
        </button>
    </div>
</div>
