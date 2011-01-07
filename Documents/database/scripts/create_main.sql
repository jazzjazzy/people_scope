# ---------------------------------------------------------------------- #
# Script generated with: DeZign for Databases v6.0.0                     #
# Target DBMS:           MySQL 5                                         #
# Project file:          Project1.dez                                    #
# Project name:                                                          #
# Author:                                                                #
# Script type:           Database creation script                        #
# Created on:            2010-11-06 16:06                                #
# ---------------------------------------------------------------------- #


# ---------------------------------------------------------------------- #
# Tables                                                                 #
# ---------------------------------------------------------------------- #

# ---------------------------------------------------------------------- #
# Add table "users"                                                      #
# ---------------------------------------------------------------------- #

CREATE TABLE `users` (
    `user_id` INTEGER NOT NULL AUTO_INCREMENT,
    `user_name` VARCHAR(40),
    `password` VARCHAR(40),
    `create_date` DATETIME,
    `modify_date` VARCHAR(40),
    `delete_date` VARCHAR(40),
    CONSTRAINT `PK_users` PRIMARY KEY (`user_id`)
);

# ---------------------------------------------------------------------- #
# Add table "clients"                                                    #
# ---------------------------------------------------------------------- #

CREATE TABLE `clients` (
    `client_id` INTEGER NOT NULL AUTO_INCREMENT,
    `business_name` VARCHAR(255),
    `ABN` VARCHAR(40),
    `address` VARCHAR(255),
    `address_2` VARCHAR(255),
    `address_3` VARCHAR(255),
    `phone` VARCHAR(40),
    `fax` VARCHAR(40),
    `create_date` DATETIME,
    `modify_date` DATETIME,
    `delete_date` DATETIME,
    CONSTRAINT `PK_clients` PRIMARY KEY (`client_id`)
);

# ---------------------------------------------------------------------- #
# Add table "clients_users"                                              #
# ---------------------------------------------------------------------- #

CREATE TABLE `clients_users` (
    `client_id` INTEGER NOT NULL,
    `user_id` INTEGER NOT NULL,
    CONSTRAINT `PK_clients_users` PRIMARY KEY (`client_id`, `user_id`)
);

# ---------------------------------------------------------------------- #
# Add table "server_connection"                                          #
# ---------------------------------------------------------------------- #

CREATE TABLE `server_connection` (
    `connection_id` INTEGER NOT NULL AUTO_INCREMENT,
    `connection_details` VARCHAR(255),
    `server_host` VARCHAR(40),
    `server_port` VARCHAR(40),
    `server_database` VARCHAR(40),
    `server_type` VARCHAR(40),
    `create_date` VARCHAR(40),
    `modify_date` VARCHAR(40),
    `delete_date` VARCHAR(40),
    CONSTRAINT `PK_server_connection` PRIMARY KEY (`connection_id`)
);

# ---------------------------------------------------------------------- #
# Add table "clients_server_connection"                                  #
# ---------------------------------------------------------------------- #

CREATE TABLE `clients_server_connection` (
    `client_id` INTEGER NOT NULL,
    `connection_id` INTEGER NOT NULL,
    CONSTRAINT `PK_clients_server_connection` PRIMARY KEY (`client_id`, `connection_id`)
);

# ---------------------------------------------------------------------- #
# Add table "database_server"                                            #
# ---------------------------------------------------------------------- #

CREATE TABLE `database_server` (
    `database_id` INTEGER NOT NULL AUTO_INCREMENT,
    `database_host` VARCHAR(40),
    `database_port` VARCHAR(40),
    `database_database` VARCHAR(40),
    `database_os` VARCHAR(40),
    `database_os_version` VARCHAR(40),
    `database_types` VARCHAR(40),
    `database_version` VARCHAR(40),
    `database_inservice` VARCHAR(40),
    `create_date` VARCHAR(40),
    `modify_date` VARCHAR(40),
    `delete_date` VARCHAR(40),
    CONSTRAINT `PK_database_server` PRIMARY KEY (`database_id`)
);

# ---------------------------------------------------------------------- #
# Add table "web_server"                                                 #
# ---------------------------------------------------------------------- #

CREATE TABLE `web_server` (
    `web_id` INTEGER NOT NULL AUTO_INCREMENT,
    `web_ip` VARCHAR(16) NOT NULL,
    `web_domain` VARCHAR(255),
    `web_server` VARCHAR(255),
    `web_port` VARCHAR(8),
    `web_os` VARCHAR(255),
    `web_os_version` VARCHAR(255),
    `web_inservice_date` DATE,
    `create_date` VARCHAR(40),
    `modify_date` VARCHAR(40),
    `delete_date` VARCHAR(40),
    CONSTRAINT `PK_web_server` PRIMARY KEY (`web_id`)
);

# ---------------------------------------------------------------------- #
# Foreign key constraints                                                #
# ---------------------------------------------------------------------- #

ALTER TABLE `clients_users` ADD CONSTRAINT `clients_clients_users` 
    FOREIGN KEY (`client_id`) REFERENCES `clients` (`client_id`);

ALTER TABLE `clients_users` ADD CONSTRAINT `users_clients_users` 
    FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

ALTER TABLE `clients_server_connection` ADD CONSTRAINT `clients_clients_server_connection` 
    FOREIGN KEY (`client_id`) REFERENCES `clients` (`client_id`);

ALTER TABLE `clients_server_connection` ADD CONSTRAINT `server_connection_clients_server_connection` 
    FOREIGN KEY (`connection_id`) REFERENCES `server_connection` (`connection_id`);
