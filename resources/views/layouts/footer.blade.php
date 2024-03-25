<!-- Agrega SweetAlert y su CSS a tu página -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.css">
<footer class="footer py-4">
    <div class="container-fluid">
        <div class="row align-items-center justify-content-lg-between">
            <div class="col-lg-6 mb-lg-0 mb-4">
                <a class="no-underline text-muted" href="{{ route('terminos') }}">Términos y condiciones</a>
                <br>
                <a class="no-underline text-muted" href="{{ route('politicas') }}">Politicas y privacidad</a>
                <div class="copyright text-center text-sm text-muted text-lg-start">
                    ©
                    <script>
                        document.write(new Date().getFullYear())
                    </script>,
                    made with <i class="fa fa-heart"></i> by
                    <a href="#" class="font-weight-bold"  id="openModal" target="_blank">The Snausers</a>
                    for a better web.

                </div>
            </div>

        </div>
    </div>
    <script>
        // Agrega un evento de clic al enlace
        document.getElementById('openModal').addEventListener('click', function(event) {
          // Evita el comportamiento predeterminado del enlace
          event.preventDefault();
      
          // Muestra el modal de SweetAlert
          Swal.fire({
            title: "Creado por los Snausers",
            width: 600,
            padding: "3em",
            color: "#716add",
            background: "#fff url(https://sweetalert2.github.io/images/trees.png)",
            backdrop: `
              rgba(0,0,123,0.4)
              url(https://sweetalert2.github.io/images/nyan-cat.gif)
              left top
              no-repeat
            `,

          }).then((result) => {
            if (result.isConfirmed) {
              // Aquí puedes hacer algo si el usuario confirma
              console.log('El usuario confirmó');
            } else if (result.isDenied) {
              // Aquí puedes hacer algo si el usuario niega
              console.log('El usuario negó');
            }
          });
        });
      </script>
</footer>
