CREATE TABLESPACE PROYECTO1
  DATAFILE 'C:\app\Administrator\oradata\orcl\P1.dbf'
  SIZE 10M
  REUSE
  AUTOEXTEND ON
  NEXT 512k
  MAXSIZE 200M;
--
-- PE: INDEX
--
CREATE TABLESPACE ID_PROYECTO1
  DATAFILE 'C:\app\Administrator\oradata\orcl\IDP1.dbf'
  SIZE 10M
  REUSE
  AUTOEXTEND ON
  NEXT 512k
  MAXSIZE 200M;

--Creación de otros tablespaces...
