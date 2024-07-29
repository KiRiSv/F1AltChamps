<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Alternate Formula 1 Champions</title>
    <link rel="stylesheet" href="main.css">
    <script>
      let current = [25,18,15,12,10,8,6,4,2,1];
      let mk = [15,12,10,9,8,7,6,5,4,3,2,1];
      let y1950 = [8,6,4,3,2];
      let y1960 = [8,6,4,3,2,1];
      let y1961 = [9,6,4,3,2,1];
      let y1991 = [10,6,4,3,2,1];
      let y2003 = [10,8,6,5,4,3,2,1];
      let posCount = 10;
      function setPointsBoxes(positions) {
        let pos = document.getElementById("positions");
        pos.innerHTML = "";
        for (let i = 1; i < positions.length+1; i++) {
          let label = document.createElement("label");
          label.id = "lp".concat(i.toString());
          label.innerHTML = "P".concat(i.toString());
          label.htmlFor = "p".concat(i.toString());
          pos.append(label);
          let inp = document.createElement("input");
          inp.type = "number";
          inp.classList.add("twoDigits");
          inp.id = "p".concat(i.toString());
          inp.name = "p".concat(i.toString());
          inp.value = positions[i-1];
          pos.append(inp);
        }
        posCount= positions.length;
      }
      function addPos() {
        if (posCount == 33) {
          alert("The heighest ever finishing position is P33...");
        } else {   
          posCount++;
          let pos = document.getElementById("positions");
          let label = document.createElement("label");
          label.htmlFor = "p".concat(posCount.toString());
          label.innerHTML = "P".concat(posCount.toString());
          label.id = "lp".concat(posCount.toString());
          pos.append(label);
          let inp = document.createElement("input");
          inp.type = "number";
          inp.classList.add("twoDigits");
          inp.id = "p".concat(posCount.toString());
          inp.name = "p".concat(posCount.toString());
          inp.value = 0;
          pos.append(inp);
          let dd = document.getElementById("system");
          dd.value= "custom";
          let sub = document.getElementById("sub");
          sub.hidden = false;
        }
      }
      function subPos() {
        let label = document.getElementById("lp".concat(posCount.toString()));
        let inp = document.getElementById("p".concat(posCount.toString()));
        label.remove();
        inp.remove();
        posCount--;
        let dd = document.getElementById("system");
        dd.value= "custom";
        if (posCount==0) {
          let sub = document.getElementById("sub");
          sub.hidden = true;
        }
      }
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
  <body onload="setPointsBoxes(current)">
    <header>
    <h1>Formula 1 Champions with alternative points distributions</h1>
    <form action="index.php" class="settings" method="get">
      WDC <label class="switch"><input type="checkbox" name="championship"><span class="slider round"></span></label> WCC
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
      <input type="number" name="flap" id="flap" class="twoDigits" value="1">
      <div id="positions"></div>
      <button onclick="addPos()" class="settings" id="plus" type="button">+</button>
      <button onclick="subPos()" class="settings" id="sub" type="button">-</button>
      <input type="submit" class="settings" value="Calculate">
    </form>
    </header>
    <table>
      <thead>
        <tr>
          <th>Year</th>
          <th>👑</th>
          <th>🥈</th>
          <th>🥉</th>
        </tr>
      </thead>
      <tbody>

      <?php 
      $servername = "localhost";
      $username = "webbah";
      // Dont look its totally not the pasword but dont look
      $password = "LH44 is the goat";
      $dbname = "f1";
      
      // Create connection
      $conn = new mysqli($servername, $username, $password, $dbname);
      // Check connection
      if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
      }
      // checks if first load
      if (count($_GET) == 0){
        $query = "SELECT * AS td FROM currenttop3 FOR XML RAW('tr'), ELEMENTS,TYPE  ";
          
      } else {
        $query = "SELECT year, driver_id , ";
        for ($x = 1; $x < 34; $x++){
          if (array_key_exists("P". $x, $_GET)){
            $query .= "( P" . $x . " * " . $_GET["p".$x] . ") +";
          } else {
            break;
          }
        }
        $query .= "( flap * " . $_GET["flap"] . ") AS score";
        $query .= " FROM final GROUP BY year, driver_id";
      }
      ?> 
      </tbody>
    </table>
  </body>
</html>
                
                