#!/bin/bash

cd scripts
psql -U postgres -d postgres -f init_tables.sql
