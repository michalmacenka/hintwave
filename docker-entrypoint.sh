#!/bin/bash

# Replace ${PORT} with the actual port in Apache configs
sed -i "s/\${PORT}/$PORT/g" /etc/apache2/ports.conf
sed -i "s/\${PORT}/$PORT/g" /etc/apache2/sites-available/000-default.conf

# Execute the main container command
exec "$@" 