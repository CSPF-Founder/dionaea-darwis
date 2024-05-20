# Dionaea-DARWIS Setup

## Prerequisites

1. Install Vagrant from the official site, https://developer.hashicorp.com/vagrant/downloads. 

- Please refer to this Installation guide if you face any issues during installation. https://developer.hashicorp.com/vagrant/docs/installation

2. Install Virtualbox from the official site, https://www.virtualbox.org/wiki/Downloads

## Installing the Setup

Download this repository via

```bash
git clone https://github.com/CSPF-Founder/dionaea-darwis.git
```
Or to download it as a zip file, click on `Code` in the top right corner and then select `Download ZIP`.

`cd` into the folder that is created.

### In Linux:

In the project folder run the below command.

```
chmod +x setupvm.sh

./setupvm.sh
```
During the installation and startup, it will ask you to select the bridge adapter interface as shown below:

> **Which interface should the network bridge to?.**

Please enter the corresponding number for the bridge interface option and press `enter`.

Once the vagrant installation is completed, it will automatically restart in Linux. 

### In Windows:

Go to the project folder on command prompt and then run the below commands.

```
vagrant up
```

During the installation and startup, it will ask you to select the bridge adapter interface as shown below:

> **Which interface should the network bridge to?.**

Please enter the corresponding number for the bridge interface option and press 'enter'.

After it has been completed, run the below command to reload the VM manually.

```
vagrant reload
```

## Accessing the Panel

The Honeypot panel is available on the URL: https://localhost:8443.

```
Note: If you want to change the port, you can change forwardport in the vagrantfile.
```

For information on how to use the panel, please refer to [Manual.md](Manual.md)

## Other info:

The samples are shared between dionaea and the main code using the ./samples folder.

The management panel can be accessed via url: https://localhost:8443

- If you want to start the VM after your computer restarts you can give `vargant up` on this folder or start from the virtualbox manager. 

- Once up you can access the VM by giving the command `vagrant ssh apiprotectorvm`


## Exposing Dionaea

Dionaea will listen on these ports: 21,80,443,445. These ports should be exposed to the network on which you expect the attack to happen.


## Contributors

Sabari Selvan

Suriya Prakash
