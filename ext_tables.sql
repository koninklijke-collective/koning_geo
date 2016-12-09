CREATE TABLE tx_koninggeo_domain_model_location (
    uid_foreign int(11) DEFAULT '0' NOT NULL,
    tablename varchar(225) DEFAULT '' NOT NULL,
    location text,
    latitude decimal(11,7) DEFAULT '0.0000000' NOT NULL,
    longitude decimal(11,7) DEFAULT '0.0000000' NOT NULL,
    viewport_ne_latitude decimal(11,7) DEFAULT '0.0000000' NOT NULL,
    viewport_ne_longitude decimal(11,7) DEFAULT '0.0000000' NOT NULL,
    viewport_sw_latitude decimal(11,7) DEFAULT '0.0000000' NOT NULL,
    viewport_sw_longitude decimal(11,7) DEFAULT '0.0000000' NOT NULL,

    INDEX identifier (uid_foreign,tablename)
);
