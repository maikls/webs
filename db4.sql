DROP TABLE IF EXISTS `en_order`;

CREATE TABLE en_order (
    `id` int(11) unsigned NOT NULL auto_increment COMMENT 'id',
    `num_order` int(11) COMMENT 'Номер заказа',
    `customers_type` int(3) COMMENT 'Тип заказчика',
    `customers_fio` varchar(1024) COMMENT 'ФИО',
    `customers_phone` int(50) COMMENT 'Номер телефона',
    `customers_email` varchar(256) COMMENT 'Электронная почта',
    `customers_name` varchar(1024) COMMENT 'Наименование юридического лица',
    `customers_inn` int(15) COMMENT 'ИНН',
    `customers_kpp` int(15) COMMENT 'КПП',
    `customers_okpo` int(15) COMMENT 'ОКПО',
    `customers_address` varchar(1024) COMMENT 'Адрес',
    `customers_postal` int(20) COMMENT 'Почтовый индекс',
    PRIMARY KEY  (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
