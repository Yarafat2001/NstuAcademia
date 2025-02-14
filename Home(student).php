<?php include 'navbarhome.php';
 ?>
<!DOCTYPE html>
<main class="container mx-auto mt-10 px-6">
    <!-- Description Section -->
    <div class="bg-white p-6 rounded-lg shadow-lg flex flex-col md:flex-row justify-between items-center mb-10">
        <div class="md:w-1/2 space-y-4">
            <h1 class="text-4xl font-bold text-gray-800">Welcome to NstuAcademia</h1>
            <p class="text-gray-700 text-lg">We are committed to providing a streamlined experience for all university students. Navigate through our system to find out more.</p>
            <a href="index.php" class="bg-university-blue text-white px-4 py-2 rounded-lg hover:bg-gray-500">Get Started</a>
        </div>
        <div class="md:w-1/2 mt-6 md:mt-0 flex justify-center">
            <img src="./Campus_view.jpg" alt="Campus Image" class="rounded-lg shadow-lg w-full max-w-md">
        </div>
    </div>

    <!-- Announcements Section -->
    <section class="mb-10">
        <h2 class="text-3xl font-bold text-center mb-6 text-gray-800">Announcements</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-4 rounded-lg shadow-lg">
                <h3 class="text-xl font-bold text-gray-800 mb-2">Course Registration</h3>
                <p class="text-gray-700">The course registration process for the next semester starts on January 10th, 2024. Prepare your documents in advance.</p>
            </div>
            <div class="bg-white p-4 rounded-lg shadow-lg">
                <h3 class="text-xl font-bold text-gray-800 mb-2">Hall Seat Allocations</h3>
                <p class="text-gray-700">New hall seat allocations are announced. Check the Hall Seat Notice page for details.</p>
            </div>
            <div class="bg-white p-4 rounded-lg shadow-lg">
                <h3 class="text-xl font-bold text-gray-800 mb-2">Academic Calendar</h3>
                <p class="text-gray-700">The academic calendar for the upcoming session has been updated. Visit the Departments page for more details.</p>
            </div>
        </div>
    </section>

    <!-- Upcoming Events Section -->
    <section class="mb-10">
        <h2 class="text-3xl font-bold text-center mb-6 text-gray-800">Upcoming Events</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-4 rounded-lg shadow-lg">
                <h3 class="text-xl font-bold text-gray-800 mb-2">Cultural Fest</h3>
                <p class="text-gray-700">Join us for the annual cultural fest on February 15th, 2024. A day full of joy and celebration.</p>
            </div>
            <div class="bg-white p-4 rounded-lg shadow-lg">
                <h3 class="text-xl font-bold text-gray-800 mb-2">Science Fair</h3>
                <p class="text-gray-700">The university science fair is scheduled for March 25th, 2024. All students are encouraged to participate.</p>
            </div>
            <div class="bg-white p-4 rounded-lg shadow-lg">
                <h3 class="text-xl font-bold text-gray-800 mb-2">Career Seminar</h3>
                <p class="text-gray-700">A seminar on career counseling will be held on April 5th, 2024. Meet industry professionals.</p>
            </div>
        </div>
    </section>
</main>

</html>
<?php include 'footer.php'; ?>