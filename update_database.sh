#!/bin/bash

latest=$(curl -s -H "Accept: application/vnd.github+json" -H "X-GitHub-Api-Version: 2022-11-28" https://api.github.com/repos/f1db/f1db/releases/latest | jq -r '.tag_name')

url="https://github.com/f1db/f1db/releases/download/${latest}/f1db-sql-mysql.zip"
check404=$(wget $url -O new-sql.zip 2>&1 | grep 'ERROR' )

if [ "$check404" != "" ]
then
    echo $check404 > error.txt
    echo "There was an error in the download."
fi

diff=$(diff new-sql.zip old-sql.zip)
if [ "$diff" != "" ] || [ ! -e old-sql.zip ]
then
    unzip new-sql.zip
    mv new-sql.zip old-sql.zip
    echo "Updating database"

    # Disable autocommit to allow for faster bulk loading
    echo "SET FOREIGN_KEY_CHECKS=0;SET autocommit=0;" > f1.sql
    cat f1db-sql-mysql.sql >> f1.sql
    echo "COMMIT;" >> f1.sql
    
    mariadb f1 < f1.sql
    echo "Making custom tables"
    mariadb f1 < update.sql
    echo "Cleaning up"
    rm f1db-sql-mysql.sql
    rm f1.sql
    echo "Database updated"
else
    echo "No changes."
    rm new-sql.zip
fi
