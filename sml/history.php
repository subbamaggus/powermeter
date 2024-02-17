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
    <script type="text/javascript" src="charthistory.js"></script>
  </head>
  <body>
    <center>
      <div id="history_chart" style="width: 400px; height: 250px;"></div>
      <input type="image" width="12" height="12" src="icon/plus.png" onclick="changeRingBufferSize(30)" />
      <input type="image" width="12" height="12" src="icon/time.png" />
      <input type="image" width="12" height="12" src="icon/minus.png" onclick="changeRingBufferSize(-30)" />

      <label id="date_from"></label>
      |
      <label id="date_to"></label>
      <br>
      <label><a href="./">home</a></label>
    </center>
  </body>
</html>