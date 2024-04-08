<x-app-layout>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate, post-check=0, pre-check=0">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">

    <title>Calendar</title>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css' rel='stylesheet'>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/locale/es.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.js"></script>



    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Calendario') }}
        </h2>
    </x-slot>

    <style>
        /* Estilos generales */
        body {
            background-color: #ffffff;
            color: #000000;
            font-family: Arial, sans-serif;
        }

        /* Estilos para la parte de Event Details */

        /* Animación para Event Details */
        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        /* Estilos para el calendario */
        #calendar {
            background-color: #a8a6a6e5;
            padding: 20px;
            border-radius: 10px;

        }

        /* Animación para el calendario */
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .fc-event {
            border-radius: 5px;
            /* Añadir bordes redondeados */
            padding: 5px;
            /* Añadir espacio interno */
            font-size: 14px;
            /* Tamaño de fuente */
            cursor: pointer;
            /* Cambiar el cursor al pasar sobre el evento */
        }

        /* Estilos para los botones del calendario */
        .fc-button {

            color: #000000;
            border-color: #000000;
            /* Red */
            border-radius: 5px;
            padding: 10px 15px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
    </style>

    <body>
        <!-- Modal -->
        <div class="modal fade" id="eventoModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Nuevo Evento</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <label for="estado">Estado</label>
                        <select class="form-select" id="title">
                            <option value="" disabled selected>Selecciona un Estado</option>
                            <option value="Prereservado">Pre-reservado</option>
                            <option value="Reservado">Reservado</option>
                            <option value="Disponible">Disponible</option>
                        </select>

                        <label for="cliente">Cliente</label>
                        <select class="form-select" id="cliente_select">
                            <option value="" disabled selected>Selecciona un Cliente</option>
                            @foreach ($clientes as $cliente)
                                <option value="{{ $cliente->id }}">{{ $cliente->nombres }} {{ $cliente->apellidos }} </option>
                            @endforeach
                        </select>


                        <label for="hotel">Hoteles</label>
                        <select class="form-select" id="hotel_nombre" name="hotel">
                            <option value="" disabled selected>Selecciona un Hotel</option>
                            @foreach ($hoteles as $hotel_nombre)
                                <option value="{{ $hotel_nombre }}">{{ $hotel_nombre }}</option>
                            @endforeach
                        </select>

                        <label for="">Fecha de Entrada</label>
                        <input type="date" class="form-control" id="start_date">
                        <label for="">Fecha de Salida</label>
                        <input type="date" class="form-control" id="end_date">

                        <span id="titleError" class="text-danger"></span>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="saveBtn">Guardar</button>
                    </div>
                </div>
            </div>
        </div>


        <!-- editar Modal flex justify-between-->
        <div class="modal fade" id="editarModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Editar Evento</h5>
                        <button type="button" id="close" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <label for="estado">Estado</label>
                        <select class="form-select" id="title_edit">
                            <option value="" disabled selected>Selecciona un Estado</option>
                            <option value="Prereservado">Pre-reservado</option>
                            <option value="Reservado">Reservado</option>
                            <option value="Disponible">Disponible</option>
                        </select>

                        <label for="cliente">Cliente</label>
                        <select class="form-select" id="cliente_edit">
                            <option value="" disabled selected>Selecciona un Cliente</option>
                            @foreach ($clientes as $cliente)
                                <option value="{{ $cliente->id }}">{{ $cliente->nombres }} {{ $cliente->apellidos }} </option>
                            @endforeach
                        </select>

                        <label for="hotel">Hoteles</label>
                        <select class="form-select" id="hotel_nombre_edit" name="hotel_nombre_edit">
                            <option value="" disabled selected>Selecciona un Hotel</option>
                            @foreach ($hoteles as $hotel_nombre)
                                <option value="{{ $hotel_nombre }}">{{ $hotel_nombre }}</option>
                            @endforeach
                        </select>

                        <label for="">Fecha de Entrada</label>
                        <input type="date" class="form-control" id="start_date_edit">
                        <label for="">Fecha de Salida</label>
                        <input type="date" class="form-control" id="end_date_edit">
                        <span id="titleError" class="text-danger"></span>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" id="updateBtn">Editar</button>
                        <button type="button" class="btn btn-danger" id="deleteBtn">Eliminar</button>
                    </div>

                </div>
            </div>
        </div>

        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="bg-gray-400 text-black shadow-md rounded px-4 py-4">
                        <h3 class="text-2xl font-bold mb-4 text-center ">Detalles del Evento</h3>
                        <hr class="mb-2">
                        <div id="event-details"
                            class="border-2 border-blue-900 bg-blue-900 shadow-md rounded px-4 py-2 text-white relative hidden"
                            style="display: none;">
                            <button type="button" id="closedetails" class="btn-close absolute top-0 right-0 mt-1 mr-1"
                                aria-label="Close"></button>
                            <div class="mb-2 mt-4">
                                <strong class="font-semibold">Estado:</strong>
                                <span id="tituloSpan" class="ml-2"></span>
                            </div>
                            <div class="mb-2">
                                <strong class="font-semibold">Cliente:</strong>
                                <span id="clienteSpan" class="ml-2"></span>
                            </div>
                            <div class="mb-2">
                                <strong class="font-semibold">Fecha de Entrada:</strong>
                                <span id="fechaInicioSpan" class="ml-2"></span>
                            </div>
                            <div class="mb-2">
                                <strong class="font-semibold">Fecha de Salida:</strong>
                                <span id="fechaFinSpan" class="ml-2"></span>
                            </div>
                            <div class="mb-2">
                                <strong class="font-semibold">Hoteles:</strong>
                                <span id="hotel_nombreSpan" class="ml-2"></span>
                            </div>
                            <div class="flex">
                                <button type="button"
                                    class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 transition duration-300 ease-in-out mt-2 mb-2"
                                    id="ModalEditar">Editar</button>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Calendar -->
                <div class="col-lg-8 col-md-6 mb-4">
                    <div id="calendar"></div>
                </div>
            </div>

            <!-- Detalles -->

        </div>





        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"
            integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous">
        </script>
        <script>
            $(document).ready(function() {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                var evento = @json($event);
                $('#calendar').fullCalendar({


                    header: {
                        left: 'prev, next today',
                        center: 'title',
                        right: 'month, agendaWeek, agendaDay'
                    },
                    locale: 'es',
                    monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto',
                        'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
                    ],
                    monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct',
                        'Nov', 'Dic'
                    ],
                    dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
                    dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'],
                    buttonText: {
                        today: 'Hoy',
                        month: 'Mes',
                        week: 'Semana',
                        day: 'Día',
                        list: 'list'
                    },

                    events: evento,
                    selectable: true,
                    selecetHelper: true,
                    eventTextColor: '#ffffff',

                    select: function(start, end, allDays, event, view) {
                        $('#eventoModal').modal('show');
                        $('#start_date').val(moment(start).format('YYYY-MM-DD'));
                        $('#end_date').val(moment(end).format('YYYY-MM-DD'));

                        $('#saveBtn').unbind().click(function() {
                            var title = $('#title').val();
                            var start_date = $('#start_date').val();
                            var end_date = $('#end_date').val();
                            var cliente_id = $('#cliente_select').val();
                            var hotel_nombre = $('#hotel_nombre').val();

                            // Obtener los valores de los campos
                            var title = $('#title').val();
                            var start_date = $('#start_date').val();
                            var end_date = $('#end_date').val();
                            var cliente_id = $('#cliente_select').val();
                            var hotel_nombre = $('#hotel_nombre').val();

                            // Establece la hora fija para guardar en la base de datos
                            var hora_inicio = '12:00:00';
                            var hora_fin = '13:00:00'; // Cambia esta hora según tus necesidades

                            // Concatena la hora fija con la fecha de inicio
                            start_date = start_date + ' ' + hora_inicio;
                            end_date = end_date + ' ' + hora_fin;

                            // Verificar si algún campo está vacío
                            if (!title || !start_date || !end_date || !cliente_id || !hotel_nombre) {
                                // Mostrar mensaje de error
                                swal("Error", "Todos los campos son obligatorios", "error");
                                return;
                            }
                            // Realizar la solicitud AJAX solo si todos los campos están llenos
                            $.ajax({
                                url: "{{ route('calendar.store') }}",
                                type: "POST",
                                dataType: 'json',
                                data: {
                                    title: title,
                                    start_date: start_date,
                                    end_date: end_date,
                                    cliente_id: cliente_id,
                                    hotel_nombre: hotel_nombre,
                                    user_id: {{ auth()->id() }}
                                },
                                success: function(response) {
                                    $('#eventoModal').modal('hide')

                                    $('#calendar').fullCalendar('renderEvent', {
                                        'title': response.title,
                                        'cliente_select': response.cliente_nombres + ' ' + response.cliente_apellidos,
                                        'hotel_nombre': response.hotel_nombre,
                                        'start': response.start_date,
                                        'end': response.end_date,

                                    });

                                    swal("¡Éxito!", "¡Evento Guardado Correctamente!",
                                            "success")
                                        .then(function() {
                                            location
                                                .reload(); // Recargar la página después del mensaje de éxito
                                        });
                                },
                                error: function(error) {
                                    if (error.responseJSON.errors) {
                                        $('#titleError').html(error.responseJSON.errors
                                            .title)
                                    } else {
                                        swal("Error",
                                            "Hubo un gran error al guardar el evento",
                                            "error");
                                    }
                                },
                            });



                        });
                    },

                    eventClick: function(event) {

                        var id = event.id;
                        var tituloSeleccionado = event.title;
                        var clienteSeleccionado = event.cliente_nombres + ' ' + event.cliente_apellidos;
                        var cliente_idSeleccionado = event.cliente_id;
                        var fechaInicioSeleccionado = moment(event.start).format('dddd, D [de] MMMM [de] YYYY');
                        var fechaFinSeleccionado = moment(event.end).format('dddd, D [de] MMMM [de] YYYY');
                        var hotel_nombreSeleccionado = event.hotel_nombre;

                        var formatoFechaInicio = moment(event.start).format('YYYY-MM-DD');
                        var formatoFechaFin = moment(event.end).format('YYYY-MM-DD');

                        //Mostrar los detalles del evento
                        document.getElementById("tituloSpan").innerText = tituloSeleccionado;
                        document.getElementById("clienteSpan").innerText = clienteSeleccionado;
                        document.getElementById("fechaInicioSpan").innerText = fechaInicioSeleccionado;
                        document.getElementById("fechaFinSpan").innerText = fechaFinSeleccionado;
                        document.getElementById("hotel_nombreSpan").innerText = hotel_nombreSeleccionado;

                        // Mostrar los valores en el modal para editar
                        console.log("Valor del título seleccionado:", tituloSeleccionado);
                        document.getElementById("title_edit").value = tituloSeleccionado;
                        document.getElementById("cliente_edit").value = cliente_idSeleccionado;
                        document.getElementById("start_date_edit").value = formatoFechaInicio;
                        document.getElementById("end_date_edit").value = formatoFechaFin;
                        document.getElementById("hotel_nombre_edit").value = hotel_nombreSeleccionado;
                        //Metodo para que se muetre el evento en la parte de detalle
                        $('#event-details').show();
                        //Metodo para cerrar el evento
                        $('#closedetails').click(function() {
                            $('#event-details').hide();
                        });


                        $('#updateBtn').unbind().click(function() {
                            var id = event.id;
                            var start_date = $('#start_date_edit').val();
                            var end_date = $('#end_date_edit').val();
                            var title = $('#title_edit').val();
                            var cliente_id = $('#cliente_edit').val();
                            var hotel_nombre = $('#hotel_nombre_edit').val();

                            $.ajax({
                                url: "{{ route('calendar.update', '') }}" + '/' + id,
                                type: "PATCH",
                                dataType: 'json',
                                data: {
                                    start_date: start_date,
                                    end_date: end_date,
                                    title: title,
                                    cliente_id: cliente_id,
                                    hotel_nombre: hotel_nombre
                                },
                                success: function(response) {
                                    swal("¡Exito!", "¡Evento Actualizado!", "success")
                                        .then(() => {
                                            $('#editarModal').modal(
                                                'hide'
                                            ); // Ocultar el modal después del mensaje de éxito
                                            location
                                                .reload(); // Recargar la página para reflejar los cambios
                                        }); // Mostrando una alerta de éxito
                                },
                                error: function(error) {
                                    swal("¡Error!",
                                        "Hubo un error al actualizar el evento.",
                                        "error"); // Mostrando una alerta de error
                                }
                            });
                        });

                        $('#deleteBtn').unbind().click(function() {
                            var id = event.id;
                            swal({
                                    title: "¿Estás seguro?",
                                    text: "¡No podrás recuperar este evento una vez eliminado!",
                                    icon: "warning",
                                    buttons: ["Cancelar", "Sí, eliminarlo"],
                                    dangerMode: true,
                                })
                                .then((willDelete) => {
                                    if (willDelete) {
                                        $.ajax({
                                            url: "{{ route('calendar.destroy', '') }}" +
                                                '/' + id,
                                            type: "DELETE",
                                            dataType: 'json',
                                            success: function(response) {
                                                swal("¡Bien hecho!",
                                                    "¡Evento Eliminado!",
                                                    "success").then(() => {
                                                    $('#editarModal').modal(
                                                        'hide');
                                                    location.reload();
                                                });
                                            },
                                            error: function(error) {
                                                console.log(error);
                                                swal("¡Error!",
                                                    "Hubo un error al eliminar el evento.",
                                                    "error");
                                            }
                                        });
                                    } else {
                                        swal("Operación cancelada", {
                                            icon: "info",
                                        });
                                    }
                                });
                        });
                    },
                    selectAllow: function(event) {
                        return moment(event.start).utcOffset(false).isSame(moment(event.end).subtract(1,
                            'second').utcOffset(false), 'day');
                    },

                    eventRender: function(event, element) {
                        var colorMapping = {
                            'Prereservado': '#00227f',
                            'Reservado': '#FF0000',
                            'Disponible': '#0C7E21'
                        };
                        if (colorMapping[event.title]) {
                            // Aplica el color correspondiente al evento
                            element.css('background-color', colorMapping[event.title]);
                        }
                        var content = '';
                        if (event.title === 'Prereservado' || event.title === 'Reservado') {
                            content = '<div class="event-title">' + event.title + ', ' + event
                                .hotel_nombre + ', ' + event.cliente_cedula + '</div>';
                        } else {
                            content = '<div class="event-title">' + event.title + '</div>';
                        }
                        // Agregar el contenido al elemento del evento
                        element.find('.fc-content').html(content);

                    },

                });


                $("#eventoModal").on("hidden.bs.modal", function() {
                    $("#saveBtn").unbind();
                });


            });

            $(document).ready(function() {
                var modalEditarBtn = document.getElementById("ModalEditar");
                var editarModal = document.getElementById("editarModal");
                // Asignar un evento clic al botón para abrir la ventana modal

                modalEditarBtn.onclick = function(event) {
                    // Mostrar la ventana modal
                    editarModal.classList.add("show");
                    editarModal.style.display = "block";

                    $('#editarModal').modal('show');
                    $("#editarModal").on("hidden.bs.modal", function() {
                        $("#updateBtn").unbind();
                        $("#deleteBtn").unbind();
                    });
                }

            });
        </script>
    </body>
</x-app-layout>
@include('layouts.footer')
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
