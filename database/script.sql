CREATE DATABASE IF NOT EXISTS pasteleria;
USE pasteleria;

CREATE TABLE pasteles (
    ID_pastel INT AUTO_INCREMENT PRIMARY KEY,
    Nombre VARCHAR(100) NOT NULL,
    Descripcion TEXT,
    Preparado_por VARCHAR(100) NOT NULL,
    Fecha_creacion DATE NOT NULL,
    Fecha_Vencimiento DATE NOT NULL
);

CREATE TABLE ingredientes (
    ID_Ingrediente INT AUTO_INCREMENT PRIMARY KEY,
    Nombre VARCHAR(100) NOT NULL UNIQUE,
    Descripcion TEXT,
    Fecha_ingreso DATE NOT NULL,
    Fecha_Vencimiento DATE NOT NULL
);

CREATE TABLE pastel_ingredientes (
    ID_Pastel_ingrediente INT AUTO_INCREMENT PRIMARY KEY,
    ID_pastel INT NOT NULL,
    ID_Ingrediente INT NOT NULL,
    FOREIGN KEY (ID_pastel) REFERENCES pasteles(ID_pastel) ON DELETE CASCADE,
    FOREIGN KEY (ID_Ingrediente) REFERENCES ingredientes(ID_Ingrediente) ON DELETE CASCADE,
    UNIQUE KEY unique_pastel_ingrediente (ID_pastel, ID_Ingrediente)
);

INSERT INTO ingredientes (Nombre, Descripcion, Fecha_ingreso, Fecha_Vencimiento) VALUES
('Harina de trigo', 'Harina blanca de trigo, uso múltiple', '2026-01-01', '2026-12-31'),
('Azúcar blanca', 'Azúcar granulada refinada', '2026-01-01', '2026-12-31'),
('Huevos', 'Huevos frescos de gallina', '2026-05-01', '2026-06-01'),
('Mantequilla', 'Mantequilla sin sal', '2026-05-01', '2026-08-01'),
('Leche entera', 'Leche pasteurizada', '2026-05-05', '2026-05-20'),
('Esencia de vainilla', 'Extracto natural de vainilla', '2026-01-01', '2026-12-31'),
('Polvo de hornear', 'Levadura química para repostería', '2026-01-01', '2026-12-31'),
('Chocolate amargo', 'Chocolate 70% cacao', '2026-03-01', '2026-09-01'),
('Crema de leche', 'Crema para batir 35% grasa', '2026-05-05', '2026-05-25'),
('Nueces', 'Nueces peladas troceadas', '2026-04-01', '2026-10-01');

INSERT INTO pasteles (Nombre, Descripcion, Preparado_por, Fecha_creacion, Fecha_Vencimiento) VALUES
('Pastel de Vainilla', 'Esponjoso pastel con esencia de vainilla y betún de mantequilla', 'Chef María González', '2026-05-10', '2026-05-17'),
('Pastel de Chocolate', 'Rico pastel de chocolate con cobertura ganache', 'Chef Carlos Ruiz', '2026-05-10', '2026-05-17'),
('Pastel de Zanahoria', 'Con nueces y cubierto con crema de queso', 'Chef Ana Martínez', '2026-05-09', '2026-05-16');


INSERT INTO pastel_ingredientes (ID_pastel, ID_Ingrediente) VALUES
(1, 1), (1, 2), (1, 3), (1, 4), (1, 5), (1, 6), (1, 7),
(2, 1), (2, 2), (2, 3), (2, 4), (2, 7), (2, 8), (2, 9),
(3, 1), (3, 2), (3, 3), (3, 7), (3, 10), (3, 5);