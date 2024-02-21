<?PHP

/*

prune concept: (separate script)
keep the last week.
delete all except one per hour for the last 4 weeks
delete all except one per day for the restore_error_handler

age
0-7     all
7-28    hourly
>28     daily

--data sets to keep:
SELECT max(time), max(id), DATE_FORMAT(time, '%d-%m-%Y %H:%i') 
FROM `power` 
WHERE time < DATE_SUB(NOW(), INTERVAL 2 DAY)
AND time > DATE_SUB(NOW(), INTERVAL 4 DAY)
GROUP by DATE_FORMAT(time, '%d-%m-%Y %H')

-- delete sets:
-- DELETE
SELECT *
FROM power
WHERE time < DATE_SUB(NOW(), INTERVAL 2 DAY)
AND time > DATE_SUB(NOW(), INTERVAL 4 DAY)
AND id NOT IN (SELECT min(id)
    FROM `power` 
    WHERE time < DATE_SUB(NOW(), INTERVAL 2 DAY)
    AND time > DATE_SUB(NOW(), INTERVAL 4 DAY)
    GROUP by DATE_FORMAT(time, '%d-%m-%Y %H'))


--data sets to keep:
SELECT max(time), max(id), DATE_FORMAT(time, '%d-%m-%Y %H:%i') 
FROM `power` 
WHERE time < DATE_SUB(NOW(), INTERVAL 4 DAY)
GROUP by DATE_FORMAT(time, '%d-%m-%Y %H')

-- delete sets:
-- DELETE 
SELECT *
FROM power 
WHERE time < DATE_SUB(NOW(), INTERVAL 4 DAY)
AND id NOT IN (SELECT min(id)
    FROM `power` 
    WHERE time < DATE_SUB(NOW(), INTERVAL 4 DAY)
    GROUP by DATE_FORMAT(time, '%d-%m-%Y'))

SELECT * 
FROM power
WHERE time < DATE_SUB(NOW(), INTERVAL 2 DAY)
    AND time > DATE_SUB(NOW(), INTERVAL 5 DAY)
   ORDER BY time
    
UPDATE power
set energy = ?
where id = ?  
  
    recalc all values between 2 and 5 days
*/

?>