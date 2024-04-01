<x-app-layout>
    <x-slot name="header">
        <div x-data="{ showModal: false }" x-cloak class="flex items-center gap-5">
            @role('superAdmin')
            <x-nav-link  :href="route('roles.rol')" 
            class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight no-underline">
                {{ __('Asignar roles') }}
            </x-nav-link>
            {{-- logs --}}
            <x-nav-link href="{{ route('logs.log') }}" class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight no-underline">
                {{ __('Logs') }}
            </x-nav-link>
            @endrole
            <x-nav-link href="{{ route('vendedores.pagosPendientes') }}" class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight no-underline">
                {{ __('Pagos pendientes') }}
            </x-nav-link>
        </div>
    </x-slot>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <div class="py-2">
        <div class="max-w mx-auto px-2 lg:px-20 mb-4">
            <div class="bg-white dark:bg-gray-900 bg-opacity-50 shadow-lg rounded-lg ">
                <div class="p-6 text-gray-900 dark:text-gray-100 overflow-auto">
                    <div id="users">
                        <input class="search mb-4" placeholder="Buscar" />
                        <table class="w-100 bg-[#f8fafc] dark:bg-gray-800 border border-[#0a0a0a] "
                            style="overflow-x: auto;">
                            <thead>
                                <tr>
                                    <th class="py-2 px-4 border-b text-center whitespace-nowrap">Nombre de usuario</th>
                                    <th class="py-2 px-4 border-b text-center whitespace-nowrap">Acción</th>
                                    <th class="py-2 px-4 border-b text-center whitespace-nowrap">Tabla modifcada</th>
                                    <th class="py-2 px-4 border-b text-center whitespace-nowrap">Modificacion</th>
                                    <th class="py-2 px-4 border-b text-center whitespace-nowrap">Datos modificados</th>
                                    <th class="py-2 px-4 border-b text-center whitespace-nowrap">Fecha de accion</th>
                                </tr>
                            </thead>
                            <tbody class="list">
                                @foreach ($datos as $dato)
                                    <tr>
                                        <td class="py-2 px-4 border-b text-center whitespace-nowrap nombres">
                                            {{ $dato->user->name }}</td>
                                        <td class="py-2 px-4 border-b text-center whitespace-nowrap accion ">
                                            {{ $dato->action }}</td>
                                        <td class="py-2 px-4 border-b text-center whitespace-nowrap tabla_modificada">
                                            {{ $dato->entity_type }}</td>
                                        <td class="py-2 px-4 border-b text-center whitespace-nowrap">
                                            <!-- Button trigger modal -->
                                            <button type="button" data-bs-toggle="modal"
                                                data-bs-target="#exampleModal{{ $dato->id }}">
                                                {{ $dato->entity_id }}
                                            </button>

                                            <!-- Modal -->
                                            <div class="modal fade" id="exampleModal{{ $dato->id }}" tabindex="-1"
                                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Información
                                                            </h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>

                                                        <div class="modal-body">
                                                            @if ($dato->entity_type === 'cliente')
                                                                @foreach ($clientes as $cliente)
                                                                    @if ($cliente->id == $dato->entity_id)
                                                                        <p><strong>cedula :</strong>
                                                                            <span>{{ $cliente->cedula }}</span>
                                                                        </p>
                                                                        <p><strong>Nombres:
                                                                            </strong><span>{{ $cliente->nombres }}</span>
                                                                        </p>
                                                                        <p><strong>Apellidos :</strong>
                                                                            <span>{{ $cliente->apellidos }}</span>
                                                                        </p>
                                                                        <p><strong>Email :</strong>
                                                                            <span>{{ $cliente->email }}</span>
                                                                        </p>
                                                                    @endif
                                                                @endforeach
                                                            @elseif ($dato->entity_type === 'vendedor')
                                                                @foreach ($vendedor as $vendedores)
                                                                    @if ($vendedores->id == $dato->entity_id)
                                                                        <p><strong>Nombres :</strong>
                                                                            <span>{{ $vendedores->nombres }}</span>
                                                                        </p>
                                                                        <p><strong>Rol:
                                                                            </strong><span>{{ $vendedores->rol }}</span>
                                                                        </p>
                                                                        <p><strong>Porcentaje de ventas :</strong>
                                                                            <span>{{ $vendedores->porcentaje_ventas }}</span>
                                                                        </p>
                                                                    @endif
                                                                @endforeach
                                                            @elseif ($dato->entity_type === 'user')
                                                                @foreach ($user as $usuario)
                                                                    @if ($usuario->id == $dato->entity_id)
                                                                        <p><strong>Nombres :</strong>
                                                                            <span>{{ $usuario->name }}</span>
                                                                        </p>
                                                                        <p><strong>Email:
                                                                            </strong><span>{{ $usuario->email }}</span>
                                                                        </p>
                                                                        <p><strong>Sala:
                                                                        </strong><span>{{ $usuario->sala }}</span>
                                                                    </p>
                                                                        <p><strong>Rol:</strong>
                                                                            @foreach ($usuario->roles as $role)
                                                                                {{ $role->name }}
                                                                            @endforeach
                                                                        </p>
                                                                    @endif
                                                                @endforeach
                                                            @elseif ($dato->entity_type === 'contrato')
                                                                @foreach ($contrato as $contratos)
                                                                    @if ($contratos->id == $dato->entity_id)
                                                                        <p><strong>Contrato ID :</strong>
                                                                            <span>{{ $contratos->contrato_id }}</span>
                                                                        </p>
                                                                        <p><strong>Ubicacion de sala:
                                                                            </strong><span>{{ $contratos->ubicacion_sala }}</span>
                                                                        </p>
                                                                        <p><strong>Años contrato:
                                                                            </strong><span>{{ $contratos->anios_contrato }}</span>
                                                                        </p>
                                                                        <p><strong>Monto:
                                                                            </strong><span>{{ $contratos->monto_contrato }}</span>
                                                                        </p>
                                                                        <p><strong>Cliente de contrato:
                                                                            </strong><span>{{ $contratos->cliente->nombres }}</span>
                                                                            <span>{{ $contratos->cliente->apellidos }}</span>
                                                                        </p>
                                                                    @endif
                                                                @endforeach
                                                            @elseif ($dato->entity_type === 'paquete')
                                                                @foreach ($paquete as $paquetes)
                                                                    @if ($paquetes->id == $dato->entity_id)
                                                                        <p><strong>Nombre del paquete:
                                                                            </strong><span>{{ $paquetes->nombre_paquete }}</span>
                                                                        </p>
                                                                        <p><strong>Mensaje :</strong>
                                                                            <span>{{ $paquetes->message }}</span>
                                                                        </p>
                                                                        <p><strong>Numero de días:
                                                                            </strong><span>{{ $paquetes->num_dias }}</span>
                                                                        </p>
                                                                        <p><strong>Numero de noches:
                                                                            </strong><span>{{ $paquetes->num_noches }}</span>
                                                                        </p>
                                                                        <p><strong>Precio de afiliado:
                                                                            </strong><span>{{ $paquetes->precio_afiliado }}</span>
                                                                        </p>
                                                                        <p><strong>Precio de no afiliado:
                                                                            </strong><span>{{ $paquetes->precio_no_afiliado }}</span>
                                                                        </p>
                                                                    @endif
                                                                @endforeach
                                                            @elseif ($dato->entity_type === 'hotel')
                                                                @foreach ($hotel as $hoteles)
                                                                    @if ($hoteles->id == $dato->entity_id)
                                                                        <p><strong>Nombre del hotel:
                                                                            </strong><span>{{ $hoteles->hotel_nombre }}</span>
                                                                        </p>
                                                                        <p><strong>País :</strong>
                                                                            <span>{{ $hoteles->pais }}</span>
                                                                        </p>
                                                                        <p><strong>Provincia:
                                                                            </strong><span>{{ $hoteles->provincia }}</span>
                                                                        </p>
                                                                        <p><strong>Ciudad:
                                                                            </strong><span>{{ $hoteles->ciudad }}</span>
                                                                        </p>
                                                                        <p><strong>Precio:
                                                                            </strong><span>{{ $hoteles->precio }}</span>
                                                                        </p>
                                                                    @endif
                                                                @endforeach
                                                                @elseif ($dato->entity_type === 'evento')
                                                                @foreach ($evento as $eventos)
                                                                    @if ($eventos->id == $dato->entity_id)
                                                                        <p><strong>Titulo:
                                                                            </strong><span>{{ $eventos->title }}</span>
                                                                        </p>
                                                                        <p><strong>Fecha de inicio :</strong>
                                                                            <span>{{ $eventos->start_date }}</span>
                                                                        </p>
                                                                        <p><strong>Fecha de finalización:
                                                                            </strong><span>{{ $eventos->end_date }}</span>
                                                                        </p>
                                                                        <p><strong>Autor:
                                                                            </strong><span>{{ $eventos->author }}</span>
                                                                        </p>
                                                                       
                                                                    @endif
                                                                @endforeach
                                                            @endif
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Cerrar</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-2 border-b text-lef">
                                            <span style="font-size: smaller; line-height: 1;">
                                                @php
                                                    $modifiedData = json_decode($dato->modified_data, true);
                                                @endphp
                                                @if (empty($modifiedData))
                                                    <p>No hay datos modificados</p>
                                                @elseif (is_array($modifiedData) && count($modifiedData) > 0)
                                                    @foreach ($modifiedData as $key => $value)
                                                        @if ($key !== 'updated_at')
                                                            <p><strong>{{ ucfirst($key) }}:</strong>
                                                                @if (is_array($value))
                                                                    <!-- Si $value es un array, puedes imprimirlo como cadena usando la función json_encode() -->
                                                                    {{ json_encode($key) }}
                                                                @else
                                                                    <!-- Si $value no es un array, puedes imprimirlo directamente -->
                                                                    {{ htmlspecialchars($value) }}
                                                                @endif
                                                            </p>
                                                        @endif
                                                    @endforeach
                                            
                                                   
                                                @endif
                                            </span>
                                            
                                        </td>

                                        <td class="py-2 px-4 border-b text-center whitespace-nowrap fecha">
                                            {{ $dato->created_at }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="pagination flex justify-center mt-2">

                            <div class="pagination "></div>
                        </div>


                    </div>

                </div>
            </div>
        </div>
    </div>
    <style>
        .pagination li {
            display: inline-block;
            padding: 5px;
        }

        .pagination a {
            display: inline-block;
            padding: 10px 15px;
            /* Ajusta el relleno según sea necesario */
            background-color: #4a4a4a;
            /* Color de fondo del botón */
            color: #fff;
            /* Color del texto del botón */
            border-radius: 5px;
            /* Bordes redondeados */
            text-decoration: none;
            /* Eliminar subrayado del enlace */
            transition: background-color 0.3s ease;
            /* Efecto de transición al pasar el ratón */
        }

        .pagination a:hover {
            background-color: #555;
            /* Cambia el color de fondo al pasar el ratón */
        }
    </style>
</x-app-layout>
<script src="//cdnjs.cloudflare.com/ajax/libs/list.js/1.5.0/list.min.js"></script>
|
<script>
    // buscador
    var options = {
        valueNames: ['nombres', 'accion', 'fecha', 'tabla_modificada'],
        page: 10, // Número de elementos por página
        pagination: true // Habilitar paginación
    };

    var userList = new List('users', options);
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
</script>
@include('layouts.footer')
