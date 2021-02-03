#!/usr/bin/env bash

working_dir=`dirname $0`

CGI_CLIENT="${working_dir}/cgi-fcgi"

function log()
{
    echo "[`date +%Y-%m-%d %H:%M:%S`] $@"
}

export LD_LIBRARY_PATH=${working_dir}
if [ ! -x "${CGI_CLIENT}" ]
then
    log "cgi client not found or not executable"
    exit 1
fi

function fcgi_get()
{
    document_root=$1
    script_file=$2
    host=$3
    port=$4
    server_name=$5

    REQUEST_URI=${script} \
    SERVER_ADDR=$3 \
    REQUEST_METHOD=GET \
    SERVER_NAME=${server_name} \
    DOCUMENT_ROOT=${document_root} \
    DOCUMENT_URI=${script} \
    SCRIPT_NAME=${script} \
    SCRIPT_FILENAME=${document_root}${script} \
    QUERY_STRING= \
    REMOTE_ADDR=127.0.0.1 \
    GATEWAY_INTERFACE=CGI/1.1 \
    ${CGI_CLIENT} -bind -connect ${host}:${port}
}

function api_get()
{
    script=$1
    fcgi_get "/home/services/api.go2yd.com/htdocs/Website" ${script} 127.0.0.1 $2 "a3-local.go2yd.com"
}

result=`api_get "/config/SystimeSyncAction.php" $1 | awk -F"\r" '{print $NF}'`
check=`echo ${result} | grep "success"`

if [ "" = "${check}" ]
then
    echo ${result}
    exit 1
else
    echo "success"
fi
