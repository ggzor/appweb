DROP DATABASE IF EXISTS examenes;
CREATE DATABASE examenes;

USE examenes;

CREATE TABLE usuarios
(
  id_usuario INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  nombre VARCHAR(150) NOT NULL,
  usuario VARCHAR(30) NOT NULL,
  pass VARCHAR(20) NOT NULL,
  pregunta VARCHAR(50) NOT NULL,
  respuesta VARCHAR(20) NOT NULL,
  tipo INT(1) NOT NULL
);

INSERT INTO usuarios
  (nombre, usuario, pass, pregunta, respuesta, tipo)
VALUES
  ('Gustavo Cerati', 'admin', 'Admin_pass01',
     '¿Cuál fue tu mejor álbum?', 'Siempre es hoy', 0),
  ('Axel', 'axelsp', 'Contraseña_01',
     '¿Quién es tu artista favorito?', 'Gustavo Cerati', 1);

