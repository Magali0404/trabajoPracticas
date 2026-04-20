DROP DATABASE IF EXISTS sistema_academico;
CREATE DATABASE sistema_academico;
USE sistema_academico;

CREATE TABLE carreras (
    id_carrera INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100),
    duracion INT
);

CREATE TABLE alumnos (
    id_alumno INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    dni VARCHAR(20) UNIQUE,
    email VARCHAR(100) UNIQUE,
    fecha_nacimiento DATE,
    telefono VARCHAR(20),
    id_carrera INT,
    FOREIGN KEY (id_carrera) REFERENCES carreras(id_carrera)
);

CREATE TABLE profesores (
    id_profesor INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100),
    apellido VARCHAR(100),
    email VARCHAR(100),
    telefono VARCHAR(20)
);

CREATE TABLE materias (
    id_materia INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100),
    curso INT,
    id_carrera INT,
    id_profesor INT,
    FOREIGN KEY (id_carrera) REFERENCES carreras(id_carrera),
    FOREIGN KEY (id_profesor) REFERENCES profesores(id_profesor)
);
