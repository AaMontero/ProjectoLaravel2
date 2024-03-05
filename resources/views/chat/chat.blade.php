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
        <div id="notificaciones" class="w-1/2 bg-white dark:bg-slate-200 px-8 py-8 mt-5 ring-1 ring-slate-900/5 shadow-xl overflow-auto" style="max-height: 700px; border-radius: 10px;">
            <h3 class="text-xl font-semibold mb-4">Notificaciones</h3>
            @foreach ($mensajes->groupBy('id_numCliente') as $telefono => $mensajesTelefono)
                @php
                    $ultimoMensaje = $mensajesTelefono->last(); // Obtener el último mensaje
                    $leido = $ultimoMensaje['leido']; // Verificar si el último mensaje está marcado como leído
                @endphp
                <div class="space-y-4">
                    <div onclick="abrirchat('{{ $telefono }}', {{ json_encode($mensajesTelefono) }})" data-telefono="{{ $telefono }}" data-id="{{ $ultimoMensaje['mensaje_recibido'] }}" class="flex items-center notificacion-clicable bg-gray-{{ $leido ? '200' : '100' }} dark:bg-gray-{{ $leido ? '600' : '800' }} rounded-lg mb-4 p-3 cursor-pointer transition duration-300 ease-in-out transform hover:scale-105">
                        <img src="https://via.placeholder.com/40" alt="User" class="w-8 h-8 rounded-full">
                        <div class="font-bold text-gray-{{ $leido ? '800' : '600' }} dark:text-gray-{{ $leido ? '200' : '400' }} ml-4">{{ $telefono }}</div>
                        @if (!$leido)
                            <span class="bg-red-500 text-white text-xs font-semibold rounded-full px-2">Nuevo</span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Chat -->
        <div id="abrirchat"
            class="relative w-1/2 bg-white dark:bg-slate-200 rounded-lg px-6 py-6 mt-5 ring-1 ring-slate-900/5 shadow-xl"
            style="display:none;">
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
            <div class="flex flex-col h-70 border border-gray-300 rounded-lg px-4 py-6 space-y-4" >
                <!-- Historial de mensajes -->
                <div class="flex items-center bg-gray-200 p-4 rounded-lg shadow-md mt-4">
                <img src="https://via.placeholder.com/40" alt="User" class="w-8 h-8 rounded-full">
                <div id="telefono-chat" class="ml-4"></div>
                </div>
                <div id="historial-mensajes" class="bg-gray-200 p-4 rounded-lg mb-4  overflow-auto" style="max-height: 480px;">
                    <ul id="miLista">
                    </ul>
                </div>
                <!-- Campo de texto para escribir -->
                <form id="mensajeForm" class="mt-4">
                    <input type="text" id="mensajeInput"
                        class="w-4/5 border rounded-md py-2 px-5 focus:outline-none focus:border-blue-500"
                        placeholder="Escribe un mensaje..." >
                    <button type="submit"
                        class="ml-auto bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-md">Enviar</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function formatearHora(fechaHoraString) {
            var fechaHora = new Date(fechaHoraString);
            var hora = fechaHora.getHours();
            var minutos = fechaHora.getMinutes();
            var horaFormato = (hora < 10 ? '0' : '') + hora; // Agregar un cero delante si la hora es menor que 10
            var minutosFormato = (minutos < 10 ? '0' : '') + minutos; // Agregar un cero delante si los minutos son menores que 10
            return horaFormato + ':' + minutosFormato;
        }
        // Obtener la lista
        var lista = document.getElementById("miLista");
        var telefonoEmisor = "593987411818";

        // function crearLineaChat(elemento) {

        //     var nuevoElemento = document.createElement("div");
        //     var elementoH1 = document.createElement("h1");
        //     var horaElemento = document.createElement("p");
        //     var otroElemento = document.createElement("div");

        //     console.log("El elemento es: " + elemento['mensaje_recibido']);
        //     console.log("el telefono es: "+ elemento['telefono_wa']);

        //     // elementoH1.textContent = elemento['mensaje_recibido'];
        //     horaElemento.textContent = formatearHora(elemento['fecha_hora']);
        //     //Estilos de la fecha
        //     horaElemento.style.fontSize = "12px"; // Tamaño de fuente para la hora
        //     horaElemento.style.color = "#999"; // Color de la hora
        //     horaElemento.style.marginTop = "5px"; // Margen superior para separar la hora del mensaje
        //     // Estilos del div y del h1
        //     nuevoElemento.style.border = "1px solid black";
        //     nuevoElemento.style.borderRadius = "10px"
        //     nuevoElemento.style.borderRadius = "5px";
        //     nuevoElemento.style.padding = "5px";
        //     nuevoElemento.style.marginBottom = "10px";
        //     nuevoElemento.style.backgroundColor = '#CCC9C9';
        //     nuevoElemento.style.fontFamily = "Arial, sans-serif";
        //     nuevoElemento.style.fontSize = "20px"; // Tamaño de fuente
        //     nuevoElemento.style.lineHeight = "1.5";
        //     nuevoElemento.style.marginRight = "50px";
        //     nuevoElemento.style.marginLeft = "100px"
        //     nuevoElemento.style.backgroundColor = "transparent";

        //     otroElemento.style.color = "white";

        //     // Aplicar estilos al div del mensaje recibido
        //      // Margen inferior del div

        //     nuevoElemento.appendChild(elementoH1);
        //     nuevoElemento.appendChild(horaElemento);

        //     if (elemento['telefono_wa'] == telefonoEmisor) {
        //         nuevoElemento.style.color = 'red'; // Cambiar a color rojo
        //         nuevoElemento.style.textAlign = 'right'; // Alinear a la derecha

        //     }
        //     return nuevoElemento;
        //     return otroElemento;

        // }

        function crearMensajeRecibido(elemento) {

            var divGrande = document.createElement("div");
            var nuevoElemento = document.createElement("div");
            var elementoH1 = document.createElement("h1");
            var horaElemento = document.createElement("p");

            console.log("El elemento es: " + elemento['mensaje_recibido']);
            console.log("el telefono es: "+ elemento['telefono_wa']);

            elementoH1.textContent = elemento['mensaje_recibido'];
            horaElemento.textContent = formatearHora(elemento['fecha_hora']);
            //Estilos de la fecha
            horaElemento.style.fontSize = "12px";
            horaElemento.style.color = "#999";
            horaElemento.style.marginTop = "6px";
            // Estilos del div y del h1
            nuevoElemento.style.borderRadius = "10px";
            nuevoElemento.style.padding = "5px";
            nuevoElemento.style.marginBottom = "10px";
            nuevoElemento.style.backgroundColor = '#CCC9C9';
            nuevoElemento.style.fontFamily = "Monserrat";
            nuevoElemento.style.fontSize = "18px";
            nuevoElemento.style.lineHeight = "1";
            nuevoElemento.style.color = 'Black';
            nuevoElemento.style.textAlign = 'justify';
            nuevoElemento.style.marginRight = "100px";
            elementoH1.style.marginLeft = '10px';
            nuevoElemento.style.boxShadow = "0 2px 4px rgba(0, 0, 0, 0.1)";


            nuevoElemento.appendChild(elementoH1);
            nuevoElemento.appendChild(horaElemento);
            divGrande.appendChild(nuevoElemento);

            return divGrande;
        }

        function crearMensajeEnviado(elemento) {
            var divGrande = document.createElement("div");
            var nuevoElemento = document.createElement("div");
            var elementoH1 = document.createElement("h1");
            var horaElemento = document.createElement("p");

            elementoH1.textContent = elemento['mensaje_recibido'];
            horaElemento.textContent = formatearHora(elemento['fecha_hora']);
            //Estilos de la fecha
            horaElemento.style.fontSize = '12px';
            horaElemento.style.color = '#999';
            horaElemento.style.marginTop = "6px";
            // Estilos del div y del h1
            nuevoElemento.style.borderRadius = "10px";
            nuevoElemento.style.padding = "5px";
            nuevoElemento.style.marginBottom = "10px";
            nuevoElemento.style.backgroundColor = '#CCC9C9';
            nuevoElemento.style.fontFamily = "Monserrat";
            nuevoElemento.style.fontSize = "18px";
            nuevoElemento.style.lineHeight = "1";
            nuevoElemento.style.color = 'red';
            nuevoElemento.style.textAlign = 'right';
            nuevoElemento.style.marginLeft = "300px";
            elementoH1.style.marginRight = '10px';
            nuevoElemento.style.boxShadow = "0 2px 4px rgba(0, 0, 0, 0.1)"; 
            nuevoElemento.appendChild(elementoH1);
            nuevoElemento.appendChild(horaElemento);
            divGrande.appendChild(nuevoElemento);

            return divGrande;
        }

        function abrirchat(telefono, mensajes) {
            var lista = document.getElementById("miLista"); // Asegúrate de tener la referencia correcta a tu lista
            var VentanaChat = document.getElementById("abrirchat");

            // Ordenar mensajes por fecha y hora
            mensajes.sort(function(a, b) {
                return new Date(a.fecha_hora) - new Date(b.fecha_hora);
            });

            // Eliminar el icono "Nuevo" de la notificación correspondiente
            var notificacion = document.querySelector(`[data-telefono="${telefono}"]`);
            if (notificacion) {

                var mensajesGuardados = localStorage.getItem('mensajesLeidos');
                    if (!mensajesGuardados) {
                        mensajesGuardados = [];
                    } else {
                        mensajesGuardados = JSON.parse(mensajesGuardados);
                    }
                // Verificar si el teléfono ya ha sido leído
                if (!mensajesGuardados.includes(telefono)) {
                mensajesGuardados.push(telefono);
                localStorage.setItem('mensajesLeidos', JSON.stringify(mensajesGuardados));
                var nuevoElemento = notificacion.querySelector('.bg-red-500');
                if (nuevoElemento) {
                    nuevoElemento.remove();
                    }
                }
            }


            if (lista.childElementCount > 0) { // La lista tiene hijos, la vaciamos
                while (lista.firstChild) {
                    lista.removeChild(lista.firstChild);
                }
            }

            if (mensajes && mensajes.length > 0) {
                mensajes.forEach(function(elemento) {

                   var elementoCreado;

                   if (elemento['telefono_wa'] == telefonoEmisor) {
                        elementoCreado = crearMensajeEnviado(elemento);
                    } else {
                        elementoCreado = crearMensajeRecibido(elemento);
                    }
                    lista.appendChild(elementoCreado);

                });
                lista.scrollTop = lista.scrollHeight;
            }


            console.log('mensajes chat: ', mensajes);

            if (VentanaChat.style.display === 'none') {
                VentanaChat.style.display = 'block';
                document.getElementById("telefono-chat").textContent = telefono;
            } else {
                VentanaChat.style.display = 'none';
            }
        }


        function cerrarChat() {
            var chat = document.getElementById('abrirchat');
            chat.style.display = 'none';
            document.getElementById("miLista").innerText = "";
        }

    </script>

</x-app-layout>


