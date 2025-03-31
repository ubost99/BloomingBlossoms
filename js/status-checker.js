/*
Name: Lauren Bost
Student #: 104902624
Date: 2025-03-29
Assignment: Project
Purpose: Build a full fledge retail website (no payment..etc) for a floral company called Blooming Blossoms
*/


// Wait for the document to be fully loaded before executing the script
document.addEventListener("DOMContentLoaded", () => {
    
    // Define the endpoints to check their status
    const endpoints = {
        "db-status": "includes/db.php",        // Check database connection
        "shop-status": "shop.php",             // Check shop page
        "cart-status": "cart.php",             // Check cart system
        "login-status": "login.php",           // Check login system
        "theme-status": "profile.php"          // Check theme loader
    };

    // Function to check the status of a service
    const checkService = async (id, url) => {
        try {
            // Send a HEAD request to the service URL
            const response = await fetch(url, { method: "HEAD" });
            const statusCell = document.getElementById(id);  // Get the status cell by its ID

            // Update the status cell based on the response
            if (response.ok) {
                statusCell.innerHTML = '<span class="badge bg-success">Online</span>'; // If service is online
            } else {
                statusCell.innerHTML = '<span class="badge bg-danger">Offline</span>';  // If service is offline
            }
        } catch (error) {
            // If there's an error (e.g., network failure), mark the service as offline
            document.getElementById(id).innerHTML = '<span class="badge bg-danger">Offline</span>';
        }
    };

    // Function to run the status checks for all services
    const runChecks = () => {
        // Loop through all the endpoints and check each service
        for (const [id, url] of Object.entries(endpoints)) {
            checkService(id, url);
        }
    };

    // Add event listener to the refresh button to manually trigger the status checks
    document.getElementById("refresh-status")?.addEventListener("click", () => {
        runChecks();
    });

    // Run the checks when the page loads
    runChecks();
    
    // Set up an interval to refresh the status every 10 seconds (10,000 milliseconds)
    setInterval(runChecks, 10000);
});
