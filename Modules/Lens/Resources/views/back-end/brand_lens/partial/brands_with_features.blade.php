<style>
    div.hide {
        display: none;
    }

    :root {
        --primary: #1a89ca;

    }

    *,
    *::after,
    *::before {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        transition: 0.1s;
    }

    a {
        text-decoration: none;
        outline: none;
        border: none;
    }

    img {
        display: block;
        max-width: 100%;
    }

    main {
        display: grid;
        margin: 20px 0;
    }

    .container_sp {
        display: grid;
        place-content: center;
        position: relative;
        overflow: hidden;
        border-radius: 1rem;
        --position: 50%;
    }


    .slider-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: left;

    }

    .image-after {
        position: absolute;
        inset: 0;
        width: var(--position);
    }

    .slider {
        position: absolute;
        inset: 0 !important;
        cursor: pointer !important;
        opacity: 0 !important;
        width: 100% !important;
        height: 100% !important;
    }

    .slider-line {
        position: absolute;
        inset: 0;
        width: .2rem;
        height: 100%;
        background-color: #fff;
        left: var(--position);
        transform: translateX(-50%);
        pointer-events: none;
    }

    .slider-button {
        position: absolute;
        background-color: var(--primary);
        color: white;
        border: 2px solid white;
        padding: .5rem;
        border-radius: 100vw;
        display: grid;
        place-items: center;
        top: 50%;
        left: var(--position);
        transform: translate(-50%, -50%);
        pointer-events: none;
        box-shadow: 1px 1px 1px hsl(0, 50%, 2%, .5);
    }

    .controls {
        margin-top: 1rem;
        display: flex;
        gap: 1rem;
        justify-content: center;
        align-items: center;
    }

    #previous-btn,
    #next-btn {
        display: block;
        width: 170px;
        height: 65px;
        color: #000;
        text-align: center;
        font-size: 10px;
        -webkit-border-radius: 5px;
        -moz-border-radius: 5px;
        border-radius: 5px;
        border-bottom: 4px solid #d2d7dc;
        background: #ebebeb;
        position: relative;
    }

    #previous-btn::before {

        display: block;
        width: 45px;
        height: 65px;
        background: #d2d7dc url("{{asset('assets/default/baLeftArrow.png')}}") no-repeat center;
        content: "";
        -webkit-border-top-left-radius: 5px;
        -webkit-border-bottom-left-radius: 5px;
        -moz-border-radius-topleft: 5px;
        -moz-border-radius-bottomleft: 5px;
        border-top-left-radius: 5px;
        border-bottom-left-radius: 5px;
        float: left;
        position: absolute;
        top: 0;
        left: 0;
    }

    #previous-btn strong {
        display: block;
        font-size: 18px;
        padding-left: 35px;
        padding-top: 10px;
    }

    #previous-btn span {
        padding-left: 35px;
    }

    #next-btn::after {
        display: block;
        width: 45px;
        height: 65px;

    }
    }) no-repeat center;
    content: "";
    -webkit-border-top-right-radius: 5px;
    -webkit-border-bottom-right-radius: 5px;
    -moz-border-radius-topleft: 5px;
    -moz-border-radius-bottomright: 5px;
    border-top-right-radius: 5px;
    border-bottom-right-radius: 5px;
    float: right;
    position: absolute;
    top: 0;
    right: 0;
    }

    #next-btn img,
    #previous-btn img {
        display: block;
        width: 65%;
        position: absolute;
        top: 50%;
        left: 39%;
        height: 35px;
        transform: translate(-50%, -50%);
        -webkit-transform: translate(-50%, -50%);
        -moz-transform: translate(-50%, -50%);
    }

    #previous-btn img {
        left: 62% !important;
        /* right: 15% !important; */
    }

    .technicalLeftMenu {
        overflow: auto;
        width: 100%;
        height: 120;
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background: #f4f4f4;
        padding-top: 20px;
        z-index: 5;
        border-right: 1px solid #ebebeb;
        display: flex;
    }

    .technicalLeftMenu.pixarMenu a {
        height: auto;
        display: flex;
        justify-content: center;
        align-items: center;


        width: 100%;
        min-width: 50px;

    }

    .technicalLeftMenu_.pixarMenu a {
        height: auto;
        display: flex;
        justify-content: center;
        align-items: center;



        width: 100%;

    }

    .technicalLeftMenu a:hover,
    .technicalLeftMenu a.active {
        background: #ebebeb;
    }

    .technicalLeftMenu a img {
        display: block;
        margin: auto;
        width: auto;
        height: auto;
        -webkit-filter: grayscale(100%);
        max-width: 90%;
    }

    .technicalLeftMenu a:hover img,
    .technicalLeftMenu a.active img {
        -webkit-filter: grayscale(0%);
    }

    .beforeAfterMenu {
        width: 100%;
        max-width: 730px;
        margin: auto;
        text-align: center;
    }

    .beforeAfterMenu a {
        display: inline-block;
        width: 104px;
        text-align: center;
        color: #64696f;
        font-size: 12px;
        position: relative;
        min-height: 125px;
    }




    .beforeAfterMenu a span {
        display: block;
        width: 50px;
        height: 50px;
        -webkit-border-radius: 5px;
        -moz-border-radius: 5px;
        border-radius: 5px;
        margin: 0 auto 10px;
        -webkit-transition: all 0.2s;
        -moz-transition: all 0.2s;
        -o-transition: all 0.2s;
        transition: all 0.2s;
    }

    span.effect {
        border: 1px solid #1a89ca;
    }

    .technicalLeftMenu.technicalLeftMenu_.pixarMenu {
        left: 69px;
        width: 40px !important;
    }
</style>
<main>
    @php
    $first_after='';
    $first_before='';
    $previous_icons=[];
    $html_links_='';
    $html_links='';
    foreach ($brand_lens as $key=>$brand_len){
    $previous_icons[$key]=$brand_len;
    }
    @endphp
    @foreach($brand_lens as $key=> $brand)
    <div class="div-tab-brand  {{$loop->first?:" hide"}}" id="dev-tap-brand{{$brand->id}}">
        <div class="controls beforeAfterMenu" id="beforeAfterMenu{{$brand->id}}">
            @foreach($brand->features as $feature)
            <a href="#" data-pair="{{$feature->id}}" data-brand="{{$brand->id}}" data-after="{{$feature->after_effect}}"
                data-before="{{$feature->before_effect}}">
                <span class="effect {{$loop->first ?'active':''}} span-f-brand-{{$brand->id}}"
                    data-default="{{$feature->icon}}" data-active="{{$feature->icon_active}}"
                    style=" background: {{$loop->first?'#1a89ca':'#ffffff'}} url('{{$loop->first?$feature->icon_active :$feature->icon }}') no-repeat center;">
                </span>
                {{$feature->name}}
            </a>
            @if($loop->first)
            @php
            $first_after=$feature->after_effect;
            $first_before=$feature->before_effect;
            @endphp
            @endif

            @endforeach


        </div>
        <div class="controls">
            @php
            $previous= $key == 0 ? $previous_icons[$brand_lens->count() -1] : $previous_icons[$key-1];

            $next= $key == $brand_lens->count()-1 ? $previous_icons[0] : $previous_icons[$key+1];
            @endphp
            <a href="#" class="btn-next-previous" data-id="{{$previous->id}}" id="previous-btn">
                <img class="icon-previous" src="{{$previous->icon}}" alt="">
            </a>

            <div class="container_sp container_sp_{{$brand->id}}">
                <div class="image-container_sp">
                    <img class="image-before slider-image image-before-{{$brand->id}}" src="{{$first_before}}"
                        alt="before" />
                    <img class="image-after slider-image image-after-{{$brand->id}}" src="{{$first_after}}"
                        alt="after" />
                </div>
                <input type="range" min="0" max="100" value="50" aria-label="Percentage of before photo shown"
                    class="slider" data-in data-id="{{$brand->id}}" />
                <div class="slider-line" aria-hidden="true"></div>
                <div class="slider-button" aria-hidden="true">
                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor"
                        viewBox="0 0 256 256">
                        <rect width="256" height="256" fill="none"></rect>
                        <line x1="128" y1="40" x2="128" y2="216" fill="none" stroke="currentColor"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="16"></line>
                        <line x1="96" y1="128" x2="16" y2="128" fill="none" stroke="currentColor" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="16"></line>
                        <polyline points="48 160 16 128 48 96" fill="none" stroke="currentColor" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="16"></polyline>
                        <line x1="160" y1="128" x2="240" y2="128" fill="none" stroke="currentColor"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="16"></line>
                        <polyline points="208 96 240 128 208 160" fill="none" stroke="currentColor"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="16"></polyline>
                    </svg>
                </div>
            </div>

            <a href="#" class="btn-next-previous" data-id="{{$next->id}}" id="next-btn">
                <img class="icon-next" src="{{$next->icon}}" alt="">
            </a>




        </div>
    </div>



    @php
    $html_links .= '<div class="d-flex flex-column" style="min-width: 200px;
    border-right: 1px solid #aaa">';
        $html_links .= '<a data-id="'.$brand->id.'" data-color="'.$brand->color.'" href="#" class="';

            if($key==0){
                $html_links .= 'active';
            }
            $html_links .= '">';


            $html_links .=' <img style="height: 25px;" src="'.$brand->icon.'">';
            $html_links .='</a>';





        $html_links .= '<a class="div-price" style="font-weight: 600" data-id="'.$brand->id.'"
            data-color="'.$brand->color.'" href="#" class="';

          if($key==0){
              $html_links .= 'active';
          }
          $html_links .= '">';

            $html_links .=' <span style="  width: 55px;color: #dd8888;">
                '.$brand->price.' '.session("currency")["symbol"].' </span>';
            $html_links .='</a>';
        $html_links .= '</div>';
    @endphp


    @endforeach
    <div class="pixar-menu-container">
        <div id="technicalLeftMenu" class="technicalLeftMenu pixarMenu">
            {!! $html_links !!}

            {{-- {!! $html_links_ !!} --}}
        </div>
    </div>

</main>

<script>
    const sliders = document.querySelectorAll('.slider');
    const startBtn = document.getElementById('start-btn');
    const endBtn = document.getElementById('end-btn');
    sliders.forEach(slider => {
        slider.addEventListener('input', (e) => {
            const id = e.target.dataset.id;
            const container_sp = document.querySelector('.container_sp_' + id);
            if (container_sp) {
                container_sp.style.setProperty('--position', `${e.target.value}%`);
            }
        });
    });

</script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const items = document.querySelectorAll(".beforeAfterMenu a");

        items.forEach(item => {
            item.addEventListener("click", function (event) {
                event.preventDefault();
                const  brand = this.getAttribute('data-brand');

                items.forEach(el => {
                    const span = el.querySelector('.beforeAfterMenu a span');

                    if (span.classList.contains('span-f-brand-'+brand)) {
                        span.classList.remove("active");
                        span.style.background = `#fff url(${span.getAttribute('data-default')}) no-repeat center`;
                    }
                });

                const span = this.querySelector('span');
                if (span) {
                    span.classList.add("active");
                    span.style.background = `#1a89ca url(${span.getAttribute('data-active')}) no-repeat center`;
                }
                const beforeImg = document.querySelector('.image-before-'+brand);
                const afterImg = document.querySelector('.image-after-'+brand);
                beforeImg.src = this.getAttribute('data-before');
                afterImg.src = this.getAttribute('data-after');
            });
        });
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const btnItems = document.querySelectorAll(".btn-next-previous");


        btnItems.forEach(item => {
            item.addEventListener("click", function (event) {
                btnItems.forEach(el => el.classList.remove("active"));
                const id = this.dataset.id;
                const brandTabs = document.querySelectorAll("[id^='dev-tap-brand']");
                brandTabs.forEach(tab => {
                    if (tab.id === "dev-tap-brand" + id) {
                        tab.classList.remove('hide');
                    } else {
                        tab.classList.add('hide');
                    }
                });
            });
        });



    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const menuItems = document.querySelectorAll(".technicalLeftMenu a");

        // Set the CSS variable on page load if a color is stored
        const storedColor = localStorage.getItem('selectedColor');
        if (storedColor) {
            document.documentElement.style.setProperty('--primary', storedColor);
        }

        menuItems.forEach(item => {
            item.addEventListener("click", function (event) {
                menuItems.forEach(el => el.classList.remove("active"));

                this.classList.add("active");

                const color = this.dataset.color;
                const id = this.dataset.id;
                localStorage.setItem('pixarColor', color);

                document.documentElement.style.setProperty('--primary', color);

                const brandTabs = document.querySelectorAll("[id^='dev-tap-brand']");
                brandTabs.forEach(tab => {
                    if (tab.id === "dev-tap-brand" + id) {
                        tab.classList.remove('hide');
                    } else {
                        tab.classList.add('hide');
                    }
                });
            });
        });
    });
</script>
