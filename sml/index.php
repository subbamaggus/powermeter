<html>
  <head>
    <meta name="viewport" content="initial-scale=1, maximum-scale=2">
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <style>
        label {
            width:100px;
            text-align: right;
            font-family: Arial, Helvetica, sans-serif;
            font-size: xx-small;
        }
    </style>

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript" src="chart.js"></script>
  </head>
  <body>
    <center>
      <div id="gauge_chart" style="width: 300px; height: 200px;"></div>
      <div id="history_chart" style="width: 400px; height: 250px;"></div>
      <input type="image" width="12" height="12" src="icon/plus.png" onclick="changeRingBufferSize(30)" />
      <input type="image" width="12" height="12" src="icon/time.png" />
      <input type="image" width="12" height="12" src="icon/minus.png" onclick="changeRingBufferSize(-30)" />
      |
      <input type="image" width="12" height="12" src="icon/slower.png" onClick="changePollRate(500)" />
      <input type="image" width="12" height="12" src="icon/faster.png" onClick="changePollRate(-500)" />
      <label id="refresh_rate"></label>
    </center>
  </body>
</html>