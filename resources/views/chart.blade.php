<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kafka demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  </head>
  <body class="bg-dark">
    <div style="width: 600px">

        <canvas id="signalTypesChart" width="20" height="20"

        </canvas>

    </div>

    {{-- <script type ="module" src="resources/js/detections.js"></script> --}}
    <div style="width: 800px;"><canvas id="test"></canvas></div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src=" https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js "></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="/socket.io/socket.io.js"></script>
  </body>
</html>

<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
  <script>
    const ctx = document.getElementById('signalTypesChart').getContext('2d');
    const chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: [],
            datasets: [{
                label: 'Number of Detections',
                data: [],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                }
            }
        }
    });
    // Enable pusher logging - don't include this in production
    Pusher.logToConsole = true;

    let channels = ["channelA" , "channelB" , "channelC" , "channelD"]
    var pusher = new Pusher('e69edaf25ba122eccca6', {
      cluster: 'ap2'
    });

    channels.forEach( (channelName) => {
        let channel = pusher.subscribe(channelName);
        channel.bind('my-event', function(data) {
            const signalType = data.SignalType;
            const existingData = chart.data.datasets[0].data;
            const existingLabels = chart.data.labels;

            // Check if the signal type already exists in the dataset
            const index = existingLabels.indexOf(signalType);
            if (index !== -1) {
            // Update the existing data point
            existingData[index]++;
            } else {
            // Add a new data point
            existingData.push(1);
            existingLabels.push(signalType);
            }
            // Update the chart
            chart.data.datasets[0].data = existingData;
            chart.data.labels = existingLabels;
            chart.update();
        });
    });


  </script>
</script>
