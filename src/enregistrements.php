<!DOCTYPE HTML>
<html>

<head>
  <title>Prototype</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="../resources/styles/Enregistrements.css">
</head>

<body>
  <div class="topnav">
    <h3>Prototype plateforme IoT</h3>
  </div>

  <br>

  <h3 style="color: #0c6980;">Tables de données</h3>

  <table class="styled-table" id="table_id">
    <thead>
      <tr>
        <th>No</th>
        <th>Carte</th>
        <th>Temperature (°C)</th>
        <th>Hmidité (%)</th>
        <th>Status capteur thermo</th>
        <th>LED 01</th>
        <th>LED 02</th>
        <th>Horodatage</th>
        <th>Date (dd-mm-yyyy)</th>
      </tr>
    </thead>
    <tbody id="tbody_table_record">
      <?php
      include '../config/connectionDB.php';

      $pdo = Database::connect();

      $sql = 'SELECT * FROM esp32_table_dht11_leds_record ORDER BY date, time';
      foreach ($pdo->query($sql) as $row) {
        $date = date_create($row['date']);
        $dateFormat = date_format($date, "d-m-Y");

        echo '<tr>';
        echo '<td class="bdr">' . $row['id'] . '</td>';
        echo '<td class="bdr">' . $row['board'] . '</td>';
        echo '<td class="bdr">' . $row['temperature'] . '</td>';
        echo '<td class="bdr">' . $row['humidity'] . '</td>';
        echo '<td class="bdr">' . $row['status_read_sensor_dht11'] . '</td>';
        echo '<td class="bdr">' . $row['LED_01'] . '</td>';
        echo '<td class="bdr">' . $row['LED_02'] . '</td>';
        echo '<td class="bdr">' . $row['time'] . '</td>';
        echo '<td>' . $dateFormat . '</td>';
        echo '</tr>';
      }
      Database::disconnect();

      ?>
    </tbody>
  </table>

  <br>

  <div class="btn-group">
    <button class="button" id="btn_prev" onclick="prevPage()">Préc</button>
    <button class="button" id="btn_next" onclick="nextPage()">Suiv</button>
    <div style="display: inline-block; position:relative; border: 0px solid #e3e3e3; float: center; margin-left: 2px;;">
      <p style="position:relative; font-size: 14px;"> Table : <span id="page"></span></p>
    </div>
    <select name="number_of_rows" id="number_of_rows">
      <option value="10">10</option>
      <option value="25">25</option>
      <option value="50">50</option>
      <option value="100">100</option>
    </select>
    <button class="button" id="btn_apply" onclick="apply_Number_of_Rows()">Appliquer</button>
  </div>

  <br>

  <script>
    //------------------------------------------------------------
    var current_page = 1;
    var records_per_page = 10;
    var l = document.getElementById("table_id").rows.length
    //------------------------------------------------------------

    //------------------------------------------------------------
    function apply_Number_of_Rows() {
      var x = document.getElementById("number_of_rows").value;
      records_per_page = x;
      changePage(current_page);
    }
    //------------------------------------------------------------

    //------------------------------------------------------------
    function prevPage() {
      if (current_page > 1) {
        current_page--;
        changePage(current_page);
      }
    }
    //------------------------------------------------------------

    //------------------------------------------------------------
    function nextPage() {
      if (current_page < numPages()) {
        current_page++;
        changePage(current_page);
      }
    }
    //------------------------------------------------------------

    //------------------------------------------------------------
    function changePage(page) {
      var btn_next = document.getElementById("btn_next");
      var btn_prev = document.getElementById("btn_prev");
      var listing_table = document.getElementById("table_id");
      var page_span = document.getElementById("page");

      // Validate page
      if (page < 1) page = 1;
      if (page > numPages()) page = numPages();

      [...listing_table.getElementsByTagName('tr')].forEach((tr) => {
        tr.style.display = 'none'; // reset all to not display
      });
      listing_table.rows[0].style.display = ""; // display the title row

      for (var i = (page - 1) * records_per_page + 1; i < (page * records_per_page) + 1; i++) {
        if (listing_table.rows[i]) {
          listing_table.rows[i].style.display = ""
        } else {
          continue;
        }
      }

      page_span.innerHTML = page + "/" + numPages() + " (Nombre total de lignes = " + (l - 1) + ") | Nombre de ligne : ";

      if (page == 0 && numPages() == 0) {
        btn_prev.disabled = true;
        btn_next.disabled = true;
        return;
      }

      if (page == 1) {
        btn_prev.disabled = true;
      } else {
        btn_prev.disabled = false;
      }

      if (page == numPages()) {
        btn_next.disabled = true;
      } else {
        btn_next.disabled = false;
      }
    }
    //------------------------------------------------------------

    //------------------------------------------------------------
    function numPages() {
      return Math.ceil((l - 1) / records_per_page);
    }
    //------------------------------------------------------------

    //------------------------------------------------------------
    window.onload = function() {
      var x = document.getElementById("number_of_rows").value;
      records_per_page = x;
      changePage(current_page);
    };
    //------------------------------------------------------------
  </script>
</body>

</html>