create table abian.Ads
(
	id int auto_increment
		primary key,
	owner int not null,
	link varchar(512) not null,
	content varchar(512) not null,
	flavor varchar(32) not null,
	date int(15) not null,
	expiration int(15) not null,
	shown int not null,
	approved int(1) not null,
	weight int not null,
	description varchar(512) not null,
	comment varchar(512) not null
)
;

create index Ads_Users
	on Ads (owner)
;

create table abian.Badges
(
	id int auto_increment
		primary key,
	name varchar(50) not null,
	type varchar(50) not null,
	description varchar(256) not null,
	value int(3) not null
)
;

create table abian.Badging
(
	id int auto_increment
		primary key,
	badge int not null,
	user int not null
)
;

create index Badge_FK
	on Badging (badge)
;

create index User_FK
	on Badging (user)
;

create table abian.Ban
(
	id int(20) auto_increment
		primary key,
	date int(20) null,
	ip varchar(60) null,
	user int null,
	issuer int default '0' not null,
	reason varchar(512) default 'No reason provided.' not null,
	appealed int(1) default '0' not null
)
;

create table abian.Bans
(
	id int auto_increment
		primary key,
	ip varchar(256) not null,
	user int not null,
	issuer int not null,
	reason varchar(2048) not null,
	date int not null,
	expire int not null,
	appealed int(1) not null
)
;

create index Bans_Users_issuer
	on Bans (issuer)
;

create index Bans_Users_user
	on Bans (user)
;

create table abian.Bots
(
	id int auto_increment
		primary key,
	user int not null,
	name varchar(64) not null,
	slug varchar(64) not null,
	description varchar(256) not null,
	body varchar(4096) not null,
	dateCreate int not null,
	dateUpdate int default '0' not null,
	listed int(1) default '0' not null,
	allowed int(1) default '0' not null,
	method int(1) default '0' not null,
	member int(1) default '0' not null
)
;

create index Users_Bots
	on Bots (user)
;

create table abian.Comments
(
	id int auto_increment
		primary key,
	`on` varchar(32) not null,
	user int not null,
	reply int null,
	message varchar(512) not null,
	Users_id int not null
)
;

create index Comments_Users
	on Comments (user)
;

create table abian.DMCA
(
	id int auto_increment
		primary key,
	issuer varchar(64) not null,
	issuerSearch varchar(512) not null,
	adder int not null,
	chillingLink varchar(512) not null,
	status varchar(1024) not null,
	emails varchar(256) not null
)
;

create index DMCA_Users
	on DMCA (adder)
;

create table abian.History
(
	id int auto_increment
		primary key,
	actor int not null,
	actorIp varchar(128) not null,
	action varchar(32) not null,
	target varchar(32) null,
	targeted int null,
	description varchar(128) not null,
	date int not null,
	actorA2 varchar(3) not null
)
;

create index History_Users
	on History (actor)
;

create table abian.News
(
	id int auto_increment
		primary key,
	name varchar(64) not null,
	slug varchar(64) not null,
	body varchar(8192) not null,
	user int not null,
	date int not null,
	published int(1) not null
)
;

create index News_Users
	on News (user)
;

create table abian.PMReceivers
(
	id int auto_increment
		primary key,
	message int not null,
	user int not null,
	archived int not null,
	`read` int not null
)
;

create index PMReceivers_Users
	on PMReceivers (user)
;

create index PrivateMessages_PMReceivers
	on PMReceivers (message)
;

create table abian.PremiumUsers
(
	id int auto_increment
		primary key,
	user int not null,
	expires int not null,
	date int not null
)
;

create index PremiumUsers_Users
	on PremiumUsers (user)
;

create table abian.PrivateMessages
(
	id int auto_increment
		primary key,
	date int not null,
	`from` int not null,
	subject varchar(64) not null,
	body varchar(8192) not null
)
;

create index Users_PrivateMessages
	on PrivateMessages (`from`)
;

create table abian.Rank
(
	id int auto_increment
		primary key,
	rank varchar(64) not null,
	appointer int not null,
	date int not null,
	user int not null
)
;

create index Users_Rank
	on Rank (user)
;

create table abian.Reports
(
	id int auto_increment
		primary key,
	date int not null,
	issuer int not null,
	target int not null,
	about varchar(2048) not null,
	resolved int(1) not null
)
;

create index Users_Reports
	on Reports (issuer)
;

create table abian.Userblobs
(
	id int auto_increment
		primary key,
	user int not null,
	ip varchar(256) not null,
	a2 varchar(3) not null,
	code varchar(256) not null,
	action varchar(256) not null,
	date int not null
)
;

create index Userblobs_Users
	on Userblobs (user)
;

create table abian.Users
(
	id int auto_increment
		primary key,
	username varchar(256) not null,
	firstName varchar(256) default '0' null,
	lastName varchar(256) default '0' null,
	password varchar(256) not null,
	oldPassword varchar(256) default '0' null,
	passwordChanged int default '0' null,
	salt varchar(512) not null,
	oldSalt varchar(512) null,
	email varchar(256) not null,
	oldEmail varchar(256) default '0' null,
	emailChanged int null,
	ip varchar(256) default '' null,
	dateRegistered int(15) not null,
	lastLoggedIn int(15) default '0' null,
	oldLastLoggedIn int(15) default '0' null,
	activated int(1) default '0' not null,
	twoStep int(1) default '0' not null,
	company varchar(64) null,
	aqName varchar(64) null,
	twitchName varchar(64) null,
	githubName varchar(64) null,
	a2 varchar(3) not null,
	timeZone varchar(64) default 'America/Denver' not null,
	lastActive int(15) default '0' not null
)
;

create table abian.Votes
(
	id int auto_increment
		primary key,
	user int not null,
	type int(1) not null
)
;

create index Votes_Users
	on Votes (user)
;

