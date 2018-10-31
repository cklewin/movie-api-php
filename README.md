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

Build Docker image
`docker build -t movie-api-php .`

Start Docker container
`docker run -p 80:80 -d -v $(pwd)/src:/var/www movie-api-php`

## API usage
See [USAGE.md](doc/USAGE.md)

## Exercise overview
* [x] Start by briefly documenting the technology stack of your choosing. Let us know what component you’ve chosen for each layer and why.
* [x] Setup a source code repository where we can watch your progress. GitHub or Bitbucket are fine.
* [ ] You’ll need to deploy your application to a hosting service of your choosing (AWS, DigitalOcean, Azure, etc). Free tiers should be sufficient.

## Requirements
* [ ] The service must be accessible over http using a command line interface (curl, node, etc.); a GUI is not necessary.
* [x] Add an authentication method to restrict access to the repository. Only users that need access to the service should be able to access it.
* [ ] The service must create, read, update, delete, and list movies in the collection.
* [ ] Each movie in the collection needs the following attributes:
  * [ ] Title [text; length between 1 and 50 characters]
  * [ ] Format [text; allowable values “VHS”, “DVD”, “Streaming”]
  * [ ] Length [time; value between 0 and 500 minutes]
  * [ ] Release Year [integer; value between 1800 and 2100]
  * [ ] Rating [integer; value between 1 and 5]
* [ ] On the collection list request, the items in the list must be sortable by movie attributes.
* [ ] Integrate a third-party web service relevant to the project.

## Extra credit (none, any, or all)
* [ ] Implement a build tool of your choosing (CloudFormation in AWS, etc)
* [ ] Integrate a testing suite of some sort

## Keep in mind
* We want to see your progress, not just a finished product. Email us your source code repository and a link to your application instance as soon as you have them setup.
* Stay in communication with us (ask questions, give status updates). This is part of the challenge.
