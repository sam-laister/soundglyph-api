#!/bin/bash

# Install Node modules
cd /usr/src/app
echo "Running NPM install..."
npm install
echo "Node modules installed."

# Running vite
echo "Starting npm run dev..."
npx vite dev --port 3000 --host 0.0.0.0
# npm run dev