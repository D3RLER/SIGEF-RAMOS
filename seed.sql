DROP DATABASE IF EXISTS sigef_ramos;
CREATE DATABASE sigef_ramos;
USE sigef_ramos;
source c:/xampp/htdocs/SIGEF-RAMOS/db.sql;

INSERT IGNORE INTO inventario (sede_id, producto, categoria, stock, stock_minimo) VALUES (1, 'Ataud Madera Fina', 'Ataud', 3, 5), (1, 'Ataud Economico', 'Ataud', 10, 5), (2, 'Ataud Metal', 'Ataud', 2, 4); 
INSERT IGNORE INTO deudos (id, dni, nombres, apellidos) VALUES (1, '87654321', 'Juan', 'Perez'); 
INSERT IGNORE INTO difuntos (id, nombres, apellidos, fecha_fallecimiento, deudo_id) VALUES (1, 'Pedro', 'Perez', '2023-10-10', 1); 
INSERT IGNORE INTO servicios (difunto_id, sede_id, tipo_servicio, precio, fecha_servicio, estado) VALUES (1, 1, 'Servicio Completo', 3500.00, CURDATE(), 'en_proceso'), (1, 1, 'Cremacion', 2000.00, DATE_SUB(CURDATE(), INTERVAL 2 DAY), 'finalizado'), (1, 2, 'Servicio Basico', 1500.00, DATE_SUB(CURDATE(), INTERVAL 5 DAY), 'finalizado'), (1, 1, 'Traslado', 500.00, DATE_SUB(CURDATE(), INTERVAL 1 MONTH), 'finalizado'), (1, 2, 'Velatorio', 1000.00, CURDATE(), 'pendiente');
