SELECT a.id,
       a.driver_no,
       FormatName(a.first_name,a.middle_name,a.last_name) driver_name,
       a.date_created,
       a.picture,
       a.attachment,
       a.contact_no,
       a.company,
       a.assigned_vehicle_id,
       CASE WHEN b.plate_no IS NULL THEN b.cs_no ELSE b.plate_no END AS vehicle
FROM sys_vehicle_monitoring.company_drivers_master a 
LEFT JOIN sys_insurance_and_registration.iar_company_car_units b 
ON a.assigned_vehicle_id = b.id 
WHERE a.active = 1