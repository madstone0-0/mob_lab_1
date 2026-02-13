create database if not exists mobileapps_2026B_madiba_quansah;
use mobileapps_2026B_madiba_quansah;

drop table if exists contacts;

create table if not exists contacts (
    pid int auto_increment PRIMARY KEY,
    pname varchar(100) not null,
    pphone varchar(100) not null
);
