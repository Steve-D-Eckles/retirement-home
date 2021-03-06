CREATE DATABASE IF NOT EXISTS retirement;
USE retirement;

-- Drop existing tables
DROP TABLE IF EXISTS roster;
DROP TABLE IF EXISTS checklists;
DROP TABLE IF EXISTS appointments;
DROP TABLE IF EXISTS employees;
DROP TABLE IF EXISTS patients;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS roles;

-- Table for user roles
CREATE TABLE roles (
  role_id INT AUTO_INCREMENT PRIMARY KEY,
  role_name VARCHAR(25) NOT NULL,
  access_level INT NOT NULL,

  UNIQUE(role_name)
);

-- Table for all registered users
CREATE TABLE users (
  user_id BIGINT AUTO_INCREMENT PRIMARY KEY,
  first_name VARCHAR(100) NOT NULL,
  last_name VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL,
  password VARCHAR(100) NOT NULL,
  phone VARCHAR(12) NOT NULL,
  dob DATE,
  role INT NOT NULL,
  confirmed BOOLEAN,

  FOREIGN KEY (role)
    REFERENCES roles (role_id)
    ON DELETE CASCADE,

  UNIQUE(email)
);

-- Table for additional information for patient users
CREATE TABLE patients (
  patient_id BIGINT PRIMARY KEY,
  family_code INT NOT NULL,
  emergency_contact VARCHAR(100),
  ec_relation VARCHAR(50),
  group_id SMALLINT,
  admit_date DATE,
  due INT DEFAULT 0,
  last_update DATE DEFAULT CURDATE(),

  -- patient_id is both a primary and foreign key because patient is a subtype of user
  FOREIGN KEY (patient_id)
    REFERENCES users (user_id)
    ON DELETE CASCADE
);

-- Table for additional information on employee users
CREATE TABLE employees (
  employee_id BIGINT PRIMARY KEY,
  salary INT,

  -- employee_id is both a primary and foreign key because employee is a subtype of user
  FOREIGN KEY (employee_id)
    REFERENCES users (user_id)
    ON DELETE CASCADE
);

-- Table for caregiver checklists for each user for each date
CREATE TABLE checklists (
  list_id BIGINT AUTO_INCREMENT PRIMARY KEY,
  patient_id BIGINT NOT NULL,
  caregiver_id BIGINT NOT NULL,
  list_date DATE NOT NULL,
  morn_med BOOLEAN DEFAULT 0,
  afternoon_med BOOLEAN DEFAULT 0,
  night_med BOOLEAN DEFAULT 0,
  breakfast BOOLEAN DEFAULT 0,
  lunch BOOLEAN DEFAULT 0,
  dinner BOOLEAN DEFAULT 0,

  FOREIGN KEY (patient_id)
    REFERENCES users (user_id)
    ON DELETE CASCADE,

  FOREIGN KEY (caregiver_id)
    REFERENCES users (user_id)
    ON DELETE CASCADE
);

-- Table for doctors' appointments
CREATE TABLE appointments (
  appt_id BIGINT AUTO_INCREMENT PRIMARY KEY,
  patient_id BIGINT NOT NULL,
  doctor_id BIGINT NOT NULL,
  appt_date DATE NOT NULL,
  comment TEXT DEFAULT NULL,
  morn_med VARCHAR(100) DEFAULT NULL,
  afternoon_med VARCHAR(100) DEFAULT NULL,
  night_med VARCHAR(100) DEFAULT NULL,

  FOREIGN KEY (patient_id)
    REFERENCES users (user_id)
    ON DELETE CASCADE,

  FOREIGN KEY (doctor_id)
    REFERENCES users (user_id)
    ON DELETE CASCADE,

  UNIQUE (patient_id, appt_date)
);

-- Table for the daily roster
CREATE TABLE roster (
  roster_date DATE PRIMARY KEY,
  supervisor_id BIGINT NOT NULL,
  doctor_id BIGINT NOT NULL,
  care_one_id BIGINT NOT NULL,
  care_two_id BIGINT NOT NULL,
  care_three_id BIGINT NOT NULL,
  care_four_id BIGINT NOT NULL,

  FOREIGN KEY (supervisor_id)
    REFERENCES users (user_id)
    ON DELETE CASCADE,

  FOREIGN KEY (doctor_id)
    REFERENCES users (user_id)
    ON DELETE CASCADE,

  FOREIGN KEY (care_one_id)
    REFERENCES users (user_id)
    ON DELETE CASCADE,

  FOREIGN KEY (care_two_id)
    REFERENCES users (user_id)
    ON DELETE CASCADE,

  FOREIGN KEY (care_three_id)
    REFERENCES users (user_id)
    ON DELETE CASCADE,

  FOREIGN KEY (care_four_id)
    REFERENCES users (user_id)
    ON DELETE CASCADE
);
