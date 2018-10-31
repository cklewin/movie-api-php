## 1. Create the Cognito user pool
### TODO document steps to create Cognito user pool, this should be automated.

## 2. Create the RDS database
### TODO document steps to configure RDS.

## 3. Create the Jenkins instance using:
- Ubuntu Server 18.04 LTS (HVM), SSD Volume Type - ami-0ac019f4fcb7cb7e6
- t2.micro
- Name: jenkins
- Security group: jenkins
- Install Docker and start Jenkins container
  -```
sudo apt-get update
sudo apt-get install apt-transport-https ca-certificates curl software-properties-common
sudo apt-key fingerprint 0EBFCD88
sudo add-apt-repository "deb [arch=amd64] https://download.docker.com/linux/ubuntu $(lsb_release -cs) stable
sudo apt-get update
sudo apt-get install docker-ce
sudo systemctl start docker
sudo usermod -G docker ubuntu
docker run -d -p 8080:8080 jenkins/jenkins
```

### TODO document steps to configure Jenkins, do we need to persist jenkins home?

## 4. Create the frontend server
### TODO document steps to bring online the frontend instance.

## Notes
- All these steps can be automated using Cloudformation or Terraform
- Can Cloudformation read the repo for changes to a Cloudformation yaml?
