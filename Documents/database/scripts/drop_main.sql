# ---------------------------------------------------------------------- #
# Script generated with: DeZign for Databases v6.0.0                     #
# Target DBMS:           MySQL 5                                         #
# Project file:          Project1.dez                                    #
# Project name:                                                          #
# Author:                                                                #
# Script type:           Database drop script                            #
# Created on:            2010-11-06 16:06                                #
# ---------------------------------------------------------------------- #


# ---------------------------------------------------------------------- #
# Drop foreign key constraints                                           #
# ---------------------------------------------------------------------- #

ALTER TABLE `clients_users` DROP FOREIGN KEY `clients_clients_users`;

ALTER TABLE `clients_users` DROP FOREIGN KEY `users_clients_users`;

ALTER TABLE `clients_server_connection` DROP FOREIGN KEY `clients_clients_server_connection`;

ALTER TABLE `clients_server_connection` DROP FOREIGN KEY `server_connection_clients_server_connection`;

# ---------------------------------------------------------------------- #
# Drop table "web_server"                                                #
# ---------------------------------------------------------------------- #

# Remove autoinc for PK drop #

ALTER TABLE `web_server` MODIFY `web_id` INTEGER NOT NULL;

# Drop constraints #

ALTER TABLE `web_server` DROP PRIMARY KEY;

# Drop table #

DROP TABLE `web_server`;

# ---------------------------------------------------------------------- #
# Drop table "database_server"                                           #
# ---------------------------------------------------------------------- #

# Remove autoinc for PK drop #

ALTER TABLE `database_server` MODIFY `database_id` INTEGER NOT NULL;

# Drop constraints #

ALTER TABLE `database_server` DROP PRIMARY KEY;

# Drop table #

DROP TABLE `database_server`;

# ---------------------------------------------------------------------- #
# Drop table "clients_server_connection"                                 #
# ---------------------------------------------------------------------- #

# Drop constraints #

ALTER TABLE `clients_server_connection` DROP PRIMARY KEY;

# Drop table #

DROP TABLE `clients_server_connection`;

# ---------------------------------------------------------------------- #
# Drop table "server_connection"                                         #
# ---------------------------------------------------------------------- #

# Remove autoinc for PK drop #

ALTER TABLE `server_connection` MODIFY `connection_id` INTEGER NOT NULL;

# Drop constraints #

ALTER TABLE `server_connection` DROP PRIMARY KEY;

# Drop table #

DROP TABLE `server_connection`;

# ---------------------------------------------------------------------- #
# Drop table "clients_users"                                             #
# ---------------------------------------------------------------------- #

# Drop constraints #

ALTER TABLE `clients_users` DROP PRIMARY KEY;

# Drop table #

DROP TABLE `clients_users`;

# ---------------------------------------------------------------------- #
# Drop table "clients"                                                   #
# ---------------------------------------------------------------------- #

# Remove autoinc for PK drop #

ALTER TABLE `clients` MODIFY `client_id` INTEGER NOT NULL;

# Drop constraints #

ALTER TABLE `clients` DROP PRIMARY KEY;

# Drop table #

DROP TABLE `clients`;

# ---------------------------------------------------------------------- #
# Drop table "users"                                                     #
# ---------------------------------------------------------------------- #

# Remove autoinc for PK drop #

ALTER TABLE `users` MODIFY `user_id` INTEGER NOT NULL;

# Drop constraints #

ALTER TABLE `users` DROP PRIMARY KEY;

# Drop table #

DROP TABLE `users`;
