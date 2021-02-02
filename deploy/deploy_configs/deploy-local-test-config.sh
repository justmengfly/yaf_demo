#!/usr/bin/env bash

# Start commands for each container, one cmd a line
START_CMDS="
cd /home/services/api.go2yd.com/htdocs/Website && composer install && cd /home/services && ${start_cmd}
"

# QA_PRE_START_CMD='
# export collect_coverage=true
# '

# Container names for each container, one name a line, same order with $start_cmds
CONTAINER_NAMES="
api-localside-${env}
"

# Port maps for each container, one map a line, same order with $start_cmds
DOCKER_PORT_MAPS="
9008:9008
"

# This is for changing container name, remove old containers when deploy new one
OLD_CONTAINER_NAMES="
api-localside-${env}
"

# Volumn maps for each container, one map a line, same order with $start_cmds
DOCKER_VOLUMN_MAPS="
/home/worker/_logs/api-localside-${env}:/home/services/api.go2yd.com/logs
"

# Other docker run options
DOCKER_RUN_OPTIONS="--cap-add SYS_PTRACE --privileged"
# Image name
IMAGE_NAME="docker2.yidian.com:5000/publish/${COMMIT_JOB}-${COMMIT_NUMBER}-image"
# This is for stopping container, kill sepicify process inside the container before 'docker stop' and 'docker rm'
DOCKER_PRESTOP_CMD='mv /var/lib/logrotate.status /home/services/api.go2yd.com/logs/logrotate.status'
# Service port for apitest
SERVICE_PORT="9008"
# Service port inside container
ORIGIN_SERVICE_PORT="9008"
