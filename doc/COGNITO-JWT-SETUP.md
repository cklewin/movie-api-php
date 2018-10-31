We'll be using JWT to authorize access to the Movie API.

The user login is outside the scope of this project, so we'll setup a Cognito user pool to provide a simple login service the will generate the JWT.  These steps are here just to get going quickly, not recommended for production.

## Create AWS Cognito user pool
- AWS Console -> Cognito
  - Create a user pool
    - Pool name: movie-api
    - Step through settings
      - Attributes:
        - Uncheck "email"
      - Policies:
        - Uncheck all required
        - Select "Only allow administrators to create users"
        - Days to expire: 14
    - App clients
      - Add an app client
        - App client name: movie-api
        - Uncheck "Generate client secret"
    - Review
        - Create pool

  - App client settings
    - Check "Cognito user pool"
    - Callback URLs: https://cklewin-movieapi.auth.us-east-1.amazoncognito.com/
    - Check "Implicit grant"
    - Save changes

  - Domain name
    - Domain prefix: cklewin-movieapi
    - Save changes

## Retrieve token
Login to your pool at the following URL:
`https://cklewin-movieapi.auth.us-east-1.amazoncognito.com/login?response_type=token&client_id=6m57a4f066hr7mt51pier2ui8s&redirect_uri=https%3A%2F%2Fcklewin-movieapi.auth.us-east-1.amazoncognito.com%2F`

You'll be redirected to the redirect_uri that has the JWT in the "access_token" parameter
