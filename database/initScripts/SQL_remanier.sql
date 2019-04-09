#------------------------------------------------------------
#        DB Generator for main_db
#------------------------------------------------------------

create database if not exists main_db
CHARACTER SET utf8
COLLATE utf8_bin;
use main_db;

#------------------------------------------------------------
# Table: tbl_country
#------------------------------------------------------------

CREATE TABLE tbl_country(
        id_country Int  Auto_increment  NOT NULL ,
        name       Varchar (25) NOT NULL UNIQUE ,
        is_active  Bool NOT NULL DEFAULT 1
	,CONSTRAINT tbl_country_PK PRIMARY KEY (id_country)
)ENGINE=InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;


#------------------------------------------------------------
# Table: tbl_state
#------------------------------------------------------------

CREATE TABLE tbl_state(
        id_state   Int  Auto_increment  NOT NULL ,
        name       Varchar (50) NOT NULL UNIQUE,
        code       Varchar (2) NOT NULL UNIQUE,
        is_active  Bool NOT NULL DEFAULT 1 ,
        id_country Int NOT NULL
	,CONSTRAINT tbl_state_PK PRIMARY KEY (id_state)

	,CONSTRAINT tbl_state_tbl_country_FK FOREIGN KEY (id_country) REFERENCES tbl_country(id_country)
)ENGINE=InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;


#------------------------------------------------------------
# Table: tbl_address
#------------------------------------------------------------

CREATE TABLE tbl_address(
        id_address Int  Auto_increment  NOT NULL ,
        physical_address    Varchar (150) NOT NULL ,
        city_name  Varchar (75) NOT NULL ,
        zip_code   Varchar (10) NOT NULL ,
        is_active  Bool NOT NULL DEFAULT 1 ,
        id_state   Int NOT NULL
	,CONSTRAINT tbl_address_PK PRIMARY KEY (id_address)

	,CONSTRAINT tbl_address_tbl_state_FK FOREIGN KEY (id_state) REFERENCES tbl_state(id_state)
)ENGINE=InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;

#------------------------------------------------------------
# Table: tbl_phone_type
#------------------------------------------------------------

CREATE TABLE tbl_phone_type(
        id_phone_type Int  Auto_increment  NOT NULL ,
        name          Varchar (15) NOT NULL UNIQUE,
        is_active     Bool NOT NULL DEFAULT 1
	,CONSTRAINT tbl_phone_type_PK PRIMARY KEY (id_phone_type)
)ENGINE=InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;

#------------------------------------------------------------
# Table: tbl_customer
#------------------------------------------------------------

CREATE TABLE tbl_customer(
        id_customer Int  Auto_increment  NOT NULL ,
        sex         Char (1) NOT NULL ,
        first_name  Varchar (50) NOT NULL ,
        last_name   Varchar (50) NOT NULL ,
        birth_date  DateTime NOT NULL ,
        occupation  Varchar (100) NOT NULL ,
        is_active   Bool NOT NULL DEFAULT 1 ,
        id_address  Int NOT NULL
	,CONSTRAINT tbl_customer_PK PRIMARY KEY (id_customer)
	,CONSTRAINT tbl_customer_tbl_address_FK FOREIGN KEY (id_address) REFERENCES tbl_address(id_address)
)ENGINE=InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;

#------------------------------------------------------------
# Table: tbl_phone_number   --- VOIR À RENDRE UNIQUE LE NUMÉRO DE TÉLÉPHONE ET EXTENSION COMBINÉ ---
#------------------------------------------------------------

CREATE TABLE tbl_phone_number(
        id_phone_number Int  Auto_increment  NOT NULL ,
        phone		    Varchar (15) NOT NULL ,
        extension       Varchar (10) ,
        is_active       Bool NOT NULL DEFAULT 1 ,
        id_phone_type   Int NOT NULL,
        id_customer Int NOT NULL
	,CONSTRAINT tbl_phone_number_PK PRIMARY KEY (id_phone_number)
    ,CONSTRAINT tbl_customer_tbl_phone_number_FK FOREIGN KEY (id_customer) REFERENCES tbl_customer(id_customer)
	,CONSTRAINT tbl_phone_number_tbl_phone_type_FK FOREIGN KEY (id_phone_type) REFERENCES tbl_phone_type(id_phone_type)
)ENGINE=InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;

#------------------------------------------------------------
# Table: tbl_user
#------------------------------------------------------------

CREATE TABLE tbl_user(
        id_user     Int  Auto_increment  NOT NULL ,
        email       Varchar (256) NOT NULL UNIQUE,
        password    Varchar (256) NOT NULL ,
        role        Int (8) NOT NULL DEFAULT 0,
        created_on  Datetime NOT NULL DEFAULT current_timestamp ,
        last_login  Datetime NOT NULL DEFAULT current_timestamp ,
        is_active   Bool NOT NULL DEFAULT 1 ,
        id_customer Int NOT NULL
	,CONSTRAINT tbl_user_PK PRIMARY KEY (id_user)

	,CONSTRAINT tbl_user_tbl_customer_FK FOREIGN KEY (id_customer) REFERENCES tbl_customer(id_customer)
	,CONSTRAINT tbl_user_tbl_customer_AK UNIQUE (id_customer)
)ENGINE=InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;


#------------------------------------------------------------
# Table: tbl_permissions
#------------------------------------------------------------

CREATE TABLE tbl_permission(
        id_permissions Int  Auto_increment  NOT NULL ,
        is_active      Bool NOT NULL DEFAULT 1 ,
        bit            Int (8) NOT NULL UNIQUE ,
        name           Varchar (64) NOT NULL UNIQUE
	,CONSTRAINT tbl_permissions_PK PRIMARY KEY (id_permissions)
    ,CONSTRAINT tbl_permissions_CHK_bit CHECK (mod(bit, 2) = 0)
)ENGINE=InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;


#------------------------------------------------------------
# Table: tbl_follow_up
#------------------------------------------------------------

CREATE TABLE tbl_follow_up(
        id_follow_up Int  Auto_increment  NOT NULL ,
        treatment    Varchar (1080) NOT NULL ,
        summary      Varchar (256) NOT NULL ,
        created_on   Datetime NOT NULL ,
        is_active    Bool NOT NULL DEFAULT 1 ,
        id_customer  Int NOT NULL
	,CONSTRAINT tbl_follow_up_PK PRIMARY KEY (id_follow_up)

	,CONSTRAINT tbl_follow_up_tbl_customer_FK FOREIGN KEY (id_customer) REFERENCES tbl_customer(id_customer)
)ENGINE=InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;


#------------------------------------------------------------
# Table: tbl_time_slot
#------------------------------------------------------------

CREATE TABLE tbl_time_slot(
        id_time_slot   Int  Auto_increment  NOT NULL ,
        slot_date_time Datetime NOT NULL ,
        duration_time  Datetime NOT NULL ,
        is_active      Bool NOT NULL ,
        is_public      Bool NOT NULL
	,CONSTRAINT tbl_time_slot_PK PRIMARY KEY (id_time_slot)
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: tbl_appointment
#------------------------------------------------------------

CREATE TABLE tbl_appointment(
        id_appointment Int  Auto_increment  NOT NULL ,
        created_on     Datetime NOT NULL ,
        is_active      Bool NOT NULL ,
        is_new         Bool NOT NULL ,
        id_customer    Int NOT NULL ,
        id_time_slot   Int NOT NULL
	,CONSTRAINT tbl_appointment_PK PRIMARY KEY (id_appointment)
)ENGINE=InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;


#------------------------------------------------------------
# Inserting basic data
#------------------------------------------------------------

# 'bit' must be a multiple of 2
INSERT INTO tbl_permission (bit,name) VALUES
(1,   'Root'),
(2,   'Appointments-Read'),
(4,   'Appointments-Write'),
(8,   'Customers-Read'),
(16,  'Customers-Write'),
(32,  'Timeslots-Read'),
(64,  'Timeslots-Write');

INSERT INTO tbl_phone_type (name) VALUES
('Résidentiel'), ('Bureau'), ('Cellulaire');

INSERT INTO tbl_country (name) VALUES
('Canada'), ('États-Unis');

SET @CAD_CountryId := (select id_country from tbl_country where name = 'Canada');
SET @USA_CountryId := (select id_country from tbl_country where name = 'États-Unis');
INSERT INTO tbl_state (code,name,id_country) VALUES
	('AB','Alberta', @CAD_CountryId),
	('BC','Colombie-Britannique', @CAD_CountryId),
	('MB','Manitoba', @CAD_CountryId),
	('NB','Nouveau-Brunswick', @CAD_CountryId),
	('NL','Terre-Neuve-et-Labrador', @CAD_CountryId),
	('NT','Northwest Territories', @CAD_CountryId),
	('NS','Nouvelle-Écosse', @CAD_CountryId),
	('NU','Nunavut', @CAD_CountryId),
	('ON','Ontario', @CAD_CountryId),
	('PE','Île-du-Prince-Édouard', @CAD_CountryId),
	('QC','Québec', @CAD_CountryId),
	('SK','Saskatchewan', @CAD_CountryId),
	('YT','Yukon', @CAD_CountryId),
	('AL','Alabama', @USA_CountryId),
	('AK','Alaska', @USA_CountryId),
	('AS','American Samoa', @USA_CountryId),
	('AZ','Arizona', @USA_CountryId),
	('AR','Arkansas', @USA_CountryId),
	('CA','California', @USA_CountryId),
	('CO','Colorado', @USA_CountryId),
	('CT','Connecticut', @USA_CountryId),
	('DE','Delaware', @USA_CountryId),
	('DC','District of Columbia', @USA_CountryId),
	('FM','Federated tbl_states of Micronesia', @USA_CountryId),
	('FL','Florida', @USA_CountryId),
	('GA','Georgia', @USA_CountryId),
	('GU','Guam', @USA_CountryId),
	('HI','Hawaii', @USA_CountryId),
	('ID','Idaho', @USA_CountryId),
	('IL','Illinois', @USA_CountryId),
	('IN','Indiana', @USA_CountryId),
	('IA','Iowa', @USA_CountryId),
	('KS','Kansas', @USA_CountryId),
	('KY','Kentucky', @USA_CountryId),
	('LA','Louisiana', @USA_CountryId),
	('ME','Maine', @USA_CountryId),
	('MH','Marshall Islands', @USA_CountryId),
	('MD','Maryland', @USA_CountryId),
	('MA','Massachusetts', @USA_CountryId),
	('MI','Michigan', @USA_CountryId),
	('MN','Minnesota', @USA_CountryId),
	('MS','Mississippi', @USA_CountryId),
	('MO','Missouri', @USA_CountryId),
	('MT','Montana', @USA_CountryId),
	('NE','Nebraska', @USA_CountryId),
	('NV','Nevada', @USA_CountryId),
	('NH','New Hampshire', @USA_CountryId),
	('NJ','New Jersey', @USA_CountryId),
	('NM','New Mexico', @USA_CountryId),
	('NY','New York', @USA_CountryId),
	('NC','North Carolina', @USA_CountryId),
	('ND','North Dakota', @USA_CountryId),
	('MP','Northern Mariana Islands', @USA_CountryId),
	('OH','Ohio', @USA_CountryId),
	('OK','Oklahoma', @USA_CountryId),
	('OR','Oregon', @USA_CountryId),
	('PW','Palau', @USA_CountryId),
	('PA','Pennsylvania', @USA_CountryId),
	('PR','Puerto Rico', @USA_CountryId),
	('RI','Rhode Island', @USA_CountryId),
	('SC','South Carolina', @USA_CountryId),
	('SD','South Dakota', @USA_CountryId),
	('TN','Tennessee', @USA_CountryId),
	('TX','Texas', @USA_CountryId),
	('UT','Utah', @USA_CountryId),
	('VT','Vermont', @USA_CountryId),
	('VI','Virgin Islands', @USA_CountryId),
	('VA','Virginia', @USA_CountryId),
	('WA','Washington', @USA_CountryId),
	('WV','West Virginia', @USA_CountryId),
	('WI','Wisconsin', @USA_CountryId),
	('WY','Wyoming', @USA_CountryId);

#------------------------------------------------------------
# Creating data for tests
#------------------------------------------------------------

INSERT INTO tbl_address (physical_address, city_name, zip_code, id_state) VALUES
('382, rang saint-joseph', 'Beauceville', 'G5X2C8', (select id_state from tbl_state where name = 'Québec')),
('13140, Boul. Lacroix', 'Saint-Georges', 'G5Y6P8', (select id_state from tbl_state where name = 'Québec'));

INSERT INTO tbl_customer (sex, first_name, last_name, birth_date, occupation, id_address) VALUES
('M', 'Jessy', 'Rodrigue', '1997-02-08', 'SysAdmin', '1'),
('M', 'Yannick', 'Jacques', '1997-08-31', 'Brogrammer', '2');

INSERT INTO tbl_user (id_customer, email, password, role) VALUES
(1, "jessy@rodrigue.com", "ValidPassword", 1),
(2, "yannick@jacques.com", "ValidPassword", DEFAULT);

INSERT INTO tbl_phone_number (phone, id_phone_type,id_customer) VALUES
('(418) 774-3835', 1,1),
('(418) 588-6211', 1,2),
('(418) 230-5469', 3,1),
('(418) 420-6969', 2,2),
('(418) 313-8034', 3,2);

INSERT INTO tbl_time_slot (slot_date_time, duration_time, is_public) VALUES
( '2019-04-30 09:00:00', '1000-01-01 01:00:00', 1),
( '2019-04-30 10:30:00', '1000-01-01 01:00:00', 1),
( '2019-04-30 11:00:00', '1000-01-01 01:30:00', 1),
( '2019-04-30 13:00:00', '1000-01-01 01:30:00', 1),
( '2019-04-30 19:00:00', '1000-01-01 01:30:00', 1);
