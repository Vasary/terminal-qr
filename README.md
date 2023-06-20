# Terminal

QR Terminal description

## Requirements
* PHP 8.2.3
* AMQP server
* Sqlite extension

## Install

### Local
1. Clone [sdk](https://vasary.github.io/tac-sdk) and run `./start`.
2. Call `./shell` and run composer install

### K8s
The project deploys via Helm chart. The chart is in ./helm directory. 
Ensure you uploaded project secrets and call `helm install --upgrade inventory -n take-a-cart ./`


## Single sing on
This service is using the Auth0 as a SSO. For each request API expects Authorization header with bearer token.

There are required environment variables for SSO.
```shell
SSO_DOMAIN=
SSO_CLIENT_ID=
SSO_SECRET=
SSO_COOKIE_SECRET=
SSO_AUDIENCE=
```

## Environment variables
```shell
APP_ENV=
APP_SECRET=
MQ_HOST=
MQ_PORT=
MQ_USER=
MQ_PASSWORD=
MQ_VHOST=
```

## Swagger
Follow this link to find API specifications
- https://vasary.github.io/tac-core
