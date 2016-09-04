#!/bin/bash

rm -rf files/nd-php
cp -r "`cat .repos_path`" files/
docker build -t ndphp/testing:latest .

