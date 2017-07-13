<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <!-- Syntax coloration -->
    <link rel="stylesheet" type="text/css" href="_examples_data/prism/prism.css" />
    <!-- This page style -->
    <link rel="stylesheet" type="text/css" href="_examples_data/style.css" />
    <!-- Flight Indicators library styles -->
    <link rel="stylesheet" type="text/css" href="css/flightindicators.css" />
    <title>jQuery Flight Indicators Plugin</title>
</head>
<body>
<div class="container">
    <h2>Air Cam Web Control</h2>
    <p>You can see the attitude of your aircraft. Trigger to take a photo with a click to the cam icon</p>
    <div class="examples">
        <div>
            <!-- icon from https://image.freepik.com/freie-ikonen/fotokamera-variante_318-30457.png -->
            <span id="camera"><img src="img/camicon.png" width="200" height="200" onclick="triggerCamera()"  style="cursor:pointer;" />
</span>
            <span id="attitude"></span>
            <span id="altimeter"></span>            
            <span id="heading"></span>
        </div>
    </div>
</div>


<!-- Syntax color -->
<script src="_examples_data/prism/prism.js"></script>

<!-- Importing jQuery library -->
<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.0/jquery.min.js"></script>

<!-- Importing the FlightIndicators library -->
<script src="js/jquery.flightindicators.js"></script>

<!-- Importing the Paho MQTT library -->
<script src="js/mqttws31-min.js"></script>
    
<!-- Let start our scripts -->
<script type="text/javascript">

<?php
    $aid = $_GET['aid'];
    echo "clientId = \"".$aid."\";"
?>
// Create a client instance
client = new Paho.MQTT.Client('www.mobile2power.de', Number('9001'), "web"+clientId);

// set callback handlers
client.onConnectionLost = onConnectionLost;
client.onMessageArrived = onMessageArrived;

// connect the client
client.connect({onSuccess:onConnect});


// called when the client connects
function onConnect() {
  // Once a connection has been made, make a subscription and send a message.
  console.log("onConnect");
  client.subscribe("uascon/"+clientId+"/position");
  message = new Paho.MQTT.Message("Hello my friend");
  message.destinationName = "World";
  client.send(message);
}

// called when the client loses its connection
function onConnectionLost(responseObject) {
  if (responseObject.errorCode !== 0) {
    console.log("onConnectionLost:"+responseObject.errorMessage);
  }
}

// called when a message arrives
function onMessageArrived(message) {
    obj = JSON.parse(message.payloadString);
    altimeter.setAltitude(obj.alt);
    attitude.setRoll(obj.rol+180.0);
    attitude.setPitch(obj.pit);
    heading.setHeading(obj.yaw+180.0);
}
  
function triggerCamera() {
    message = new Paho.MQTT.Message("{\"cam\":\"takepicture\"}");
    message.destinationName = "uascon/"+clientId+"/event";
    console.log("send "+message+" to "+message.destinationName);
    client.send(message);
}
    
var attitude = $.flightIndicator('#attitude', 'attitude', {roll:50, pitch:-20, size:200, showBox : true});
var heading = $.flightIndicator('#heading', 'heading', {heading:150, showBox:true});
var altimeter = $.flightIndicator('#altimeter', 'altimeter');

</script>
</body>
</html>