<?php include 'navbarhome.php'; ?>

    <!-- Events Section -->
    <section class="container mx-auto p-6">
        <h2 class="text-3xl font-bold text-blue-900 text-center mb-6">Upcoming Events</h2>

        <!-- Events List -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Event Card 1 -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-xl font-semibold text-blue-900 mb-2">Event Title 1</h3>
                <p class="text-gray-700">Short description of the event goes here. Briefly tell what the event is about.</p>
                <p class="text-gray-600 mt-2">📅 Date: January 15, 2024</p>
                <a href="event-details.html?id=1" class="text-blue-700 mt-3 inline-block hover:underline">Learn More</a>
            </div>

            <!-- Event Card 2 -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-xl font-semibold text-blue-900 mb-2">Event Title 2</h3>
                <p class="text-gray-700">Short description of the event goes here. Briefly tell what the event is about.</p>
                <p class="text-gray-600 mt-2">📅 Date: February 10, 2024</p>
                <a href="event-details.html?id=2" class="text-blue-700 mt-3 inline-block hover:underline">Learn More</a>
            </div>

            <!-- Event Card 3 -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-xl font-semibold text-blue-900 mb-2">Event Title 3</h3>
                <p class="text-gray-700">Short description of the event goes here. Briefly tell what the event is about.</p>
                <p class="text-gray-600 mt-2">📅 Date: March 20, 2024</p>
                <a href="event-details.html?id=3" class="text-blue-700 mt-3 inline-block hover:underline">Learn More</a>
            </div>

            <!-- Add more event cards as needed -->
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
