#
# runs composer install in separate Docker container for dependencies
# to keep the production environment clean
#
docker run --rm -v $(pwd)/src:/app --user $(id -u):$(id -g) composer install --ignore-platform-reqs
