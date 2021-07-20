# StreetGroupTechTest
Endpoints
## Routes
### /api/home-owners
- Valid methods POST
- Required only a CSV to be sent in the request

#### Headers that must be set

- Accept - application/json
- Content-Type - text/csv 

#### Return value
##### Status code: 201
```
{
    users: - array - this is an array of the User Entities data
}
```
##### Status code: 400/500
```
{
    message: - string - this is the error message
}
```
