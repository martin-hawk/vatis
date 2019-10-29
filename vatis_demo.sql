-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 2019 m. Spa 29 d. 20:27
-- Server version: 10.4.6-MariaDB
-- PHP Version: 7.3.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `vatis`
--

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `classifier_company`
--

CREATE TABLE `classifier_company` (
  `CompanyCode` varchar(4) COLLATE utf8_unicode_ci NOT NULL,
  `Code` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `VATPayer` tinyint(1) NOT NULL DEFAULT 1,
  `VATCode` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Street` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `House` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `Flat` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `PostalCode` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `City` varchar(85) COLLATE utf8_unicode_ci NOT NULL,
  `District` varchar(85) COLLATE utf8_unicode_ci NOT NULL,
  `Country` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `DirectorPosition` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `Director` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `AccountantPosition` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `Accountant` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `CurrencyCode` varchar(3) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Sukurta duomenų kopija lentelei `classifier_company`
--

INSERT INTO `classifier_company` (`CompanyCode`, `Code`, `VATPayer`, `VATCode`, `Name`, `Street`, `House`, `Flat`, `PostalCode`, `City`, `District`, `Country`, `DirectorPosition`, `Director`, `AccountantPosition`, `Accountant`, `CurrencyCode`) VALUES
('MV00', '702068', 0, '', 'Martynas Vanagas', 'Smolensko g.', '31', '10', '03201', 'Vilnius', '', 'LT', 'Individualią veiklą vykdantis asmuo', 'Martynas Vanagas', '', '', 'EUR');

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `classifier_company_bank`
--

CREATE TABLE `classifier_company_bank` (
  `CompanyCode` varchar(4) COLLATE utf8_unicode_ci NOT NULL,
  `BankID` int(11) NOT NULL,
  `AccountName` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `BankName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Street` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `House` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `Flat` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `PostalCode` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `City` varchar(85) COLLATE utf8_unicode_ci NOT NULL,
  `Disctrict` varchar(85) COLLATE utf8_unicode_ci NOT NULL,
  `Country` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `SWIFT` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `IBAN` varchar(35) COLLATE utf8_unicode_ci NOT NULL,
  `AccountCurrency` varchar(3) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Sukurta duomenų kopija lentelei `classifier_company_bank`
--

INSERT INTO `classifier_company_bank` (`CompanyCode`, `BankID`, `AccountName`, `BankName`, `Street`, `House`, `Flat`, `PostalCode`, `City`, `Disctrict`, `Country`, `SWIFT`, `IBAN`, `AccountCurrency`) VALUES
('MV00', 3, 'Swedbank', 'AB \"Swedbank\"', 'Konstitucijos pr.', '20A', '', '03502', 'Vilnius', 'Vilniaus apskr.', 'LT', 'HABALT22', 'LT83 7300 XXXX DDDD 1655', 'MLT');

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `classifier_contractor`
--

CREATE TABLE `classifier_contractor` (
  `CompanyCode` varchar(4) COLLATE utf8_unicode_ci NOT NULL,
  `ID` bigint(10) UNSIGNED ZEROFILL NOT NULL,
  `Code` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `VATCode` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `Name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `Street` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `House` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `Flat` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `PostalCode` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `City` varchar(85) COLLATE utf8_unicode_ci NOT NULL,
  `District` varchar(85) COLLATE utf8_unicode_ci NOT NULL,
  `Country` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `IsLegal` tinyint(1) NOT NULL DEFAULT 1,
  `IsLT` tinyint(1) NOT NULL DEFAULT 1,
  `IsEU` tinyint(1) NOT NULL DEFAULT 1,
  `ContractorsGroup` varchar(5) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Sukurta duomenų kopija lentelei `classifier_contractor`
--

INSERT INTO `classifier_contractor` (`CompanyCode`, `ID`, `Code`, `VATCode`, `Name`, `Street`, `House`, `Flat`, `PostalCode`, `City`, `District`, `Country`, `IsLegal`, `IsLT`, `IsEU`, `ContractorsGroup`) VALUES
('MV00', 0000500000, '123033512', 'LT230335113', 'Maxima LT, UAB', 'Naugarduko g.', '84', '', '03160', 'Vilnius', '', 'LT', 1, 1, 0, 'PIRK');

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `classifier_contractor_group`
--

CREATE TABLE `classifier_contractor_group` (
  `CompanyCode` varchar(4) COLLATE utf8_unicode_ci NOT NULL,
  `GroupID` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `GroupDescription` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `RangeFrom` bigint(10) UNSIGNED ZEROFILL NOT NULL,
  `RangeTo` bigint(10) UNSIGNED ZEROFILL NOT NULL,
  `CurrentNumber` bigint(10) UNSIGNED ZEROFILL DEFAULT NULL,
  `isExternal` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Sukurta duomenų kopija lentelei `classifier_contractor_group`
--

INSERT INTO `classifier_contractor_group` (`CompanyCode`, `GroupID`, `GroupDescription`, `RangeFrom`, `RangeTo`, `CurrentNumber`, `isExternal`) VALUES
('MV00', 'PIRK', 'Pirkėjas', 0000500000, 0000599999, 0000500001, 0);

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `classifier_counter`
--

CREATE TABLE `classifier_counter` (
  `CompanyCode` varchar(4) COLLATE utf8_unicode_ci NOT NULL,
  `DocumentType` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `DocumentDescription` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `Prefix` varchar(4) COLLATE utf8_unicode_ci NOT NULL,
  `RangeFrom` bigint(10) UNSIGNED ZEROFILL NOT NULL,
  `RangeTo` bigint(10) UNSIGNED ZEROFILL NOT NULL,
  `CurrentNumber` bigint(10) UNSIGNED ZEROFILL DEFAULT NULL,
  `isExternal` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Sukurta duomenų kopija lentelei `classifier_counter`
--

INSERT INTO `classifier_counter` (`CompanyCode`, `DocumentType`, `DocumentDescription`, `Prefix`, `RangeFrom`, `RangeTo`, `CurrentNumber`, `isExternal`) VALUES
('MV00', 'SD', 'Sąskaita pirkėjui', 'MVSF', 0000000001, 0000099999, 0000000042, 0);

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `classifier_country`
--

CREATE TABLE `classifier_country` (
  `Language` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `CountryCode` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `CountryName` varchar(75) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Sukurta duomenų kopija lentelei `classifier_country`
--

INSERT INTO `classifier_country` (`Language`, `CountryCode`, `CountryName`) VALUES
('EN', 'AD', 'Andorra'),
('EN', 'AE', 'United Arab Emirates'),
('EN', 'AF', 'Afghanistan'),
('EN', 'AG', 'Antigua and Barbuda'),
('EN', 'AI', 'Anguilla'),
('EN', 'AL', 'Albania'),
('EN', 'AM', 'Armenia'),
('EN', 'AN', 'Netherlands Antilles'),
('EN', 'AO', 'Angola'),
('EN', 'AQ', 'Antarctica'),
('EN', 'AR', 'Argentina'),
('EN', 'AS', 'American Samoa'),
('EN', 'AT', 'Austria'),
('EN', 'AU', 'Australia'),
('EN', 'AW', 'Aruba'),
('EN', 'AX', 'Aland Islands'),
('EN', 'AZ', 'Azerbaijan'),
('EN', 'BA', 'Bosnia and Herzegovina'),
('EN', 'BB', 'Barbados'),
('EN', 'BD', 'Bangladesh'),
('EN', 'BE', 'Belgium'),
('EN', 'BF', 'Burkina Faso'),
('EN', 'BG', 'Bulgaria'),
('EN', 'BH', 'Bahrain'),
('EN', 'BI', 'Burundi'),
('EN', 'BJ', 'Benin'),
('EN', 'BL', 'Saint-Barthélemy'),
('EN', 'BM', 'Bermuda'),
('EN', 'BN', 'Brunei Darussalam'),
('EN', 'BO', 'Bolivia'),
('EN', 'BR', 'Brazil'),
('EN', 'BS', 'Bahamas'),
('EN', 'BT', 'Bhutan'),
('EN', 'BV', 'Bouvet Island'),
('EN', 'BW', 'Botswana'),
('EN', 'BY', 'Belarus'),
('EN', 'BZ', 'Belize'),
('EN', 'CA', 'Canada'),
('EN', 'CC', 'Cocos (Keeling) Islands'),
('EN', 'CD', 'Congo, (Kinshasa)'),
('EN', 'CF', 'Central African Republic'),
('EN', 'CG', 'Congo (Brazzaville)'),
('EN', 'CH', 'Switzerland'),
('EN', 'CI', 'Côte d\'Ivoire'),
('EN', 'CK', 'Cook Islands'),
('EN', 'CL', 'Chile'),
('EN', 'CM', 'Cameroon'),
('EN', 'CN', 'China'),
('EN', 'CO', 'Colombia'),
('EN', 'CR', 'Costa Rica'),
('EN', 'CU', 'Cuba'),
('EN', 'CV', 'Cape Verde'),
('EN', 'CX', 'Christmas Island'),
('EN', 'CY', 'Cyprus'),
('EN', 'CZ', 'Czech Republic'),
('EN', 'DE', 'Germany'),
('EN', 'DJ', 'Djibouti'),
('EN', 'DK', 'Denmark'),
('EN', 'DM', 'Dominica'),
('EN', 'DO', 'Dominican Republic'),
('EN', 'DZ', 'Algeria'),
('EN', 'EC', 'Ecuador'),
('EN', 'EE', 'Estonia'),
('EN', 'EG', 'Egypt'),
('EN', 'EH', 'Western Sahara'),
('EN', 'ER', 'Eritrea'),
('EN', 'ES', 'Spain'),
('EN', 'ET', 'Ethiopia'),
('EN', 'FI', 'Finland'),
('EN', 'FJ', 'Fiji'),
('EN', 'FK', 'Falkland Islands (Malvinas)'),
('EN', 'FM', 'Micronesia, Federated States of'),
('EN', 'FO', 'Faroe Islands'),
('EN', 'FR', 'France'),
('EN', 'GA', 'Gabon'),
('EN', 'GB', 'United Kingdom'),
('EN', 'GD', 'Grenada'),
('EN', 'GE', 'Georgia'),
('EN', 'GF', 'French Guiana'),
('EN', 'GG', 'Guernsey'),
('EN', 'GH', 'Ghana'),
('EN', 'GI', 'Gibraltar'),
('EN', 'GL', 'Greenland'),
('EN', 'GM', 'Gambia'),
('EN', 'GN', 'Guinea'),
('EN', 'GP', 'Guadeloupe'),
('EN', 'GQ', 'Equatorial Guinea'),
('EN', 'GR', 'Greece'),
('EN', 'GS', 'South Georgia and the South Sandwich Islands'),
('EN', 'GT', 'Guatemala'),
('EN', 'GU', 'Guam'),
('EN', 'GW', 'Guinea-Bissau'),
('EN', 'GY', 'Guyana'),
('EN', 'HK', 'Hong Kong, SAR China'),
('EN', 'HM', 'Heard and Mcdonald Islands'),
('EN', 'HN', 'Honduras'),
('EN', 'HR', 'Croatia'),
('EN', 'HT', 'Haiti'),
('EN', 'HU', 'Hungary'),
('EN', 'ID', 'Indonesia'),
('EN', 'IE', 'Ireland'),
('EN', 'IL', 'Israel'),
('EN', 'IM', 'Isle of Man'),
('EN', 'IN', 'India'),
('EN', 'IO', 'British Indian Ocean Territory'),
('EN', 'IQ', 'Iraq'),
('EN', 'IR', 'Iran, Islamic Republic of'),
('EN', 'IS', 'Iceland'),
('EN', 'IT', 'Italy'),
('EN', 'JE', 'Jersey'),
('EN', 'JM', 'Jamaica'),
('EN', 'JO', 'Jordan'),
('EN', 'JP', 'Japan'),
('EN', 'KE', 'Kenya'),
('EN', 'KG', 'Kyrgyzstan'),
('EN', 'KH', 'Cambodia'),
('EN', 'KI', 'Kiribati'),
('EN', 'KM', 'Comoros'),
('EN', 'KN', 'Saint Kitts and Nevis'),
('EN', 'KP', 'Korea (North)'),
('EN', 'KR', 'Korea (South)'),
('EN', 'KW', 'Kuwait'),
('EN', 'KY', 'Cayman Islands'),
('EN', 'KZ', 'Kazakhstan'),
('EN', 'LA', 'Lao PDR'),
('EN', 'LB', 'Lebanon'),
('EN', 'LC', 'Saint Lucia'),
('EN', 'LI', 'Liechtenstein'),
('EN', 'LK', 'Sri Lanka'),
('EN', 'LR', 'Liberia'),
('EN', 'LS', 'Lesotho'),
('EN', 'LT', 'Lithuania'),
('EN', 'LU', 'Luxembourg'),
('EN', 'LV', 'Latvia'),
('EN', 'LY', 'Libya'),
('EN', 'MA', 'Morocco'),
('EN', 'MC', 'Monaco'),
('EN', 'MD', 'Moldova'),
('EN', 'ME', 'Montenegro'),
('EN', 'MF', 'Saint-Martin (French part)'),
('EN', 'MG', 'Madagascar'),
('EN', 'MH', 'Marshall Islands'),
('EN', 'MK', 'Macedonia, Republic of'),
('EN', 'ML', 'Mali'),
('EN', 'MM', 'Myanmar'),
('EN', 'MN', 'Mongolia'),
('EN', 'MO', 'Macao, SAR China'),
('EN', 'MP', 'Northern Mariana Islands'),
('EN', 'MQ', 'Martinique'),
('EN', 'MR', 'Mauritania'),
('EN', 'MS', 'Montserrat'),
('EN', 'MT', 'Malta'),
('EN', 'MU', 'Mauritius'),
('EN', 'MV', 'Maldives'),
('EN', 'MW', 'Malawi'),
('EN', 'MX', 'Mexico'),
('EN', 'MY', 'Malaysia'),
('EN', 'MZ', 'Mozambique'),
('EN', 'NA', 'Namibia'),
('EN', 'NC', 'New Caledonia'),
('EN', 'NE', 'Niger'),
('EN', 'NF', 'Norfolk Island'),
('EN', 'NG', 'Nigeria'),
('EN', 'NI', 'Nicaragua'),
('EN', 'NL', 'Netherlands'),
('EN', 'NO', 'Norway'),
('EN', 'NP', 'Nepal'),
('EN', 'NR', 'Nauru'),
('EN', 'NU', 'Niue'),
('EN', 'NZ', 'New Zealand'),
('EN', 'OM', 'Oman'),
('EN', 'PA', 'Panama'),
('EN', 'PE', 'Peru'),
('EN', 'PF', 'French Polynesia'),
('EN', 'PG', 'Papua New Guinea'),
('EN', 'PH', 'Philippines'),
('EN', 'PK', 'Pakistan'),
('EN', 'PL', 'Poland'),
('EN', 'PM', 'Saint Pierre and Miquelon'),
('EN', 'PN', 'Pitcairn'),
('EN', 'PR', 'Puerto Rico'),
('EN', 'PS', 'Palestinian Territory'),
('EN', 'PT', 'Portugal'),
('EN', 'PW', 'Palau'),
('EN', 'PY', 'Paraguay'),
('EN', 'QA', 'Qatar'),
('EN', 'RE', 'Réunion'),
('EN', 'RO', 'Romania'),
('EN', 'RS', 'Serbia'),
('EN', 'RU', 'Russian Federation'),
('EN', 'RW', 'Rwanda'),
('EN', 'SA', 'Saudi Arabia'),
('EN', 'SB', 'Solomon Islands'),
('EN', 'SC', 'Seychelles'),
('EN', 'SD', 'Sudan'),
('EN', 'SE', 'Sweden'),
('EN', 'SG', 'Singapore'),
('EN', 'SH', 'Saint Helena'),
('EN', 'SI', 'Slovenia'),
('EN', 'SJ', 'Svalbard and Jan Mayen Islands'),
('EN', 'SK', 'Slovakia'),
('EN', 'SL', 'Sierra Leone'),
('EN', 'SM', 'San Marino'),
('EN', 'SN', 'Senegal'),
('EN', 'SO', 'Somalia'),
('EN', 'SR', 'Suriname'),
('EN', 'SS', 'South Sudan'),
('EN', 'ST', 'Sao Tome and Principe'),
('EN', 'SV', 'El Salvador'),
('EN', 'SY', 'Syrian Arab Republic (Syria)'),
('EN', 'SZ', 'Swaziland'),
('EN', 'TC', 'Turks and Caicos Islands'),
('EN', 'TD', 'Chad'),
('EN', 'TF', 'French Southern Territories'),
('EN', 'TG', 'Togo'),
('EN', 'TH', 'Thailand'),
('EN', 'TJ', 'Tajikistan'),
('EN', 'TK', 'Tokelau'),
('EN', 'TL', 'Timor-Leste'),
('EN', 'TM', 'Turkmenistan'),
('EN', 'TN', 'Tunisia'),
('EN', 'TO', 'Tonga'),
('EN', 'TR', 'Turkey'),
('EN', 'TT', 'Trinidad and Tobago'),
('EN', 'TV', 'Tuvalu'),
('EN', 'TW', 'Taiwan, Republic of China'),
('EN', 'TZ', 'Tanzania, United Republic of'),
('EN', 'UA', 'Ukraine'),
('EN', 'UG', 'Uganda'),
('EN', 'UM', 'US Minor Outlying Islands'),
('EN', 'US', 'United States of America'),
('EN', 'UY', 'Uruguay'),
('EN', 'UZ', 'Uzbekistan'),
('EN', 'VA', 'Holy See (Vatican City State)'),
('EN', 'VC', 'Saint Vincent and Grenadines'),
('EN', 'VE', 'Venezuela (Bolivarian Republic)'),
('EN', 'VG', 'British Virgin Islands'),
('EN', 'VI', 'Virgin Islands, US'),
('EN', 'VN', 'Viet Nam'),
('EN', 'VU', 'Vanuatu'),
('EN', 'WF', 'Wallis and Futuna Islands'),
('EN', 'WS', 'Samoa'),
('EN', 'YE', 'Yemen'),
('EN', 'YT', 'Mayotte'),
('EN', 'ZA', 'South Africa'),
('EN', 'ZM', 'Zambia'),
('EN', 'ZW', 'Zimbabwe'),
('LT', 'AD', 'Andora'),
('LT', 'AE', 'Jungtiniai Arabų Emyratai'),
('LT', 'AF', 'Afganistanas'),
('LT', 'AG', 'Antigva ir Barbuda'),
('LT', 'AI', 'Angilija'),
('LT', 'AL', 'Albanija'),
('LT', 'AM', 'Armėnija'),
('LT', 'AN', 'Nyderlandų Antilai'),
('LT', 'AO', 'Angola'),
('LT', 'AQ', 'Antarktida'),
('LT', 'AR', 'Argentina'),
('LT', 'AS', 'Amerikos Samoa'),
('LT', 'AT', 'Austrija'),
('LT', 'AU', 'Australija'),
('LT', 'AW', 'Aruba'),
('LT', 'AX', 'Alandai'),
('LT', 'AZ', 'Azerbaidžanas'),
('LT', 'BA', 'Bosnija ir Hercegovina'),
('LT', 'BB', 'Barbadosas'),
('LT', 'BD', 'Bangladešas'),
('LT', 'BE', 'Belgija'),
('LT', 'BF', 'Burkina Fasas'),
('LT', 'BG', 'Bulgarija'),
('LT', 'BH', 'Bahreinas'),
('LT', 'BI', 'Burundis'),
('LT', 'BJ', 'Beninas'),
('LT', 'BL', 'Šv. Bartolomėjaus sala'),
('LT', 'BM', 'Bermuda'),
('LT', 'BN', 'Brunėjus'),
('LT', 'BO', 'Bolivija'),
('LT', 'BR', 'Brazilija'),
('LT', 'BS', 'Bahamai'),
('LT', 'BT', 'Butanas'),
('LT', 'BV', 'Buvė'),
('LT', 'BW', 'Botsvana'),
('LT', 'BY', 'Baltarusija'),
('LT', 'BZ', 'Belizas'),
('LT', 'CA', 'Kanada'),
('LT', 'CC', 'Kokosų salos'),
('LT', 'CD', 'Kongo Demokratinė Respublika'),
('LT', 'CF', 'Centrinės Afrikos Respublika'),
('LT', 'CG', 'Kongo Respublika'),
('LT', 'CH', 'Šveicarija'),
('LT', 'CI', 'Dramblio Kaulo Krantas'),
('LT', 'CK', 'Kuko salos'),
('LT', 'CL', 'Čilė'),
('LT', 'CM', 'Kamerūnas'),
('LT', 'CN', 'Kinija'),
('LT', 'CO', 'Kolumbija'),
('LT', 'CR', 'Kosta Rika'),
('LT', 'CU', 'Kuba'),
('LT', 'CV', 'Žaliasis Kyšulys'),
('LT', 'CX', 'Kalėdų sala'),
('LT', 'CY', 'Kipras'),
('LT', 'CZ', 'Čekija'),
('LT', 'DE', 'Vokietija'),
('LT', 'DJ', 'Džibutis'),
('LT', 'DK', 'Danija'),
('LT', 'DM', 'Dominika'),
('LT', 'DO', 'Dominikos Respublika'),
('LT', 'DZ', 'Alžyras'),
('LT', 'EC', 'Ekvadoras'),
('LT', 'EE', 'Estija'),
('LT', 'EG', 'Egiptas'),
('LT', 'EH', 'Vakarų Sachara'),
('LT', 'ER', 'Eritrėja'),
('LT', 'ES', 'Ispanija'),
('LT', 'ET', 'Etiopija'),
('LT', 'FI', 'Suomija'),
('LT', 'FJ', 'Fidžis'),
('LT', 'FK', 'Falklando salos'),
('LT', 'FM', 'Mikronezijos Federacinės Valstijos'),
('LT', 'FO', 'Farerų salos'),
('LT', 'FR', 'Prancūzija'),
('LT', 'GA', 'Gabonas'),
('LT', 'GB', 'Jungtinė Karalystė'),
('LT', 'GD', 'Grenada'),
('LT', 'GE', 'Gruzija'),
('LT', 'GF', 'Prancūzijos Gviana'),
('LT', 'GG', 'Gernsis'),
('LT', 'GH', 'Gana'),
('LT', 'GI', 'Gibraltaras'),
('LT', 'GL', 'Grenlandija'),
('LT', 'GM', 'Gambija'),
('LT', 'GN', 'Gvinėja'),
('LT', 'GP', 'Gvadelupa'),
('LT', 'GQ', 'Pusiaujo Gvinėja'),
('LT', 'GR', 'Graikija'),
('LT', 'GS', 'Pietų Džordžija ir Pietų Sandvičo salos'),
('LT', 'GT', 'Gvatemala'),
('LT', 'GU', 'Guamas'),
('LT', 'GW', 'Bisau Gvinėja'),
('LT', 'GY', 'Gajana'),
('LT', 'HK', 'Honkongas'),
('LT', 'HM', 'Herdo ir Makdonaldo salos'),
('LT', 'HN', 'Hondūras'),
('LT', 'HR', 'Kroatija'),
('LT', 'HT', 'Haitis'),
('LT', 'HU', 'Vengrija'),
('LT', 'ID', 'Indonezija'),
('LT', 'IE', 'Airija'),
('LT', 'IL', 'Izraelis'),
('LT', 'IM', 'Meno sala'),
('LT', 'IN', 'Indija'),
('LT', 'IO', 'Indijos vandenyno britų salos'),
('LT', 'IQ', 'Irakas'),
('LT', 'IR', 'Iranas'),
('LT', 'IS', 'Islandija'),
('LT', 'IT', 'Italija'),
('LT', 'JE', 'Džersis'),
('LT', 'JM', 'Jamaika'),
('LT', 'JO', 'Jordanija'),
('LT', 'JP', 'Japonija'),
('LT', 'KE', 'Kenija'),
('LT', 'KG', 'Kirgizija'),
('LT', 'KH', 'Kambodža'),
('LT', 'KI', 'Kiribatis'),
('LT', 'KM', 'Komorai'),
('LT', 'KN', 'Sent Kitsas ir Nevis'),
('LT', 'KP', 'Šiaurės Korėja'),
('LT', 'KR', 'Pietų Korėja'),
('LT', 'KW', 'Kuveitas'),
('LT', 'KY', 'Kaimanų salos'),
('LT', 'KZ', 'Kazachstanas'),
('LT', 'LA', 'Laosas'),
('LT', 'LB', 'Libanas'),
('LT', 'LC', 'Sent Lusija'),
('LT', 'LI', 'Lichtenšteinas'),
('LT', 'LK', 'Šri Lanka'),
('LT', 'LR', 'Liberija'),
('LT', 'LS', 'Lesotas'),
('LT', 'LT', 'Lietuva'),
('LT', 'LU', 'Liuksemburgas'),
('LT', 'LV', 'Latvija'),
('LT', 'LY', 'Libija'),
('LT', 'MA', 'Marokas'),
('LT', 'MC', 'Monakas'),
('LT', 'MD', 'Moldavija'),
('LT', 'ME', 'Juodkalnija'),
('LT', 'MF', 'San Martenas'),
('LT', 'MG', 'Madagaskaras'),
('LT', 'MH', 'Maršalo salos'),
('LT', 'MK', 'Makedonija'),
('LT', 'ML', 'Malis'),
('LT', 'MM', 'Mianmaras'),
('LT', 'MN', 'Mongolija'),
('LT', 'MO', 'Makao'),
('LT', 'MP', 'Šiaurės Marianos salos'),
('LT', 'MQ', 'Martinika'),
('LT', 'MR', 'Mauritanija'),
('LT', 'MS', 'Montseratas'),
('LT', 'MT', 'Malta'),
('LT', 'MU', 'Mauricijus'),
('LT', 'MV', 'Maldyvai'),
('LT', 'MW', 'Malavis'),
('LT', 'MX', 'Meksika'),
('LT', 'MY', 'Malaizija'),
('LT', 'MZ', 'Mozambikas'),
('LT', 'NA', 'Namibija'),
('LT', 'NC', 'Naujoji Kaledonija'),
('LT', 'NE', 'Nigeris'),
('LT', 'NF', 'Norfolko sala'),
('LT', 'NG', 'Nigerija'),
('LT', 'NI', 'Nikaragva'),
('LT', 'NL', 'Nyderlandai'),
('LT', 'NO', 'Norvegija'),
('LT', 'NP', 'Nepalas'),
('LT', 'NR', 'Nauru'),
('LT', 'NU', 'Niujė'),
('LT', 'NZ', 'Naujoji Zelandija'),
('LT', 'OM', 'Omanas'),
('LT', 'PA', 'Panama'),
('LT', 'PE', 'Peru'),
('LT', 'PF', 'Prancūzijos Polinezija'),
('LT', 'PG', 'Papua Naujoji Gvinėja'),
('LT', 'PH', 'Filipinai'),
('LT', 'PK', 'Pakistanas'),
('LT', 'PL', 'Lenkija'),
('LT', 'PM', 'Sen Pjeras ir Mikelonas'),
('LT', 'PN', 'Pitkerno salos'),
('LT', 'PR', 'Puerto Rikas'),
('LT', 'PS', 'Palestina'),
('LT', 'PT', 'Portugalija'),
('LT', 'PW', 'Palau'),
('LT', 'PY', 'Paragvajus'),
('LT', 'QA', 'Kataras'),
('LT', 'RE', 'Reunionas'),
('LT', 'RO', 'Rumunija'),
('LT', 'RS', 'Serbija'),
('LT', 'RU', 'Rusija'),
('LT', 'RW', 'Ruanda'),
('LT', 'SA', 'Saudo Arabija'),
('LT', 'SB', 'Saliamono Salos'),
('LT', 'SC', 'Seišeliai'),
('LT', 'SD', 'Sudanas'),
('LT', 'SE', 'Švedija'),
('LT', 'SG', 'Singapūras'),
('LT', 'SH', 'Šv. Elenos sala'),
('LT', 'SI', 'Slovėnija'),
('LT', 'SJ', 'Svalbardas'),
('LT', 'SK', 'Slovakija'),
('LT', 'SL', 'Siera Leonė'),
('LT', 'SM', 'San Marinas'),
('LT', 'SN', 'Senegalas'),
('LT', 'SO', 'Somalis'),
('LT', 'SR', 'Surinamas'),
('LT', 'ST', 'San Tomė ir Prinsipė'),
('LT', 'SV', 'Salvadoras'),
('LT', 'SY', 'Sirija'),
('LT', 'SZ', 'Svazilandas'),
('LT', 'TC', 'Terkso ir Kaikoso salos'),
('LT', 'TD', 'Čadas'),
('LT', 'TF', 'Prancūzijos Pietų Sritys'),
('LT', 'TG', 'Togas'),
('LT', 'TH', 'Tailandas'),
('LT', 'TJ', 'Tadžikija'),
('LT', 'TK', 'Tokelau'),
('LT', 'TL', 'Rytų Timoras'),
('LT', 'TM', 'Turkmėnija'),
('LT', 'TN', 'Tunisas'),
('LT', 'TO', 'Tonga'),
('LT', 'TR', 'Turkija'),
('LT', 'TT', 'Trinidadas ir Tobagas'),
('LT', 'TV', 'Tuvalu'),
('LT', 'TW', 'Taivanas'),
('LT', 'TZ', 'Tanzanija'),
('LT', 'UA', 'Ukraina'),
('LT', 'UG', 'Uganda'),
('LT', 'UM', 'Jungtinių Amerikos Valstijų mažosios aplinkinės salos'),
('LT', 'US', 'Jungtinės Amerikos Valstijos'),
('LT', 'UY', 'Urugvajus'),
('LT', 'UZ', 'Uzbekija'),
('LT', 'VA', 'Vatikanas'),
('LT', 'VC', 'Sent Vinsentas ir Grenadinai'),
('LT', 'VE', 'Venesuela'),
('LT', 'VG', 'Mergelių Salos (Didžioji Britanija)'),
('LT', 'VI', 'Mergelių salos (JAV)'),
('LT', 'VN', 'Vietnamas'),
('LT', 'VU', 'Vanuatu'),
('LT', 'WF', 'Volisas ir Futūna'),
('LT', 'WS', 'Samoa'),
('LT', 'YE', 'Jemenas'),
('LT', 'YT', 'Majotas'),
('LT', 'ZA', 'Pietų Afrikos Respublika'),
('LT', 'ZM', 'Zambija'),
('LT', 'ZW', 'Zimbabvė');

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `classifier_currency`
--

CREATE TABLE `classifier_currency` (
  `CurrencyCode` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `CurrencyDescription` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Sukurta duomenų kopija lentelei `classifier_currency`
--

INSERT INTO `classifier_currency` (`CurrencyCode`, `CurrencyDescription`) VALUES
('AFN', 'afganis'),
('ALL', 'lekas'),
('AMD', 'dramas'),
('ANG', 'Olandijos Antilų guldenas'),
('AOA', 'kvanza'),
('ARS', 'Argentinos pesas'),
('AUD', 'Australijos doleris'),
('AWG', 'Arubos guldenas'),
('AZN', 'Azerbaidžano manatas'),
('BAM', 'konvertuojamoji markė'),
('BBD', 'Barbadoso doleris'),
('BDT', 'taka'),
('BGN', 'levas'),
('BHD', 'Bahreino dinaras'),
('BIF', 'Burundžio frankas'),
('BMD', 'Bermudos doleris'),
('BND', 'Brunėjaus doleris'),
('BOB', 'bolivianas'),
('BRL', 'realas'),
('BSD', 'Bahamų doleris'),
('BTN', 'ngultrumas'),
('BWP', 'pula'),
('BYN', 'Baltarusijos rublis'),
('BZD', 'Belizo doleris'),
('CAD', 'Kanados doleris'),
('CDF', 'Kongo frankas'),
('CHF', 'Šveicarijos frankas'),
('CLP', 'Čilės pesas'),
('CNY', 'ženminbi juanis'),
('COP', 'Kolumbijos pesas'),
('CRC', 'Kosta Rikos kolonas'),
('CUC', 'konvertuojamasis pesas'),
('CUP', 'Kubos pesas'),
('CVE', 'Žaliojo Kyšulio eskudas'),
('CZK', 'Čekijos krona'),
('D', 'JAE dirhamas'),
('DJF', 'Džibučio frankas'),
('DKK', 'Danijos krona'),
('DOP', 'Dominikos pesas'),
('DZD', 'Alžyro dinaras'),
('EGP', 'Egipto svaras'),
('ERN', 'nakfa'),
('ETB', 'biras'),
('EUR', 'euras'),
('FJD', 'Fidžio doleris'),
('FKP', 'Folklando Salų svaras'),
('GBP', 'svaras sterlingų'),
('GEL', 'laris'),
('GGP', 'Gernsio svaras'),
('GHS', 'Ganos sedis'),
('GIP', 'Gibraltaro svaras'),
('GMD', 'dalasis'),
('GNF', 'Gvinėjos frankas'),
('GTQ', 'ketcalis'),
('GYD', 'Gajanos doleris'),
('HKD', 'Honkongo doleris'),
('HNL', 'lempira'),
('HRK', 'kuna'),
('HTG', 'gurdas'),
('HUF', 'forintas'),
('IDR', 'Indonezijos rupija'),
('ILS', 'šekelis'),
('IMP', 'Meno svaras'),
('INR', 'Indijos rupija'),
('IQD', 'Irako dinaras'),
('IRR', 'Irano rialas'),
('ISK', 'Islandijos krona'),
('JEP', 'Džersio svaras'),
('JMD', 'Jamaikos doleris'),
('JOD', 'Jordanijos dinaras'),
('JPY', 'jena'),
('KES', 'Kenijos šilingas'),
('KGS', 'somas'),
('KHR', 'rielis'),
('KMF', 'Komorų frankas'),
('KPW', 'Šiaurės Korėjos vonas'),
('KRW', 'Pietų Korėjos vonas'),
('KWD', 'Kuveito dinaras'),
('KYD', 'Kaimanų Salų doleris'),
('KZT', 'tengė'),
('LAK', 'kipas'),
('LBP', 'Libano svaras'),
('LKR', 'Šri Lankos rupija'),
('LRD', 'Liberijos doleris'),
('LSL', 'lotis (dgs. maločiai)'),
('LYD', 'Libijos dinaras'),
('MAD', 'Maroko dirhamas'),
('MDL', 'Moldovos lėja'),
('MGA', 'ariaris'),
('MKD', 'denaras'),
('MLT', 'Daugiavaliutė'),
('MMK', 'kiatas'),
('MNT', 'tugrikas'),
('MOP', 'pataka'),
('MRO', 'ugija'),
('MUR', 'Mauricijaus rupija'),
('MVR', 'rufija'),
('MWK', 'Malavio kvača'),
('MXN', 'Meksikos pesas'),
('MYR', 'ringitas'),
('MZN', 'metikalis'),
('NAD', 'Namibijos doleris'),
('NGN', 'naira'),
('NIO', 'kordobos oras'),
('NOK', 'Norvegijos krona'),
('NPR', 'Nepalo rupija'),
('NZD', 'Naujosios Zelandijos doleris'),
('OMR', 'Omano rialas'),
('PAB', 'balboja'),
('PEN', 'solis'),
('PGK', 'kina'),
('PHP', 'Filipinų pesas'),
('PKR', 'Pakistano rupija'),
('PLN', 'zlotas'),
('PYG', 'gvaranis'),
('QAR', 'Kataro rialas'),
('RON', 'Rumunijos lėja'),
('RSD', 'Serbijos dinaras'),
('RUB', 'Rusijos rublis'),
('RWF', 'Ruandos frankas'),
('SAR', 'Saudo Arabijos rialas'),
('SBD', 'Saliamono Salų doleris'),
('SCR', 'Seišelių rupija'),
('SDG', 'Sudano svaras'),
('SEK', 'Švedijos krona'),
('SGD', 'Singapūro doleris'),
('SHP', 'Šv. Elenos svaras'),
('SLL', 'leonė'),
('SOS', 'Somalio šilingas'),
('SRD', 'Surinamo doleris'),
('SSP', 'Pietų Sudano svaras'),
('STD', 'dobra'),
('SVC', 'Salvadoro kolonas'),
('SYP', 'Sirijos svaras'),
('SZL', 'lilangenis'),
('THB', 'batas'),
('TJS', 'somonis'),
('TMT', 'Turkmėnistano manatas'),
('TND', 'Tuniso dinaras'),
('TOP', 'panga'),
('TRY', 'Turkijos lira'),
('TTD', 'Trinidado ir Tobago doleris'),
('TWD', 'naujasis Taivano doleris'),
('TZS', 'Tanzanijos šilingas'),
('UAH', 'grivina'),
('UGX', 'Ugandos šilingas'),
('USD', 'JAV doleris'),
('UYU', 'Urugvajaus pesas'),
('UZS', 'sumas'),
('VEF', 'bolivaras'),
('VND', 'dongas'),
('VUV', 'vatu'),
('WST', 'tala'),
('XAF', 'CFA frankas (BEAC)'),
('XCD', 'Rytų Karibų doleris'),
('XOF', 'CFA frankas (BCEAO)'),
('XPF', 'CFP frankas'),
('YER', 'Jemeno rialas'),
('ZAR', 'randas'),
('ZMW', 'Zambijos kvača'),
('ZWL', 'Zimbabvės doleris');

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `classifier_payment_term`
--

CREATE TABLE `classifier_payment_term` (
  `CompanyCode` varchar(4) COLLATE utf8_unicode_ci NOT NULL,
  `PaymentTerm` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `TermDescription` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `TermDays` tinyint(3) UNSIGNED NOT NULL,
  `CalendarDays` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Sukurta duomenų kopija lentelei `classifier_payment_term`
--

INSERT INTO `classifier_payment_term` (`CompanyCode`, `PaymentTerm`, `TermDescription`, `TermDays`, `CalendarDays`) VALUES
('MV00', '5days', '5 kalendorinės dienos', 5, 1);

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `classifier_product`
--

CREATE TABLE `classifier_product` (
  `CompanyCode` varchar(4) COLLATE utf8_unicode_ci NOT NULL,
  `ProductCode` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `GoodsServicesID` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `Description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ForeignDescription` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `UnitOfMeasure` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `DefaultQuantity` float(10,3) NOT NULL,
  `DefaultPrice` float(10,4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Sukurta duomenų kopija lentelei `classifier_product`
--

INSERT INTO `classifier_product` (`CompanyCode`, `ProductCode`, `GoodsServicesID`, `Description`, `ForeignDescription`, `UnitOfMeasure`, `DefaultQuantity`, `DefaultPrice`) VALUES
('MV00', 'APS', 'PS', 'Apskaitos konsultacija', 'Accounting consultation', 'val', 1.000, 0.0000),
('MV00', 'DESIGN', 'PS', 'Maketavimo paslauga', 'Design service', 'vnt', 1.000, 0.0000),
('MV00', 'SAP-FI-CON', 'PS', 'SAP FI konsultacija', 'SAP FI cunsultation', 'val', 1.000, 25.0000);

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `classifier_uom`
--

CREATE TABLE `classifier_uom` (
  `UOMCode` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `UOMDescription` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `ForeignUOMDescription` varchar(100) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Sukurta duomenų kopija lentelei `classifier_uom`
--

INSERT INTO `classifier_uom` (`UOMCode`, `UOMDescription`, `ForeignUOMDescription`) VALUES
('g', 'gramas', 'gram'),
('kg', 'kilogramas', 'kilogram'),
('l', 'litras', 'litre'),
('m', 'metras', 'metre'),
('min', 'minutė', 'minute'),
('simb', 'simbolis', 'symbol'),
('val', 'valanda', 'hour'),
('vnt', 'vienetas', 'unit'),
('žod', 'žodis', 'word');

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `classifier_user`
--

CREATE TABLE `classifier_user` (
  `CompanyCode` varchar(4) COLLATE utf8_unicode_ci NOT NULL,
  `UserID` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `Password` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `First_name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Last_name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Temp_pass` varchar(55) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Temp_pass_activate` tinyint(1) NOT NULL DEFAULT 0,
  `Email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `Active` int(11) NOT NULL DEFAULT 0,
  `Level_access` int(11) NOT NULL DEFAULT 2,
  `Random_key` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Sukurta duomenų kopija lentelei `classifier_user`
--

INSERT INTO `classifier_user` (`CompanyCode`, `UserID`, `Password`, `First_name`, `Last_name`, `Temp_pass`, `Temp_pass_activate`, `Email`, `Active`, `Level_access`, `Random_key`) VALUES
('MV00', 'martynas', 'fe01ce2a7fbac8fafaed7c982a04e229', 'Martynas', 'Vanagas', NULL, 0, 'martynas@vanagas.pro', 1, 1, NULL);

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `classifier_vat_rate`
--

CREATE TABLE `classifier_vat_rate` (
  `VATRate` varchar(6) COLLATE utf8_unicode_ci NOT NULL,
  `VATPercentage` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `Reason` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ForeignReason` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `VATRateDescription` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Sukurta duomenų kopija lentelei `classifier_vat_rate`
--

INSERT INTO `classifier_vat_rate` (`VATRate`, `VATPercentage`, `Reason`, `ForeignReason`, `VATRateDescription`) VALUES
('NEPVM', '', 'Ne PVM objektas', 'Not VAT object', 'Be PVM'),
('PVM1', '21.00', 'Standartinis PVM tarifas', 'Standard VAT rate', 'Šalies teritorijoje patiektos prekės ir / ar suteiktos paslaugos (LR PVMĮ 19 str. 1 dalis)'),
('PVM15', '', 'Ne PVM objektas Lietuvos Respublikoje pagal LR PVMĮ 13 str.', 'Not a VAT object in the Republic of Lithuania according to the VAT Law Article 13', 'Už Lietuvos ribų patiektos prekės ir/ar suteiktos paslaugos (ne PVM objektas Lietuvoje, bet PVM atskaita galima pagal PVMĮ 58 str. 1 dalies 2 punkto nuostatas)');

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `document_sales_header`
--

CREATE TABLE `document_sales_header` (
  `CompanyCode` varchar(4) COLLATE utf8_unicode_ci NOT NULL,
  `Year` varchar(4) COLLATE utf8_unicode_ci NOT NULL,
  `SystemID` bigint(10) UNSIGNED ZEROFILL NOT NULL,
  `Period` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `DocumentType` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `InvoiceSeries` varchar(5) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'MAR',
  `DocumentNo` int(5) UNSIGNED ZEROFILL NOT NULL,
  `DocumentDate` date NOT NULL,
  `PostingDate` date NOT NULL,
  `SystemDate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `DueDate` date NOT NULL,
  `PaymentTerm` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `BankID` int(11) NOT NULL,
  `Customer` int(10) UNSIGNED ZEROFILL NOT NULL,
  `CurrencyDate` date NOT NULL,
  `CurrencyCode` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `CurrencyRate` float(10,6) NOT NULL,
  `DocumentTotalNet` decimal(10,2) NOT NULL COMMENT 'Euro or currency amount',
  `LocalTotalNet` decimal(10,2) NOT NULL COMMENT 'Euro amount',
  `DocumentTotalVAT` decimal(10,2) NOT NULL COMMENT 'Euro or currency amount',
  `LocalTotalVAT` decimal(10,2) NOT NULL COMMENT 'Euro amount',
  `DocumentTotalGross` decimal(10,2) NOT NULL COMMENT 'Euro or currency amount',
  `LocalTotalGross` decimal(10,2) NOT NULL COMMENT 'Euro amount',
  `VATDate` date NOT NULL COMMENT 'Tax Reporting Date',
  `Comment` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Username` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `ReversedWith` bigint(10) UNSIGNED ZEROFILL DEFAULT NULL,
  `ReverseYear` varchar(4) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ReversePeriod` varchar(2) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ReverseDocumentDate` date DEFAULT NULL,
  `ReverseType` varchar(2) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Specifies whether doc. is ReversaL doc. or ReverseD doc.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Sukurta duomenų kopija lentelei `document_sales_header`
--

INSERT INTO `document_sales_header` (`CompanyCode`, `Year`, `SystemID`, `Period`, `DocumentType`, `InvoiceSeries`, `DocumentNo`, `DocumentDate`, `PostingDate`, `SystemDate`, `DueDate`, `PaymentTerm`, `BankID`, `Customer`, `CurrencyDate`, `CurrencyCode`, `CurrencyRate`, `DocumentTotalNet`, `LocalTotalNet`, `DocumentTotalVAT`, `LocalTotalVAT`, `DocumentTotalGross`, `LocalTotalGross`, `VATDate`, `Comment`, `Username`, `ReversedWith`, `ReverseYear`, `ReversePeriod`, `ReverseDocumentDate`, `ReverseType`) VALUES
('MV00', '2019', 0000000001, '10', 'SD', 'MVSF', 00041, '2019-10-29', '2019-10-29', '2019-10-29 17:56:39', '2019-10-29', NULL, 1, 0000500000, '2019-10-29', 'EUR', 1.000000, '25.00', '25.00', '0.00', '0.00', '25.00', '25.00', '2019-10-29', NULL, 'SAP-MARTYNAS', NULL, NULL, NULL, NULL, NULL),
('MV00', '2019', 0000000042, '10', 'SD', 'MVSF', 00042, '2019-10-29', '2019-10-29', '2019-10-29 17:58:12', '2019-10-29', NULL, 1, 0000500000, '2019-10-29', 'EUR', 1.000000, '1.00', '1.00', '0.00', '0.00', '1.00', '1.00', '2019-10-29', NULL, 'SAP-MARTYNAS', NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `document_sales_items`
--

CREATE TABLE `document_sales_items` (
  `CompanyCode` varchar(4) COLLATE utf8_unicode_ci NOT NULL,
  `Year` varchar(4) COLLATE utf8_unicode_ci NOT NULL,
  `SystemID` bigint(10) UNSIGNED ZEROFILL NOT NULL,
  `LineID` smallint(5) UNSIGNED ZEROFILL NOT NULL,
  `LineType` tinyint(1) UNSIGNED DEFAULT NULL,
  `ClearingDate` date DEFAULT NULL,
  `ClearingEntryDate` date DEFAULT NULL,
  `ClearingDocument` bigint(10) DEFAULT NULL,
  `DebitCreditID` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
  `ProductCode` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `Quantity` float(10,3) NOT NULL,
  `DocumentPrice` float(10,4) NOT NULL,
  `LocalPrice` float(10,4) NOT NULL,
  `DiscountRate` decimal(10,2) NOT NULL DEFAULT 0.00,
  `DocumentDiscount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `LocalDiscount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `VATRate` varchar(6) COLLATE utf8_unicode_ci NOT NULL,
  `DocumentVAT` decimal(10,2) NOT NULL,
  `LocalVAT` decimal(10,2) NOT NULL,
  `DocumentNet` decimal(10,2) NOT NULL,
  `LocalNet` decimal(10,2) NOT NULL,
  `DocumentGross` decimal(10,2) NOT NULL,
  `LocalGross` decimal(10,2) NOT NULL,
  `GLAccount` varchar(6) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Comment` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Sukurta duomenų kopija lentelei `document_sales_items`
--

INSERT INTO `document_sales_items` (`CompanyCode`, `Year`, `SystemID`, `LineID`, `LineType`, `ClearingDate`, `ClearingEntryDate`, `ClearingDocument`, `DebitCreditID`, `ProductCode`, `Quantity`, `DocumentPrice`, `LocalPrice`, `DiscountRate`, `DocumentDiscount`, `LocalDiscount`, `VATRate`, `DocumentVAT`, `LocalVAT`, `DocumentNet`, `LocalNet`, `DocumentGross`, `LocalGross`, `GLAccount`, `Comment`) VALUES
('MV00', '2019', 0000000001, 00001, NULL, NULL, NULL, NULL, 'K', 'SAP-FI-CON', 1.000, 25.0000, 25.0000, '0.00', '0.00', '0.00', 'NEPVM', '0.00', '0.00', '25.00', '25.00', '25.00', '25.00', NULL, NULL),
('MV00', '2019', 0000000042, 00001, NULL, NULL, NULL, NULL, 'K', 'APS', 1.000, 1.0000, 1.0000, '0.00', '0.00', '0.00', 'NEPVM', '0.00', '0.00', '1.00', '1.00', '1.00', '1.00', NULL, NULL);

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `system_log`
--

CREATE TABLE `system_log` (
  `ID` bigint(20) UNSIGNED ZEROFILL NOT NULL,
  `UserID` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `TimeStamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `Action` varchar(4) COLLATE utf8_unicode_ci NOT NULL,
  `MetaData` varchar(4) COLLATE utf8_unicode_ci DEFAULT NULL,
  `DocumentID` bigint(10) UNSIGNED ZEROFILL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `classifier_company`
--
ALTER TABLE `classifier_company`
  ADD PRIMARY KEY (`CompanyCode`,`Code`) USING BTREE,
  ADD UNIQUE KEY `Code` (`Code`,`VATCode`) USING BTREE;

--
-- Indexes for table `classifier_company_bank`
--
ALTER TABLE `classifier_company_bank`
  ADD PRIMARY KEY (`BankID`),
  ADD UNIQUE KEY `IBAN` (`IBAN`),
  ADD UNIQUE KEY `IBAN_2` (`IBAN`);

--
-- Indexes for table `classifier_contractor`
--
ALTER TABLE `classifier_contractor`
  ADD PRIMARY KEY (`ID`,`CompanyCode`,`Code`) USING BTREE,
  ADD KEY `CompanyID` (`CompanyCode`),
  ADD KEY `ID` (`ID`),
  ADD KEY `Code` (`Code`),
  ADD KEY `VATCode` (`VATCode`),
  ADD KEY `Name` (`Name`);

--
-- Indexes for table `classifier_contractor_group`
--
ALTER TABLE `classifier_contractor_group`
  ADD PRIMARY KEY (`CompanyCode`,`GroupID`) USING BTREE,
  ADD KEY `GroupID` (`GroupID`);

--
-- Indexes for table `classifier_counter`
--
ALTER TABLE `classifier_counter`
  ADD PRIMARY KEY (`CompanyCode`,`DocumentType`);

--
-- Indexes for table `classifier_country`
--
ALTER TABLE `classifier_country`
  ADD PRIMARY KEY (`Language`,`CountryCode`) USING BTREE,
  ADD KEY `CountryCode` (`CountryCode`),
  ADD KEY `Language` (`Language`);

--
-- Indexes for table `classifier_currency`
--
ALTER TABLE `classifier_currency`
  ADD PRIMARY KEY (`CurrencyCode`),
  ADD KEY `CurrencyCode` (`CurrencyCode`);

--
-- Indexes for table `classifier_payment_term`
--
ALTER TABLE `classifier_payment_term`
  ADD PRIMARY KEY (`CompanyCode`,`PaymentTerm`) USING BTREE,
  ADD KEY `CompanyCode` (`CompanyCode`,`PaymentTerm`) USING BTREE;

--
-- Indexes for table `classifier_product`
--
ALTER TABLE `classifier_product`
  ADD PRIMARY KEY (`CompanyCode`,`ProductCode`) USING BTREE,
  ADD KEY `CompanyCode` (`CompanyCode`),
  ADD KEY `ProductCode` (`ProductCode`);

--
-- Indexes for table `classifier_uom`
--
ALTER TABLE `classifier_uom`
  ADD PRIMARY KEY (`UOMCode`),
  ADD KEY `UOMCode` (`UOMCode`),
  ADD KEY `UOMDescription` (`UOMDescription`);

--
-- Indexes for table `classifier_user`
--
ALTER TABLE `classifier_user`
  ADD PRIMARY KEY (`CompanyCode`,`UserID`) USING BTREE,
  ADD KEY `CompanyCode` (`CompanyCode`),
  ADD KEY `Username` (`UserID`,`Email`) USING BTREE;

--
-- Indexes for table `classifier_vat_rate`
--
ALTER TABLE `classifier_vat_rate`
  ADD PRIMARY KEY (`VATRate`),
  ADD KEY `VATRate` (`VATRate`),
  ADD KEY `VATPercentage` (`VATPercentage`);

--
-- Indexes for table `document_sales_header`
--
ALTER TABLE `document_sales_header`
  ADD PRIMARY KEY (`CompanyCode`,`Year`,`SystemID`) USING BTREE,
  ADD KEY `CompanyCode` (`CompanyCode`) USING BTREE,
  ADD KEY `ID` (`SystemID`),
  ADD KEY `Customer` (`Customer`),
  ADD KEY `Year` (`Year`),
  ADD KEY `Period` (`Period`),
  ADD KEY `DocumentDate` (`DocumentDate`),
  ADD KEY `PostingDate` (`PostingDate`),
  ADD KEY `DocumentType` (`DocumentType`),
  ADD KEY `DocumentNo` (`DocumentNo`);

--
-- Indexes for table `document_sales_items`
--
ALTER TABLE `document_sales_items`
  ADD PRIMARY KEY (`CompanyCode`,`Year`,`SystemID`,`LineID`),
  ADD KEY `DocumentNo` (`LineID`),
  ADD KEY `CompanyCode` (`CompanyCode`),
  ADD KEY `ID` (`SystemID`),
  ADD KEY `ProductCode` (`ProductCode`),
  ADD KEY `Year` (`Year`);

--
-- Indexes for table `system_log`
--
ALTER TABLE `system_log`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `classifier_company_bank`
--
ALTER TABLE `classifier_company_bank`
  MODIFY `BankID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `system_log`
--
ALTER TABLE `system_log`
  MODIFY `ID` bigint(20) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
