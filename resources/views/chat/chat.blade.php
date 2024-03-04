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
                <div id="historial-mensajes" class="bg-gray-200 p-4 rounded-lg mb-4">
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

        //formato de la hora
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
            nuevoElemento.style.fontFamily = "Arial, sans-serif";
            nuevoElemento.style.fontSize = "20px";
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
            nuevoElemento.style.fontFamily = "Arial, sans-serif";
            nuevoElemento.style.fontSize = "20px";
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


{{-- <div class="" role="row"><div tabindex="-1" class="CzM4m _2zFLj _3sxvM" data-id="true_593998557785@c.us_3EB04F7528778CD583D844_out"><div class="message-out focusable-list-item _1AOLJ _2UtSC _1jHIY"><span class=""></span><div class="UzMP7 _1uv-a _3m5cz"><span data-icon="tail-out" class="p0s8B"><svg viewBox="0 0 8 13" height="13" width="8" preserveAspectRatio="xMidYMid meet" class="" version="1.1" x="0px" y="0px" enable-background="new 0 0 8 13"><title>tail-out</title><path opacity="0.13" d="M5.188,1H0v11.193l6.467-8.625 C7.526,2.156,6.958,1,5.188,1z"></path><path fill="currentColor" d="M5.188,0H0v11.193l6.467-8.625C7.526,1.156,6.958,0,5.188,0z"></path></svg></span><div class="_1BOF7 _2AOIt"><span aria-label="Tú:"></span><div><div class="cm280p3y to2l77zo n1yiu2zv c6f98ldp ooty25bp oq31bsqd"><div class="copyable-text" data-pre-plain-text="[10:07, 29/1/2024] Johan: "><div class="_21Ahp"><span dir="ltr" aria-label="" class="_11JPr selectable-text copyable-text" style="min-height: 0px;"><span>Documentos editados</span></span><span class=""><span class="o38k74y6 i86elurf neme6l2y kojwoqec bbl9m3t3 i5tg98hk jfqm35v0 przvwfww bdbt56hn cr2cog7z" aria-hidden="true"><span class="tvf2evcx oq44ahr5 qg8w82as"></span><span class="tvf2evcx oq44ahr5">10:07</span></span></span></div></div><div class="g0rxnol2 g2bpp9au faxx4fbg aja0i6dq jnwc1y2a bn7x0pqn qnz2jpws"><div class="gq1t1y46 o38k74y6 e4p1bexh cr2cog7z le5p0ye3 p357zi0d gndfcl4n" role="button"><span class="l7jjieqr fewfhwl7" dir="auto">10:07</span><div class="do8e0lj9 l7jjieqr k6y3xtnu"><span aria-label=" Leído " data-icon="msg-dblcheck" class="ajgik1ph"><svg viewBox="0 0 16 11" height="11" width="16" preserveAspectRatio="xMidYMid meet" class="" fill="none"><title>msg-dblcheck</title><path d="M11.0714 0.652832C10.991 0.585124 10.8894 0.55127 10.7667 0.55127C10.6186 0.55127 10.4916 0.610514 10.3858 0.729004L4.19688 8.36523L1.79112 6.09277C1.7488 6.04622 1.69802 6.01025 1.63877 5.98486C1.57953 5.95947 1.51817 5.94678 1.45469 5.94678C1.32351 5.94678 1.20925 5.99544 1.11192 6.09277L0.800883 6.40381C0.707784 6.49268 0.661235 6.60482 0.661235 6.74023C0.661235 6.87565 0.707784 6.98991 0.800883 7.08301L3.79698 10.0791C3.94509 10.2145 4.11224 10.2822 4.29844 10.2822C4.40424 10.2822 4.5058 10.259 4.60313 10.2124C4.70046 10.1659 4.78086 10.1003 4.84434 10.0156L11.4903 1.59863C11.5623 1.5013 11.5982 1.40186 11.5982 1.30029C11.5982 1.14372 11.5348 1.01888 11.4078 0.925781L11.0714 0.652832ZM8.6212 8.32715C8.43077 8.20866 8.2488 8.09017 8.0753 7.97168C7.99489 7.89128 7.8891 7.85107 7.75791 7.85107C7.6098 7.85107 7.4892 7.90397 7.3961 8.00977L7.10411 8.33984C7.01947 8.43717 6.97715 8.54508 6.97715 8.66357C6.97715 8.79476 7.0237 8.90902 7.1168 9.00635L8.1959 10.0791C8.33132 10.2145 8.49636 10.2822 8.69102 10.2822C8.79681 10.2822 8.89838 10.259 8.99571 10.2124C9.09304 10.1659 9.17556 10.1003 9.24327 10.0156L15.8639 1.62402C15.9358 1.53939 15.9718 1.43994 15.9718 1.32568C15.9718 1.1818 15.9125 1.05697 15.794 0.951172L15.4386 0.678223C15.3582 0.610514 15.2587 0.57666 15.1402 0.57666C14.9964 0.57666 14.8715 0.635905 14.7657 0.754395L8.6212 8.32715Z" fill="currentColor"></path></svg></span></div></div></div></div></div><span class=""></span><div class="_1OdBf"></div></div><div class="tvf2evcx m0h2a7mj lb5m6g5c j7l1k36l ktfrpxia nu7pwgvd p357zi0d dnb887gk gjuq5ydh i2cterl7 kcgo1i74 sap93d0t gndfcl4n FxqSn"><div class="tvf2evcx m0h2a7mj lb5m6g5c j7l1k36l ktfrpxia nu7pwgvd dnb887gk gjuq5ydh i2cterl7 rqm6ogl5 i5tg98hk folpon7g przvwfww snweb893"><div></div></div></div></div><div class="p357zi0d g0rxnol2 blj1rie1 px10qoeu ltyqj8pj b4u6kxhc rwlvdxyg"></div></div></div></div> --}}
