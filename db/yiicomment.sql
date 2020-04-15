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
    ,   created_at      TIMESTAMP(0)    NOT NULL DEFAULT CURRENT_TIMESTAMP
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