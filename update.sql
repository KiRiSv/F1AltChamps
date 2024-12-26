/* What the group concat below unwraps into
SELECT
year, driver_id,
SUM(CASE
  WHEN position_number = '1' THEN 1 ELSE 0 END
) AS "1",
SUM(CASE
  WHEN position_number = '2' THEN 1 ELSE 0 END
) AS "2",
SUM(CASE
  WHEN position_number = '3' THEN 1 ELSE 0 END
) AS "3",
SUM(CASE
  WHEN position_number = '4' THEN 1 ELSE 0 END
) AS "4"
ETC...
FROM race_result
GROUP BY year, driver_id;
*/

DROP TABLE IF EXISTS driver_pos;
DROP TABLE IF EXISTS currenttop3;
-- Sums up finishes in each position for each driver in each season
SET @sql = NULL;
SELECT
GROUP_CONCAT(DISTINCT CONCAT(
  'SUM(',
  'CASE WHEN position_number = ', position_number, ' THEN 1 ELSE 0 END)', 
  'AS P', position_number) ORDER BY LENGTH(position_number), position_number
)
INTO @sql
FROM race_result;


SET @sql = CONCAT('SELECT year, driver_id, constructor_id, SUM(fastest_lap) AS flap, ', @sql, 
  ' FROM race_result JOIN race ON race_id = id GROUP BY year, driver_id');


SET @sql = CONCAT('CREATE TABLE driver_pos AS ', @sql);



PREPARE stmt FROM @sql;

EXECUTE stmt;
DEALLOCATE PREPARE stmt;


ALTER TABLE driver_pos ADD COLUMN Countback INT NOT NULL DEFAULT 0;

-- Gets countback by using row number when sorted by position count
SET @rownum = NULL;
SELECT
GROUP_CONCAT(DISTINCT CONCAT(
  'driver_pos.P', position_number, ' DESC') ORDER BY LENGTH(position_number), position_number
)
INTO @rownum
FROM race_result;

SET @rownum = CONCAT('UPDATE driver_pos INNER JOIN (
  SELECT ROW_NUMBER() OVER(PARTITION BY year ORDER BY ', @rownum ,') as Countback, driver_id, year FROM driver_pos) as temp
  ON driver_pos.driver_id = temp.driver_id AND driver_pos.year = temp.year
  SET driver_pos.countback = temp.countback;');


PREPARE stmt FROM @rownum;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

--Make a table of the current point system to cache it
SET @innerTop3 = "SELECT year, driver_id , constructor_id, Countback, ( P1 * 25) +( P2 * 18) +( P3 * 15) +( P4 * 12) +( P5 * 10) +( P6 * 8) +( P7 * 6) +( P8 * 4) +( P9 * 2) +( P10 * 1) +( flap * 1) AS score FROM driver_pos ORDER BY score DESC, Countback ASC";
SET @outerTop3 = CONCAT( "CREATE TABLE currenttop3 AS ", 
"SELECT year, (SELECT driver_id FROM (" ,@innerTop3, ") AS a WHERE year=m.year ORDER BY score DESC, Countback ASC FETCH FIRST 1 ROWS ONLY) AS P1,",
"(SELECT driver_id FROM (" ,@innerTop3, ") AS b WHERE year=m.year ORDER BY score DESC, Countback ASC OFFSET 1 ROWS FETCH FIRST 1 ROWS ONLY) AS P2,",
"(SELECT driver_id FROM (" ,@innerTop3, ") AS c WHERE year=m.year ORDER BY score DESC, Countback ASC OFFSET 2 ROWS FETCH FIRST 1 ROWS ONLY) AS P3 ",
"FROM (" ,@innerTop3, ") AS m GROUP BY year ORDER BY year DESC;");


PREPARE stmt FROM @outerTop3;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;