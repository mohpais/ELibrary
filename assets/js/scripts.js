window.addEventListener('DOMContentLoaded', event => {

    // Toggle the side navigation
    const sidebarToggle = document.body.querySelector('#sidebarToggle');
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', event => {
            event.preventDefault();
            document.body.classList.toggle('sb-sidenav-toggled');
            localStorage.setItem('sb|sidebar-toggle', document.body.classList.contains('sb-sidenav-toggled'));
        });
    }

});

// Define a reusable AJAX function
function sendAjaxRequest(url, method, data, successCallback, errorCallback) {
    $.ajax({
        url: url,
        method: method,
        data: data,
        success: successCallback,
        error: errorCallback
    });
}

function calculateSemester(joinDate) {
    // Parse the joinDate string into a Date object
    const joinDateObj = new Date(joinDate);

    // Calculate the current date
    const currentDate = new Date();

    // Calculate the difference in months between the current date and join date
    const monthsDiff = (currentDate.getFullYear() - joinDateObj.getFullYear()) * 12 +
        (currentDate.getMonth() - joinDateObj.getMonth());

    // Calculate the semester based on the difference in months
    const semester = Math.ceil(monthsDiff / 6);
    console.log("s", semester);
    return semester === 0 ? 1 : semester;
}

function onlyNumberKey(evt) {
             
    // Only ASCII character in that range allowed
    var ASCIICode = (evt.which) ? evt.which : evt.keyCode
    if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57))
        return false;
    return true;
}
