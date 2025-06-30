create database padaria;
use padaria;

create table produtos(
codigo_interno int auto_increment not null,
descricao varchar (200),
valor double,
unidade_medida varchar (30),
status_prod varchar(10),
primary key(codigo_interno)
); 

create table codigo_barras(
id int auto_increment,
cod_barras varchar (100),
cod_interno int,
st_at varchar (20),
primary key (id),
foreign key (cod_interno)  references produtos ( codigo_interno)
	);
 
create table usuario (
id int auto_increment,
nome varchar (100),
senha varchar (50),
primary key(id)
);

create table cad_fornecedores(
cod_fornecedores int auto_increment,
nome_fantasia varchar (100),
razao_social varchar (100),
cnpj varchar (14),
email varchar (50),
telefone varchar (11),
primary key (cod_fornecedores)
);

create table vendas(
cod_venda int auto_increment not null,
valor double,
datah datetime,
forma_pagamento varchar (50),
primary key (cod_venda)
);

create table item_vendas(
cod_item int auto_increment,
cod_ved int,
cod_int int,
quantidade double,
subtotal double,
primary key (cod_item),
foreign key (cod_ved) references vendas ( cod_venda),
foreign key (cod_int) references produtos (codigo_interno)
);

create table cad_clientes(
cod_cliente int auto_increment,
nome varchar (100),
nome_fantasia varchar (100),
cpf varchar (11),
cnpj varchar (14),
email varchar (50),
telefone varchar (11),
valor double,
datah datetime,
st_devedor varchar (10),
comentario varchar (255),
cod_vend int,
primary key (cod_cliente),
foreign key (cod_vend) references vendas (cod_venda)
);

INSERT INTO produtos (descricao, valor, unidade_medida, status_prod) VALUES
('Refrigerante Coca-Cola Zero 2L Retornável', '8.50', 'UN', 'ativo'),
('Refrigerante Coca-Cola 2L Retornável', '8.50', 'UN', 'ativo'),
('Água Nestlé 510ml Sem Gás', '2.75', 'UN', 'ativo'),
('Água Sprite Lemon Fresh 510ml', '4.50', 'UN', 'ativo'),
('Mortadela Defumada Perdigão', '23.90', 'KG', 'ativo'),
('Pão Francês', '13.90', 'KG', 'ativo'),
('Refrigerante Coca-Cola 2L', '10.50', 'UN', 'ativo'),
('Refrigerante Coca-Cola Zero 2L', '10.50', 'UN', 'ativo'),
('Mortadela Marba', '19.90', 'KG', 'ativo'),
('Presunto Perdigão', '29.99', 'KG', 'ativo'),
('Apresuntado Perdigão', '25.75', 'KG', 'ativo'),
('Queijo Mussarela', '35.99', 'KG', 'ativo'),
('Pão de Leite', '24.99', 'KG', 'ativo'),
('Pão Santo Antônio', '22.99', 'KG', 'ativo'),
('Pão de Hot Dog', '26.90', 'KG', 'ativo'),
('Pão de Hambúrguer', '27.90', 'KG', 'ativo'),
('Pão Doce', '26.50', 'KG', 'ativo'),
('Brigadeirão', '8.50', 'UN', 'ativo'),
('Tortinha Holandesa', '8.50', 'UN', 'ativo'),
('Pudim (Pedaço)', '8.50', 'UN', 'ativo'),
('Pudim Inteiro', '45.90', 'UN', 'ativo'),
('Torta Doce Recheada', '70.00', 'UN', 'ativo'),
('Torta Salgada Recheada', '56.00', 'UN', 'ativo'),
('Bomba Diversas', '8.50', 'UN', 'ativo'),
('Carolinas Diversas', '30.99', 'KG', 'ativo'),
('Bolo no Pote', '10.00', 'UN', 'ativo'),
('Trufas no Pote', '11.00', 'UN', 'ativo'),
('Pão de Mel no Pote', '11.00', 'UN', 'ativo'),
('Lua de Mel', '30.90', 'KG', 'ativo'),
('Pão Amanteigado', '18.99', 'KG', 'ativo'),
('Bolo de Corte', '17.90', 'KG', 'ativo'),
('Bolo Caseiro', '16.99', 'KG', 'ativo'),
('Doce Folheado', '32.00', 'KG', 'ativo'),
('Salgado Folheado', '30.90', 'KG', 'ativo'),
('Donuts Recheados', '6.00', 'UN', 'ativo'),
('Muffins', '5.90', 'UN', 'ativo'),
('Sonho', '33.90', 'KG', 'ativo'),
('Broa Aerosa', '24.90', 'KG', 'ativo'),
('Broa Caxambu', '24.90', 'KG', 'ativo'),
('Cueca Virada', '23.90', 'KG', 'ativo'),
('Samantha', '23.90', 'KG', 'ativo'),
('Pão Caseiro', '22.99', 'KG', 'ativo'),
('Baguete', '27.90', 'KG', 'ativo'),
('Baguete Recheada', '35.90', 'KG', 'ativo'),
('Café Bule Pequeno', '2.25', 'UN', 'ativo'),
('Café Bule Grande', '4.00', 'UN', 'ativo'),
('Café Expresso Pequeno', '3.25', 'UN', 'ativo'),
('Café Expresso Grande', '6.00', 'UN', 'ativo'),
('Pingado Bule', '5.75', 'UN', 'ativo'),
('Pingado Expresso', '7.50', 'UN', 'ativo'),
('Suco Natural', '8.75', 'UN', 'ativo'),
('Chocolate Gelado', '9.00', 'UN', 'ativo'),
('Chocolate Quente', '7.00', 'UN', 'ativo'),
('Cappuccino', '7.90', 'UN', 'ativo'),
('Nescau Bebida Láctea 270ml', '3.75', 'UN', 'ativo'),
('Nescau Bebida Láctea 390ml', '7.00', 'UN', 'ativo'),
('Mococa Bebida Láctea 200ml', '2.25', 'UN', 'ativo'),
('Alpino Bebida Láctea 390ml', '7.00', 'UN', 'ativo'),
('Nescafé Bebida Láctea 390ml', '7.00', 'UN', 'ativo'),
('Neston Bebida Láctea 390ml', '7.00', 'UN', 'ativo'),
('Nescafé Solúvel 40g', '4.75', 'UN', 'ativo'),
('Nestlé Nescau 370g', '7.90', 'UN', 'ativo'),
('Café Toninho 1kg', '17.90', 'UN', 'ativo'),
('Café Toninho 500g', '8.95', 'UN', 'ativo'),
('Café Coppo 1kg', '15.99', 'UN', 'ativo'),
('Café Coppo 500g', '7.99', 'UN', 'ativo'),
('Filtro de Café Melitta', '9.75', 'UN', 'ativo'),
('Açúcar União 1kg', '6.50', 'UN', 'ativo'),
('Leite Tirol 1L', '5.75', 'UN', 'ativo'),
('Triunfo Cream Cracker 164g', '2.99', 'UN', 'ativo'),
('Triunfo Biscoito Recheado Tortini Chocolate 90g', '2.89', 'UN', 'ativo'),
('Triunfo Biscoito Maizena 345g', '5.93', 'UN', 'ativo'),
('Bauducco Wafer Chocolate 140g', '3.75', 'UN', 'ativo'),
('Bauducco Wafer Morango 140g', '3.75', 'UN', 'ativo'),
('Ruffles 68g', '5.90', 'UN', 'ativo'),
('Doritos 68g', '5.90', 'UN', 'ativo'),
('Cheetos 68g', '4.75', 'UN', 'ativo'),
('Fandangos 68g', '4.75', 'UN', 'ativo'),
('Cebolitos 68g', '6.00', 'UN', 'ativo'),
('Trident Menta 8g', '2.50', 'UN', 'ativo'),
('Trident Morango 8g', '2.50', 'UN', 'ativo'),
('Trident Hortelã 8g', '2.50', 'UN', 'ativo'),
('Trident Melancia 8g', '2.50', 'UN', 'ativo'),
('Halls Melancia 28g', '2.75', 'UN', 'ativo'),
('Halls Cereja 28g', '2.75', 'UN', 'ativo'),
('Halls Menta 28g', '2.75', 'UN', 'ativo'),
('Halls Extra Forte 28g', '2.75', 'UN', 'ativo'),
('Bala Variada (Unidade)', '0.20', 'UN', 'ativo'),
('Bala Variada (Grande)', '0.25', 'UN', 'ativo'),
('Chiclete (Unidade)', '0.30', 'UN', 'ativo'),
('Pirulito (Unidade)', '0.35', 'UN', 'ativo'),
('Nestlé Suflair', '4.75', 'UN', 'ativo'),
('Mentos Hortelã', '2.50', 'UN', 'ativo'),
('Mentos Tutti-Frutti', '2.50', 'UN', 'ativo'),
('Mentos Morango', '2.50', 'UN', 'ativo'),
('Mentos Frutas Vermelhas', '2.50', 'UN', 'ativo'),
('Pão de Queijo', '1.00', 'UN', 'ativo'),
('Pão de Batata', '25.90', 'KG', 'ativo'),
('Suco Del Valle Frutas Cítricas 390ml', '4.25', 'UN', 'ativo'),
('Suco Del Valle Laranja 1L', '8.00', 'UN', 'ativo');

      
                      
insert into codigo_barras ( cod_interno, cod_barras,st_at)
values('1','7894900701','sim'),
	  ('2','8690453625','sim'),
      ('3','0076543289','sim'),
      ('4','5678903456','sim'),
      ('5','8670542789','sim'),
      ('6','7890564111','sim'),
      ('7','8905461233','sim'),
      ('8','9084556531','sim'),
      ('9','1278934655','sim'),
      ('10','7892340010','sim'),
      ('11','0732849018','sim'),
      ('12','9037465892','sim'),
      ('13','6757485748','sim'),
      ('14','7854673491','sim'),
      ('15','8900768651','sim'),
      ('16','9087656744','sim'),
      ('17','7676567590','sim'),
      ('18','1347867436','sim'),
      ('19','8765645649','sim'),
      ('20','7545658769','sim'),
      ('21','9756486879','sim'),
      ('22','7654559878','sim'),
      ('23','6754527790','sim'),
      ('24','9809787867','sim'),
      ('25','8767565790','sim'),
      ('26','8765648980','sim'),
      ('27','7854578989','sim'),
      ('28','9807947284','sim'),
      ('29','7543478900','sim'),
      ('30','1232345767','sim'),
      ('31','8707809009','sim'),
      ('32','1238746574','sim'),
      ('33','7584750948','sim'),
      ('34','4875093840','sim'),
      ('35','8750398530','sim'),
      ('36','4503475049','sim'),
      ('37','4857392593','sim'),
      ('38','3503959300','sim'),
      ('39','4750759300','sim'),
      ('40','6708909098','sim'),
      ('41','8728382749','sim'),
      ('42','6348394038','sim'),
      ('43','8794859090','sim'),
      ('44','8798090900','sim'),
      ('45','1392849343','sim'),
      ('46','5989040994','sim'),
      ('47','3908349094','sim'),
      ('48','0987798755','sim'),
      ('49','7690908899','sim'),
      ('50','7665798000','sim'),
      ('51','8785908508','sim'),
      ('52','8769890989','sim'),
      ('53','7676576878','sim'),
      ('54','6553687989','sim'),
      ('55','7876578989','sim'),
      ('56','7594870986','sim'),
      ('57','7698907656','sim'),
      ('58','4546878990','sim'),
      ('59','7664345879','sim'),
      ('60','7654368789','sim'),
      ('61','8775466879','sim'),
      ('62','7654589809','sim'),
      ('63','7645435567','sim'),
      ('64','6744344354','sim'),
      ('65','6534587890','sim'),
      ('66','8776587878','sim'),
      ('67','7654365876','sim'),
      ('68','7867658989','sim'),
      ('69','0986698980','sim'),
      ('70','9088698709','sim'),
      ('71','8977678900','sim'),
      ('72','9078677589','sim'),
      ('73','6566986899','sim'),
      ('74','7656899089','sim'),
      ('75','7898988899','sim'),
      ('76','7637687878','sim'),
      ('77','8758989089','sim'),
      ('78','6545787789','sim'),
      ('79','8372974388','sim'),
      ('80','9848989000','sim'),
      ('81','9865435654','sim'),
      ('82','6649898877','sim'),
      ('83','0987654333','sim'),
      ('84','7563548789','sim'),
      ('85','6432135676','sim'),
      ('86','7564765887','sim'),
      ('87','7656468789','sim'),
      ('88','6447869798','sim'),
      ('89','6745487899','sim'),
      ('90','7948593479','sim'),
      ('91','8678569898','sim'),
      ('92','8969658990','sim'),
      ('93','7867435767','sim'),
      ('94','0696788089','sim'),
      ('95','8985789899','sim'),
      ('96','2457789898','sim'),
      ('97','8098598990','sim'),
      ('98','6752814837','sim'),
      ('99','7938475499','sim'),
      ('100','9675468789','sim');
      
insert into codigo_barras ( cod_interno, cod_barras, st_at)
values('1', '7894900000', 'sim');



insert into usuario ( nome, senha)
values ('Laura Cristina','1234'),
       ('Liliane Fogaça','5678');
       
       
	
insert into cad_clientes (nome, nome_fantasia, cpf, cnpj, email, telefone, st_devedor)
values  ('Julia Silva', '', '23456789001','0','julia@gmail.com', '19903458906','não'),
		('Centro de Pagamento do Exército', 'CPEx', '0','2100200200022','cpex@gmail.com', '19999706512','não'),
        ('Mercado Atacadão', 'Atacadão', '0','22001003000177','mercadoatacadao@gmail.com', '19969753498','não'),
        ('Martins Ferragens - Ferro e Aço para Construção', 'martins ferro', '0','23003004000134','martins ferro@gmail.com', '19909872020','não'),
        ('WQ Portões e Estruturas Metálicas', 'WQ Portões', '0','89002898000154','wq portoes@gmail.com', '19934567890','não'),
        ('Provisão Produtos Elétricos', 'Provisão', '0','10200345000123','provisoes@gmail.com', '19989761234','não'),
        ('Marisa', 'Marisa', '45367890123','0','marisa@gmail.com', '19967843902','não'),
        ('Yugo Tamashiro', '', '12345678901','0','yugo tamashiro@gmail.com', '19978905466','não'),
        ('Congrelongo Concretaria', 'Congrelongo', '0','20310456000189','congrelon@gmail.com', '19998763425','não'),
        ('João Silva', '', '90876543000','0','joao silva@gmail.com', '19960024553','não'),
        ('Invictus Consultoria em RH', 'Invictus', '0','01001001000101','invictus@gmail.com', '19935526789','não');
              
insert into cad_fornecedores (razao_social, nome_fantasia, cnpj, email,telefone)  
values	('Paulo Souza LTDA','Cevisko','01002002000102','cevisko@gmail.com', '19999996475'),
		('Cristiane Delgato LTDA','Donato','22452882000132','donato@gmail.com', '19987901112'),
        ('BRF LTDA','Perdigão','18412672000167','perdigao@gmail.com', '19919821934'),
        ('Atillio Fontana LTDA','Sadia','56002567000154','sadia@gmail.com', '19909904329'),
        ('Claudemir José Pelissari LTDA','Pelissari','62343011000101','pelissari@gmail.com', '19998763456'),
        ('Berkshire Hathaway LTDA','Coca-Cola','67890123000102','coca-cola@gmail.com', '19990742143'),
        ('Vicent del Bianchi LTDA','Del Bianchi Distribuidora','54012345000168','delbianchi@gmail.com', '19978907784'),
        ('Paul Bulcke LTDA','Nestle','80444556000145','nestle@gmail.com', '19909122312'),
        ('Alexandro Cooser LTDA','Cooser','98055702000120','cooser@gmail.com', '19990874356'),
        ('Refrigerantes Mogi Industria e Comércio de Bebidas LTDA ','Mogi','44602578000100','mogi@gmail.com', '19921564378'),
		('Amelia-Toffit','Toffit','73678003000103','@gmail.com', '19989457632');
       
insert into vendas (valor, datah, forma_pagamento)
values ('13.90','2024/08/01','crediario'),
	   ('2.75','2024/04/1','crediario'),
	   ('23.90','2024/09/02','crediario');
     
     
     
insert into item_vendas (cod_int, cod_ved, quantidade, subtotal)
values ('1','1', '2', '17.00'),
	   ('2','2', '1', '8.50'),
       ('5','3', '0.5', '11.95'),
       ('6','3', '1', '13.90');


/* comentando selects
select * from produtos;

select * from codigo_barras;

select * from usuario;

select * from cad_clientes;
	
select * from cad_fornecedores;

select * from item_vendas;

select * from vendas;
*/