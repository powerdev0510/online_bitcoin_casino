CREATE TABLE `Users` (
  user varchar(255) UNIQUE,
  email varchar(255) UNIQUE,
  password varchar(255),
  user_id varchar(255),
  balance double,
  invested double,
  invested_profit double,
  wagered double,
  bets double,
  profit double,
  bitcoin_address varchar(255),
  server_num double,
  server_hash varchar(255),
  shuffle_num double,
  shuffle_hash varchar(255),
  PRIMARY KEY (`user`)
) ENGINE = InnoDB;

CREATE TABLE `Bets` (
  id double,
  user varchar(255),
  time varchar(255),
  timestamp double,
  bet double,
  multiplier varchar(255),
  prediction varchar(255),
  roll double,
  profit double,
  outcome varchar(255),
  server_num double,
  server_hash varchar(255),
  shuffle_num double,
  shuffle_hash varchar(255),
  PRIMARY KEY (`id`)
) ENGINE = InnoDB;

CREATE TABLE `Bankroll` (
  user varchar(255),
  invested double,
  invested_profit double,
  PRIMARY KEY (`user`)
) ENGINE = InnoDB;

CREATE TABLE `Site` (
  bets double,
  wagered double,
  investor_profit double,
  invested double,
  unrealized double,
  PRIMARY KEY (`bets`)
) ENGINE = InnoDB;
INSERT INTO `Site`(`bets`,`wagered`,`investor_profit`,`invested`,`unrealized`) VALUES (0,0,0,0,0);