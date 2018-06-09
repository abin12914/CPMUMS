$(function () {
    var date = new Date()
    var d    = date.getDate(),
        m    = date.getMonth(),
        y    = date.getFullYear()

    //show wlcome message if freshly logged in
    if(loggedUser) {
        swal({
            title: 'Welcome '+ loggedUser,
            type: 'success',
            text: 'You Are Successfully Logged In.',
            timer: 2000,
            allowOutsideClick : false,
            showConfirmButton : false,
        });
    }

    // initialize the calendar
    $('#calendar').fullCalendar({
        height: 600,
        header: {
            left: 'prev',
            center: 'title',
            right: 'next'
      },
      events    : truckCertificates,
    });
});