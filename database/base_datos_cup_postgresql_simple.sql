-- ============================================================
-- BASE DE DATOS Y TABLAS
-- Sistema Web de Admisión Universitaria CUP - FICCT
-- PostgreSQL
-- Sin índices adicionales
-- ============================================================

-- Si estás en pgAdmin, primero crea la base de datos manualmente con el nombre:
-- cup_ficct
--
-- Luego selecciona esa base de datos y ejecuta este script.

DROP TABLE IF EXISTS resultado_admision CASCADE;
DROP TABLE IF EXISTS notas CASCADE;
DROP TABLE IF EXISTS evaluacion_config CASCADE;
DROP TABLE IF EXISTS asistencia_detalle CASCADE;
DROP TABLE IF EXISTS asistencia_clase CASCADE;
DROP TABLE IF EXISTS carga_horaria CASCADE;
DROP TABLE IF EXISTS grupo_estudiante CASCADE;
DROP TABLE IF EXISTS grupos CASCADE;
DROP TABLE IF EXISTS postulante_requisito CASCADE;
DROP TABLE IF EXISTS requisitos CASCADE;
DROP TABLE IF EXISTS inscripciones CASCADE;
DROP TABLE IF EXISTS pagos CASCADE;
DROP TABLE IF EXISTS postulantes CASCADE;
DROP TABLE IF EXISTS cupo_carrera_gestion CASCADE;
DROP TABLE IF EXISTS carreras CASCADE;
DROP TABLE IF EXISTS gestion_academica CASCADE;
DROP TABLE IF EXISTS usuario_importado CASCADE;
DROP TABLE IF EXISTS lote_carga_usuario CASCADE;
DROP TABLE IF EXISTS bitacora CASCADE;
DROP TABLE IF EXISTS rol_permiso CASCADE;
DROP TABLE IF EXISTS usuarios CASCADE;
DROP TABLE IF EXISTS permisos CASCADE;
DROP TABLE IF EXISTS roles CASCADE;
DROP TABLE IF EXISTS docentes CASCADE;
DROP TABLE IF EXISTS materias CASCADE;
DROP TABLE IF EXISTS aulas CASCADE;

CREATE TABLE roles (
    id_rol BIGSERIAL PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE,
    descripcion VARCHAR(150),
    estado VARCHAR(20) NOT NULL DEFAULT 'ACTIVO'
);

CREATE TABLE permisos (
    id_permiso BIGSERIAL PRIMARY KEY,
    nombre VARCHAR(80) NOT NULL UNIQUE,
    descripcion VARCHAR(150)
);

CREATE TABLE rol_permiso (
    id_rol BIGINT NOT NULL,
    id_permiso BIGINT NOT NULL,
    PRIMARY KEY (id_rol, id_permiso),
    FOREIGN KEY (id_rol) REFERENCES roles(id_rol),
    FOREIGN KEY (id_permiso) REFERENCES permisos(id_permiso)
);

CREATE TABLE usuarios (
    id_usuario BIGSERIAL PRIMARY KEY,
    id_rol BIGINT NOT NULL,
    nombre_usuario VARCHAR(50) NOT NULL UNIQUE,
    correo VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    estado VARCHAR(20) NOT NULL DEFAULT 'ACTIVO',
    fecha_creacion TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_rol) REFERENCES roles(id_rol)
);

CREATE TABLE bitacora (
    id_bitacora BIGSERIAL PRIMARY KEY,
    id_usuario BIGINT NOT NULL,
    fecha_hora TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    modulo VARCHAR(80) NOT NULL,
    accion VARCHAR(80) NOT NULL,
    descripcion VARCHAR(255),
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario)
);

CREATE TABLE lote_carga_usuario (
    id_lote BIGSERIAL PRIMARY KEY,
    nombre_archivo VARCHAR(150) NOT NULL,
    tipo_archivo VARCHAR(30) NOT NULL,
    fecha_carga TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    cargado_por BIGINT NOT NULL,
    estado VARCHAR(20) NOT NULL DEFAULT 'PENDIENTE',
    FOREIGN KEY (cargado_por) REFERENCES usuarios(id_usuario)
);

CREATE TABLE usuario_importado (
    id_usuario_importado BIGSERIAL PRIMARY KEY,
    id_lote BIGINT NOT NULL,
    nombre_completo VARCHAR(120) NOT NULL,
    correo VARCHAR(100) NOT NULL,
    rol_sugerido VARCHAR(50),
    estado_generacion VARCHAR(30) NOT NULL DEFAULT 'PENDIENTE',
    observacion VARCHAR(255),
    FOREIGN KEY (id_lote) REFERENCES lote_carga_usuario(id_lote)
);

CREATE TABLE gestion_academica (
    id_gestion BIGSERIAL PRIMARY KEY,
    nombre VARCHAR(60) NOT NULL,
    anio INT NOT NULL,
    periodo VARCHAR(20) NOT NULL,
    fecha_inicio DATE NOT NULL,
    fecha_fin DATE NOT NULL,
    estado VARCHAR(20) NOT NULL DEFAULT 'ACTIVA'
);

CREATE TABLE carreras (
    id_carrera BIGSERIAL PRIMARY KEY,
    codigo VARCHAR(20) NOT NULL UNIQUE,
    nombre VARCHAR(100) NOT NULL UNIQUE,
    estado VARCHAR(20) NOT NULL DEFAULT 'ACTIVA'
);

CREATE TABLE cupo_carrera_gestion (
    id_cupo BIGSERIAL PRIMARY KEY,
    id_carrera BIGINT NOT NULL,
    id_gestion BIGINT NOT NULL,
    cupo_maximo INT NOT NULL,
    FOREIGN KEY (id_carrera) REFERENCES carreras(id_carrera),
    FOREIGN KEY (id_gestion) REFERENCES gestion_academica(id_gestion),
    UNIQUE (id_carrera, id_gestion)
);

CREATE TABLE postulantes (
    id_postulante BIGSERIAL PRIMARY KEY,
    ci VARCHAR(20) NOT NULL UNIQUE,
    nombres VARCHAR(80) NOT NULL,
    apellidos VARCHAR(80) NOT NULL,
    fecha_nacimiento DATE NOT NULL,
    sexo CHAR(1) NOT NULL,
    direccion VARCHAR(150),
    telefono VARCHAR(20),
    correo VARCHAR(100) UNIQUE,
    colegio_procedencia VARCHAR(120),
    ciudad VARCHAR(80),
    titulo_bachiller VARCHAR(120),
    otros_requisitos VARCHAR(255),
    id_carrera_primera_opcion BIGINT NOT NULL,
    id_carrera_segunda_opcion BIGINT,
    fecha_registro TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    estado VARCHAR(20) NOT NULL DEFAULT 'REGISTRADO',
    FOREIGN KEY (id_carrera_primera_opcion) REFERENCES carreras(id_carrera),
    FOREIGN KEY (id_carrera_segunda_opcion) REFERENCES carreras(id_carrera)
);

CREATE TABLE requisitos (
    id_requisito BIGSERIAL PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE,
    descripcion VARCHAR(200),
    obligatorio BOOLEAN NOT NULL DEFAULT TRUE,
    estado VARCHAR(20) NOT NULL DEFAULT 'ACTIVO'
);

CREATE TABLE postulante_requisito (
    id_postulante BIGINT NOT NULL,
    id_requisito BIGINT NOT NULL,
    presentado BOOLEAN NOT NULL DEFAULT FALSE,
    fecha_presentacion DATE,
    observacion VARCHAR(255),
    PRIMARY KEY (id_postulante, id_requisito),
    FOREIGN KEY (id_postulante) REFERENCES postulantes(id_postulante),
    FOREIGN KEY (id_requisito) REFERENCES requisitos(id_requisito)
);

CREATE TABLE pagos (
    id_pago BIGSERIAL PRIMARY KEY,
    id_postulante BIGINT NOT NULL,
    monto NUMERIC(10,2) NOT NULL,
    metodo_pago VARCHAR(30) NOT NULL,
    codigo_transaccion VARCHAR(50),
    estado_pago VARCHAR(20) NOT NULL DEFAULT 'PENDIENTE',
    fecha_pago TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_postulante) REFERENCES postulantes(id_postulante)
);

CREATE TABLE inscripciones (
    id_inscripcion BIGSERIAL PRIMARY KEY,
    id_postulante BIGINT NOT NULL,
    id_gestion BIGINT NOT NULL,
    fecha_inscripcion TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    estado_inscripcion VARCHAR(20) NOT NULL DEFAULT 'INSCRITO',
    FOREIGN KEY (id_postulante) REFERENCES postulantes(id_postulante),
    FOREIGN KEY (id_gestion) REFERENCES gestion_academica(id_gestion),
    UNIQUE (id_postulante, id_gestion)
);

CREATE TABLE grupos (
    id_grupo BIGSERIAL PRIMARY KEY,
    id_gestion BIGINT NOT NULL,
    codigo_grupo VARCHAR(20) NOT NULL,
    cupo_maximo INT NOT NULL DEFAULT 70,
    estado VARCHAR(20) NOT NULL DEFAULT 'ACTIVO',
    FOREIGN KEY (id_gestion) REFERENCES gestion_academica(id_gestion),
    UNIQUE (id_gestion, codigo_grupo)
);

CREATE TABLE grupo_estudiante (
    id_grupo_estudiante BIGSERIAL PRIMARY KEY,
    id_grupo BIGINT NOT NULL,
    id_inscripcion BIGINT NOT NULL,
    fecha_asignacion TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_grupo) REFERENCES grupos(id_grupo),
    FOREIGN KEY (id_inscripcion) REFERENCES inscripciones(id_inscripcion),
    UNIQUE (id_inscripcion)
);

CREATE TABLE docentes (
    id_docente BIGSERIAL PRIMARY KEY,
    ci VARCHAR(20) NOT NULL UNIQUE,
    nombres VARCHAR(80) NOT NULL,
    apellidos VARCHAR(80) NOT NULL,
    telefono VARCHAR(20),
    correo VARCHAR(100) UNIQUE,
    profesional_area VARCHAR(100),
    tiene_maestria BOOLEAN NOT NULL DEFAULT FALSE,
    tiene_diplomado_educacion_superior BOOLEAN NOT NULL DEFAULT FALSE,
    estado_contratacion VARCHAR(20) NOT NULL DEFAULT 'ACTIVO',
    fecha_registro TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE materias (
    id_materia BIGSERIAL PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE,
    descripcion VARCHAR(200),
    estado VARCHAR(20) NOT NULL DEFAULT 'ACTIVA'
);

CREATE TABLE aulas (
    id_aula BIGSERIAL PRIMARY KEY,
    codigo VARCHAR(20) NOT NULL UNIQUE,
    nombre VARCHAR(80) NOT NULL,
    capacidad INT NOT NULL,
    ubicacion VARCHAR(100),
    estado VARCHAR(20) NOT NULL DEFAULT 'DISPONIBLE'
);

CREATE TABLE carga_horaria (
    id_carga_horaria BIGSERIAL PRIMARY KEY,
    id_grupo BIGINT NOT NULL,
    id_materia BIGINT NOT NULL,
    id_docente BIGINT NOT NULL,
    id_aula BIGINT NOT NULL,
    dia_semana VARCHAR(15) NOT NULL,
    hora_inicio TIME NOT NULL,
    hora_fin TIME NOT NULL,
    FOREIGN KEY (id_grupo) REFERENCES grupos(id_grupo),
    FOREIGN KEY (id_materia) REFERENCES materias(id_materia),
    FOREIGN KEY (id_docente) REFERENCES docentes(id_docente),
    FOREIGN KEY (id_aula) REFERENCES aulas(id_aula)
);

CREATE TABLE asistencia_clase (
    id_asistencia_clase BIGSERIAL PRIMARY KEY,
    id_carga_horaria BIGINT NOT NULL,
    fecha_clase DATE NOT NULL,
    tema_avanzado VARCHAR(200),
    registrado_por BIGINT NOT NULL,
    fecha_registro TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_carga_horaria) REFERENCES carga_horaria(id_carga_horaria),
    FOREIGN KEY (registrado_por) REFERENCES usuarios(id_usuario),
    UNIQUE (id_carga_horaria, fecha_clase)
);

CREATE TABLE asistencia_detalle (
    id_asistencia_detalle BIGSERIAL PRIMARY KEY,
    id_asistencia_clase BIGINT NOT NULL,
    id_inscripcion BIGINT NOT NULL,
    estado_asistencia VARCHAR(20) NOT NULL,
    observacion VARCHAR(255),
    FOREIGN KEY (id_asistencia_clase) REFERENCES asistencia_clase(id_asistencia_clase),
    FOREIGN KEY (id_inscripcion) REFERENCES inscripciones(id_inscripcion),
    UNIQUE (id_asistencia_clase, id_inscripcion)
);

CREATE TABLE evaluacion_config (
    id_evaluacion BIGSERIAL PRIMARY KEY,
    id_gestion BIGINT NOT NULL,
    id_materia BIGINT NOT NULL,
    numero_evaluacion INT NOT NULL,
    porcentaje NUMERIC(5,2) NOT NULL,
    FOREIGN KEY (id_gestion) REFERENCES gestion_academica(id_gestion),
    FOREIGN KEY (id_materia) REFERENCES materias(id_materia),
    UNIQUE (id_gestion, id_materia, numero_evaluacion)
);

CREATE TABLE notas (
    id_nota BIGSERIAL PRIMARY KEY,
    id_inscripcion BIGINT NOT NULL,
    id_evaluacion BIGINT NOT NULL,
    nota NUMERIC(5,2) NOT NULL,
    fecha_registro TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_inscripcion) REFERENCES inscripciones(id_inscripcion),
    FOREIGN KEY (id_evaluacion) REFERENCES evaluacion_config(id_evaluacion),
    UNIQUE (id_inscripcion, id_evaluacion)
);

CREATE TABLE resultado_admision (
    id_resultado BIGSERIAL PRIMARY KEY,
    id_inscripcion BIGINT NOT NULL UNIQUE,
    promedio_final NUMERIC(5,2) NOT NULL,
    estado_resultado VARCHAR(20) NOT NULL,
    id_carrera_admitida BIGINT,
    orden_opcion_admitida INT,
    fecha_resultado TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_inscripcion) REFERENCES inscripciones(id_inscripcion),
    FOREIGN KEY (id_carrera_admitida) REFERENCES carreras(id_carrera)
);
