Login to the [cognito user pool](https://cklewin-movieapi.auth.us-east-1.amazoncognito.com/login?response_type=token&client_id=6m57a4f066hr7mt51pier2ui8s&redirect_uri=https%3A%2F%2Fcklewin-movieapi.auth.us-east-1.amazoncognito.com%2F)

You'll be redirected to a new URL after signing in, copy the value of the access_token query string parameter to pass in with the HTTP "Authentication" header.

`MOVIE_API_TOKEN=<access_token>`

The API will respond in JSON.

## Methods
### Create a movie
```
curl --header "Authentication: Bearer "$MOVIE_API_TOKEN \
-X POST \
-H "Content-Type: application/x-www-form-urlencoded" \
-d "title=Star Wars&format=VHS&length=127&release_year=1976&rating=5" \
http://localhost/api/v1/movies
```
- parameters: (all required)
  - title
    - (text, max 50 characters)
  - format
    - one of (DVD|VHS|Streaming)
  - length
    - (integer between 0 and 500)
  - release_year
    - (integer between 1800 and 2100)
  - rating
    - (integer between 1 and 5)
- JSON response
```
{
    "messages": [
        "movie created"
    ],
    "success": true,
    "movie_id": 24
}
```

### List all movies
```
curl --header "Authentication: Bearer "$MOVIE_API_TOKEN \
http://localhost/api/v1/movies
```
- parameters
  - sort_field
    - \(optional) default title
    - options [title|etc]
  - sort_dir
    - \(optional) default value: asc
    - options [asc|desc]
  - ```
curl --header "Authentication: Bearer "$MOVIE_API_TOKEN \
http://localhost/api/v1/movies?sort_field=title&sort_dir=desc
```
- JSON response
```
{
    "success": true,
    "data": [
        {
            "id": 1,
            "title": "Star Wars",
            "format": "VHS",
            "length": 243,
            "release_year": 1976,
            "rating": 5
        },
        {
            "id": 2,
            "title": "Lord of the Rings",
            "format": "DVD",
            "length": 359,
            "release_year": 2001,
            "rating": 4
        }
    ]
}
```

### Read a movie
```
curl --header "Authentication: Bearer "$MOVIE_API_TOKEN \
http://localhost/api/v1/movies/1
```
- JSON response
```
{
    "success": true,
    "data": [
        {
            "id": 1,
            "title": "Star Wars",
            "format": "VHS",
            "length": 243,
            "release_year": 1976,
            "rating": 5
        }
    ]
}
```

### Update a movie
curl --header "Authentication: Bearer "$MOVIE_API_TOKEN \
-X PUT \
-H "Content-Type: application/x-www-form-urlencoded" \
-d "release_year=1972&rating=4" \
http://localhost/api/v1/movies/24
```
- parameters: (at least one required)
  - title
    - (text, max 50 characters)
  - format
    - one of (DVD|VHS|Streaming)
  - length
    - (integer between 0 and 500)
  - release_year
    - (integer between 1800 and 2100)
  - rating
    - (integer between 1 and 5)
- JSON response
```
{
    "messages": [
        "movie updated"
    ],
    "success": true
}
```

### Delete a movie
```
curl --header "Authentication: Bearer "$MOVIE_API_TOKEN \
-X DELETE \
http://localhost/api/v1/movies/24
```
- JSON response
```
{
    "messages": [
        "movie deleted"
    ],
    "success": true
}
```
