<html>
  <head>
    <meta name="viewport" content="initial-scale=0.5, maximum-scale=2">
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
      <div id="history_chart" style="width: 800px; height: 500px;"></div>
      <input type="image" width="12" height="12" src="icon/plus.png" onclick="changeFrom(-2)" />
      <input type="image" width="12" height="12" src="icon/minus.png" onclick="changeFrom(2)" />
      <label id="date_from"></label>
      |
      <label id="date_to"></label>
      <input type="image" width="12" height="12" src="icon/minus.png" onclick="changeTo(-2)" />
      <input type="image" width="12" height="12" src="icon/plus.png" onclick="changeTo(2)" />
      <br>
      <label><a href="./">home</a></label>
    </center>
  </body>
</html>