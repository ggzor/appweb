#!/usr/bin/env bash

docker run --name php \
           --rm -it \
           -p 80:80 \
           -v "$(pwd):/opt/lampp/htdocs" \
           tomsik68/xampp:7

