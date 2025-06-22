#!/bin/bash
# This script should set up a CRON job to run cron.php every 5 minutes.
# You need to implement the CRON setup logic here.
CRON_PHP_PATH="$(pwd)/src/cron.php"
EXISTS=$(crontab -l 2>/dev/null | grep -F "$CRON_PHP_PATH")
if [ -z "$EXISTS" ]; then
  (crontab -l 2>/dev/null; echo "*/5 * * * * php $CRON_PHP_PATH") | crontab -
  echo "✅ CRON job added to run every 5 minutes."
else
  echo "⚠️ CRON job already exists."
fi