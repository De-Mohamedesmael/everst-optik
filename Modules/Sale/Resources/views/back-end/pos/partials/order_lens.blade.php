<!-- customer_details modal -->
<div id="order_lens_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"
    class="modal fade text-left">
    <div role="document" class="modal-dialog">
        <div class="modal-content">
            <div
                class="modal-header position-relative border-0 d-flex justify-content-between align-items-center @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif">
                <h5 class="modal-title position-relative  d-flex align-items-center" style="gap: 5px;">{{ __('lang.customer_details') }}
                    <span class=" header-pill"></span>
                </h5>

                <button type="button" data-dismiss="modal" aria-label="Close"
                    class="close btn btn-danger d-flex justify-content-center align-items-center rounded-circle text-white"><span
                        aria-hidden="true" style="border-radius: 10px !important;"><i class="dripicons-cross"></i></span></button>
                <span class="position-absolute modal-border"></span>
            </div>
            <div class="modal-body">
                <ul class="nav nav-tabs mb-2 @if (app()->isLocale('ar')) flex-row-reverse @else flex-row @endif"
                    role="tablist">
                    <li class="nav-item">
                        <a class="nav-link  active " href="#create"
                           role="tab" data-toggle="tab">{{translate('create_lens')}}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " href="#preview_lens"
                           role="tab" data-toggle="tab">{{translate('preview_lens')}}</a>
                    </li>

                </ul>

                <div class="tab-content">
                    <div role="tabpanel"
                         class="tab-pane fade  show active"
                         id="create">

                        @include('sale::back-end.pos.partials.order_lens_form')
                    </div>

                    <div role="tabpanel"
                         class="tab-pane fade "
                         id="preview_lens">

                        @include('lens::back-end.brand_lens.partial.brands_with_features',['brand_lens'=>$brand_lens])
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary " data-dismiss="modal">{{ __('lang.close') }}</button>
            </div>
        </div>
    </div>
</div>
<script>
    $('#')
</script>
