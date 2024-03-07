# Setup

Clone this repoistory
```bash
git clone https://github.com/cspF-Founder/dionaea-darwis
```

First time setup
```bash
cd dionaea-darwis
./install.sh
```

Once all 3 containers started, now go to browser and open
```text
https://localhost:12443
```

It will take you to the setup page. Click "Setup" button, it will do base setup for the panel. If successful, then it will take to license activation page.

## Obtain Free License Key
You can obtain free Threat intel license key from CySecurity page.

(The Free license key will have limit of 1000 hash check and 100 file upload per day)

<https://cysecurity.co/panel/keys/request>


## Activation
Once you obtain the free license key, give the key in the activation page of the honeypot panel and proceed with further user creation

## To Stop
To stop the honeypot contains, type the following
```bash
docker compose down
```

## To start (in case if it is stopped)
To restart the containers
```bash
docker compose up -d 
```



## Other info:

The samples are shared between dionaea and the main code using the ./samples folder.

The management panel can be accessed via 

https://localhost:12443


## Exposing Dionae

Dionae will listen on these ports: 443,80,21,445. These should be exposed to the network on which you expect attack to happen. 
