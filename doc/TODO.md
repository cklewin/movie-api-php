# TODO
- [ ] Amazon EC2 instances
  - [ ] Jenkins for testing and deployment
  - [ ] API frontend: Docker container - Apache/PHP
- [ ] Amazon RDB
- [x] AWS Cognito user pool for testing

- [ ] Build working API
  - [ ] Authorize user
  - [ ] Sanitize inputs
  - [ ] Verify input parameters
  - [ ] Respond to API methods (Create, Read, Update, Delete, List)
  - [ ] Save changes to database

- [x] Restrict API access to authenticated users
  - [x] AWS Cognito user pool
  - [x] Generate token: see [COGNITO-JWT-SETUP.md](COGNITO-JWT-SETUP.md)
  - [x] Verify token using libraries from [pmill/aws-cognito](https://github.com/pmill/aws-cognito)

- [ ] Deployment
  - [ ] Github webhook -> Jenkins
  - [ ] Deploy to a staging
    - [ ] Run verification tests
    - [ ] Alert
  - [ ] Deploy to production

- [ ] Query a third party API to get additional movie data

## Further considerations
- The JWT library I'm using doesn't cache the JWK set
- Using nginx as the front end for PHP will have better performance
- Caching will increase performance, memcached
- Needs centralized logging and monitoring
- Rate limiting should be enabled to prevent abuse
- The API only cares about the base URL, so trailing slashes and other parts in the path are silently ignored - an error message would be a good idea to maintain consistency
- SSL encryption should be enabled
  - Self-signed certificate
  - Letsencrypt
  - Other CA
  - The infrastructure creation could be codified
    - Terraform
    - Kubernetes
    - Docker Swarm
    - AWS Cloudformation
    - AWS EKS
    - AWS API Gateway
    - AWS Lambda
