#!/usr/bin/env bash

set -euo pipefail

TARGET="/opt/lampp/htdocs/t"

if [[ ! -d "$(dirname "$TARGET")" ]]; then
  echo "No estás utilizando xampp en Linux :("
  exit 1
fi

echo "Agregando permisos a la cadena de directorios..."

CUR_DIR="$(pwd)"
while [[ "$CUR_DIR" != '/' ]]; do
  if [[ "$(stat --format '%A' "$CUR_DIR")" != *x ]]; then
    echo "chmod o+x $CUR_DIR"
    chmod o+x "$CUR_DIR"
  fi

  CUR_DIR="$(dirname "$CUR_DIR")"
done

if [[ -L "$TARGET" ]]; then
  echo "La carpeta ya está enlazada."
  exit 1
fi

echo "Enlazando directorio..."
sudo ln -s "$(pwd)" "$TARGET"

