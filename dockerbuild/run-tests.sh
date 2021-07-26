#!/usr/bin/env bash

set -e
set -x

/data/run-metadata-tests.sh
/data/run-integration-tests.sh
