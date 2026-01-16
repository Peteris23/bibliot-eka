#!/bin/bash
cd /home/darkwizard/bibliot-eka
ln -sf "$PWD/storage/app/public" "$PWD/public/storage"
echo "Storage link created successfully!"
ls -la public/ | grep storage
