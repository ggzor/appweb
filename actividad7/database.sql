DROP DATABASE IF EXISTS examenes;
CREATE DATABASE examenes;

USE examenes;

CREATE TABLE usuarios
(
  id_usuario INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  nombre VARCHAR(150) NOT NULL,
  pass VARCHAR(20) NOT NULL,
  tipo INT(1) NOT NULL
);

INSERT INTO usuarios
  (nombre, pass, tipo)
VALUES
  ('Administrador', 'admin_pass', 0),
  ('Usuario', 'user_pass', 1);