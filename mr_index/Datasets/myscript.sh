#!/bin/bash

for json_file in `ls *.json`
do
  echo "importing $json_file"
  json_path="$(pwd)/$json_file"
  mongoimport --db IR --collection Index --file $json_path --jsonArray
done
