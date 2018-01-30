#!/usr/bin/env bash

function info {
  echo " "
  echo "--> $1"
  echo " "
}

info "Init project"
cd ~/sites
./init --env=Development --overwrite=y

