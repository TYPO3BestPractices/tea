#!/bin/bash

[[ ! -e /.dockerenv ]] && exit 0

set -xe

apk add parallel
apk add icu-dev
docker-php-ext-configure intl
docker-php-ext-install intl
docker-php-ext-enable intl
docker-php-ext-install pdo_mysql mysqli
