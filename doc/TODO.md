# TODO
- [x] Amazon EC2 instances
  - [x] Jenkins for testing and deployment
  - [x] API frontend: Docker container - Apache/PHP
- [x] Amazon RDS
- [x] AWS Cognito user pool for testing

- [x] Build working API
  - [x] Authorize user
  - [x] Sanitize inputs
  - [x] Verify input parameters
  - [x] Respond to API methods (Create, Read, Update, Delete, List)
  - [ ] Enable sorting on List
  - [x] Save changes to database

- [x] Restrict API access to authenticated users
  - [x] AWS Cognito user pool
  - [x] Generate token: see [COGNITO-JWT-SETUP.md](COGNITO-JWT-SETUP.md)
  - [x] Verify token using libraries from [pmill/aws-cognito](https://github.com/pmill/aws-cognito)

- [x] Deployment
  - [ ] Github webhook -> Jenkins
    - would need to enable access, Jenkins is not open to public
  - [x] Deploy to a staging
    - [ ] (optional) Run verification tests
    - [ ] (optional) Alert
  - [x] Deploy to production
    - [ ] (optional) Run verification tests
    - [ ] (optional) Revert to previous if tests fail
    - [ ] (optional) Alert

- [ ] Query a third party API to get additional movie data

## Further considerations
- The JWT library I'm using doesn't cache the JWK set
- Using nginx as the front end for PHP will have better performance
- Caching will increase performance, memcached
- Rate limiting should be enabled to prevent abuse
- The Docker image rebuilds the PHP composer requirements.  This takes time and I don't think this is necessary and would be better to include the /vendor folder in the repo.
- The API only cares about the base URL, so trailing slashes and other parts in the path are silently ignored - an error message would be a good idea to maintain consistency
- Needs centralized logging and monitoring
- SSL encryption should be enabled
  - Self-signed certificate
  - Letsencrypt
  - Other CA
- The infrastructure creation could be codified and fault-tolerant
  - Terraform
  - Kubernetes
  - Docker Swarm
  - AWS Cloudformation
  - AWS Auto scaling
  - AWS EKS
  - AWS API Gateway
  - AWS Lambda
