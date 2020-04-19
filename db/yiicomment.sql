------------------------------
-- Archivo de base de datos --
------------------------------

DROP TABLE IF EXISTS usuarios CASCADE;

CREATE TABLE usuarios 
(
        id          BIGSERIAL       PRIMARY KEY
    ,   log_us      varchar(60)     NOT NULL UNIQUE
    ,   nombre      varchar(60)     NOT NULL
    ,   apellido    varchar(60)     NOT NULL
    ,   email       varchar(255)    NOT NULL UNIQUE
    ,   password    varchar(255)    NOT NULL
    ,   rol         varchar(255)    NOT NULL
    ,   auth_key    varchar(255)
    ,   url_img     varchar(2048)   
    ,   img_name    varchar(255)
);

DROP TABLE IF EXISTS comentarios CASCADE;

CREATE TABLE comentarios
(
        id              BIGSERIAL       PRIMARY KEY
    ,   usuario_id      BIGINT          NOT NULL REFERENCES usuarios (id) ON DELETE CASCADE
    ,   text            varchar(280)    NOT NULL
    ,   created_at      TIMESTAMP(0)    DEFAULT CURRENT_TIMESTAMP
);

DROP TABLE IF EXISTS megustas CASCADE;

CREATE TABLE megustas
(
        usuario_id      BIGINT      REFERENCES usuarios (id) ON DELETE CASCADE
    ,   comentario_id   BIGINT      REFERENCES comentarios(id) ON DELETE CASCADE
    ,   PRIMARY KEY (usuario_id, comentario_id)  
);

DROP TABLE IF EXISTS seguidores CASCADE;

CREATE TABLE seguidores
(
    seguidor_id     BIGINT      REFERENCES usuarios (id)
  , seguido_id      BIGINT      REFERENCES usuarios (id)
  , PRIMARY KEY (seguidor_id, seguido_id)
);

INSERT INTO usuarios (log_us, nombre, apellido, email, password, rol, auth_key)
VALUES  ('esscart', 'david', 'florido', 'david.xipi99@hotmail.com', 'hola', 'admin', crypt('hola', gen_salt('bf', 10))),
        ('admin', 'pepe', 'garcia', 'admin@hotmail.com', 'hola', 'admin', crypt('hola', gen_salt('bf', 10))),
        ('mike', 'miguel', 'sierra', 'jose@hotmail.com', 'hola', 'usuario', crypt('hola', gen_salt('bf', 10)));

INSERT INTO comentarios (usuario_id, text)
VALUES  (3, 'este es mi primer comentario'),
        (3, 'hola'),
        (2, 'buenas noches');

INSERT INTO seguidores (seguidor_id, seguido_id)
VALUES (3, 1);