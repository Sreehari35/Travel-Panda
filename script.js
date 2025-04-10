function validateForm() {
    var departure = document.getElementById("departure").value;
    var destination = document.getElementById("destination").value;
    var date = document.getElementById("date").value;
    var mode = document.getElementById("mode").value;
    var ticketClass = document.getElementById("class").value;
    var email = document.getElementById("email").value;

    if (departure === destination) {
        alert("Departure and Destination cities cannot be the same.");
        return false;
    }

    var today = new Date();
    var travelDate = new Date(date);
    if (travelDate <= today) {
        alert("Please select a valid future date for travel.");
        return false;
    }

    if (!email.match(/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/)) {
        alert("Please enter a valid email address.");
        return false;
    }

    if (mode === "") {
        alert("Please select a mode of transport (Flight or Train).");
        return false;
    }

    if (ticketClass === "") {
        alert("Please select a ticket class.");
        return false;
    }

    // Display results instead of redirecting
    document.getElementById("booking-results").innerHTML = `
        <h3>Booking Details</h3>
        <p><strong>From:</strong> ${departure}</p>
        <p><strong>To:</strong> ${destination}</p>
        <p><strong>Travel Date:</strong> ${date}</p>
        <p><strong>Mode:</strong> ${mode.charAt(0).toUpperCase() + mode.slice(1)}</p>
        <p><strong>Class:</strong> ${ticketClass.charAt(0).toUpperCase() + ticketClass.slice(1)}</p>
        <p><strong>Email:</strong> ${email}</p>
        <button onclick="location.reload()">New Search</button>
    `;

    // Show results section
    document.getElementById("booking-results").style.display = "block";

    // Hide the form after submission
    document.getElementById("booking-form").style.display = "none";

    return false; // Prevent actual form submission
}
