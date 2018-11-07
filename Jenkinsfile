pipeline {
    agent any
    options {
        disableConcurrentBuilds()
        timestamps()
        buildDiscarder(logRotator(numToKeepStr: '1'))
    }
    environment {
        BUILD_FOLDER = "${'builds/' + env.BRANCH_NAME + '/' + env.BUILD_NUMBER}"
        IMAGE_NAME = "${'movie-api-php.' + env.BRANCH_NAME + '.' + env.BUILD_NUMBER}"
        SSH_CMD = "${'ssh ' + env.DEPLOY_USER + '@' + env.DEPLOY_HOST}"
    }
    stages {
        stage('Prepare') {
            steps {
                checkout scm
                sh '${SSH_CMD} "mkdir -p ~/${BUILD_FOLDER}"'
                sh 'tar --exclude .git -cpf - . |${SSH_CMD} "tar -C ~/${BUILD_FOLDER} -xpf -"'
            }
        }
        stage('Build') {
            steps {
                // this step is only needed if the src/vendor folder is left out of the repo
                //sh '${SSH_CMD} "cd ~/${BUILD_FOLDER} && ./composer-install.sh"'
                sh '${SSH_CMD} "docker build -t ${IMAGE_NAME} ~/${BUILD_FOLDER}"'
            }
        }
        stage('Test') {
            environment {
                RUNNING_CONTAINER = sh(returnStdout: true, script: '${SSH_CMD} "docker container ls -f publish=8080/tcp -q"').trim()
		MYSQL_PASSWORD = credentials('MYSQL_PASSWORD') 
            }
            steps {
                script {
                    // if a container is already running, we can stop it or fail the test
                    if (env.RUNNING_CONTAINER) {
                        //sh '${SSH_CMD} "docker stop ${RUNNING_CONTAINER}"'
                        echo "cannot run tests because a container is already running on port 8080"

                        // will this still run our cleanup?
                        exit 1
                    }
                }
                sh '${SSH_CMD} "docker run -d -e \'MYSQL_HOST_READ=${MYSQL_HOST_READ}\' -e \'MYSQL_HOST_WRITE=${MYSQL_HOST_WRITE}\' -e \'MYSQL_DATABASE=${MYSQL_DATABASE}\' -e \'MYSQL_USER=${MYSQL_USER}\' -e \'MYSQL_PASSWORD=${MYSQL_PASSWORD}\' --rm --name ${IMAGE_NAME} -p 8080:80 ${IMAGE_NAME}"'
                sh 'sleep 10'
                // TODO: add a retry to test for port 8080 connection
                // TODO: run curl tests
                // TODO: how will we test JWT authentication?
                //	- generate a token from Cognito user pool?
                //	- force test user?
                echo 'Run tests'
                sh '${SSH_CMD} "docker stop ${IMAGE_NAME}"'

                // this should always run if we built the image - unless the branch is master, then we'll keep it for production deployment
                //script {
                //}
            }
        }
        stage('Deploy') {
            when {
                branch 'master'
            }
            environment {
                // get original deploy container
                PREVIOUS_CONTAINER = sh(returnStdout: true, script: '${SSH_CMD} "docker container ls -f publish=80/tcp -q"').trim()
		MYSQL_PASSWORD = credentials('MYSQL_PASSWORD') 
            }
            steps {
                script {
                    // TODO: the service is down for about 1 second here, there's a better way
                    // stop original deploy container
                    if (env.PREVIOUS_CONTAINER) {
                        sh '${SSH_CMD} "docker stop ${PREVIOUS_CONTAINER}"'
                    }
                }
                // start new container
                sh '${SSH_CMD} "docker run -d -e \'MYSQL_HOST_READ=${MYSQL_HOST_READ}\' -e \'MYSQL_HOST_WRITE=${MYSQL_HOST_WRITE}\' -e \'MYSQL_DATABASE=${MYSQL_DATABASE}\' -e \'MYSQL_USER=${MYSQL_USER}\' -e \'MYSQL_PASSWORD=${MYSQL_PASSWORD}\' --name ${IMAGE_NAME} -p 80:80 ${IMAGE_NAME}"'

                // TODO: run final tests

                // TODO: switch back to original container if test fails

                // TODO: clean up the original container if test passes?

                echo 'Deploy to production complete'
            }
        }
    }
    post {
        always {
            // TODO: want to cleanup, but is this safe?
            sh '${SSH_CMD} "rm -r ~/${BUILD_FOLDER}"'

            // TODO: also cleanup docker images if not the master branch
            script {
                // stop original deploy container
                if (env.BRANCH_NAME != 'master') {
                    sh '${SSH_CMD} "docker image rm ${IMAGE_NAME}"'
                }
            }
        }
    }
}
