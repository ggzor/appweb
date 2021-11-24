-- TRIGGERS
DELIMITER //

-- Registrar fecha de modificación del reactivo.
CREATE TRIGGER modificar_fecha_insert BEFORE INSERT ON reactivo
FOR EACH ROW SET NEW.fecha = NOW(); //

CREATE TRIGGER modificar_fecha_update BEFORE UPDATE ON reactivo
FOR EACH ROW SET NEW.fecha = NOW(); //

CREATE PROCEDURE hacer_query(id_usuario INT, busqueda TEXT, tema INT, nivel VARCHAR(50))
  BEGIN
  SELECT * FROM reactivo
    WHERE reactivo.id_creador = id_usuario
      AND (tema = 0 OR reactivo.id_tema = tema)
      AND (nivel = 'TODOS' OR reactivo.nivel = nivel)
      AND (busqueda = '' OR (MATCH (enunciado) AGAINST (busqueda)))
    ORDER BY fecha DESC;
  END; //

CREATE FUNCTION crear_examen(
  id_usuario INT,
  id_tema INT,
  nivel VARCHAR(50),
  cantidad_reactivos INT) RETURNS INT
  BEGIN
    INSERT INTO examen
      (id_usuario, fecha, calificacion, cantidad_reactivos, id_tema, nivel)
    VALUES
      (id_usuario, NOW(), NULL, cantidad_reactivos, id_tema, nivel);

    SET @id_examen = LAST_INSERT_ID();

    INSERT INTO ref_reactivo (id_examen, id_reactivo)
      SELECT @id_examen as id_examen, id_reactivo FROM reactivo
      WHERE reactivo.id_tema = id_tema AND reactivo.nivel = nivel
        AND reactivo.publicado
      ORDER BY RAND()
      LIMIT cantidad_reactivos;

    RETURN @id_examen;
  END; //

DELIMITER ;