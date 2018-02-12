#!/usr/bin/env bash

function info {
  echo " "
  echo "--> $1"
  echo " "
}

info "Init project"
cd ~/sites
php init --env=Development --overwrite=y

