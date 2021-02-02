CONTAINER_NAME="open_website_local"
docker rm -f ${CONTAINER_NAME}
source deploy_configs/deploy-test-config.sh
source dockerfile/assemble-yidian-config.sh
#echo $(pwd)
#docker run -td -p ${SERVICE_PORT}:${ORIGIN_SERVICE_PORT} -v $(pwd)""
PROJECT_DIR_HOST="$(dirname $(pwd))"
PROJECT_DIR_CONTAINER="/home/services/api.go2yd.com/"
docker run --name ${CONTAINER_NAME} -td  -p ${SERVICE_PORT}:${ORIGIN_SERVICE_PORT} -v ${PROJECT_DIR_HOST}:${PROJECT_DIR_CONTAINER}/htdocs/Website ${BASE_IMAGE} /bin/bash -c "/bin/bash ${PROJECT_DIR_CONTAINER}/htdocs/Website/deploy/local_start.sh;/bin/bash"