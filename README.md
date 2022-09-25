# Description
The service collects co2 measurements from different sensors. Sensor has statuses: OK, WARN, ALERT.
- OK - 3 last measurements under 2000
- WARN - at least 1 of the last measurements more or equal 2000
- ALERT - at least 3 of the last measurements more or equal 2000

This is not a production ready application, but rather an example, how to use different patterns/technologies to build a service for a certain purpose.

# In application are used:

## Hexagonal architecture
We have there 3 layers:
- Port - REST controllers, database dependency (repositories, mappings, migrations), Application/Domain event listeners
- Application - commands with command handlers, repository interfaces
- Domain - Domain models, Domain events

Dependency graph:

Port depends on <- Application depends on <- Domain

## Command Query Request Segregation
Reading flow:
1. HTTP request goes to REST Controller, which calls read repository
2. Repository returns data and REST controller transforms them into JSON response

Writing flow:
1. HTTP request goes to REST Controller, which executes command
2. Command Handler [calls Domain method] and write repository. [Application Event is thrown]
3. Domain model processes domain method call from point 2 [and throws a Domain event]
4. REST controller returns a response with the status of an operation

Symfony messenger is used to implement command execution.

## Domain Driven Design
co2 sensor behavior from the [Description](#Description) is mirrored by Sensor model

## Event Driven Architecture
Application/Domain events are used to inform other parts of the application, that something was changed.
Events are used to trigger different scenarios. Event listeners connect those scenarios.

Symfony implementation of EventDispatcherInterface is used to dispatch and handle events.

# Testing
We have 3 types of the tests here:
- Unit tests - to insure, that an isolated part of the code (method) works as intended. Example: Domain method test.
- Integration tests - to insure, that the combination of classes (application parts) works as intended. Example: Repository method test.
- Application tests - to insure, that the whole application scenario works as intended. Examples: REST controller method test.

## Installation
1. Install [Docker](https://www.docker.com/)
2. Install [Task](https://taskfile.dev/)
3. Download source code of the application to local some directory
4. Open a directory in CLI and run `docker-compose up -d`
5. After all services are started run `task login`. It opens a CLI to PHP container with the application
6. Run `composer install`
7. After installation of all composer packages run `symfony serve`

## Usage
   
### Providing measurements to the service:
```
POST http://localhost:8000/api/v1/sensors/137e3b0b-2cc1-40b1-aa53-843b9d05775e/measurements
Content-Type: application/json

{
  "co2": 1800,
  "time": "2022-09-25T10:31:47+00:00"
}
```
#### Get sensor status:
```
GET http://localhost:8000/api/v1/sensors/137e3b0b-2cc1-40b1-aa53-843b9d05775e
```
#### Get sensor metrics:
```
GET http://localhost:8000/api/v1/sensors/137e3b0b-2cc1-40b1-aa53-843b9d05775e/metrics
```
#### Get sensor alerts:
```
GET http://localhost:8000/api/v1/sensors/137e3b0b-2cc1-40b1-aa53-843b9d05775e/alerts
```

## Development
1. Do all the steps from [Installation](#Installation) except 7
2. Do some changes
3. Run `task check-code` to perform code formatting, dependencies checks and run tests