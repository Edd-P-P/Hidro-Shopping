                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- End of Main Content -->

           <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Hidrosistemas <?php echo date('Y'); ?></span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->
        </div>
        <!-- End of Content Wrapper -->
    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">¿Listo para salir?</h5>
                    <button class="close" type="button" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Selecciona "Cerrar Sesión" si estás listo para finalizar tu sesión actual.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancelar</button>
                    <a class="btn btn-primary" href="logout.php">Cerrar Sesión</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- SB Admin 2 Simplificado - JavaScript integrado -->
    <script>
        // Toggle del sidebar
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarToggleTop = document.getElementById('sidebarToggleTop');
            const body = document.body;
            
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    body.classList.toggle('sidebar-toggled');
                    document.querySelector('.sidebar').classList.toggle('toggled');
                    
                    if (document.querySelector('.sidebar').classList.contains('toggled')) {
                        document.querySelector('.sidebar .collapse').classList.remove('show');
                    }
                });
            }
            
            if (sidebarToggleTop) {
                sidebarToggleTop.addEventListener('click', function(e) {
                    e.preventDefault();
                    body.classList.toggle('sidebar-toggled');
                    document.querySelector('.sidebar').classList.toggle('toggled');
                    
                    if (document.querySelector('.sidebar').classList.contains('toggled')) {
                        document.querySelector('.sidebar .collapse').classList.remove('show');
                    }
                });
            }
            
            // Cerrar sidebar responsive cuando se hace clic fuera
            window.addEventListener('DOMContentLoaded', function() {
                const sidebar = document.querySelector('.sidebar');
                const contentWrapper = document.getElementById('content-wrapper');
                
                if (contentWrapper && window.innerWidth < 768) {
                    contentWrapper.addEventListener('click', function() {
                        if (body.classList.contains('sidebar-toggled')) {
                            body.classList.remove('sidebar-toggled');
                            sidebar.classList.remove('toggled');
                        }
                    });
                }
            });
            
            // Prevent the content wrapper from scrolling when the fixed side navigation hovered over
            const fixedNav = document.querySelector('body.fixed-nav .sidebar');
            if (fixedNav) {
                fixedNav.on('mousewheel DOMMouseScroll wheel', function(e) {
                    if (window.innerWidth > 768) {
                        const e0 = e.originalEvent;
                        const delta = e0.wheelDelta || -e0.detail;
                        this.scrollTop += (delta < 0 ? 1 : -1) * 30;
                        e.preventDefault();
                    }
                });
            }
            
            // Scroll to top button appear
            window.addEventListener('scroll', function() {
                const scrollDistance = window.scrollY;
                const scrollToTop = document.querySelector('.scroll-to-top');
                
                if (scrollToTop) {
                    if (scrollDistance > 100) {
                        scrollToTop.style.display = 'block';
                    } else {
                        scrollToTop.style.display = 'none';
                    }
                }
            });
            
            // Smooth scrolling using scrollToTop
            document.querySelectorAll('.scroll-to-top').forEach(function(button) {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                });
            });
        });
        
        // Función para inicializar gráficos (se usa en inicio.php)
        function initCharts() {
            // Area Chart
            const areaChart = document.getElementById('myAreaChart');
            if (areaChart) {
                new Chart(areaChart, {
                    type: 'line',
                    data: {
                        labels: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"],
                        datasets: [{
                            label: "Ventas",
                            lineTension: 0.3,
                            backgroundColor: "rgba(78, 115, 223, 0.05)",
                            borderColor: "rgba(78, 115, 223, 1)",
                            pointRadius: 3,
                            pointBackgroundColor: "rgba(78, 115, 223, 1)",
                            pointBorderColor: "rgba(78, 115, 223, 1)",
                            pointHoverRadius: 3,
                            pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
                            pointHoverBorderColor: "rgba(78, 115, 223, 1)",
                            pointHitRadius: 10,
                            pointBorderWidth: 2,
                            data: [0, 10000, 5000, 15000, 10000, 20000, 15000, 25000, 20000, 30000, 25000, 40000],
                        }],
                    },
                    options: {
                        maintainAspectRatio: false,
                        layout: {
                            padding: {
                                left: 10,
                                right: 25,
                                top: 25,
                                bottom: 0
                            }
                        },
                        scales: {
                            xAxes: {
                                gridLines: {
                                    display: false,
                                    drawBorder: false
                                }
                            },
                            yAxes: {
                                ticks: {
                                    callback: function(value) {
                                        return '$' + value;
                                    }
                                },
                                gridLines: {
                                    color: "rgb(234, 236, 244)",
                                    zeroLineColor: "rgb(234, 236, 244)",
                                    drawBorder: false,
                                    borderDash: [2],
                                    zeroLineBorderDash: [2]
                                }
                            },
                        },
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                backgroundColor: "rgb(255,255,255)",
                                bodyColor: "#858796",
                                titleMarginBottom: 10,
                                titleColor: '#6e707e',
                                titleFont: {
                                    size: 14
                                },
                                borderColor: '#dddfeb',
                                borderWidth: 1,
                                padding: 15,
                                displayColors: false,
                                intersect: false,
                                mode: 'index',
                                caretPadding: 10,
                                callbacks: {
                                    label: function(context) {
                                        return '$' + context.parsed.y;
                                    }
                                }
                            }
                        }
                    }
                });
            }
            
            // Pie Chart
            const pieChart = document.getElementById('myPieChart');
            if (pieChart) {
                new Chart(pieChart, {
                    type: 'doughnut',
                    data: {
                        labels: ["Válvulas", "Cilindros", "Mangueras", "Accesorios"],
                        datasets: [{
                            data: [55, 30, 15, 10],
                            backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e'],
                            hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf', '#dda20a'],
                            hoverBorderColor: "rgba(234, 236, 244, 1)",
                        }],
                    },
                    options: {
                        maintainAspectRatio: false,
                        plugins: {
                            tooltip: {
                                backgroundColor: "rgb(255,255,255)",
                                bodyColor: "#858796",
                                borderColor: '#dddfeb',
                                borderWidth: 1,
                                padding: 15,
                                displayColors: false,
                                caretPadding: 10,
                            },
                            legend: {
                                display: false
                            }
                        },
                        cutout: '80%',
                    },
                });
            }
        }
        
        // Inicializar gráficos cuando la página cargue
        document.addEventListener('DOMContentLoaded', initCharts);
    </script>
</body>
</html>