CREATE OR REPLACE PROCEDURE addSystemUser(userName varchar2, userLastname varchar2, userNick varchar2, userPassword varchar2,  userMail varchar2,  userBirth date )
IS
BEGIN
    INSERT INTO SYSTEM_USER(USER_ID, USER_FIRSTNAME, USER_LASTNAME, USER_NICKNAME, USER_EMAIL, USER_PASSWORD,USER_BIRTHDATE)
        VALUES(SEQ_USER.nextval, userName, userLastname, userNick, userPassword, userMail, userBirth );
END;