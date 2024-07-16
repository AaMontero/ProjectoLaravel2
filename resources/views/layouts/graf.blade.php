
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 10px;
            max-width: 1200px;
        }
        .card {
            margin-bottom: 30px;
        }
        .card-title {
            font-weight: bold;
            color: #007bff;
        }
        .table th, .table td {
            vertical-align: middle;
        }
        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="container">
        
        <!-- Gráficos -->
        <div class="row">
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-chart-bar"></i> Gráfico de Barras</h5>
                        <div class="chart-container">
                            <canvas id="barChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-chart-line"></i> Gráfico de Líneas</h5>
                        <div class="chart-container">
                            <canvas id="lineChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12" style="display: flex">
                <div class="card" >
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-chart-pie"></i> Gráfico de Torta</h5>
                        <div class="chart-container">
                            <canvas id="pieChart"></canvas>
                        </div>
                        
                    </div>
                </div>
                <div class="col-md-9"> 
                    <div class="card" style="margin-left: 10%; height: 90%; width: 90%">
                            <canvas id="myChart" style="width:100%;max-width:600px"></canvas>
                    </div>
                   
                </div>
               
            </div>
        </div>
        
        <!-- Tabla -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-table"></i> Datos de Ejemplo</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Nombre</th>
                                <th>Apellido</th>
                                <th>Correo Electrónico</th>
                                <th>Teléfono</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Juan</td>
                                <td>Pérez</td>
                                <td>juan.perez@example.com</td>
                                <td>555-1234</td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>María</td>
                                <td>López</td>
                                <td>maria.lopez@example.com</td>
                                <td>555-5678</td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>Carlos</td>
                                <td>Sánchez</td>
                                <td>carlos.sanchez@example.com</td>
                                <td>555-8765</td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td>Ana</td>
                                <td>Martínez</td>
                                <td>ana.martinez@example.com</td>
                                <td>555-4321</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
   

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Bar Chart
        const barCtx = document.getElementById('barChart').getContext('2d');
        new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio'],
                datasets: [{
                    label: 'Ventas',
                    data: [65, 59, 80, 81, 56, 55, 40],
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Line Chart
        const lineCtx = document.getElementById('lineChart').getContext('2d');
        new Chart(lineCtx, {
            type: 'line',
            data: {
                labels: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio'],
                datasets: [{
                    label: 'Ingresos',
                    data: [65, 59, 80, 81, 56, 55, 40],
                    fill: false,
                    borderColor: 'rgba(54, 162, 235, 1)',
                    tension: 0.1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Pie Chart
        const pieCtx = document.getElementById('pieChart').getContext('2d');
        new Chart(pieCtx, {
            type: 'pie',
            data: {
                labels: ['Rojo', 'Azul', 'Amarillo', 'Verde', 'Púrpura', 'Naranja'],
                datasets: [{
                    label: 'Distribución de Colores',
                    data: [12, 19, 3, 5, 2, 3],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true
            }
        });

        const xValues = [100,200,300,400,500,600,700,800,900,1000];

new Chart("myChart", {
  type: "line",
  data: {
    labels: xValues,
    datasets: [{ 
      data: [860,1140,1060,1060,1070,1110,1330,2210,7830,2478],
      borderColor: "red",
      fill: false
    }, { 
      data: [1600,1700,1700,1900,2000,2700,4000,5000,6000,7000],
      borderColor: "green",
      fill: false
    }, { 
      data: [300,700,2000,5000,6000,4000,2000,1000,200,100],
      borderColor: "blue",
      fill: false
    }]
  },
  options: {
    legend: {display: false}
  }
});
    </script>
</body>
</html>
