#!/usr/bin/env bash

DIST_FILE_NAME="*.tar.gz"

PROJECT_DIR="api.go2yd.com"

START_SCRIPT="./start_env/start_api.sh"

SYNC_DATA_OPERATIONS="
tar zxf api-local.tar.gz -C start_env/api.go2yd.com/htdocs/Website
"

DEST_FILE_NAME=""

DEST_FILE_PATH=""

BASE_IMAGE="docker2.yidian.com:5000/centos7/php72_without_nginx:20210201"

MAINTAINER="zhangxiaojing \"zhangxiaojing@yidian-inc.com\""

HOME_DIR="/home/services"

LOG_DIRS="
${HOME_DIR}/${PROJECT_DIR}/logs
"

DATA_DIRS="
"
