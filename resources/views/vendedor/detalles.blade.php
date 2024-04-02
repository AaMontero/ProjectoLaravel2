<x-app-layout>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css' rel='stylesheet'>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Historial Vendedor') }}
            </h2>
        </div>
    </x-slot>
    <div class = "border-4 mx-16 mt-4">
        <div class="pt-2 pb-2 ">
            <div class="bg-gray-100 pl-4 pt-4 rounded-md grid grid-cols-2 gap-4">
                <div class = "pl-12">
                    <p class="text-gray-700">
                        <span class="text-lg font-bold mb-2">Nombres del Vendedor:</span>
                        {{ $vendedor->nombres }}
                    </p>
                    <p class="text-gray-700">
                        <span class="text-lg font-bold mb-2">Rol Actual:</span>
                        {{ $vendedor->rol }}
                    </p>
                </div>
                <div class = "pl-12">
                    <p class="text-gray-700">
                        <span class="text-lg font-bold mb-2">Saldo Pendiente:</span>
                        ${{ $pagosPendientes }}
                    </p>

                </div>
            </div>
        </div>
    </div>

    <div class="bg-gray-100 py-8 px-32">
        <div class="bg-white rounded-md shadow-md overflow-hidden">
            <div class="bg-blue-500 text-white py-2 px-6">
                <h2 class="text-2xl font-bold text-center">Historial de Pagos</h2>
            </div>

            <div class="py-2">
                @foreach ($pagosXmeses as $mes => $pagosPorMes)
                    <h2 class="text-lg text-center font-semibold mt-8">
                        {{ $mesesanio[intval(explode('-', $mes)[1])] }} - {{ explode('-', $mes)[0] }} </h2>

                    @foreach ($pagosPorMes as $pago)
                        <div class="px-4">
                            <div class="flex items-center justify-between border-y-2 mt-1">
                                <div class = "">
                                    <label class="text-xl font-semibold">{{ $pago->concepto }}</label>
                                    <p class="text-sm pl-4 text-gray-500">
                                        <span class="font-bold">Estado: </span>
                                        {{ $pago->estado }}
                                    </p>
                                </div>
                                <div>
                                    <p
                                        class="text-lg text-right font-bold 
                                    @if ($pago->estado == 'Pendiente') text-gray-500 @endif
                                    @if ($pago->estado == 'Pago') text-green-500 @endif
                                    @if ($pago->estado == 'Cancelado') text-red-500 @endif">
                                        ${{ $pago->valor_pago }}
                                    </p>
                                    <label class="text-sm text-gray-500">
                                        <span class="font-bold">Fecha:</span>
                                        {{ $pago->fecha_pago }}
                                    </label>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endforeach
            </div>

            <!-- Agrega más divs como este para cada pago -->
        </div>
    </div>


    </div>
</x-app-layout>
