#!/usr/bin/env ash

# Create data table
aws dynamodb create-table --table-name sildisco_local_user-log \
  --attribute-definitions AttributeName=ID,AttributeType=S \
  --key-schema AttributeName=ID,KeyType=HASH \
  --provisioned-throughput ReadCapacityUnits=10,WriteCapacityUnits=10 \
  --endpoint-url http://dynamo:8000


# Enable Time to Live
aws dynamodb update-time-to-live --table-name sildisco_local_user-log \
  --time-to-live-specification "Enabled=true,AttributeName=ExpiresAt" \
  --endpoint-url http://dynamo:8000