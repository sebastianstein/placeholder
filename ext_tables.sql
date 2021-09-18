CREATE TABLE tx_placeholder_domain_model_placeholder
(

	uid               int(11)                         NOT NULL auto_increment,
	pid               int(11)             DEFAULT '0' NOT NULL,

	tstamp            int(11) unsigned    DEFAULT '0' NOT NULL,
	crdate            int(11) unsigned    DEFAULT '0' NOT NULL,
	cruser_id         int(11) unsigned    DEFAULT '0' NOT NULL,
	deleted           tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden            tinyint(4) unsigned DEFAULT '0' NOT NULL,
	starttime         int(11) unsigned    DEFAULT '0' NOT NULL,
	endtime           int(11) unsigned    DEFAULT '0' NOT NULL,

	t3ver_oid         int(11)             DEFAULT '0' NOT NULL,
	t3ver_id          int(11)             DEFAULT '0' NOT NULL,
	t3ver_wsid        int(11)             DEFAULT '0' NOT NULL,
	t3ver_label       varchar(255)        DEFAULT ''  NOT NULL,
	t3ver_state       tinyint(4)          DEFAULT '0' NOT NULL,
	t3ver_stage       int(11)             DEFAULT '0' NOT NULL,
	t3ver_count       int(11)             DEFAULT '0' NOT NULL,
	t3ver_tstamp      int(11)             DEFAULT '0' NOT NULL,
	t3ver_move_id     int(11)             DEFAULT '0' NOT NULL,

	sys_language_uid  int(11)             DEFAULT '0' NOT NULL,
	l10n_parent       int(11)             DEFAULT '0' NOT NULL,
	l10n_diffsource   mediumblob,

	marker_identifier varchar(150)        DEFAULT ''  NOT NULL,
	description       text,
	value             varchar(255)        DEFAULT ''  NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid),
	KEY t3ver_oid (t3ver_oid, t3ver_wsid),
	KEY language (l10n_parent, sys_language_uid)

);

CREATE TABLE tx_placeholder_domain_model_log
(
	uid        int(11)                         NOT NULL auto_increment,

	crdate     int(11) unsigned    DEFAULT '0' NOT NULL,
	cruser_id  int(11) unsigned    DEFAULT '0' NOT NULL,
	deleted    tinyint(4) unsigned DEFAULT '0' NOT NULL,

	request_id varchar(13)         DEFAULT ''  NOT NULL,
	time_micro double(16, 4)                   NOT NULL default '0.0000',
	component  varchar(255)        DEFAULT ''  NOT NULL,
	level      tinyint(1) unsigned DEFAULT '0' NOT NULL,
	message    text,
	data       text,

	PRIMARY KEY (uid)
);
