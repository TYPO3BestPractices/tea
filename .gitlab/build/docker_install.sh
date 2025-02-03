#!/bin/bash

[[ ! -e /.dockerenv ]] && exit 0

set -xe

apk add parallel
