DROP DATABASE IF EXISTS votaciones;

CREATE DATABASE votaciones;

USE votaciones;

CREATE TABLE votante (
  id_votante INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(128) NOT NULL,
  numero_ine VARCHAR(5) NOT NULL,
  genero VARCHAR(1) NOT NULL
);

CREATE TABLE voto (
  id_voto INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  id_votante INT NOT NULL,
  partido VARCHAR(20) NOT NULL,
  fecha DATETIME NOT NULL,
  FOREIGN KEY (id_votante) REFERENCES votante(id_votante)
);

INSERT INTO votante (nombre, numero_ine, genero) VALUES
  ('Ana Alvarado Huitzil', '00001', 'M'),
  ('Lulú Hernandez Merino', '00003', 'M'),
  ('Diego Polo Merino', '00015', 'H'),
  ('Quetzalli Juárez', '00022', 'M'),
  ('Ricardo Milos', '00006', 'H');
