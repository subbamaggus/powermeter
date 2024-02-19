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


SELECT max(time), max(id), DATE_FORMAT(time, '%d-%m-%Y %H:%i') 
FROM `power` 
WHERE time < DATE_SUB(NOW(), INTERVAL 5 HOUR)
AND time > DATE_SUB(NOW(), INTERVAL 10 HOUR)
GROUP by DATE_FORMAT(time, '%d-%m-%Y %H')

SELECT max(time), max(id), DATE_FORMAT(time, '%d-%m-%Y %H:%i') 
FROM `power` 
WHERE time < DATE_SUB(NOW(), INTERVAL 10 HOUR)
GROUP by DATE_FORMAT(time, '%d-%m-%Y %H')

*/

?>