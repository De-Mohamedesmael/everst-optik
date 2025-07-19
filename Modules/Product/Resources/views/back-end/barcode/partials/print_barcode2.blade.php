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
        <div style="width:200px !important;text-align: left;padding: 5px;">
            <h3 style="margin: 0;text-transform: uppercase">
                @if ($print['name'])
                    {{ data_get($details, 'details.product_name', '') }}
                @endif
            </h3>
            <p style="margin:0 5px">
                @if ($print['color'])
                    {{ data_get($details, 'details.color_name', '') }}
                @endif
            </p>
            <span style="margin:0 5px;display: block">
                @if ($print['size'])
                    {{ data_get($details, 'details.size_name', '') }}
                @endif
            </span>
        </div>
        <div style="width:200px !important;text-align: center;font-size: 14px;margin:0 5px">
            <div style="font-size: 22px">
                @if ($print['color'])
                    {{ data_get($details, 'details.price', '') }} TL
                @endif
            </div>
            <div>
                {{-- $details['details']['brand_name'] --}}
                {{ data_get($details, 'details.brand_name', '') }}
            </div>
            <div style="display: flex;gap: 10px">
                <div>
                    deleted
                </div>
                <div>
                    {{-- $details['details']['updated_at']->format('Y-m-d') --}}
                    {{ data_get($details, 'details.updated_at', '')?->format('Y-m-d') }}
                </div>
            </div>
        </div>
    </div>



    @php
    $details['qty'] = $details['qty'] - 1;
    @endphp
    @endwhile
    @endforeach

</div>


<script type="text/javascript"></script>
