<div class="card-body">
    <form action="{{ route('admin.money_safe.watch-money-to-safe-transaction', $id) }}" method="get">
        <div class="row  @if (app()->isLocale('ar')) justify-content-end @else justify-content-start @endif ">
            <div class="col-2">
                <div class="form-group">
                    {!! Form::text('start_date', request()->get('start_date'), [
                        'class' => 'form-control datepicker',
                        'placeholder' => __('lang.start_date'),
                    ]) !!}
                </div>
            </div>
            <div class="col-2">
                <div class="form-group">
                    {!! Form::text('end_date', request()->get('end_date'), ['class' => 'form-control datepicker', 'placeholder' => __('lang.end_date')]) !!}
                </div>
            </div>
            {{-- <div class="col-2"></div> --}}
            <div class="col-4">
                <div class="form-group">
                    <button type="submit" name="submit" class="btn btn-primary width-100" title="search">
                        <i class="fa fa-eye"></i> {{ __('Search') }}</button>
                </div>
            </div>
            {{-- <div class="col-2">
            <div class="form-group">
                {!! Form::select(
                    'currecy_change',$selected_currencies,null,
                    ['class' => 'form-control select2']
                ) !!}
            </div>
        </div>
        <div class="col-2">
            <div class="form-group">
                <button type="submit" name="submit" class="btn btn-success width-100 text-center" title="search">
                     {{ __('lang.change_to') }}</button>
            </div>
        </div> --}}
        </div>
    </form>
</div>
