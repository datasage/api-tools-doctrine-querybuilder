#!/bin/sh
# .laminas-ci/pre-install.sh

apt install ssh -y
ssh-keyscan -H github.com >> ~/.ssh/known_hosts