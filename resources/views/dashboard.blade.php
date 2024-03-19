<x-app-layout>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css' rel='stylesheet'>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('HOME') }}
        </h2>
    </x-slot>
    <style>
        .imagen{
        background-image: url("{{ asset('images/travel.jpg') }}");
        height: 575px;
        width: 1400px;
        margin-left: auto;
        margin-right: auto;
        background-size: cover; /* Ajusta la imagen para cubrir completamente el contenedor */
        background-position: center; /* Centra la imagen dentro del contenedor */
        border-radius: 10px;
        }
    </style>

<div class="mt-4">
    <div class="imagen">
        <div class="flex justify-center items-center h-96 mb-4">
            <div class="text-center font-extrabold text-5xl">
                <p class="bg-gradient-to-r from-blue-500 via-green-600 to-yellow-400 bg-clip-text text-transparent font-bold mb-4 text-xl md:text-4xl lg:text-5xl xl:text-6xl">BIENVENIDOS</p>
                <p class="bg-gradient-to-r from-red-500 via-purple-600 to-pink-400 bg-clip-text text-transparent font-bold text-xl md:text-4xl lg:text-5xl xl:text-6xl">CRM de Qori Travel</p>
            </div>
        </div>
    </div>
</div>
@include('layouts.footer')
</x-app-layout>


