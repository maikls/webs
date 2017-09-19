
var page_constructor = '/certificate/';
var enotary_debug = true;
if(enotary_debug) { console.log('enotary - search - script'); }


function getItem(id, price, iTitle, iDescr, iImg, iKeys, iHomepage) {
 var tpItem = new Object();
 
 tpItem.id = id;
 tpItem.price = price;
 tpItem.title = iTitle;
 tpItem.description = iDescr;

 if(iImg == '') { iImg = 'no-logo.png';}
 tpItem.image = iImg;

 tpItem.keywords = iKeys;

 return tpItem;
}



var tpItems = [];

tpItems.push(getItem(1, '960', '&quot;Квалифицированный сертификат&quot;', '', '', 'госуслуги, gosuslugi.ru,госуслуги москвы, mos.ru,фнс россии, nalog.ru,фсс, fss.ru,росфинмониторинг, fedsfm.ru,единый реестр доменных имен роскомнадзора, eais.rkn.gov.ru,zakupki.gov.ru, zakupki.gov.ru,федеральное казначейство гис гмп, roskazna.ru/gis-gmp/index.php,цб (фсфр), cbr.ru/finmarkets/,росимущество, mvpt.rosim.ru,росфинмониторинг, fedsfm.ru,фсрар, fsrar.ru,фипс (роспатент), www1.fips.ru,мосэнергосбыт, mosenergosbyt.ru,фау главгосэкспертиза россии, uslugi.gge.ru,росаккредитация, fsa.gov.ru,этп стройторги, stroytorgi.ru,торговая система оборонторг, oborontorg.ru,ао отс, otc.ru,газнефтеторг.ру, gazneftetorg.ru/trades/,этп аукционный конкурсный дом, a-k-d.ru,торговая система спецстройторг, sstorg.ru,коммерсантъ картотека, utp.kartoteka.ru/etp/,единая строительная тендерная площадка – сро (естп сро), estp.ru,электронная торговая площадка российского аукционного дома (рад), lot-online.ru,универсальная электронная торговая площадка electro-torgi, electro-torgi.ru,сэтонлайн, setonline.ru,системы электронных паспортов (сэп), elpts.ru,этп угмк, zakupki.ugmk.com,гуп мосводосток, mosvodostok.com,этп элтокс, eltox.ru,оператор фискальных данных первый офд, 1-ofd.ru,росприроднадзор (рпн), rpn.gov.ru/node/20356,гис жкх, dom.gosuslugi.ru/#!/main,смэв, smev.gosuslugi.ru/portal', ''));
tpItems.push(getItem(2, '960', 'Пакет &quot;Коммерческие ЭТП&quot;', 'Пакет включает в себя: - усиленный квалифицированный сертификат по требованиям федерального закона №63 - ФЗ &quot;О электронной подписи&quot;, который могут использовать юридические лица, индивидуальные предприниматели и физические лица, в т.ч. и для обращения в государственные службы и ведомства. Позволяет работать с: - Электронными Торговыми Площадками в требования которых указанано использование усиленного квалифицированного сертификата', '', 'госуслуги, gosuslugi.ru,госуслуги москвы, mos.ru,фнс россии, nalog.ru,фсс, fss.ru,росфинмониторинг, fedsfm.ru,единый реестр доменных имен роскомнадзора, eais.rkn.gov.ru,zakupki.gov.ru, zakupki.gov.ru,федеральное казначейство гис гмп, roskazna.ru/gis-gmp/index.php,цб (фсфр), cbr.ru/finmarkets/,росимущество, mvpt.rosim.ru,росфинмониторинг, fedsfm.ru,фсрар, fsrar.ru,фипс (роспатент), www1.fips.ru,мосэнергосбыт, mosenergosbyt.ru,фау главгосэкспертиза россии, uslugi.gge.ru,росаккредитация, fsa.gov.ru,этп стройторги, stroytorgi.ru,торговая система оборонторг, oborontorg.ru,ао отс, otc.ru,газнефтеторг.ру, gazneftetorg.ru/trades/,этп аукционный конкурсный дом, a-k-d.ru,торговая система спецстройторг, sstorg.ru,коммерсантъ картотека, utp.kartoteka.ru/etp/,единая строительная тендерная площадка – сро (естп сро), estp.ru,электронная торговая площадка российского аукционного дома (рад), lot-online.ru,универсальная электронная торговая площадка electro-torgi, electro-torgi.ru,сэтонлайн, setonline.ru,системы электронных паспортов (сэп), elpts.ru,этп угмк, zakupki.ugmk.com,гуп мосводосток, mosvodostok.com,этп элтокс, eltox.ru,оператор фискальных данных первый офд, 1-ofd.ru,росприроднадзор (рпн), rpn.gov.ru/node/20356,гис жкх, dom.gosuslugi.ru/#!/main,смэв, smev.gosuslugi.ru/portal', ''));
tpItems.push(getItem(3, '960', 'Пакет &quot;СтройТорги&quot;', 'Пакет включает в себя: - усиленный квалифицированный сертификат по требованиям федерального закона №63 - ФЗ &quot;О электронной подписи&quot;, который могут использовать юридические лица, индивидуальные предприниматели и физические лица, в т.ч. и для обращения в государственные службы и ведомства. - услугу генерации ключей. Позволяет работать с: - Электронными Торговыми Площадками в требования которых указанано использование усиленного квалифицированного сертификата - площадкой СтройТорги', '', 'этп стройторги, stroytorgi.ru,госуслуги, gosuslugi.ru,госуслуги москвы, mos.ru,фнс россии, nalog.ru,фсс, fss.ru,росфинмониторинг, fedsfm.ru,единый реестр доменных имен роскомнадзора, eais.rkn.gov.ru,zakupki.gov.ru, zakupki.gov.ru,федеральное казначейство гис гмп, roskazna.ru/gis-gmp/index.php,цб (фсфр), cbr.ru/finmarkets/,росимущество, mvpt.rosim.ru,росфинмониторинг, fedsfm.ru,фсрар, fsrar.ru,фипс (роспатент), www1.fips.ru,мосэнергосбыт, mosenergosbyt.ru,фау главгосэкспертиза россии, uslugi.gge.ru,росаккредитация, fsa.gov.ru,торговая система оборонторг, oborontorg.ru,ао отс, otc.ru,газнефтеторг.ру, gazneftetorg.ru/trades/,этп аукционный конкурсный дом, a-k-d.ru,торговая система спецстройторг, sstorg.ru,коммерсантъ картотека, utp.kartoteka.ru/etp/,единая строительная тендерная площадка – сро (естп сро), estp.ru,электронная торговая площадка российского аукционного дома (рад), lot-online.ru,универсальная электронная торговая площадка electro-torgi, electro-torgi.ru,сэтонлайн, setonline.ru,системы электронных паспортов (сэп), elpts.ru,этп угмк, zakupki.ugmk.com,гуп мосводосток, mosvodostok.com,этп элтокс, eltox.ru,оператор фискальных данных первый офд, 1-ofd.ru,росприроднадзор (рпн), rpn.gov.ru/node/20356,гис жкх, dom.gosuslugi.ru/#!/main,смэв, smev.gosuslugi.ru/portal', ''));
tpItems.push(getItem(4, '2660', 'Пакет &quot;Федеральные ЭТП&quot;+', 'Пакет включает в себя: - усиленный квалифицированный сертификат по требованиям федерального закона №63 - ФЗ &quot;О электронной подписи&quot;, который могут использовать юридические лица, индивидуальные предприниматели и физические лица, в т.ч. и для обращения в государственные службы и ведомства. Позволяет работать с: - Электронными Торговыми Площадками в требования которых указанано использование усиленного квалифицированного сертификата. - 5 Федеральными ЭТП: Сбербанк — АСТ, ЭТП ММВБ «Госзакупки», Единая электронная торговая площадка (ЕЭТП), РТС-тендер, ZakazRF, zakupki mos (портал поставщиков г. Москвы)', '', 'этп сбербанк — аст, sberbank-ast.ru,этп ммвб госзакупки, etp-micex.ru,этп единая электронная торговая площадка (еэтп), roseltorg.ru,этп ртс тендер, rts-tender.ru,этп zakaz rf, etp.zakazrf.ru,этп портал поставщиков москвы, zakupki.mos.ru,национальный центр маркетинга и конъюнктуры цен, goszakupki.by,ис тендеры, icetrade.by,госуслуги, gosuslugi.ru,госуслуги москвы, mos.ru,фнс россии, nalog.ru,фсс, fss.ru,росфинмониторинг, fedsfm.ru,единый реестр доменных имен роскомнадзора, eais.rkn.gov.ru,zakupki.gov.ru, zakupki.gov.ru,федеральное казначейство гис гмп, roskazna.ru/gis-gmp/index.php,цб (фсфр), cbr.ru/finmarkets/,росимущество, mvpt.rosim.ru,росфинмониторинг, fedsfm.ru,фсрар, fsrar.ru,фипс (роспатент), www1.fips.ru,мосэнергосбыт, mosenergosbyt.ru,фау главгосэкспертиза россии, uslugi.gge.ru,росаккредитация, fsa.gov.ru,этп стройторги, stroytorgi.ru,торговая система оборонторг, oborontorg.ru,ао отс, otc.ru,газнефтеторг.ру, gazneftetorg.ru/trades/,этп аукционный конкурсный дом, a-k-d.ru,торговая система спецстройторг, sstorg.ru,коммерсантъ картотека, utp.kartoteka.ru/etp/,единая строительная тендерная площадка – сро (естп сро), estp.ru,электронная торговая площадка российского аукционного дома (рад), lot-online.ru,универсальная электронная торговая площадка electro-torgi, electro-torgi.ru,сэтонлайн, setonline.ru,системы электронных паспортов (сэп), elpts.ru,этп угмк, zakupki.ugmk.com,гуп мосводосток, mosvodostok.com,этп элтокс, eltox.ru,оператор фискальных данных первый офд, 1-ofd.ru,росприроднадзор (рпн), rpn.gov.ru/node/20356,гис жкх, dom.gosuslugi.ru/#!/main,смэв, smev.gosuslugi.ru/portal', ''));
tpItems.push(getItem(5, '1160', 'Пакет &quot;Площадка Федресурс&quot;+', 'Пакет включает в себя: - усиленный квалифицированный сертификат по требованиям федерального закона №63 - ФЗ &quot;О электронной подписи&quot;, который могут использовать юридические лица, индивидуальные предприниматели и физические лица, в т.ч. и для обращения в государственные службы и ведомства. Позволяет работать с: - Электронными Торговыми Площадками в требования которых указанано использование усиленного квалифицированного сертификата. - порталом Федресурс (Единый федеральный реестр юридически значимых сведений о фактах деятельности юридических лиц, индивидуальных предпринимателей и иных субъектов экономической деятельности) группы компаний Интерфакс', '', 'федресурс (гп интерфакс), fedresurs.ru,ефрсб, bankrot.fedresurs.ru,интерфакс-црки, e-disclosure.ru,скрин, disclosure.skrin.ru,система раскрытия информации ак&м, disclosure.ru,аэи прайм, disclosure.1prime.ru,иа азипи - информ, e-disclosure.azipi.ru,госуслуги, gosuslugi.ru,госуслуги москвы, mos.ru,фнс россии, nalog.ru,фсс, fss.ru,росфинмониторинг, fedsfm.ru,единый реестр доменных имен роскомнадзора, eais.rkn.gov.ru,zakupki.gov.ru, zakupki.gov.ru,федеральное казначейство гис гмп, roskazna.ru/gis-gmp/index.php,цб (фсфр), cbr.ru/finmarkets/,росимущество, mvpt.rosim.ru,росфинмониторинг, fedsfm.ru,фсрар, fsrar.ru,фипс (роспатент), www1.fips.ru,мосэнергосбыт, mosenergosbyt.ru,фау главгосэкспертиза россии, uslugi.gge.ru,росаккредитация, fsa.gov.ru,этп стройторги, stroytorgi.ru,торговая система оборонторг, oborontorg.ru,ао отс, otc.ru,газнефтеторг.ру, gazneftetorg.ru/trades/,этп аукционный конкурсный дом, a-k-d.ru,торговая система спецстройторг, sstorg.ru,коммерсантъ картотека, utp.kartoteka.ru/etp/,единая строительная тендерная площадка – сро (естп сро), estp.ru,электронная торговая площадка российского аукционного дома (рад), lot-online.ru,универсальная электронная торговая площадка electro-torgi, electro-torgi.ru,сэтонлайн, setonline.ru,системы электронных паспортов (сэп), elpts.ru,этп угмк, zakupki.ugmk.com,гуп мосводосток, mosvodostok.com,этп элтокс, eltox.ru,оператор фискальных данных первый офд, 1-ofd.ru,росприроднадзор (рпн), rpn.gov.ru/node/20356,гис жкх, dom.gosuslugi.ru/#!/main,смэв, smev.gosuslugi.ru/portal,', ''));
tpItems.push(getItem(6, '2460', 'Пакет &quot;Группа площадок B2B&quot;+', 'Пакет включает в себя: - усиленный квалифицированный сертификат по требованиям федерального закона №63 - ФЗ &quot;О электронной подписи&quot;, который могут использовать юридические лица, индивидуальные предприниматели и физические лица, в т.ч. и для обращения в государственные службы и ведомства. Позволяет работать с: - Электронными Торговыми Площадками в требования которых указанано использование усиленного квалифицированного сертификата. - порталами группы площадок B2B-Center. Центр электронных торгов B2B-Center позволяет проводить 43 вида торговых процедур, как на закупку, так и на продажу товаров и услуг. Система объединяет закупки различных отраслей экономики: энергетики, нефтехимии, металлургии, автомобильной промышленности и многих других отраслей.', '', 'этп b2b-center, b2b-center.ru,госуслуги, gosuslugi.ru,госуслуги москвы, mos.ru,фнс россии, nalog.ru,фсс, fss.ru,росфинмониторинг, fedsfm.ru,единый реестр доменных имен роскомнадзора, eais.rkn.gov.ru,zakupki.gov.ru, zakupki.gov.ru,федеральное казначейство гис гмп, roskazna.ru/gis-gmp/index.php,цб (фсфр), cbr.ru/finmarkets/,росимущество, mvpt.rosim.ru,росфинмониторинг, fedsfm.ru,фсрар, fsrar.ru,фипс (роспатент), www1.fips.ru,мосэнергосбыт, mosenergosbyt.ru,фау главгосэкспертиза россии, uslugi.gge.ru,росаккредитация, fsa.gov.ru,этп стройторги, stroytorgi.ru,торговая система оборонторг, oborontorg.ru,ао отс, otc.ru,газнефтеторг.ру, gazneftetorg.ru/trades/,этп аукционный конкурсный дом, a-k-d.ru,торговая система спецстройторг, sstorg.ru,коммерсантъ картотека, utp.kartoteka.ru/etp/,единая строительная тендерная площадка – сро (естп сро), estp.ru,электронная торговая площадка российского аукционного дома (рад), lot-online.ru,универсальная электронная торговая площадка electro-torgi, electro-torgi.ru,сэтонлайн, setonline.ru,системы электронных паспортов (сэп), elpts.ru,этп угмк, zakupki.ugmk.com,гуп мосводосток, mosvodostok.com,этп элтокс, eltox.ru,оператор фискальных данных первый офд, 1-ofd.ru,росприроднадзор (рпн), rpn.gov.ru/node/20356,гис жкх, dom.gosuslugi.ru/#!/main,смэв, smev.gosuslugi.ru/portal', ''));
tpItems.push(getItem(7, '2760', 'Пакет &quot;площадка ТЭК Торг&quot;+', 'Пакет включает в себя: - усиленный квалифицированный сертификат по требованиям федерального закона №63 - ФЗ &quot;О электронной подписи&quot;, который могут использовать юридические лица, индивидуальные предприниматели и физические лица, в т.ч. и для обращения в государственные службы и ведомства. Позволяет работать с: - Электронными Торговыми Площадками в требования которых указанано использование усиленного квалифицированного сертификата. - порталом ТЭК Торг в секции НК Роснефть. Секция ПАО «НК «Роснефть» на ЭТП ТЭК-Торг - это платформа, позволяющая крупнейшей нефтяной компании России и ее дочерним обществам организовать открытое и прозрачное взаимодействие с поставщиками в ходе проведения корпоративных закупок и проведения тендеров на реализацию углеводородного сырья в электронном виде.', '', 'этп тэк торг (секция пао нк роснефть), rn.tektorg.ru,госуслуги, gosuslugi.ru,госуслуги москвы, mos.ru,фнс россии, nalog.ru,фсс, fss.ru,росфинмониторинг, fedsfm.ru,единый реестр доменных имен роскомнадзора, eais.rkn.gov.ru,zakupki.gov.ru, zakupki.gov.ru,федеральное казначейство гис гмп, roskazna.ru/gis-gmp/index.php,цб (фсфр), cbr.ru/finmarkets/,росимущество, mvpt.rosim.ru,росфинмониторинг, fedsfm.ru,фсрар, fsrar.ru,фипс (роспатент), www1.fips.ru,мосэнергосбыт, mosenergosbyt.ru,фау главгосэкспертиза россии, uslugi.gge.ru,росаккредитация, fsa.gov.ru,этп стройторги, stroytorgi.ru,торговая система оборонторг, oborontorg.ru,ао отс, otc.ru,газнефтеторг.ру, gazneftetorg.ru/trades/,этп аукционный конкурсный дом, a-k-d.ru,торговая система спецстройторг, sstorg.ru,коммерсантъ картотека, utp.kartoteka.ru/etp/,единая строительная тендерная площадка – сро (естп сро), estp.ru,электронная торговая площадка российского аукционного дома (рад), lot-online.ru,универсальная электронная торговая площадка electro-torgi, electro-torgi.ru,сэтонлайн, setonline.ru,системы электронных паспортов (сэп), elpts.ru,этп угмк, zakupki.ugmk.com,гуп мосводосток, mosvodostok.com,этп элтокс, eltox.ru,оператор фискальных данных первый офд, 1-ofd.ru,росприроднадзор (рпн), rpn.gov.ru/node/20356,гис жкх, dom.gosuslugi.ru/#!/main,смэв, smev.gosuslugi.ru/portal', ''));
tpItems.push(getItem(8, '3460', 'Пакет &quot;площадка Фабрикант&quot;+', 'Пакет включает в себя: - усиленный квалифицированный сертификат по требованиям федерального закона №63 - ФЗ &quot;О электронной подписи&quot;, который могут использовать юридические лица, индивидуальные предприниматели и физические лица, в т.ч. и для обращения в государственные службы и ведомства. Позволяет работать с: - Электронными Торговыми Площадками в требования которых указанано использование усиленного квалифицированного сертификата. - площадкой ЭТП Фабрикант. На ЭТП Фабрикант производят электронные закупки крупнейшие российские и зарубежные компании. Десятки тысяч поставщиков товаров и услуг с помощью портала находят своих заказчиков и расширяют рынки сбыта. Фабрикант предлагает широкий спектр услуг и решений, направленных на повышение конкурентоспособности вашего бизнеса. Мы разрабатываем технологии, которые позволяют сделать закупки эффективными, а снабжение предприятий - удобным и оперативным.', '', 'этп фабрикант, fabrikant.ru,госуслуги, gosuslugi.ru,госуслуги москвы, mos.ru,фнс россии, nalog.ru,фсс, fss.ru,росфинмониторинг, fedsfm.ru,единый реестр доменных имен роскомнадзора, eais.rkn.gov.ru,zakupki.gov.ru, zakupki.gov.ru,федеральное казначейство гис гмп, roskazna.ru/gis-gmp/index.php,цб (фсфр), cbr.ru/finmarkets/,росимущество, mvpt.rosim.ru,росфинмониторинг, fedsfm.ru,фсрар, fsrar.ru,фипс (роспатент), www1.fips.ru,мосэнергосбыт, mosenergosbyt.ru,фау главгосэкспертиза россии, uslugi.gge.ru,росаккредитация, fsa.gov.ru,этп стройторги, stroytorgi.ru,торговая система оборонторг, oborontorg.ru,ао отс, otc.ru,газнефтеторг.ру, gazneftetorg.ru/trades/,этп аукционный конкурсный дом, a-k-d.ru,торговая система спецстройторг, sstorg.ru,коммерсантъ картотека, utp.kartoteka.ru/etp/,единая строительная тендерная площадка – сро (естп сро), estp.ru,электронная торговая площадка российского аукционного дома (рад), lot-online.ru,универсальная электронная торговая площадка electro-torgi, electro-torgi.ru,сэтонлайн, setonline.ru,системы электронных паспортов (сэп), elpts.ru,этп угмк, zakupki.ugmk.com,гуп мосводосток, mosvodostok.com,этп элтокс, eltox.ru,оператор фискальных данных первый офд, 1-ofd.ru,росприроднадзор (рпн), rpn.gov.ru/node/20356,гис жкх, dom.gosuslugi.ru/#!/main,смэв, smev.gosuslugi.ru/portal', ''));
tpItems.push(getItem(9, '4160', 'Пакет &quot;Комби&quot;', 'Пакет включает в себя: - усиленный квалифицированный сертификат по требованиям федерального закона №63 - ФЗ &quot;О электронной подписи&quot;, который могут использовать юридические лица, индивидуальные предприниматели и физические лица, в т.ч. и для обращения в государственные службы и ведомства. Позволяет работать с: - Электронными Торговыми Площадками в требования которых указанано использование усиленного квалифицированного сертификата. - 5 Федеральными ЭТП: Сбербанк — АСТ, ЭТП ММВБ «Госзакупки», Единая электронная торговая площадка (ЕЭТП), РТС-тендер, ZakazRF, zakupki mos (портал поставщиков г. Москвы); - Группой площадок B2B-Center.', '', 'этп b2b-center, b2b-center.ru,этп сбербанк — аст, sberbank-ast.ru,этп ммвб госзакупки, etp-micex.ru,этп единая электронная торговая площадка (еэтп), roseltorg.ru,этп ртс тендер, rts-tender.ru,этп zakaz rf, etp.zakazrf.ru,этп портал поставщиков москвы, zakupki.mos.ru,национальный центр маркетинга и конъюнктуры цен, goszakupki.by,ис тендеры, icetrade.by,госуслуги, gosuslugi.ru,госуслуги москвы, mos.ru,фнс россии, nalog.ru,фсс, fss.ru,росфинмониторинг, fedsfm.ru,единый реестр доменных имен роскомнадзора, eais.rkn.gov.ru,zakupki.gov.ru, zakupki.gov.ru,федеральное казначейство гис гмп, roskazna.ru/gis-gmp/index.php,цб (фсфр), cbr.ru/finmarkets/,росимущество, mvpt.rosim.ru,росфинмониторинг, fedsfm.ru,фсрар, fsrar.ru,фипс (роспатент), www1.fips.ru,мосэнергосбыт, mosenergosbyt.ru,фау главгосэкспертиза россии, uslugi.gge.ru,росаккредитация, fsa.gov.ru,этп стройторги, stroytorgi.ru,торговая система оборонторг, oborontorg.ru,ао отс, otc.ru,газнефтеторг.ру, gazneftetorg.ru/trades/,этп аукционный конкурсный дом, a-k-d.ru,торговая система спецстройторг, sstorg.ru,коммерсантъ картотека, utp.kartoteka.ru/etp/,единая строительная тендерная площадка – сро (естп сро), estp.ru,электронная торговая площадка российского аукционного дома (рад), lot-online.ru,универсальная электронная торговая площадка electro-torgi, electro-torgi.ru,сэтонлайн, setonline.ru,системы электронных паспортов (сэп), elpts.ru,этп угмк, zakupki.ugmk.com,гуп мосводосток, mosvodostok.com,этп элтокс, eltox.ru,оператор фискальных данных первый офд, 1-ofd.ru,росприроднадзор (рпн), rpn.gov.ru/node/20356,гис жкх, dom.gosuslugi.ru/#!/main,смэв, smev.gosuslugi.ru/portal', ''));
tpItems.push(getItem(10, '8460', 'Пакет &quot;Универсальный&quot;', 'Пакет включает в себя: - усиленный квалифицированный сертификат по требованиям федерального закона №63 - ФЗ &quot;О электронной подписи&quot;, который могут использовать юридические лица, индивидуальные предприниматели и физические лица, в т.ч. и для обращения в государственные службы и ведомства. Позволяет работать с: - Электронными Торговыми Площадками в требования которых указанано использование усиленного квалифицированного сертификата. - 5 Федеральными ЭТП: Сбербанк — АСТ, ЭТП ММВБ «Госзакупки», Единая электронная торговая площадка (ЕЭТП), РТС-тендер, ZakazRF, zakupki mos (портал поставщиков г. Москвы); - Группой площадок B2B-Center; - ЭТП ТЭК Торг (Секция ПАО «НК «Роснефть»); - ЭТП Фабрикант; - площадкой Федресурс (ГП Интерфакс); - порталом Росреестра', '', ',,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,', ''));
tpItems.push(getItem(11, '3000', 'Пакет &quot;Автограф Издательства&quot;', 'Пакет включает в себя усиленный неквалифицированный сертификат для работы на портале i-autograph.com по тарифному плану &quot;Авторский&quot;', '', 'автограф издательства, i-autograph.com', ''));
tpItems.push(getItem(12, '900', 'Пакет &quot;Усиленный неквалифицированный сертификат&quot;', 'Пакет включает в себя усиленный НЕквалифицированный сертификат, который может использоваться для работы в корпоративных информационных системах. При оптовых закупках предоставляются индивидуальные скидки.', '', '', ''));


if(enotary_debug) { console.log('Registered tpItems: ' + tpItems.length); }



var translit_table = new Array(
    ['а', 'a', 'f'], 
    ['б', 'b', ','], 
    ['в', 'v', 'd'],  
    ['г', 'g', 'u'],
    ['д', 'd', 'l'], 
    ['е', 'e', 't'], 
    ['ё', 'yo', '`'], 
    ['ж', 'zh', ';'], 
    ['з', 'z', 'p'],
    ['и', 'i', 'b'], 
    ['й', 'y', 'q'], 
    ['к', 'k', 'r'],  
    ['л', 'l', 'k'],
    ['м', 'm', 'v'], 
    ['н', 'n', 'y'], 
    ['о', 'o', 'j'],  
    ['п', 'p', 'g'],  
    ['р', 'r', 'h'],
    ['с', 's', 'c'], 
    ['т', 't', 'n'], 
    ['у', 'u', 'e'],  
    ['ф', 'f', 'a'],
    ['х', 'h', '['], 
    ['ц', 'c', 'w'], 
    ['ч', 'ch', 'x'], 
    ['ш', 'sh', 'i'], 
    ['щ', 'shch', 'o'],
    ['ъ', '', ']'],  
    ['ы', 'y', 's'], 
    ['ь', '', 'm'],   
    ['э', 'e', ''], 
    ['ю', 'yu', '.'], 
    ['я', 'ya', 'z']);


function enot_translit_ru_en(txt_rus) {
	txt_rus = txt_rus.toLowerCase();
	txt_res = '';
	for(var i = 0; i < txt_rus.length; i++) {
		var txt_sym = txt_rus.charAt(i);
		for(var j = 0; j < translit_table.length; j++) {
			if(txt_sym == translit_table[j][0]) {
				txt_sym = translit_table[j][1];
				break;
			}
		}
	  txt_res += txt_sym;	
	}
  return txt_res;	
}


function enot_check_text_size(txt, max_size) {
 if(txt.length > max_size) { return txt.substring(0, max_size) + '...';	}
   else return txt;
}


function enot_translit_ru_bad(txt_rus) {
	txt_rus = txt_rus.toLowerCase();
	txt_res = '';
	for(var i = 0; i < txt_rus.length; i++) {
		var txt_sym = txt_rus.charAt(i);
		for(var j = 0; j < translit_table.length; j++) {
			if(txt_sym == translit_table[j][0]) {
				txt_sym = translit_table[j][2];
				break;
			}
		}
	  txt_res += txt_sym;	
	}
  return txt_res;	
}



function enot_html_item_by_index(index) {
	var html = '<a class="p-a-1 platform clr-diamondBlue" href="' + page_constructor + 
    tpItems[index].id + '" id="' + tpItems[index].id + '">';
    //'?tag=' + tpItems[index].id + '" id="' + tpItems[index].id + '">';
    html += '<p><span class="bold">' + tpItems[index].title +  '</span></p>';
    html += '<p><span class="price">от ' + tpItems[index].price + ' &#8381;</span></p>';
    html += '<p class="description small clr-diamondBlue">' + enot_check_text_size(tpItems[index].description, 150) + '</p>';
    html += '</a>';
	html += '<hr>';

    return html;
}


function enot_html_item_by_id(id) {
 var item = null;
 for(var i = 0; i < tpItems.length; i++) {
 	if(tpItems[i].id == id) {
 		return enot_html_item_by_index(i);
 	}
 }
}


function enot_add_default_tp() {
	jQuery('#tp-items').empty();
	jQuery('#tp-items').append(enot_html_item_by_id('1'));
	jQuery('#tp-items').append(enot_html_item_by_id('2'));
	jQuery('#tp-items').append(enot_html_item_by_id('3'));
	jQuery('#tp-items').append(enot_html_item_by_id('4'));
	
}


function enot_search() {
	var search_text = jQuery('#search').val();
	if(enotary_debug) {	console.log('enot_search: ' + search_text ); }

	if(search_text.length < 2) { 
		enot_add_default_tp();
		return false; 
	}

	var cnt_res = 0;
	search_text = search_text.toLowerCase();
	

	jQuery('#tp-items').empty();
	jQuery('#tp-items').append('<h4>Результат поиска:</h4>');

	for(var i = 0; i < tpItems.length; i++) {
		var txt_lib = tpItems[i].title + ' ' + ' ' + tpItems[i].keywords + ' ' + 
		enot_translit_ru_en(tpItems[i].title) + ' ' + enot_translit_ru_en(tpItems[i].keywords) + ' ' + 
		enot_translit_ru_bad(tpItems[i].title) + ' ' + enot_translit_ru_bad(tpItems[i].keywords);
		txt_lib = txt_lib.toLowerCase();
		var n = txt_lib.search(search_text);

		if(enotary_debug) {		
			//console.log(' > lib: ' + txt_lib);
			//console.log(' > search: ' + search_text + ' Result: ' + n);
		}

		if(n != -1) {
			cnt_res++;
			jQuery('#tp-items').append(enot_html_item_by_id(tpItems[i].id));
		}
	}

	if(cnt_res == 0) {
	    jQuery('#tp-items').append('<p>Таких площадок не найдено, но попробуйте вот эти:</p>');	
		jQuery('#tp-items').append(enot_html_item_by_id('x3'));
		jQuery('#tp-items').append(enot_html_item_by_id('x15'));	   
	}
}



/**
 * order form scripts
 * Steps: wizard_order_preview, wizard_order_contact, wizard_order_company, wizard_order_delivery, wizard_order_payment, wizard_order_final
 */

var current_step = 0;

function order_wizard(step) {
  console.log("order_wizard: " + step);  
  current_step = step;
  calc_price();
  
  if(step == 0)  {
      set_visible('wizard_order_items', false);
      set_visible('wizard_order_preview', true);
      set_visible('wizard_order_contact', true);
      set_visible('wizard_order_company', true);
      set_visible('wizard_order_delivery', true);
      set_visible('wizard_order_payment', true);
      set_visible('wizard_order_final', false);
      
      set_visible('list-inputs', false);

  } 
  else if(step == 1)  {
      set_visible('wizard_order_items', false);
      set_visible('wizard_order_preview', true);
      set_visible('wizard_order_contact', false);
      set_visible('wizard_order_company', false);
      set_visible('wizard_order_delivery', true);
      set_visible('wizard_order_payment', true);
      set_visible('wizard_order_final', false);
      
      set_visible('list-inputs', true);
  } 
  else if(step == 2)  {
      set_visible('wizard_order_items', false);
      set_visible('wizard_order_preview', true);
      set_visible('wizard_order_contact', false);
      set_visible('wizard_order_company', false);
      set_visible('wizard_order_delivery', false);
      set_visible('wizard_order_payment', false);
      set_visible('wizard_order_final', true);
      
      set_visible('list-inputs', true);
  }   
  else  {
      set_visible('wizard_order_items', true);
      set_visible('wizard_order_preview', false);
      set_visible('wizard_order_contact', false);
      set_visible('wizard_order_company', false);
      set_visible('wizard_order_delivery', false);
      set_visible('wizard_order_payment', false);
      set_visible('wizard_order_final', false);
      set_visible('list-inputs', true);
  }

    set_disable('btn-next-contacts', true);
    input_validator();
 }


function block_button(id, is_block) {
  var btn = get_by_id(id);
  if(btn == null) { return false; }
  
  if(is_block) {
    set_class(id, 'disabled', true);        
    set_disable(id, true); 
  } else {
    set_disable(id, false); 
    set_class(id, 'disabled', false); 
  }
}
   
   
function isEmptyValue(id) {
  if(id != '') {
    var txtValue = get_value(id, true);
    return is_empty(txtValue);
  } else {
      return true;
  }
}   
    
function block_by_values(idButton, idV1, idV2, idV3, idV4, idV5, idV6) {
  var needBlock = false;
  var txtValue = '';
  
  if(idV1 != '') {  if(isEmptyValue(idV1)) { needBlock = true; } }
  if(idV2 != '') {  if(isEmptyValue(idV2)) { needBlock = true; } }
  if(idV3 != '') {  if(isEmptyValue(idV3)) { needBlock = true; } }
  if(idV4 != '') {  if(isEmptyValue(idV4)) { needBlock = true; } }
  if(idV5 != '') {  if(isEmptyValue(idV5)) { needBlock = true; } }
  if(idV6 != '') {  if(isEmptyValue(idV6)) { needBlock = true; } }
  
  //if(needBlock) { toLog('need block: ' + idButton); } else { toLog('unblock: ' + idButton); }
  block_button(idButton, needBlock);
}    
    

 function bad_text(id, minSize, isEmail, isPhone) {
   var value = get_value(id, true);
   toLog('validate_text: ' + id + ', minSize: ' + minSize + ', value: ' + value);
   var is_bad = false;
   var rowID = '#row-' + id;

   // removeClass - addClass
   if(isEmail) {
      if(!ValidMail(value)) { { is_bad = true; } }
   } else
   if(isPhone) {
      if(!ValidPhone(value)) { { is_bad = true; } }
   }   
     else {
      if(value.length < minSize) { is_bad = true; }
    }
   
   if(is_bad) {
    if(value.length >0) {  
       jQuery(rowID).addClass("has-danger"); 
       jQuery(rowID).removeClass("has-success");
     }
     else {
       jQuery(rowID).removeClass("has-danger"); 
       jQuery(rowID).removeClass("has-success");
     }
   }
     else { 
       jQuery(rowID).removeClass("has-danger"); 
       jQuery(rowID).addClass("has-success");
     }
   
   return is_bad;
 }




/**

	define('LAW_TYPE_UR', 0);
	define('LAW_TYPE_FL', 1);
	define('LAW_TYPE_IP', 2);

*/

function input_validator() {
  console.log("input_validator()"); 

  if(current_step == 0)  {

      var needBlock = false;

      // Need for all types .......................................
      if(bad_text('name', 5, false, false)) { needBlock = true; };
      if(bad_text('phone', 10, false, true)) { needBlock = true; };
      if(bad_text('email', 8, true, false)) { needBlock = true; };

      var law_type = parseInt(get_value('law-type', true));
      if(law_type == 1) {
         // UL   
         //block_by_values('btn-next-payment', 'name', 'phone', 'email', 
         // 'company-title', 'company-inn', 'company-address');
        
        if(bad_text('company-title', 3, false, false)) { needBlock = true; }; // Наименование юр-го лица*
        if(bad_text('company-inn', 9, false, false)) { needBlock = true; }; // ИНН (юр-го лица)*
		if(bad_text('company-kpp',  8, false, false)) { needBlock = true; }; // ИНН (юр-го лица)*
		if(bad_text('company-okpo', 7, false, false)) { needBlock = true; }; // ИНН (юр-го лица)*
        if(bad_text('company-address', 8, false, false)) { needBlock = true; }; // Юридический адрес*

      } else {
        // FL & IP
        //block_by_values('btn-next-payment', 'name', 'phone', 'email', 'fl-inn', 'fl-address', '');
        if(bad_text('fl-inn', 9, false, false)) { needBlock = true; };
        if(bad_text('fl-address', 8, false, false)) { needBlock = true; };
      }

//	  if(bad_text('post-index', 5, false, false)) { needBlock = true; }; // Почтовый индекс
	  if(bad_text('post-index', 5, false, true)) { needBlock = true; }; // Почтовый индекс

      block_button('btn-next-payment', needBlock);
  }
}


function calc_price() {
    var arr_id 	= list_id.split(";");
    var arr_prices = list_price.split(";");
    var price = parseInt(base_price);
    
    // ok-v2
    var html_preview = html_tag('li', '<b>' + get_value('package-title', false) + '</b>' + ' ' + 
           price + ' Руб.' + ' ');
    

    // Calc price from selected additional items
    for(i = 0; i < arr_id.length; i++) {
        var cb = get_by_id("addon-" + arr_id[i]);
        if((cb != null) && (cb.checked)) {
            var addon_price = parseInt(arr_prices[i]);
            price += addon_price;
            //var addon = get_by_id("addon-title-" + arr_id[i]);
            var addon_title = get_value("addon-title-" + arr_id[i], false);
            if(addon_price > 0) { addon_title += ' ' + addon_price + ' Руб.'; }
            html_preview += html_tag('li', addon_title, false); // auto build compact order preview
        }
    }

    // Calc price from CSP
    var index_csp = parseInt(get_value('csp-type', true));
    if(index_csp  >= 0 ) {
        var csp_price = parseInt(arrCSP[index_csp]);
        var csp_title = get_value("csp-title-" + index_csp, true);
        toLog('CSP selected: ' + index_csp + ', price: ' + arrCSP[index_csp] + ', title: ' + csp_title );
        
        if(csp_price > 0) { csp_title += ' (' + csp_price + ' Руб.)'; }
        html_preview += html_tag('li', 'Криптопровайдер: ' + csp_title);
        if( csp_price > 0) { price += csp_price;  }
    }

    // Calc price from Token
    var index_token = parseInt(get_value('token-type', true));
    if(index_token  >= 0 ) {
        var token_price = parseInt(arrToken[index_token]);
        var token_title = get_value("token-title-" + index_token, true);
        toLog('Token selected: ' + index_token + ', price: ' + arrToken[index_token] + ', title: ' + token_title );
        
        if(token_price > 0) { token_title += ' (' + token_price + ' Руб.)'; }
        html_preview += html_tag('li', 'Токен:: ' + token_title);
        if( token_price > 0) { price += token_price;  }
    }
    
    // Set total price
    set_value("price-total", price + " Руб.", false);
    set_value("price-total2", price + " Руб.", false);
    set_value("price-total3", price , true);
//    var item = document.getElementById("prices");
//    item.innerHTML = '2678';
//    alert (item.value);

    toLog("prices: " + price);

    var html_inputs = "";
    // add contact information ........................................
    var sName = get_value('name', true);
    var sPhone = get_value('phone', true);
    var sEmail = get_value('email', true);
    var sPostIndex = get_value('post-index', true);
    
    if( !is_empty(sName) && !is_empty(sPhone) && !is_empty(sEmail)) {
       var txt = 'ФИО: ' + sName + ', Email: ' + sEmail + ", Телефон: " + sPhone; 
       html_inputs += html_tag('li', txt, false);  
    }
    
    
    var law_type = parseInt(get_value('law-type', true));
  
   if(law_type == 0) {
    // UL
        var sCompany = get_value('company-title', true);
        var sINN = get_value('company-inn', true);
        var sKPP = get_value('company-kpp', true);
        var sOKPO = get_value('company-okpo', true);
        var sAddr = get_value('company-address', true);
                
        if( !is_empty(sCompany) && !is_empty(sINN) ) {
            var txt = 'Реквизиты: ' + sCompany + ', ИНН: ' + sINN + ", КПП: " + sKPP + ", ОКПО: " + sOKPO + 
                    ", адрес: " + sAddr + ", Почтовый индекс: " + sPostIndex; 
            html_inputs += html_tag('li', txt, false);  
        }
   }
    else {
      // IP and FL  
        var sINN = get_value('fl-inn', true);
        var sAddr = get_value('fl-address', true);
        
        if( !is_empty(sINN) && !is_empty(sAddr) ) {
            var txt = 'ИНН: ' + sINN + ', Адрес: ' + sAddr + ", Почтовый индекс: " + sPostIndex; 
            html_inputs += html_tag('li', txt, false);  
        }

    }


   //toLog('XXX: ' + get_radio_value('delivery-type'));
   // 1 - in office
   // 2 - courier
   var delivery_type = parseInt(get_radio_value('delivery-type'));
   if(delivery_type > 0) {
     var txt = 'Доставка: ' + get_value('lbl-delivery-' + delivery_type);
      html_preview += html_tag('li', txt, false); 
   }

   
    set_value("list-preview", html_preview, false);
    set_value("list-inputs", html_inputs, false);
} //End calc_price

function check_csp(id) {
  id = parseInt(id);

  var docToken = parseInt(get_value('token-type',true));
  var docCSP = parseInt(get_value('csp-type', true));
  toLog('docToken: ' + docToken);
  if(docCSP != -2) {
    set_value('csp-type', id, true);
    
    if(id < 0) {
      if( id == -1 ) { block_button('btn-next-start', true);  }
      //set_value("selected-csp", csp_title, false);
      toLog('not selected CSP: ' + id + ' / ' + docCSP);
    }
    else { 
        var csp_title = get_value("csp-title-" + id, true);
        block_button('btn-next-start', false);
        set_value("selected-csp", csp_title, false);
        toLog('selected CSP: ' + id + ', ' + csp_title);
    }
    if (docToken == -1 ) { block_button('btn-next-start', true);  }
  }
  calc_price();
}

function check_token(id) {
  id = parseInt(id);
  
  var docCSP = parseInt(get_value('csp-type',true));
  var docToken = parseInt(get_value('token-type', true));
  if(docToken != -2) {
    set_value('token-type', id, true);
    if(id < 0) { 
      if(id == -1) { block_button('btn-next-start', true);  }
      toLog('not selected Token: ' + id + ' / ' + docToken);
    }
       else { 
        var token_title = get_value("token-title-" + id, true);
        block_button('btn-next-start', false); 
        set_value("selected-token", token_title, false);
        //toLog('selected CSP: ' + id + ', ' + csp_title);
      }
    if (docCSP == -1 ) { block_button('btn-next-start', true);  }
  }  
  calc_price();
}

//function set_type(path, price)
function set_type()
{
//    prices = parseInt(price);
    var item = document.getElementById("price-total3");
    var price = item.value;
    var order = document.getElementById("id-order").value;
    alert (price);
    var data_pr="price=" + price;
    alert (data_pr);
    alert ("order = "+order);
//    $.ajax({
//        type: "POST",
//        url: "/pay/save.php",
//        data: { prices: price }
//        });
//          .done(function( msg ) {
//              alert( "Data Saved: " + msg );
//    });


//    jQuery.ajax({
///		url: "/wp-content/plugins/enotary/classes/robo.php",
//		type: "POST",
//		data: data,
//		cache: false,
//		success: function (url) {
//			if (url) {
//				alert('Ваше сообщение отправлено');
//				location.href = url;
//				window.open(url);
//			} else alert('Ваше сообщение не отправлено, попробуйте позже');
//		},
//		error: function (html){
//            alert('Проблемы на сервере, Ваше сообщение не отправлено, попробуйте позже');
//        }
//	});

    window.open("/pay/demo2.php?price="+price+"&zakaz="+order, "_blank");
//    window.open("/wp-content/themes/enotary/test-1.php", "_blank");
//    window.open("/wp-content/plugins/enotary/classes/robo.php?price="+price+"zag=Оплата заказа с сайта" , "_blank");
    toLog('test type: 123 ');
//    toLog('paths: ' + path);
//    toLog('price: ' + prices);
}

function check_token_old(id) {
  set_class('token-1', 'active', id==1);
  set_class('token-2', 'active', id==2);
  set_class('token-3', 'active', id==3);

  calc_price();
}

function scrollTo(id) {
  //jQuery('html, body').animate({ scrollTop: jQuery('#' + id).offset().top }, 'slow'); return false; 
}