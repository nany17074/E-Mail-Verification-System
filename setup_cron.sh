#!/bin/bash

# Get absolute path to script directory
SCRIPT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" >/dev/null 2>&1 && pwd )"

# CRON job command (runs daily at 00:00)
CRON_JOB="0 0 * * * php \"$SCRIPT_DIR/cron.php\""

# Add to crontab if not exists
if ! crontab -l | grep -q "$CRON_JOB"; then
    (crontab -l ; echo "$CRON_JOB") | crontab -
    echo "CRON job configured: $CRON_JOB"
else
    echo "CRON job already exists"
fi
