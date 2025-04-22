<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
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

        #start-btn,
        #end-btn {
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

        #start-btn::before {

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

        #start-btn strong {
            display: block;
            font-size: 18px;
            padding-left: 35px;
            padding-top: 10px;
        }

        #start-btn span {
            padding-left: 35px;
        }

        #end-btn::after {
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

        #end-btn img {
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
            overflow: scroll;
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
            padding: 20px 0;
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
        <div class="controls beforeAfterMenu">
            <a href="#" data-pair="1">
                <span class="effect active" data-default="reflectionBlue.png" data-active="reflectionWhite.png"
                    style=" background: #1a89ca url(/assets/website/reflectionWhite.png) no-repeat center;">
                </span>
                Prevents reflections, presents pure vision
            </a>
            <a href="#" data-pair="2">
                <span class="effect" data-default="hydroBlue.png" data-active="hydroWhite.png"
                    style=" background: #fff url(/assets/website/hydroBlue.png) no-repeat center;">
                </span>
                Superhydrophobic, water repellent
            </a>
            <a href="#" data-pair="3">
                <span class="effect" data-default="stratchBlue.png" data-active="stratchWhite.png"
                    style=" background: #fff url(/assets/website/stratchBlue.png)  no-repeat center;">
                </span>
                Anti-scratch
            </a>
            <a href="#" data-pair="4">
                <span class="effect" data-default="cleanBlue.png" data-active="cleanWhite.png"
                    style=" background: #fff url(/assets/website/cleanBlue.png)  no-repeat center;">
                </span>
                Anti-dust, easy clean
            </a>
            <a href="#" data-pair="5">
                <span class="effect" data-default="fogBlue.png" data-active="fogWhite.png"
                    style=" background: #fff url(/assets/website/fogBlue.png)   no-repeat center;">
                </span>
                Anti-fog
            </a>
            <a href="#" data-pair="6">
                <span class="effect" data-default="uvBlue.png" data-active="uvWhite.png"
                    style=" background: #fff url(/assets/website/uvBlue.png)   no-repeat center;">
                </span>
                Max UV Protection
            </a>
            <a href="#" data-pair="7">
                <span class="effect" data-default="lightBlue.png" data-active="lightWhite.png"
                    style=" background: #fff url(/assets/website/lightBlue.png) no-repeat center;">
                </span>
                Blue Light protection
            </a>
        </div>

        <div class="controls">
            <a href="#" id="start-btn">
                <strong>
                    Standard
                </strong>

                <span>
                    AR
                </span>
            </a>


            <div class="container">
                <div class="image-container">
                    <img class="image-before slider-image" src="assets/website/1before.jpg" alt="before" />
                    <img class="image-after slider-image" src="assets/website/1after.jpg" alt="after" />
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

            <a href="#" id="end-btn">
                <img src="/assets/website/pixarBluv.png" alt="">
            </a>

        </div>



        <div class="technicalLeftMenu pixarMenu">
            <a data-img="/assets/website/pixar.png" data-color="#4eaf30" href="#"><img
                    src="/assets/website/pixarV.png"></a>
            <a data-img="/assets/website/pixarUv.png" data-color="#4eaf30" href="#"><img
                    src="/assets/website/pixarUvV.png"></a>
            <a data-img="/assets/website/pixarBluv.png" data-color="#1a89ca" class="active" href="#"><img
                    src="/assets/website/pixarBluvV.png"></a>
            <a data-img="/assets/website/pixarAqua.png" data-color="#00abc6" href="#"><img
                    src="/assets/website/pixarAquaV.png"></a>
            <a data-img="/assets/website/pixarDrive.png" data-color="#eb639f" href="#"><img
                    src="/assets/website/pixarDriveV.png"></a>
            <a data-img="/assets/website/slc.png" data-color="#215395" href="#"><img src="/assets/website/slcV.png"></a>
        </div>

    </main>

    <script>
        const container = document.querySelector('.container');
        const slider = document.querySelector('.slider');
        const startBtn = document.getElementById('start-btn');
        const endBtn = document.getElementById('end-btn');

        slider.addEventListener('input', (e) => {
            container.style.setProperty('--position', `${e.target.value}%`);
        });

        startBtn.addEventListener('click', () => {
            slider.value = 0;
            slider.dispatchEvent(new Event('input'));
        });

        endBtn.addEventListener('click', () => {
            slider.value = 100;
            slider.dispatchEvent(new Event('input'));
        });



        const beforeImg = document.querySelector('.image-before');
        const afterImg = document.querySelector('.image-after');
        const buttons = document.querySelectorAll(".beforeAfterMenu a");
        // Mapping for image pairs
        const imagePairs = {
            1: { before: '/assets/website/1before.jpg', after: '/assets/website/1after.jpg' },
            2: { before: '/assets/website/2before.jpg', after: '/assets/website/2after.jpg' },
            3: { before: '/assets/website/3before.jpg', after: '/assets/website/3after.jpg' },
            4: { before: '/assets/website/4before.jpg', after: '/assets/website/4after.jpg' },
            5: { before: '/assets/website/5before.jpg', after: '/assets/website/5after.jpg' },
            6: { before: '/assets/website/6before.jpg', after: '/assets/website/6after.jpg' },
            7: { before: '/assets/website/7before.jpg', after: '/assets/website/7after.jpg' },
        };

        // Event listener for all buttons
        buttons.forEach(button => {
            button.addEventListener('click', (event) => {
                const pair = button.getAttribute('data-pair');
                const images = imagePairs[pair];
                beforeImg.src = images.before;
                afterImg.src = images.after;
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
            const endBtnImg = document.querySelector("#end-btn img");

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



                    // Update the img src inside #end-btn
                    const newImgSrc = this.dataset.img;
                    if (endBtnImg) {
                        endBtnImg.src = newImgSrc;
                    }
                });
            });
        });
    </script>



</body>

</html>
