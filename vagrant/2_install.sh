#!/bin/bash
cd /app/builddocker/code/

git clone https://github.com/CSPF-Founder/dionaea-engine ./dionaea

TARGET_DIR="."
samples_dir="./samples"

if [ ! -d "$samples_dir" ]; then
    mkdir -p "$samples_dir"
fi

chmod 777 -R $samples_dir

generate_random_string() {
    characters="0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"

    length=32
    string=""
    for ((i=0; i<$length; i++)); do
        random_index=$((RANDOM % ${#characters}))
        string+=${characters:$random_index:1}
    done

    echo "$string"
}

generate_random_username() {
    characters="abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"

    length=32
    string=""
    for ((i=0; i<$length; i++)); do
        random_index=$((RANDOM % ${#characters}))
        string+=${characters:$random_index:1}
    done

    echo "$string"
}

mariadb_user=$(generate_random_username)
mariadb_password=$(generate_random_string)
sudo sed -i "s/EXAMPLE_DB_PASS_TO_REPLACE/${mariadb_password}/g" ./docker-compose.yml 
sudo sed -i "s/EXAMPLE_DB_PASS_TO_REPLACE/${mariadb_password}/g" ./darwis_panel/panel/config/web_config.env

sudo sed -i "s/EXAMPLE_DB_USER_TO_REPLACE/${mariadb_user}/g" ./docker-compose.yml 
sudo sed -i "s/EXAMPLE_DB_USER_TO_REPLACE/${mariadb_user}/g" ./darwis_panel/panel/config/web_config.env


# Generate self-signed certificate
 Set the common name for the certificate
SSL_COMMON_NAME="example.com"
# Set the certificate validity period in days
SSL_VALIDITY_DAYS=365

SSL_DIR="${TARGET_DIR}/darwis_panel/docker_resources/ssl_certs"

sudo mkdir "$SSL_DIR"

# Generate private key
sudo openssl genpkey -algorithm RSA -out "${SSL_DIR}/server.key"

# Generate certificate signing request (CSR)
sudo openssl req -new -key "${SSL_DIR}/server.key" -out "${SSL_DIR}/server.csr" -subj "/CN=${SSL_COMMON_NAME}"

# Generate a self-signed certificate using the CSR
sudo openssl x509 -req -in "${SSL_DIR}/server.csr" -signkey "${SSL_DIR}/server.key" -out "${SSL_DIR}/server.crt" -days ${SSL_VALIDITY_DAYS}

# Display generated certificate information
sudo openssl x509 -in "${SSL_DIR}/server.crt" -noout -text

# echo "Certificate and key files are generated in ${SSL_DIR} directory."

if docker compose version &> /dev/null ; then
    sudo docker compose up -d --build
elif docker-compose version &> /dev/null ; then
    sudo docker-compose up -d --build
else
    echo "Error: Neither 'docker-compose' nor 'docker compose' is available."
fi

echo "Setup completed"
