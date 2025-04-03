@php use Modules\AddStock\Entities\AddStockLine;use Modules\Product\Entities\Product; @endphp
<style type="text/css">
    .text-center {
        text-align: center;
    }

    .text-uppercase {
        text-transform: uppercase;
    }

    /*Css related to printing of barcode*/
    .label-border-outer {
        border: 0.1px solid grey !important;
    }

    .label-border-internal {
        /*border: 0.1px dotted grey !important;*/
    }

    .sticker-border {
        border: 0.1px dotted grey !important;
        overflow: hidden;
        box-sizing: border-box;
    }

    #preview_box {
        padding-left: 30px !important;
    }

    @media print {
        .content-wrapper {
            border-left: none !important;
            /*fix border issue on invoice*/
        }

        .label-border-outer {
            border: none !important;
        }

        .label-border-internal {
            border: none !important;
        }

        .sticker-border {
            border: none !important;
        }

        #preview_box {
            padding-left: 0px !important;
        }

        #toast-container {
            display: none !important;
        }

        .tooltip {
            display: none !important;
        }

        .btn {
            display: none !important;
        }
    }

    @media print {
        #preview_body {
            display: block !important;
        }
    }

    @page {
        margin-top: 0in;
        margin-bottom: 0in;
        margin-left: 0in;
        margin-right: 0in;

    }
</style>

<title>{{ __('lang.print_labels') }}</title>
<button class="btn btn-success" onclick="window.print()">@lang('lang.print')</button>
<div id="preview_body">
    @php
    $loop_count = 0;
    @endphp
    @foreach ($product_details as $details)
    @while ($details['qty'] > 0)
    <div class="sticker-border text-center" style="display: flex;width:fit-content">
        <div style="width:200px !important;text-align: left;padding: 5px;display: flex;justify-content: space-between">

            <div>
                <span style="margin: 4px;display: block;font-size: 12px">
                    Lorem, ipsum dolor.
                </span>
                <span style="margin: 4px;display: block;font-size: 12px">
                    Lorem, ipsum dolor.
                </span>
                <span style="margin: 4px;display: block;font-size: 12px">
                    Lorem, ipsum dolor.
                </span>
                <span style="margin: 4px;display: block;font-size: 12px">
                    Lorem, ipsum dolor.
                </span>
            </div>

            <div style="height:0.7in">
                <img style="height: 100%" src="{{ asset('assets/back-end/images/QR_code.svg') }}">
            </div>
        </div>
        <div style="width:200px !important;text-align: center;padding: 10px;">
            <h6 style="margin: 0;text-transform: uppercase;font-weight: 700">
                Lorem ipsum dolor sit amet consectetur
            </h6>
            <p style="margin:4px">
                Lorem, ipsum dolor.
            </p>
            <span style="margin:4px;display: block">
                Lorem, ipsum dolor.
            </span>
        </div>
    </div>



    @php
    $details['qty'] = $details['qty'] - 1;
    @endphp
    @endwhile
    @endforeach

</div>


<script type="text/javascript"></script>
