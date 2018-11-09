## Simple PHP REST API example
Uses JWT tokens for authentication against an AWS Cognito user pool

## Requirements
- 1 AWS instance for the frontend API
  - will run an Apache/PHP docker container for the front-end
- 1 AWS instance for Jenkins deployment
  - to test and deploy from GitHub with a webhook trigger
- 1 AWS RDS MySQL database
  - for persistent storage
- 1 AWS Cognito user pool
  - for testing JWT authentication

## Progress
See [TODO.md](doc/TODO.md)

## Running in development
Install PHP requirements with composer
`./composer-install.sh`

Start the Docker services
`docker-compose up`

This will start a MySQL server for development and a web server at http://localhost/

## API usage
See [USAGE.md](doc/USAGE.md)

## Exercise overview
* [x] Start by briefly documenting the technology stack of your choosing. Let us know what component you’ve chosen for each layer and why.
* [x] Setup a source code repository where we can watch your progress. GitHub or Bitbucket are fine.
* [x] You’ll need to deploy your application to a hosting service of your choosing (AWS, DigitalOcean, Azure, etc). Free tiers should be sufficient.

## Requirements
* [x] The service must be accessible over http using a command line interface (curl, node, etc.); a GUI is not necessary.
* [x] Add an authentication method to restrict access to the repository. Only users that need access to the service should be able to access it.
* [x] The service must create, read, update, delete, and list movies in the collection.
* [x] Each movie in the collection needs the following attributes:
  * [x] Title [text; length between 1 and 50 characters]
  * [x] Format [text; allowable values “VHS”, “DVD”, “Streaming”]
  * [x] Length [time; value between 0 and 500 minutes]
  * [x] Release Year [integer; value between 1800 and 2100]
  * [x] Rating [integer; value between 1 and 5]
* [x] On the collection list request, the items in the list must be sortable by movie attributes.
* [x] Integrate a third-party web service relevant to the project.

## Optional
* [ ] Implement a build tool of your choosing (CloudFormation in AWS, etc)
* [ ] Integrate a testing suite of some sort
