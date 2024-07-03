#!/usr/bin/env bash
#
# We don't want to run drush commands if drupal isn't installed.
# Similarly, we don't want to attempt to run config-import if there aren't any config files to import
# @todo expand further to pass --uri for all sites, with an eye towards multisite
#

DRUSH_CMD="drush --root=/app/web --uri=https://develop-sr3snxi-7vmrylydihhra.us.platformsh.site"

if [ -n "$($DRUSH_CMD status --field=bootstrap)" ]; then
  echo "Running database sync..."
  $DRUSH_CMD sql-cli < /app/web/content.sql
  $DRUSH_CMD cache-rebuild
  $DRUSH_CMD updatedb
  if [ -n "$(ls $($DRUSH_CMD php:eval "echo realpath(Drupal\Core\Site\Settings::get('config_sync_directory'));")/*.yml 2>/dev/null)" ]; then
    $DRUSH_CMD config-import -y
  else
    echo "No config to import. Skipping."
  fi
else
  echo "Drupal not installed. Skipping standard Drupal deploy steps"
fi
