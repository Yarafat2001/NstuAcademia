<?php include 'navbarhome.php'; ?>
 <!-- Contact Form Section -->
    <section class="container mx-auto p-6">
        <div class="bg-white shadow-md rounded-lg p-6 max-w-lg mx-auto">
            <h2 class="text-3xl font-bold mb-4 text-center text-blue-900">Contact Us</h2>
            <p class="text-gray-700 text-center mb-6">Feel free to reach out to us with any questions or feedback.</p>
            <form action="submit_contact.php" method="post">
                <div class="mb-4">
                    <label for="name" class="block text-gray-700 font-semibold">Name</label>
                    <input type="text" id="name" name="name" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div class="mb-4">
                    <label for="email" class="block text-gray-700 font-semibold">Email</label>
                    <input type="email" id="email" name="email" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div class="mb-4">
                    <label for="subject" class="block text-gray-700 font-semibold">Subject</label>
                    <input type="text" id="subject" name="subject" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div class="mb-4">
                    <label for="message" class="block text-gray-700 font-semibold">Message</label>
                    <textarea id="message" name="message" rows="4" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required></textarea>
                </div>
                <div class="text-center">
                    <button type="submit" class="bg-blue-900 text-white px-4 py-2 rounded-lg font-semibold hover:bg-blue-700">Send Message</button>
                </div>
            </form>
        </div>
    </section>

    <!-- Tailwind JS for dropdown behavior -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const dropdown = document.querySelector('.dropdown');
            const dropdownMenu = document.querySelector('.dropdown-menu');
            dropdown.addEventListener('click', () => {
                dropdownMenu.classList.toggle('hidden');
            });
        });
    </script>
</body>
</html>
