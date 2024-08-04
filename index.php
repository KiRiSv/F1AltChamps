<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Alternate Formula 1 Champions</title>
    <link rel="icon" type="image/x-icon" href="./favicon.ico">
    <link rel="stylesheet" href="main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
      //preset distributions
      const current = [25,18,15,12,10,8,6,4,2,1];
      const mk = [15,12,10,9,8,7,6,5,4,3,2,1];
      const y1950 = [8,6,4,3,2];
      const y1960 = [8,6,4,3,2,1];
      const y1961 = [9,6,4,3,2,1];
      const y1991 = [10,6,4,3,2,1];
      const y2003 = [10,8,6,5,4,3,2,1];
      let posCount = 10;
      function pageLoad() {
        //if no settings load default settings
        if (!window.location.search) {
          setPointsBoxes(current);
        //if settings put settings into the fields
        } else {
          const urlParams = new URLSearchParams(window.location.search);
          let positions = [];
          let dd = document.getElementById("system");
          //set value of dropdown
          dd.value = urlParams.get("points-distribution");
          //set value of championship selector
          if (urlParams.has("championship")) {
            let championshipToggle = document.getElementById("cSwitch");
            championshipToggle.checked = true;
          }
          //set distribution to preset if not custom
          if (dd.value != "custom") {
            swapDist();
          //set points values if custom distribution
          } else {
            if (urlParams.has("flap")) {
              let flap = document.getElementById("flap");
              flap.value = urlParams.get("flap");
            }
            // add points values until none are left
            for (let i = 1; i < 34; i++) {
              if (urlParams.has("p"+i.toString())) {
                positions.push(urlParams.get("p"+i.toString()));
              } else {
                break;
              }
            }
            // set the values of the boxes with past setting
            setPointsBoxes(positions);
          }
        }
      }
      // setup position points boxes
      function setPointsBoxes(positions) {
        let pos = document.getElementById("positions");
        // clear current boxes
        pos.innerHTML = "";
        posCount = 0;
        // add boxes with values 
        for (let i = 0; i < positions.length; i++) {
          addPos(positions[i]);
        }
      }
      // Add a position number input box
      function addPos(val = 0) {
        if (posCount == 33) {
          alert("The heighest ever finishing position is P33...");
        } else {
          posCount++;
          // load the div for the number inputs
          let pos = document.getElementById("positions");
          // make a label for the number box
          let label = document.createElement("label");
          label.htmlFor = "p".concat(posCount.toString());
          label.innerHTML = "P".concat(posCount.toString());
          label.id = "lp".concat(posCount.toString());
          pos.append(label);
          // make number input
          let inp = document.createElement("input");
          inp.type = "number";
          inp.classList.add("twoDigits");
          inp.id = "p".concat(posCount.toString());
          inp.name = "p".concat(posCount.toString());
          inp.onChange = "setCustom()";
          inp.value = val;
          pos.append(inp);
          // if number box was added by user change the setting to custom
          if (val == 0){
            setCustom();
          }
          // unhide box remove button now that there is boxes to remove
          let sub = document.getElementById("sub");
          sub.hidden = false;
        }
      }
      function subPos() {
        // get lowest position box and label
        let label = document.getElementById("lp".concat(posCount.toString()));
        let inp = document.getElementById("p".concat(posCount.toString()));
        label.remove();
        inp.remove();
        posCount--;
        setCustom();
        // hide box remove button if not more boxes
        if (posCount==0) {
          let sub = document.getElementById("sub");
          sub.hidden = true;
        }
      }
      // set dropdown value to custom
      function setCustom() {
        let dd = document.getElementById("system");
        dd.value = "custom";
      }
      // set settings on preset selection
      function swapDist(){
        let dd = document.getElementById("system");
        let fast = document.getElementById("flap");
        switch (dd.value) {
          case "current":
            setPointsBoxes(current);
            fast.value = 1;
            break;
          case "mk8":
            setPointsBoxes(mk);
            fast.value = 0;
            break;
          case "1950":
            setPointsBoxes(y1950);
            fast.value = 1;
            break;
          case "1960":
            fast.value = 0;
            setPointsBoxes(y1960);
            break;
          case "1961":
            fast.value = 0;
            setPointsBoxes(y1961);
            break;
          case "1991":
            fast.value = 0;
            setPointsBoxes(y1991);
            break;
          case "2003":
            fast.value = 0;
            setPointsBoxes(y2003);
            break;
          default:
            break;
        }
      }
    </script>
  </head>
  <body onload="pageLoad()">
    <header>
    <h1>Formula 1 Champions with alternative points distributions</h1>
    <form action="index.php" class="settings" method="get">
      WDC <label class="switch"><input type="checkbox" id="cSwitch" name="championship"><span class="slider round"></span></label> WCC
      <label for="system">Choose points distribution:</label>
      <select onchange="swapDist()" class="settings" name="points-distribution" id="system">     
        <option value="current">Current</option>
        <option value="1950">1950-1959</option>
        <option value="1960">1960</option>
        <option value="1961">1961-1990</option>
        <option value="1991">1991-2002</option>
        <option value="2003">2003-2009</option>
        <option value="mk8">Mario Kart 8</option>
        <option value="custom">Custom</option>
      </select>
      <label for="flap">Fastest Lap</label>
      <input type="number" onchange="setCustom()" name="flap" id="flap" class="twoDigits" value="1">
      <div id="positions"></div>
      <br>
      <button onclick="addPos()" class="settings" id="plus" type="button">+</button>
      <button onclick="subPos()" class="settings" id="sub" type="button">-</button>
      <input type="submit" class="settings" value="Calculate">
    </form>
    </header>
    <!-- split for table and graphs -->
    <div class="row">
      <div class="column" id="table">
        <table>
          <thead>
            <tr>
              <th>Year</th>
              <th>🏆</th>
              <th>🥈</th>
              <th>🥉</th>
            </tr>
          </thead>
          <tbody>
          <?php 
          require_once('/home/webadmin/database.php');
          // Create connection
          $conn = new mysqli(HOST, DB_USR, DB_PSWD, DB_NAME);
          // Check connection
          if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
          }
          // creates unique name for temporary view
          $viewName = "v" . rand();
          // if default setting display default table
          if (count($_GET) == 0 || ($_GET["points-distribution"] == "current" && ! array_key_exists("championship", $_GET))){
            $query = "SELECT * FROM currenttop3 ; ";
          //If teams championship
          } elseif (array_key_exists("championship", $_GET)) {
            // create view to tabulate points
            $innerQuery = "( SELECT year, driver_id , constructor_id, ";
            for ($x = 1; $x < 34; $x++){
              if (array_key_exists("p". $x, $_GET)){
                //check for valid input and also protect from sql injection
                if (is_numeric($_GET["p".$x])) {
                  $innerQuery .= "( P" . $x . " * " . $_GET["p".$x] . ") +";
                } else {
                  die("Invalid data");
                }
              } else {
                break;
              }
            }
            //check for valid input and also protect from sql injection
            if (is_numeric($_GET["flap"])) {
              $innerQuery .= "( flap * " . $_GET["flap"] . ") AS score";
            } else {
              die("Invalid data");
            }
            $innerQuery .= " FROM driver_pos GROUP BY year, driver_id)";
            $query = "CREATE VIEW " . $viewName . " AS SELECT year, constructor_id, sum(score) AS score FROM " . $innerQuery . " AS iq GROUP BY iq.year, iq.constructor_id;";
            $conn->query($query);
            // Get the top 3 finishers from the view
            $query = "SELECT year, (SELECT constructor_id FROM " . $viewName . " WHERE year=m.year ORDER BY score DESC, constructor_id ASC FETCH FIRST 1 ROWS ONLY) AS P1,";
            $query .= "(SELECT constructor_id FROM " . $viewName . "  WHERE year=m.year ORDER BY score DESC, constructor_id ASC OFFSET 1 ROWS FETCH FIRST 1 ROWS ONLY) AS P2,";
            $query .= "(SELECT constructor_id FROM " . $viewName . "  WHERE year=m.year ORDER BY score DESC, constructor_id ASC OFFSET 2 ROWS FETCH FIRST 1 ROWS ONLY) AS P3 ";
            $query .= "FROM " . $viewName . " m GROUP BY year ORDER BY year DESC;";
            //If drivers championship
          } else {
            // create view to tabulate points
            $query = "CREATE VIEW " . $viewName  . " AS SELECT year, driver_id , ";
            for ($x = 1; $x < 34; $x++){
              if (array_key_exists("p". $x, $_GET)){
                //check for valid input and also protect from sql injection
                if (is_numeric($_GET["p".$x])) {
                  $query .= "( P" . $x . " * " . $_GET["p".$x] . ") +";
                } else {
                  die("Invalid data");
                }
              } else {
                break;
              }
            }
            //check for valid input and also protect from sql injection
            if (is_numeric($_GET["flap"])) {
              $query .= "( flap * " . $_GET["flap"] . ") AS score";
            } else {
              die("Invalid data");
            }
            $query .= " FROM driver_pos GROUP BY year, driver_id;";
            $conn->query($query);
            // Get the top 3 finishers from the view
            $query = "SELECT year, (SELECT driver_id FROM " . $viewName . " WHERE year=m.year ORDER BY score DESC, driver_id ASC FETCH FIRST 1 ROWS ONLY) AS P1,";
            $query .= "(SELECT driver_id FROM " . $viewName . "  WHERE year=m.year ORDER BY score DESC, driver_id ASC OFFSET 1 ROWS FETCH FIRST 1 ROWS ONLY) AS P2,";
            $query .= "(SELECT driver_id FROM " . $viewName . "  WHERE year=m.year ORDER BY score DESC, driver_id ASC OFFSET 2 ROWS FETCH FIRST 1 ROWS ONLY) AS P3 ";
            $query .= "FROM " . $viewName . " m GROUP BY year ORDER BY year DESC;";
          }
          $result = $conn->query($query);
          if ($result->num_rows > 0) {
            $championshipCount = array();
            $championshipYears = array();
            // output data of each row
            while($row = $result->fetch_assoc()) {
              echo "<tr>";
              echo "<td>" . $row["year"] . "</td>";
              // Captialize names and replace - with a space
              $p1 = ucwords(str_replace("-", " ", $row["P1"]));
              $p2 = ucwords(str_replace("-", " ", $row["P2"]));
              $p3 = ucwords(str_replace("-", " ", $row["P3"]));
              echo "<td>" .  $p1  . "</td>";
              echo "<td>" .  $p2  . "</td>";
              echo "<td>" .  $p3  . "</td>";
              echo "</tr>";
              // tabulate championship data for graphs 
              if (array_key_exists($p1,$championshipCount)) {
                $championshipCount[$p1]++;
                $championshipYears[$p1][] = $row["year"];
              } else {
                $championshipCount[$p1] = 1;
                $championshipYears[$p1] = [$row["year"]];
              }
            }
            //setup line to export data to js
            $data = "const data = [";
            //sort by most championship won
            arsort($championshipCount);
            $i = 0;
            foreach($championshipCount as $champion => $finalCount){
              //stop at top 10
              if($i >= 10){
                break;
              }
              sort($championshipYears[$champion]);
              $set = "{ label: '" . $champion . "', data: [";
              //set to zero at the start
              $set .= "{x: 1949 , y: 0},";
              //set to zero the year before winning so that the line goes up from there
              $set .= "{x: " . $championshipYears[$champion][0] -1 . ", y: 0}";
              foreach ($championshipYears[$champion] as $count => $year) { 
                $set .= ",{x: " . $year . ", y: ". $count + 1 . "}";
              }
              //set the final championship count for the driver
              $set .= ",{x: 2024, y: ". $finalCount . "}";
              $set .= "]},";
              $i++;
              $data .= $set;
            }
            $data = rtrim($data,',');
            $data .= "];";
            // set data variable in script tag
            echo "<script>" . $data . "</script>";
          } else {
            echo "ERROR";
          }
          // remove middle step view
          $conn->query("DROP VIEW IF EXISTS " . $viewName . ";");
          $conn->close();
          ?> 
          </tbody>
        </table>
      </div>
      <div class="column" id="charts">
        <canvas id="chart"></canvas>
        <script>
          const chartElem = document.getElementById("chart");
          // create y axsis 
          let championshipYears = [];
          for (let index = 1950; index < 2025; index++) {
            championshipYears.push(index);
          }
          new Chart(chartElem, {
            type: 'line',
            data: {
              labels: championshipYears,
              //data from php script
              datasets: data
            },
            options: {
              scales: {
                y: {
                  beginAtZero: true,
                  ticks: {
                    stepSize: 1
                  }
                }
              },
              plugins: {
                title: {
                  display: true,
                  text: 'Top 10 champions using chosen points distribution'
                }
              }
            }
          });
        </script>
      </div>
    </div>
    <footer>
      Created by Anna Zhou and Kim Rikter-Svendsen. 
      <a href="https://github.com/KiRiSv/F1AltChamps">Code on GitHub<i class="fa fa-github"></i></a>
    </footer>
  </body>
</html>
                
                