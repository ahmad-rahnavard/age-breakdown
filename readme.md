## Notes

- The API endpoint which returns a JSON response with a breakdown of ages and percentage of the repeated ages
    - endpoint:  ```/api/v1/ages/breakdown```
    - ```POST``` request
    - Accepts ```application/json```
    - requires a CSV file with ```file``` name, including ```(string)name,(int)age``` in each line

- The "file data validation" and "handling the first line" as "header" should be handled in a real app!
- The structure of broken down ages is:
```json5
{"age": "percentage"} // e.g. {"22": 40}
```
which can be changed easily, according to what is needed in the api response!!!

like:
```json5
[{"age": 22, "percentage": 40},{...}]
```
or
```json
[
  {
    "age": 22,
    "count": 4,
    "percentage": "40%",
    "names": ["John", "Anna", "Ben", "Sofia"]
  },
  {
    ...
  }
]
```
- The json response structures which are defined in ```App\Providers\AppServiceProvider``` directory:
  
  ```json
  {
    "status" : 200,
    "message": "Success!",
    "data"   : []
  }
  ```
  for the successful results, and
  
  ```json
  {
    "status" : 400,
    "message": "Error!",
    "errors" : []
  }
  ```
  for the errors.
