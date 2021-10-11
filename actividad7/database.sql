DROP DATABASE IF EXISTS examenes;
CREATE DATABASE examenes;

USE examenes;

CREATE TABLE usuarios
(
  id_usuario INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  nombre VARCHAR(100) NOT NULL,
  usuario VARCHAR(25) NOT NULL,
  pass VARCHAR(25) NOT NULL,
  pregunta VARCHAR(100) NOT NULL,
  respuesta VARCHAR(100) NOT NULL,
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
  nombre VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE reactivo
(
  id_reactivo INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  publicado BOOLEAN NOT NULL,
  id_creador INT NOT NULL,
  id_tema INT NOT NULL,
  nivel ENUM('BASICO', 'INTERMEDIO', 'AVANZADO') NOT NULL,
  enunciado TEXT NOT NULL,
  multiple BOOLEAN NOT NULL DEFAULT false,

  FOREIGN KEY (id_creador) REFERENCES usuarios(id_usuario),
  FOREIGN KEY (id_tema) REFERENCES tema(id_tema)
);

CREATE TABLE ref_reactivo 
(
  id_ref_reactivo INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  id_examen INT NOT NULL,
  id_reactivo INT NOT NULL,

  UNIQUE KEY (id_examen, id_reactivo),

  FOREIGN KEY (id_examen) REFERENCES examen(id_examen),
  FOREIGN KEY (id_reactivo) REFERENCES reactivo(id_reactivo)
);

CREATE TABLE opcion
(
  id_opcion INT NOT NULL AUTO_INCREMENT,
  id_reactivo INT NOT NULL,
  correcta BOOLEAN NOT NULL,
  contenido TEXT NOT NULL,

  PRIMARY KEY (id_opcion, id_reactivo),
  FOREIGN KEY (id_reactivo) REFERENCES reactivo(id_reactivo) 
);

CREATE TABLE opcion_elegida
(
  id_opcion_elegida INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  id_ref_reactivo INT NOT NULL,
  id_opcion INT NOT NULL,
  id_reactivo INT NOT NULL,

  UNIQUE KEY (id_ref_reactivo, id_opcion),
  FOREIGN KEY (id_ref_reactivo) REFERENCES ref_reactivo(id_ref_reactivo),
  CONSTRAINT fk_opcion FOREIGN KEY (id_opcion, id_reactivo) REFERENCES opcion(id_opcion, id_reactivo)
);

INSERT INTO usuarios
  (id_usuario, nombre, usuario, pass, pregunta, respuesta, tipo)
VALUES
  (1, 'Gustavo Cerati', 'admin', 'Admin_pass01',
      '¿Cuál fue tu mejor álbum?', 'Siempre es hoy', 0),
  (2,'Felix Chan', 'admin1', 'adminPass1*',
     '¿Dónde vivo?', 'En mi casa', 0),
  (3, 'Axel', 'axelsp', 'Contraseña_01',
      '¿Quién es tu artista favorito?', 'Gustavo Cerati', 1),
  (4, 'Sol', 'sunSky', 'Contraseña1234_',
      '¿Color favorito?', 'Azul', 1);

INSERT INTO tema 
  (id_tema, nombre)
VALUES 
  (1, 'Matemáticas'),
  (2, 'Español');

-- Opción múltiple
INSERT INTO reactivo VALUES
  (1, true,
      1, 2, -- creador, tema
      'BASICO', 
      'Son algunos de los marcadores gráficos que se utilizan para organizar el contenido de un reglamento.',
      false); -- no multiple

INSERT INTO opcion (id_opcion, id_reactivo, correcta, contenido) VALUES 
  (1, 1, true,  'Incisos, viñetas, números romanos y negritas.'),
  (2, 1, false, 'Puntos, comas, signos de interrogación y signos de admiración.'),
  (3, 1, false, 'Guion largo, guion corto y paréntesis.'),
  (4, 1, false, 'Párrafos, versos y estrofas.');

-- Verdadero/Falso
INSERT INTO reactivo VALUES
  (2, true,
      2, 1, -- creador, tema
      'AVANZADO', 
      '¿Las manecillas del reloj están en ángulo recto cuando marcan las tres?',
      false); -- no multiple

INSERT INTO opcion (id_opcion, id_reactivo, correcta, contenido)
VALUES 
  (5, 2, true, 'Verdadero'),
  (6, 2, false,'Falso.');

-- Múltiples opciones válidas
INSERT INTO reactivo VALUES
  (3, true,
      2, 1, -- creador, tema
      'INTERMEDIO', 
      'Figuras geometrícas con al menos 4 lados',
      true); -- multiple

INSERT INTO opcion (id_opcion, id_reactivo, correcta, contenido)
VALUES 
  (7,  3, false, 'Triangulo'),
  (8,  3, true,  'Cuadrado'),
  (9,  3, false, 'Circulo'),
  (10, 3, true,  'Pentágono');

INSERT INTO examen
  (id_examen, id_usuario, fecha, calificacion)
VALUES
  (1, 3, NOW(), 8.8),
  (2, 4, NOW(), NULL);

INSERT INTO ref_reactivo
  (id_ref_reactivo, id_examen, id_reactivo)
VALUES
  (1, 1, 1), 
  (2, 1, 2),  
  (3, 1, 3), 

  (4, 2, 2), 
  (5, 2, 3); 

INSERT INTO opcion_elegida
  (id_ref_reactivo, id_opcion, id_reactivo)
VALUES
  -- Primer examen, contestado
  (1, 1,  1),
  (2, 5,  2),
  (3, 8,  3),
  (3, 10, 3);

CREATE VIEW reactivos_por_examen AS
SELECT id_examen,
       reactivo.id_reactivo as id_reactivo, 
       nivel, tema.nombre as nombre_tema,
       enunciado,
       multiple
FROM ref_reactivo 
JOIN reactivo 
ON ref_reactivo.id_reactivo = reactivo.id_reactivo
JOIN tema
ON reactivo.id_tema = tema.id_tema
ORDER BY id_examen, reactivo.id_reactivo;

CREATE VIEW elegidas_por_reactivo AS
SELECT ref_reactivo.id_examen as id_examen,
       reactivo.id_reactivo as id_reactivo,
       opcion.id_opcion as id_opcion,
       correcta,
       contenido,
       opcion_elegida.id_opcion_elegida as id_opcion_elegida
FROM ref_reactivo
JOIN reactivo
ON ref_reactivo.id_reactivo = reactivo.id_reactivo
JOIN opcion
ON reactivo.id_reactivo = opcion.id_reactivo
LEFT JOIN opcion_elegida
ON ref_reactivo.id_ref_reactivo = opcion_elegida.id_ref_reactivo 
   AND opcion.id_opcion = opcion_elegida.id_opcion
ORDER BY ref_reactivo.id_examen, reactivo.id_reactivo, opcion.id_opcion;

INSERT INTO reactivo VALUES
  (4, true, 1, 2, 'Básico', 'Son los subgéneros del cuento y la novela.', true),
  (5, true, 1, 2, 'Básico', 'Son expresiones de sabiduría popular que utilizan el lenguaje en doble sentido.', false),
  (6, true, 1, 2, 'Básico', 'Es quien se encarga de relatar los sucesos de una historia en los cuentos o novelas.', false),
  (7, true, 2, 1, 'Básico', '¿Cuál es el valor absoluto del resultado de la siguiente operación? −8 + 3 =', false),
  (8, true, 2, 1, 'Básico', 'Es el 25 % de 133.', false),
  (9, true, 2, 1, 'Básico', 'Son figuras geométricas que están formadas por cuatro lados.', false);
  
INSERT INTO opcion 
(id_reactivo, correcta, contenido)
VALUES
(4, true, 'Policiaco, romántico,  y aventuras.'),
(4, true, 'Terror y  ciencia ficción'),
(4, false, 'Himno, epístola, égloga y canción.'),
(4, false, 'Diálogo, ensayo, biografía y cartas.'),
(5, false, 'Canciones.'),
(5, false, 'Fábulas.'),
(5, true, 'Refranes.'),
(5, false, 'Pregones.'),
(6, false, 'Personaje.'),
(6, false, 'Locutor.'),
(6, false, 'Expositor.'),
(6, true, 'Narrador.'),
(7, false, '-5'),
(7, true, '5'),
(7, false, '-11'),
(7, false, '11'),
(8, false, '99.75'),
(8, false, '32.35'),
(8, true, '33.25'),
(8, false, '97.59'),
(9, false, 'círculos'),
(9, false, 'triángulos'),
(9, false, 'polígonos'),
(9, true, 'cuadriláteros');

-- INSERT INTO ref_reactivo (id_examen, id_reactivo)
-- VALUES (1, 4), (1, 5);