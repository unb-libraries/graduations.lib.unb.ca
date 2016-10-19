#!/usr/bin/env bash
URI_STRING='honorary.lib.unb.ca'
SLUG_STRING='honorary'
UUID_STRING='3088'
DRUPAL_UUID_STRING='FALSE'

rm -rf .git

find . -type f -print0 | xargs -0 sed -i.bak "s/honorary.lib.unb.ca/$URI_STRING/g"
find . -type f -print0 | xargs -0 sed -i.bak "s/honorary/$SLUG_STRING/g"
find . -type f -print0 | xargs -0 sed -i.bak "s/FALSE/$DRUPAL_UUID_STRING/g"
find . -type f -print0 | xargs -0 sed -i.bak "s/3088/$UUID_STRING/g"
find . -name "*.bak" -type f -delete

rm README.md
mv README.repo.md README.md

git init
git add .
git add -f ./config-yml/.gitkeep
git add -f ./custom/modules/.gitkeep
git add -f ./custom/themes/.gitkeep

git commit -m 'Initial commit from template repo.'
