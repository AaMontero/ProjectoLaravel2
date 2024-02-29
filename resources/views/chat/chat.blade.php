<x-app-layout>
    <style>
        /* Estilo para las notificaciones clicables */
        .notificacion-clicable {
            cursor: pointer;
            /* Cambia el cursor al pasar sobre las notificaciones */
            transition: background-color 0.3s;
            /* Transición suave para el cambio de color de fondo */
        }

        /* Cambia el color de fondo al pasar el ratón sobre las notificaciones */
        .notificacion-clicable:hover {
            background-color: rgba(0, 0, 0, 0.1);
            /* Cambia el color de fondo al pasar el ratón */
        }
    </style>
    <div class="flex space-x-8">
        <!-- Notificaciones -->
        <div id="notificaciones"
            class="w-1/2 bg-white dark:bg-slate-200 rounded-lg px-8 py-6 mt-5 ring-1 ring-slate-900/5 shadow-xl">
            <h3 class="text-xl font-semibold mb-4">Notificaciones</h3>
            @foreach ($mensajes->groupBy('id_numCliente') as $telefono => $mensajesTelefono)
                <div class="space-y-4">
                    <div onclick="abrirchat('{{ $telefono }}', {{ json_encode($mensajesTelefono) }})"
                        data-telefono="{{ $telefono }}"
                        class="notificacion-clicable bg-gray-100 dark:bg-gray-800 rounded-lg mb-4 p-4 cursor-pointer transition duration-300 ease-in-out transform hover:scale-105">

                        <img src="https://via.placeholder.com/40" alt="User" class="w-8 h-8 rounded-full">
                        <div class="font-bold text-gray-800 dark:text-gray-200">{{ $telefono }}</div>
                        <div class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                            @foreach ($mensajesTelefono as $mensaje)
                                <div class="notificacion-mensaje flex items-center space-x-2">
                                    <div>{{ $mensaje->mensaje_recibido }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <h1 id = "telefonoAbierto">asdadassd</h1>
        <!-- Chat -->
        <div id="abrirchat"
            class="relative w-1/2 bg-white dark:bg-slate-200 rounded-lg px-8 py-6 mt-5 ring-1 ring-slate-900/5 shadow-xl"
            style="border-color: #4a5568; display:none;">
            <h3 class="text-xl font-semibold mb-4">Chat</h3>
            <button onclick="cerrarChat()" class="absolute top-6 right-4 text-gray-600 hover:text-gray-800">
                <!-- Icono de cierre (X) -->
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>

            <!-- Historial de mensajes -->
            <div class="flex flex-col h-70 border border-gray-300 rounded-lg px-6 py-8" style="border-color: #4a5568;">
                <!-- Historial de mensajes -->
                <img src="https://via.placeholder.com/40" alt="User" class="w-8 h-8 rounded-full">
                <div id="telefono-chat"></div>
                <div id="historial-mensajes">
                    <!-- Aquí se mostrarán los mensajes -->
                    @foreach ($mensajes as $telefono)
                        @if (isset($_POST['telefonoAbierto']) && $telefono->id_numCliente == $_POST['telefonoAbierto'])
                            <h1>{{ $telefono['id'] }}</h1>
                        @endif
                    @endforeach
                </div>
                <!-- Campo de texto para escribir -->
                <form id="mensajeForm" class="mt-4">
                    <input type="text" id="mensajeInput"
                        class="w-full border rounded-md py-2 px-3 focus:outline-none focus:border-blue-500"
                        placeholder="Escribe un mensaje...">
                    <button type="submit"
                        class="mt-2 w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-md">Enviar</button>
                </form>
            </div>
        </div>

    </div>

    <script>
        function abrirchat(telefono, mensajes) {
            var activo = document.getElementById("telefonoAbierto").value = telefono.toString();
            console.log("el valor activo es: " + activo);
            console.log('Abriendo chat para el teléfono:', telefono);
            var VentanaChat = document.getElementById("abrirchat");
            console.log('mensajes chat: ', mensajes);

            if (VentanaChat.style.display === 'none') {
                //cargarMensajes(mensajes);
                VentanaChat.style.display = 'block';
                document.getElementById("telefono-chat").textContent = telefono;
                //document.getElementById("historial-mensajes").textContent = mensajes;
            } else {
                VentanaChat.style.display = 'none';
            }
            // Mostrar el área de chat
        }
        function cerrarChat() {
            var chat = document.getElementById('abrirchat');
            chat.style.display = 'none';
        }
    </script>

</x-app-layout>
