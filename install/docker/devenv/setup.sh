#!/bin/bash

if [[ ${#} -ne 2 ]]; then
	echo "Usage: ${0} <ndphp local repository> <docker dev dir>"
	echo ""
	echo "Example:"
	echo -e "\t# ${0} /home/user/repos/git/nd-php /opt/ndphp_dev"
	echo ""
	exit 1
fi

NDPHP_LOCAL_GIT_REPOS="${1}"
NDPHP_DOCKER_DEV_DIR="${2}"

mkdir -p "${NDPHP_DOCKER_DEV_DIR}"

cp -r install/* "${NDPHP_DOCKER_DEV_DIR}"

echo "${NDPHP_LOCAL_GIT_REPOS}" > "${NDPHP_DOCKER_DEV_DIR}"/.repos_path

chmod 755 "${NDPHP_DOCKER_DEV_DIR}"/build.sh

