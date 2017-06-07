#!/bin/bash

# Load configuration variables
if [ ! -f local.env ]; then
    echo "ERROR: Please create local.env (use local.env.dist for inspiration)."
    exit
fi
source local.env

echo "Creating Docker container"
result=`docker build -t $DOCKER_CONTAINER_NAME . | tail -n1`
echo $result

if [[ ${result} != *"Successfully"* ]];then
    echo "Build failed"
    exit
fi
id=`echo $result | cut -d ' ' -f3`

echo "Tagging and pushing to Docker hub"
docker tag $id $DOCKER_USER/$DOCKER_CONTAINER_NAME
result=`docker push $DOCKER_USER/$DOCKER_CONTAINER_NAME`
if [[ ${result} != *"latest"* ]];then
    echo "Push failed"
    echo $result
    exit
fi

echo "Updating OpenWhisk action '$OW_ACTION_NAME'"
wsk action update --docker $OW_ACTION_NAME $DOCKER_USER/$DOCKER_CONTAINER_NAME --web true

echo "Invoking OpenWhisk action: wsk action invoke -br $OW_ACTION_NAME --param name Everyone"
wsk action invoke -br $OW_ACTION_NAME --param name Everyone
