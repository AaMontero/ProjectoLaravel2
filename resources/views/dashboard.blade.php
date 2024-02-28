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

    <div class="flex max-w sm:px-6 mx-auto shadow-xls">
        <!-- Notificaciones-->

<div id="notificaciones" class="bg-white dark:bg-slate-200 rounded-lg px-6 py-4 mt-4 ring-1 ring-slate-900/5">
    <h3 class="text-xl font-semibold mb-4">Notificaciones</h3>
    <div class="space-y-4">
        <!-- Notificación 1 -->
        <div onclick="abrirVentanaChat()" class="notificacion-clicable flex items-center justify-between px-4 py-2 bg-gray-100 dark:bg-gray-800 rounded-lg transition duration-300 ease-in-out transform hover:scale-105">
            <div class="flex items-center space-x-2">
                <img src="https://via.placeholder.com/40" alt="User" class="w-8 h-8 rounded-full">
                <div>
                    <strong>Admin</strong>: Bienvenido al sistema.
                </div>
            </div>
        </div>
        <!-- Notificación 2 -->
        <div onclick="abrirVentanaChat()" class="notificacion-clicable flex items-center justify-between px-4 py-2 bg-gray-100 dark:bg-gray-800 rounded-lg transition duration-300 ease-in-out transform hover:scale-105">
            <div class="flex items-center space-x-2">
                <img src="https://via.placeholder.com/40" alt="User" class="w-8 h-8 rounded-full">
                <div>
                    <strong>Usuario 1</strong>: Tienes un nuevo mensaje.
                </div>
            </div>
        </div>
        <!-- Notificación 3 -->
        <div onclick="abrirVentanaChat()" class="notificacion-clicable flex items-center justify-between px-4 py-2 bg-gray-100 dark:bg-gray-800 rounded-lg transition duration-300 ease-in-out transform hover:scale-105">
            <div class="flex items-center space-x-2">
                <img src="https://via.placeholder.com/40" alt="User" class="w-8 h-8 rounded-full">
                <div>
                    <strong>Usuario 2</strong>: Tu factura ha sido procesada.
                </div>
            </div>
        </div>
    </div>
</div>


        <!-- Chats de Whatsapp -->
        <div id="abrirchat" class="bg-white dark:bg-slate-200 rounded-lg px-6 py-8 mt-4 ring-1 ring-slate-900/5 shadow-xl" style="border-color: #4a5568; display: none;">
            <div class="relative">
                <h3 class="text-xl font-semibold mb-4">Chat</h3>
                <button onclick="cerrarChat()" class="absolute top-0 right-4 text-gray-600 hover:text-gray-800">
                    <!-- Icono de cierre (X) -->
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
                <div class="flex flex-col h-64 border border-gray-300 rounded-lg" style="border-color: #4a5568;">
                    <!-- Historial de mensajes -->
                    <div class="overflow-y-auto flex-1 p-4 space-y-4">
                        <div class="flex items-start">
                            <img src="https://via.placeholder.com/40" alt="User" class="w-8 h-8 rounded-full mr-2">
                            <div>
                                <strong class="text-blue-500">Tú:</strong>
                                <p class="bg-blue-100 rounded-lg py-2 px-4">Hola, ¿cómo estás?</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <img src="https://via.placeholder.com/40" alt="User" class="w-8 h-8 rounded-full mr-2">
                            <div>
                                <strong class="text-blue-500">Usuario:</strong>
                                <p class="bg-blue-100 rounded-lg py-2 px-4">¡Hola! Estoy bien, gracias. ¿Y tú?</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <img src="https://via.placeholder.com/40" alt="User" class="w-8 h-8 rounded-full mr-2">
                            <div>
                                <strong class="text-blue-500">Tú:</strong>
                                <p class="bg-blue-100 rounded-lg py-2 px-4">Muy bien, gracias por preguntar.</p>
                            </div>
                        </div>
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
            </div>

        </div>
    </div>

    @include('layouts.footer')

    <script>
        function abrirVentanaChat() {
            var VentanaChat = document.getElementById("abrirchat");
            if (VentanaChat.style.display === 'none') {
                VentanaChat.style.display = 'block';
            } else {
                VentanaChat.style.display = 'none';
            }
        }
        function cerrarChat() {
        var chat = document.getElementById('abrirchat');
        chat.style.display = 'none';
    }
    </script>
</x-app-layout>

