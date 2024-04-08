google.charts.load('current', {'packages':['corechart']});
google.charts.setOnLoadCallback(pollDataSource);


var date_from_date = new Date();
var date_to_date = new Date();


var date_from = "";
var date_to = "";

var ring_buffer;

var history_chart;
var history_data;
var history_options;
var last_date;
  
var intervalID;

function formatDate(date) {
  console.log("date: " + date);
  var mydate = new Date(date);
  console.log("mydate: " + mydate);
  return mydate.toISOString("YYYY-MM-DD HH:mm:ss").substring(0, 19).replace("T", ' ');
}

function initRingBuffer(data) {
    
  var ring_buffer_header = ['Time', 'Data'];
  var ring_buffer_l = [ring_buffer_header];
  var i = 0;
  
  function addElement(item) {
    ring_buffer_l.push([item.date, Number(item.value)]);
    i ++;
  }

  data.forEach(addElement);
  return ring_buffer_l;
}

function changeFrom(diff) {
  var mydate = new Date(date_from);
  mydate.setHours(mydate.getHours() + diff - mydate.getTimezoneOffset()/60);
  
  console.log("changeFrom:" + diff);
  
  date_from = formatDate(mydate);
  document.getElementById("date_from").innerHTML = "" + date_from;
  pollDataSource();
}

function changeTo(diff) {
  var mydate = new Date(date_to);
  mydate.setHours(mydate.getHours() + diff - mydate.getTimezoneOffset()/60);
  
  console.log("changeFrom:" + diff);
  
  date_to = formatDate(mydate);
  document.getElementById("date_to").innerHTML = "" + date_to;
  pollDataSource();
}

function processData(jsonData) {
  var DataObject = JSON.parse(jsonData);

  ring_buffer = initRingBuffer(DataObject);
  history_data = google.visualization.arrayToDataTable(ring_buffer);

}

function handler() {
  if(this.status == 200 &&
    this.responseText != null ) {
    // success!
    processData(this.responseText);
    drawChart();
  }
}

function pollDataSource() {
  date_from_date = Date.parse(document.getElementById("date_from").innerHTML);
  date_to_date = Date.parse(document.getElementById("date_to").innerHTML);
  
  date_from = document.getElementById("date_from").innerHTML;
  date_to = document.getElementById("date_to").innerHTML;

  document.getElementById("link").href = "?date_from=" + date_from + "&date_to=" + date_to + "&sensor=" + document.getElementById("sensor").value;
  var client = new XMLHttpRequest();
  client.onload = handler;
  client.open("GET", "api.php?date_from=" + date_from + "&date_to=" + date_to + "&sensor=" + document.getElementById("sensor").value);
  client.send();
}

function drawChart() {

  history_options = {
    title: 'Messwert',
    legend: { position: 'bottom' },
    vAxis: {
      minValue: 0,
      maxValue: 2000
    },
    isStacked: true
  };

  history_chart = new google.visualization.SteppedAreaChart(document.getElementById('history_chart'));
  history_data = google.visualization.arrayToDataTable(ring_buffer);
  history_chart.draw(history_data, history_options);

  document.getElementById("date_from").innerHTML = "" + date_from;
  document.getElementById("date_to").innerHTML = "" + date_to;

}

function changeSensor() {
  pollDataSource();
}