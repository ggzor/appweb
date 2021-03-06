DROP DATABASE IF EXISTS examenes;
CREATE DATABASE examenes;

USE examenes;

-- Tablas principales

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
  fecha DATETIME NOT NULL,
  id_tema INT NOT NULL,
  nivel ENUM('BASICO', 'INTERMEDIO', 'AVANZADO') NOT NULL,

  calificacion FLOAT DEFAULT NULL,
  cantidad_reactivos INT DEFAULT 0,

  FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario)
);

CREATE TABLE tema
(
  id_tema INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  nombre VARCHAR(50) NOT NULL UNIQUE,
  descripcion_tema TEXT NOT NULL,
  imagen_tema TEXT NOT NULL
);

CREATE TABLE reactivo
(
  id_reactivo INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  publicado BOOLEAN NOT NULL,
  id_creador INT NOT NULL,
  id_tema INT NOT NULL,
  fecha DATETIME NOT NULL,
  nivel ENUM('BASICO', 'INTERMEDIO', 'AVANZADO') NOT NULL,
  enunciado TEXT NOT NULL,
  multiple BOOLEAN NOT NULL DEFAULT false,

  FULLTEXT (enunciado),

  FOREIGN KEY (id_creador) REFERENCES usuarios(id_usuario),
  FOREIGN KEY (id_tema) REFERENCES tema(id_tema)
);

CREATE TABLE ref_reactivo
(
  id_ref_reactivo INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  id_examen INT NOT NULL,
  id_reactivo INT NOT NULL,

  UNIQUE KEY (id_examen, id_reactivo),

  FOREIGN KEY (id_examen) REFERENCES examen(id_examen) ON DELETE CASCADE,
  FOREIGN KEY (id_reactivo) REFERENCES reactivo(id_reactivo) ON DELETE CASCADE
);

CREATE TABLE opcion
(
  id_opcion INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  id_reactivo INT NOT NULL,
  correcta BOOLEAN NOT NULL,
  contenido TEXT NOT NULL,

  FOREIGN KEY (id_reactivo) REFERENCES reactivo(id_reactivo) ON DELETE CASCADE
);

CREATE TABLE opcion_elegida
(
  id_opcion_elegida INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  id_ref_reactivo INT NOT NULL,
  id_opcion INT NOT NULL,

  UNIQUE KEY (id_ref_reactivo, id_opcion),
  FOREIGN KEY (id_ref_reactivo) REFERENCES ref_reactivo(id_ref_reactivo) ON DELETE CASCADE,
  FOREIGN KEY (id_opcion) REFERENCES opcion(id_opcion) ON DELETE CASCADE
);

-- Views

CREATE VIEW reactivos_por_examen AS
SELECT id_examen,
       reactivo.id_reactivo as id_reactivo,
       ref_reactivo.id_ref_reactivo as id_ref_reactivo,
       nivel, tema.nombre as nombre_tema,
       enunciado,
       multiple
FROM ref_reactivo
JOIN reactivo
ON ref_reactivo.id_reactivo = reactivo.id_reactivo
JOIN tema
ON reactivo.id_tema = tema.id_tema
ORDER BY id_examen, ref_reactivo.id_ref_reactivo;

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
ORDER BY ref_reactivo.id_examen, ref_reactivo.id_ref_reactivo, opcion.id_opcion;

CREATE VIEW opciones_por_reactivo AS
SELECT reactivo.id_reactivo as id_reactivo,
       opcion.id_opcion as id_opcion,
       correcta,
       contenido
FROM reactivo
JOIN opcion
ON reactivo.id_reactivo = opcion.id_reactivo
ORDER BY opcion.id_opcion;

CREATE VIEW maximos_por_tema AS
SELECT id_tema, nivel, COUNT(*) as cantidad
FROM reactivo
WHERE reactivo.publicado
GROUP BY id_tema, nivel;


INSERT INTO usuarios
  (id_usuario, nombre, usuario, pass, pregunta, respuesta, tipo)
VALUES
  (1, 'Gustavo Cerati', 'admin1', 'Admin_01',
      '??Cu??l fue tu mejor ??lbum?', 'Siempre es hoy', 0),
  (2,'Felix Chan', 'admin2', 'Admin_02',
     '??D??nde vivo?', 'En mi casa', 0),
  (3, 'Axel', 'usuario1', 'Usuario_01',
      '??Qui??n es tu artista favorito?', 'Gustavo Cerati', 1),
  (4, 'Sol', 'usuario2', 'Usuario_02',
      '??Color favorito?', 'Azul', 1);

INSERT INTO tema
  (id_tema, nombre, descripcion_tema, imagen_tema)
VALUES
  (1, 'Matem??ticas',
      'Ejercicios que requieren de tu habilidad l??gica y de calcular para resolverlos.',
      'imagenes/temas/matematicas.png'),
  (2, 'Espa??ol',
      '??Qu?? tanto dominas tu propio lenguaje? ??Pru??balo!',
      'imagenes/temas/espanol.png');

-- Opci??n m??ltiple
INSERT INTO reactivo VALUES
  (1, true,
      1, 2, -- creador, tema
      NOW(),
      'BASICO',
      'Son algunos de los marcadores gr??ficos que se utilizan para organizar el contenido de un reglamento.',
      false); -- no multiple

INSERT INTO opcion (id_opcion, id_reactivo, correcta, contenido) VALUES
  (1, 1, true,  'Incisos, vi??etas, n??meros romanos y negritas.'),
  (2, 1, false, 'Puntos, comas, signos de interrogaci??n y signos de admiraci??n.'),
  (3, 1, false, 'Guion largo, guion corto y par??ntesis.'),
  (4, 1, false, 'P??rrafos, versos y estrofas.');

-- Verdadero/Falso
INSERT INTO reactivo VALUES
  (2, true,
      2, 1, -- creador, tema
      '2021-10-25 10:10:10',
      'AVANZADO',
      '??Las manecillas del reloj est??n en ??ngulo recto cuando marcan las tres?',
      false); -- no multiple

INSERT INTO opcion (id_opcion, id_reactivo, correcta, contenido)
VALUES
  (5, 2, true, 'Verdadero'),
  (6, 2, false,'Falso.');

-- M??ltiples opciones v??lidas
INSERT INTO reactivo VALUES
  (3, true,
      2, 1, -- creador, tema
      '2021-10-19 10:10:10',
      'INTERMEDIO',
      'Figuras geometr??cas con al menos 4 lados',
      true); -- multiple

INSERT INTO opcion (id_opcion, id_reactivo, correcta, contenido)
VALUES
  (7,  3, false, 'Triangulo'),
  (8,  3, true,  'Cuadrado'),
  (9,  3, false, 'Circulo'),
  (10, 3, true,  'Pent??gono');

INSERT INTO examen
  (id_examen, id_usuario, fecha, calificacion,
    id_tema, nivel, cantidad_reactivos)
VALUES
  (1, 3, NOW(), 8.8, 1, 'INTERMEDIO', 3),
  (2, 4, NOW(), NULL, 2, 'AVANZADO', 2);

INSERT INTO ref_reactivo
  (id_ref_reactivo, id_examen, id_reactivo)
VALUES
  (1, 1, 1),
  (2, 1, 2),
  (3, 1, 3),

  (4, 2, 2),
  (5, 2, 3);

INSERT INTO opcion_elegida
  (id_ref_reactivo, id_opcion)
VALUES
  -- Primer examen, contestado
  (1, 1),
  (2, 5),
  (3, 8),
  (3, 7),
  (4, 2);

-- VIEWS

-- Agregando m??s datos

INSERT INTO reactivo VALUES
  (4, true, 1, 2, '2021-10-03 08:10:10', 'AVANZADO', 'Son los subg??neros del cuento y la novela.', true),
  (5, true, 1, 2, '2021-10-25 10:20:10', 'INTERMEDIO', 'Son expresiones de sabidur??a popular que utilizan el lenguaje en doble sentido.', false),
  (6, true, 1, 2, '2021-10-20 02:08:10', 'BASICO', 'Es quien se encarga de relatar los sucesos de una historia en los cuentos o novelas.', false),
  (7, true, 2, 1, '2021-10-13 03:10:16', 'AVANZADO', '??Cu??l es el valor absoluto del resultado de la siguiente operaci??n? ???8 + 3 =', false),
  (8, true, 1, 1, '2021-10-25 14:14:10', 'INTERMEDIO', 'Es el 25 % de 133.', false),
  (9, true, 2, 1, '2021-10-26 09:10:15', 'BASICO', 'Son figuras geom??tricas que est??n formadas por cuatro lados.', false);

INSERT INTO opcion
(id_reactivo, correcta, contenido)
VALUES
(4, true, 'Policiaco, rom??ntico,  y aventuras.'),
(4, true, 'Terror y  ciencia ficci??n'),
(4, false, 'Himno, ep??stola, ??gloga y canci??n.'),
(4, false, 'Di??logo, ensayo, biograf??a y cartas.'),
(5, false, 'Canciones.'),
(5, false, 'F??bulas.'),
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
(9, false, 'c??rculos'),
(9, false, 'tri??ngulos'),
(9, false, 'pol??gonos'),
(9, true, 'cuadril??teros');

INSERT INTO ref_reactivo
  (id_ref_reactivo, id_examen, id_reactivo)
VALUES
  (6, 1, 8);

INSERT INTO opcion_elegida
  (id_ref_reactivo, id_opcion)
VALUES
  -- Primer examen, contestado
  (6, 28);

-- INSERT INTO ref_reactivo (id_examen, id_reactivo)
-- VALUES (1, 4), (1, 5);
