------------------------------
-- Archivo de base de datos --
------------------------------

/* Extension de encriptacion */
CREATE EXTENSION pgcrypto;

DROP TABLE IF EXISTS usuarios CASCADE;

/* Tabla principal de usuarios, dato de un usuario registrado */
CREATE TABLE usuarios 
(
        id          BIGSERIAL       PRIMARY KEY
    ,   log_us      varchar(60)     NOT NULL UNIQUE
    ,   nombre      varchar(60)     NOT NULL
    ,   apellido    varchar(60)     NOT NULL
    ,   email       varchar(255)    NOT NULL UNIQUE
    ,   password    varchar(255)    NOT NULL
    ,   rol         varchar(255)    DEFAULT 'user'
    ,   auth_key    varchar(255)
    ,   url_img     varchar(2048)   DEFAULT 'user.svg'
    ,   bio         varchar(280)    DEFAULT 'Hola! Estoy usando yiiComment'
    ,   token       varchar(32)
    ,   ubi         varchar(50)   
    ,   img_name    varchar(255)
);

DROP TABLE IF EXISTS comentarios CASCADE;

/* Tabla de comentarios que es lo que se comparte en la plataforma.
*  Datos que tendra un comentario:
*  Los comentarios tendrán su propio id, el id del usuario que ha publicado dicho comentario, el contenido de este
*  y la fecha en la que se ha creado.
*/
CREATE TABLE comentarios
(
        id              BIGSERIAL       PRIMARY KEY
    ,   usuario_id      BIGINT          NOT NULL REFERENCES usuarios (id) ON DELETE CASCADE
    ,   text            varchar(280)    NOT NULL
    ,   created_at      TIMESTAMP(0)    DEFAULT CURRENT_TIMESTAMP
);

DROP TABLE IF EXISTS comfav CASCADE;

/* Tabla donde se guardan los comentarios favorios */
CREATE TABLE comfav
(
        usuario_id      BIGINT      REFERENCES usuarios (id) ON DELETE CASCADE
    ,   comentario_id   BIGINT      REFERENCES comentarios(id) ON DELETE CASCADE
    ,   PRIMARY KEY (usuario_id, comentario_id)  
);

DROP TABLE IF EXISTS megustas CASCADE;

/* En esta tabla se almacena el comentario y el usuario que ha realizado la accion */
CREATE TABLE megustas
(
        usuario_id      BIGINT      REFERENCES usuarios (id) ON DELETE CASCADE
    ,   comentario_id   BIGINT      REFERENCES comentarios(id) ON DELETE CASCADE
    ,   PRIMARY KEY (usuario_id, comentario_id)  
);

DROP TABLE IF EXISTS seguidores CASCADE;

/* Un usuario puede seguir a otro por medio de un insert en esta tabla, 
*  cuando un usuario sigue a otro el seguidor podra ver los comentarios del usuario al que ha seguido
*/
CREATE TABLE seguidores
(
    seguidor_id     BIGINT      REFERENCES usuarios (id) ON DELETE CASCADE
  , seguido_id      BIGINT      REFERENCES usuarios (id) ON DELETE CASCADE
  , PRIMARY KEY (seguidor_id, seguido_id)
);

INSERT INTO usuarios (log_us, nombre, apellido, email, password, rol, auth_key)
VALUES  ('florido', 'david', 'florido', 'david.xipi99@hotmail.com', crypt('hola', gen_salt('bf', 10)), 'usuario', ''),
        ('admin', 'admin', 'admin', 'yiicomment@gmail.com', crypt('admin', gen_salt('bf', 10)), 'admin', ''),
        ('mike', 'miguel', 'sierra', 'jose@hotmail.com', crypt('hola', gen_salt('bf', 10)), 'usuario', ''),
        ('saborido', 'Jose Maria', 'monge', 'saborido@hotmail.com', crypt('hola', gen_salt('bf', 10)), 'usuario', ''),
        ('alexvidal', 'alejandro', 'vidal', 'alexvidal@hotmail.com', crypt('hola', gen_salt('bf', 10)), 'usuario', ''),
        ('casal', 'cristian', 'casal', 'ccasal@hotmail.com', crypt('hola', gen_salt('bf', 10)), 'usuario', ''),
        ('alonso', 'juan', 'alonso', 'alonso@hotmail.com', crypt('hola', gen_salt('bf', 10)), 'usuario', '');

INSERT INTO comentarios (usuario_id, text)
VALUES  (3, 'este es mi primer comentario'),
        (3, 'hola'),
        (4, 'Estoy usando yiicomment'),
        (6, 'Es la primera vez que comento'),
        (5, 'Ya queda menos'),
        (1, 'Pinta mal la pandemia'),
        (2, 'lorem lorem lorem lorem lorem lorem lorem lorem lorem lorem lorem lorem lorem lorem lorem lorem lorem lorem lorem lorem lorem lorem lorem lorem lorem lorem'),
        (4, 'buenas noches'),
        (5, 'primer comentario'),
        (3, 'se ve chula la aplicacion'),
        (1, 'ni tan mal'),
        (4, 'ya se esta acabando el tfg'),
        (6, 'mañana mas'),
        (4, 'viva el betis'),
        (6, 'php es mejor que java');

INSERT INTO seguidores (seguidor_id, seguido_id)
VALUES  (3, 1),
        (1, 3),
        (4, 5),
        (1, 6),
        (7, 5),
        (2, 1),
        (3, 4);