#!/usr/bin/env bash

set -euo pipefail

DIRECTORY="$(pwd)"

if (( $# > 0 )); then
  DIRECTORY=$1
fi

if [[ ! -d "$DIRECTORY" ]]; then
  echo "El directorio no existe. :("
  exit 1
fi

docker run --name php \
           --rm -it \
           -p 80:80 \
           -v "$DIRECTORY:/opt/lampp/htdocs" \
           tomsik68/xampp:7

