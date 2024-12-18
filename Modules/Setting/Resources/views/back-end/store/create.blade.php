<div class="modal-dialog" role="document">
    <div class="modal-content">
        {!! Form::open(['url' => route('admin.store.store'), 'id'=>'form-store','method' => 'post']) !!}

        <div
            class="modal-header position-relative border-0 d-flex justify-content-between align-items-center @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">

            <h4 class="modal-title px-2 position-relative">@lang('lang.add_store')
                <span class=" header-modal-pill"></span>
            </h4>
            <button type="button"
                    class="close btn btn-danger d-flex justify-content-center align-items-center rounded-circle text-white"
                    data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <span class="position-absolute modal-border"></span>
        </div>

        <div
            class="modal-body row @if (app()->isLocale('ar')) flex-row-reverse justify-content-end @else justify-content-start flex-row @endif align-items-center">
            <div
                class=" d-flex mb-2 align-items-center form-group @if (app()->isLocale('ar')) flex-row-reverse mr-3 @else ml-3 @endif">
                <label class="modal-label-width" for="name">@lang('lang.name') *</label>
                <div
                    class="select_body input-wrapper d-flex justify-content-between align-items-center mb-2 form-group @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">
                    <input type="text" required style="width: 100%"
                           class="form-control initial-balance-input my-0 @if (app()->isLocale('ar')) text-end @else text-start @endif"
                           placeholder="@lang('lang.name')" name="name" value="{{ old('name') }}" >
                    @error('name')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div
                class=" d-flex mb-2 align-items-center form-group @if (app()->isLocale('ar')) flex-row-reverse mr-3 @else ml-3 @endif">
                <label class="modal-label-width" for="phone_number">@lang('lang.phone_number') </label>
                <div
                    class="select_body input-wrapper d-flex justify-content-between align-items-center mb-2 form-group @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">
                    <input type="text" required style="width: 100%"
                           class="form-control initial-balance-input my-0 @if (app()->isLocale('ar')) text-end @else text-start @endif"
                           placeholder="@lang('lang.phone_number')" name="phone_number" value="{{ old('phone_number') }}" >
                    @error('phone_number')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div
                class=" d-flex mb-2 align-items-center form-group @if (app()->isLocale('ar')) flex-row-reverse mr-3 @else ml-3 @endif">
                <label class="modal-label-width" for="email">@lang('lang.email') </label>
                <div
                    class="select_body input-wrapper d-flex justify-content-between align-items-center mb-2 form-group @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">
                    <input type="text" required style="width: 100%"
                           class="form-control initial-balance-input my-0 @if (app()->isLocale('ar')) text-end @else text-start @endif"
                           placeholder="@lang('lang.email')" name="email" value="{{ old('email') }}" >
                    @error('email')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div
                class=" d-flex mb-2 align-items-center form-group @if (app()->isLocale('ar')) flex-row-reverse mr-3 @else ml-3 @endif">
                <label class="modal-label-width" for="manager_name">@lang('lang.manager_name') </label>
                <div
                    class="select_body input-wrapper d-flex justify-content-between align-items-center mb-2 form-group @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">
                    <input type="text" required style="width: 100%"
                           class="form-control initial-balance-input my-0 @if (app()->isLocale('ar')) text-end @else text-start @endif"
                           placeholder="@lang('lang.manager_name')" name="manager_name" value="{{ old('manager_name') }}" >
                    @error('manager_name')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>



            <div
                class=" d-flex mb-2 align-items-center form-group @if (app()->isLocale('ar')) flex-row-reverse mr-3 @else ml-3 @endif">
                <label class="modal-label-width" for="manager_mobile_number">@lang('lang.manager_mobile_number') </label>
                <div
                    class="select_body input-wrapper d-flex justify-content-between align-items-center mb-2 form-group @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">
                    <input type="text" required style="width: 100%"
                           class="form-control initial-balance-input my-0 @if (app()->isLocale('ar')) text-end @else text-start @endif"
                           placeholder="@lang('lang.manager_mobile_number')" name="manager_mobile_number" value="{{ old('manager_mobile_number') }}" >
                    @error('manager_mobile_number')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>


            <div
                class=" d-flex mb-2 align-items-center form-group @if (app()->isLocale('ar')) flex-row-reverse mr-3 @else ml-3 @endif">
                <label class="modal-label-width" for="details">@lang('lang.details') </label>
                <div
                    class="select_body input-wrapper d-flex justify-content-between align-items-center mb-2 form-group @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">
                    {!! Form::textarea('details',null, ['class' => 'form-control' , 'placeholder' => __('lang.details') , 'rows' => '2']);  !!}

                    @error('details')
                      <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <div class="modal-footer d-flex justify-content-center align-content-center gap-3">
            <button type="submit" class="col-3 py-1 btn btn-main">@lang('lang.save')</button>
            <button type="button" class="col-3 py-1 btn btn-danger" data-dismiss="modal">@lang('lang.close')</button>
        </div>

        {!! Form::close() !!}

    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
