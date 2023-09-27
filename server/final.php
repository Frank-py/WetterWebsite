<? php
require_once('sqlconnection.php');

try {
    $graphType = 'temperature'; // Replace with your desired graph type
    $amount = 7; // Replace with your desired amount
    $interval = 'DAY'; // Replace with your desired interval

    // Call the getData function
    $data = getData($graphType, $amount, $interval);
    
    // Handle the data here as needed
    print_r($data);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .title {
            text-align: center;
            font-family: 'Arial', sans-serif;
            font-size: 36px;
            color: #3498db; /* Blue color */
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .header {
            display: flex;
            justify-content: space-between;
            padding: 20px;
        }

        .card {
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 20px;
            margin: 10px;
            width: calc(14.28% - 20px); /* 100% / 7 cards */
            text-align: center;
        }

        .sun-icon {
            font-size: 50px;
            margin-bottom: 10px;
        }

        .graph-card {
            display: flex;
            justify-content: space-between;
        }

        .graph {
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 20px;
            margin: 10px;
            flex: 1;
        }
        footer {
            color: white;
            padding: 10px 0;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .footer-links,
        .social-links {
            list-style: none;
            padding: 0;
            display: flex;
        }

        .footer-links li,
        .social-links li {
            margin-right: 20px; /* Add spacing between links */
        }

        .footer-links li:last-child,
        .social-links li:last-child {
            margin-right: 0; /* Remove margin for the last link */
        }

        .footer-links a,
        .social-links a {
            color: black;
            text-decoration: none;
            font-size: 16px; /* Adjust the font size to your liking */
            transition: color 0.3s; /* Add a smooth color transition on hover */
        }

        .footer-links a:hover,
        .social-links a:hover {
            color: #2c3e50; /* Change the text color on hover */
        }
        .footer-bottom {
            color: black;
            text-align: center;
            padding: 10px 0;
        }
        .wind-icon {
            font-size: 48px;
        }
        .rain-icon {
            font-size: 48px;
        }
        .compass-icon {
            font-size: 48px;
        }
        .humidity-icon {
            font-size: 48px;
        }
    </style>
</head>
<body>
    <div class="title">WVS-Wetter</div> 
    <div class="header">
        <div class="card">
            <p>Temperature: 22 °C</p>
            <img src="https://cdn0.iconfinder.com/data/icons/small-nature/50/3_Sep-11-512.png" width="48" height="48">
        </div>
        <div class="card">
            <p>Humidity: 50 %</p>
            <img src="https://cdn.iconscout.com/icon/free/png-256/free-humidity-4596680-3796777.png?f=webp&w=256" width="48" height="48">
        </div>
        <div class="card">
            <p>Air pressure: 100 hPa</p>
            <img src="https://cdn4.iconfinder.com/data/icons/weather-287/32/92-_pressure-_air-_wind-_weather-512.png" width="48" height="48">
        </div>
        <div class="card">
            <p>Wind speed: 3.4 m/s</p>
            <img src="https://thumbs.dreamstime.com/b/wind-icon-flat-vector-template-design-trendy-simple-isolated-illustration-signage-178596721.jpg" width="48" height="48">
        </div>
        <div class="card">
            <p>Wind direction: North</p>
            <img src="https://media.istockphoto.com/id/1174262453/vector/compass-line-icon-vector-illustration-isolated-flat-compass-topography-or-sea-navigation.jpg?s=612x612&w=0&k=20&c=qI7W8Mmn-gzowubSph4kaHQRhL2l54ZT8cvzf28IUG8=" width="48" height="48">
        </div>
        <div class="card">
            <p>Amount of rainfall: 10mm</p>
            <img src="https://static.vecteezy.com/system/resources/previews/011/529/600/original/forecast-and-weather-concept-minimalistic-monochrome-signs-suitable-for-apps-sites-advertisement-editable-stroke-line-icon-of-raindrops-under-cloud-as-symbol-of-rain-vector.jpg" width="48" height="48">
        </div>
    </div>
    <div>
        <label for="timeInterval">Select Time Interval:</label>
            <select id="timeInterval" onchange="showSelectedValue()">
                <option value="hour">Hour</option>
                <option value="day">Day</option>
                <option value="week">Week</option>
                <option value="month">Month</option>
        </select>
    </div>
    <div class="graph-card">
        <div class="graph">
            <canvas id="temperature"></canvas>
        </div>
        <div class="graph">
            <canvas id="humidity"></canvas>
        </div>
    </div>

    <div class="graph-card">
        <div class="graph">
            <canvas id="air_pressure"></canvas>
        </div>
        <div class="graph">
            <canvas id="wind_speed"></canvas>
        </div>
        <div class="graph">
            <canvas id="rain_fall"></canvas>
        </div>
    </div>
        <footer>
            <div class="footer-content">
                <ul class="footer-links">
                    <li><a href="#">Home</a></li>
                    <li><a href="/about-us">About Us</a></li> <!-- Replace with your actual subdomain URL -->
                </ul>
                <ul class="social-links">
                    <li><a href="https://github.com/Frank-py"><img width=55px height=55px src="github-icon.png" alt="GitHub"></a></li> <!-- Replace with your GitHub profile URL and icon -->
                    <li><a href="mailto:lenj94002@gmail.com"><img src="email-icon.png" width=55px height=55px alt="Email"></a></li> <!-- Replace with your email address and icon -->
                </ul>
            </div>
            <div class="footer-bottom">
                &copy; 2016-2023 WVS-Wetter. All rights reserved.
            </div>
        </footer>
            
    <script>
        // Example data for the graphs

        (async () => {

        var Temperature;
        var Humidity;
        var AirPressure;
        var WindSpeed;
        var Rainfall;

        var GustOfWind;
        var WindDirection;

        async function createGraph(graphType) {
            let obj;
            const res = await fetch(`http://localhost:8000/data?graphType=${graphType}`, {});
            obj = await res.json();
            var labels = [];
            var attributeData = [];
            for (let i = 0; i < obj.length; i++) {
                labels.push(obj[i].Timestamp);
                attributeData.push(obj[i][graphType]);
            }
            const data = {
                labels: labels,
                datasets: [{
                    label: graphType,
                    fill: true,
                    //backgroundColor: "rgba(220,220,220,0.2)",
                    borderWidth: 2,
                    //borderColor: "rgba(220,220,220,1)",
                    pointBorderColor: "rgba(220,220,220,1)",
                    pointBackgroundColor: "#fff",
                    pointBorderWidth: 1,
                    pointHoverRadius: 5,
                    pointHoverBackgroundColor: "rgba(220,220,220,1)",
                    pointHoverBorderColor: "rgba(220,220,220,1)",
                    pointHoverBorderWidth: 2,
                    backgroundColor: 'rgb(1,193,175)',
                    borderColor: 'rgb(1,193,175)',
                    data: attributeData
                }]
            };

            const config = {
                type: 'line',
                data: data,
                options: {
                    plugins: {
                        legend: {
                            labels: {
                                color: 'rgb(1,193,175)'
                            }
                        }
                    },
                    scales: {
                        x: {
                            border: {
                                color: "rgb(1,193,175)", // this here
                            },
                            ticks: {
                                color: "rgb(1,193,175)", // this here
                            },
                            grid: {
                                //display: false,
                                tickColor: "rgb(1,193,175)" // this her
                            }
                                                        
                        },
                        y: {
                            ticks: {
                                color: "rgb(1,193,175)", // this here
                            },
                            grid: {
                                //display: false,
                                tickColor: "rgb(1,193,175)" // this her
                            },
                            border: {
                                color: "rgb(1,193,175)", // this here
                            },

                        },
                    }
                }
            };

            return config;
        }
        async function updateChart(graphTypestr, graph) {
            let obj;
            const res = await fetch(`http://localhost:8000/data?graphType=${graphTypestr}`, {});
            obj = await res.json();
            if (obj.slice(-1).Timestamp != graph.data.labels.slice(-1)[0]) {
                graph.data.datasets[0].data.push(obj.slice(-1).Temperature);
                graph.update();
            }
        }
        var config;
        config = await createGraph("temperature");
        Temperature = new Chart(
            document.getElementById('temperature'), 
            config
        );
        config = await createGraph("humidity");
        Humidity = new Chart(document.getElementById('humidity'),
            config
        );
        config = await createGraph("air_pressure");
        AirPressure = new Chart(document.getElementById('air_pressure'),
            config
        );
        config = await createGraph("wind_speed")
        WindSpeed = new Chart(document.getElementById('wind_speed'),
            config
        );
        config = await createGraph("rain_fall")
        Rainfall = new Chart(document.getElementById('rain_fall'),
            config
        );

        setInterval(function () {updateChart("temperature", Temperature);}, 3000);
        setInterval(function () {updateChart("humidity", Humidity);}, 3000);
        setInterval(function () {updateChart("wind_speed", WindSpeed);}, 3000);
        setInterval(function () {updateChart("rain_fall", Rainfall);}, 3000);
        function showSelectedValue() {
            var selectElement = document.getElementById("timeInterval");
            var selectedValue = selectElement.value;
            alert("Selected Value: " + selectedValue);
        }

        })();
</script>
    </body>
    
    </html>
