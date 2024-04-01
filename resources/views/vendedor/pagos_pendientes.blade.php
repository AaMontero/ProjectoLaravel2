<x-app-layout>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css' rel='stylesheet'>
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

    <script>
        function pagarUsuarios(usuarioId) {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', '/pagoVendedores/' + usuarioId, true); // Ruta del endpoint
            xhr.setRequestHeader('Content-Type', 'application/json');

            xhr.onload = function() {
                if (xhr.status >= 200 && xhr.status < 300) {
                    alert("Se ha registrado el pago");
                    window.location.reload();
                } else {
                    console.error('Error al marcar los pagos como hechos: ' + xhr.status);
                }
            };

            xhr.onerror = function() {
                console.error('Error de red al marcar como pagos');
            };

            xhr.send();
        }
    </script>
    <div class="py-2 ">
        <div class="max-w mx-auto px-2 lg:px-20 mb-4">
            <h2 class = "ml-8">Pagos Pendientes</h2>
            @foreach ($pagosPendientesPorVendedor as $vendedorId => $pagosPendientes)
                <div class="bg-white dark:bg-gray-900 bg-opacity-50 shadow-lg rounded-lg ">
                    <div class="p-6 text-gray-900 dark:text-gray-100 ">
                        <div class = "flex justify-between">
                            <h3 class="font-bold">Vendedor:
                                {{ $vendedores->first(function ($vendedor) use ($vendedorId) {
                                    return $vendedor->id == $vendedorId;
                                })->nombres }}
                            </h3>
                            <x-primary-button onclick="pagarUsuarios('{{ $vendedorId }}')"
                                class="mt-4">Pagar</x-primary-button>

                        </div>

                        <table class="w-100 bg-white dark:bg-gray-800 border border-gray-300">
                            <thead>
                                <tr> <!--Etiquetas de la tabla de clientes-->
                                    <th class="py-2 px-4 border-b text-center ">Valor de Pago</th>
                                    <th class="py-2 px-4 border-b text-center ">Fecha</th>
                                    <th class="py-2 px-4 border-b text-center ">Concepto</th>
                                    <th class="py-2 px-4 border-b text-center ">ID del vendedor</th>
                                    <th class="py-2 px-4 border-b text-center ">Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pagosPendientes as $pago)
                                    <tr> <!--Tabla que muestra los clientes-->
                                        <td class="py-2 px-4 border-b text-center">{{ $pago->valor_pago }}</td>
                                        <td class="py-2 px-4 border-b text-center">{{ $pago->fecha_pago }}</td>
                                        <td class="py-2 px-4 border-b text-center">{{ $pago->concepto }}</td>
                                        <td class="py-2 px-4 border-b text-center">{{ $pago->vendedor_id }}</td>
                                        <td class = "text-right pr-6">
                                            <x-dropdown class="origin-top absolute ">
                                                <x-slot name="trigger">
                                                    <button>
                                                        <svg class="ml-5 w-5 h-5 text-gray-400 dark:text-gray-200"
                                                            xmlns="http://www.w3.org/2000/svg" fill="none"
                                                            viewBox="0 0 24 24" stroke-width="1.5"
                                                            stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                                                        </svg>
                                                    </button>
                                                </x-slot>
                                                <x-slot name="content">
                                                    <?php
                                                    
                                                    ?>
                                                    <x-dropdown-link :href="route('pagoVendedor.edit', $pago)">
                                                        {{ __('Editar Pago') }}
                                                    </x-dropdown-link>
                                                    <x-dropdown-link :href="route('pagoVendedor.pagar', $pago)">
                                                        {{ __('Pago Realizado') }}
                                                    </x-dropdown-link>

                                                </x-slot>
                                            </x-dropdown>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="py-2 ">
        <div class="max-w mx-auto px-2 lg:px-20 mb-4">
            <h2 class = "ml-8">Historial de pagos realizados</h2>
            <div class="bg-white dark:bg-gray-900 bg-opacity-50 shadow-lg rounded-lg ">
                <div class="p-6 text-gray-900 dark:text-gray-100 ">
                    <table class="w-100 bg-white dark:bg-gray-800 border border-gray-300">
                        <thead>
                            <tr> <!--Etiquetas de la tabla de clientes-->
                                <th class="py-2 px-4 border-b text-center ">Valor de Pago</th>
                                <th class="py-2 px-4 border-b text-center ">Fecha</th>
                                <th class="py-2 px-4 border-b text-center ">Concepto</th>
                                <th class="py-2 px-4 border-b text-center ">ID del vendedor</th>
                                <th class="py-2 px-4 border-b text-center ">Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pagosEfectivos as $pago)
                                <tr> <!--Tabla que muestra los clientes-->
                                    <td class="py-2 px-4 border-b text-center">{{ $pago->valor_pago }}</td>
                                    <td class="py-2 px-4 border-b text-center">{{ $pago->fecha_pago }}</td>
                                    <td class="py-2 px-4 border-b text-center">{{ $pago->concepto }}</td>
                                    <td class="py-2 px-4 border-b text-center">{{ $pago->vendedor_id }}</td>
                                    <td class = "text-right pr-6">
                                        <x-dropdown class="origin-top absolute ">
                                            <x-slot name="trigger">
                                                <button>
                                                    <svg class="ml-5 w-5 h-5 text-gray-400 dark:text-gray-200"
                                                        xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                                                    </svg>
                                                </button>
                                            </x-slot>
                                            <x-slot name="content">
                                                <?php
                                                ?>
                                                <x-dropdown-link :href="route('pagoVendedor.revertirPago', $pago)">
                                                    {{ __('Deshacer Pago') }}
                                                </x-dropdown-link>


                                            </x-slot>
                                        </x-dropdown>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class = "ml-20 mr-20">
                        <p class="ml-5 flex justify-center items-center list-none space-x-2">
                            {{ $pagosEfectivos->appends([]) }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
