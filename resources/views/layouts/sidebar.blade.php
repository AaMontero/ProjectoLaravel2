<!-- component -->
<link rel="stylesheet" href="https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css" />
<link href='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css' rel='stylesheet'>
<div class="min-h-screen flex flex-row bg-gray-100">
  <div class="flex flex-col w-56 bg-white rounded-r-3xl overflow-hidden">
    <div class="flex items-center justify-center h-20 shadow-md">
      <h1 class="text-3xl uppercase text-indigo-500">AITRIV</h1>
    </div>
    <div x-data="{ open1: false }">
      <ul class="flex flex-col pt-3 cursor-pointer m-0">
          <h6 @click="open1 = !open1" class="cursor-pointer m-0">Financiero</h6>
          <template x-if="open1">
            <div  x-if="open">
              <li x-data="{ open: false }">
                <a @click="open = !open" class="no-underline flex flex-row items-center h-8 transform hover:translate-x-2 transition-transform ease-in duration-200 text-gray-500 hover:text-gray-800">
                  <span class="inline-flex items-center justify-center h-12 w-12 text-lg text-gray-400"><i class="bx bx-chat"></i></span>
                  <span class="text-sm font-medium">Ganancias</span>
                </a>
                <div x-show="open" class="pl-4 py-1">
                  <!-- Elementos -->
                  <ul class="cursor-pointer">
                       <li><a class="text-gray-500 transform hover:translate-x-2 transition-transform ease-in duration-200
                        hover:text-gray-800 no-underline text-sm" href="/dashboard">-Pagos</a></li>
                        <li><a class="text-gray-500 transform hover:translate-x-2 transition-transform ease-in duration-200
                          hover:text-gray-800 no-underline text-sm" href="/dashboard">-Comisiones</a></li>
                  </ul>
                </div>
              </li>

              <li x-data="{ open: false }">
                <a @click="open = !open" class="no-underline flex flex-row items-center h-6 transform hover:translate-x-2 transition-transform ease-in duration-200 text-gray-500 hover:text-gray-800">
                  <span class="inline-flex items-center justify-center h-12 w-12 text-lg text-gray-400"><i class="bx bx-chat"></i></span>
                  <span class="text-sm font-medium">Billetera</span>
                </a>
                <div x-show="open" class="pl-4 py-1">
                  <!-- Elementos -->
                  <ul>
                    <li><a class="text-gray-500 transform hover:translate-x-2 transition-transform ease-in duration-200
                       hover:text-gray-800 no-underline text-sm" href="/dashboard">-Ajustes de Billetera</a></li>
                    <li><a class="text-gray-500 transform hover:translate-x-2 transition-transform ease-in duration-200
                       hover:text-gray-800 no-underline text-sm" href="/dashboard">-Pagos en espera</a></li>
                  </ul>
                </div>
              </li>


              <li x-data="{ open: false }">
                <a @click="open = !open" class="no-underline flex flex-row items-center h-6 transform hover:translate-x-2 transition-transform ease-in duration-200 text-gray-500 hover:text-gray-800">
                  <span class="inline-flex items-center justify-center h-12 w-12 text-lg text-gray-400"><i class="bx bx-chat"></i></span>
                  <span class="text-sm font-medium">Suscripción</span>
                </a>
                <div x-show="open" class="pl-4 py-1">
                  <!-- Elementos -->
                  <ul>
                    <li><a class="text-gray-500 transform hover:translate-x-2 transition-transform ease-in duration-200
                       hover:text-gray-800 no-underline text-sm" href="/dashboard">-Ajustes de suscripción</a></li>
                    <li><a class="text-gray-500 transform hover:translate-x-2 transition-transform ease-in duration-200
                       hover:text-gray-800 no-underline text-sm" href="/dashboard">-Pagos en espera</a></li>
                  </ul>
                </div>
              </li>

              <li x-data="{ open: false }">
                <a @click="open = !open" class="no-underline flex flex-row items-center h-6 transform hover:translate-x-2 transition-transform ease-in duration-200 text-gray-500 hover:text-gray-800">
                  <span class="inline-flex items-center justify-center h-12 w-12 text-lg text-gray-400"><i class="bx bx-chat"></i></span>
                  <span class="text-sm font-medium">Transferencia bancaria</span>
                </a>
                <div x-show="open" class="pl-4 py-1">
                  <!-- Elementos -->
                  {{-- <ul>
                    <li><a class="text-gray-500 transform hover:translate-x-2 transition-transform ease-in duration-200
                       hover:text-gray-800 no-underline" href="">-Ajustes de suscripción</a></li>
                    <li><a class="text-gray-500 transform hover:translate-x-2 transition-transform ease-in duration-200
                       hover:text-gray-800 no-underline" href="">-Pagos en espera</a></li>
                  </ul> --}}
                </div>
              </li>

            </div>

          </template>
      </ul>
  </div>

  <div x-data="{ open1: false }">
    <ul class="flex flex-col pt-3 cursor-pointer m-0">
        <h6 @click="open1 = !open1" class="cursor-pointer">Chats</h6>
        <template x-if="open1">
          <div  x-if="open">
            <li x-data="{ open: false }">
              <a @click="open = !open" class="no-underline flex flex-row items-center h-8 transform hover:translate-x-2 transition-transform ease-in duration-200 text-gray-500 hover:text-gray-800">
                <span class="inline-flex items-center justify-center h-12 w-12 text-lg text-gray-400"><i class="bx bx-chat"></i></span>
                <span class="text-sm font-medium">Chat</span>
              </a>
              <div x-show="open" class="pl-4 py-1">
                <!-- Elementos -->
                <ul>
                  <li><a class="text-gray-500 transform hover:translate-x-2 transition-transform ease-in duration-200
                     hover:text-gray-800 no-underline  h-12 w-12 text-sm" href="{{ route('chat.chat') }}" :active="request()->routeIs('chat.*')">-Chat IA</a></li>
                </ul>
              </div>
            </li>
          </div>

        </template>
    </ul>
  </div>

  <div x-data="{ open1: false }">
    <ul class="flex flex-col pt-2 cursor-pointer m-0">
        <h6 @click="open1 = !open1" class="cursor-pointer m-0">Turismo</h6>
        <template x-if="open1">
          <div  x-if="open">
            <li x-data="{ open: false }">
              <a @click="open = !open" class="no-underline flex flex-row items-center h-8 transform hover:translate-x-2 transition-transform ease-in duration-200 text-gray-500 hover:text-gray-800">
                <span class="inline-flex items-center justify-center h-12 w-12 text-lg text-gray-400"><i class="bx bx-chat"></i></span>
                <span class="text-sm font-medium">Paquetes</span>
              </a>
              <div x-show="open" class="pl-4 py-1">
                <!-- Elementos -->
                <ul class="cursor-pointer">
                     <li><a class="text-gray-500 transform hover:translate-x-2 transition-transform ease-in duration-200
                      hover:text-gray-800 no-underline text-sm" href="/dashboard">-Ajustes</a></li>
                      <li><a class="text-gray-500 transform hover:translate-x-2 transition-transform ease-in duration-200
                        hover:text-gray-800 no-underline text-sm" href="/dashboard">-Listado de paquetes</a></li>
                </ul>
              </div>
            </li>

            <li x-data="{ open: false }">
              <a @click="open = !open" class="no-underline flex flex-row items-center h-6 transform hover:translate-x-2 transition-transform ease-in duration-200 text-gray-500 hover:text-gray-800">
                <span class="inline-flex items-center justify-center h-12 w-12 text-lg text-gray-400"><i class="bx bx-chat"></i></span>
                <span class="text-sm font-medium">Alojamiento</span>
              </a>
              <div x-show="open" class="pl-4 py-1">
                <!-- Elementos -->
                <ul>
                  <li><a class="text-gray-500 transform hover:translate-x-2 transition-transform ease-in duration-200
                     hover:text-gray-800 no-underline text-sm" href="/dashboard">-Ajustes</a></li>
                  <li><a class="text-gray-500 transform hover:translate-x-2 transition-transform ease-in duration-200
                     hover:text-gray-800 no-underline text-sm" href="/dashboard">-Listado de alojamientos</a></li>
                </ul>
              </div>
            </li>


            <li x-data="{ open: false }">
              <a @click="open = !open" class="no-underline flex flex-row items-center h-6 transform hover:translate-x-2 transition-transform ease-in duration-200 text-gray-500 hover:text-gray-800">
                <span class="inline-flex items-center justify-center h-12 w-12 text-lg text-gray-400"><i class="bx bx-chat"></i></span>
                <span class="text-sm font-medium">Renta de autos</span>
              </a>
              <div x-show="open" class="pl-4 py-1">
                <!-- Elementos -->
                <ul>
                  <li><a class="text-gray-500 transform hover:translate-x-2 transition-transform ease-in duration-200
                     hover:text-gray-800 no-underline text-sm" href="/dashboard">-Ajustes</a></li>
                  <li><a class="text-gray-500 transform hover:translate-x-2 transition-transform ease-in duration-200
                     hover:text-gray-800 no-underline text-sm" href="/dashboard">-Listado</a></li>
                </ul>
              </div>
            </li>

            <li x-data="{ open: false }">
              <a @click="open = !open" class="no-underline flex flex-row items-center h-6 transform hover:translate-x-2 transition-transform ease-in duration-200 text-gray-500 hover:text-gray-800">
                <span class="inline-flex items-center justify-center h-12 w-12 text-lg text-gray-400"><i class="bx bx-chat"></i></span>
                <span class="text-sm font-medium">Lineas aereas</span>
              </a>
              <div x-show="open" class="pl-4 py-1">
                <!-- Elementos -->
                <ul>
                  <li><a class="text-gray-500 transform hover:translate-x-2 transition-transform ease-in duration-200
                     hover:text-gray-800 no-underline text-sm" href="/dashboard">-Ajustes</a></li>
                  <li><a class="text-gray-500 transform hover:translate-x-2 transition-transform ease-in duration-200
                     hover:text-gray-800 no-underline text-sm" href="/dashboard">-Listado</a></li>
                </ul>
              </div>
            </li>

          </div>

        </template>
    </ul>
  </div>

  <div x-data="{ open1: false }">
    <ul class="flex flex-col pt-3 cursor-pointer m-0">
        <h6 @click="open1 = !open1" class="cursor-pointer">Ajustes</h6>
        <template x-if="open1">
          <div  x-if="open">
            <li x-data="{ open: false }">
              <a @click="open = !open" class="no-underline flex flex-row items-center h-8 transform hover:translate-x-2 transition-transform ease-in duration-200 text-gray-500 hover:text-gray-800">
                <span class="inline-flex items-center justify-center h-12 w-12 text-lg text-gray-400"><i class="bx bx-chat"></i></span>
                <span class="text-sm font-medium">Ajustes de sistema</span>
              </a>
              <div x-show="open" class="pl-4 py-1">
              </div>
            </li>
            <li x-data="{ open: false }">
              <a @click="open = !open" class="no-underline flex flex-row items-center h-8 transform hover:translate-x-2 transition-transform ease-in duration-200 text-gray-500 hover:text-gray-800">
                <span class="inline-flex items-center justify-center h-12 w-12 text-lg text-gray-400"><i class="bx bx-chat"></i></span>
                <span class="text-sm font-medium">Ajustes de correos</span>
              </a>
              <div x-show="open" class="pl-4 py-1">
              </div>
            </li>
            <li x-data="{ open: false }">
              <a @click="open = !open" class="no-underline flex flex-row items-center h-8 transform hover:translate-x-2 transition-transform ease-in duration-200 text-gray-500 hover:text-gray-800">
                <span class="inline-flex items-center justify-center h-12 w-12 text-lg text-gray-400"><i class="bx bx-chat"></i></span>
                <span class="text-sm font-medium">Ajustes de sms</span>
              </a>
              <div x-show="open" class="pl-4 py-1">
              </div>
            </li>
            <li x-data="{ open: false }">
              <a @click="open = !open" class="no-underline flex flex-row items-center h-8 transform hover:translate-x-2 transition-transform ease-in duration-200 text-gray-500 hover:text-gray-800">
                <span class="inline-flex items-center justify-center h-12 w-12 text-lg text-gray-400"><i class="bx bx-chat"></i></span>
                <span class="text-sm font-medium">Notificaciones</span>
              </a>
              <div x-show="open" class="pl-4 py-1">
              </div>
            </li>
            <li x-data="{ open: false }">
              <a @click="open = !open" class="no-underline flex flex-row items-center h-8 transform hover:translate-x-2 transition-transform ease-in duration-200 text-gray-500 hover:text-gray-800">
                <span class="inline-flex items-center justify-center h-12 w-12 text-lg text-gray-400"><i class="bx bx-chat"></i></span>
                <span class="text-sm font-medium">Chat de ajustes</span>
              </a>
              <div x-show="open" class="pl-4 py-1">
              </div>
            </li>
            <li x-data="{ open: false }">
              <a @click="open = !open" class="no-underline flex flex-row items-center h-8 transform hover:translate-x-2 transition-transform ease-in duration-200 text-gray-500 hover:text-gray-800">
                <span class="inline-flex items-center justify-center h-12 w-12 text-lg text-gray-400"><i class="bx bx-chat"></i></span>
                <span class="text-sm font-medium">Ajustes de pagos</span>
              </a>
              <div x-show="open" class="pl-4 py-1">
              </div>
            </li>
            <li x-data="{ open: false }">
              <a @click="open = !open" class="no-underline flex flex-row items-center h-8 transform hover:translate-x-2 transition-transform ease-in duration-200 text-gray-500 hover:text-gray-800">
                <span class="inline-flex items-center justify-center h-12 w-12 text-lg text-gray-400"><i class="bx bx-chat"></i></span>
                <span class="text-sm font-medium">Ajustes de seguridad</span>
              </a>
              <div x-show="open" class="pl-4 py-1">
              </div>
            </li>
            <li x-data="{ open: false }">
              <a @click="open = !open" class="no-underline flex flex-row items-center h-8 transform hover:translate-x-2 transition-transform ease-in duration-200 text-gray-500 hover:text-gray-800">
                <span class="inline-flex items-center justify-center h-12 w-12 text-lg text-gray-400"><i class="bx bx-chat"></i></span>
                <span class="text-sm font-medium">Ajustes de carga de archivos</span>
              </a>
              <div x-show="open" class="pl-4 py-1">
              </div>
            </li>
            <li x-data="{ open: false }">
              <a @click="open = !open" class="no-underline flex flex-row items-center h-8 transform hover:translate-x-2 transition-transform ease-in duration-200 text-gray-500 hover:text-gray-800">
                <span class="inline-flex items-center justify-center h-12 w-12 text-lg text-gray-400"><i class="bx bx-chat"></i></span>
                <span class="text-sm font-medium">Límites</span>
              </a>
              <div x-show="open" class="pl-4 py-1">
              </div>
            </li>
            <li x-data="{ open: false }">
              <a @click="open = !open" class="no-underline flex flex-row items-center h-8 transform hover:translate-x-2 transition-transform ease-in duration-200 text-gray-500 hover:text-gray-800">
                <span class="inline-flex items-center justify-center h-12 w-12 text-lg text-gray-400"><i class="bx bx-chat"></i></span>
                <span class="text-sm font-medium">Analítica</span>
              </a>
              <div x-show="open" class="pl-4 py-1">
              </div>
            </li>
          </div>

        </template>
    </ul>
  </div>

  </div>
</div>
