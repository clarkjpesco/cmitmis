<!-- Footer -->
<footer class="bg-white shadow-lg mt-12">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="text-center text-gray-500 text-sm">
      <p>&copy; 2025 CMITMIS. All rights reserved.</p>
    </div>
  </div>
</footer>

<script>
  // Mobile menu toggle
  const mobileMenuBtn = document.getElementById('mobileMenuBtn');
  const mobileMenu = document.getElementById('mobileMenu');
  const menuIcon = mobileMenuBtn.querySelector('i');

  mobileMenuBtn.addEventListener('click', function() {
    mobileMenu.classList.toggle('hidden');

    // Toggle between hamburger and X icon
    if (mobileMenu.classList.contains('hidden')) {
      menuIcon.classList.remove('fa-times');
      menuIcon.classList.add('fa-bars');
    } else {
      menuIcon.classList.remove('fa-bars');
      menuIcon.classList.add('fa-times');
    }
  });
</script>

</body>

</html>