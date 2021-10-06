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

CREATE TABLE examen
(
  id_examen INT KEY NOT NULL AUTO_INCREMENT,
  id_usuario INT NOT NULL,
  fecha DATE NOT NULL,
  calificacion FLOAT,
  FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario)
);

CREATE TABLE tema
(
  id_tema INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  nombre VARCHAR(20) NOT NULL
);

CREATE TABLE reactivo
(
  id_reactivo INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  publicado BOOLEAN NOT NULL,
  id_creador INT NOT NULL,
  id_tema INT NOT NULL,
  nivel VARCHAR(20) NOT NULL,
  enunciado VARCHAR(250) NOT NULL,
  FOREIGN KEY (id_creador) REFERENCES usuarios(id_usuario),
  FOREIGN KEY (id_tema) REFERENCES tema(id_tema)
);

CREATE TABLE ref_reactivo 
(
  id_ref_reactivo INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  id_examen INT NOT NULL,
  id_reactivo INT NOT NULL,
  FOREIGN KEY (id_examen) REFERENCES examen(id_examen),
  FOREIGN KEY (id_reactivo) REFERENCES reactivo(id_reactivo)
);
CREATE TABLE opcion
(
  id_opcion INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  id_reactivo INT NOT NULL,
  contenido VARCHAR(130) NOT NULL,
  correacta BOOLEAN NOT NULL,
  FOREIGN KEY (id_reactivo) REFERENCES reactivo(id_reactivo) 
);

CREATE TABLE opcion_elegida
(
  id_opcion_elegida INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  id_ref_reactivo INT NOT NULL,
  id_opcion INT NOT NULL,
  FOREIGN KEY (id_ref_reactivo) REFERENCES opcion(id_opcion)
);

INSERT INTO usuarios
  (nombre, usuario, pass, pregunta, respuesta, tipo)
VALUES
  ('Gustavo Cerati', 'admin', 'Admin_pass01',
     '¿Cuál fue tu mejor álbum?', 'Siempre es hoy', 0),
  ('Felix Chan', 'admin1', 'adminPass1*',
     '¿Dónde vivo?', 'En mi casa', 0),
  ('Axel', 'axelsp', 'Contraseña_01',
     '¿Quién es tu artista favorito?', 'Gustavo Cerati', 1),
  ('Sol', 'sunSky', 'Contraseña1234_',
     '¿Color favorito?', 'Azul', 1);

INSERT INTO tema 
  (nombre)
VALUES 
  ('Matemáticas'), ('Español');

INSERT INTO reactivo 
(publicado, id_creador, id_tema, nivel, enunciado)
VALUES
  (true, 1, 2, 'Básico', 'Son algunos de los marcadores gráficos que se utilizan para organizar el contenido de un reglamento.'),  
  (true, 1, 2, 'Básico', 'Son los subgéneros del cuento y la novela.'),
  (true, 1, 2, 'Básico', 'Son expresiones de sabiduría popular que utilizan el lenguaje en doble sentido.'),
  (true, 1, 2, 'Básico', 'Es quien se encarga de relatar los sucesos de una historia en los cuentos o novelas.'),
  (true, 2, 1, 'Básico', '¿Cuál es el valor absoluto del resultado de la siguiente operación? −8 + 3 ='),
  (true, 2, 1, 'Básico', 'Es el 25 % de 133.'),
  (true, 2, 1, 'Básico', 'Son figuras geométricas que están formadas por cuatro lados.');
INSERT INTO opcion 
  (id_reactivo, contenido, correacta)
VALUES 
(1, 'Incisos, viñetas, números romanos y negritas.', true),
(1, 'Puntos, comas, signos de interrogación y signos de admiración.', false),
(1, 'Guion largo, guion corto y paréntesis.', false),
(1, 'Párrafos, versos y estrofas.', false),
(2, 'Policiaco, romántico,  y aventuras.', true),
(2, 'Terror y  ciencia ficción', true),
(2, 'Himno, epístola, égloga y canción.', false),
(2, 'Diálogo, ensayo, biografía y cartas.', false),
(3, 'Canciones.', false),
(3, 'Fábulas.', false),
(3, 'Refranes.', true),
(3, 'Pregones.', false),
(4, 'Personaje.', false),
(4, 'Locutor.', false),
(4, 'Expositor.', false),
(4, 'Narrador.', true),
(5, '-5', false),
(5, '5', true),
(5, '-11', false),
(5, '11', false),
(6, '99.75', false),
(6, '32.35', false),
(6, '33.25', true),
(6, '97.59', false),
(7,'círculos', false),
(7,'triángulos', false),
(7,'polígonos', false),
(7,'cuadriláteros', false);

INSERT INTO examen
  (id_usuario, fecha, calificacion)
VALUES
  (3, NOW(), 8),
  (4, NOW(), NULL);

INSERT INTO ref_reactivo
  (id_examen, id_reactivo)
VALUES
  (1, 1),
  (1, 2),
  (1, 6),
  (1, 7),
  (2, 3),
  (2, 4),
  (2, 5),
  (2, 6);

INSERT INTO opcion_elegida
  (id_ref_reactivo, id_opcion)
VALUES
  (1, 1),
  (2, 5),
  (2, 6),
  (3, 22),
  (4, 28);

