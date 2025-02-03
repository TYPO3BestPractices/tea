#!/bin/bash

[[ ! -e /.dockerenv ]] && exit 0

set -xe

apk add parallel
docker-php-ext-configure intl
docker-php-ext-install intl
docker-php-ext-enable intl
