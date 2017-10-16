CREATE OR REPLACE PROCEDURE addSystemUser(userName varchar2, userLastname varchar2, userNick varchar2, userPassword varchar2,  userMail varchar2, userProfession varchar2,  userBirth date )
IS
BEGIN
    INSERT INTO SYSTEM_USER(USER_ID, USER_FIRSTNAME, USER_LASTNAME, USER_NICKNAME, USER_EMAIL, USER_PASSWORD, USER_PROFESSION,USER_BIRTHDATE)
        VALUES(SEQ_USER.NEXTVAL, userName, userLastname, userNick, userPassword, userMail, userProfession, userBirth );
    INSERT INTO BINNACLE(BINNACLE_ID, USER_ID, OLD_PASSWORD, CURRENT_PASSWORD)
        VALUES (SEQ_BINNACLE.NEXTVAL, SEQ_USER.CURRVAL, userMail, userMail);
END;









--select * from PROVINCE;
--SELECT SEQ_USER.CURRVAL VAL FROM DUAL; 
--SELECT * 
--alter sequence seq_user increment by 1;
--WHERE CONTINENT_ID = 1;
/*DELETE FROM SYSTEM_USER
WHERE USER_ID >=0;     
CALL ADDSYSTEMUSER('TEST','TEST','TEST','TEST','TEST',TO_DATE('1998-05-05','YYYY-MM-DD'));
*/