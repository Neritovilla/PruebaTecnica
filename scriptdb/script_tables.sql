/*creamos la base dedatos*/
CREATE DATABASE pruebaTecnica CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

/*creamos la tabla region*/
CREATE TABLE Region(
    id_region INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL
);

/*creamos la tabla provincia*/
CREATE TABLE Provincia (
    id_provincia INT AUTO_INCREMENT PRIMARY KEY,
    nombre_provincia VARCHAR(100) NOT NULL,
    capital_provincia VARCHAR(100) NOT NULL,
    descripcion_provincia TEXT,
    poblacion_provincia  DECIMAL(10, 2),
    superficie_provincia DECIMAL(10, 2),
    latitud_provincia DECIMAL(9, 6),
    longitud_provincia DECIMAL(9, 6),
    id_region INT,
    FOREIGN KEY (id_region) REFERENCES Region(id_region)
);

/*creamos la tabla empleado*/
CREATE TABLE Empleado (
    id_empleado INT AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(50),
    nombres VARCHAR(50),
    apellidos VARCHAR(50),
    cedula VARCHAR(20),
    id_provincia INT,
    fecha_nacimiento DATE,
    email VARCHAR(100),
    observacion TEXT,
    fotografia LONGBLOB,
    fecha_ingreso DATE,
    cargo VARCHAR(50),
    departamento VARCHAR(50),
    id_provincia_laboral INT,
    sueldo DECIMAL(10, 2),
    jornada_presencial INT(2),
    observaciones_laboral TEXT,
    estado VARCHAR(20),
    FOREIGN KEY (id_provincia) REFERENCES Provincia(id_provincia),
    FOREIGN KEY (id_provincia_laboral) REFERENCES Provincia(id_provincia)
);