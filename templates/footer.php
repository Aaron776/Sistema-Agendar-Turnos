</main>

            <!-- Footer -->
            <footer class="main-footer">
                <p>© 2023 Dashboard Profesional - Creado con HTML, CSS y JavaScript</p>
            </footer>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle sidebar en móviles
            const toggleSidebar = document.querySelector('.toggle-sidebar');
            const sidebar = document.querySelector('.sidebar');
            const contentWrapper = document.querySelector('.content-wrapper');
            
            toggleSidebar.addEventListener('click', function() {
                sidebar.classList.toggle('active');
                contentWrapper.classList.toggle('sidebar-active');
            });

            // Menú de usuario
            const userAvatar = document.querySelector('.user-avatar');
            const userDropdown = document.querySelector('.user-dropdown');
            
            userAvatar.addEventListener('click', function(e) {
                e.stopPropagation();
                userDropdown.classList.toggle('active');
            });

            // Cerrar menú al hacer clic fuera
            document.addEventListener('click', function(e) {
                if (!userAvatar.contains(e.target) && !userDropdown.contains(e.target)) {
                    userDropdown.classList.remove('active');
                }
            });

            // Menú desplegable
            const treeview = document.querySelector('.treeview');
            const treeviewMenu = document.querySelector('.treeview-menu');
            const menuToggle = document.querySelector('.menu-toggle');
            
            treeview.addEventListener('click', function(e) {
                e.preventDefault();
                this.classList.toggle('active');
                treeviewMenu.classList.toggle('active');
            });

            // Efecto de carga para las tarjetas
            const cards = document.querySelectorAll('.card, .info-box');
            cards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.1}s`;
                card.classList.add('fade-in');
            });

            // Simular carga de datos
            setTimeout(() => {
                document.querySelectorAll('.progress-bar').forEach(bar => {
                    const width = bar.style.width;
                    bar.style.width = '0';
                    setTimeout(() => {
                        bar.style.transition = 'width 1.5s ease';
                        bar.style.width = width;
                    }, 300);
                });
            }, 500);
        });

        document.addEventListener('DOMContentLoaded', function() {
        // Toggle sidebar en móviles
        const toggleSidebar = document.querySelector('.toggle-sidebar');
        const sidebar = document.querySelector('.sidebar');
        const contentWrapper = document.querySelector('.content-wrapper');

        toggleSidebar.addEventListener('click', function() {
            sidebar.classList.toggle('active');
            contentWrapper.classList.toggle('sidebar-active');
        });

        // Menú de usuario
        const userAvatar = document.querySelector('.user-avatar');
        const userDropdown = document.querySelector('.user-dropdown');

        userAvatar.addEventListener('click', function(e) {
            e.stopPropagation();
            userDropdown.classList.toggle('active');
        });

        // Cerrar menú al hacer clic fuera
        document.addEventListener('click', function(e) {
            if (!userAvatar.contains(e.target) && !userDropdown.contains(e.target)) {
                userDropdown.classList.remove('active');
            }
        });
    });
    </script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle sidebar en móviles
        const toggleSidebar = document.querySelector('.toggle-sidebar');
        const sidebar = document.querySelector('.sidebar');
        const contentWrapper = document.querySelector('.content-wrapper');

        toggleSidebar.addEventListener('click', function() {
            sidebar.classList.toggle('active');
            contentWrapper.classList.toggle('sidebar-active');
        });

        // Menú de usuario
        const userAvatar = document.querySelector('.user-avatar');
        const userDropdown = document.querySelector('.user-dropdown');

        userAvatar.addEventListener('click', function(e) {
            e.stopPropagation();
            userDropdown.classList.toggle('active');
        });

        // Cerrar menú al hacer clic fuera
        document.addEventListener('click', function(e) {
            if (!userAvatar.contains(e.target) && !userDropdown.contains(e.target)) {
                userDropdown.classList.remove('active');
            }
        });
    });
</script>
    
</body>
</html>