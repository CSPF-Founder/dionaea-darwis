# Dionaea-DARWIS Setup

## Installing the Setup

Download the repository via
```bash
git clone https://github.com/CSPF-Founder/dionaea-darwis.git
```
Or to download it as a zip file, click on 'Code' in the top right corner and then select 'Download ZIP'.

cd into the folder that is created.

In the project folder run the below command.
```bash
./install.sh
```

## Accessing the Panel

Once all the 3 docker containers are up, the Honeypot panel is available on the URL: https://localhost:12443.

For information on how to use the panel, please refer to [Manual.md](Manual.md)

## To stop the docker

To stop the honeypot containers, go to the project folder and run the below command

```bash
docker compose down
```

## To start/restart the docker (in case if it is stopped)

To restart the containers, go to the project folder and run the below command

```bash
docker compose up -d 
```

## Other info:

The samples are shared between dionaea and the main code using the ./samples folder.

The management panel can be accessed via 

https://localhost:12443


## Exposing Dionaea

Dionaea will listen on these ports: 443,80,21,445. These should be exposed to the network on which you expect the attack to happen.


## Contributors

Sabari Selvan

Suriya Prakash
