@php
    use Modules\Setting\Entities\System;$letter_footer = System::getProperty('letter_footer');
@endphp
@if(!empty($letter_footer))
    <div class="row" style="text-align: center; width: 100%;">
        <img src="{{asset('uploads/'.$letter_footer)}}" alt="footer" style="width: 100%;">
    </div>
@endif
