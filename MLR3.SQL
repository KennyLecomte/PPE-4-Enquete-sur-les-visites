DROP TABLE IF EXISTS ENQUETE;

DROP TABLE IF EXISTS BUDGET;

DROP TABLE IF EXISTS BATEAU;

DROP TABLE IF EXISTS DEPARTEMENT;

DROP TABLE IF EXISTS CATEGORIESOCIAL;

DROP TABLE IF EXISTS PREFERER;

# -----------------------------------------------------------------------------
#       TABLE : ENQUETE
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS ENQUETE
 (
   ID INTEGER(2) NOT NULL  ,
   ID_UTILISER CHAR(32) NOT NULL  ,
   ID_APPARTENIR INTEGER(2) NOT NULL  ,
   NUMERO CHAR(32) NOT NULL  ,
   TRANCHEAGE CHAR(32) NULL  ,
   MOTIF CHAR(32) NULL  ,
   CODEPOSTAL INTEGER(2) NULL  ,
   VILLE CHAR(32) NULL  ,
   VIP BOOL NULL  ,
   DATEENQUETE DATE NULL  
   , PRIMARY KEY (ID) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       INDEX DE LA TABLE ENQUETE
# -----------------------------------------------------------------------------


CREATE  INDEX I_FK_ENQUETE_BUDGET
     ON ENQUETE (ID_UTILISER ASC);

CREATE  INDEX I_FK_ENQUETE_CATEGORIESOCIAL
     ON ENQUETE (ID_APPARTENIR ASC);

CREATE  INDEX I_FK_ENQUETE_DEPARTEMENT
     ON ENQUETE (NUMERO ASC);

# -----------------------------------------------------------------------------
#       TABLE : BUDGET
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS BUDGET
 (
   ID CHAR(32) NOT NULL  ,
   MIN INTEGER(2) NULL  ,
   MAX INTEGER(2) NULL  
   , PRIMARY KEY (ID) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       TABLE : BATEAU
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS BATEAU
 (
   MODELE CHAR(32) NOT NULL  ,
   CATEGORIE CHAR(32) NULL  
   , PRIMARY KEY (MODELE) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       TABLE : DEPARTEMENT
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS DEPARTEMENT
 (
   NUMERO CHAR(32) NOT NULL  ,
   NOM CHAR(32) NULL  
   , PRIMARY KEY (NUMERO) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       TABLE : CATEGORIESOCIAL
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS CATEGORIESOCIAL
 (
   ID INTEGER(2) NOT NULL  ,
   NOMCATE CHAR(32) NULL  
   , PRIMARY KEY (ID) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       TABLE : PREFERER
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS PREFERER
 (
   ID INTEGER(2) NOT NULL  ,
   MODELE CHAR(32) NOT NULL  
   , PRIMARY KEY (ID,MODELE) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       INDEX DE LA TABLE PREFERER
# -----------------------------------------------------------------------------


CREATE  INDEX I_FK_PREFERER_ENQUETE
     ON PREFERER (ID ASC);

CREATE  INDEX I_FK_PREFERER_BATEAU
     ON PREFERER (MODELE ASC);


# -----------------------------------------------------------------------------
#       CREATION DES REFERENCES DE TABLE
# -----------------------------------------------------------------------------


ALTER TABLE ENQUETE 
  ADD FOREIGN KEY FK_ENQUETE_BUDGET (ID_UTILISER)
      REFERENCES BUDGET (ID) ;


ALTER TABLE ENQUETE 
  ADD FOREIGN KEY FK_ENQUETE_CATEGORIESOCIAL (ID_APPARTENIR)
      REFERENCES CATEGORIESOCIAL (ID) ;


ALTER TABLE ENQUETE 
  ADD FOREIGN KEY FK_ENQUETE_DEPARTEMENT (NUMERO)
      REFERENCES DEPARTEMENT (NUMERO) ;


ALTER TABLE PREFERER 
  ADD FOREIGN KEY FK_PREFERER_ENQUETE (ID)
      REFERENCES ENQUETE (ID) ;


ALTER TABLE PREFERER 
  ADD FOREIGN KEY FK_PREFERER_BATEAU (MODELE)
      REFERENCES BATEAU (MODELE) ;

