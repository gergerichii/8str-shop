#!/usr/bin/env bash

if [ $1 ] && [ ! -d "$1" ]; then
    echo "Create folder $1";
    mkdir -p "$1";
fi;