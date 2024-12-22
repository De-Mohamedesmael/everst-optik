<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        div.beforeAfterMenu.hide {
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
            margin: 200px 0;
        }

        .container {
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
            inset: 0;
            cursor: pointer;
            opacity: 0;
            width: 100%;
            height: 100%;
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
            background: #d2d7dc url(/assets/website/baLeftArrow.png) no-repeat center;
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
            background: #d2d7dc url(/assets/website/baRightArrow.png) no-repeat center;
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

        #next-btn img {
            display: block;
            width: 65%;
            position: absolute;
            top: 50%;
            left: 39%;
            transform: translate(-50%, -50%);
            -webkit-transform: translate(-50%, -50%);
            -moz-transform: translate(-50%, -50%);
        }

        .technicalLeftMenu {
            width: 80px;
            height: 100%;
            position: fixed;
            top: 0;
            left: 0;
            background: #f4f4f4;
            padding-top: 55px;
            z-index: 5;
            border-right: 1px solid #ebebeb;
        }

        .technicalLeftMenu.pixarMenu a {
            height: auto;
            padding: 35px 0;
            display: block;
            width: 100%;
            border-bottom: 1px solid #dcdcdc;
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
    </style>
</head>

<body>
<main>
    @php
        $first_after='';
        $first_before='';
        $next_icon='';
        $previous_icon='';
        $is_first=true;
    @endphp
    @foreach($brand_lens as $key=> $brand)
        <div class="controls beforeAfterMenu {{$loop->first?:"hide"}}" id="beforeAfterMenu{{$brand->id}}">
            @foreach($brand->features as $key=> $feature)
                <a href="#" data-pair="{{$feature->id}}" data-after="{{$feature->after_effect}}" data-before="{{$feature->before_effect}}" >
                    <span class="effect {{$is_first?'active':''}}" data-default="{{$feature->icon}}" data-active="{{$feature->icon_active}}"
                          style=" background: {{$is_first?'#1a89ca':'#ffffff'}} url('{{$is_first?$feature->icon_active :$feature->icon }}') no-repeat center;">
                    </span>
                    {{$feature->name}}
                </a>
            @if($is_first)
                    @php
                        $first_after=$feature->after_effect;
                        $first_before=$feature->before_effect;
                        $is_first=false;
                    @endphp
            @endif

            @endforeach
            @if($key == 1)
                    @php($next_icon = $brand->icon)
            @endif
            @if($loop->last)
                @php($previous_icon = $brand->icon)
            @endif
        </div>
    @endforeach


    <div class="controls">



        <a href="#" id="previous-btn">
            <img src="{{$previous_icon}}" alt="">
        </a>

        <div class="container">
            <div class="image-container">
                <img class="image-before slider-image" src="{{$first_before}}" alt="before" />
                <img class="image-after slider-image" src="{{$first_after}}" alt="after" />
            </div>
            <input type="range" min="0" max="100" value="50" aria-label="Percentage of before photo shown"
                   class="slider" />
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

        <a href="#" id="next-btn">
            <img src="{{$next_icon}}" alt="">
        </a>




    </div>



    <div class="technicalLeftMenu pixarMenu">
        @foreach($brand_lens as $key => $brand)
            <a data-img="{{$brand->icon}}"   data-color="#4eaf30" href="#" class="{{$key==0?'active':''}}"><img style="    transform: rotate(-90deg);
    height: 30px;" src="{{$brand->icon}}"></a>
        @endforeach
    </div>

</main>

<script>
    const container = document.querySelector('.container');
    const slider = document.querySelector('.slider');
    const previousBtn = document.getElementById('previous-btn');
    const nextBtn = document.getElementById('next-btn');

    slider.addEventListener('input', (e) => {
        container.style.setProperty('--position', `${e.target.value}%`);
    });

    previousBtn.addEventListener('click', () => {
        slider.value = 0;
        slider.dispatchEvent(new Event('input'));
    });

    nextBtn.addEventListener('click', () => {
        slider.value = 100;
        slider.dispatchEvent(new Event('input'));
    });



    const beforeImg = document.querySelector('.image-before');
    const afterImg = document.querySelector('.image-after');
    const buttons = document.querySelectorAll(".beforeAfterMenu a");
    // Mapping for image pairs


    // Event listener for all buttons
    buttons.forEach(button => {
        button.addEventListener('click', (event) => {
            beforeImg.src = button.getAttribute('data-before');
            afterImg.src =  button.getAttribute('data-after');
        });
    });

</script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const items = document.querySelectorAll(".beforeAfterMenu a span");


        items.forEach(item => {
            item.addEventListener("click", function (event) {
                items.forEach(el => {
                        el.classList.remove("active")
                        el.style.background = `#fff url(${el.getAttribute('data-default')}) no-repeat center`;
                    }
                ); // Remove 'active' from all
                // Set the active background for the clicked span
                this.classList.add("active");
                this.style.background = `#1a89ca url(${this.getAttribute('data-active')}) no-repeat center`;
            });
        });
    });
</script>


<script>
    document.addEventListener("DOMContentLoaded", function () {
        const menuItems = document.querySelectorAll(".technicalLeftMenu a");
        const nextBtnImg = document.querySelector("#next-btn img");

        // Set the CSS variable on page load if a color is stored
        const storedColor = localStorage.getItem('selectedColor');
        if (storedColor) {
            document.documentElement.style.setProperty('--primary', storedColor);
        }

        menuItems.forEach(item => {
            item.addEventListener("click", function (event) {
                menuItems.forEach(el => el.classList.remove("active")); // Remove 'active' from all
                this.classList.add("active"); // Add 'active' to the clicked element

                // Store the color value in local storage
                const color = this.dataset.color; // Assuming color is stored in data-color attribute
                localStorage.setItem('pixarColor', color);
                document.documentElement.style.setProperty('--primary', color);



                // Update the img src inside #next-btn
                const newImgSrc = this.dataset.img;
                if (nextBtnImg) {
                    nextBtnImg.src = newImgSrc;
                }
            });
        });
    });
</script>



</body>

</html>
