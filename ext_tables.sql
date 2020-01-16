CREATE TABLE tx_koninggeo_domain_model_location (
    uid_foreign int(11) DEFAULT '0' NOT NULL,
    tablename varchar(225) DEFAULT '' NOT NULL,
    place_id varbinary(1024) DEFAULT '' NOT NULL,
    label text,
    latitude decimal(11,7) DEFAULT '0.0000000' NOT NULL,
    longitude decimal(11,7) DEFAULT '0.0000000' NOT NULL,
    response text,

    UNIQUE identifier (uid_foreign,tablename)
);
