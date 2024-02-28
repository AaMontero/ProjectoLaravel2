<x-app-layout>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css' rel='stylesheet'>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
    <style>
        /* Estilo para las notificaciones clicables */
        .notificacion-clicable {
            cursor: pointer; /* Cambia el cursor al pasar sobre las notificaciones */
            transition: background-color 0.3s; /* Transición suave para el cambio de color de fondo */
        }

        /* Cambia el color de fondo al pasar el ratón sobre las notificaciones */
        .notificacion-clicable:hover {
            background-color: rgba(0, 0, 0, 0.1); /* Cambia el color de fondo al pasar el ratón */
        }
    </style>

    <div class="flex">
        <div id="notificaciones" class="w-1/2 bg-white dark:bg-slate-200 rounded-lg px-8 py-6 mt-5 ring-1 ring-slate-900/5 mx-4 shadow-xl ">
            <h3 class="text-xl font-semibold mb-4">Notificaciones</h3>
            <div class="space-y-4">
                <!-- Notificación 1 -->
            {{-- @foreach($mensajes as $mensaje)
                <div onclick="abrirVentanaChat('{{ $mensaje->id_wa }}')" class="notificacion-clicable flex items-center justify-between px-4 py-2 bg-gray-100 dark:bg-gray-800 rounded-lg transition duration-300 ease-in-out transform hover:scale-105">
                    <div class="flex items-center space-x-2">
                        <img src="https://via.placeholder.com/40" alt="User" class="w-8 h-8 rounded-full">
                        <div>
                            <strong>{{ $mensaje->telefono_wa }}</strong>: {{ $mensaje->mensaje_recibido }}
                        </div>
                    </div>
                </div>
            @endforeach --}}
            {{-- @foreach($mensajes->groupBy('telefono_wa') as $telefono => $mensajesTelefono)
            <div class="notificacion-clicable bg-gray-100 dark:bg-gray-800 rounded-lg mb-4">
                <div class="p-4">
                    <div class="font-bold">{{ $telefono }}</div>
                    <div class="mt-2">
                        @foreach($mensajesTelefono as $mensaje)
                            <div onclick="abrirVentanaChat('{{ $telefono }}', '{{ $mensaje->mensaje_recibido }}')" class="flex items-center space-x-2 cursor-pointer">
                                <img src="https://via.placeholder.com/40" alt="User" class="w-8 h-8 rounded-full">
                                <div>{{ $mensaje->mensaje_recibido }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach --}}




        <!-- Chats de Whatsapp -->

    </div>
{{-- 
    <div id="abrirchat" class=" bg-white dark:bg-slate-200 rounded-lg px-6 py-8 mt-5 ring-1 ring-slate-900/5 shadow-xl mx-4" style="border-color: #4a5568;">
        <div class="relative">
            <h3 class="text-xl font-semibold mb-4">Chat</h3>
            <button onclick="cerrarChat()" class="absolute top-0 right-4 text-gray-600 hover:text-gray-800">
                <!-- Icono de cierre (X) -->
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
            @foreach($mensajes->groupBy('telefono_wa') as $telefono => $mensajesTelefono)
            <div class="flex flex-col h-70 border border-gray-300 rounded-lg px-6 py-8" style="border-color: #4a5568;">
                <!-- Historial de mensajes -->
                <div class="overflow-y-auto flex-1 p-4 space-y-4">
                    @foreach($mensajesTelefono as $mensaje)
                    <div class="mensaje" data-telefono="{{ $mensaje->telefono_wa }}"> <!-- Añade el atributo data-telefono -->
                        <div class="flex items-start">
                            <img src="https://via.placeholder.com/40" alt="User" class="w-8 h-8 rounded-full mr-2">
                            <div>
                                <strong class="text-blue-500">{{ $mensaje->telefono_wa }}:</strong>
                                <p class="bg-blue-100 rounded-lg py-2 px-4">{{ $mensaje->mensaje_recibido }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Campo de texto para escribir -->
                <form action="#" method="POST" class="p-4 border-t border-gray-300">
                    @csrf
                    <div class="flex">
                        <input type="text" name="message" class="flex-1 border border-gray-300 rounded-l-md px-4 py-2 focus:outline-none focus:ring focus:border-blue-500" placeholder="Escribe un mensaje...">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-r-md">Enviar</button>
                    </div>
                </form>
            </div>
            @endforeach

        </div>
    </div> --}}




    <script>
        function abrirVentanaChat(telefono) {
            var VentanaChat = document.getElementById("abrirchat");
            if (VentanaChat.style.display === 'none') {
                VentanaChat.style.display = 'block';
                mostrarMensajes(telefono); // Llama a la función para mostrar los mensajes del número de teléfono
            } else {
                VentanaChat.style.display = 'none';
            }
        }
        function mostrarMensajes(telefono) {
            var mensajes = document.getElementsByClassName('mensaje');
            for (var i = 0; i < mensajes.length; i++) {
                if (mensajes[i].getAttribute('data-telefono') !== telefono) {
                    mensajes[i].style.display = 'none'; // Oculta los mensajes que no corresponden al número de teléfono
                } else {
                    mensajes[i].style.display = 'block'; // Muestra los mensajes que corresponden al número de teléfono
                }
            }
        }
        function cerrarChat() {
            var chat = document.getElementById('abrirchat');
            chat.style.display = 'none';
        }
    </script>
</x-app-layout>
@include('layouts.footer')
