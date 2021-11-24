#!/usr/bin/env bash

cat database.sql \
    <(python generar_reactivos.py) \
    triggers_procedures.sql \
  | /opt/lampp/bin/mysql -u root
