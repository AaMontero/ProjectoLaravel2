<x-app-layout>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css' rel='stylesheet'>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('INICIO') }}
        </h2>
    </x-slot>
    <style>
        .imagen-container {
            position: relative;
            width: 100%;
            height: auto; /* Ajusta automáticamente la altura según el contenido */
        }

        .imagen {
            width: 100%;
            height: auto; /* Ajusta automáticamente la altura según el contenido */
            border-radius: 10px;
        }

        .texto {
            position: absolute;
            top: 40%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
        }
    </style>

    <div class="mt-4">
        <div class="imagen-container">
            <img src="{{ asset('images/travel.jpg') }}" alt="Imagen" class="imagen">
            <div class="texto">
                <p class="bg-gradient-to-r from-blue-500 via-green-600 to-yellow-400 bg-clip-text text-transparent font-bold mb-4 text-xl md:text-4xl lg:text-5xl xl:text-6xl">BIENVENIDOS</p>
                <p class="bg-gradient-to-r from-red-500 via-purple-600 to-pink-400 bg-clip-text text-transparent font-bold text-xl md:text-4xl lg:text-5xl xl:text-6xl">CRM de Qori Travel</p>
            </div>
        </div>
    </div>

</x-app-layout>
@include('layouts.footer')


