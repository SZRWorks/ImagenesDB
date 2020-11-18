/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Author:  Gael
 * Created: 17 nov. 2020
 */

CREATE DATABASE imagesDB;
USE imagesDB;

CREATE TABLE images(
    id int primary key AUTO_INCREMENT,
    type varchar(255),
    imagen longblob
);
