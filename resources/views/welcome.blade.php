<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.4.0/fonts/remixicon.css" rel="stylesheet" />
    <link rel="icon" type="image/png"  sizes="200x200" href="{{ asset('images/logoFondoNegro.jpeg') }}">


    <title>Trivai</title>
</head>

<body>
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap");

        :root {
            --primary-color: #111317;
            --primary-color-light: #1f2125;
            --primary-color-extra-light: #35373b;
            --secondary-color: #ffcd57;
            --secondary-color-dark: #ffcd57;
            --text-light: #d1d5db;
            --white: #ffffff;
            --max-width: 1200px;
        }

        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
        }

        .section__container {
            max-width: var(--max-width);
            margin: auto;
            padding: 5rem 1rem;
        }

        .section__header {
            margin-bottom: 1rem;
            font-size: 2.25rem;
            font-weight: 600;
            text-align: center;
            color: var(--white);
        }

        .section__subheader {
            max-width: 600px;
            margin: auto;
            text-align: center;
            color: var(--text-light);
        }

        .btn {
            padding: 1rem 1rem;
            outline: none;
            border: none;
            font-size: 1rem;
            color: #000000;
            background-color: var(--secondary-color);
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn:hover {
            background-color: var(--secondary-color-dark);
        }

        img {
            width: 85%;
            height: 1%;
            display: flex;
        }

        a {
            text-decoration: none;
        }

        .bg__blur {
            position: absolute;
            box-shadow: 0 0 1000px 50px var(--secondary-color);
            z-index: -1;
        }

        body {
            font-family: "Poppins", sans-serif;
            background-color: var(--primary-color);
        }

        nav {
            max-width: var(--max-width);
            margin: auto;
            padding: 1rem 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 2rem;
        }

        .nav__logo {
            width: 12%;
        }

        .nav__links {
            list-style: none;
            display: flex;
            align-items: center;
            gap: 2rem;
        }

        .link a {
            position: relative;
            padding-bottom: 0.75rem;
            color: var(--white);
        }

        .link a::after {
            content: "";
            position: absolute;
            height: 2px;
            width: 0;
            left: 0;
            bottom: 0;
            background-color: var(--secondary-color);
            transition: 0.3s;
        }

        .link a:hover::after {
            width: 50%;
        }

        .header__container {
            position: relative;
            padding-top: 2rem;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            align-items: center;
            gap: 2rem;
        }

        .header__container::before {
            content: "TRIVAI";
            position: absolute;
            bottom: 5rem;
            right: 20rem;
            font-size: 10rem;
            font-weight: 700;
            line-height: 7rem;
            color: var(--white);
            opacity: 0.05;
            z-index: -1;
        }

        .header__blur {
            bottom: 5rem;
            right: 0;
        }

        .header__content h4 {
            margin-bottom: 1rem;
            font-size: 1rem;
            font-weight: 600;
            color: var(--secondary-color);
        }

        .header__content h1 {
            margin-bottom: 1rem;
            font-size: 4rem;
            font-weight: 700;
            line-height: 6rem;
            color: var(--white);
        }

        .header__content h1 span {
            -webkit-text-fill-color: transparent;
            font-size: 5rem;
            -webkit-text-stroke: 1px var(--white);
        }

        .header__content p {
            margin-bottom: 2rem;
            color: var(--text-light);
        }

        .header__image {
            position: relative;
        }




        .review__footer {
            margin-top: 4rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 2rem;
        }

        .review__member {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
        }

        .review__member img {
            max-width: 60px;
            border-radius: 100%;
        }

        .review__member__details h4 {
            margin-bottom: 0;
        }

        .review__nav {
            display: flex;
            align-items: center;
            gap: 2rem;
        }

        .review__nav span {
            font-size: 2rem;
            color: var(--secondary-color);
            cursor: pointer;
        }

        .footer__container {
            position: relative;
            display: grid;
            grid-template-columns: 400px repeat(2, 1fr);
            gap: 2rem;
        }

        .footer__blur {
            bottom: 0;
            right: 0;
        }

        .footer__logo {
            max-width: 150px;
            margin-bottom: 2rem;
        }

        .footer__col p {
            margin-bottom: 2rem;
            color: var(--text-light);
        }

        .footer__socials {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .footer__socials a {
            padding: 5px 10px;
            font-size: 1.25rem;
            color: var(--secondary-color);
            border: 1px solid var(--secondary-color);
            border-radius: 100%;
            transition: 0.3s;
        }

        .footer__socials a:hover {
            color: var(--white);
            background-color: var(--secondary-color);
        }

        .footer__col h4 {
            margin-bottom: 2rem;
            font-size: 1.2rem;
            font-weight: 500;
            color: var(--white);
        }

        .footer__col>a {
            display: block;
            margin-bottom: 1rem;
            color: var(--text-light);
            transition: 0.3s;
        }

        .footer__col>a:hover {
            color: var(--secondary-color);
        }

        .footer__bar {
            max-width: var(--max-width);
            margin: auto;
            padding: 1rem;
            font-size: 0.8rem;
            color: var(--white);
        }

        .btn a {
            text-decoration: none;
            color: inherit;
        }

        @media (width < 900px) {
            .nav__links {
                display: none;
            }

            .header__container {
                grid-template-columns: repeat(1, 1fr);
            }

            .header__image {
                grid-area: 1/1/2/2;
            }

            .explore__grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 1rem;
            }

            .class__container {
                grid-template-columns: repeat(1, 1fr);
            }

            .class__image {
                min-height: 500px;
            }

            .price__grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .review__container {
                gap: 2rem;
            }

            .footer__container {
                grid-template-columns: 1fr 200px;
            }
        }

        @media (width < 600px) {
            .explore__header {
                flex-direction: column;
            }

            .explore__grid {
                grid-template-columns: repeat(1, 1fr);
            }

            .join__container {
                margin-bottom: 15rem;
            }

            .join__grid {
                width: 100%;
                margin: 0;
                bottom: -20rem;
            }

            .price__grid {
                grid-template-columns: repeat(1, 1fr);
            }

            .review__container {
                flex-direction: column;
                gap: 0;
            }

            .review__footer {
                flex-direction: column;
            }

            .footer__container {
                grid-template-columns: 1fr 150px;
            }

            .footer__bar {
                text-align: center;
            }
        }
    </style>
    <nav>
        <div class="nav__logo">
            <a href="https://travelqori.com/"><img src="{{ asset('images/logo3.png') }}" alt="logo" /></a>
        </div>
        <ul class="nav__links">
            <li class="link"><a href="{{ route('terminos') }}">Términos y condiciones</a></li>
            <li class="link"><a href="{{ route('politicas') }}">Políticas y privacidad</a></li>
            
        </ul>
        <button class="btn"><a href="{{ route('login') }}">Incio de sesión</a></button>
    </nav>

    <header class="section__container header__container">
        <div class="header__content">
            <span class="bg__blur"></span>
            <span class="bg__blur header__blur"></span>
            <h4>QORI INTERNATIONAL GROUP L.L.C.</h4>
            <h1><span>TRIVAI </span> en búsqueda de ampliación</h1>
            <p>
                TRIVAI brindará soporte constante antes, durante y después del viaje, todo mediante mensaje de texto, es
                tan sencillo como interactuar con una persona real por WhatsApp, todo esto gracias a nuestro poderoso
                sistema de Red Neuronal y la versatilidad de nuestra Inteligencia Artificial.
            </p>
            <button class="btn">Contactanos</button>
        </div>
        <div class="header__image">
            <img src="{{ asset('images/logo3.png') }}" alt="header" />
        </div>
    </header>


    <footer class="section__container footer__container">
        <span class="bg__blur"></span>
        <span class="bg__blur footer__blur"></span>
        <div class="footer__col">
            <div class="footer__logo"></div>
            <p>
                TRIVAI facilitará la búsqueda y reserva de tickets aéreos, hospedajes, reservaciones, paquetes
                turísticos e inclusive brindará la facilidad de crear un itinerario completo.
            </p>
            <div class="footer__socials">
                <a href="https://www.facebook.com/QoriTGroup"><i class="ri-facebook-fill"></i></a>
                <a href="https://www.instagram.com/qorittravelgroup?igsh=Nm1pcnI3dGJ5OWc1"><i class="ri-instagram-line"></i></a>
                <a href="https://www.tiktok.com/@qorittravel.group?_t=8lMPwjveS3h&_r=1"><i class="ri-tiktok-fill"></i></a>
            </div>
        </div>
        <div class="footer__col">
            <h4>Contáctanos</h4>
            <a>Teléfono: +593 2 501 1253</a>
            <a>Correo: hola@travelqori.com</a>
            <a>Av. Shyris y Gaspar de Villaroel, C.C. Galeria, piso 2, local 47, Quito - Ecuador.</a>
        </div>
      
        <div class="footer__col">
            <h4>Equipo</h4>
            <a href="https://travelqori.com/about-us/">Nosotros</a>
            <a href="#">Trabaje con nosotros</a>
            <a href="#">Soporte</a>
            <a href="#">Acontecimientos</a>
        </div>
    </footer>
    <div class="footer__bar">
        Copyright © 2024 All rights reserved.
    </div>
</body>

</html>
