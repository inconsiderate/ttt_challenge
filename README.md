
# Local dev setup instructions
- Clone this repo
- bring up the docker containers
  - `docker compose up --build`
- install composer packages
  - `docker exec -it symfony_app sh -c "composer install"`
- run doctrine migrations and populate local database
  - `docker exec -it symfony_app sh -c "php bin/console doctrine:migrations:migrate"`
- project should now be visible at http://localhost:9876
  
- example tests can be run in the containers with `docker exec -it symfony_app sh -c "vendor/bin/phpunit"`

# Notes
In a real world scenario I would ideally make the following additions/changes to this code and infra:
- auth/access tokens for API routes
- traefik or other reverse proxy with subdomain to allow this project to run concurrently with other local docker apps
- adminer (or phpmyadmin) container for quick access to DBs without exposing ports (ideally routed through traefik)
- frontend service to allow client to easily view and add jobs, volunteers, and relationships
- uniform build scripts (utilizing one of npm, bash, make, etc. hopefully the same tool across all company projects) to one-line bringing up the project locally.
- define .env files in secure storage for alt environments

# API Endpoints

### `/api/jobs` (GET)
Returns a json list of available jobs:
```json
{
    "name": "Front Desk",
    "description": "Manage the front desk, keep track of signed in guest and print entry badges. At http://contributetocommunity.org we organize events which would benefit the community. These range from organizing a yoga workshop, career fairs to food festivals always rely upon the willingness and generosity of our volunteers who want to give something back to the community. Please check the events calendar to view the job postings and do apply for one or more openings as per your availability!"
},
{
    "name": "Stage Organizer",
    "description": "Organize and arrange the props on the performance stage during the event. At http://contributetocommunity.org we organize events which would benefit the community. These range from organizing a yoga workshop, career fairs to food festivals always rely upon the willingness and generosity of our volunteers who want to give something back to the community. Please check the events calendar to view the job postings and do apply for one or more openings as per your availability!"
}
```


### `/api/jobs/{id}` (GET)
Returns a single job and the volunteers who are willing to perform that job:
```json
{
    "name": "Guest Coordinator",
    "description": "Provide information to the guests about events, locations & details. At http://contributetocommunity.org we organize events which would benefit the community. These range from organizing a yoga workshop, career fairs to food festivals always rely upon the willingness and generosity of our volunteers who want to give something back to the community. Please check the events calendar to view the job postings and do apply for one or more openings as per your availability!",
    "volunteers": [
        "Ethyl Donnelly",
        "Freeda Koelpin",
        "Gloria Williamson",
        "Gordon Kirlin",
        "Isabell Prosacco",
        "Jacky Renner",
        "Laverna D'Amore",
        "Lonnie Kreiger",
        "Muriel Metz",
        "Nat Conn",
        "Orin Bahringer",
        "Pascale Hayes",
        "Phoebe Collier",
        "Sabryna Schowalter",
        "Viola Wisozk"
    ]
}
```

### `/api/jobs/create` (POST)
Consumes submitted json create a new job.

Required fields: name, description

```bash
curl -X POST http://localhost:9876/api/jobs/create \
-H "Content-Type: application/json" \
-d '{"name": "New Job Title", "description": "Details about the job."}'
```



### `/api/volunteers?page={page}&pageSize={pageSize}` (GET)
Returns a paginated json list of volunteers and all jobs each has applied to:
```json
{
    "full_name": "Barry Towne",
    "applied_jobs": [
        "Stage Organizer",
        "General Supervisor"
    ]
},
{
    "full_name": "Ethyl Donnelly",
    "applied_jobs": [
        "Guest Coordinator",
        "Food Services"
    ]
}
```

### `/api/volunteers/{id}` (GET)
Returns a single volunteer and all jobs they've applied to:
```json
{
    "name": "Barry Towne",
    "jobs": [
        "Stage Organizer",
        "General Supervisor"
    ]
}
```

### `/api/volunteers/create` (POST)
Consumes submitted json create a new volunteer.

Required fields: first_name, last_name

```bash
curl -X POST http://localhost:9876/api/volunteers/create \
-H "Content-Type: application/json" \
-d '{"first_name": "Alexis", "last_name": "Rose"}'
```




# Challenge Instructions

ContributeToCommunity.org is a place where people volunteer for various community events happening in the locality. The organization posts volunteering positions through various media platforms and ingest data through multiple channels to maintain its central database.

The org has received an excel file from one of the media partners which has data for open volunteering positions and list of people who have applied for those positions for pre-holiday season lunch/entertainment event

## Objective
The objective of this exercise is to create technical architecture documentation and a small application which will:

1. Load data into the relational database with correct relationships
2. Create API call(s) to provide :
    - A list of volunteers applied for a given job id
    - A paginated list of volunteers with the position details they applied for
    - Create an API call to create a new job record
## Guidelines:
- Use Symfony(7.0 or later) with PHP 8.3
- Utilize Doctrine to define data model and entities. Use MySQL as DB engine.
- The data load need not be part of the code itself, but do mention how you loaded data.
- The APIs need not be secured
- Write PHPUnit tests
- Docker containerize application code as well as database
- Provide enough documentation to execute the project locally
- Make fair assumptions wherever necessary and mention those in the documentation
