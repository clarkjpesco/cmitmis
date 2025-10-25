       </div>
       </div>

       <script>
           // Simple JavaScript for interactivity
           document.addEventListener('DOMContentLoaded', function() {
               // Sidebar toggle for mobile (could be expanded)
               const sidebar = document.querySelector('.w-64');

               // You can add sidebar toggle functionality here for mobile responsiveness

               // Example: Add active class to navigation items on click
               const navItems = document.querySelectorAll('nav a');
               navItems.forEach(item => {
                   item.addEventListener('click', function() {
                       navItems.forEach(i => i.classList.remove('bg-primary', 'text-white'));
                       navItems.forEach(i => i.classList.add('hover:bg-gray-700'));
                       this.classList.add('bg-primary', 'text-white');
                       this.classList.remove('hover:bg-gray-700');
                   });
               });
           });
       </script>
       </body>

       </html>