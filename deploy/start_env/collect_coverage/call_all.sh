#!/usr/bin/env bash

working_dir=`dirname $0`
CGI_CLIENT="${working_dir}/cgi-fcgi"
export LD_LIBRARY_PATH=${working_dir}

function fcgi_get()
{
    document_root=$1
    script_file=$2
    host=$3
    port=$4
    server_name=$5

    REQUEST_METHOD=GET \
    SERVER_NAME=${server_name} \
    DOCUMENT_ROOT=${document_root} \
    DOCUMENT_URI=${script} \
    SCRIPT_NAME=${script} \
    SCRIPT_FILENAME=${document_root}${script} \
    QUERY_STRING= \
    GATEWAY_INTERFACE=CGI/1.1 \
    REMOTE_ADDR = 127.0.0.1 \
    REQUEST_URI=${script}
    ${CGI_CLIENT} -bind -connect ${host}:${port}
}

function api_get()
{
    script=$1
    fcgi_get "/home/services/api.go2yd.com/htdocs/Website" ${script} 127.0.0.1 9000 "qa-int.go2yd.com/Website"
}

while [ "`ps -ax | grep php-fpm -c`" = 0 ]; do
    sleep 5;
done

sleep 10

for file in `find /home/services/api.go2yd.com/htdocs/Website/ | grep -oP '(?<=/home/services/api.go2yd.com/htdocs/Website).*\.php$'`; do
    api_get $file
done
