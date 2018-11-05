## 1. Create the Cognito user pool
see [COGNITO-JWT-SETUP.md](COGNITO-JWT-SETUP.md)

## 2. Create the RDS database
- MySQL 5.7.23
- db.t2.micro
- DB instance identifier: movies
- fill in the master username and password
- Next
- the default configuration will work, I enabled the following logs: 
  - Error log
  - General log
  - Slow query log
- Create database
- Instances
  - Connect
    - Endpoint
      - this is the hostname for the database

## 3. Create the Jenkins instance:
- Ubuntu Server 18.04 LTS (HVM), SSD Volume Type - ami-0ac019f4fcb7cb7e6
- t2.micro
- Name: jenkins-node
- Security group: jenkins
  - allow inbound port 80/tcp (restrict this to your IP address)
  - allow inbound port 22/tcp (restrict this to your IP address)
- Launch
- Install Docker, create a docker user, and start Jenkins container
```
sudo apt-get update -y
sudo apt-get install apt-transport-https ca-certificates curl software-properties-common
curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo apt-key add -
sudo apt-key fingerprint 0EBFCD88
sudo add-apt-repository "deb [arch=amd64] https://download.docker.com/linux/ubuntu $(lsb_release -cs) stable"
sudo apt-get update -y
sudo apt-get install docker-ce
sudo systemctl start docker
sudo useradd docker -d /home/docker -m -s /bin/bash -g docker
sudo su docker -c "docker run -d --rm --name jenkins -p 80:8080 -v jenkins-data:/var/jenkins_home jenkins/jenkins"
```
- Generate an SSH key to deploy to the web-node later
```
sudo su docker -c "docker exec -u jenkins -ti jenkins /bin/bash -c 'ssh-keygen -b 4096'"
```
- Get the web-node host key
```
sudo su docker -c "docker exec -u jenkins -ti jenkins /bin/bash -c 'ssh docker@your_web-node_private-ip'"
```
- Get the SSH public key from the pair you just generated
```
sudo su docker -c "docker exec -u jenkins -ti jenkins /bin/bash -c 'cat ~/.ssh/id_rsa.pub'"
```

## 4. Create the front-end API server using:
- Ubuntu Server 18.04 LTS (HVM), SSD Volume Type - ami-0ac019f4fcb7cb7e6
- t2.micro
- Name: web-node
- Security group: web servers
  - allow inbound port 80/tcp
  - allow inbound port 8080/tcp (restrict this to the jenkins security group)
  - allow inbound port 22/tcp (restrict this to your IP address and the jenkins security group)
- Launch
- Install Docker and create a docker user
```
sudo apt-get update -y
sudo apt-get install apt-transport-https ca-certificates curl software-properties-common
curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo apt-key add -
sudo apt-key fingerprint 0EBFCD88
sudo add-apt-repository "deb [arch=amd64] https://download.docker.com/linux/ubuntu $(lsb_release -cs) stable"
sudo apt-get update -y
sudo apt-get install docker-ce
sudo systemctl start docker
sudo useradd docker -d /home/docker -m -s /bin/bash -g docker
```
- Create ~docker/.ssh/authorized_keys containing the SSH public key from jenkins-node
- Set the file permissions
```
sudo chown -R docker:docker ~docker/.ssh
sudo chmod 700 ~docker
sudo chmod 500 ~docker/.ssh
sudo chmod 400 ~docker/.ssh/authorized_keys
```

## 5. Configure Jenkins 
Follow the container logs on the jenkins-node instance to get the Jenkins unlock key
```
sudo su docker -c "docker logs -f jenkins"
```

Go to http://your_jenkins-node_public_ip
- Install suggested plugins
- Create First Admin User
- Save and Continue
- Save and Finish
- Start using Jenkins
- Jenkins
  - Manage Jenkins
    - Configure System
      - Global properties
       - Add "DEPLOY_HOST"=your_web-node_private-ip
       - Add "DEPLOY_USER"=docker
      - System Admin email address: email@example.com
      - Save
  - New Item
    - General
      - Enter an item name: movie-api-php
      - Multibranch Pipeline
      - OK
    - I had trouble here, Jenkins barfed with a poorly formatted error message. I went to Manage Jenkins -> Manage Plugins -> Available -> Install without restart -> Checked restart -> Logged back in and selected this new job to continue the configuration.
    - Configure
      - Source Code Management
        - Branch Sources
          - Add source
            - GitHub
              - Owner: cklewin
              - Repository: movie-api-php
              - Behaviors
                - Discover branches strategy: Exclude branches that are also filed as PRs
                - Discover pull requests from origin: (Delete this)
                - Discover pull requests from forks: (Delete this)
    - Save

## Notes
- All these steps can be automated using Cloudformation or Terraform
- Can Cloudformation read the repo for changes to a Cloudformation yaml?
