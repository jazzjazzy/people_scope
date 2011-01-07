# ---------------------------------------------------------------------- #
# Script generated with: DeZign for Databases v6.0.0                     #
# Target DBMS:           MySQL 5                                         #
# Project file:          people_scope.dez                                #
# Project name:                                                          #
# Author:                                                                #
# Script type:           Database creation script                        #
# Created on:            2010-05-16 13:07                                #
# ---------------------------------------------------------------------- #


# ---------------------------------------------------------------------- #
# Tables                                                                 #
# ---------------------------------------------------------------------- #

# ---------------------------------------------------------------------- #
# Add table "Administration"                                             #
# ---------------------------------------------------------------------- #

CREATE TABLE `Administration` (
    `admin_id` INTEGER NOT NULL,
    `user_name` VARCHAR(40),
    `password` VARCHAR(40),
    `database_server` VARCHAR(40),
    `database_host` VARCHAR(40),
    `ip_range` VARCHAR(40),
    `create_date` DATETIME,
    `modifiy_date` DATETIME,
    `last_login` DATETIME
);
