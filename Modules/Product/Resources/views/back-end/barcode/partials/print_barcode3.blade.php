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
<button class="btn btn-success" onclick="window.print()">@lang('lang.print') </button>
<div id="preview_body" style="display: flex;flex-direction: column">
    @php
    $loop_count = 0;
    @endphp
    @foreach ($product_details as $details)
    @while ($details['qty'] > 0)
    <div style="height:fit-content !important;padding: 4px; line-height: {{ $page_height }}in;  display: inline-block;width: 1.2in;"
        class="sticker-border text-center">
        <div
            style="display:inline-block;vertical-align:middle;line-height:14px !important; font-size: 14px;width: 100%;">
            <div style="display: flex;justify-content: center;gap: 10px;">
                <p class="text-center" style="padding: 2px !important; margin: 0px;">
                    @if (!empty($print['name']))
                    @if (!empty($print['size']) && !empty($details['details']->size_name))
                    {{ str_replace($details['details']->size_name, '', $details['details']->product_name) }}
                    @elseif (!empty($print['color']) && !empty($details['details']->color_name))
                    {{ str_replace($details['details']->color_name, '', $details['details']->product_name) }}
                    @else
                    {{ $details['details']->product_name }}
                    @endif
                    @if (!empty($print['color']) && !empty($details['details']->color_name))
                    {{ $details['details']->color_name }}
                    @endif
                    @endif
                </p>
                @if (!empty($print['size']) && !empty($details['details']->size_name))
                <p style="margin: 0">
                    {{ $details['details']->size_name }}&nbsp;&nbsp;
                </p>
                @endif
            </div>


            @if (!empty($print['free_text']))
            <span style="display: block !important">
                {{ $print['free_text'] }}
            </span>
            @endif

            <div class="center-block" style="max-width: 95%; overflow: hidden; padding-left: 6px; vertical-align: top;">
                @if($page_height == 3)
                {!! DNS1D::getBarcodeSVG($details['details']->sku, "C128", 1,25, '#2A3239') !!}
                @else
                {!! DNS1D::getBarcodeSVG($details['details']->sku, "C128", 1,20, '#2A3239') !!}
                @endif
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