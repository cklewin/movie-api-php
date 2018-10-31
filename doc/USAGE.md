Login to the [cognito user pool](https://cklewin-movieapi.auth.us-east-1.amazoncognito.com/login?response_type=token&client_id=6m57a4f066hr7mt51pier2ui8s&redirect_uri=https%3A%2F%2Fcklewin-movieapi.auth.us-east-1.amazoncognito.com%2F)

You'll be redirected to a new URL after signing in, copy the value of the access_token query string parameter to pass in with the HTTP "Authentication" header.

`MOVIE_API_TOKEN=[access_token]`

## Methods
* Create a movie

* List all movies
`curl --header "Authentication: Bearer "$MOVIE_API_TOKEN http://localhost/api/v1/movies`
- parameters:
  - sort_field
    - \(optional) default title
    - options [title|etc]
  - sort_dir
    - \(optional) default value: asc
    - options [asc|desc]
  - `curl --header "Authentication: Bearer "$MOVIE_API_TOKEN http://localhost/api/v1/movies?sort_field=title&sort_dir=desc`

* Read a movie
`curl --header "Authentication: Bearer "$MOVIE_API_TOKEN http://localhost/api/v1/movies/1/`

* Update a movie

* Delete a movie
