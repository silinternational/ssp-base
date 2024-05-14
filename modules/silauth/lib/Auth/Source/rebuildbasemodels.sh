#!/usr/bin/env bash

TABLES=(user previous_password)
SUFFIX="Base"

declare -A models
models["failed_login_username"]="FailedLoginUsernameBase"
models["failed_login_ip_address"]="FailedLoginIpAddressBase"

for i in "${!models[@]}"; do
    CMD="/data/src/yii gii/model --tableName=$i --modelClass=${models[$i]} --generateRelations=all --enableI18N=1 --overwrite=1 --interactive=0 --ns=SimpleSAML\Module\silauth\Auth\Source\models"
    echo $CMD
    $CMD
done
