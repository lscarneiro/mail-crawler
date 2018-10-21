# mail-crawler

This project was developed as a coding exercise using Laravel, Angular and Docker

# How to run

**First you need to make sure you have docker, docker-compose, npm and angular-cli installed**

After cloning the repo `cd` into it and execute these steps:

`cd front/`

`npm install`

`ng build --prod`

`cd ..`

`docker-compose up -d --build`

If for any reasons you don't have all 4 containers running after `up`, do a `docker-compose restart` (this could happen because the database took too long to wake up, which occurred to me sometimes)

After that, you should have the admin working at `http://localhost:8002` and the api at `http://localhost:8003`.
The console application (which is the crawler itself) is running on background and feeding the database.

To login access `http://localhost:8002` and use email: `audima@audima.com` and password: `audima`
