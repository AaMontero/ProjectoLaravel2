<!-- SweetAlert -->


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
                <a href="https://travelqori.com/" class="font-weight-bold no-underline" target="_blank" >QORI</a>
                for a better web.
            </div>
        </div>
        <div class="col-lg-6 text-end">
            <a class="font-weight-bold cursor-default p-3" style="color: transparent; text-decoration: none;"  id="openModal" target="_blank" ></a>
        </div>
    </div>
</div>

    <script>
        // Modal
        document.getElementById('openModal').addEventListener('click', function(event) {
          event.preventDefault();
          Swal.fire({
            title: "Creado por los Snausers",
            width: 600,
            padding: "3em",
            background: "#fff url(https://sweetalert2.github.io/images/trees.png)",
            backdrop: `
              rgba(0,0,123,0.4)
              url(https://sweetalert2.github.io/images/nyan-cat.gif)
              left top
              no-repeat
            `,
          }).then((result) => {
          });
        });
      </script>
</footer>
