<x-app-layout>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Full Calendar js</title>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css' rel='stylesheet'>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>


    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Calendar') }}
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
#event-details {
    background-color: #cecece;
    padding: 20px;
    border-radius: 10px;
    animation: fadeIn 0.5s ease-in-out;
}

/* Animación para Event Details */
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

/* Estilos para el calendario */
#calendar {
    background-color:#bcbcbc;
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

/* Estilos para los botones del calendario */
.fc-button {

    color: #000000;
    border-color: #000000; /* Red */
    border-radius: 5px;
    padding: 10px 15px;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}


/* Estilos para los eventos agregados */
.fc-event {
    background-color: #4dff40; /* Orange */
    border-color: #40ff69; /* Orange */
    border-radius: 5px;
    padding: 5px;
    font-size: 14px;
    cursor: pointer;
}

/* Estilos para eventos del mismo día */
.fc-day-grid-event {
    background-color: #00227f; /* Blue */
    border-color: #081844; /* Blue */
    border-radius: 5px;
    padding: 5px;
    font-size: 14px;
    cursor: pointer;
}

    </style>
    <body>
        <!-- Button trigger modal -->


        <!-- Modal -->
        <div class="modal fade" id="eventoModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Nuevo Evento</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <label for="estado">Estado</label>
                        <select class="form-select" id="title">
                            <option value="Prereservado">Pre-reservado</option>
                            <option value="Reservado">Reservado</option>
                            <option value="Disponible">Disponible</option>
                        </select>
                        <label for="">Titular</label>
                        <input type="text" class="form-control" id="author">
                        <label for="">Descripcion</label>
                        <input type="text" class="form-control" id="note">
                        <label for="">Fecha Inicio</label>
                        <input type="date" class="form-control" id="start_date">
                        <label for="">Fecha salida</label>
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


        <!-- editar Modal -->
        <div class="modal fade" id="editarModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Editar Evento</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <label for="estado">Estado</label>
                        <select class="form-select" id="title_edit">
                            <option value="Prereservado">Pre-reservado</option>
                            <option value="Reservado">Reservado</option>
                            <option value="Disponible">Disponible</option>
                        </select>
                        <label for="text">Autor</label>
                        <input type="text" class="form-control" id="author_edit">
                        <label for="">Descripcion</label>
                        <input type="text" class="form-control" id="note_edit">
                        <label for="">Fecha Inicio</label>
                        <input type="date" class="form-control" id="start_date_edit">
                        <label for="">Fecha salida</label>
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

        <div class="flex justify-between">
            <!-- Event Details -->
            <div class="w-1/4 p-4">
                <div id="event-details">
                    <h3 class="text-lg font-bold mb-2">Detalles del Evento</h3>
                    <hr class="mb-2">
                    <div class="mb-1">
                        <strong>Título:</strong> <span id="tituloSpan"></span>
                    </div>
                    <div class="mb-1">
                        <strong>Autor:</strong> <span id="autorSpan"></span>
                    </div>
                    <div class="mb-1">
                        <strong>Fecha de Inicio:</strong> <span id="fechaInicioSpan"></span>
                    </div>
                    <div class="mb-1">
                        <strong>Fecha de Fin:</strong> <span id="fechaFinSpan"></span>
                    </div>
                    <div class="mb-1">
                        <strong>Nota:</strong> <span id="notaSpan"></span>
                    </div>
                </div>
            </div>

            <!-- Calendar -->
            <div class="w-3/4 p-4">
                <div id="calendar"></div>
            </div>
        </div>



        @include('layouts.footer')

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
                    select: function(start, end, allDays) {
                        $('#eventoModal').modal('show');
                        $('#start_date').val(moment(start).format('YYYY-MM-DD'));
                        $('#end_date').val(moment(end).format('YYYY-MM-DD'));

                        $('#saveBtn').unbind().click(function() {
                            var title = $('#title').val();
                            console.log("El valor de end es: "+ end);
                            var start_date = $('#start_date').val();
                            var end_date = $('#end_date').val();
                            console.log("El valor de end_date es: " + end_date);
                            var author = $('#author').val();
                            var note = $('#note').val();


                            $.ajax({
                                url: "{{ route('calendar.store') }}",
                                type: "POST",
                                dataType: 'json',
                                data: {
                                    title: title,
                                    start_date: start_date,
                                    end_date: end_date,
                                    author: author,
                                    note: note,
                                    user_id: {{ auth()->id() }}
                                },
                                success: function(response) {
                                    $('#eventoModal').modal('hide')
                                    $('#calendar').fullCalendar('renderEvent', {
                                        'title': response.title,
                                        'author': response.author,
                                        'note': response.note,
                                        'start': response.start_date,
                                        'end': response.end_date,
                                   });

                                   swal("¡Éxito!", "¡Evento Guardado Correctamente!", "success")
                                        .then(function() {
                                            location.reload(); // Recargar la página después del mensaje de éxito
                                        });
                                },
                                error: function(error) {
                                    if (error.responseJSON.errors) {
                                        $('#titleError').html(error.responseJSON.errors.title)
                                    }
                                },

                            });

                        });
                    },

                    eventClick: function(event) {
                        $('#editarModal').modal('show');

                        var id = event.id;
                        var tituloSeleccionado = event.title;
                        var autorSeleccionado = event.author;
                        var fechaInicioSeleccionado = event.start;
                        var fechaFinSeleccionado = event.end;
                        var notaSeleccionado = event.note;
                        // Convertir las fechas a un formato legible
                        // var fechaInicioSeleccionado = new Date(fechaInicioSeleccionado);
                        // var formatoFechaInicio = fechaInicioSeleccionado.toISOString().split('T')[0];
                        // var fechaFinSeleccionado = new Date(fechaFinSeleccionado);
                        // var formatoFechaFin = fechaFinSeleccionado.toISOString().split('T')[0];
                        var formatoFechaInicio = moment(fechaInicioSeleccionado).format('YYYY-MM-DD');
                        var formatoFechaFin = moment(fechaFinSeleccionado).format('YYYY-MM-DD');
                        //Mostrar los detalles del evento en el modal
                        document.getElementById("tituloSpan").innerText = tituloSeleccionado;
                        document.getElementById("autorSpan").innerText = autorSeleccionado;
                        document.getElementById("fechaInicioSpan").innerText = fechaInicioSeleccionado;
                        document.getElementById("fechaFinSpan").innerText = fechaFinSeleccionado;
                        document.getElementById("notaSpan").innerText = notaSeleccionado;

                        // Establecer los valores en el formulario de edición
                        console.log("Valor del título seleccionado:", tituloSeleccionado);
                        document.getElementById("title_edit").value = tituloSeleccionado;
                        document.getElementById("author_edit").value = autorSeleccionado;
                        document.getElementById("start_date_edit").value = formatoFechaInicio;
                        document.getElementById("end_date_edit").value = formatoFechaFin;
                        document.getElementById("note_edit").value = notaSeleccionado;

                        // Configurar la función de clic para el botón de actualización
                        $('#updateBtn').unbind().click(function() {
                            console.log('Editado Correctamente');
                            var id = event.id;
                            var start_date = $('#start_date_edit').val();
                            var end_date = $('#end_date_edit').val();
                            var title = $('#title_edit').val();
                            var author = $('#author_edit').val();
                            var note = $('#note_edit').val();

                            $.ajax({
                                url: "{{ route('calendar.update', '') }}" + '/' + id,
                                type: "PATCH",
                                dataType: 'json',
                                data: {
                                    start_date: start_date,
                                    end_date: end_date,
                                    title: title,
                                    author: author,
                                    note: note
                                },
                                success: function(response) {
                                    swal("¡Exito!", "¡Evento Actualizado!", "success").then(() => {
                                        $('#editarModal').modal('hide'); // Ocultar el modal después del mensaje de éxito
                                        location.reload(); // Recargar la página para reflejar los cambios
                                    });// Mostrando una alerta de éxito
                                 },
                                error: function(error) {
                                    console.log(error);
                                    swal("¡Error!", "Hubo un error al actualizar el evento.", "error"); // Mostrando una alerta de error
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
                                        url: "{{ route('calendar.destroy', '') }}" + '/' + id,
                                        type: "DELETE",
                                        dataType: 'json',
                                        success: function(response) {
                                            swal("¡Bien hecho!", "¡Evento Eliminado!", "success").then(() => {
                                                $('#editarModal').modal('hide');
                                                location.reload();
                                            });
                                        },
                                        error: function(error) {
                                            console.log(error);
                                            swal("¡Error!", "Hubo un error al eliminar el evento.", "error");
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
                        return moment(event.start).utcOffset(false).isSame(moment(event.end).subtract(1,'second').utcOffset(false), 'day');
                    },


                });



                $("#eventoModal").on("hidden.bs.modal", function() {
                    $("#saveBtn").unbind();
                });
                $("#editarModal").on("hidden.bs.modal", function() {
                    $("#updateBtn").unbind();
                });
                $("#editarModal").on("hidden.bs.modal", function() {
                    $("#deleteBtn").unbind();
                });

            });
        </script>
    </body>
</x-app-layout>
