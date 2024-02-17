google.charts.load('current', {'packages':['corechart']});
google.charts.setOnLoadCallback(pollDataSource);

var date_from = "2024-02-16 22:00:00";
var date_to = "2024-02-16 23:00:00";

var ring_buffer;

var history_chart;
var history_data;
var history_options;
var last_date;
  
var intervalID;

function initRingBuffer(data) {
    
  var ring_buffer_header = ['Time', 'Data'];
  var ring_buffer_l = [ring_buffer_header];
  var i = 0;
  
  function addElement(item) {
    ring_buffer_l.push([item.date, Number(item.energy)]);
    i ++;
  }

  data.forEach(addElement);
  return ring_buffer_l;
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
  var client = new XMLHttpRequest();
  client.onload = handler;
  client.open("GET", "api.php?date_from=" + date_from + "&date_to=" + date_to);
  client.send();
}

function drawChart() {

  history_options = {
    title: 'Energy',
    legend: { position: 'bottom' },
    vAxis: {
      minValue: 0,
      maxValue: 3000
    },
    isStacked: true
  };

  history_chart = new google.visualization.SteppedAreaChart(document.getElementById('history_chart'));
  history_data = google.visualization.arrayToDataTable(ring_buffer);
  history_chart.draw(history_data, history_options);

  document.getElementById("date_from").innerHTML = "date_from: " + date_from;
  document.getElementById("date_to").innerHTML = "date_to: " + date_to;

}
