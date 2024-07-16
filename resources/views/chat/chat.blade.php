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
    <div class="flex flex-col lg:flex-row lg:space-x-8 pl-4">
        <!-- Notificaciones -->
        <div id="notificaciones"
            class=" bg-white dark:bg-slate-200 px-8 py-8 mt-5 ring-1  rounded-lg
            ring-slate-900/5 shadow-xl ">
            <h3 class="text-xl font-semibold mb-4">Notificaciones</h3>
            @foreach ($mensajes->groupBy('id_numCliente') as $telefono => $mensajesTelefono)
                @php
                    $ultimoMensaje = $mensajesTelefono->last();
                    $leido = $ultimoMensaje->visto == 1;
                @endphp
                <div class="space-y-4">
                    <div onclick="abrirchat('{{ $telefono }}', {{ json_encode($mensajesTelefono) }})"
                        data-telefono="{{ $telefono }}" data-id="{{ $ultimoMensaje['mensaje_enviado'] }}"
                        class="w-96 flex items-center notificacion-clicable bg-gray-{{ $leido ? '200' : '100' }} dark:bg-gray-{{ $leido ? '600' : '800' }} rounded-lg mb-4 p-3 cursor-pointer transition duration-300 ease-in-out transform hover:scale-105"
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
        <div id="abrirchat" style="display: none; width: 600px; "
            class="relative w-full  bg-white dark:bg-slate-200 rounded-lg px-6 py-6 mt-5 ring-1 ring-slate-900/5 shadow-xl">
            <h3 class="text-xl font-semibold">Chat</h3>
            <button onclick="cerrarChat()" class="absolute top-6 right-4 text-gray-600 hover:text-gray-800 h-20">
                <!-- Icono de cierre (X) -->
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>

            <!-- Historial de mensajes -->
            <div class="flex flex-col border border-gray-300 rounded-lg px-4 py-4 space-y-4">
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
                {{-- Campo para mostrar las imagenes subidas --}}
                <div id="archivoSeleccionado" class="flex items-center ml-1w-20 h-8" style="display:none;">
                    <img src="{{ asset('images\clip.png') }}" alt="Icono de archivo" id="iconoArchivoSeleccionado"
                        class="w-6 h-6 rounded">
                    <p id="nombreArchivoSeleccionado" class="ml-3 mt-3 text-xs"></p>
                </div>
                <!-- Campo de texto para escribir -->
                <form id="mensajeForm" class="mt-2 flex items-center relative" method = "POST"
                    action="{{ route('chat.envia') }}" enctype="multipart/form-data">
                    @csrf
                    <input type ="hidden" id = "numeroEnvioOculto" name = "numeroEnvio">
                    <textarea id="mensajeInput" name="mensajeEnvio"
                        class="w-full lg:w-4/5 border rounded-md py-2 px-3 lg:px-5 focus:outline-none focus:border-blue-500 ml-0 lg:ml-auto resize-none"
                        style="height: 40px; overflow:hidden" placeholder="Escribe un mensaje..." onkeypress="enviarConEnter(event)"></textarea>
                    <input type="file" name="archivo" id="archivo" style="display: none;"
                        accept="image/jpeg, image/png, .pdf, .doc, .docx, .xlsx, .xls, .xml, .svg">
                    <!-- Input oculto para la carga de archivos -->
                    <label for="archivo" class=" font-semibold py-2 px-3 rounded-md cursor-pointer">
                        <img src="{{ asset('images\nube.png') }}" alt="Icono de archivos" class="w-10 h-10 rounded"
                            id="iconoArchivo">
                    </label>
                    <button type="button" onclick="enviarFormulario()"
                        class=" bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-md">Enviar</button>
                </form>
            </div>
        </div>
    </div>


    <script>
        document.getElementById('archivo').addEventListener('change', function(event) {
            const archivo = event.target.files[0];
            const iconoArchivo = document.getElementById('iconoArchivo');
            const iconoArchivoSeleccionado = document.getElementById('iconoArchivoSeleccionado');
            const nombreArchivoSeleccionado = document.getElementById('nombreArchivoSeleccionado');

            if (archivo) {
                if (archivo.type.includes('image')) {
                    iconoArchivo.src = "{{ asset('images/imagen.png') }}";
                    iconoArchivoSeleccionado.src = "{{ asset('images/imagen.png') }}";

                } else {
                    iconoArchivo.src = "{{ asset('images/archivo.png') }}";
                    iconoArchivoSeleccionado.src = "{{ asset('images/archivo.png') }}";
                }
                nombreArchivoSeleccionado.textContent = archivo.name;

                // Mostrar el contenedor del archivo seleccionado
                document.getElementById('archivoSeleccionado').style.display = 'flex';


            } else {
                // Ocultar el contenedor del archivo seleccionado si no se selecciona ningún archivo
                document.getElementById('archivoSeleccionado').style.display = 'none';
            }

        });

        var desarrollo = true;
        var telefonoEmisor = '593999938356';
        var tokenFin =
            "EAAX58V5eyEEBOw68TUvx8eKrZAGnZBwHkAGkXI3VvGVnrrOgWOxjFJJGMOsJBXvuyiqypELtmuVJkdVBkQKoIBLopZAtAep3xkPv85wQnrYiEQ4paIokQZCCn5JEDJT6VdEx1jyBgckIhOxQkEH15qZAVd7TQHKNMX92mQNZAJQXRporOTY17DrZAu8KAIpZBaVZAHUwZCtgg48C3ZBJ9limN2C1V4oVugZD";
        var urlPrincipal = "https://aitriv.com"
        if (desarrollo) {
            urlPrincipal = "127.0.0.1:8000"
            telefonoEmisor = '593987411818';
            tokenFin =
                "EAALYfjkUo48BO06TlfWIDkeLafOPCf6rpQ9bsj04emZBwgF4k1RkjpQCbVuITExfGuM0MPhGwyQQa1J8UwqSy0KYEmfbwxZASwNIXXhEjsM0VMP5S4SEKZChrvr0t6iJZAVrhbSNh44VBZBAPZCSrXsJwdGXZC2bF0ZBt55tHwMggZCet6CdQi62ncUkNdKN1DQFd";
        }
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

        // Variable global para rastrear el estado de envío de la imagen
        var imagenEnviada = false;

        function enviarFormulario() {
            // Verificar si la imagen ya se ha enviado
            if (imagenEnviada) {
                console.log("La imagen ya ha sido enviada, no se puede enviar nuevamente.");
                return; // Salir de la función si la imagen ya ha sido enviada
            }

            llamadaAjax()
                .then((respuesta) => {
                    console.log("Respuesta del servidor:", respuesta);
                    try {
                        var objeto = JSON.parse(respuesta);
                        var lista = document.getElementById("miLista");
                        var mensajeEnviado = objeto.mensaje_enviado;
                        if (typeof mensajeEnviado === 'string' && mensajeEnviado.startsWith('{"ruta":')) {
                            // Si mensaje_enviado es una cadena y comienza con '{"ruta":', entonces parsearla como JSON
                            mensajeEnviado = JSON.parse(mensajeEnviado);
                        }
                        console.log(objeto.ruta);
                        if (mensajeEnviado.ruta !== undefined && mensajeEnviado.ruta !== "") {
                            lista.appendChild(crearMensajeImgEnviado(objeto));
                            // Establecer la variable de estado de la imagen enviada a true
                            imagenEnviada = true;
                        } else {
                            lista.appendChild(crearMensajeEnviado(objeto));
                        }
                        document.getElementById("mensajeInput").value = "";

                        // Reiniciar la imagen y el nombre del archivo seleccionado
                        document.getElementById('archivoSeleccionado').style.display = 'none';
                        document.getElementById("iconoArchivo").src = "{{ asset('images/nube.png') }}";
                        document.getElementById("nombreArchivoSeleccionado").textContent = "";
                    } catch (error) {
                        console.error("Error al analizar el JSON:", error);
                    }
                })
                .catch((error) => {
                    console.error("Error en la llamada AJAX:", error);
                });
        }

        function llamadaAjax() {
            return new Promise((resolve, reject) => {
                const formData = new FormData(document.getElementById('mensajeForm'));

                // Iterar sobre los valores en el FormData y hacer un console.log
                for (const entry of formData.entries()) {
                    console.log(entry);
                }
                fetch("{{ route('chat.envia') }}", {
                        method: "POST",
                        body: formData,
                    })
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
        var telefonoPruebas = "";

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
                font-size: 12px;
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
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            `;
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

        function crearMensajeImgEnviado(elemento) {
            datosImg = JSON.parse(elemento['mensaje_enviado']);
            urlImg = datosImg.ruta;
            msnImg = datosImg.textoImagen;
            var divGrande = document.createElement("div");
            var nuevoElemento = document.createElement("div");
            var elementoH1 = document.createElement("h4");
            var horaElemento = document.createElement("small");
            var imagenElemento = document.createElement("img");

            divGrande.style = `display: flex; justify-content: flex-end; `;
            elementoH1.textContent = msnImg;
            horaElemento.textContent = formatearHora(elemento['fecha_hora']);
            imagenElemento.src = urlImg;
            imagenElemento.style = `
                width: 300px;
                height: auto;
                margin-bottom: 5px;
                margin-right: 5px;
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
                background-color: #dcf8c6;
                font-family: Monserrat;
                font-size: 12px;
                padding-left: 10px;
                line-height: 1;
                color: #00000;
                padding-left: 10px;
                margin-left: 100px;
                text-align: right;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            `;

            // Ajuste de dimensiones del cuadro según la longitud del mensaje
            if (elemento['mensaje_enviado'].length < 20) {
                nuevoElemento.style.display = "flex";
                var dimensionCuadro = 105 + elemento['mensaje_enviado'].length * 10;
                nuevoElemento.style.width = dimensionCuadro + 'px';
                horaElemento.style.marginTop = "15px"; /* Cambiado a 15px */
                horaElemento.style.marginLeft = "30px";
            } else {
                nuevoElemento.style.display = "inline-block";
            }
            // Estilos para el elemento H1
            elementoH1.style.marginLeft = '5px';
            // Agregar elementos al nuevo elemento
            nuevoElemento.appendChild(elementoH1);
            nuevoElemento.appendChild(horaElemento);
            nuevoElemento.appendChild(imagenElemento); // Agregar imagen al nuevo elemento
            divGrande.appendChild(nuevoElemento);

            return divGrande;
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
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            `;
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

                        if (elemento['mensaje_enviado'].startsWith('{')) {
                            //console.log("entra 1");
                            elementoCreado = crearMensajeImgEnviado(elemento);
                        } else {
                            //console.log("entra 2");
                            elementoCreado = crearMensajeEnviado(elemento);
                        }

                    } else {
                        if (elemento['mensaje_enviado'].startsWith('{')) {
                            //console.log("entra 3");
                            elementoCreado = crearMensajeImgRecibido(elemento);
                        } else {
                            //console.log("entra 4");
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
