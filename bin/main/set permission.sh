#!/bin/sh

cd ../..
sudo chmod -R a+rw var
sudo chmod -R a+rw .env*
#sudo chmod -R a+rw public/uploads
ls -l
