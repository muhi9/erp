<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use BaseBundle\DBAL\Migrations\BaseMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240923_INIT extends BaseMigration
{
	private $tree = [
		[
			'name' => 'User',
			'type' => ['name_key'=>'module.root', 'name'=>'Root Module name'],
			'sub' => [
				[
					'name' => 'Company Type',
					'type' => ['name_key'=>'user.root_elements', 'name'=>'User'],
					'sub' => [
						['name' => 'LTD', 'type' => ['name_key'=>'user.company_type', 'name'=>'Company type']],
						['name' => 'PLC', 'type' => ['name_key'=>'user.company_type', 'name'=>'Company type']],
						['name' => 'AD', 'type' => ['name_key'=>'user.company_type', 'name'=>'Company type']],
					],
				], [
					'name' => 'Contacts',
					'type' => ['name_key'=>'user.root_elements', 'name'=>'User'],
					'sub' => [
						[
							'name' => 'Address',
							'type' => ['name_key'=>'user.contacts', 'name'=>'Contact Types'],
							'sub' => [
								['name' => 'Home', 'type' => ['name_key'=>'user.address', 'name'=>'Address']],
								['name' => 'Work', 'type' => ['name_key'=>'user.address', 'name'=>'Address']],
								['name' => 'School', 'type' => ['name_key'=>'user.address', 'name'=>'Address']],
								['name' => 'Place of Birth', 'type' => ['name_key'=>'user.address', 'name'=>'Address']],
							],
						], [
							'name' => 'Phone',
							'type' => ['name_key'=>'user.contacts', 'name'=>'Contact Types'],
							'sub' => [
								['name' => 'Mobile', 'type' => ['name_key'=>'user.phone', 'name'=>'Phone']],
								['name' => 'Home', 'type' => ['name_key'=>'user.phone', 'name'=>'Phone']],
								['name' => 'Work', 'type' => ['name_key'=>'user.phone', 'name'=>'Phone']],
								['name' => 'Fax', 'type' => ['name_key'=>'user.phone', 'name'=>'Phone']],
								['name' => 'Pager', 'type' => ['name_key'=>'user.phone', 'name'=>'Phone']],
							],
						], [
							'name' => 'Mail',
							'type' => ['name_key'=>'user.contacts', 'name'=>'Contact Types'],
							'sub' => [
								['name' => 'Home', 'type' => ['name_key'=>'user.mail', 'name'=>'Mail']],
								['name' => 'Work', 'type' => ['name_key'=>'user.mail', 'name'=>'Mail']],
								['name' => 'School', 'type' => ['name_key'=>'user.mail', 'name'=>'Mail']],
							],
						], [
							'name' => 'URL',
							'type' => ['name_key'=>'user.contacts', 'name'=>'Contact Types'],
							'sub' => [
								['name' => 'Home', 'type' => ['name_key'=>'user.url', 'name'=>'URL']],
								['name' => 'Work', 'type' => ['name_key'=>'user.url', 'name'=>'URL']],
								['name' => 'School', 'type' => ['name_key'=>'user.url', 'name'=>'URL']],
							],
						], [
							'name' => 'Social Network Profile',
							'type' => ['name_key'=>'user.contacts', 'name'=>'Contact Types'],
							'sub' => [
								['name' => 'Facebook', 'type' => ['name_key'=>'user.soc', 'name'=>'Social Network Profile']],
								['name' => 'Twitter', 'type' => ['name_key'=>'user.soc', 'name'=>'Social Network Profile']],
								['name' => 'LinkedIn', 'type' => ['name_key'=>'user.soc', 'name'=>'Social Network Profile']],
							],
						], [
							'name' => 'IM',
							'type' => ['name_key'=>'user.contacts', 'name'=>'Contact Types'],
							'sub' => [
								['name' => 'Messenger', 'type' => ['name_key'=>'user.im', 'name'=>'IM']],
								['name' => 'Viber', 'type' => ['name_key'=>'user.im', 'name'=>'IM']],
								['name' => 'Telegram', 'type' => ['name_key'=>'user.im', 'name'=>'IM']],
								['name' => 'WhatsApp', 'type' => ['name_key'=>'user.im', 'name'=>'IM']],
								['name' => 'Skype', 'type' => ['name_key'=>'user.im', 'name'=>'IM']],
							],
						],
					],
				], [
					'name' => 'Type',
					'type' => ['name_key'=>'user.root_elements', 'name'=>'User'],
					'sub' => [
						[
							'name' => 'Natural Person',
							'type' => ['name_key'=>'user.type', 'name'=>'Type'],
							'bnom_key' => 'person',
							'sub' => [
								['name' => 'Supplier', 'type' => ['name_key'=>'user.sub_type', 'name'=>'Sub Type'], 'bnom_key' => 'ROLE_SUPPLIER'],
								['name' => 'Client', 'type' => ['name_key'=>'user.sub_type', 'name'=>'Sub Type'], 'bnom_key' => 'ROLE_CLIENT'],
								['name' => 'Service Provider', 'type' => ['name_key'=>'user.sub_type', 'name'=>'Sub Type'], 'bnom_key' => 'ROLE_SERVICE_PROVIDER'],
								['name' => 'Admin', 'type' => ['name_key'=>'user.sub_type', 'name'=>'Sub Type'], 'bnom_key' => 'ROLE_ADMIN'],
							],
						], [
							'name' => 'Legal Person',
							'type' => ['name_key'=>'user.type', 'name'=>'Type'],
							'bnom_key' => 'company',
							'sub' => [
								['name' => 'Supplier', 'type' => ['name_key'=>'user.sub_type', 'name'=>'Sub Type'], 'bnom_key' => 'ROLE_SUPPLIER'],
								['name' => 'Client', 'type' => ['name_key'=>'user.sub_type', 'name'=>'Sub Type'], 'bnom_key' => 'ROLE_CLIENT'],
								['name' => 'Service Provider', 'type' => ['name_key'=>'user.sub_type', 'name'=>'Sub Type'], 'bnom_key' => 'ROLE_SERVICE_PROVIDER'],
							],
						],
					],
				],
			],
		], [
			'name' => 'Prices',
			'type' => ['name_key'=>'module.root', 'name'=>'Root Module name'],
			'sub' => [
				[
					'name' => 'Currencies',
					'type' => ['name_key'=>'prices.root_elements', 'name'=>'Prices'],
					'sub' => [
						['name' => 'BGN', 'type' => ['name_key'=>'price.currency', 'name'=>'Currency', 'extra_field'=>["display","decimals"]], 'extra' => ['display'=>'{price} лв.', 'decimals'=>'2']],
						['name' => 'EUR', 'type' => ['name_key'=>'price.currency', 'name'=>'Currency', 'extra_field'=>["display","decimals"]], 'extra' => ['display'=>'€ {price}', 'decimals'=>'2']],
						['name' => 'USD', 'type' => ['name_key'=>'price.currency', 'name'=>'Currency', 'extra_field'=>["display","decimals"]], 'extra' => ['display'=>'$ {price}', 'decimals'=>'2']],
					],
				], [
					'name' => 'User prices',
					'type' => ['name_key'=>'prices.root_elements', 'name'=>'Prices'],
					'sub' => [
					],
				],
			],
		], [
			'name' => 'Messages',
			'type' => ['name_key'=>'module.root', 'name'=>'Root Module name'],
			'sub' => [
				[
					'name' => 'Message Types',
					'type' => ['name_key'=>'message.root_elements', 'name'=>'Messages'],
					'sub' => [
						['name' => 'SMS', 'type' => ['name_key'=>'message.type', 'name'=>'Type'], 'bnom_key'=>'sms'],
						['name' => 'Email', 'type' => ['name_key'=>'message.type', 'name'=>'Type'], 'bnom_key'=>'email'],
						['name' => 'Telegram', 'type' => ['name_key'=>'message.type', 'name'=>'Type'], 'bnom_key'=>'telegram'],
						['name' => 'Notification', 'type' => ['name_key'=>'message.type', 'name'=>'Type'], 'bnom_key'=>'notification'],
					],
				], [
					'name' => 'Messages Severity',
					'type' => ['name_key'=>'message.root_elements', 'name'=>'Messages'],
					'sub' => [
						['name' => 'Notice', 'type' => ['name_key'=>'message.severity', 'name'=>'Severity'], 'bnom_key'=>'notice'],
						['name' => 'Warning', 'type' => ['name_key'=>'message.severity', 'name'=>'Severity'], 'bnom_key'=>'warning'],
						['name' => 'Error', 'type' => ['name_key'=>'message.severity', 'name'=>'Severity'], 'bnom_key'=>'error'],
						['name' => 'Important', 'type' => ['name_key'=>'message.severity', 'name'=>'Severity'], 'bnom_key'=>'important'],
					],
				], [
					'name' => 'Message Queue Status',
					'type' => ['name_key'=>'message.root_elements', 'name'=>'Messages'],
					'sub' => [
						['name' => 'Pending', 'type' => ['name_key'=>'message.queue_status', 'name'=>'Status'], 'bnom_key'=>'pending'],
						['name' => 'Sending', 'type' => ['name_key'=>'message.queue_status', 'name'=>'Status'], 'bnom_key'=>'sending'],
						['name' => 'Sent', 'type' => ['name_key'=>'message.queue_status', 'name'=>'Status'], 'bnom_key'=>'sent'],
						['name' => 'Error', 'type' => ['name_key'=>'message.queue_status', 'name'=>'Status'], 'bnom_key'=>'error'],
					],
				],
			],
		], 
	];

	private $manualNomTypes = [
		'PRE' => [ // insert before $tree
			[
				'name_key' => 'module.root',
				'name' => 'Root Module name',
				'parent_name_key' => NULL,
				'parent_name_key1' => NULL,
				'status' => 1,
				'descr' => 'Root type for setting name of the module sub-leafs will use.',
				'extra_field' => NULL,
			], [
				'name_key' => 'dummy',
				'name'=> 'Dummy nom type',
				'parent_name_key' => NULL,
				'parent_name_key1' => NULL,
				'status' => 1,
				'descr' => 'Dummy nomtype for dummy test/records',
				'extra_field' => NULL,
			],
		],
		
	];

	private $truncate = [
		'base_noms', 'base_noms_extra', 'base_noms_links', 'nom_type',
		'fos_user', 'user_address', 'user_bank_account', 'user_contact',  'user_personal_info', 'user_profile_role_types',
		'language', 'settings', 'country',

	];

		

	



	private $settings = [
		[
			'name' => 'nomtype settings',
			'status' => 1,
			'type' => 'nomtype',
			'settings' => [
				'sa' => 0,
				'automin' => 5
			]
		]
	];

	private $country = [
		['name'=>'Afghanistan',									 'iso_2l'=>'AF', 'iso_3l'=>'AFG', 'nativeName'=>NULL, 'capital'=>'Kabul', 'tz'=>'UTC+04:30', 'currency'=>'Afghan afghani (AFN, ؋)', 'phonecode'=>'+93'],
		['name'=>'Åland Islands',								 'iso_2l'=>'AX', 'iso_3l'=>'ALA', 'nativeName'=>NULL, 'capital'=>'Mariehamn', 'tz'=>'', 'currency'=>'Euro (EUR, €)', 'phonecode'=>'+358'],
		['name'=>'Albania',										 'iso_2l'=>'AL', 'iso_3l'=>'ALB', 'nativeName'=>NULL, 'capital'=>'Tirana', 'tz'=>'UTC+01:00', 'currency'=>'Albanian lek (ALL, L)', 'phonecode'=>'+355'],
		['name'=>'Algeria',										 'iso_2l'=>'DZ', 'iso_3l'=>'DZA', 'nativeName'=>NULL, 'capital'=>'Algiers', 'tz'=>'UTC', 'currency'=>'Algerian dinar (DZD, د.ج)', 'phonecode'=>'+213'],
		['name'=>'American Samoa',								 'iso_2l'=>'AS', 'iso_3l'=>'ASM', 'nativeName'=>NULL, 'capital'=>'Pago Pago', 'tz'=>'UTC-11:00', 'currency'=>'', 'phonecode'=>'+1 684'],
		['name'=>'Andorra',										 'iso_2l'=>'AD', 'iso_3l'=>'AND', 'nativeName'=>NULL, 'capital'=>'Andorra la Vella', 'tz'=>'UTC+01:00', 'currency'=>'Euro (EUR, €)', 'phonecode'=>'+376'],
		['name'=>'Angola',										 'iso_2l'=>'AO', 'iso_3l'=>'AGO', 'nativeName'=>NULL, 'capital'=>'Luanda', 'tz'=>'UTC+01:00', 'currency'=>'Angolan kwanza (AOA, Kz)', 'phonecode'=>'+244'],
		['name'=>'Anguilla',									 'iso_2l'=>'AI', 'iso_3l'=>'AIA', 'nativeName'=>NULL, 'capital'=>'The Valley', 'tz'=>'UTC-04:00', 'currency'=>'East Caribbean dollar (XCD, $)', 'phonecode'=>'+1 264'],
		['name'=>'Antarctica',									 'iso_2l'=>'AQ', 'iso_3l'=>'ATA', 'nativeName'=>NULL, 'capital'=>'', 'tz'=>'', 'currency'=>'', 'phonecode'=>''],
		['name'=>'Antigua and Barbuda',							 'iso_2l'=>'AG', 'iso_3l'=>'ATG', 'nativeName'=>NULL, 'capital'=>'St. John\'s', 'tz'=>'UTC-04:00', 'currency'=>'East Caribbean dollar (XCD, $)', 'phonecode'=>'+1 268'],
		['name'=>'Argentina',									 'iso_2l'=>'AR', 'iso_3l'=>'ARG', 'nativeName'=>NULL, 'capital'=>'Buenos Aires', 'tz'=>'UTC-03:00', 'currency'=>'Argentine peso (ARS, $)', 'phonecode'=>'+54'],
		['name'=>'Armenia',										 'iso_2l'=>'AM', 'iso_3l'=>'ARM', 'nativeName'=>NULL, 'capital'=>'Yerevan', 'tz'=>'UTC+04:00', 'currency'=>'Armenian dram (AMD, դր.)', 'phonecode'=>'+374'],
		['name'=>'Aruba',										 'iso_2l'=>'AW', 'iso_3l'=>'ABW', 'nativeName'=>NULL, 'capital'=>'Oranjestad', 'tz'=>'UTC-04:00', 'currency'=>'Aruban florin (AWG, ƒ)', 'phonecode'=>'+297'],
		['name'=>'Australia',									 'iso_2l'=>'AU', 'iso_3l'=>'AUS', 'nativeName'=>NULL, 'capital'=>'Canberra', 'tz'=>'UTC+07:00 - UTC+10:00', 'currency'=>'Australian dollar (AUD, $)', 'phonecode'=>'+61'],
		['name'=>'Austria',										 'iso_2l'=>'AT', 'iso_3l'=>'AUT', 'nativeName'=>NULL, 'capital'=>'Vienna', 'tz'=>'UTC+01:00', 'currency'=>'Euro (EUR, €)', 'phonecode'=>'+43'],
		['name'=>'Azerbaijan',									 'iso_2l'=>'AZ', 'iso_3l'=>'AZE', 'nativeName'=>NULL, 'capital'=>'Baku', 'tz'=>'UTC+04:00', 'currency'=>'Azerbaijani manat (AZN, )', 'phonecode'=>'+994'],
		['name'=>'Bahamas',										 'iso_2l'=>'BS', 'iso_3l'=>'BHS', 'nativeName'=>NULL, 'capital'=>'Nassau', 'tz'=>'UTC-05:00', 'currency'=>'Bahamian dollar (BSD, $)', 'phonecode'=>'+1 242'],
		['name'=>'Bahrain',										 'iso_2l'=>'BH', 'iso_3l'=>'BHR', 'nativeName'=>NULL, 'capital'=>'Manama', 'tz'=>'UTC+03:00', 'currency'=>'Bahraini dinar (BHD, .د.ب)', 'phonecode'=>'+973'],
		['name'=>'Bangladesh',									 'iso_2l'=>'BD', 'iso_3l'=>'BGD', 'nativeName'=>NULL, 'capital'=>'Dhaka', 'tz'=>'UTC+06:00', 'currency'=>'Bangladeshi taka (BDT, ৳)', 'phonecode'=>'+880'],
		['name'=>'Barbados',									 'iso_2l'=>'BB', 'iso_3l'=>'BRB', 'nativeName'=>NULL, 'capital'=>'Bridgetown', 'tz'=>'UTC-04:00', 'currency'=>'Barbadian dollar (BBD, $)', 'phonecode'=>'+1 246'],
		['name'=>'Belarus',										 'iso_2l'=>'BY', 'iso_3l'=>'BLR', 'nativeName'=>NULL, 'capital'=>'Minsk', 'tz'=>'UTC+03:00', 'currency'=>'Belarusian ruble (BYR, Br)', 'phonecode'=>'+375'],
		['name'=>'Belgium',										 'iso_2l'=>'BE', 'iso_3l'=>'BEL', 'nativeName'=>NULL, 'capital'=>'Brussels', 'tz'=>'UTC+01:00', 'currency'=>'Euro (EUR, €)', 'phonecode'=>'+32'],
		['name'=>'Belize',										 'iso_2l'=>'BZ', 'iso_3l'=>'BLZ', 'nativeName'=>NULL, 'capital'=>'Belmopan', 'tz'=>'UTC-06:00', 'currency'=>'Belize dollar (BZD, $)', 'phonecode'=>'+501'],
		['name'=>'Benin',										 'iso_2l'=>'BJ', 'iso_3l'=>'BEN', 'nativeName'=>NULL, 'capital'=>'Porto-Novo', 'tz'=>'UTC+01:00', 'currency'=>'West African CFA franc (XOF, Fr)', 'phonecode'=>'+229'],
		['name'=>'Bermuda',										 'iso_2l'=>'BM', 'iso_3l'=>'BMU', 'nativeName'=>NULL, 'capital'=>'Hamilton', 'tz'=>'UTC-04:00', 'currency'=>'Bermudian dollar (BMD, $)', 'phonecode'=>'+1 441'],
		['name'=>'Bhutan',										 'iso_2l'=>'BT', 'iso_3l'=>'BTN', 'nativeName'=>NULL, 'capital'=>'Thimphu', 'tz'=>'UTC+05:30', 'currency'=>'Bhutanese ngultrum (BTN, Nu.), Indian rupee (INR, ₹)', 'phonecode'=>'+975'],
		['name'=>'Bolivia',										 'iso_2l'=>'BO', 'iso_3l'=>'BOL', 'nativeName'=>NULL, 'capital'=>'Sucre', 'tz'=>'UTC-04:00', 'currency'=>'Bolivian boliviano (BOB, Bs.)', 'phonecode'=>'+591'],
		['name'=>'Bonaire, Sint Eustatius and Saba',			 'iso_2l'=>'BQ', 'iso_3l'=>'BES', 'nativeName'=>NULL, 'capital'=>'', 'tz'=>'', 'currency'=>'United States dollar (USD, $)', 'phonecode'=>''],
		['name'=>'Bosnia and Herzegovina',						 'iso_2l'=>'BA', 'iso_3l'=>'BIH', 'nativeName'=>NULL, 'capital'=>'Sarajevo', 'tz'=>'UTC+01:00', 'currency'=>'Bosnia and Herzegovina convertible mark (BAM, KM or КМ)', 'phonecode'=>'+387'],
		['name'=>'Botswana',									 'iso_2l'=>'BW', 'iso_3l'=>'BWA', 'nativeName'=>NULL, 'capital'=>'Gaborone', 'tz'=>'UTC+02:00', 'currency'=>'Botswana pula (BWP, P)', 'phonecode'=>'+267'],
		['name'=>'Bouvet Island',								 'iso_2l'=>'BV', 'iso_3l'=>'BVT', 'nativeName'=>NULL, 'capital'=>'', 'tz'=>'', 'currency'=>'', 'phonecode'=>''],
		['name'=>'Brazil',										 'iso_2l'=>'BR', 'iso_3l'=>'BRA', 'nativeName'=>NULL, 'capital'=>'Brasília', 'tz'=>'UTC-05:00 - UTC-03:00', 'currency'=>'Brazilian real (BRL, R$)', 'phonecode'=>'+55'],
		['name'=>'British Indian Ocean Territory',				 'iso_2l'=>'IO', 'iso_3l'=>'IOT', 'nativeName'=>NULL, 'capital'=>'Diego Garcia', 'tz'=>'UTC+06', 'currency'=>'United States dollar (USD, $)', 'phonecode'=>'+246'],
		['name'=>'Brunei',										 'iso_2l'=>'BN', 'iso_3l'=>'BRN', 'nativeName'=>NULL, 'capital'=>'Bandar Seri Begawan', 'tz'=>'UTC+08:00', 'currency'=>'Brunei dollar (BND, $), Singapore dollar (SGD, $)', 'phonecode'=>'+673'],
		['name'=>'Bulgaria',									 'iso_2l'=>'BG', 'iso_3l'=>'BGR', 'nativeName'=>NULL, 'capital'=>'Sofia', 'tz'=>'UTC+02:00', 'currency'=>'Bulgarian lev (BGN, лв)', 'phonecode'=>'+359'],
		['name'=>'Burkina Faso',								 'iso_2l'=>'BF', 'iso_3l'=>'BFA', 'nativeName'=>NULL, 'capital'=>'Ouagadougou', 'tz'=>'UTC', 'currency'=>'West African CFA franc (XOF, Fr)', 'phonecode'=>'+226'],
		['name'=>'Burundi',										 'iso_2l'=>'BI', 'iso_3l'=>'BDI', 'nativeName'=>NULL, 'capital'=>'Bujumbura', 'tz'=>'UTC+02:00', 'currency'=>'Burundian franc (BIF, Fr)', 'phonecode'=>'+257'],
		['name'=>'Cambodia',									 'iso_2l'=>'KH', 'iso_3l'=>'KHM', 'nativeName'=>NULL, 'capital'=>'Phnom Penh', 'tz'=>'UTC+07:00', 'currency'=>'Cambodian riel (KHR, ៛)', 'phonecode'=>'+855'],
		['name'=>'Cameroon',									 'iso_2l'=>'CM', 'iso_3l'=>'CMR', 'nativeName'=>NULL, 'capital'=>'Yaoundé', 'tz'=>'UTC+01:00', 'currency'=>'Central African CFA franc (XAF, Fr)', 'phonecode'=>'+237'],
		['name'=>'Canada',										 'iso_2l'=>'CA', 'iso_3l'=>'CAN', 'nativeName'=>NULL, 'capital'=>'Ottawa', 'tz'=>'UTC-08:00 - UTC-04:00', 'currency'=>'Canadian dollar (CAD, $)', 'phonecode'=>'+1'],
		['name'=>'Cape Verde',									 'iso_2l'=>'CV', 'iso_3l'=>'CPV', 'nativeName'=>NULL, 'capital'=>'Praia', 'tz'=>'UTC-01:00', 'currency'=>'Cape Verdean escudo (CVE, Esc or $)', 'phonecode'=>'+238'],
		['name'=>'Cayman Islands',								 'iso_2l'=>'KY', 'iso_3l'=>'CYM', 'nativeName'=>NULL, 'capital'=>'George Town', 'tz'=>'UTC-05:00', 'currency'=>'Cayman Islands dollar (KYD, $)', 'phonecode'=>'+1 345'],
		['name'=>'Central African Republic',					 'iso_2l'=>'CF', 'iso_3l'=>'CAF', 'nativeName'=>NULL, 'capital'=>'Bangui', 'tz'=>'UTC+01:00', 'currency'=>'Central African CFA franc (XAF, Fr)', 'phonecode'=>'+236'],
		['name'=>'Chad',										 'iso_2l'=>'TD', 'iso_3l'=>'TCD', 'nativeName'=>NULL, 'capital'=>'N\Djamena', 'tz'=>'UTC+01:00', 'currency'=>'Central African CFA franc (XAF, Fr)', 'phonecode'=>'+235'],
		['name'=>'Chile',										 'iso_2l'=>'CL', 'iso_3l'=>'CHL', 'nativeName'=>NULL, 'capital'=>'Santiago', 'tz'=>'UTC-04:00', 'currency'=>'Chilean peso (CLP, $)', 'phonecode'=>'+56'],
		['name'=>'China',										 'iso_2l'=>'CN', 'iso_3l'=>'CHN', 'nativeName'=>NULL, 'capital'=>'Beijing', 'tz'=>'UTC+08:00', 'currency'=>'Chinese yuan (CNY, ¥ or 元)', 'phonecode'=>'+86'],
		['name'=>'Christmas Island',							 'iso_2l'=>'CX', 'iso_3l'=>'CXR', 'nativeName'=>NULL, 'capital'=>'Flying Fish Cove', 'tz'=>'UTC+07', 'currency'=>'Australian dollar (AUD, $)', 'phonecode'=>'+61 8 9164'],
		['name'=>'Cocos (Keeling) Islands',						 'iso_2l'=>'CC', 'iso_3l'=>'CCK', 'nativeName'=>NULL, 'capital'=>'West Island', 'tz'=>'UTC+06:30', 'currency'=>'Australian dollar (AUD, $), Cocos (Keeling) Islands dollar (None, $)', 'phonecode'=>'+61 8 9162'],
		['name'=>'Colombia',									 'iso_2l'=>'CO', 'iso_3l'=>'COL', 'nativeName'=>NULL, 'capital'=>'Bogotá', 'tz'=>'UTC-05:00', 'currency'=>'Colombian peso (COP, $)', 'phonecode'=>'+57'],
		['name'=>'Comoros',										 'iso_2l'=>'KM', 'iso_3l'=>'COM', 'nativeName'=>NULL, 'capital'=>'Moroni', 'tz'=>'UTC+03:00', 'currency'=>'Comorian franc (KMF, Fr)', 'phonecode'=>''],
		['name'=>'Congo, Republic of the',						 'iso_2l'=>'CG', 'iso_3l'=>'COG', 'nativeName'=>NULL, 'capital'=>'Brazzaville', 'tz'=>'UTC+01:00', 'currency'=>'Central African CFA franc (XAF, Fr)', 'phonecode'=>'+242'],
		['name'=>'Congo, the Democratic Republic of the',		 'iso_2l'=>'CD', 'iso_3l'=>'COD', 'nativeName'=>NULL, 'capital'=>'Kinshasa', 'tz'=>'UTC+01:00 - UTC+02:00', 'currency'=>'Congolese franc (CDF, Fr)', 'phonecode'=>'+243'],
		['name'=>'Cook Islands',								 'iso_2l'=>'CK', 'iso_3l'=>'COK', 'nativeName'=>NULL, 'capital'=>'Avarua', 'tz'=>'UTC-10:00', 'currency'=>'New Zealand dollar (NZD, $), Cook Islands dollar (None, $)', 'phonecode'=>'+682'],
		['name'=>'Costa Rica',									 'iso_2l'=>'CR', 'iso_3l'=>'CRI', 'nativeName'=>NULL, 'capital'=>'San José', 'tz'=>'UTC-06:00', 'currency'=>'Costa Rican colón (CRC, ₡)', 'phonecode'=>'+506'],
		['name'=>'Côte d\'Ivoire',								 'iso_2l'=>'CI', 'iso_3l'=>'CIV', 'nativeName'=>NULL, 'capital'=>'Yamoussoukro', 'tz'=>'', 'currency'=>'West African CFA franc (XOF, Fr)', 'phonecode'=>'+225'],
		['name'=>'Croatia',										 'iso_2l'=>'HR', 'iso_3l'=>'HRV', 'nativeName'=>NULL, 'capital'=>'Zagreb', 'tz'=>'UTC+01:00', 'currency'=>'Croatian kuna (HRK, kn)', 'phonecode'=>'+385'],
		['name'=>'Cuba',										 'iso_2l'=>'CU', 'iso_3l'=>'CUB', 'nativeName'=>NULL, 'capital'=>'Havana', 'tz'=>'UTC-03:00', 'currency'=>'Cuban convertible peso (CUC, $), Cuban peso (CUP, $)', 'phonecode'=>'+53'],
		['name'=>'Curaçao',										 'iso_2l'=>'CW', 'iso_3l'=>'CUW', 'nativeName'=>NULL, 'capital'=>'Willemstad', 'tz'=>'', 'currency'=>'Netherlands Antillean guilder (ANG, ƒ)', 'phonecode'=>'+599 9'],
		['name'=>'Cyprus',										 'iso_2l'=>'CY', 'iso_3l'=>'CYP', 'nativeName'=>NULL, 'capital'=>'Nicosia', 'tz'=>'UTC+02:00', 'currency'=>'Euro (EUR, €)', 'phonecode'=>'+357'],
		['name'=>'Czech Republic',								 'iso_2l'=>'CZ', 'iso_3l'=>'CZE', 'nativeName'=>NULL, 'capital'=>'Prague', 'tz'=>'UTC+01:00', 'currency'=>'Czech koruna (CZK, Kč)', 'phonecode'=>'+420'],
		['name'=>'Denmark',										 'iso_2l'=>'DK', 'iso_3l'=>'DNK', 'nativeName'=>NULL, 'capital'=>'Copenhagen', 'tz'=>'UTC+01:00', 'currency'=>'Danish krone (DKK, kr)', 'phonecode'=>'+45'],
		['name'=>'Djibouti',									 'iso_2l'=>'DJ', 'iso_3l'=>'DJI', 'nativeName'=>NULL, 'capital'=>'Djibouti', 'tz'=>'UTC+03:00', 'currency'=>'Djiboutian franc (DJF, Fr)', 'phonecode'=>'+253'],
		['name'=>'Dominica',									 'iso_2l'=>'DM', 'iso_3l'=>'DMA', 'nativeName'=>NULL, 'capital'=>'Roseau', 'tz'=>'UTC-04:00', 'currency'=>'East Caribbean dollar (XCD, $)', 'phonecode'=>'+1 767'],
		['name'=>'Dominican Republic',							 'iso_2l'=>'DO', 'iso_3l'=>'DOM', 'nativeName'=>NULL, 'capital'=>'Santo Domingo', 'tz'=>'UTC-04:00', 'currency'=>'Dominican peso (DOP, $)', 'phonecode'=>'+1 809 / 829 / 849'],
		['name'=>'East Timor',									 'iso_2l'=>'TL', 'iso_3l'=>'TLS', 'nativeName'=>NULL, 'capital'=>'Dili', 'tz'=>'UTC+09', 'currency'=>'United States dollar (USD, $)', 'phonecode'=>'+670'],
		['name'=>'Ecuador',										 'iso_2l'=>'EC', 'iso_3l'=>'ECU', 'nativeName'=>NULL, 'capital'=>'Quito', 'tz'=>'UTC-05:00', 'currency'=>'United States dollar (USD, $)', 'phonecode'=>'+593'],
		['name'=>'Egypt',										 'iso_2l'=>'EG', 'iso_3l'=>'EGY', 'nativeName'=>NULL, 'capital'=>'Cairo', 'tz'=>'UTC+02:00', 'currency'=>'Egyptian pound (EGP, £ or ج.م)', 'phonecode'=>'+20'],
		['name'=>'El Salvador',									 'iso_2l'=>'SV', 'iso_3l'=>'SLV', 'nativeName'=>NULL, 'capital'=>'San Salvador', 'tz'=>'UTC-06:00', 'currency'=>'Salvadoran colón (SVC, ₡), United States dollar (USD, $)', 'phonecode'=>'+503'],
		['name'=>'Equatorial Guinea',							 'iso_2l'=>'GQ', 'iso_3l'=>'GNQ', 'nativeName'=>NULL, 'capital'=>'Malabo', 'tz'=>'UTC+01:00', 'currency'=>'Central African CFA franc (XAF, Fr)', 'phonecode'=>'+240'],
		['name'=>'Eritrea',										 'iso_2l'=>'ER', 'iso_3l'=>'ERI', 'nativeName'=>NULL, 'capital'=>'Asmara', 'tz'=>'UTC+03:00', 'currency'=>'Eritrean nakfa (ERN, Nfk)', 'phonecode'=>'+291'],
		['name'=>'Estonia',										 'iso_2l'=>'EE', 'iso_3l'=>'EST', 'nativeName'=>NULL, 'capital'=>'Tallinn', 'tz'=>'UTC+03:00', 'currency'=>'Euro (EUR, €)', 'phonecode'=>'+372'],
		['name'=>'Ethiopia',									 'iso_2l'=>'ET', 'iso_3l'=>'ETH', 'nativeName'=>NULL, 'capital'=>'Addis Ababa', 'tz'=>'UTC+03:00', 'currency'=>'Ethiopian birr (ETB, Br)', 'phonecode'=>'+251'],
		['name'=>'Falkland Islands (Malvinas)',					 'iso_2l'=>'FK', 'iso_3l'=>'FLK', 'nativeName'=>NULL, 'capital'=>'Stanley', 'tz'=>'UTC-04:00', 'currency'=>'Falkland Islands pound (FKP, £)', 'phonecode'=>'+500'],
		['name'=>'Faroe Islands',								 'iso_2l'=>'FO', 'iso_3l'=>'FRO', 'nativeName'=>NULL, 'capital'=>'Tórshavn', 'tz'=>'UTC', 'currency'=>'Danish krone (DKK, kr), Faroese króna (None, kr)', 'phonecode'=>'+298'],
		['name'=>'Fiji',										 'iso_2l'=>'FJ', 'iso_3l'=>'FJI', 'nativeName'=>NULL, 'capital'=>'Suva', 'tz'=>'UTC+12:00', 'currency'=>'Fijian dollar (FJD, $)', 'phonecode'=>'+679'],
		['name'=>'Finland',										 'iso_2l'=>'FI', 'iso_3l'=>'FIN', 'nativeName'=>NULL, 'capital'=>'Helsinki', 'tz'=>'UTC+02:00', 'currency'=>'Euro (EUR, €)', 'phonecode'=>'+358'],
		['name'=>'France',										 'iso_2l'=>'FR', 'iso_3l'=>'FRA', 'nativeName'=>NULL, 'capital'=>'Paris', 'tz'=>'UTC+01:00', 'currency'=>'Euro (EUR, €)', 'phonecode'=>'+33'],
		['name'=>'French Guiana',								 'iso_2l'=>'GF', 'iso_3l'=>'GUF', 'nativeName'=>NULL, 'capital'=>'Cayenne', 'tz'=>'UTC-01:00', 'currency'=>'', 'phonecode'=>'+594'],
		['name'=>'French Polynesia',							 'iso_2l'=>'PF', 'iso_3l'=>'PYF', 'nativeName'=>NULL, 'capital'=>'Papeete', 'tz'=>'UTC-10:00', 'currency'=>'CFP franc (XPF, Fr)', 'phonecode'=>'+689'],
		['name'=>'French Southern Territories',					 'iso_2l'=>'TF', 'iso_3l'=>'ATF', 'nativeName'=>NULL, 'capital'=>'Port-aux-Français', 'tz'=>'', 'currency'=>'Euro (EUR, €)', 'phonecode'=>''],
		['name'=>'Gabon',										 'iso_2l'=>'GA', 'iso_3l'=>'GAB', 'nativeName'=>NULL, 'capital'=>'Libreville', 'tz'=>'UTC+01:00', 'currency'=>'Central African CFA franc (XAF, Fr)', 'phonecode'=>'+241'],
		['name'=>'Gambia',										 'iso_2l'=>'GM', 'iso_3l'=>'GMB', 'nativeName'=>NULL, 'capital'=>'Banjul', 'tz'=>'UTC', 'currency'=>'Gambian dalasi (GMD, D)', 'phonecode'=>'+220'],
		['name'=>'Georgia',										 'iso_2l'=>'GE', 'iso_3l'=>'GEO', 'nativeName'=>NULL, 'capital'=>'Tbilisi', 'tz'=>'UTC+04:00', 'currency'=>'Georgian lari (GEL, ლ)', 'phonecode'=>'+995'],
		['name'=>'Germany',										 'iso_2l'=>'DE', 'iso_3l'=>'DEU', 'nativeName'=>NULL, 'capital'=>'Berlin', 'tz'=>'UTC+01:00', 'currency'=>'Euro (EUR, €)', 'phonecode'=>'+49'],
		['name'=>'Ghana',										 'iso_2l'=>'GH', 'iso_3l'=>'GHA', 'nativeName'=>NULL, 'capital'=>'Accra', 'tz'=>'UTC', 'currency'=>'Ghanaian cedi (GHS, ₵)', 'phonecode'=>'+233'],
		['name'=>'Gibraltar',									 'iso_2l'=>'GI', 'iso_3l'=>'GIB', 'nativeName'=>NULL, 'capital'=>'Gibraltar', 'tz'=>'UTC+01:00', 'currency'=>'Gibraltar pound (GIP, £)', 'phonecode'=>'+350'],
		['name'=>'Greece',										 'iso_2l'=>'GR', 'iso_3l'=>'GRC', 'nativeName'=>NULL, 'capital'=>'Athens', 'tz'=>'UTC+02:00', 'currency'=>'Euro (EUR, €)', 'phonecode'=>'+30'],
		['name'=>'Greenland',									 'iso_2l'=>'GL', 'iso_3l'=>'GRL', 'nativeName'=>NULL, 'capital'=>'Nuuk', 'tz'=>'UTC-03:00', 'currency'=>'Danish krone (DKK, kr)', 'phonecode'=>'+299'],
		['name'=>'Grenada',										 'iso_2l'=>'GD', 'iso_3l'=>'GRD', 'nativeName'=>NULL, 'capital'=>'St. George\'s', 'tz'=>'UTC-04:00', 'currency'=>'East Caribbean dollar (XCD, $)', 'phonecode'=>'+1 473'],
		['name'=>'Guadeloupe',									 'iso_2l'=>'GP', 'iso_3l'=>'GLP', 'nativeName'=>NULL, 'capital'=>'', 'tz'=>'UTC-04:00', 'currency'=>'', 'phonecode'=>'+590'],
		['name'=>'Guam',										 'iso_2l'=>'GU', 'iso_3l'=>'GUM', 'nativeName'=>NULL, 'capital'=>'Hagåtña', 'tz'=>'UTC+10:00', 'currency'=>'United States dollar (USD, $)', 'phonecode'=>'+1 671'],
		['name'=>'Guatemala',									 'iso_2l'=>'GT', 'iso_3l'=>'GTM', 'nativeName'=>NULL, 'capital'=>'Guatemala City', 'tz'=>'UTC-06:00', 'currency'=>'Guatemalan quetzal (GTQ, Q)', 'phonecode'=>'+502'],
		['name'=>'Guernsey',									 'iso_2l'=>'GG', 'iso_3l'=>'GGY', 'nativeName'=>NULL, 'capital'=>'St. Peter Port', 'tz'=>'UTC', 'currency'=>'British pound (GBP, £), Guernsey pound (None, £)', 'phonecode'=>'+44 1481'],
		['name'=>'Guinea',										 'iso_2l'=>'GN', 'iso_3l'=>'GIN', 'nativeName'=>NULL, 'capital'=>'Conakry', 'tz'=>'UTC', 'currency'=>'Guinean franc (GNF, Fr)', 'phonecode'=>'+224'],
		['name'=>'Guinea-Bissau',								 'iso_2l'=>'GW', 'iso_3l'=>'GNB', 'nativeName'=>NULL, 'capital'=>'Bissau', 'tz'=>'UTC', 'currency'=>'West African CFA franc (XOF, Fr)', 'phonecode'=>'+245'],
		['name'=>'Guyana',										 'iso_2l'=>'GY', 'iso_3l'=>'GUY', 'nativeName'=>NULL, 'capital'=>'Georgetown', 'tz'=>'UTC-03:00', 'currency'=>'Guyanese dollar (GYD, $)', 'phonecode'=>'+592'],
		['name'=>'Haiti',										 'iso_2l'=>'HT', 'iso_3l'=>'HTI', 'nativeName'=>NULL, 'capital'=>'Port-au-Prince', 'tz'=>'UTC-05:00', 'currency'=>'Haitian gourde (HTG, G)', 'phonecode'=>'+509'],
		['name'=>'Heard Island and McDonald Islands',			 'iso_2l'=>'HM', 'iso_3l'=>'HMD', 'nativeName'=>NULL, 'capital'=>'', 'tz'=>'UTC+05', 'currency'=>'', 'phonecode'=>''],
		['name'=>'Honduras',									 'iso_2l'=>'HN', 'iso_3l'=>'HND', 'nativeName'=>NULL, 'capital'=>'Tegucigalpa', 'tz'=>'UTC-06:00', 'currency'=>'Honduran lempira (HNL, L)', 'phonecode'=>'+504'],
		['name'=>'Hong Kong',									 'iso_2l'=>'HK', 'iso_3l'=>'HKG', 'nativeName'=>NULL, 'capital'=>'', 'tz'=>'UTC+08:00', 'currency'=>'Hong Kong dollar (HKD, $)', 'phonecode'=>'+852'],
		['name'=>'Hungary',										 'iso_2l'=>'HU', 'iso_3l'=>'HUN', 'nativeName'=>NULL, 'capital'=>'Budapest', 'tz'=>'UTC+01:00', 'currency'=>'Hungarian forint (HUF, Ft)', 'phonecode'=>'+36'],
		['name'=>'Iceland',										 'iso_2l'=>'IS', 'iso_3l'=>'ISL', 'nativeName'=>NULL, 'capital'=>'Reykjavík', 'tz'=>'UTC', 'currency'=>'Icelandic króna (ISK, kr)', 'phonecode'=>'+354'],
		['name'=>'India',										 'iso_2l'=>'IN', 'iso_3l'=>'IND', 'nativeName'=>NULL, 'capital'=>'New Delhi', 'tz'=>'UTC+05:30', 'currency'=>'Indian rupee (INR, ₹)', 'phonecode'=>'+91'],
		['name'=>'Indonesia',									 'iso_2l'=>'ID', 'iso_3l'=>'IDN', 'nativeName'=>NULL, 'capital'=>'Jakarta', 'tz'=>'UTC+07:00 - UTC+09:00', 'currency'=>'Indonesian rupiah (IDR, Rp)', 'phonecode'=>'+62'],
		['name'=>'Iran',										 'iso_2l'=>'IR', 'iso_3l'=>'IRN', 'nativeName'=>NULL, 'capital'=>'Tehran', 'tz'=>'UTC+03:30', 'currency'=>'Iranian rial (IRR, ﷼)', 'phonecode'=>'+98'],
		['name'=>'Iraq',										 'iso_2l'=>'IQ', 'iso_3l'=>'IRQ', 'nativeName'=>NULL, 'capital'=>'Baghdad', 'tz'=>'UTC+03:00', 'currency'=>'Iraqi dinar (IQD, ع.د)', 'phonecode'=>'+964'],
		['name'=>'Ireland',										 'iso_2l'=>'IE', 'iso_3l'=>'IRL', 'nativeName'=>NULL, 'capital'=>'Dublin', 'tz'=>'UTC', 'currency'=>'Euro (EUR, €)', 'phonecode'=>'+353'],
		['name'=>'Isle of Man',									 'iso_2l'=>'IM', 'iso_3l'=>'IMN', 'nativeName'=>NULL, 'capital'=>'Douglas', 'tz'=>'UTC', 'currency'=>'British pound (GBP, £), Manx pound (None, £)', 'phonecode'=>'+44 1624'],
		['name'=>'Israel',										 'iso_2l'=>'IL', 'iso_3l'=>'ISR', 'nativeName'=>NULL, 'capital'=>'Jerusalem', 'tz'=>'UTC+02:00', 'currency'=>'Israeli new shekel (ILS, ₪)', 'phonecode'=>'+972'],
		['name'=>'Italy',										 'iso_2l'=>'IT', 'iso_3l'=>'ITA', 'nativeName'=>NULL, 'capital'=>'Rome', 'tz'=>'UTC+01:00', 'currency'=>'Euro (EUR, €)', 'phonecode'=>'+39'],
		['name'=>'Jamaica',										 'iso_2l'=>'JM', 'iso_3l'=>'JAM', 'nativeName'=>NULL, 'capital'=>'Kingston', 'tz'=>'UTC-05:00', 'currency'=>'Jamaican dollar (JMD, $)', 'phonecode'=>'+1 876'],
		['name'=>'Japan',										 'iso_2l'=>'JP', 'iso_3l'=>'JPN', 'nativeName'=>NULL, 'capital'=>'Tokyo', 'tz'=>'UTC+09:00', 'currency'=>'Japanese yen (JPY, ¥)', 'phonecode'=>'+81'],
		['name'=>'Jersey',										 'iso_2l'=>'JE', 'iso_3l'=>'JEY', 'nativeName'=>NULL, 'capital'=>'St. Helier', 'tz'=>'UTC', 'currency'=>'British pound (GBP, £), Jersey pound (None, £)', 'phonecode'=>'+44 1534'],
		['name'=>'Jordan',										 'iso_2l'=>'JO', 'iso_3l'=>'JOR', 'nativeName'=>NULL, 'capital'=>'Amman', 'tz'=>'UTC+02:00', 'currency'=>'Jordanian dinar (JOD, د.ا)', 'phonecode'=>'+962'],
		['name'=>'Kazakhstan',									 'iso_2l'=>'KZ', 'iso_3l'=>'KAZ', 'nativeName'=>NULL, 'capital'=>'Astana', 'tz'=>'UTC+06:00', 'currency'=>'Kazakhstani tenge (KZT, ₸)', 'phonecode'=>'+7 6xx, 7xx'],
		['name'=>'Kenya',										 'iso_2l'=>'KE', 'iso_3l'=>'KEN', 'nativeName'=>NULL, 'capital'=>'Nairobi', 'tz'=>'UTC+03:00', 'currency'=>'Kenyan shilling (KES, Sh)', 'phonecode'=>'+254'],
		['name'=>'Kiribati',									 'iso_2l'=>'KI', 'iso_3l'=>'KIR', 'nativeName'=>NULL, 'capital'=>'Tarawa', 'tz'=>'UTC+12:00', 'currency'=>'Australian dollar (AUD, $), Kiribati dollar (None, $)', 'phonecode'=>'+686'],
		['name'=>'Kuwait',										 'iso_2l'=>'KW', 'iso_3l'=>'KWT', 'nativeName'=>NULL, 'capital'=>'Kuwait City', 'tz'=>'UTC+03:00', 'currency'=>'Kuwaiti dinar (KWD, د.ك)', 'phonecode'=>'+965'],
		['name'=>'Kyrgyzstan',									 'iso_2l'=>'KG', 'iso_3l'=>'KGZ', 'nativeName'=>NULL, 'capital'=>'Bishkek', 'tz'=>'UTC+06:00', 'currency'=>'Kyrgyzstani som (KGS, лв)', 'phonecode'=>'+996'],
		['name'=>'Laos',										 'iso_2l'=>'LA', 'iso_3l'=>'LAO', 'nativeName'=>NULL, 'capital'=>'Vientiane', 'tz'=>'UTC+07:00', 'currency'=>'Lao kip (LAK, ₭)', 'phonecode'=>'+856'],
		['name'=>'Latvia',										 'iso_2l'=>'LV', 'iso_3l'=>'LVA', 'nativeName'=>NULL, 'capital'=>'Riga', 'tz'=>'UTC+03:00', 'currency'=>'Latvian lats (LVL, Ls)', 'phonecode'=>'+371'],
		['name'=>'Lebanon',										 'iso_2l'=>'LB', 'iso_3l'=>'LBN', 'nativeName'=>NULL, 'capital'=>'Beirut', 'tz'=>'UTC+02:00', 'currency'=>'Lebanese pound (LBP, ل.ل)', 'phonecode'=>'+961'],
		['name'=>'Lesotho',										 'iso_2l'=>'LS', 'iso_3l'=>'LSO', 'nativeName'=>NULL, 'capital'=>'Maseru', 'tz'=>'UTC+02:00', 'currency'=>'Lesotho loti (LSL, L), South African rand (ZAR, R)', 'phonecode'=>'+266'],
		['name'=>'Liberia',										 'iso_2l'=>'LR', 'iso_3l'=>'LBR', 'nativeName'=>NULL, 'capital'=>'Monrovia', 'tz'=>'UTC', 'currency'=>'Liberian dollar (LRD, $)', 'phonecode'=>'+231'],
		['name'=>'Libya',										 'iso_2l'=>'LY', 'iso_3l'=>'LBY', 'nativeName'=>NULL, 'capital'=>'Tripoli', 'tz'=>'UTC+02:00', 'currency'=>'Libyan dinar (LYD, ل.د)', 'phonecode'=>'+218'],
		['name'=>'Liechtenstein',								 'iso_2l'=>'LI', 'iso_3l'=>'LIE', 'nativeName'=>NULL, 'capital'=>'Vaduz', 'tz'=>'UTC+01:00', 'currency'=>'Swiss franc (CHF, Fr)', 'phonecode'=>'+423'],
		['name'=>'Lithuania',									 'iso_2l'=>'LT', 'iso_3l'=>'LTU', 'nativeName'=>NULL, 'capital'=>'Vilnius', 'tz'=>'UTC+02:00', 'currency'=>'Lithuanian litas (LTL, Lt)', 'phonecode'=>'+370'],
		['name'=>'Luxembourg',									 'iso_2l'=>'LU', 'iso_3l'=>'LUX', 'nativeName'=>NULL, 'capital'=>'Luxembourg', 'tz'=>'UTC+01:00', 'currency'=>'Euro (EUR, €)', 'phonecode'=>'+352'],
		['name'=>'Macau',										 'iso_2l'=>'MO', 'iso_3l'=>'MAC', 'nativeName'=>NULL, 'capital'=>'', 'tz'=>'UTC+08:00', 'currency'=>'Macanese pataca (MOP, P)', 'phonecode'=>'+853'],
		['name'=>'Macedonia',									 'iso_2l'=>'MK', 'iso_3l'=>'MKD', 'nativeName'=>NULL, 'capital'=>'Skopje', 'tz'=>'UTC+01:00', 'currency'=>'Macedonian denar (MKD, ден)', 'phonecode'=>'+389'],
		['name'=>'Madagascar',									 'iso_2l'=>'MG', 'iso_3l'=>'MDG', 'nativeName'=>NULL, 'capital'=>'Antananarivo', 'tz'=>'UTC+03:00', 'currency'=>'Malagasy ariary (MGA, Ar)', 'phonecode'=>'+261'],
		['name'=>'Malawi',										 'iso_2l'=>'MW', 'iso_3l'=>'MWI', 'nativeName'=>NULL, 'capital'=>'Lilongwe', 'tz'=>'UTC+02:00', 'currency'=>'Malawian kwacha (MWK, MK)', 'phonecode'=>'+265'],
		['name'=>'Malaysia',									 'iso_2l'=>'MY', 'iso_3l'=>'MYS', 'nativeName'=>NULL, 'capital'=>'Kuala Lumpur', 'tz'=>'UTC+08:00', 'currency'=>'Malaysian ringgit (MYR, RM)', 'phonecode'=>'+60'],
		['name'=>'Maldives',									 'iso_2l'=>'MV', 'iso_3l'=>'MDV', 'nativeName'=>NULL, 'capital'=>'Malé', 'tz'=>'UTC+05:00', 'currency'=>'Maldivian rufiyaa (MVR, ރ.)', 'phonecode'=>'+960'],
		['name'=>'Mali',										 'iso_2l'=>'ML', 'iso_3l'=>'MLI', 'nativeName'=>NULL, 'capital'=>'Bamako', 'tz'=>'UTC', 'currency'=>'West African CFA franc (XOF, Fr)', 'phonecode'=>'+223'],
		['name'=>'Malta',										 'iso_2l'=>'MT', 'iso_3l'=>'MLT', 'nativeName'=>NULL, 'capital'=>'Valletta', 'tz'=>'UTC+01:00', 'currency'=>'Euro (EUR, €)', 'phonecode'=>'+356'],
		['name'=>'Marshall Islands',							 'iso_2l'=>'MH', 'iso_3l'=>'MHL', 'nativeName'=>NULL, 'capital'=>'Majuro', 'tz'=>'UTC+10:00', 'currency'=>'United States dollar (USD, $)', 'phonecode'=>'+692'],
		['name'=>'Martinique',									 'iso_2l'=>'MQ', 'iso_3l'=>'MTQ', 'nativeName'=>NULL, 'capital'=>'', 'tz'=>'UTC-04:00', 'currency'=>'', 'phonecode'=>'+596'],
		['name'=>'Mauritania',									 'iso_2l'=>'MR', 'iso_3l'=>'MRT', 'nativeName'=>NULL, 'capital'=>'Nouakchott', 'tz'=>'UTC', 'currency'=>'Mauritanian ouguiya (MRO, UM)', 'phonecode'=>'+222'],
		['name'=>'Mauritius',									 'iso_2l'=>'MU', 'iso_3l'=>'MUS', 'nativeName'=>NULL, 'capital'=>'Port Louis', 'tz'=>'UTC+04:00', 'currency'=>'Mauritian rupee (MUR, ₨)', 'phonecode'=>'+230'],
		['name'=>'Mayotte',										 'iso_2l'=>'YT', 'iso_3l'=>'MYT', 'nativeName'=>NULL, 'capital'=>'Mamoudzou', 'tz'=>'UTC+03:00', 'currency'=>'Euro (EUR, €)', 'phonecode'=>'+262 269 / 639'],
		['name'=>'Mexico',										 'iso_2l'=>'MX', 'iso_3l'=>'MEX', 'nativeName'=>NULL, 'capital'=>'Mexico City', 'tz'=>'UTC-08:00 - UTC-06:00', 'currency'=>'Mexican peso (MXN, $)', 'phonecode'=>'+52'],
		['name'=>'Micronesia, Federated States of',				 'iso_2l'=>'FM', 'iso_3l'=>'FSM', 'nativeName'=>NULL, 'capital'=>'Palikir', 'tz'=>'UTC+10:00', 'currency'=>'Micronesian dollar (None, $), United States dollar (USD, $)', 'phonecode'=>'+691'],
		['name'=>'Moldova, Republic of',						 'iso_2l'=>'MD', 'iso_3l'=>'MDA', 'nativeName'=>NULL, 'capital'=>'Chisinau', 'tz'=>'UTC+03:00', 'currency'=>'Moldovan leu (MDL, L)', 'phonecode'=>'+373'],
		['name'=>'Monaco',										 'iso_2l'=>'MC', 'iso_3l'=>'MCO', 'nativeName'=>NULL, 'capital'=>'Monaco', 'tz'=>'UTC+01:00', 'currency'=>'Euro (EUR, €)', 'phonecode'=>'+377'],
		['name'=>'Mongolia',									 'iso_2l'=>'MN', 'iso_3l'=>'MNG', 'nativeName'=>NULL, 'capital'=>'Ulaanbaatar', 'tz'=>'UTC+08:00', 'currency'=>'Mongolian tögrög (MNT, ₮)', 'phonecode'=>'+976'],
		['name'=>'Montenegro',									 'iso_2l'=>'ME', 'iso_3l'=>'MNE', 'nativeName'=>NULL, 'capital'=>'Podgorica', 'tz'=>'UTC+01', 'currency'=>'Euro (EUR, €)', 'phonecode'=>'+382'],
		['name'=>'Montserrat',									 'iso_2l'=>'MS', 'iso_3l'=>'MSR', 'nativeName'=>NULL, 'capital'=>'Plymouth', 'tz'=>'UTC-04:00', 'currency'=>'East Caribbean dollar (XCD, $)', 'phonecode'=>'+1 664'],
		['name'=>'Morocco',										 'iso_2l'=>'MA', 'iso_3l'=>'MAR', 'nativeName'=>NULL, 'capital'=>'Rabat', 'tz'=>'UTC', 'currency'=>'Moroccan dirham (MAD, د.م.)', 'phonecode'=>'+212'],
		['name'=>'Mozambique',									 'iso_2l'=>'MZ', 'iso_3l'=>'MOZ', 'nativeName'=>NULL, 'capital'=>'Maputo', 'tz'=>'UTC+02:00', 'currency'=>'Mozambican metical (MZN, MTn)', 'phonecode'=>'+258'],
		['name'=>'Myanmar (Burma)',								 'iso_2l'=>'MM', 'iso_3l'=>'MMR', 'nativeName'=>NULL, 'capital'=>'Naypyidaw', 'tz'=>'UTC+06:30', 'currency'=>'Myanma kyat (MMK, K)', 'phonecode'=>'+95'],
		['name'=>'Namibia',										 'iso_2l'=>'NA', 'iso_3l'=>'NAM', 'nativeName'=>NULL, 'capital'=>'Windhoek', 'tz'=>'UTC+02:00', 'currency'=>'Namibian dollar (NAD, $), South African rand (ZAR, R)', 'phonecode'=>'+264'],
		['name'=>'Nauru',										 'iso_2l'=>'NR', 'iso_3l'=>'NRU', 'nativeName'=>NULL, 'capital'=>'Yaren', 'tz'=>'UTC+12:00', 'currency'=>'Australian dollar (AUD, $), Nauruan dollar (None, $)', 'phonecode'=>'+674'],
		['name'=>'Nepal',										 'iso_2l'=>'NP', 'iso_3l'=>'NPL', 'nativeName'=>NULL, 'capital'=>'Kathmandu', 'tz'=>'UTC+05:30', 'currency'=>'Nepalese rupee (NPR, ₨)', 'phonecode'=>'+977'],
		['name'=>'Netherlands',									 'iso_2l'=>'NL', 'iso_3l'=>'NLD', 'nativeName'=>NULL, 'capital'=>'Amsterdam', 'tz'=>'UTC+01:00', 'currency'=>'Euro (EUR, €)', 'phonecode'=>'+31'],
		['name'=>'New Caledonia',								 'iso_2l'=>'NC', 'iso_3l'=>'NCL', 'nativeName'=>NULL, 'capital'=>'Nouméa', 'tz'=>'UTC+11:00', 'currency'=>'CFP franc (XPF, Fr)', 'phonecode'=>'+687'],
		['name'=>'New Zealand',									 'iso_2l'=>'NZ', 'iso_3l'=>'NZL', 'nativeName'=>NULL, 'capital'=>'Wellington', 'tz'=>'UTC+12:00', 'currency'=>'New Zealand dollar (NZD, $)', 'phonecode'=>'+64'],
		['name'=>'Nicaragua',									 'iso_2l'=>'NI', 'iso_3l'=>'NIC', 'nativeName'=>NULL, 'capital'=>'Managua', 'tz'=>'UTC-06:00', 'currency'=>'Nicaraguan córdoba (NIO, C$)', 'phonecode'=>'+505'],
		['name'=>'Niger',										 'iso_2l'=>'NE', 'iso_3l'=>'NER', 'nativeName'=>NULL, 'capital'=>'Niamey', 'tz'=>'UTC+01:00', 'currency'=>'West African CFA franc (XOF, Fr)', 'phonecode'=>'+227'],
		['name'=>'Nigeria',										 'iso_2l'=>'NG', 'iso_3l'=>'NGA', 'nativeName'=>NULL, 'capital'=>'Abuja', 'tz'=>'UTC+01:00', 'currency'=>'Nigerian naira (NGN, ₦)', 'phonecode'=>'+234'],
		['name'=>'Niue',										 'iso_2l'=>'NU', 'iso_3l'=>'NIU', 'nativeName'=>NULL, 'capital'=>'Alofi', 'tz'=>'UTC-11:00', 'currency'=>'New Zealand dollar (NZD, $), Niuean dollar (None, $)', 'phonecode'=>'+683'],
		['name'=>'Norfolk Island',								 'iso_2l'=>'NF', 'iso_3l'=>'NFK', 'nativeName'=>NULL, 'capital'=>'Kingston', 'tz'=>'UTC+11:30', 'currency'=>'Australian dollar (AUD, $)', 'phonecode'=>'+672 3'],
		['name'=>'North Korea',									 'iso_2l'=>'KP', 'iso_3l'=>'PRK', 'nativeName'=>NULL, 'capital'=>'Pyongyang', 'tz'=>'UTC+09:00', 'currency'=>'North Korean won (KPW, ₩)', 'phonecode'=>'+850'],
		['name'=>'Northern Mariana Islands',					 'iso_2l'=>'MP', 'iso_3l'=>'MNP', 'nativeName'=>NULL, 'capital'=>'Saipan', 'tz'=>'UTC+10:00', 'currency'=>'United States dollar (USD, $)', 'phonecode'=>'+1 670'],
		['name'=>'Norway',										 'iso_2l'=>'NO', 'iso_3l'=>'NOR', 'nativeName'=>NULL, 'capital'=>'Oslo', 'tz'=>'UTC+01:00', 'currency'=>'Norwegian krone (NOK, kr)', 'phonecode'=>'+47'],
		['name'=>'Oman',										 'iso_2l'=>'OM', 'iso_3l'=>'OMN', 'nativeName'=>NULL, 'capital'=>'Muscat', 'tz'=>'UTC+04:00', 'currency'=>'Omani rial (OMR, ر.ع.)', 'phonecode'=>'+968'],
		['name'=>'Pakistan',									 'iso_2l'=>'PK', 'iso_3l'=>'PAK', 'nativeName'=>NULL, 'capital'=>'Islamabad', 'tz'=>'UTC+05:00', 'currency'=>'Pakistani rupee (PKR, ₨)', 'phonecode'=>'+92'],
		['name'=>'Palau',										 'iso_2l'=>'PW', 'iso_3l'=>'PLW', 'nativeName'=>NULL, 'capital'=>'Ngerulmud', 'tz'=>'UTC+09:00', 'currency'=>'Palauan dollar (None, $), United States dollar (USD, $)', 'phonecode'=>'+680'],
		['name'=>'Palestinian Territory, Occupied',				 'iso_2l'=>'PS', 'iso_3l'=>'PSE', 'nativeName'=>NULL, 'capital'=>'', 'tz'=>'UTC+02:00', 'currency'=>'', 'phonecode'=>'+970'],
		['name'=>'Panama',										 'iso_2l'=>'PA', 'iso_3l'=>'PAN', 'nativeName'=>NULL, 'capital'=>'Panama City', 'tz'=>'UTC-05:00', 'currency'=>'Panamanian balboa (PAB, B/.), United States dollar (USD, $)', 'phonecode'=>'+507'],
		['name'=>'Papua New Guinea',							 'iso_2l'=>'PG', 'iso_3l'=>'PNG', 'nativeName'=>NULL, 'capital'=>'Port Moresby', 'tz'=>'UTC+10:00', 'currency'=>'Papua New Guinean kina (PGK, K)', 'phonecode'=>'+675'],
		['name'=>'Paraguay',									 'iso_2l'=>'PY', 'iso_3l'=>'PRY', 'nativeName'=>NULL, 'capital'=>'Asunción', 'tz'=>'UTC-04:00', 'currency'=>'Paraguayan guaraní (PYG, ₲)', 'phonecode'=>'+595'],
		['name'=>'Peru',										 'iso_2l'=>'PE', 'iso_3l'=>'PER', 'nativeName'=>NULL, 'capital'=>'Lima', 'tz'=>'UTC-05:00', 'currency'=>'Peruvian nuevo sol (PEN, S/.)', 'phonecode'=>'+51'],
		['name'=>'Philippines',									 'iso_2l'=>'PH', 'iso_3l'=>'PHL', 'nativeName'=>NULL, 'capital'=>'Manila', 'tz'=>'UTC+08:00', 'currency'=>'Philippine peso (PHP, ₱)', 'phonecode'=>'+63'],
		['name'=>'Pitcairn',									 'iso_2l'=>'PN', 'iso_3l'=>'PCN', 'nativeName'=>NULL, 'capital'=>'Adamstown', 'tz'=>'UTC-08', 'currency'=>'New Zealand dollar (NZD, $), Pitcairn Islands dollar (None, $)', 'phonecode'=>'+872'],
		['name'=>'Poland',										 'iso_2l'=>'PL', 'iso_3l'=>'POL', 'nativeName'=>NULL, 'capital'=>'Warsaw', 'tz'=>'UTC+01:00', 'currency'=>'Polish złoty (PLN, zł)', 'phonecode'=>'+48'],
		['name'=>'Portugal',									 'iso_2l'=>'PT', 'iso_3l'=>'PRT', 'nativeName'=>NULL, 'capital'=>'Lisbon', 'tz'=>'UTC+01:00', 'currency'=>'Euro (EUR, €)', 'phonecode'=>'+351'],
		['name'=>'Puerto Rico',									 'iso_2l'=>'PR', 'iso_3l'=>'PRI', 'nativeName'=>NULL, 'capital'=>'San Juan', 'tz'=>'UTC-04:00', 'currency'=>'United States dollar (USD, $)', 'phonecode'=>'+1 787 / 939'],
		['name'=>'Qatar',										 'iso_2l'=>'QA', 'iso_3l'=>'QAT', 'nativeName'=>NULL, 'capital'=>'Doha', 'tz'=>'UTC+03:00', 'currency'=>'Qatari riyal (QAR, ر.ق)', 'phonecode'=>'+974'],
		['name'=>'Réunion',										 'iso_2l'=>'RE', 'iso_3l'=>'REU', 'nativeName'=>NULL, 'capital'=>'', 'tz'=>'UTC+04:00', 'currency'=>'', 'phonecode'=>'+262'],
		['name'=>'Romania',										 'iso_2l'=>'RO', 'iso_3l'=>'ROU', 'nativeName'=>NULL, 'capital'=>'Bucharest', 'tz'=>'UTC+02:00', 'currency'=>'Romanian leu (RON, L)', 'phonecode'=>'+40'],
		['name'=>'Russia',										 'iso_2l'=>'RU', 'iso_3l'=>'RUS', 'nativeName'=>NULL, 'capital'=>'Moscow', 'tz'=>'UTC+03:00', 'currency'=>'Russian ruble (RUB, р.)', 'phonecode'=>'+7'],
		['name'=>'Rwanda',										 'iso_2l'=>'RW', 'iso_3l'=>'RWA', 'nativeName'=>NULL, 'capital'=>'Kigali', 'tz'=>'UTC+02:00', 'currency'=>'Rwandan franc (RWF, Fr)', 'phonecode'=>'+250'],
		['name'=>'Saint Barthélemy',							 'iso_2l'=>'BL', 'iso_3l'=>'BLM', 'nativeName'=>NULL, 'capital'=>'Gustavia', 'tz'=>'UTC-04', 'currency'=>'Euro (EUR, €)', 'phonecode'=>''],
		['name'=>'Saint Helena',								 'iso_2l'=>'SH', 'iso_3l'=>'SHN', 'nativeName'=>NULL, 'capital'=>'Jamestown', 'tz'=>'UTC', 'currency'=>'Saint Helena pound (SHP, £)', 'phonecode'=>'+290'],
		['name'=>'Saint Kitts and Nevis',						 'iso_2l'=>'KN', 'iso_3l'=>'KNA', 'nativeName'=>NULL, 'capital'=>'Basseterre', 'tz'=>'UTC-04:00', 'currency'=>'East Caribbean dollar (XCD, $)', 'phonecode'=>'+1 869'],
		['name'=>'Saint Lucia',									 'iso_2l'=>'LC', 'iso_3l'=>'LCA', 'nativeName'=>NULL, 'capital'=>'Castries', 'tz'=>'UTC-04:00', 'currency'=>'East Caribbean dollar (XCD, $)', 'phonecode'=>'+1 758'],
		['name'=>'Saint Martin (French part)',					 'iso_2l'=>'MF', 'iso_3l'=>'MAF', 'nativeName'=>NULL, 'capital'=>'Marigot', 'tz'=>'UTC-04', 'currency'=>'Euro (EUR, €)', 'phonecode'=>'+590'],
		['name'=>'Saint Pierre and Miquelon',					 'iso_2l'=>'PM', 'iso_3l'=>'SPM', 'nativeName'=>NULL, 'capital'=>'St. Pierre', 'tz'=>'UTC-03', 'currency'=>'Euro (EUR, €)', 'phonecode'=>'+508'],
		['name'=>'Saint Vincent and the Grenadines',			 'iso_2l'=>'VC', 'iso_3l'=>'VCT', 'nativeName'=>NULL, 'capital'=>'Kingstown', 'tz'=>'UTC-04', 'currency'=>'East Caribbean dollar (XCD, $)', 'phonecode'=>'+1 784'],
		['name'=>'Samoa',										 'iso_2l'=>'WS', 'iso_3l'=>'WSM', 'nativeName'=>NULL, 'capital'=>'Apia', 'tz'=>'UTC-11:00', 'currency'=>'Samoan tala (WST, T)', 'phonecode'=>'+685'],
		['name'=>'San Marino',									 'iso_2l'=>'SM', 'iso_3l'=>'SMR', 'nativeName'=>NULL, 'capital'=>'San Marino', 'tz'=>'UTC+01:00', 'currency'=>'Euro (EUR, €)', 'phonecode'=>'+378'],
		['name'=>'São Tomé and Príncipe',						 'iso_2l'=>'ST', 'iso_3l'=>'STP', 'nativeName'=>NULL, 'capital'=>'São Tomé', 'tz'=>'UTC', 'currency'=>'São Tomé and Príncipe dobra (STD, Db)', 'phonecode'=>'+239'],
		['name'=>'Saudi Arabia',								 'iso_2l'=>'SA', 'iso_3l'=>'SAU', 'nativeName'=>NULL, 'capital'=>'Riyadh', 'tz'=>'UTC+03:00', 'currency'=>'Saudi riyal (SAR, ر.س)', 'phonecode'=>'+966'],
		['name'=>'Senegal',										 'iso_2l'=>'SN', 'iso_3l'=>'SEN', 'nativeName'=>NULL, 'capital'=>'Dakar', 'tz'=>'UTC', 'currency'=>'West African CFA franc (XOF, Fr)', 'phonecode'=>'+221'],
		['name'=>'Serbia',										 'iso_2l'=>'RS', 'iso_3l'=>'SRB', 'nativeName'=>NULL, 'capital'=>'Belgrade', 'tz'=>'UTC+01:00', 'currency'=>'Serbian dinar (RSD, дин. or din.)', 'phonecode'=>'+381'],
		['name'=>'Seychelles',									 'iso_2l'=>'SC', 'iso_3l'=>'SYC', 'nativeName'=>NULL, 'capital'=>'Victoria', 'tz'=>'UTC+04:00', 'currency'=>'Seychellois rupee (SCR, ₨)', 'phonecode'=>'+248'],
		['name'=>'Sierra Leone',								 'iso_2l'=>'SL', 'iso_3l'=>'SLE', 'nativeName'=>NULL, 'capital'=>'Freetown', 'tz'=>'UTC', 'currency'=>'Sierra Leonean leone (SLL, Le)', 'phonecode'=>'+232'],
		['name'=>'Singapore',									 'iso_2l'=>'SG', 'iso_3l'=>'SGP', 'nativeName'=>NULL, 'capital'=>'Singapore', 'tz'=>'UTC+08:00', 'currency'=>'Brunei dollar (BND, $), Singapore dollar (SGD, $)', 'phonecode'=>'+65'],
		['name'=>'Sint Maarten',								 'iso_2l'=>'SX', 'iso_3l'=>'SXM', 'nativeName'=>NULL, 'capital'=>'Philipsburg', 'tz'=>'', 'currency'=>'Netherlands Antillean guilder (ANG, ƒ)', 'phonecode'=>'+599 5'],
		['name'=>'Slovakia',									 'iso_2l'=>'SK', 'iso_3l'=>'SVK', 'nativeName'=>NULL, 'capital'=>'Bratislava', 'tz'=>'UTC+01:00', 'currency'=>'Euro (EUR, €)', 'phonecode'=>'+421'],
		['name'=>'Slovenia',									 'iso_2l'=>'SI', 'iso_3l'=>'SVN', 'nativeName'=>NULL, 'capital'=>'Ljubljana', 'tz'=>'UTC+01:00', 'currency'=>'Euro (EUR, €)', 'phonecode'=>'+386'],
		['name'=>'Solomon Islands',								 'iso_2l'=>'SB', 'iso_3l'=>'SLB', 'nativeName'=>NULL, 'capital'=>'Honiara', 'tz'=>'UTC+11:00', 'currency'=>'Solomon Islands dollar (SBD, $)', 'phonecode'=>'+677'],
		['name'=>'Somalia',										 'iso_2l'=>'SO', 'iso_3l'=>'SOM', 'nativeName'=>NULL, 'capital'=>'Mogadishu', 'tz'=>'UTC+03:00', 'currency'=>'Somali shilling (SOS, Sh)', 'phonecode'=>'+252'],
		['name'=>'South Africa',								 'iso_2l'=>'ZA', 'iso_3l'=>'ZAF', 'nativeName'=>NULL, 'capital'=>'Pretoria', 'tz'=>'UTC+02:00', 'currency'=>'South African rand (ZAR, R)', 'phonecode'=>'+27'],
		['name'=>'South Georgia and the South Sandwich Islands', 'iso_2l'=>'GS', 'iso_3l'=>'SGS', 'nativeName'=>NULL, 'capital'=>'Grytviken', 'tz'=>'UTC-02', 'currency'=>'British pound (GBP, £), South Georgia and the South Sandwich Islands pound (None, £)', 'phonecode'=>''],
		['name'=>'South Korea',									 'iso_2l'=>'KR', 'iso_3l'=>'KOR', 'nativeName'=>NULL, 'capital'=>'Seoul', 'tz'=>'UTC+09:00', 'currency'=>'South Korean won (KRW, ₩)', 'phonecode'=>'+82'],
		['name'=>'Spain',										 'iso_2l'=>'ES', 'iso_3l'=>'ESP', 'nativeName'=>NULL, 'capital'=>'Madrid', 'tz'=>'UTC+01:00', 'currency'=>'Euro (EUR, €)', 'phonecode'=>'+34'],
		['name'=>'Sri Lanka',									 'iso_2l'=>'LK', 'iso_3l'=>'LKA', 'nativeName'=>NULL, 'capital'=>'Sri Jayawardenapura Kotte', 'tz'=>'UTC+05:30', 'currency'=>'Sri Lankan rupee (LKR, Rs)', 'phonecode'=>'+94'],
		['name'=>'Sudan',										 'iso_2l'=>'SD', 'iso_3l'=>'SDN', 'nativeName'=>NULL, 'capital'=>'Khartoum', 'tz'=>'UTC+02:00', 'currency'=>'Sudanese pound (SDG, £)', 'phonecode'=>'+249'],
		['name'=>'Suriname',									 'iso_2l'=>'SR', 'iso_3l'=>'SUR', 'nativeName'=>NULL, 'capital'=>'Paramaribo', 'tz'=>'UTC-03:30', 'currency'=>'Surinamese dollar (SRD, $)', 'phonecode'=>'+597'],
		['name'=>'Svalbard and Jan Mayen',						 'iso_2l'=>'SJ', 'iso_3l'=>'SJM', 'nativeName'=>NULL, 'capital'=>'', 'tz'=>'', 'currency'=>'', 'phonecode'=>''],
		['name'=>'Swaziland',									 'iso_2l'=>'SZ', 'iso_3l'=>'SWZ', 'nativeName'=>NULL, 'capital'=>'Mbabane', 'tz'=>'UTC+02:00', 'currency'=>'Swazi lilangeni (SZL, L)', 'phonecode'=>'+268'],
		['name'=>'Sweden',										 'iso_2l'=>'SE', 'iso_3l'=>'SWE', 'nativeName'=>NULL, 'capital'=>'Stockholm', 'tz'=>'UTC+01:00', 'currency'=>'Swedish krona (SEK, kr)', 'phonecode'=>'+46'],
		['name'=>'Switzerland',									 'iso_2l'=>'CH', 'iso_3l'=>'CHE', 'nativeName'=>NULL, 'capital'=>'Bern', 'tz'=>'UTC+01:00', 'currency'=>'Swiss franc (CHF, Fr)', 'phonecode'=>'+41'],
		['name'=>'Syrian Arab Republic',						 'iso_2l'=>'SY', 'iso_3l'=>'SYR', 'nativeName'=>NULL, 'capital'=>'Damascus', 'tz'=>'UTC+02:00', 'currency'=>'Syrian pound (SYP, £ or ل.س)', 'phonecode'=>'+963'],
		['name'=>'Taiwan',										 'iso_2l'=>'TW', 'iso_3l'=>'TWN', 'nativeName'=>NULL, 'capital'=>'Taipei', 'tz'=>'UTC+08:00', 'currency'=>'New Taiwan dollar (TWD, $)', 'phonecode'=>'+886'],
		['name'=>'Tajikistan',									 'iso_2l'=>'TJ', 'iso_3l'=>'TJK', 'nativeName'=>NULL, 'capital'=>'Dushanbe', 'tz'=>'UTC+06:00', 'currency'=>'Tajikistani somoni (TJS, ЅМ)', 'phonecode'=>'+992'],
		['name'=>'Tanzania',									 'iso_2l'=>'TZ', 'iso_3l'=>'TZA', 'nativeName'=>NULL, 'capital'=>'Dodoma', 'tz'=>'UTC+03:00', 'currency'=>'Tanzanian shilling (TZS, Sh)', 'phonecode'=>'+255'],
		['name'=>'Thailand',									 'iso_2l'=>'TH', 'iso_3l'=>'THA', 'nativeName'=>NULL, 'capital'=>'Bangkok', 'tz'=>'UTC+07:00', 'currency'=>'Thai baht (THB, ฿)', 'phonecode'=>'+66'],
		['name'=>'Togo',										 'iso_2l'=>'TG', 'iso_3l'=>'TGO', 'nativeName'=>NULL, 'capital'=>'Lomé', 'tz'=>'UTC', 'currency'=>'West African CFA franc (XOF, Fr)', 'phonecode'=>'+228'],
		['name'=>'Tokelau',										 'iso_2l'=>'TK', 'iso_3l'=>'TKL', 'nativeName'=>NULL, 'capital'=>'', 'tz'=>'UTC-10', 'currency'=>'New Zealand dollar (NZD, $)', 'phonecode'=>'+690'],
		['name'=>'Tonga',										 'iso_2l'=>'TO', 'iso_3l'=>'TON', 'nativeName'=>NULL, 'capital'=>'Nuku', 'tz'=>'UTC+13:00', 'currency'=>'Tongan paʻanga (TOP, T$)', 'phonecode'=>'+676'],
		['name'=>'Trinidad and Tobago',							 'iso_2l'=>'TT', 'iso_3l'=>'TTO', 'nativeName'=>NULL, 'capital'=>'Port of Spain', 'tz'=>'UTC-04:00', 'currency'=>'Trinidad and Tobago dollar (TTD, $)', 'phonecode'=>'+1 868'],
		['name'=>'Tunisia',										 'iso_2l'=>'TN', 'iso_3l'=>'TUN', 'nativeName'=>NULL, 'capital'=>'Tunis', 'tz'=>'UTC+01:00', 'currency'=>'Tunisian dinar (TND, د.ت)', 'phonecode'=>'+216'],
		['name'=>'Turkey',										 'iso_2l'=>'TR', 'iso_3l'=>'TUR', 'nativeName'=>NULL, 'capital'=>'Ankara', 'tz'=>'UTC+02:00', 'currency'=>'Turkish lira (TRY, TL)', 'phonecode'=>'+90'],
		['name'=>'Turkmenistan',								 'iso_2l'=>'TM', 'iso_3l'=>'TKM', 'nativeName'=>NULL, 'capital'=>'Ashgabat', 'tz'=>'UTC+05:00', 'currency'=>'Turkmenistani manat (TMT, m)', 'phonecode'=>'+993'],
		['name'=>'Turks and Caicos Islands',					 'iso_2l'=>'TC', 'iso_3l'=>'TCA', 'nativeName'=>NULL, 'capital'=>'Cockburn Town', 'tz'=>'UTC-05:00', 'currency'=>'United States dollar (USD, $)', 'phonecode'=>'+1 649'],
		['name'=>'Tuvalu',										 'iso_2l'=>'TV', 'iso_3l'=>'TUV', 'nativeName'=>NULL, 'capital'=>'Funafuti', 'tz'=>'UTC+12:00', 'currency'=>'Australian dollar (AUD, $), Tuvaluan dollar (None, $)', 'phonecode'=>'+688'],
		['name'=>'Uganda',										 'iso_2l'=>'UG', 'iso_3l'=>'UGA', 'nativeName'=>NULL, 'capital'=>'Kampala', 'tz'=>'UTC+03:00', 'currency'=>'Ugandan shilling (UGX, Sh)', 'phonecode'=>'+256'],
		['name'=>'Ukraine',										 'iso_2l'=>'UA', 'iso_3l'=>'UKR', 'nativeName'=>NULL, 'capital'=>'Kiev', 'tz'=>'UTC+03:00', 'currency'=>'Ukrainian hryvnia (UAH, ₴)', 'phonecode'=>'+380'],
		['name'=>'United Arab Emirates',						 'iso_2l'=>'AE', 'iso_3l'=>'ARE', 'nativeName'=>NULL, 'capital'=>'Abu Dhabi', 'tz'=>'UTC+04:00', 'currency'=>'United Arab Emirates dirham (AED, د.إ)', 'phonecode'=>'+971'],
		['name'=>'United Kingdom',								 'iso_2l'=>'GB', 'iso_3l'=>'GBR', 'nativeName'=>NULL, 'capital'=>'London', 'tz'=>'UTC', 'currency'=>'British pound (GBP, £)', 'phonecode'=>'+44'],
		['name'=>'United States',								 'iso_2l'=>'US', 'iso_3l'=>'USA', 'nativeName'=>NULL, 'capital'=>'Washington, D.C.', 'tz'=>'UTC-11:00 - UTC-05:00', 'currency'=>'United States dollar (USD, $)', 'phonecode'=>'+1'],
		['name'=>'United States Minor Outlying Islands',		 'iso_2l'=>'UM', 'iso_3l'=>'UMI', 'nativeName'=>NULL, 'capital'=>'', 'tz'=>'', 'currency'=>'United States dollar (USD, $)', 'phonecode'=>'+1'],
		['name'=>'Uruguay',										 'iso_2l'=>'UY', 'iso_3l'=>'URY', 'nativeName'=>NULL, 'capital'=>'Montevideo', 'tz'=>'UTC-03:00', 'currency'=>'Uruguayan peso (UYU, $)', 'phonecode'=>'+598'],
		['name'=>'Uzbekistan',									 'iso_2l'=>'UZ', 'iso_3l'=>'UZB', 'nativeName'=>NULL, 'capital'=>'Tashkent', 'tz'=>'UTC+06:00', 'currency'=>'Uzbekistani som (UZS, лв)', 'phonecode'=>'+998'],
		['name'=>'Vanuatu',										 'iso_2l'=>'VU', 'iso_3l'=>'VUT', 'nativeName'=>NULL, 'capital'=>'Port Vila', 'tz'=>'UTC+11:00', 'currency'=>'Vanuatu vatu (VUV, Vt)', 'phonecode'=>'+678'],
		['name'=>'Vatican City',								 'iso_2l'=>'VA', 'iso_3l'=>'VAT', 'nativeName'=>NULL, 'capital'=>'Vatican City', 'tz'=>'UTC+01', 'currency'=>'Euro (EUR, €)', 'phonecode'=>'+39 06 698'],
		['name'=>'Venezuela',									 'iso_2l'=>'VE', 'iso_3l'=>'VEN', 'nativeName'=>NULL, 'capital'=>'Caracas', 'tz'=>'UTC-04:00', 'currency'=>'Venezuelan bolívar (VEF, Bs F)', 'phonecode'=>'+58'],
		['name'=>'Vietnam',										 'iso_2l'=>'VN', 'iso_3l'=>'VNM', 'nativeName'=>NULL, 'capital'=>'Hanoi', 'tz'=>'UTC+07:00', 'currency'=>'Vietnamese đồng (VND, ₫)', 'phonecode'=>'+84'],
		['name'=>'Virgin Islands, British',						 'iso_2l'=>'VG', 'iso_3l'=>'VGB', 'nativeName'=>NULL, 'capital'=>'Road Town', 'tz'=>'UTC-04', 'currency'=>'British Virgin Islands dollar (None, $), United States dollar (USD, $)', 'phonecode'=>'+1 284'],
		['name'=>'Virgin Islands, U.S.',						 'iso_2l'=>'VI', 'iso_3l'=>'VIR', 'nativeName'=>NULL, 'capital'=>'Charlotte Amalie', 'tz'=>'UTC-04', 'currency'=>'United States dollar (USD, $)', 'phonecode'=>'+1 340'],
		['name'=>'Wallis and Futuna',							 'iso_2l'=>'WF', 'iso_3l'=>'WLF', 'nativeName'=>NULL, 'capital'=>'Mata-Utu', 'tz'=>'UTC+12:00', 'currency'=>'CFP franc (XPF, Fr)', 'phonecode'=>'+681'],
		['name'=>'Western Sahara',								 'iso_2l'=>'EH', 'iso_3l'=>'ESH', 'nativeName'=>NULL, 'capital'=>'Laâyoune (El Aaiún)', 'tz'=>'', 'currency'=>'Moroccan dirham (MAD, د.م.)', 'phonecode'=>'+212 5288 / 5289'],
		['name'=>'Yemen',										 'iso_2l'=>'YE', 'iso_3l'=>'YEM', 'nativeName'=>NULL, 'capital'=>'Sanaá', 'tz'=>'UTC+03:00', 'currency'=>'Yemeni rial (YER, ﷼)', 'phonecode'=>'+967'],
		['name'=>'Zambia',										 'iso_2l'=>'ZM', 'iso_3l'=>'ZMB', 'nativeName'=>NULL, 'capital'=>'Lusaka', 'tz'=>'UTC+02:00', 'currency'=>'Zambian kwacha (ZMK, ZK)', 'phonecode'=>'+260'],
		['name'=>'Zimbabwe',									 'iso_2l'=>'ZW', 'iso_3l'=>'ZWE', 'nativeName'=>NULL, 'capital'=>'Harare', 'tz'=>'UTC+02:00', 'currency'=>'Botswana pula (BWP, P), British pound (GBP, £), Euro (EUR, €), South African rand (ZAR, R), United States dollar (USD, $), Zimbabwean dollar (ZWL, $)', 'phonecode'=>'+263'],
	];


	public function up(Schema $schema) : void
	{
		// truncate database
		$this->connection->exec("SET FOREIGN_KEY_CHECKS=0");
		$this->connection->exec("SET UNIQUE_CHECKS = 0");
		foreach ($this->truncate as $temp) {
			$this->connection->exec("TRUNCATE `$temp`");
		}

		// pre nom_type
		foreach ($this->manualNomTypes['PRE'] as $temp) {
			$temp['created_at'] = date('Y-m-d H:i:s');
			$temp['updated_at'] = date('Y-m-d H:i:s');
			$this->connection->insert('nom_type', $temp);
		}

		// insert dummy
		$this->connection->insert('base_noms', [
			'id' => 0,
			'parent_id' => NULL,
			'name' => 'Dummy default nom for altering db',
			'type' => 'dummy',
			'status' => 1,
			'bnorder' => 0,
			'created_at' => date('Y-m-d H:i:s'),
			'updated_at' => date('Y-m-d H:i:s'),
		]);
		$this->connection->executeQuery("UPDATE base_noms SET id=0");
		$this->connection->executeQuery("ALTER TABLE base_noms AUTO_INCREMENT = 1");

		// insert tree
		$unfolded = [];
		foreach ($this->tree as $node) {
			$this->writeBaseNomsTree($node);
			$unfolded += $this->unfoldBaseNomsTree($node);
		}

		
		// insert languages
		$this->connection->insert('language', [
			'name' => 'English',
			'name_local' => 'English',
			'iso2' => 'en',
			'iso3' => 'eng',
			'encoding' => 'en_US.UTF8',
			'status' => 1,
			'created_at' => date('Y-m-d H:i:s'),
			'updated_at' => date('Y-m-d H:i:s'),
		]);
		$english = 1;

		


		// settings
		foreach ($this->settings as $settings) {
			$settings['settings'] = isset($settings['settings']) ? json_encode($settings['settings']) : NULL;
			$settings['created_at'] = date('Y-m-d H:i:s');
			$settings['updated_at'] = date('Y-m-d H:i:s');
			$this->connection->insert('settings', $settings);
		}

		// country
		foreach ($this->country as $country) {
			$country['status'] = true;
			$country['created_at'] = date('Y-m-d H:i:s');
			$country['updated_at'] = date('Y-m-d H:i:s');
			$this->connection->insert('country', $country);
		}

		
		$this->connection->exec("SET FOREIGN_KEY_CHECKS=1");
		$this->connection->exec("SET UNIQUE_CHECKS = 1");

	}

	public function down(Schema $schema) : void
	{
		// this down() migration is auto-generated, please modify it to your needs

	}


}
