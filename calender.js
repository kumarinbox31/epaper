$(document).ready(function () {
    // Initialize the calendar
    $('#calender').fullCalendar({
        // Set the options for the calendar
        // You can customize the options as per your requirements
        header: {
            left: 'prev,next today',
            center: 'title',
            // right: 'month,agendaWeek,agendaDay'
            right: false
        },
        defaultView: 'month', // Show the calendar in the month view
        events: [
            $.ajax({
                type:'post',
                url:'events.php',
                dataType:'json',
                success:function(res){
                    
                }
            })
            // {
            //     title: 'Event 1',
            //     start: '2023-05-01',
            //     url: 'https://example.com' // Specify the URL for redirection
            // },
            // {
            //     title: 'Event 2',
            //     start: '2023-05-02',
            //     url: 'https://example.com' // Specify the URL for redirection
            // }
            // Add more events as needed
        ],
        eventClick: function (event) {
            // Handle the event click
            if (event.url) {
                window.open(event.url); // Open the URL in a new tab
            }
            return false; // Prevent the default behavior
        }
    });
    $('#archive').click(function () {
        var x = document.getElementById("calender");
        if (x.style.display == "none") {
            x.style.display = "block";
        } else {
            x.style.display = "none";
        }
    })
});







