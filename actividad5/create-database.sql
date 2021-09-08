DROP DATABASE IF EXISTS concesionaria;

CREATE DATABASE concesionaria;

USE concesionaria;

CREATE TABLE vendedor (
  id_vendedor INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(128) NOT NULL
);

CREATE TABLE automovil (
  id_automovil INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  marca VARCHAR(40) NOT NULL,
  modelo VARCHAR(40) NOT NULL,
  precio INT NOT NULL,
  imagen VARCHAR(40) NOT NULL
);

CREATE TABLE venta (
  id_venta INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  id_automovil INT NOT NULL UNIQUE KEY,
  id_vendedor INT NOT NULL,
  nombre_cliente VARCHAR(100) NOT NULL, 
  fecha DATE NOT NULL,
  FOREIGN KEY (id_automovil) REFERENCES automovil(id_automovil),
  FOREIGN KEY (id_vendedor) REFERENCES vendedor(id_vendedor)
);

INSERT INTO vendedor (nombre) VALUES
  ('Oliver Cruz '),
  ('Lulú Meza'),
  ('Luna Li'),
  ('Luke Ayala'),
  ('María Zardoya'),
  ('Felix Lee ');


INSERT INTO automovil (marca, modelo, precio, imagen) VALUES
  ('Audi','R8',3470000,'audi-r8.jpg'),
  ('Toyota','Sienna 2021',699900,'toyota-sienna.jpg'),
  ('BMW','M4 Coupé 2021',2020000,'bmw-m4-coupé.jpg'),
  ('Volkswagen','Polo 2021',559000,'volkswagen-polo.jpg'),
  ('Toyota','RAV4',539300,'toyota-rav4.jpg');

CREATE VIEW venta_completa AS
SELECT automovil.id_automovil, imagen, marca, modelo, precio, 
       id_venta, nombre_cliente, vendedor.nombre, fecha
FROM automovil 
LEFT JOIN venta ON automovil.id_automovil = venta.id_automovil 
LEFT JOIN vendedor ON venta.id_vendedor = vendedor.id_vendedor;

CREATE VIEW conteo_ventas AS
SELECT nombre, COUNT(id_venta) AS conteo
FROM vendedor
LEFT JOIN venta ON vendedor.id_vendedor = venta.id_vendedor
GROUP BY nombre;
