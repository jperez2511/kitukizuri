#!/bin/bash

/opt/mssql/bin/sqlservr &

echo "Esperando que SQL Server arranque..."
sleep 20

echo "Creando base de datos '$MSSQL_DATABASE' si no existe..."
/opt/mssql-tools/bin/sqlcmd -S localhost -U SA -P "$SA_PASSWORD" -Q "IF NOT EXISTS (SELECT name FROM sys.databases WHERE name = '$MSSQL_DATABASE') CREATE DATABASE [$MSSQL_DATABASE]"

echo "Base de datos '$MSSQL_DATABASE' creada o ya existe."

fg