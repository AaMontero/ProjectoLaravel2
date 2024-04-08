<x-app-layout>
    <x-slot name="header">
        <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    </x-slot>
    <style>
        .notificacion-clicable {
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .notificacion-clicable:hover {
            background-color: rgba(0, 0, 0, 0.1);
        }
    </style>
    <div class="flex flex-col lg:flex-row lg:space-x-8">
        <!-- Notificaciones -->
        <div id="notificaciones"
            class="w-full lg:w-1/2 bg-white dark:bg-slate-200 px-8 py-8 mt-5 ring-1 ring-slate-900/5 shadow-xl overflow-auto"
            style="max-height: 700px; border-radius: 10px;">
            <h3 class="text-xl font-semibold mb-4">Notificaciones</h3>
            @foreach ($mensajes->groupBy('id_numCliente') as $telefono => $mensajesTelefono)
                @php
                    $ultimoMensaje = $mensajesTelefono->last();
                    $leido = $ultimoMensaje->visto == 1;
                @endphp
                <div class="space-y-4">
                    <div onclick="abrirchat('{{ $telefono }}', {{ json_encode($mensajesTelefono) }})"
                        data-telefono="{{ $telefono }}" data-id="{{ $ultimoMensaje['mensaje_enviado'] }}"
                        class="flex items-center notificacion-clicable bg-gray-{{ $leido ? '200' : '100' }} dark:bg-gray-{{ $leido ? '600' : '800' }} rounded-lg mb-4 p-3 cursor-pointer transition duration-300 ease-in-out transform hover:scale-105"
                        id="notificacion-{{ $ultimoMensaje->id }}">
                        <img src="https://via.placeholder.com/40" alt="User" class="w-8 h-8 rounded-full">
                        <div
                            class="font-bold text-gray-{{ $leido ? '800' : '600' }} dark:text-gray-{{ $leido ? '200' : '400' }} ml-4">
                            {{ $telefono }}
                        </div>
                        @if (!$leido)
                            <span class="bg-red-500 text-white text-xs font-semibold rounded-full px-2 ml-2">Nuevo
                            </span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Chat -->
        <div id="abrirchat"
            class="relative w-full lg:w-1/2 bg-white dark:bg-slate-200 rounded-lg px-6 py-6 mt-5 ring-1 ring-slate-900/5 shadow-xl"
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
            <div class="flex flex-col h-70 border border-gray-300 rounded-lg px-4 py-4 space-y-4">
                <!-- Historial de mensajes -->
                <div class="flex items-center bg-gray-200 p-2 rounded-lg shadow-md">
                    <img src="{{ asset('images\logoFondoNegro.jpeg') }}" alt="User" class="w-8 h-8 rounded-full">
                    <div id="telefono-chat" class="ml-4"></div>
                </div>
                <div id="historial-mensajes" class="bg-gray-200 p-2 rounded-lg mb-4 overflow-auto"
                    style="max-height: 500px;">
                    <ul id="miLista">
                    </ul>
                </div>
                <!-- Campo de texto para escribir -->
                <form id="mensajeForm" class="mt-4 flex items-center relative" method = "GET" action={{ route('chat.envia') }}>
                    @csrf
                    <input type ="hidden" id = "numeroEnvioOculto" name = "numeroEnvio">
                    <textarea id="mensajeInput" name="mensajeEnvio" class="w-full lg:w-4/5 border rounded-md py-2 px-3 lg:px-5 focus:outline-none focus:border-blue-500 ml-0 lg:ml-auto resize-none"
                    style="height: 40px;"
                    placeholder="Escribe un mensaje..."  onkeypress="enviarConEnter(event)"></textarea>

                    <button type="button" onclick="enviarFormulario()"
                        class=" bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-md">Enviar</button>
                </form>
            </div>
        </div>
    </div>


    <script>

        //Recibir los mensajes en tiempo real
        Pusher.logToConsole = true;
        var pusher = new Pusher('217450e1ce096539fb1c', {
            cluster: 'sa1'
        });
        var channel = pusher.subscribe('whatsapp-channel');
        channel.bind('whatsapp-event', function(data) {
            var objeto = {
                mensaje_enviado: data['mensaje'],
                fecha_hora: data['horaMensaje'],
                numero: data['usuario']
            }
            var lista = document.getElementById("miLista");
            lista.appendChild(crearMensajeRecibido(objeto));

        });

        function enviarFormulario() {
            llamadaAjax()
                .then((respuesta) => {
                    try {
                        var objeto = JSON.parse(respuesta);
                        var lista = document.getElementById("miLista");
                        lista.appendChild(crearMensajeEnviado(objeto));
                        document.getElementById("mensajeInput").value = "";
                    } catch (error) {
                        console.error("Error al analizar el JSON:", error);
                    }
                })
                .catch((error) => {
                    console.error("Error en la llamada AJAX:", error);
                });
        }

        function llamadaAjax() { //
            return new Promise((resolve, reject) => {
                const textoIngresado = document.getElementById("mensajeInput").value;
                const numeroAbierto = document.getElementById("numeroEnvioOculto").value;
                const url =
                    'http://localhost:8000/enviaWpp?_token=BArhkXdxx3XTqwCablP7TY6IWlBox9tl254qbkhM&numeroEnvio=' +
                    numeroAbierto + '&mensajeEnvio=' +
                    textoIngresado;

                fetch(url)
                    .then((response) => {
                        if (!response.ok) {
                            throw new Error(`Error en la respuesta del servidor: ${response.statusText}`);
                        }
                        return response.text();
                    })
                    .then((responseData) => {
                        resolve(responseData);
                    })
                    .catch((error) => {
                        console.error("Error en la llamada fetch:", error);
                        reject(error);
                    });
            });
        }

        function enviarConEnter(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                enviarFormulario();
            }
        }

        // Obtener la lista
        var lista = document.getElementById("miLista");
        var telefonoEmisor = "593987411818";

        function crearMensajeImgRecibido(elemento) {
            datosImg = JSON.parse(elemento['mensaje_enviado']);
            urlImg = datosImg.ruta
            msnImg = datosImg.textoImagen;
            var divGrande = document.createElement("div");
            var nuevoElemento = document.createElement("div");
            var elementoH1 = document.createElement("h4");
            var horaElemento = document.createElement("small");
            var imagenElemento = document.createElement("img");
            elementoH1.textContent = msnImg;
            horaElemento.textContent = formatearHora(elemento['fecha_hora']);
            imagenElemento.src = urlImg;
            imagenElemento.style = `
            width: 350px;
            height: auto;
            margin-bottom: 5px;
            margin-left:5px;
            `;

            // Estilos para la hora
            horaElemento.style = `
        font-size: 12px;
        color: #515151;
        margin-left: 10px;
        `;

            // Estilos para el nuevo elemento
            nuevoElemento.style = `
        border-radius: 10px;
        margin-bottom: 8px;
        background-color: #ffffff;
        font-family: Monserrat;
        font-size: 15px;
        padding-right: 10px;
        line-height: 1;
        color: #00000;
        margin-right: 100px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        `;

            // Ajuste de dimensiones del cuadro según la longitud del mensaje
            if (elemento['mensaje_enviado'].length < 20) {
                nuevoElemento.style.display = "flex";
                var dimensionCuadro = 105 + elemento['mensaje_enviado'].length * 10;
                nuevoElemento.style.width = dimensionCuadro + 'px';
                horaElemento.style.marginTop = "20px";
                horaElemento.style.marginLeft = "35px";
            } else {
                nuevoElemento.style.display = "inline-block";
            }
            // Estilos para el elemento H1
            elementoH1.style.marginLeft = '10px';
            // Agregar elementos al nuevo elemento
            nuevoElemento.appendChild(elementoH1);
            nuevoElemento.appendChild(horaElemento);
            nuevoElemento.appendChild(imagenElemento); // Agregar imagen al nuevo elemento
            divGrande.appendChild(nuevoElemento);

            return divGrande;
        }

        function crearMensajeRecibido(elemento) {
            var divGrande = document.createElement("div");
            var nuevoElemento = document.createElement("div");
            var elementoH1 = document.createElement("h4");
            var horaElemento = document.createElement("small");
            elementoH1.textContent = elemento['mensaje_enviado'];
            elementoH1.style.fontSize = '1.1rem';
            horaElemento.textContent = formatearHora(elemento['fecha_hora']);

            horaElemento.style = `
        font-size: 12px;
        color: #515151;
        margin-left: 10px;
        `;
        //font-size: 13px;
            nuevoElemento.style = `
        border-radius: 10px;
        margin-bottom: 8px;
        background-color: #ffffff;
        font-family: Monserrat;
        padding-right:10px;
        line-height: 1;
        color: #00000;
        margin-right: 100px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); `;
            if (elemento['mensaje_enviado'].length < 20) {
                nuevoElemento.style.display = "flex";
                var dimensionCuadro = 105 + elemento['mensaje_enviado'].length * 10;
                nuevoElemento.style.width = dimensionCuadro + 'px';
                horaElemento.style.marginTop = "20px";
                horaElemento.style.marginLeft = "35px";

            } else {
                nuevoElemento.style.display = "inline-block";
            }
            elementoH1.style.marginLeft = '10px';
            nuevoElemento.appendChild(elementoH1);
            nuevoElemento.appendChild(horaElemento);
            divGrande.appendChild(nuevoElemento);

            return divGrande;
        }

        function crearCuadroFecha(fecha) {
            var divFecha = document.createElement("div");
            var small = document.createElement("small");
            small.textContent = fecha;
            divFecha.style = 'text-align: center; margin-bottom: 8px;';
            small.style =
                ' font-weight: bold; padding: 5px; background-color: white; border-radius: 5px; margin-bottom: 4px; color: gray';
            divFecha.appendChild(small);
            return divFecha;
        }

        function crearMensajeEnviado(elemento) {
            // Crear elementos DOM
            var divGrande = document.createElement("div");
            var nuevoElemento = document.createElement("div");
            var elementoH1 = document.createElement("h4");
            var horaElemento = document.createElement("small");
            divGrande.style = `width: 100%; background-color: black;`;
            // Configurar contenido y estilos
            elementoH1.textContent = elemento['mensaje_enviado'];
            elementoH1.style.fontSize = '1.1rem';
            horaElemento.textContent = formatearHora(elemento['fecha_hora']);

            divGrande.style = `display: flex; justify-content: flex-end; `;
            horaElemento.style = `
        font-size: 12px;
        color: #515151;
        margin-left: 30px;

        `;

            nuevoElemento.style = `
        border-radius: 10px;
        margin-bottom: 8px;
        background-color: #dcf8c6;
        font-family: Monserrat;
        line-height: 1;
        color: #00000;
        padding-left: 10px;
        text-align: right;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); `;
            if (elemento['mensaje_enviado'].length < 20) {
                nuevoElemento.style.display = "flex";
                var dimensionCuadro = 105 + elemento['mensaje_enviado'].length * 10;
                nuevoElemento.style.width = dimensionCuadro + 'px';
                horaElemento.style.marginTop = "20px";

            } else {
                nuevoElemento.style.display = "inline-block";
                horaElemento.style.marginRight = "10px";
            }
            elementoH1.style.marginRight = '10px';
            nuevoElemento.appendChild(elementoH1);
            nuevoElemento.appendChild(horaElemento);
            divGrande.appendChild(nuevoElemento);

            return divGrande;
        }

        function marcarMensajesComoLeidos(idChat) {

            var xhr = new XMLHttpRequest();
            xhr.open('GET', '/visto/' + idChat, true); // Ruta del endpoint
            xhr.setRequestHeader('Content-Type', 'application/json');

            xhr.onload = function() {
                if (xhr.status >= 200 && xhr.status < 300) {
                    console.log('Mensajes marcados como leídos exitosamente');
                } else {
                    console.error('Error al marcar los mensajes como leídos: ' + xhr.status);
                }
            };

            xhr.onerror = function() {
                console.error('Error de red al marcar los mensajes como leídos');
            };

            xhr.send(); // Envía la solicitud POST
        }

        function abrirchat(telefono, mensajes) {
            var lista = document.getElementById("miLista"); // Asegúrate de tener la referencia correcta a tu lista
            var VentanaChat = document.getElementById("abrirchat");
            document.getElementById("numeroEnvioOculto").value = telefono;
            elementos = document.getElementById("historial-mensajes");

            // Ordenar mensajes por fecha y hora
            mensajes.sort(function(a, b) {
                return new Date(a.fecha_hora) - new Date(b.fecha_hora);
            });

            // Eliminar el icono "Nuevo" de la notificación correspondiente
            var notificacion = document.querySelector(`[data-telefono="${telefono}"]`);
            if (notificacion) {
                var nuevoElemento = notificacion.querySelector('.bg-red-500');
                if (nuevoElemento) {
                    nuevoElemento.remove();
                }
            }
            // Limpiar la lista de mensajes existentes
            lista.innerHTML = '';

            // Mostrar el teléfono del chat actual
            document.getElementById("telefono-chat").textContent = telefono;
            var horaMensajeAnterior = null;

            if (mensajes && mensajes.length > 0) {
                var fecha = mensajes[0]['fecha_hora'].substring(0, 10);
                elementoCreado = crearCuadroFecha(transformarFecha(fecha));
                lista.appendChild(elementoCreado);
                mensajes.forEach(function(elemento) {
                    var fecha = elemento['fecha_hora'].substring(0, 10);
                    var elementoCreado;
                    if (horaMensajeAnterior !== null && horaMensajeAnterior != fecha) {
                        elementoCreado = crearCuadroFecha(transformarFecha(fecha));
                        lista.appendChild(elementoCreado);
                    }
                    if (elemento['telefono_wa'] == telefonoEmisor) {
                        elementoCreado = crearMensajeEnviado(elemento);
                    } else {
                        if (elemento['mensaje_enviado'].startsWith('{')) {

                            elementoCreado = crearMensajeImgRecibido(elemento);
                        } else {
                            elementoCreado = crearMensajeRecibido(elemento);
                        }

                    }
                    horaMensajeAnterior = fecha;
                    lista.appendChild(elementoCreado);
                });
            }
            setTimeout(function() {
                VentanaChat.style.display = 'block';
                elementos.scrollTop = elementos.scrollHeight;
            }, 1000);


            marcarMensajesComoLeidos(telefono);
        }


        function cerrarChat() {
            var chat = document.getElementById('abrirchat');
            chat.style.display = 'none';
            document.getElementById("miLista").innerText = "";
        }


        function transformarFecha(fechaString) {
            var partesFecha = fechaString.split('-');
            var año = partesFecha[0];
            var mes = partesFecha[1];
            var dia = partesFecha[2];
            var fecha = new Date(año, mes - 1,
                dia);
            var nombresMeses = ["enero", "febrero", "marzo", "abril", "mayo", "junio", "julio", "agosto", "septiembre",
                "octubre", "noviembre", "diciembre"
            ];
            var fechaFormateada = dia + " de " + nombresMeses[mes - 1] + " de " + año;
            return fechaFormateada;
        }

        function formatearHora(fechaHoraString) {
            var fechaHora = new Date(fechaHoraString);
            var hora = fechaHora.getHours();
            var minutos = fechaHora.getMinutes();
            var horaFormato = (hora < 10 ? '0' : '') + hora; // Agregar un cero delante si la hora es menor que 10
            var minutosFormato = (minutos < 10 ? '0' : '') +
                minutos; // Agregar un cero delante si los minutos son menores que 10
            return horaFormato + ':' + minutosFormato;
        }
    </script>

</x-app-layout>
