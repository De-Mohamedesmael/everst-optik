<!-- Modal -->
<div class="modal-dialog  modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="uploaded_files">{{translate('uploaded_files')}}</h5>
            <button type="button" data-dismiss="modal" aria-label="Close" class="close btn btn-danger d-flex justify-content-center align-items-center rounded-circle text-white">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <div class="modal-body">
            @php
                $product_price_sell=$prescription->product_price_sell;
                $data_len=json_decode($prescription->data);
                if(isset($data_len->VA_amount)){
                    $total_vu=$data_len->VA_amount->total;
                    $product_price_sell=$product_price_sell - $total_vu;
                }
             @endphp
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>{{translate('name')}}</th>
                            <th>{{translate('value')}}</th>
                            <th>{{translate('price')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td> {{$prescription->product_name.'|'.$prescription->product_sku}}</td>
                                <td> </td>
                                <td> {{$product_price_sell}}</td>
                            <tr>
                            <tr>
                                <td> {{translate('TinTing_amount')}}</td>
                                <td> {{$data_len->VA->TinTing->text}}</td>
                                <td> {{$data_len->VA_amount->TinTing_amount}}</td>
                            <tr>
                            <tr>
                                <td> {{translate('Base_amount')}}</td>
                                <td> {{$data_len->VA->Base->text}}</td>
                                <td> {{$data_len->VA_amount->Base_amount}}</td>
                            <tr>
                            <tr>
                                <td> {{translate('Ozel_amount')}}</td>
                                <td> {{$data_len->VA->Ozel->text}}</td>
                                <td> {{$data_len->VA_amount->Ozel_amount}}</td>
                            <tr>
                            <tr>
                                <td> {{translate('code_title')}}</td>
                                <td> {{$data_len->VA->code->text}}</td>
                                <td> 0 </td>
                            <tr>
                        </tbody>
                        <tfoot>
                        <tr>
                            <th class="table_totals" style="text-align: right">
                                @lang('lang.total')</th>
                            <td></td>
                            <td>{{$prescription->product_price_sell}}</td>
                        </tr>
                        </tfoot>
                    </table>
                </div>

            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-outline-danger" data-dismiss="modal">{{translate('Close')}}</button>
        </div>

    </div>
</div>
