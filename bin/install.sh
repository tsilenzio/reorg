#!/usr/bin/env bash

# Install Essential Tools
sudo apt-get install -q -y --allow-unauthenticated wget curl

# Install Docker
wget -q -O - https://get.docker.io/gpg | sudo apt-key add -
echo "deb https://apt.dockerproject.org/repo ubuntu-xenial main" | sudo tee /etc/apt/sources.list.d/docker.list > /dev/null
sudo apt-get update -qq
sudo apt-get install -q -y --allow-unauthenticated linux-image-extra-$(uname -r) linux-image-extra-virtual docker-engine
# sudo gpasswd -a vagrant docker
# sudo systemctl restart docker

# Install Docker compose
wget -q -O - https://github.com/docker/compose/releases/download/1.8.0/docker-compose-`uname -s`-`uname -m` | sudo tee /usr/bin/docker-compose > /dev/null
sudo chmod +x /usr/bin/docker-compose
