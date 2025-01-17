#!/usr/bin/env sh

# -e = exit when one command returns != 0, -v print each command before executing
set -ev

# Install chomedriver
curl -s -L -o chromedriver_linux64.zip https://chromedriver.storage.googleapis.com/2.40/chromedriver_linux64.zip \
    && unzip -o -d $HOME chromedriver_linux64.zip \
	&& chmod +x $HOME/chromedriver

# Install composer package
if [ ${HUMHUB_VERSION} = "v1.2" ]; then
    composer global require fxp/composer-asset-plugin
fi