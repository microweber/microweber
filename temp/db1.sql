--MSSQL SQL Dump File. Created on: 08-17-2012 18:40:28
--Created from MySQL
--Created with Data Loader Ver.:4.1
--For more information Please visit http://www.DBLoad.com
-- 
CREATE TABLE [firecms_cart] ([id] BIGINT,[to_table] VARCHAR(1500),[to_table_id] BIGINT,[updated_on] DATETIME,[created_on] DATETIME,[item_name] TEXT,[price] TEXT,[currency] TEXT,[weight] TEXT,[qty] TEXT,[other_info] TEXT,[sid] TEXT,[sku] TEXT,[size] TEXT,[colors] TEXT,[order_completed] CHAR(1),[order_id] TEXT,[height] TEXT,[length] TEXT,[width] TEXT,[added_shipping_price] VARCHAR(50),[skip_promo_code] CHAR(1)
);
INSERT INTO [firecms_cart] ( [id], [to_table], [to_table_id], [updated_on], [created_on], [item_name], [price], [currency], [weight], [qty], [other_info], [sid], [sku], [size], [colors], [order_completed], [order_id], [height], [length], [width], [added_shipping_price], [skip_promo_code]) VALUES (2,'table_content',4170,'2012-02-19 22:00:11','2012-02-19 21:58:01','RG6 60% MaxPipe CATV Wht 1000 FT Reel in Box.','80',NULL,'30.35','1',NULL,'72b77d8d645218dacdd045ffbcfada1e',NULL,NULL,NULL,'n',NULL,'12.25','12.5','12.5',NULL,'n')
INSERT INTO [firecms_cart] ( [id], [to_table], [to_table_id], [updated_on], [created_on], [item_name], [price], [currency], [weight], [qty], [other_info], [sid], [sku], [size], [colors], [order_completed], [order_id], [height], [length], [width], [added_shipping_price], [skip_promo_code]) VALUES (3,'table_content',4170,'2012-02-19 22:03:21','2012-02-19 22:02:56','RG6 60% MaxPipe CATV Wht 1000 FT Reel in Box.','80',NULL,'30.35','95',NULL,'bbfdf1f2857b8fd6de4e748214177c2e',NULL,NULL,NULL,'y','ORD12021911291325252','12.25','12.5','12.5',NULL,'n')
INSERT INTO [firecms_cart] ( [id], [to_table], [to_table_id], [updated_on], [created_on], [item_name], [price], [currency], [weight], [qty], [other_info], [sid], [sku], [size], [colors], [order_completed], [order_id], [height], [length], [width], [added_shipping_price], [skip_promo_code]) VALUES (4,'table_content',4170,'2012-02-19 22:06:15','2012-02-19 22:05:43','RG6 60% MaxPipe CATV Wht 1000 FT Reel in Box.','80',NULL,'30.35','100',NULL,'bbfdf1f2857b8fd6de4e748214177c2e',NULL,NULL,NULL,'y','ORD12021911291325252','12.25','12.5','12.5',NULL,'n')
INSERT INTO [firecms_cart] ( [id], [to_table], [to_table_id], [updated_on], [created_on], [item_name], [price], [currency], [weight], [qty], [other_info], [sid], [sku], [size], [colors], [order_completed], [order_id], [height], [length], [width], [added_shipping_price], [skip_promo_code]) VALUES (5,'table_content',4169,'2012-02-19 22:17:15','2012-02-19 22:17:15','RG6 60% MaxPipe CATV Wht 1000 FT Reel.','80',NULL,'29.4','1',NULL,'bbfdf1f2857b8fd6de4e748214177c2e',NULL,NULL,NULL,'y','ORD12021911291325252','10.13','12.75','12.75',NULL,'n')
INSERT INTO [firecms_cart] ( [id], [to_table], [to_table_id], [updated_on], [created_on], [item_name], [price], [currency], [weight], [qty], [other_info], [sid], [sku], [size], [colors], [order_completed], [order_id], [height], [length], [width], [added_shipping_price], [skip_promo_code]) VALUES (6,'table_content',4136,'2012-02-19 23:04:12','2012-02-19 23:04:12','Computer Interface Cord, for non USB Birdog version','16',NULL,'0.11','1',NULL,'27eec8bfc6a509f6c0ed50ecfbe21ed0',NULL,NULL,NULL,'n',NULL,'1.25','6','1.31',NULL,'n')
CREATE TABLE [firecms_cart_currency] ([id] BIGINT,[updated_on] DATETIME,[created_on] DATETIME,[is_active] CHAR(1),[currency_from] TEXT,[currency_to] TEXT,[currency_rate] TEXT
);
INSERT INTO [firecms_cart_currency] ( [id], [updated_on], [created_on], [is_active], [currency_from], [currency_to], [currency_rate]) VALUES (39,'2010-10-25 01:36:14','2010-10-25 01:36:14','n','eur','usd','1.393')
INSERT INTO [firecms_cart_currency] ( [id], [updated_on], [created_on], [is_active], [currency_from], [currency_to], [currency_rate]) VALUES (38,'2010-10-25 01:35:49','2010-10-25 01:35:49','n','eur','gbp','0.887')
CREATE TABLE [firecms_cart_orders] ([id] BIGINT,[updated_on] DATETIME,[created_on] DATETIME,[sid] TEXT,[country] TEXT,[order_id] TEXT,[promo_code] TEXT,[amount] TEXT,[clientid] BIGINT,[customercode] VARCHAR(255),[transactionid] VARCHAR(255),[cardholdernumber] VARCHAR(50),[expiresmonth] BIGINT,[expiresyear] BIGINT,[cvv2] BIGINT,[bname] VARCHAR(30),[bemailaddress] VARCHAR(255),[baddress1] VARCHAR(255),[bcity] VARCHAR(255),[bstate] VARCHAR(3),[bzipcode] VARCHAR(255),[bphone] VARCHAR(40),[shipping_total_charges] VARCHAR(255),[tracking_number] TEXT,[shipping_service] VARCHAR(4),[shipping] VARCHAR(255),[currency_code] VARCHAR(255),[last_name] VARCHAR(255),[first_name] VARCHAR(255),[email] VARCHAR(255),[city] VARCHAR(255),[state] VARCHAR(255),[zip] VARCHAR(255),[address2] VARCHAR(255),[to_table] VARCHAR(1500),[to_table_id] BIGINT,[created_by] BIGINT,[edited_by] BIGINT,[session_id] VARCHAR(255),[is_paid] CHAR(1),[names] VARCHAR(255),[phone] VARCHAR(255),[address] VARCHAR(255),[url] VARCHAR(1500),[user_ip] VARCHAR(255)
);
INSERT INTO [firecms_cart_orders] ( [id], [updated_on], [created_on], [sid], [country], [order_id], [promo_code], [amount], [clientid], [customercode], [transactionid], [cardholdernumber], [expiresmonth], [expiresyear], [cvv2], [bname], [bemailaddress], [baddress1], [bcity], [bstate], [bzipcode], [bphone], [shipping_total_charges], [tracking_number], [shipping_service], [shipping], [currency_code], [last_name], [first_name], [email], [city], [state], [zip], [address2], [to_table], [to_table_id], [created_by], [edited_by], [session_id], [is_paid], [names], [phone], [address], [url], [user_ip]) VALUES (207,'2012-02-19 21:10:19','2012-02-19 21:10:19','72b77d8d645218dacdd045ffbcfada1e',NULL,'ORD12021911291325252','5PERCENT','76.00',NULL,NULL,NULL,NULL,1,2000,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',NULL,'','','','','','',NULL,NULL,NULL,12,12,'72b77d8d645218dacdd045ffbcfada1e','n',NULL,'','','{SITE_URL}shop/view:cart','127.0.0.1')
INSERT INTO [firecms_cart_orders] ( [id], [updated_on], [created_on], [sid], [country], [order_id], [promo_code], [amount], [clientid], [customercode], [transactionid], [cardholdernumber], [expiresmonth], [expiresyear], [cvv2], [bname], [bemailaddress], [baddress1], [bcity], [bstate], [bzipcode], [bphone], [shipping_total_charges], [tracking_number], [shipping_service], [shipping], [currency_code], [last_name], [first_name], [email], [city], [state], [zip], [address2], [to_table], [to_table_id], [created_by], [edited_by], [session_id], [is_paid], [names], [phone], [address], [url], [user_ip]) VALUES (208,'2012-02-19 22:03:38','2012-02-19 22:03:38','bbfdf1f2857b8fd6de4e748214177c2e',NULL,'ORD12021911291325252','5PERCENT','7220.00',NULL,NULL,NULL,NULL,1,2000,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',NULL,'','','','','','23426',NULL,NULL,NULL,12,12,'bbfdf1f2857b8fd6de4e748214177c2e','n',NULL,'','','{SITE_URL}shop/view:cart','127.0.0.1')
INSERT INTO [firecms_cart_orders] ( [id], [updated_on], [created_on], [sid], [country], [order_id], [promo_code], [amount], [clientid], [customercode], [transactionid], [cardholdernumber], [expiresmonth], [expiresyear], [cvv2], [bname], [bemailaddress], [baddress1], [bcity], [bstate], [bzipcode], [bphone], [shipping_total_charges], [tracking_number], [shipping_service], [shipping], [currency_code], [last_name], [first_name], [email], [city], [state], [zip], [address2], [to_table], [to_table_id], [created_by], [edited_by], [session_id], [is_paid], [names], [phone], [address], [url], [user_ip]) VALUES (209,'2012-02-19 22:13:27','2012-02-19 22:13:27','bbfdf1f2857b8fd6de4e748214177c2e',NULL,'ORD12021911291325252','5PERCENT','8000.00',NULL,NULL,NULL,NULL,1,2000,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',NULL,'','','','','','54335',NULL,NULL,NULL,12,12,'bbfdf1f2857b8fd6de4e748214177c2e','n',NULL,'','','{SITE_URL}shop/view:cart','127.0.0.1')
INSERT INTO [firecms_cart_orders] ( [id], [updated_on], [created_on], [sid], [country], [order_id], [promo_code], [amount], [clientid], [customercode], [transactionid], [cardholdernumber], [expiresmonth], [expiresyear], [cvv2], [bname], [bemailaddress], [baddress1], [bcity], [bstate], [bzipcode], [bphone], [shipping_total_charges], [tracking_number], [shipping_service], [shipping], [currency_code], [last_name], [first_name], [email], [city], [state], [zip], [address2], [to_table], [to_table_id], [created_by], [edited_by], [session_id], [is_paid], [names], [phone], [address], [url], [user_ip]) VALUES (210,'2012-02-19 22:29:44','2012-02-19 22:29:44','bbfdf1f2857b8fd6de4e748214177c2e',NULL,'ORD12021911291325252','5PERCENT','80.00',NULL,NULL,NULL,NULL,1,2000,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'14.70',NULL,'56756756','756756','aasdas@abv.bg','56756756','5675675','67566',NULL,NULL,NULL,12,12,'bbfdf1f2857b8fd6de4e748214177c2e','n',NULL,'5675','7567567567','{SITE_URL}shop/view:cart','127.0.0.1')
CREATE TABLE [firecms_cart_orders_shipping_cost] ([id] BIGINT,[updated_on] DATETIME,[created_on] DATETIME,[is_active] CHAR(1),[ship_to_continent] TEXT,[shiping_cost_per_item] TEXT
);
CREATE TABLE [firecms_cart_promo_codes] ([id] BIGINT,[updated_on] DATETIME,[created_on] DATETIME,[promo_code] TEXT,[amount_modifier] TEXT,[amount_modifier_type] TEXT,[description] TEXT,[valid_from] DATETIME,[valid_to] DATETIME,[auto_apply_to_all] CHAR(1)
);
CREATE TABLE [firecms_comments] ([id] BIGINT,[to_table] VARCHAR(1500),[to_table_id] BIGINT,[updated_on] DATETIME,[created_on] DATETIME,[comment_name] TEXT,[comment_body] TEXT,[comment_email] TEXT,[comment_website] TEXT,[is_moderated] CHAR(1),[for_newsletter] CHAR(1),[created_by] BIGINT,[edited_by] BIGINT,[session_id] VARCHAR(255)
);
INSERT INTO [firecms_comments] ( [id], [to_table], [to_table_id], [updated_on], [created_on], [comment_name], [comment_body], [comment_email], [comment_website], [is_moderated], [for_newsletter], [created_by], [edited_by], [session_id]) VALUES (265,'table_users_statuses',103,'2010-12-23 11:40:55','2010-12-23 11:40:55',NULL,'Feelin Gogd right now...',NULL,NULL,'n','n',1761,1761,'a1c03a53b131193827956a0fb19d0007')
INSERT INTO [firecms_comments] ( [id], [to_table], [to_table_id], [updated_on], [created_on], [comment_name], [comment_body], [comment_email], [comment_website], [is_moderated], [for_newsletter], [created_by], [edited_by], [session_id]) VALUES (242,'table_users_statuses',88,'2010-12-21 11:05:12','2010-12-21 11:05:12',NULL,'I love this feature....good job pete...  :)',NULL,NULL,'n','n',1726,1726,'bc00cec344d11450d1f44f4cc1ee66a8')
INSERT INTO [firecms_comments] ( [id], [to_table], [to_table_id], [updated_on], [created_on], [comment_name], [comment_body], [comment_email], [comment_website], [is_moderated], [for_newsletter], [created_by], [edited_by], [session_id]) VALUES (243,'table_users_statuses',89,'2010-12-21 11:05:37','2010-12-21 11:05:37',NULL,'I love this too...',NULL,NULL,'n','n',1726,1726,'bc00cec344d11450d1f44f4cc1ee66a8')
INSERT INTO [firecms_comments] ( [id], [to_table], [to_table_id], [updated_on], [created_on], [comment_name], [comment_body], [comment_email], [comment_website], [is_moderated], [for_newsletter], [created_by], [edited_by], [session_id]) VALUES (284,'table_users_statuses',106,'2010-12-28 04:08:03','2010-12-28 04:08:03',NULL,'cool video',NULL,NULL,'n','n',2,2,'06a32ed0d85039717abcb6b4bdd28afe')
INSERT INTO [firecms_comments] ( [id], [to_table], [to_table_id], [updated_on], [created_on], [comment_name], [comment_body], [comment_email], [comment_website], [is_moderated], [for_newsletter], [created_by], [edited_by], [session_id]) VALUES (306,'table_users_statuses',155,'2011-01-01 01:31:25','2011-01-01 01:31:25',NULL,'You probably have to go invite them back again.',NULL,NULL,'n','n',1284,1284,'262e830a0590fce5cc5eab96888720d6')
INSERT INTO [firecms_comments] ( [id], [to_table], [to_table_id], [updated_on], [created_on], [comment_name], [comment_body], [comment_email], [comment_website], [is_moderated], [for_newsletter], [created_by], [edited_by], [session_id]) VALUES (309,'table_users_statuses',156,'2011-01-01 02:06:50','2011-01-01 02:06:50',NULL,'Oh, hey!..how r u ?',NULL,NULL,'n','n',1284,1284,'e839a254eabda1ecb2e862300b280849')
INSERT INTO [firecms_comments] ( [id], [to_table], [to_table_id], [updated_on], [created_on], [comment_name], [comment_body], [comment_email], [comment_website], [is_moderated], [for_newsletter], [created_by], [edited_by], [session_id]) VALUES (310,'table_users_statuses',139,'2011-01-01 02:57:32','2011-01-01 02:57:32',NULL,'yeah, happy new year everyone!!!!',NULL,NULL,'n','n',1819,1819,'876d9d93d9d1ca627d50c2e4ebbed51c')
INSERT INTO [firecms_comments] ( [id], [to_table], [to_table_id], [updated_on], [created_on], [comment_name], [comment_body], [comment_email], [comment_website], [is_moderated], [for_newsletter], [created_by], [edited_by], [session_id]) VALUES (311,'table_users_statuses',139,'2011-01-01 03:03:41','2011-01-01 03:03:41',NULL,'Happy new year to u too',NULL,NULL,'n','n',1284,1284,'f9ef12cd8fddce6111fcbbb387e038f4')
INSERT INTO [firecms_comments] ( [id], [to_table], [to_table_id], [updated_on], [created_on], [comment_name], [comment_body], [comment_email], [comment_website], [is_moderated], [for_newsletter], [created_by], [edited_by], [session_id]) VALUES (315,'table_users_statuses',51,'2011-01-01 03:28:43','2011-01-01 03:28:43',NULL,'hey everyone ?',NULL,NULL,'n','n',1732,1732,'0b60636d0ac591d93039dfb0ec9a25af')
INSERT INTO [firecms_comments] ( [id], [to_table], [to_table_id], [updated_on], [created_on], [comment_name], [comment_body], [comment_email], [comment_website], [is_moderated], [for_newsletter], [created_by], [edited_by], [session_id]) VALUES (317,'table_votes',276,'2011-01-01 03:42:02','2011-01-01 03:42:02',NULL,'I LOVE IT

',NULL,NULL,'n','n',580,580,'913c0ba61a13680ed3f90f77ddcb3670')
INSERT INTO [firecms_comments] ( [id], [to_table], [to_table_id], [updated_on], [created_on], [comment_name], [comment_body], [comment_email], [comment_website], [is_moderated], [for_newsletter], [created_by], [edited_by], [session_id]) VALUES (318,'table_users_statuses',155,'2011-01-01 07:28:03','2011-01-01 07:28:03',NULL,'allie oh my gosh i remember u dont u remember me princessb oh and my brother bishopsonic dont u remember',NULL,NULL,'n','n',1223,1223,'60eda43844291ff1d6e08a9d659ec6d9')
INSERT INTO [firecms_comments] ( [id], [to_table], [to_table_id], [updated_on], [created_on], [comment_name], [comment_body], [comment_email], [comment_website], [is_moderated], [for_newsletter], [created_by], [edited_by], [session_id]) VALUES (319,'table_users_statuses',154,'2011-01-01 07:28:42','2011-01-01 07:28:42',NULL,'did u lose them',NULL,NULL,'n','n',1223,1223,'60eda43844291ff1d6e08a9d659ec6d9')
INSERT INTO [firecms_comments] ( [id], [to_table], [to_table_id], [updated_on], [created_on], [comment_name], [comment_body], [comment_email], [comment_website], [is_moderated], [for_newsletter], [created_by], [edited_by], [session_id]) VALUES (323,'table_users_statuses',155,'2011-01-02 11:03:29','2011-01-02 11:03:29',NULL,'hey Princess, I remember u. how have you been ?....I miss all my friends...... you should put your picture and also edit your name and put Princess',NULL,NULL,'n','n',1284,1284,'6168c31892218a3c946cc35a99756b7a')
INSERT INTO [firecms_comments] ( [id], [to_table], [to_table_id], [updated_on], [created_on], [comment_name], [comment_body], [comment_email], [comment_website], [is_moderated], [for_newsletter], [created_by], [edited_by], [session_id]) VALUES (342,'table_users_statuses',138,'2011-01-04 04:22:33','2011-01-04 04:22:33',NULL,'ikr... we sound do good togetherrr',NULL,NULL,'n','n',1275,1275,'74d0e1cae0936f2f17da1885eabfb4c7')
INSERT INTO [firecms_comments] ( [id], [to_table], [to_table_id], [updated_on], [created_on], [comment_name], [comment_body], [comment_email], [comment_website], [is_moderated], [for_newsletter], [created_by], [edited_by], [session_id]) VALUES (335,'table_users_statuses',165,'2011-01-03 12:17:07','2011-01-03 12:17:07',NULL,'We are now open....  guys!  Please tell everyone you know',NULL,NULL,'n','n',1761,1761,'3da94fcfc632fe9bc75565e143ad6cfe')
INSERT INTO [firecms_comments] ( [id], [to_table], [to_table_id], [updated_on], [created_on], [comment_name], [comment_body], [comment_email], [comment_website], [is_moderated], [for_newsletter], [created_by], [edited_by], [session_id]) VALUES (338,'table_users_statuses',155,'2011-01-03 05:39:46','2011-01-03 05:39:46',NULL,'i know but i dont have my computer i have my moms',NULL,NULL,'n','n',1223,1223,'6328c701891ce09a31d3214fcb1b7958')
INSERT INTO [firecms_comments] ( [id], [to_table], [to_table_id], [updated_on], [created_on], [comment_name], [comment_body], [comment_email], [comment_website], [is_moderated], [for_newsletter], [created_by], [edited_by], [session_id]) VALUES (340,'table_users_statuses',170,'2011-01-04 06:10:08','2011-01-04 06:10:08',NULL,'Write me something',NULL,NULL,'n','n',1770,1770,'eb169efc2b17cfa34e594e47f89e315c')
INSERT INTO [firecms_comments] ( [id], [to_table], [to_table_id], [updated_on], [created_on], [comment_name], [comment_body], [comment_email], [comment_website], [is_moderated], [for_newsletter], [created_by], [edited_by], [session_id]) VALUES (341,'table_users_statuses',138,'2011-01-04 01:26:35','2011-01-04 01:26:35',NULL,'now its taylor +alex   skide-kids has become much more better now........ but i have no friends i have to add some',NULL,NULL,'n','n',1320,1320,'db8b6fc61d2de91e6a3f7e56b1dc72ed')
INSERT INTO [firecms_comments] ( [id], [to_table], [to_table_id], [updated_on], [created_on], [comment_name], [comment_body], [comment_email], [comment_website], [is_moderated], [for_newsletter], [created_by], [edited_by], [session_id]) VALUES (343,'table_users_statuses',173,'2011-01-04 04:31:32','2011-01-04 04:31:32',NULL,'okkkkkkj oddd lol',NULL,NULL,'n','n',1320,1320,'215e82edf143d6565d39d7c5c9b3f197')
INSERT INTO [firecms_comments] ( [id], [to_table], [to_table_id], [updated_on], [created_on], [comment_name], [comment_body], [comment_email], [comment_website], [is_moderated], [for_newsletter], [created_by], [edited_by], [session_id]) VALUES (344,'table_users_statuses',179,'2011-01-05 08:55:42','2011-01-05 08:55:42',NULL,'Oh hey,...watsup?',NULL,NULL,'n','n',1284,1284,'185570d2e5ddfbf9e3c3134025b276c6')
INSERT INTO [firecms_comments] ( [id], [to_table], [to_table_id], [updated_on], [created_on], [comment_name], [comment_body], [comment_email], [comment_website], [is_moderated], [for_newsletter], [created_by], [edited_by], [session_id]) VALUES (346,'table_users_statuses',138,'2011-01-05 04:40:31','2011-01-05 04:40:31',NULL,'sureeee lol jkjkjk just kidding you do sound good together ',NULL,NULL,'n','n',1320,1320,'b81cf09856ff509379a148fea73b79e5')
INSERT INTO [firecms_comments] ( [id], [to_table], [to_table_id], [updated_on], [created_on], [comment_name], [comment_body], [comment_email], [comment_website], [is_moderated], [for_newsletter], [created_by], [edited_by], [session_id]) VALUES (351,'table_users_statuses',181,'2011-01-05 10:22:27','2011-01-05 10:22:27',NULL,'I say Amen to that.',NULL,NULL,'n','n',1284,1284,'1f74c4571f95dc0e3cc6d1a761b0b5f2')
INSERT INTO [firecms_comments] ( [id], [to_table], [to_table_id], [updated_on], [created_on], [comment_name], [comment_body], [comment_email], [comment_website], [is_moderated], [for_newsletter], [created_by], [edited_by], [session_id]) VALUES (352,'table_users_statuses',182,'2011-01-06 01:32:33','2011-01-06 01:32:33',NULL,'i like fridays better lol',NULL,NULL,'n','n',1320,1320,'67dbff767117e1b4dd80e407a16dd06f')
INSERT INTO [firecms_comments] ( [id], [to_table], [to_table_id], [updated_on], [created_on], [comment_name], [comment_body], [comment_email], [comment_website], [is_moderated], [for_newsletter], [created_by], [edited_by], [session_id]) VALUES (356,'table_users_statuses',138,'2011-01-06 04:19:10','2011-01-06 04:19:10',NULL,'He kissed me on the cheek today and i sware i almost threw up with happinessss',NULL,NULL,'n','n',1275,1275,'4a0455ecc8704cd9d5362508139bc05e')
INSERT INTO [firecms_comments] ( [id], [to_table], [to_table_id], [updated_on], [created_on], [comment_name], [comment_body], [comment_email], [comment_website], [is_moderated], [for_newsletter], [created_by], [edited_by], [session_id]) VALUES (357,'table_users_statuses',180,'2011-01-07 08:28:27','2011-01-07 08:28:27',NULL,'Mee too.',NULL,NULL,'n','n',1284,1284,'db48e1550e0b477d546a4b687b1bbfa8')
INSERT INTO [firecms_comments] ( [id], [to_table], [to_table_id], [updated_on], [created_on], [comment_name], [comment_body], [comment_email], [comment_website], [is_moderated], [for_newsletter], [created_by], [edited_by], [session_id]) VALUES (359,'table_users_statuses',190,'2011-01-09 05:32:17','2011-01-09 05:32:17',NULL,'I say, thank God everyday.',NULL,NULL,'n','n',1735,1735,'e9104d76bf37cc3010d2d23b6d1f8484')
INSERT INTO [firecms_comments] ( [id], [to_table], [to_table_id], [updated_on], [created_on], [comment_name], [comment_body], [comment_email], [comment_website], [is_moderated], [for_newsletter], [created_by], [edited_by], [session_id]) VALUES (363,'table_users_statuses',155,'2011-01-09 06:03:40','2011-01-09 06:03:40',NULL,'Oh okay.. why dont u ask her to do it for u ?',NULL,NULL,'n','n',1284,1284,'df5c8c26b2e4d5f4f2319eac5b605c7c')
INSERT INTO [firecms_comments] ( [id], [to_table], [to_table_id], [updated_on], [created_on], [comment_name], [comment_body], [comment_email], [comment_website], [is_moderated], [for_newsletter], [created_by], [edited_by], [session_id]) VALUES (368,'table_content',4799,'2012-03-17 12:04:57','2012-03-17 12:04:57',NULL,'jh',NULL,NULL,'n','n',14,14,'535f3f857845298c81f9bdc7836619a0')
CREATE TABLE [firecms_content] ([id] BIGINT,[content_type] VARCHAR(150),[content_url] VARCHAR(150),[content_filename] VARCHAR(150),[content_parent] BIGINT,[content_title] VARCHAR(150),[content_body] TEXT,[updated_on] DATETIME,[is_active] CHAR(1),[content_subtype] VARCHAR(150),[content_subtype_value] VARCHAR(150),[content_layout_file] VARCHAR(150),[is_home] CHAR(1),[content_meta_title] VARCHAR(600),[content_meta_description] TEXT,[content_meta_keywords] TEXT,[content_meta_other_code] TEXT,[created_on] DATETIME,[is_featured] CHAR(1),[original_link] TEXT,[content_description] TEXT,[created_by] BIGINT,[edited_by] BIGINT,[is_pinged] CHAR(1),[comments_enabled] CHAR(1),[content_layout_name] VARCHAR(50),[content_layout_style] VARCHAR(50),[content_filename_sync_with_editor] CHAR(1),[content_body_filename] VARCHAR(150),[visible_on_frontend] CHAR(1),[is_special] CHAR(1),[require_login] CHAR(1),[active_site_template] VARCHAR(150),[expires_on] DATETIME
);
INSERT INTO [firecms_content] ( [id], [content_type], [content_url], [content_filename], [content_parent], [content_title], [content_body], [updated_on], [is_active], [content_subtype], [content_subtype_value], [content_layout_file], [is_home], [content_meta_title], [content_meta_description], [content_meta_keywords], [content_meta_other_code], [created_on], [is_featured], [original_link], [content_description], [created_by], [edited_by], [is_pinged], [comments_enabled], [content_layout_name], [content_layout_style], [content_filename_sync_with_editor], [content_body_filename], [visible_on_frontend], [is_special], [require_login], [active_site_template], [expires_on]) VALUES (1,'page','home','',0,'Home','&lt;div class=&quot;hero-unit&quot;&gt;
&lt;h1&gt;&amp;nbsp;&lt;/h1&gt;
&lt;/div&gt;','2012-02-23 14:34:26','y','static','','home/index.php','y','Home','Home','Home','','2009-07-21 03:55:15','n','','',2,14,'y','y','home',NULL,'n','','y','n','','jobs',NULL)
INSERT INTO [firecms_content] ( [id], [content_type], [content_url], [content_filename], [content_parent], [content_title], [content_body], [updated_on], [is_active], [content_subtype], [content_subtype_value], [content_layout_file], [is_home], [content_meta_title], [content_meta_description], [content_meta_keywords], [content_meta_other_code], [created_on], [is_featured], [original_link], [content_description], [created_by], [edited_by], [is_pinged], [comments_enabled], [content_layout_name], [content_layout_style], [content_filename_sync_with_editor], [content_body_filename], [visible_on_frontend], [is_special], [require_login], [active_site_template], [expires_on]) VALUES (3477,'page','dish-network','',3482,'Dish Network','','2012-01-03 09:50:46','y','static','','dish-network/index.php','n','','','',NULL,'2012-01-03 05:26:59','n','','Dish Network',10,10,'y','y','',NULL,'n',NULL,'y','n','','digital',NULL)
INSERT INTO [firecms_content] ( [id], [content_type], [content_url], [content_filename], [content_parent], [content_title], [content_body], [updated_on], [is_active], [content_subtype], [content_subtype_value], [content_layout_file], [is_home], [content_meta_title], [content_meta_description], [content_meta_keywords], [content_meta_other_code], [created_on], [is_featured], [original_link], [content_description], [created_by], [edited_by], [is_pinged], [comments_enabled], [content_layout_name], [content_layout_style], [content_filename_sync_with_editor], [content_body_filename], [visible_on_frontend], [is_special], [require_login], [active_site_template], [expires_on]) VALUES (4823,'post','sdfsdf',NULL,3484,'sdfsdf','fhfghfghf','2012-03-28 14:10:12','y',NULL,NULL,NULL,'n',NULL,NULL,NULL,NULL,'2012-03-28 12:46:28','n',NULL,NULL,14,14,'y','y',NULL,NULL,'n',NULL,'y','n','n',NULL,NULL)
INSERT INTO [firecms_content] ( [id], [content_type], [content_url], [content_filename], [content_parent], [content_title], [content_body], [updated_on], [is_active], [content_subtype], [content_subtype_value], [content_layout_file], [is_home], [content_meta_title], [content_meta_description], [content_meta_keywords], [content_meta_other_code], [created_on], [is_featured], [original_link], [content_description], [created_by], [edited_by], [is_pinged], [comments_enabled], [content_layout_name], [content_layout_style], [content_filename_sync_with_editor], [content_body_filename], [visible_on_frontend], [is_special], [require_login], [active_site_template], [expires_on]) VALUES (4822,'post','tyuytu',NULL,3484,'tyuytu','','2012-03-28 14:10:15','y',NULL,NULL,NULL,'n',NULL,NULL,NULL,NULL,'2012-03-28 12:45:43','n',NULL,NULL,14,14,'y','y',NULL,NULL,'n',NULL,'y','n','n',NULL,NULL)
INSERT INTO [firecms_content] ( [id], [content_type], [content_url], [content_filename], [content_parent], [content_title], [content_body], [updated_on], [is_active], [content_subtype], [content_subtype_value], [content_layout_file], [is_home], [content_meta_title], [content_meta_description], [content_meta_keywords], [content_meta_other_code], [created_on], [is_featured], [original_link], [content_description], [created_by], [edited_by], [is_pinged], [comments_enabled], [content_layout_name], [content_layout_style], [content_filename_sync_with_editor], [content_body_filename], [visible_on_frontend], [is_special], [require_login], [active_site_template], [expires_on]) VALUES (4821,'post','ytuyt',NULL,3484,'ytuyt','','2012-03-28 14:10:08','y',NULL,NULL,NULL,'n',NULL,NULL,NULL,NULL,'2012-03-28 12:43:40','n',NULL,NULL,14,14,'y','y',NULL,NULL,'n',NULL,'y','n','n',NULL,NULL)
INSERT INTO [firecms_content] ( [id], [content_type], [content_url], [content_filename], [content_parent], [content_title], [content_body], [updated_on], [is_active], [content_subtype], [content_subtype_value], [content_layout_file], [is_home], [content_meta_title], [content_meta_description], [content_meta_keywords], [content_meta_other_code], [created_on], [is_featured], [original_link], [content_description], [created_by], [edited_by], [is_pinged], [comments_enabled], [content_layout_name], [content_layout_style], [content_filename_sync_with_editor], [content_body_filename], [visible_on_frontend], [is_special], [require_login], [active_site_template], [expires_on]) VALUES (4819,'post','fghfghf',NULL,3484,'aaaaaaaaaaaaa','fghfgh','2012-03-28 14:10:17','y',NULL,NULL,NULL,'n',NULL,NULL,NULL,NULL,'2012-03-28 12:38:13','n',NULL,NULL,14,14,'y','y',NULL,NULL,'n',NULL,'y','n','n',NULL,NULL)
INSERT INTO [firecms_content] ( [id], [content_type], [content_url], [content_filename], [content_parent], [content_title], [content_body], [updated_on], [is_active], [content_subtype], [content_subtype_value], [content_layout_file], [is_home], [content_meta_title], [content_meta_description], [content_meta_keywords], [content_meta_other_code], [created_on], [is_featured], [original_link], [content_description], [created_by], [edited_by], [is_pinged], [comments_enabled], [content_layout_name], [content_layout_style], [content_filename_sync_with_editor], [content_body_filename], [visible_on_frontend], [is_special], [require_login], [active_site_template], [expires_on]) VALUES (4820,'post','ertert',NULL,3484,'ertert','erte','2012-03-28 14:10:03','y',NULL,NULL,NULL,'n',NULL,NULL,NULL,NULL,'2012-03-28 12:43:11','n',NULL,NULL,14,14,'y','y',NULL,NULL,'n',NULL,'y','n','n',NULL,NULL)
INSERT INTO [firecms_content] ( [id], [content_type], [content_url], [content_filename], [content_parent], [content_title], [content_body], [updated_on], [is_active], [content_subtype], [content_subtype_value], [content_layout_file], [is_home], [content_meta_title], [content_meta_description], [content_meta_keywords], [content_meta_other_code], [created_on], [is_featured], [original_link], [content_description], [created_by], [edited_by], [is_pinged], [comments_enabled], [content_layout_name], [content_layout_style], [content_filename_sync_with_editor], [content_body_filename], [visible_on_frontend], [is_special], [require_login], [active_site_template], [expires_on]) VALUES (4818,'post','asdasda-20120328123015',NULL,3484,'asdasda','asdasda','2012-03-28 14:09:57','y',NULL,NULL,NULL,'n',NULL,NULL,NULL,NULL,'2012-03-28 12:30:15','n',NULL,NULL,14,14,'y','y',NULL,NULL,'n',NULL,'y','n','n',NULL,NULL)
INSERT INTO [firecms_content] ( [id], [content_type], [content_url], [content_filename], [content_parent], [content_title], [content_body], [updated_on], [is_active], [content_subtype], [content_subtype_value], [content_layout_file], [is_home], [content_meta_title], [content_meta_description], [content_meta_keywords], [content_meta_other_code], [created_on], [is_featured], [original_link], [content_description], [created_by], [edited_by], [is_pinged], [comments_enabled], [content_layout_name], [content_layout_style], [content_filename_sync_with_editor], [content_body_filename], [visible_on_frontend], [is_special], [require_login], [active_site_template], [expires_on]) VALUES (4817,'post','asdasda',NULL,3484,'asdasda','asdasda','2012-03-28 14:09:50','y',NULL,NULL,NULL,'n',NULL,NULL,NULL,NULL,'2012-03-28 12:28:56','n',NULL,NULL,14,14,'y','y',NULL,NULL,'n',NULL,'y','n','n',NULL,NULL)
INSERT INTO [firecms_content] ( [id], [content_type], [content_url], [content_filename], [content_parent], [content_title], [content_body], [updated_on], [is_active], [content_subtype], [content_subtype_value], [content_layout_file], [is_home], [content_meta_title], [content_meta_description], [content_meta_keywords], [content_meta_other_code], [created_on], [is_featured], [original_link], [content_description], [created_by], [edited_by], [is_pinged], [comments_enabled], [content_layout_name], [content_layout_style], [content_filename_sync_with_editor], [content_body_filename], [visible_on_frontend], [is_special], [require_login], [active_site_template], [expires_on]) VALUES (3484,'page','jobs','',0,'Jobs','','2012-03-28 12:19:12','y','dynamic','100002038','jobs/index.php','n','','','',NULL,'2012-01-17 05:07:45','n','','',12,14,'y','y','jobs',NULL,'n',NULL,'y','n','','jobs',NULL)
INSERT INTO [firecms_content] ( [id], [content_type], [content_url], [content_filename], [content_parent], [content_title], [content_body], [updated_on], [is_active], [content_subtype], [content_subtype_value], [content_layout_file], [is_home], [content_meta_title], [content_meta_description], [content_meta_keywords], [content_meta_other_code], [created_on], [is_featured], [original_link], [content_description], [created_by], [edited_by], [is_pinged], [comments_enabled], [content_layout_name], [content_layout_style], [content_filename_sync_with_editor], [content_body_filename], [visible_on_frontend], [is_special], [require_login], [active_site_template], [expires_on]) VALUES (3586,'post','winegard-chimney-mount-for-18inch-and-24inch-dishes-and-j-pipes',NULL,3484,'Winegard, Chimney Mount for 18inch and 24inch Dishes, and J Pipes','&lt;p&gt;&amp;bull; Chimney Mount designed for 46/60 cm&lt;br /&gt;
&amp;nbsp; (18&amp;rdquo;-24&amp;rdquo;) satellite antennas&lt;br /&gt;
&amp;bull; Mounts around chimney to provide&lt;br /&gt;
&amp;nbsp; secure mounting for satellite antennas to&lt;br /&gt;
&amp;nbsp; any size chimney.&lt;/p&gt;','2012-02-19 19:12:02','y',NULL,NULL,NULL,'n',NULL,NULL,NULL,NULL,'2012-02-15 17:21:39','n',NULL,NULL,12,12,'y','y',NULL,NULL,'n',NULL,'y','n','n',NULL,NULL)
INSERT INTO [firecms_content] ( [id], [content_type], [content_url], [content_filename], [content_parent], [content_title], [content_body], [updated_on], [is_active], [content_subtype], [content_subtype_value], [content_layout_file], [is_home], [content_meta_title], [content_meta_description], [content_meta_keywords], [content_meta_other_code], [created_on], [is_featured], [original_link], [content_description], [created_by], [edited_by], [is_pinged], [comments_enabled], [content_layout_name], [content_layout_style], [content_filename_sync_with_editor], [content_body_filename], [visible_on_frontend], [is_special], [require_login], [active_site_template], [expires_on]) VALUES (3587,'post','adaptor-pv-90-degree-f-sold-as-each',NULL,3484,'Adaptor, PV 90 degree F (SOLD AS EACH)','&lt;p&gt;&amp;bull; 90&amp;deg; F-Adapters Make 4-Way Multiswitch Installs in&lt;br /&gt;
&amp;nbsp; All-Weather Enclosures Easy&lt;br /&gt;
&amp;bull; Connector screws on to barrel portion of F-adapter&lt;br /&gt;
&amp;bull; Use in tight fitting spots&lt;/p&gt;','2012-02-19 19:12:03','y',NULL,NULL,NULL,'n',NULL,NULL,NULL,NULL,'2012-02-15 17:21:40','n',NULL,NULL,12,12,'y','y',NULL,NULL,'n',NULL,'y','n','n',NULL,NULL)
INSERT INTO [firecms_content] ( [id], [content_type], [content_url], [content_filename], [content_parent], [content_title], [content_body], [updated_on], [is_active], [content_subtype], [content_subtype_value], [content_layout_file], [is_home], [content_meta_title], [content_meta_description], [content_meta_keywords], [content_meta_other_code], [created_on], [is_featured], [original_link], [content_description], [created_by], [edited_by], [is_pinged], [comments_enabled], [content_layout_name], [content_layout_style], [content_filename_sync_with_editor], [content_body_filename], [visible_on_frontend], [is_special], [require_login], [active_site_template], [expires_on]) VALUES (3588,'post','coax-seal-1-2x3-32x60',NULL,3484,'Coax-Seal 1-2X3-32x60','&lt;p&gt;&amp;bull; Pliable Sealant Keeps Moisture Out of Sensitive Electrical Fittings on Systems&lt;br /&gt;
&amp;bull; Each Roll is 1/2&amp;rdquo; x 60&amp;rdquo;&lt;/p&gt;','2012-02-19 19:12:04','y',NULL,NULL,NULL,'n',NULL,NULL,NULL,NULL,'2012-02-15 17:22:51','n',NULL,NULL,12,12,'y','y',NULL,NULL,'n',NULL,'y','n','n',NULL,NULL)
INSERT INTO [firecms_content] ( [id], [content_type], [content_url], [content_filename], [content_parent], [content_title], [content_body], [updated_on], [is_active], [content_subtype], [content_subtype_value], [content_layout_file], [is_home], [content_meta_title], [content_meta_description], [content_meta_keywords], [content_meta_other_code], [created_on], [is_featured], [original_link], [content_description], [created_by], [edited_by], [is_pinged], [comments_enabled], [content_layout_name], [content_layout_style], [content_filename_sync_with_editor], [content_body_filename], [visible_on_frontend], [is_special], [require_login], [active_site_template], [expires_on]) VALUES (3589,'post','screw-concrete-1-4-x-1-3-4-blue-coating-box-of-100',NULL,3484,'Screw, Concrete 1-4 x 1 3-4 - Blue Coating - Box of 100','&lt;p&gt;&amp;bull; Used these screws to secure different materials or products&lt;br /&gt;
&amp;nbsp; to masonry surfaces such as brick or concrete.&lt;br /&gt;
&amp;bull; 1/4 x 1 3/4&lt;/p&gt;','2012-02-19 19:12:05','y',NULL,NULL,NULL,'n',NULL,NULL,NULL,NULL,'2012-02-15 17:22:53','n',NULL,NULL,12,12,'y','y',NULL,NULL,'n',NULL,'y','n','n',NULL,NULL)
INSERT INTO [firecms_content] ( [id], [content_type], [content_url], [content_filename], [content_parent], [content_title], [content_body], [updated_on], [is_active], [content_subtype], [content_subtype_value], [content_layout_file], [is_home], [content_meta_title], [content_meta_description], [content_meta_keywords], [content_meta_other_code], [created_on], [is_featured], [original_link], [content_description], [created_by], [edited_by], [is_pinged], [comments_enabled], [content_layout_name], [content_layout_style], [content_filename_sync_with_editor], [content_body_filename], [visible_on_frontend], [is_special], [require_login], [active_site_template], [expires_on]) VALUES (3590,'post','cable-tie-act-11inch-black-bag-of-100',NULL,3484,'Cable Tie, ACT - 11inch Black (Bag of 100)','&lt;p&gt;&lt;span style=&quot;line-height: 115%; font-family: Calibri; color: black; font-size: 11pt; mso-bidi-font-family: Calibri; mso-fareast-font-family: ''Times New Roman''; mso-ansi-language: EN-US; mso-fareast-language: EN-US; mso-bidi-language: AR-SA&quot;&gt;This 11&amp;rdquo; black nylon tie has an angled tail providing quicker insertion alignment and a tail finger grip allows easier handling and tightening.&lt;/span&gt;&lt;/p&gt;
&lt;p&gt;&amp;bull; 11&amp;quot; Black ties.&lt;br /&gt;
&amp;bull; Low insertion &amp;amp; pull-through force&lt;br /&gt;
&amp;bull; High tensile strength is achieved&lt;br /&gt;
&amp;nbsp; from one piece, advanced pawl and teeth design&lt;br /&gt;
&amp;bull; Manufactured from filtered nylon reducing particle contamination&lt;br /&gt;
&amp;bull; Angled tail provides quicker initial insertion alignment &amp;amp; tail finger&lt;br /&gt;
&amp;nbsp; grips allow easier handling &amp;amp; tightening&lt;br /&gt;
&amp;bull; Rounded edges provides added safety &amp;amp; eliminates insulation damage&lt;br /&gt;
&amp;bull; They also have a formulated molding process for consistency&lt;br /&gt;
&amp;bull; For Outdoor Use&lt;/p&gt;','2012-02-19 19:12:05','y',NULL,NULL,NULL,'n',NULL,NULL,NULL,NULL,'2012-02-15 17:22:55','n',NULL,NULL,12,12,'y','y',NULL,NULL,'n',NULL,'y','n','n',NULL,NULL)
INSERT INTO [firecms_content] ( [id], [content_type], [content_url], [content_filename], [content_parent], [content_title], [content_body], [updated_on], [is_active], [content_subtype], [content_subtype_value], [content_layout_file], [is_home], [content_meta_title], [content_meta_description], [content_meta_keywords], [content_meta_other_code], [created_on], [is_featured], [original_link], [content_description], [created_by], [edited_by], [is_pinged], [comments_enabled], [content_layout_name], [content_layout_style], [content_filename_sync_with_editor], [content_body_filename], [visible_on_frontend], [is_special], [require_login], [active_site_template], [expires_on]) VALUES (3591,'post','cable-tie-act-11inch-white-bag-of-100',NULL,3484,'Cable Tie, ACT - 11inch White (Bag of 100)','&lt;p&gt;&amp;bull; 11&amp;quot; White ties.&lt;br /&gt;
&amp;bull; Low insertion &amp;amp; pull-through force&lt;br /&gt;
&amp;bull; High tensile strength is achieved&lt;br /&gt;
&amp;nbsp; from one piece, advanced pawl and teeth design&lt;br /&gt;
&amp;bull; Manufactured from filtered nylon reducing particle contamination&lt;br /&gt;
&amp;bull; Angled tail provides quicker initial insertion alignment &amp;amp; tail finger&lt;br /&gt;
&amp;nbsp; grips allow easier handling &amp;amp; tightening&lt;br /&gt;
&amp;bull; Rounded edges provides added safety &amp;amp; eliminates insulation damage&lt;br /&gt;
&amp;bull; They also have a formulated molding process for consistency&lt;br /&gt;
&amp;bull; For Outdoor Use&lt;/p&gt;','2012-02-19 19:12:06','y',NULL,NULL,NULL,'n',NULL,NULL,NULL,NULL,'2012-02-15 17:22:57','n',NULL,NULL,12,12,'y','y',NULL,NULL,'n',NULL,'y','n','n',NULL,NULL)
INSERT INTO [firecms_content] ( [id], [content_type], [content_url], [content_filename], [content_parent], [content_title], [content_body], [updated_on], [is_active], [content_subtype], [content_subtype_value], [content_layout_file], [is_home], [content_meta_title], [content_meta_description], [content_meta_keywords], [content_meta_other_code], [created_on], [is_featured], [original_link], [content_description], [created_by], [edited_by], [is_pinged], [comments_enabled], [content_layout_name], [content_layout_style], [content_filename_sync_with_editor], [content_body_filename], [visible_on_frontend], [is_special], [require_login], [active_site_template], [expires_on]) VALUES (3592,'post','cable-tie-act-14inch-black-bag-of-100',NULL,3484,'Cable Tie, ACT - 14inch Black (Bag of 100)','&lt;p&gt;These are the really GOOD cable ties, the best we have to offer. Solid latch-pawls that don''t break or let go when under pressure, resistant to cold temperature fracturing, great cable ties.&lt;/p&gt;
&lt;p&gt;We have tested a lot of cable ties made by many different manufacturers and these are the ones that came through with our stamp of approval.&lt;/p&gt;
&lt;ul&gt;
    &lt;li&gt;14&amp;quot; Black ties.&lt;/li&gt;
    &lt;li&gt;Low insertion &amp;amp; pull-through force&lt;/li&gt;
    &lt;li&gt;High tensile strength is achieved from one piece, advanced pawl and teeth design&lt;/li&gt;
    &lt;li&gt;Manufactured from filtered nylon reducing particle contamination&lt;/li&gt;
    &lt;li&gt;Angled tail provides quicker initial insertion alignment &amp;amp; tail finger grips allow easier handling &amp;amp; tightening&lt;/li&gt;
    &lt;li&gt;Rounded edges provides added safety &amp;amp; eliminates insulation damage&lt;/li&gt;
    &lt;li&gt;They also have a formulated molding process for consistency&lt;/li&gt;
    &lt;li&gt;For Indoor and Outdoor Use&lt;/li&gt;
&lt;/ul&gt;
&lt;p&gt;&amp;nbsp;&lt;/p&gt;','2012-02-19 19:12:07','y',NULL,NULL,NULL,'n',NULL,NULL,NULL,NULL,'2012-02-15 17:22:59','n',NULL,NULL,12,12,'y','y',NULL,NULL,'n',NULL,'y','n','n',NULL,NULL)
INSERT INTO [firecms_content] ( [id], [content_type], [content_url], [content_filename], [content_parent], [content_title], [content_body], [updated_on], [is_active], [content_subtype], [content_subtype_value], [content_layout_file], [is_home], [content_meta_title], [content_meta_description], [content_meta_keywords], [content_meta_other_code], [created_on], [is_featured], [original_link], [content_description], [created_by], [edited_by], [is_pinged], [comments_enabled], [content_layout_name], [content_layout_style], [content_filename_sync_with_editor], [content_body_filename], [visible_on_frontend], [is_special], [require_login], [active_site_template], [expires_on]) VALUES (3593,'post','cable-tie-act-14inch-white-bag-of-100',NULL,3484,'Cable Tie, ACT - 14inch White (Bag of 100)','

&lt;p style=&quot;MARGIN: 0in 0in 10pt&quot; class=MsoNormal&gt;&lt;span style=&quot;COLOR: black; mso-bidi-font-family: Calibri&quot;&gt;&lt;font face=Calibri&gt;This 14” white nylon tie has an angled tail providing quicker insertion alignment and a tail finger grip allows easier handling and tightening. They are the best cable ties we have to offer! The solid latch-pawls don’t break or let go under pressure and they are resistant to cold-temperature fracturing.&lt;span style=&quot;mso-spacerun: yes&quot;&gt;&amp;nbsp; &lt;/span&gt;100 cable ties per bag.&lt;span style=&quot;mso-spacerun: yes&quot;&gt;&amp;nbsp; &lt;/span&gt;DIRECTV approved.&lt;?xml:namespace prefix = o ns = &quot;urn:schemas-microsoft-com:office:office&quot; /&gt;&lt;o:p&gt;&lt;/o:p&gt;&lt;/font&gt;&lt;/span&gt;&lt;/p&gt;
&lt;ul type=disc&gt;
&lt;ul type=circle&gt;
&lt;li style=&quot;LINE-HEIGHT: normal; MARGIN: 0in 0in 10pt; COLOR: black; mso-margin-top-alt: auto; mso-margin-bottom-alt: auto; mso-list: l0 level2 lfo1; tab-stops: list 1.0in&quot; class=MsoNormal&gt;&lt;span style=&quot;mso-bidi-font-family: Calibri&quot;&gt;&lt;font face=Calibri&gt;14” black ties.&lt;o:p&gt;&lt;/o:p&gt;&lt;/font&gt;&lt;/span&gt; 
&lt;li style=&quot;LINE-HEIGHT: normal; MARGIN: 0in 0in 10pt; COLOR: black; mso-margin-top-alt: auto; mso-margin-bottom-alt: auto; mso-list: l0 level2 lfo1; tab-stops: list 1.0in&quot; class=MsoNormal&gt;&lt;span style=&quot;mso-bidi-font-family: Calibri&quot;&gt;&lt;font face=Calibri&gt;Low insertion &amp;amp; pull-through force.&lt;o:p&gt;&lt;/o:p&gt;&lt;/font&gt;&lt;/span&gt; 
&lt;li style=&quot;LINE-HEIGHT: normal; MARGIN: 0in 0in 10pt; COLOR: black; mso-margin-top-alt: auto; mso-margin-bottom-alt: auto; mso-list: l0 level2 lfo1; tab-stops: list 1.0in&quot; class=MsoNormal&gt;&lt;span style=&quot;mso-bidi-font-family: Calibri&quot;&gt;&lt;font face=Calibri&gt;High tensile strength is achieved from one piece, advanced pawl and teeth design.&lt;o:p&gt;&lt;/o:p&gt;&lt;/font&gt;&lt;/span&gt; 
&lt;li style=&quot;LINE-HEIGHT: normal; MARGIN: 0in 0in 10pt; COLOR: black; mso-margin-top-alt: auto; mso-margin-bottom-alt: auto; mso-list: l0 level2 lfo1; tab-stops: list 1.0in&quot; class=MsoNormal&gt;&lt;span style=&quot;mso-bidi-font-family: Calibri&quot;&gt;&lt;font face=Calibri&gt;Manufactured from filtered nylon reducing particle contamination.&lt;o:p&gt;&lt;/o:p&gt;&lt;/font&gt;&lt;/span&gt; 
&lt;li style=&quot;LINE-HEIGHT: normal; MARGIN: 0in 0in 10pt; COLOR: black; mso-margin-top-alt: auto; mso-margin-bottom-alt: auto; mso-list: l0 level2 lfo1; tab-stops: list 1.0in&quot; class=MsoNormal&gt;&lt;span style=&quot;mso-bidi-font-family: Calibri&quot;&gt;&lt;font face=Calibri&gt;Angled tail provides quicker initial insertion alignment &amp;amp; tail finger grips allow easier handling &amp;amp; tightening.&lt;o:p&gt;&lt;/o:p&gt;&lt;/font&gt;&lt;/span&gt; 
&lt;li style=&quot;LINE-HEIGHT: normal; MARGIN: 0in 0in 10pt; COLOR: black; mso-margin-top-alt: auto; mso-margin-bottom-alt: auto; mso-list: l0 level2 lfo1; tab-stops: list 1.0in&quot; class=MsoNormal&gt;&lt;span style=&quot;mso-bidi-font-family: Calibri&quot;&gt;&lt;font face=Calibri&gt;The rounded edges provide added safety and eliminates insulation damage.&lt;o:p&gt;&lt;/o:p&gt;&lt;/font&gt;&lt;/span&gt; 
&lt;li style=&quot;LINE-HEIGHT: normal; MARGIN: 0in 0in 10pt; COLOR: black; mso-margin-top-alt: auto; mso-margin-bottom-alt: auto; mso-list: l0 level2 lfo1; tab-stops: list 1.0in&quot; class=MsoNormal&gt;&lt;span style=&quot;mso-bidi-font-family: Calibri&quot;&gt;&lt;font face=Calibri&gt;They also have a formulated molding process for consistency.&lt;o:p&gt;&lt;/o:p&gt;&lt;/font&gt;&lt;/span&gt; 
&lt;li style=&quot;LINE-HEIGHT: normal; MARGIN: 0in 0in 10pt; COLOR: black; mso-margin-top-alt: auto; mso-margin-bottom-alt: auto; mso-list: l0 level2 lfo1; tab-stops: list 1.0in&quot; class=MsoNormal&gt;&lt;span style=&quot;mso-bidi-font-family: Calibri&quot;&gt;&lt;font face=Calibri&gt;For indoor and outdoor use.&lt;o:p&gt;&lt;/o:p&gt;&lt;/font&gt;&lt;/span&gt;&lt;/li&gt;&lt;/ul&gt;&lt;/ul&gt;','2012-02-19 19:12:08','y',NULL,NULL,NULL,'n',NULL,NULL,NULL,NULL,'2012-02-15 17:23:01','n',NULL,NULL,12,12,'y','y',NULL,NULL,'n',NULL,'y','n','n',NULL,NULL)
INSERT INTO [firecms_content] ( [id], [content_type], [content_url], [content_filename], [content_parent], [content_title], [content_body], [updated_on], [is_active], [content_subtype], [content_subtype_value], [content_layout_file], [is_home], [content_meta_title], [content_meta_description], [content_meta_keywords], [content_meta_other_code], [created_on], [is_featured], [original_link], [content_description], [created_by], [edited_by], [is_pinged], [comments_enabled], [content_layout_name], [content_layout_style], [content_filename_sync_with_editor], [content_body_filename], [visible_on_frontend], [is_special], [require_login], [active_site_template], [expires_on]) VALUES (3594,'post','cable-tie-act-7inch-black-bag-of-100',NULL,3484,'Cable Tie, ACT - 7inch Black (Bag of 100)','&lt;p&gt;&amp;nbsp;&lt;/p&gt;
&lt;div style=&quot;margin: 0in 0in 10pt&quot;&gt;&lt;span style=&quot;color: black&quot;&gt;This 7&amp;rdquo; black nylon tie has an angled tail providing quicker insertion alignment and a tail finger grip allows easier handling and tightening. They are the best cable ties we have to offer!&amp;nbsp;The solid latch-pawls don&amp;rsquo;t break or let go under pressure and they are resistant to cold-temperature fracturing. 100 cable ties per bag.&lt;/span&gt;&lt;/div&gt;
&lt;ul type=&quot;disc&quot;&gt;
    &lt;ul type=&quot;circle&quot;&gt;
        &lt;li style=&quot;line-height: normal; margin: 0in 0in 10pt; color: black&quot;&gt;7&amp;rdquo; black ties.&lt;/li&gt;
        &lt;li style=&quot;line-height: normal; margin: 0in 0in 10pt; color: black&quot;&gt;Low insertion &amp;amp; pull-through force.&lt;/li&gt;
        &lt;li style=&quot;line-height: normal; margin: 0in 0in 10pt; color: black&quot;&gt;High tensile strength is achieved from one piece, advanced pawl and teeth design.&lt;/li&gt;
        &lt;li style=&quot;line-height: normal; margin: 0in 0in 10pt; color: black&quot;&gt;Manufactured from filtered nylon reducing particle contamination.&lt;/li&gt;
        &lt;li style=&quot;line-height: normal; margin: 0in 0in 10pt; color: black&quot;&gt;Angled tail provides quicker initial insertion alignment &amp;amp; tail finger grips allow easier handling &amp;amp; tightening.&lt;/li&gt;
        &lt;li style=&quot;line-height: normal; margin: 0in 0in 10pt; color: black&quot;&gt;Rounded edges provide added safety and eliminates insulation damage.&lt;/li&gt;
        &lt;li style=&quot;line-height: normal; margin: 0in 0in 10pt; color: black&quot;&gt;They also have a formulated molding process for consistency.&lt;/li&gt;
        &lt;li style=&quot;line-height: normal; margin: 0in 0in 10pt; color: black&quot;&gt;For indoor and outdoor use.&lt;/li&gt;
    &lt;/ul&gt;
&lt;/ul&gt;','2012-02-19 19:12:09','y',NULL,NULL,NULL,'n',NULL,NULL,NULL,NULL,'2012-02-15 17:23:02','n',NULL,NULL,12,12,'y','y',NULL,NULL,'n',NULL,'y','n','n',NULL,NULL)
INSERT INTO [firecms_content] ( [id], [content_type], [content_url], [content_filename], [content_parent], [content_title], [content_body], [updated_on], [is_active], [content_subtype], [content_subtype_value], [content_layout_file], [is_home], [content_meta_title], [content_meta_description], [content_meta_keywords], [content_meta_other_code], [created_on], [is_featured], [original_link], [content_description], [created_by], [edited_by], [is_pinged], [comments_enabled], [content_layout_name], [content_layout_style], [content_filename_sync_with_editor], [content_body_filename], [visible_on_frontend], [is_special], [require_login], [active_site_template], [expires_on]) VALUES (3595,'post','cable-tie-act-7inch-white-bag-of-100',NULL,3484,'Cable Tie, ACT - 7inch White (Bag of 100)','
&lt;p style=&quot;MARGIN: 0in 0in 10pt&quot; class=MsoNormal&gt;&lt;span style=&quot;COLOR: black; mso-bidi-font-family: Calibri&quot;&gt;&lt;font face=Calibri&gt;This 7” white nylon tie has an angled tail providing quicker insertion alignment and a tail finger grip allows easier handling and tightening. They are the best cable ties we have to offer! The solid latch-pawls don’t break or let go under pressure and they are resistant to cold-temperature fracturing.&lt;span style=&quot;mso-spacerun: yes&quot;&gt;&amp;nbsp; &lt;/span&gt;100 cable ties per bag.&lt;span style=&quot;mso-spacerun: yes&quot;&gt;&amp;nbsp; &lt;/span&gt;DIRECTV approved.&lt;?xml:namespace prefix = o ns = &quot;urn:schemas-microsoft-com:office:office&quot; /&gt;&lt;o:p&gt;&lt;/o:p&gt;&lt;/font&gt;&lt;/span&gt;&lt;/p&gt;
&lt;ul type=disc&gt;
&lt;ul type=circle&gt;
&lt;li style=&quot;LINE-HEIGHT: normal; MARGIN: 0in 0in 10pt; COLOR: black; mso-margin-top-alt: auto; mso-margin-bottom-alt: auto; mso-list: l0 level2 lfo1; tab-stops: list 1.0in&quot; class=MsoNormal&gt;&lt;span style=&quot;mso-bidi-font-family: Calibri&quot;&gt;&lt;font face=Calibri&gt;7” black ties.&lt;o:p&gt;&lt;/o:p&gt;&lt;/font&gt;&lt;/span&gt; 
&lt;li style=&quot;LINE-HEIGHT: normal; MARGIN: 0in 0in 10pt; COLOR: black; mso-margin-top-alt: auto; mso-margin-bottom-alt: auto; mso-list: l0 level2 lfo1; tab-stops: list 1.0in&quot; class=MsoNormal&gt;&lt;span style=&quot;mso-bidi-font-family: Calibri&quot;&gt;&lt;font face=Calibri&gt;Low insertion &amp;amp; pull-through force.&lt;o:p&gt;&lt;/o:p&gt;&lt;/font&gt;&lt;/span&gt; 
&lt;li style=&quot;LINE-HEIGHT: normal; MARGIN: 0in 0in 10pt; COLOR: black; mso-margin-top-alt: auto; mso-margin-bottom-alt: auto; mso-list: l0 level2 lfo1; tab-stops: list 1.0in&quot; class=MsoNormal&gt;&lt;span style=&quot;mso-bidi-font-family: Calibri&quot;&gt;&lt;font face=Calibri&gt;High tensile strength is achieved from one piece, advanced pawl and teeth design.&lt;o:p&gt;&lt;/o:p&gt;&lt;/font&gt;&lt;/span&gt; 
&lt;li style=&quot;LINE-HEIGHT: normal; MARGIN: 0in 0in 10pt; COLOR: black; mso-margin-top-alt: auto; mso-margin-bottom-alt: auto; mso-list: l0 level2 lfo1; tab-stops: list 1.0in&quot; class=MsoNormal&gt;&lt;span style=&quot;mso-bidi-font-family: Calibri&quot;&gt;&lt;font face=Calibri&gt;Manufactured from filtered nylon reducing particle contamination.&lt;o:p&gt;&lt;/o:p&gt;&lt;/font&gt;&lt;/span&gt; 
&lt;li style=&quot;LINE-HEIGHT: normal; MARGIN: 0in 0in 10pt; COLOR: black; mso-margin-top-alt: auto; mso-margin-bottom-alt: auto; mso-list: l0 level2 lfo1; tab-stops: list 1.0in&quot; class=MsoNormal&gt;&lt;span style=&quot;mso-bidi-font-family: Calibri&quot;&gt;&lt;font face=Calibri&gt;Angled tail provides quicker initial insertion alignment &amp;amp; tail finger grips allow easier handling &amp;amp; tightening.&lt;o:p&gt;&lt;/o:p&gt;&lt;/font&gt;&lt;/span&gt; 
&lt;li style=&quot;LINE-HEIGHT: normal; MARGIN: 0in 0in 10pt; COLOR: black; mso-margin-top-alt: auto; mso-margin-bottom-alt: auto; mso-list: l0 level2 lfo1; tab-stops: list 1.0in&quot; class=MsoNormal&gt;&lt;span style=&quot;mso-bidi-font-family: Calibri&quot;&gt;&lt;font face=Calibri&gt;The rounded edges provide added safety and eliminates insulation damage.&lt;o:p&gt;&lt;/o:p&gt;&lt;/font&gt;&lt;/span&gt; 
&lt;li style=&quot;LINE-HEIGHT: normal; MARGIN: 0in 0in 10pt; COLOR: black; mso-margin-top-alt: auto; mso-margin-bottom-alt: auto; mso-list: l0 level2 lfo1; tab-stops: list 1.0in&quot; class=MsoNormal&gt;&lt;span style=&quot;mso-bidi-font-family: Calibri&quot;&gt;&lt;font face=Calibri&gt;They also have a formulated molding process for consistency.&lt;o:p&gt;&lt;/o:p&gt;&lt;/font&gt;&lt;/span&gt; 
&lt;li style=&quot;LINE-HEIGHT: normal; MARGIN: 0in 0in 10pt; COLOR: black; mso-margin-top-alt: auto; mso-margin-bottom-alt: auto; mso-list: l0 level2 lfo1; tab-stops: list 1.0in&quot; class=MsoNormal&gt;&lt;span style=&quot;mso-bidi-font-family: Calibri&quot;&gt;&lt;font face=Calibri&gt;For indoor and outdoor use.&lt;o:p&gt;&lt;/o:p&gt;&lt;/font&gt;&lt;/span&gt;&lt;/li&gt;&lt;/ul&gt;&lt;/ul&gt;','2012-02-19 19:12:09','y',NULL,NULL,NULL,'n',NULL,NULL,NULL,NULL,'2012-02-15 17:23:04','n',NULL,NULL,12,12,'y','y',NULL,NULL,'n',NULL,'y','n','n',NULL,NULL)
INSERT INTO [firecms_content] ( [id], [content_type], [content_url], [content_filename], [content_parent], [content_title], [content_body], [updated_on], [is_active], [content_subtype], [content_subtype_value], [content_layout_file], [is_home], [content_meta_title], [content_meta_description], [content_meta_keywords], [content_meta_other_code], [created_on], [is_featured], [original_link], [content_description], [created_by], [edited_by], [is_pinged], [comments_enabled], [content_layout_name], [content_layout_style], [content_filename_sync_with_editor], [content_body_filename], [visible_on_frontend], [is_special], [require_login], [active_site_template], [expires_on]) VALUES (3596,'post','cable-tie-act-7inch-black-w-mount-hole-bag-of-100',NULL,3484,'Cable Tie, ACT - 7inch Black w-Mount Hole (Bag of 100)','&lt;p&gt;&amp;bull; 7&amp;quot; Cable Ties with Mounting Hole&lt;/p&gt;','2012-02-19 19:12:10','y',NULL,NULL,NULL,'n',NULL,NULL,NULL,NULL,'2012-02-15 17:23:06','n',NULL,NULL,12,12,'y','y',NULL,NULL,'n',NULL,'y','n','n',NULL,NULL)
INSERT INTO [firecms_content] ( [id], [content_type], [content_url], [content_filename], [content_parent], [content_title], [content_body], [updated_on], [is_active], [content_subtype], [content_subtype_value], [content_layout_file], [is_home], [content_meta_title], [content_meta_description], [content_meta_keywords], [content_meta_other_code], [created_on], [is_featured], [original_link], [content_description], [created_by], [edited_by], [is_pinged], [comments_enabled], [content_layout_name], [content_layout_style], [content_filename_sync_with_editor], [content_body_filename], [visible_on_frontend], [is_special], [require_login], [active_site_template], [expires_on]) VALUES (3597,'post','test-um-coax-mapper-cable-tester',NULL,3484,'Test-um coax mapper cable tester...','&lt;p&gt;&amp;nbsp;&lt;/p&gt;
&lt;div style=&quot;line-height: normal; margin: 0in 0in 0pt&quot;&gt;The Coax Mapper is a handheld tester for technicians of DBS, CATV, security system and other coax system installations. It speeds up your installation time by helping you find and identify multiple coax cables.&amp;nbsp;It works wonders, especially with three and four room installations.&amp;nbsp;Used in conjunction with the included remote terminators, the Coax Mapper displays PASS, OPEN, or SHORT with just one test button on the main unit. One button also initiates a tone signal.&amp;nbsp;It includes four color coded F connectors and BNC adapters.&lt;/div&gt;','2012-02-19 19:12:11','y',NULL,NULL,NULL,'n',NULL,NULL,NULL,NULL,'2012-02-15 17:23:07','n',NULL,NULL,12,12,'y','y',NULL,NULL,'n',NULL,'y','n','n',NULL,NULL)
INSERT INTO [firecms_content] ( [id], [content_type], [content_url], [content_filename], [content_parent], [content_title], [content_body], [updated_on], [is_active], [content_subtype], [content_subtype_value], [content_layout_file], [is_home], [content_meta_title], [content_meta_description], [content_meta_keywords], [content_meta_other_code], [created_on], [is_featured], [original_link], [content_description], [created_by], [edited_by], [is_pinged], [comments_enabled], [content_layout_name], [content_layout_style], [content_filename_sync_with_editor], [content_body_filename], [visible_on_frontend], [is_special], [require_login], [active_site_template], [expires_on]) VALUES (3598,'post','test-um-coax-mapper-ends',NULL,3484,'Test-um coax mapper ends','&lt;p&gt;&amp;bull; Test-um coax mapper ends&lt;br /&gt;
&amp;bull; These are to be use with CX200&lt;/p&gt;','2012-02-19 19:12:12','y',NULL,NULL,NULL,'n',NULL,NULL,NULL,NULL,'2012-02-15 17:23:09','n',NULL,NULL,12,12,'y','y',NULL,NULL,'n',NULL,'y','n','n',NULL,NULL)
INSERT INTO [firecms_content] ( [id], [content_type], [content_url], [content_filename], [content_parent], [content_title], [content_body], [updated_on], [is_active], [content_subtype], [content_subtype_value], [content_layout_file], [is_home], [content_meta_title], [content_meta_description], [content_meta_keywords], [content_meta_other_code], [created_on], [is_featured], [original_link], [content_description], [created_by], [edited_by], [is_pinged], [comments_enabled], [content_layout_name], [content_layout_style], [content_filename_sync_with_editor], [content_body_filename], [visible_on_frontend], [is_special], [require_login], [active_site_template], [expires_on]) VALUES (3599,'post','cable-flex-clips-dual-screw-1-2inch-depth-blk-bag-100',NULL,3484,'Cable Flex Clips, Dual, screw, 1-2inch depth, Blk, Bag-100','&lt;p&gt;&amp;bull; Superior design avoids stress on the cable &amp;amp; stress on the clip&lt;br /&gt;
&amp;bull; Integrity of signal is unimpeded &amp;amp; life of&lt;br /&gt;
&amp;nbsp; clip is virtually unmatched&lt;br /&gt;
&amp;bull; Manufactured from a pliable, durable polyethylene&lt;br /&gt;
&amp;bull; Wraps around cable without exerting&lt;br /&gt;
&amp;nbsp; pressure on coax&lt;br /&gt;
&amp;bull; Adjustable teeth&lt;br /&gt;
&amp;bull; Each clip can accommodate additional&lt;br /&gt;
&amp;nbsp; ground or messenger wire&lt;br /&gt;
&amp;bull; Screws eliminate chance of accidental damage from tools&lt;br /&gt;
&amp;bull; Designed for ground wire--penetrates&lt;br /&gt;
&amp;nbsp; the surface 1/2&amp;rdquo; &lt;br /&gt;
&amp;bull; Holds dual cable&lt;br /&gt;
&amp;bull; Black in color&lt;br /&gt;
&amp;bull; Bag of 100&lt;/p&gt;','2012-02-19 19:12:13','y',NULL,NULL,NULL,'n',NULL,NULL,NULL,NULL,'2012-02-15 17:23:11','n',NULL,NULL,12,12,'y','y',NULL,NULL,'n',NULL,'y','n','n',NULL,NULL)
INSERT INTO [firecms_content] ( [id], [content_type], [content_url], [content_filename], [content_parent], [content_title], [content_body], [updated_on], [is_active], [content_subtype], [content_subtype_value], [content_layout_file], [is_home], [content_meta_title], [content_meta_description], [content_meta_keywords], [content_meta_other_code], [created_on], [is_featured], [original_link], [content_description], [created_by], [edited_by], [is_pinged], [comments_enabled], [content_layout_name], [content_layout_style], [content_filename_sync_with_editor], [content_body_filename], [visible_on_frontend], [is_special], [require_login], [active_site_template], [expires_on]) VALUES (3600,'post','cable-beld-rg11-sngl-60percent-sc-1000-blk',NULL,3484,'Cable, Beld RG11 Sngl 60% SC -1000 Blk','&lt;p&gt;
&lt;table cellspacing=&quot;0&quot; cellpadding=&quot;0&quot; width=&quot;90%&quot; border=&quot;0&quot;&gt;
    &lt;tbody&gt;
        &lt;tr&gt;
            &lt;td class=&quot;catMainTxt&quot;&gt;Put Ups and Colors:&lt;/td&gt;
        &lt;/tr&gt;
    &lt;/tbody&gt;
&lt;/table&gt;
&lt;/p&gt;
&lt;div class=&quot;putUpsCls&quot; id=&quot;putUpsDiv&quot;&gt;
&lt;table class=&quot;tblOuterCls&quot; cellspacing=&quot;0&quot; cellpadding=&quot;0&quot;&gt;
    &lt;tbody&gt;
        &lt;tr&gt;
            &lt;td&gt;
            &lt;table class=&quot;tblInnerCls&quot; cellspacing=&quot;1&quot; cellpadding=&quot;5&quot;&gt;
                &lt;tbody&gt;
                    &lt;tr&gt;
                        &lt;td class=&quot;tblHdrCls&quot;&gt;Item #&lt;/td&gt;
                        &lt;td class=&quot;tblHdrCls&quot;&gt;Putup&lt;/td&gt;
                        &lt;td class=&quot;tblHdrCls&quot;&gt;Ship Weight&lt;/td&gt;
                        &lt;td class=&quot;tblHdrCls&quot;&gt;Color&lt;/td&gt;
                        &lt;td class=&quot;tblHdrCls&quot;&gt;Notes&lt;/td&gt;
                        &lt;td class=&quot;tblHdrCls&quot;&gt;Item Desc&lt;/td&gt;
                    &lt;/tr&gt;
                    &lt;tr&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;1523A 0101000&lt;/td&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;1,000 FT&lt;/td&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;66.000 LB&lt;/td&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;BLACK&lt;/td&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;&amp;nbsp;&lt;/td&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;#14 GIFHDLDPE SH PVC&lt;/td&gt;
                    &lt;/tr&gt;
                &lt;/tbody&gt;
            &lt;/table&gt;
            &lt;/td&gt;
        &lt;/tr&gt;
    &lt;/tbody&gt;
&lt;/table&gt;
&lt;/div&gt;
&lt;div class=&quot;catMain&quot;&gt;Physical Characteristics (Overall)&lt;/div&gt;
&lt;div class=&quot;catSub lvl1&quot;&gt;Conductor&lt;/div&gt;
&lt;div class=&quot;attribNm lvl2&quot;&gt;AWG:&lt;/div&gt;
&lt;div class=&quot;lvl3&quot;&gt;
&lt;table class=&quot;tblOuterCls&quot; cellspacing=&quot;0&quot; cellpadding=&quot;0&quot; border=&quot;0&quot;&gt;
    &lt;tbody&gt;
        &lt;tr&gt;
            &lt;td&gt;
            &lt;table class=&quot;tblInnerCls&quot; cellspacing=&quot;1&quot; cellpadding=&quot;0&quot;&gt;
                &lt;tbody&gt;
                    &lt;tr&gt;
                        &lt;td class=&quot;tblHdrCls&quot;&gt;# Coax&lt;/td&gt;
                        &lt;td class=&quot;tblHdrCls&quot;&gt;AWG&lt;/td&gt;
                        &lt;td class=&quot;tblHdrCls&quot;&gt;Stranding&lt;/td&gt;
                        &lt;td class=&quot;tblHdrCls&quot;&gt;Conductor Material&lt;/td&gt;
                        &lt;td class=&quot;tblHdrCls&quot;&gt;Dia. (in.)&lt;/td&gt;
                    &lt;/tr&gt;
                    &lt;tr&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;1&lt;/td&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;14&lt;/td&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;Solid&lt;/td&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;BCCS - Bare Copper Covered Steel&lt;/td&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;.064&lt;/td&gt;
                    &lt;/tr&gt;
                &lt;/tbody&gt;
            &lt;/table&gt;
            &lt;/td&gt;
        &lt;/tr&gt;
    &lt;/tbody&gt;
&lt;/table&gt;
&lt;/div&gt;
&lt;div class=&quot;catSub lvl1&quot;&gt;Insulation&lt;/div&gt;
&lt;div class=&quot;attribNm lvl2&quot;&gt;Insulation Material:&lt;/div&gt;
&lt;div class=&quot;lvl3&quot;&gt;
&lt;table class=&quot;tblOuterCls&quot; cellspacing=&quot;0&quot; cellpadding=&quot;0&quot; border=&quot;0&quot;&gt;
    &lt;tbody&gt;
        &lt;tr&gt;
            &lt;td&gt;
            &lt;table class=&quot;tblInnerCls&quot; cellspacing=&quot;1&quot; cellpadding=&quot;0&quot;&gt;
                &lt;tbody&gt;
                    &lt;tr&gt;
                        &lt;td class=&quot;tblHdrCls&quot;&gt;Insulation Material&lt;/td&gt;
                        &lt;td class=&quot;tblHdrCls&quot;&gt;Dia. (in.)&lt;/td&gt;
                    &lt;/tr&gt;
                    &lt;tr&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;Gas-injected FPE - Foam Polyethylene&lt;/td&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;.280&lt;/td&gt;
                    &lt;/tr&gt;
                &lt;/tbody&gt;
            &lt;/table&gt;
            &lt;/td&gt;
        &lt;/tr&gt;
    &lt;/tbody&gt;
&lt;/table&gt;
&lt;/div&gt;
&lt;div class=&quot;catSub lvl1&quot;&gt;Outer Shield&lt;/div&gt;
&lt;div class=&quot;attribNm lvl2&quot;&gt;Outer Shield Material:&lt;/div&gt;
&lt;div class=&quot;lvl3&quot;&gt;
&lt;table class=&quot;tblOuterCls&quot; cellspacing=&quot;0&quot; cellpadding=&quot;0&quot; border=&quot;0&quot;&gt;
    &lt;tbody&gt;
        &lt;tr&gt;
            &lt;td&gt;
            &lt;table class=&quot;tblInnerCls&quot; cellspacing=&quot;1&quot; cellpadding=&quot;0&quot;&gt;
                &lt;tbody&gt;
                    &lt;tr&gt;
                        &lt;td class=&quot;tblHdrCls&quot;&gt;Layer #&lt;/td&gt;
                        &lt;td class=&quot;tblHdrCls&quot;&gt;Outer Shield Trade Name&lt;/td&gt;
                        &lt;td class=&quot;tblHdrCls&quot;&gt;Type&lt;/td&gt;
                        &lt;td class=&quot;tblHdrCls&quot;&gt;Outer Shield Material&lt;/td&gt;
                        &lt;td class=&quot;tblHdrCls&quot;&gt;Coverage (%)&lt;/td&gt;
                    &lt;/tr&gt;
                    &lt;tr&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;1&lt;/td&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;Duobond&amp;reg;&lt;/td&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;Tape&lt;/td&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;Bonded Aluminum Foil-Polyester Tape-Aluminum Foil&lt;/td&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;100&lt;/td&gt;
                    &lt;/tr&gt;
                    &lt;tr&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;2&lt;/td&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;&amp;nbsp;&lt;/td&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;Braid&lt;/td&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;AL - Aluminum&lt;/td&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;60&lt;/td&gt;
                    &lt;/tr&gt;
                &lt;/tbody&gt;
            &lt;/table&gt;
            &lt;/td&gt;
        &lt;/tr&gt;
    &lt;/tbody&gt;
&lt;/table&gt;
&lt;/div&gt;
&lt;div class=&quot;catSub lvl1&quot;&gt;Outer Jacket&lt;/div&gt;
&lt;div class=&quot;attribNm lvl2&quot;&gt;Outer Jacket Material:&lt;/div&gt;
&lt;div class=&quot;lvl3&quot;&gt;
&lt;table class=&quot;tblOuterCls&quot; cellspacing=&quot;0&quot; cellpadding=&quot;0&quot; border=&quot;0&quot;&gt;
    &lt;tbody&gt;
        &lt;tr&gt;
            &lt;td&gt;
            &lt;table class=&quot;tblInnerCls&quot; cellspacing=&quot;1&quot; cellpadding=&quot;0&quot;&gt;
                &lt;tbody&gt;
                    &lt;tr&gt;
                        &lt;td class=&quot;tblHdrCls&quot;&gt;Outer Jacket Material&lt;/td&gt;
                    &lt;/tr&gt;
                    &lt;tr&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;PVC - Polyvinyl Chloride&lt;/td&gt;
                    &lt;/tr&gt;
                &lt;/tbody&gt;
            &lt;/table&gt;
            &lt;/td&gt;
        &lt;/tr&gt;
    &lt;/tbody&gt;
&lt;/table&gt;
&lt;/div&gt;
&lt;div class=&quot;catSub lvl1&quot;&gt;Overall Cabling&lt;/div&gt;
&lt;div class=&quot;attribTabCls&quot;&gt;
&lt;table width=&quot;95%&quot; border=&quot;0&quot;&gt;
    &lt;tbody&gt;
        &lt;tr&gt;
            &lt;td class=&quot;attribNm&quot; style=&quot;width: 40%&quot;&gt;Overall Nominal Diameter:&lt;/td&gt;
            &lt;td class=&quot;attribVal&quot; style=&quot;width: 60%&quot;&gt;0.400 in.&lt;/td&gt;
        &lt;/tr&gt;
    &lt;/tbody&gt;
&lt;/table&gt;
&lt;/div&gt;
&lt;div class=&quot;catMain&quot;&gt;Mechanical Characteristics (Overall)&lt;/div&gt;
&lt;div class=&quot;attribTabCls&quot;&gt;
&lt;table width=&quot;95%&quot; border=&quot;0&quot;&gt;
    &lt;tbody&gt;
        &lt;tr&gt;
            &lt;td class=&quot;attribNm&quot; style=&quot;width: 40%&quot;&gt;Operating Temperature Range:&lt;/td&gt;
            &lt;td class=&quot;attribVal&quot; style=&quot;width: 60%&quot;&gt;-40&amp;deg;C To +80&amp;deg;C&lt;/td&gt;
        &lt;/tr&gt;
    &lt;/tbody&gt;
&lt;/table&gt;
&lt;/div&gt;
&lt;div class=&quot;attribTabCls&quot;&gt;
&lt;table width=&quot;95%&quot; border=&quot;0&quot;&gt;
    &lt;tbody&gt;
        &lt;tr&gt;
            &lt;td class=&quot;attribNm&quot; style=&quot;width: 40%&quot;&gt;Non-UL Temperature Rating:&lt;/td&gt;
            &lt;td class=&quot;attribVal&quot; style=&quot;width: 60%&quot;&gt;80&amp;deg;C&lt;/td&gt;
        &lt;/tr&gt;
    &lt;/tbody&gt;
&lt;/table&gt;
&lt;/div&gt;
&lt;div class=&quot;attribTabCls&quot;&gt;
&lt;table width=&quot;95%&quot; border=&quot;0&quot;&gt;
    &lt;tbody&gt;
        &lt;tr&gt;
            &lt;td class=&quot;attribNm&quot; style=&quot;width: 40%&quot;&gt;Bulk Cable Weight:&lt;/td&gt;
            &lt;td class=&quot;attribVal&quot; style=&quot;width: 60%&quot;&gt;54 lbs/1000 ft.&lt;/td&gt;
        &lt;/tr&gt;
    &lt;/tbody&gt;
&lt;/table&gt;
&lt;/div&gt;
&lt;div class=&quot;attribTabCls&quot;&gt;
&lt;table width=&quot;95%&quot; border=&quot;0&quot;&gt;
    &lt;tbody&gt;
        &lt;tr&gt;
            &lt;td class=&quot;attribNm&quot; style=&quot;width: 40%&quot;&gt;Max. Recommended Pulling Tension:&lt;/td&gt;
            &lt;td class=&quot;attribVal&quot; style=&quot;width: 60%&quot;&gt;260 lbs.&lt;/td&gt;
        &lt;/tr&gt;
    &lt;/tbody&gt;
&lt;/table&gt;
&lt;/div&gt;
&lt;div class=&quot;attribTabCls&quot;&gt;
&lt;table width=&quot;95%&quot; border=&quot;0&quot;&gt;
    &lt;tbody&gt;
        &lt;tr&gt;
            &lt;td class=&quot;attribNm&quot; style=&quot;width: 40%&quot;&gt;Min. Bend Radius (Install)/Minor Axis:&lt;/td&gt;
            &lt;td class=&quot;attribVal&quot; style=&quot;width: 60%&quot;&gt;4.500 in.&lt;/td&gt;
        &lt;/tr&gt;
    &lt;/tbody&gt;
&lt;/table&gt;
&lt;/div&gt;
&lt;div class=&quot;catMain&quot;&gt;Applicable Specifications and Agency Compliance (Overall)&lt;/div&gt;
&lt;div class=&quot;catSub lvl1&quot;&gt;Applicable Standards &amp;amp; Environmental Programs&lt;/div&gt;
&lt;div class=&quot;attribTabCls&quot;&gt;
&lt;table width=&quot;95%&quot; border=&quot;0&quot;&gt;
    &lt;tbody&gt;
        &lt;tr&gt;
            &lt;td class=&quot;attribNm&quot; style=&quot;width: 40%&quot;&gt;NEC/(UL) Specification:&lt;/td&gt;
            &lt;td class=&quot;attribVal&quot; style=&quot;width: 60%&quot;&gt;CM, CATV&lt;/td&gt;
        &lt;/tr&gt;
    &lt;/tbody&gt;
&lt;/table&gt;
&lt;/div&gt;
&lt;div class=&quot;attribTabCls&quot;&gt;
&lt;table width=&quot;95%&quot; border=&quot;0&quot;&gt;
    &lt;tbody&gt;
        &lt;tr&gt;
            &lt;td class=&quot;attribNm&quot; style=&quot;width: 40%&quot;&gt;CEC/C(UL) Specification:&lt;/td&gt;
            &lt;td class=&quot;attribVal&quot; style=&quot;width: 60%&quot;&gt;CM&lt;/td&gt;
        &lt;/tr&gt;
    &lt;/tbody&gt;
&lt;/table&gt;
&lt;/div&gt;
&lt;div class=&quot;attribTabCls&quot;&gt;
&lt;table width=&quot;95%&quot; border=&quot;0&quot;&gt;
    &lt;tbody&gt;
        &lt;tr&gt;
            &lt;td class=&quot;attribNm&quot; style=&quot;width: 40%&quot;&gt;EU CE Mark:&lt;/td&gt;
            &lt;td class=&quot;attribVal&quot; style=&quot;width: 60%&quot;&gt;Yes&lt;/td&gt;
        &lt;/tr&gt;
    &lt;/tbody&gt;
&lt;/table&gt;
&lt;/div&gt;
&lt;div class=&quot;attribTabCls&quot;&gt;
&lt;table width=&quot;95%&quot; border=&quot;0&quot;&gt;
    &lt;tbody&gt;
        &lt;tr&gt;
            &lt;td class=&quot;attribNm&quot; style=&quot;width: 40%&quot;&gt;EU Directive 2000/53/EC (ELV):&lt;/td&gt;
            &lt;td class=&quot;attribVal&quot; style=&quot;width: 60%&quot;&gt;Yes&lt;/td&gt;
        &lt;/tr&gt;
    &lt;/tbody&gt;
&lt;/table&gt;
&lt;/div&gt;
&lt;div class=&quot;attribTabCls&quot;&gt;
&lt;table width=&quot;95%&quot; border=&quot;0&quot;&gt;
    &lt;tbody&gt;
        &lt;tr&gt;
            &lt;td class=&quot;attribNm&quot; style=&quot;width: 40%&quot;&gt;EU Directive 2002/95/EC (RoHS):&lt;/td&gt;
            &lt;td class=&quot;attribVal&quot; style=&quot;width: 60%&quot;&gt;Yes&lt;/td&gt;
        &lt;/tr&gt;
    &lt;/tbody&gt;
&lt;/table&gt;
&lt;/div&gt;
&lt;div class=&quot;attribTabCls&quot;&gt;
&lt;table width=&quot;95%&quot; border=&quot;0&quot;&gt;
    &lt;tbody&gt;
        &lt;tr&gt;
            &lt;td class=&quot;attribNm&quot; style=&quot;width: 40%&quot;&gt;EU RoHS Compliance Date (mm/dd/yyyy):&lt;/td&gt;
            &lt;td class=&quot;attribVal&quot; style=&quot;width: 60%&quot;&gt;01/01/2004&lt;/td&gt;
        &lt;/tr&gt;
    &lt;/tbody&gt;
&lt;/table&gt;
&lt;/div&gt;
&lt;div class=&quot;attribTabCls&quot;&gt;
&lt;table width=&quot;95%&quot; border=&quot;0&quot;&gt;
    &lt;tbody&gt;
        &lt;tr&gt;
            &lt;td class=&quot;attribNm&quot; style=&quot;width: 40%&quot;&gt;EU Directive 2002/96/EC (WEEE):&lt;/td&gt;
            &lt;td class=&quot;attribVal&quot; style=&quot;width: 60%&quot;&gt;Yes&lt;/td&gt;
        &lt;/tr&gt;
    &lt;/tbody&gt;
&lt;/table&gt;
&lt;/div&gt;
&lt;div class=&quot;attribTabCls&quot;&gt;
&lt;table width=&quot;95%&quot; border=&quot;0&quot;&gt;
    &lt;tbody&gt;
        &lt;tr&gt;
            &lt;td class=&quot;attribNm&quot; style=&quot;width: 40%&quot;&gt;EU Directive 2003/11/EC (BFR):&lt;/td&gt;
            &lt;td class=&quot;attribVal&quot; style=&quot;width: 60%&quot;&gt;Yes&lt;/td&gt;
        &lt;/tr&gt;
    &lt;/tbody&gt;
&lt;/table&gt;
&lt;/div&gt;
&lt;div class=&quot;attribTabCls&quot;&gt;
&lt;table width=&quot;95%&quot; border=&quot;0&quot;&gt;
    &lt;tbody&gt;
        &lt;tr&gt;
            &lt;td class=&quot;attribNm&quot; style=&quot;width: 40%&quot;&gt;CA Prop 65 (CJ for Wire &amp;amp; Cable):&lt;/td&gt;
            &lt;td class=&quot;attribVal&quot; style=&quot;width: 60%&quot;&gt;Yes&lt;/td&gt;
        &lt;/tr&gt;
    &lt;/tbody&gt;
&lt;/table&gt;
&lt;/div&gt;
&lt;div class=&quot;attribTabCls&quot;&gt;
&lt;table width=&quot;95%&quot; border=&quot;0&quot;&gt;
    &lt;tbody&gt;
        &lt;tr&gt;
            &lt;td class=&quot;attribNm&quot; style=&quot;width: 40%&quot;&gt;MII Order #39 (China RoHS):&lt;/td&gt;
            &lt;td class=&quot;attribVal&quot; style=&quot;width: 60%&quot;&gt;Yes&lt;/td&gt;
        &lt;/tr&gt;
    &lt;/tbody&gt;
&lt;/table&gt;
&lt;/div&gt;
&lt;div class=&quot;attribTabCls&quot;&gt;
&lt;table width=&quot;95%&quot; border=&quot;0&quot;&gt;
    &lt;tbody&gt;
        &lt;tr&gt;
            &lt;td class=&quot;attribNm&quot; style=&quot;width: 40%&quot;&gt;Series Type:&lt;/td&gt;
            &lt;td class=&quot;attribVal&quot; style=&quot;width: 60%&quot;&gt;Series 11&lt;/td&gt;
        &lt;/tr&gt;
    &lt;/tbody&gt;
&lt;/table&gt;
&lt;/div&gt;
&lt;div class=&quot;catSub lvl1&quot;&gt;Flame Test&lt;/div&gt;
&lt;div class=&quot;attribTabCls&quot;&gt;
&lt;table width=&quot;95%&quot; border=&quot;0&quot;&gt;
    &lt;tbody&gt;
        &lt;tr&gt;
            &lt;td class=&quot;attribNm&quot; style=&quot;width: 40%&quot;&gt;UL Flame Test:&lt;/td&gt;
            &lt;td class=&quot;attribVal&quot; style=&quot;width: 60%&quot;&gt;UL1685 UL Loading&lt;/td&gt;
        &lt;/tr&gt;
    &lt;/tbody&gt;
&lt;/table&gt;
&lt;/div&gt;
&lt;div class=&quot;catSub lvl1&quot;&gt;Plenum/Non-Plenum&lt;/div&gt;
&lt;div class=&quot;attribTabCls&quot;&gt;
&lt;table width=&quot;95%&quot; border=&quot;0&quot;&gt;
    &lt;tbody&gt;
        &lt;tr&gt;
            &lt;td class=&quot;attribNm&quot; style=&quot;width: 40%&quot;&gt;Plenum (Y/N):&lt;/td&gt;
            &lt;td class=&quot;attribVal&quot; style=&quot;width: 60%&quot;&gt;No&lt;/td&gt;
        &lt;/tr&gt;
    &lt;/tbody&gt;
&lt;/table&gt;
&lt;/div&gt;
&lt;div class=&quot;catMain&quot;&gt;Electrical Characteristics (Overall)&lt;/div&gt;
&lt;div class=&quot;attribNm lvl1&quot;&gt;Nom. Characteristic Impedance:&lt;/div&gt;
&lt;div class=&quot;lvl2&quot;&gt;
&lt;table class=&quot;tblOuterCls&quot; cellspacing=&quot;0&quot; cellpadding=&quot;0&quot; border=&quot;0&quot;&gt;
    &lt;tbody&gt;
        &lt;tr&gt;
            &lt;td&gt;
            &lt;table class=&quot;tblInnerCls&quot; cellspacing=&quot;1&quot; cellpadding=&quot;0&quot;&gt;
                &lt;tbody&gt;
                    &lt;tr&gt;
                        &lt;td class=&quot;tblHdrCls&quot;&gt;Impedance (Ohm)&lt;/td&gt;
                    &lt;/tr&gt;
                    &lt;tr&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;75&lt;/td&gt;
                    &lt;/tr&gt;
                &lt;/tbody&gt;
            &lt;/table&gt;
            &lt;/td&gt;
        &lt;/tr&gt;
    &lt;/tbody&gt;
&lt;/table&gt;
&lt;/div&gt;
&lt;div class=&quot;attribNm lvl1&quot;&gt;Nom. Inductance:&lt;/div&gt;
&lt;div class=&quot;lvl2&quot;&gt;
&lt;table class=&quot;tblOuterCls&quot; cellspacing=&quot;0&quot; cellpadding=&quot;0&quot; border=&quot;0&quot;&gt;
    &lt;tbody&gt;
        &lt;tr&gt;
            &lt;td&gt;
            &lt;table class=&quot;tblInnerCls&quot; cellspacing=&quot;1&quot; cellpadding=&quot;0&quot;&gt;
                &lt;tbody&gt;
                    &lt;tr&gt;
                        &lt;td class=&quot;tblHdrCls&quot;&gt;Inductance (&amp;micro;H/ft)&lt;/td&gt;
                    &lt;/tr&gt;
                    &lt;tr&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;.097&lt;/td&gt;
                    &lt;/tr&gt;
                &lt;/tbody&gt;
            &lt;/table&gt;
            &lt;/td&gt;
        &lt;/tr&gt;
    &lt;/tbody&gt;
&lt;/table&gt;
&lt;/div&gt;
&lt;div class=&quot;attribNm lvl1&quot;&gt;Maximum Capacitance Conductor to Shield:&lt;/div&gt;
&lt;div class=&quot;lvl2&quot;&gt;
&lt;table class=&quot;tblOuterCls&quot; cellspacing=&quot;0&quot; cellpadding=&quot;0&quot; border=&quot;0&quot;&gt;
    &lt;tbody&gt;
        &lt;tr&gt;
            &lt;td&gt;
            &lt;table class=&quot;tblInnerCls&quot; cellspacing=&quot;1&quot; cellpadding=&quot;0&quot;&gt;
                &lt;tbody&gt;
                    &lt;tr&gt;
                        &lt;td class=&quot;tblHdrCls&quot;&gt;Capacitance (pF/ft)&lt;/td&gt;
                    &lt;/tr&gt;
                    &lt;tr&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;16.2&lt;/td&gt;
                    &lt;/tr&gt;
                &lt;/tbody&gt;
            &lt;/table&gt;
            &lt;/td&gt;
        &lt;/tr&gt;
    &lt;/tbody&gt;
&lt;/table&gt;
&lt;/div&gt;
&lt;div class=&quot;attribNm lvl1&quot;&gt;Nominal Velocity of Propagation:&lt;/div&gt;
&lt;div class=&quot;lvl2&quot;&gt;
&lt;table class=&quot;tblOuterCls&quot; cellspacing=&quot;0&quot; cellpadding=&quot;0&quot; border=&quot;0&quot;&gt;
    &lt;tbody&gt;
        &lt;tr&gt;
            &lt;td&gt;
            &lt;table class=&quot;tblInnerCls&quot; cellspacing=&quot;1&quot; cellpadding=&quot;0&quot;&gt;
                &lt;tbody&gt;
                    &lt;tr&gt;
                        &lt;td class=&quot;tblHdrCls&quot;&gt;VP (%)&lt;/td&gt;
                    &lt;/tr&gt;
                    &lt;tr&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;83&lt;/td&gt;
                    &lt;/tr&gt;
                &lt;/tbody&gt;
            &lt;/table&gt;
            &lt;/td&gt;
        &lt;/tr&gt;
    &lt;/tbody&gt;
&lt;/table&gt;
&lt;/div&gt;
&lt;div class=&quot;attribNm lvl1&quot;&gt;Nominal Delay:&lt;/div&gt;
&lt;div class=&quot;lvl2&quot;&gt;
&lt;table class=&quot;tblOuterCls&quot; cellspacing=&quot;0&quot; cellpadding=&quot;0&quot; border=&quot;0&quot;&gt;
    &lt;tbody&gt;
        &lt;tr&gt;
            &lt;td&gt;
            &lt;table class=&quot;tblInnerCls&quot; cellspacing=&quot;1&quot; cellpadding=&quot;0&quot;&gt;
                &lt;tbody&gt;
                    &lt;tr&gt;
                        &lt;td class=&quot;tblHdrCls&quot;&gt;Delay (ns/ft)&lt;/td&gt;
                    &lt;/tr&gt;
                    &lt;tr&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;1.2&lt;/td&gt;
                    &lt;/tr&gt;
                &lt;/tbody&gt;
            &lt;/table&gt;
            &lt;/td&gt;
        &lt;/tr&gt;
    &lt;/tbody&gt;
&lt;/table&gt;
&lt;/div&gt;
&lt;div class=&quot;attribNm lvl1&quot;&gt;Nom. Conductor DC Resistance:&lt;/div&gt;
&lt;div class=&quot;lvl2&quot;&gt;
&lt;table class=&quot;tblOuterCls&quot; cellspacing=&quot;0&quot; cellpadding=&quot;0&quot; border=&quot;0&quot;&gt;
    &lt;tbody&gt;
        &lt;tr&gt;
            &lt;td&gt;
            &lt;table class=&quot;tblInnerCls&quot; cellspacing=&quot;1&quot; cellpadding=&quot;0&quot;&gt;
                &lt;tbody&gt;
                    &lt;tr&gt;
                        &lt;td class=&quot;tblHdrCls&quot;&gt;DCR @ 20&amp;deg;C (Ohm/1000 ft)&lt;/td&gt;
                    &lt;/tr&gt;
                    &lt;tr&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;11.0&lt;/td&gt;
                    &lt;/tr&gt;
                &lt;/tbody&gt;
            &lt;/table&gt;
            &lt;/td&gt;
        &lt;/tr&gt;
    &lt;/tbody&gt;
&lt;/table&gt;
&lt;/div&gt;
&lt;div class=&quot;attribNm lvl1&quot;&gt;Nominal Outer Shield DC Resistance:&lt;/div&gt;
&lt;div class=&quot;lvl2&quot;&gt;
&lt;table class=&quot;tblOuterCls&quot; cellspacing=&quot;0&quot; cellpadding=&quot;0&quot; border=&quot;0&quot;&gt;
    &lt;tbody&gt;
        &lt;tr&gt;
            &lt;td&gt;
            &lt;table class=&quot;tblInnerCls&quot; cellspacing=&quot;1&quot; cellpadding=&quot;0&quot;&gt;
                &lt;tbody&gt;
                    &lt;tr&gt;
                        &lt;td class=&quot;tblHdrCls&quot;&gt;DCR @ 20&amp;deg;C (Ohm/1000 ft)&lt;/td&gt;
                    &lt;/tr&gt;
                    &lt;tr&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;4.1&lt;/td&gt;
                    &lt;/tr&gt;
                &lt;/tbody&gt;
            &lt;/table&gt;
            &lt;/td&gt;
        &lt;/tr&gt;
    &lt;/tbody&gt;
&lt;/table&gt;
&lt;/div&gt;
&lt;div class=&quot;attribNm lvl1&quot;&gt;Max. Attenuation:&lt;/div&gt;
&lt;div class=&quot;lvl2&quot;&gt;
&lt;table class=&quot;tblOuterCls&quot; cellspacing=&quot;0&quot; cellpadding=&quot;0&quot; border=&quot;0&quot;&gt;
    &lt;tbody&gt;
        &lt;tr&gt;
            &lt;td&gt;
            &lt;table class=&quot;tblInnerCls&quot; cellspacing=&quot;1&quot; cellpadding=&quot;0&quot;&gt;
                &lt;tbody&gt;
                    &lt;tr&gt;
                        &lt;td class=&quot;tblHdrCls&quot;&gt;Freq. (MHz)&lt;/td&gt;
                        &lt;td class=&quot;tblHdrCls&quot;&gt;Attenuation (dB/100 ft.)&lt;/td&gt;
                    &lt;/tr&gt;
                    &lt;tr&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;5&lt;/td&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;0.38&lt;/td&gt;
                    &lt;/tr&gt;
                    &lt;tr&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;55&lt;/td&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;0.97&lt;/td&gt;
                    &lt;/tr&gt;
                    &lt;tr&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;211&lt;/td&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;1.81&lt;/td&gt;
                    &lt;/tr&gt;
                    &lt;tr&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;270&lt;/td&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;2.05&lt;/td&gt;
                    &lt;/tr&gt;
                    &lt;tr&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;300&lt;/td&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;2.15&lt;/td&gt;
                    &lt;/tr&gt;
                    &lt;tr&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;350&lt;/td&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;2.32&lt;/td&gt;
                    &lt;/tr&gt;
                    &lt;tr&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;400&lt;/td&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;2.47&lt;/td&gt;
                    &lt;/tr&gt;
                    &lt;tr&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;450&lt;/td&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;2.65&lt;/td&gt;
                    &lt;/tr&gt;
                    &lt;tr&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;550&lt;/td&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;2.94&lt;/td&gt;
                    &lt;/tr&gt;
                    &lt;tr&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;750&lt;/td&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;3.50&lt;/td&gt;
                    &lt;/tr&gt;
                    &lt;tr&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;870&lt;/td&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;3.84&lt;/td&gt;
                    &lt;/tr&gt;
                    &lt;tr&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;1000&lt;/td&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;4.23&lt;/td&gt;
                    &lt;/tr&gt;
                &lt;/tbody&gt;
            &lt;/table&gt;
            &lt;/td&gt;
        &lt;/tr&gt;
    &lt;/tbody&gt;
&lt;/table&gt;
&lt;/div&gt;
&lt;div class=&quot;attribNm lvl1&quot;&gt;Max. Operating Voltage - UL:&lt;/div&gt;
&lt;div class=&quot;lvl2&quot;&gt;
&lt;table class=&quot;tblOuterCls&quot; cellspacing=&quot;0&quot; cellpadding=&quot;0&quot; border=&quot;0&quot;&gt;
    &lt;tbody&gt;
        &lt;tr&gt;
            &lt;td&gt;
            &lt;table class=&quot;tblInnerCls&quot; cellspacing=&quot;1&quot; cellpadding=&quot;0&quot;&gt;
                &lt;tbody&gt;
                    &lt;tr&gt;
                        &lt;td class=&quot;tblHdrCls&quot;&gt;Voltage&lt;/td&gt;
                    &lt;/tr&gt;
                    &lt;tr&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;300 V RMS&lt;/td&gt;
                    &lt;/tr&gt;
                &lt;/tbody&gt;
            &lt;/table&gt;
            &lt;/td&gt;
        &lt;/tr&gt;
    &lt;/tbody&gt;
&lt;/table&gt;
&lt;/div&gt;
&lt;div class=&quot;attribNm lvl1&quot;&gt;Max. Operating Voltage - Non-UL:&lt;/div&gt;
&lt;div class=&quot;lvl2&quot;&gt;
&lt;table class=&quot;tblOuterCls&quot; cellspacing=&quot;0&quot; cellpadding=&quot;0&quot; border=&quot;0&quot;&gt;
    &lt;tbody&gt;
        &lt;tr&gt;
            &lt;td&gt;
            &lt;table class=&quot;tblInnerCls&quot; cellspacing=&quot;1&quot; cellpadding=&quot;0&quot;&gt;
                &lt;tbody&gt;
                    &lt;tr&gt;
                        &lt;td class=&quot;tblHdrCls&quot;&gt;Voltage&lt;/td&gt;
                    &lt;/tr&gt;
                    &lt;tr&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;300 V RMS&lt;/td&gt;
                    &lt;/tr&gt;
                &lt;/tbody&gt;
            &lt;/table&gt;
            &lt;/td&gt;
        &lt;/tr&gt;
    &lt;/tbody&gt;
&lt;/table&gt;
&lt;/div&gt;
&lt;div class=&quot;attribNm lvl1&quot;&gt;Minimum Structural Return Loss:&lt;/div&gt;
&lt;div class=&quot;lvl2&quot;&gt;
&lt;table class=&quot;tblOuterCls&quot; cellspacing=&quot;0&quot; cellpadding=&quot;0&quot; border=&quot;0&quot;&gt;
    &lt;tbody&gt;
        &lt;tr&gt;
            &lt;td&gt;
            &lt;table class=&quot;tblInnerCls&quot; cellspacing=&quot;1&quot; cellpadding=&quot;0&quot;&gt;
                &lt;tbody&gt;
                    &lt;tr&gt;
                        &lt;td class=&quot;tblHdrCls&quot;&gt;Description&lt;/td&gt;
                        &lt;td class=&quot;tblHdrCls&quot;&gt;Freq. (MHz)&lt;/td&gt;
                        &lt;td class=&quot;tblHdrCls&quot;&gt;Start Freq. (MHz)&lt;/td&gt;
                        &lt;td class=&quot;tblHdrCls&quot;&gt;Stop Freq. (MHz)&lt;/td&gt;
                        &lt;td class=&quot;tblHdrCls&quot;&gt;Min. SRL (dB)&lt;/td&gt;
                    &lt;/tr&gt;
                    &lt;tr&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;&amp;nbsp;&lt;/td&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;&amp;nbsp;&lt;/td&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;5&lt;/td&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;1000&lt;/td&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;20&lt;/td&gt;
                    &lt;/tr&gt;
                &lt;/tbody&gt;
            &lt;/table&gt;
            &lt;/td&gt;
        &lt;/tr&gt;
    &lt;/tbody&gt;
&lt;/table&gt;
&lt;/div&gt;
&lt;div class=&quot;catMain&quot;&gt;Notes (Overall)&lt;/div&gt;
&lt;div class=&quot;attribTabCls&quot;&gt;
&lt;table width=&quot;95%&quot; border=&quot;0&quot;&gt;
    &lt;tbody&gt;
        &lt;tr&gt;
            &lt;td class=&quot;attribNm&quot; style=&quot;width: 1%&quot;&gt;Notes:&lt;/td&gt;
            &lt;td class=&quot;attribVal&quot; style=&quot;width: 99%&quot;&gt;Sweep tested 5 MHz to 1 GHz.&lt;/td&gt;
        &lt;/tr&gt;
    &lt;/tbody&gt;
&lt;/table&gt;
&lt;/div&gt;
&lt;div style=&quot;font-size: 9pt; margin-left: 2.5%; width: 94%; color: #053177; margin-right: 2.5%; font-family: Arial, Helvetica, sans-serif&quot;&gt;Revision Number: 1 &amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;Revision Date: 05-14-2007&lt;/div&gt;','2012-02-19 19:12:13','y',NULL,NULL,NULL,'n',NULL,NULL,NULL,NULL,'2012-02-15 17:23:12','n',NULL,NULL,12,12,'y','y',NULL,NULL,'n',NULL,'y','n','n',NULL,NULL)
INSERT INTO [firecms_content] ( [id], [content_type], [content_url], [content_filename], [content_parent], [content_title], [content_body], [updated_on], [is_active], [content_subtype], [content_subtype_value], [content_layout_file], [is_home], [content_meta_title], [content_meta_description], [content_meta_keywords], [content_meta_other_code], [created_on], [is_featured], [original_link], [content_description], [created_by], [edited_by], [is_pinged], [comments_enabled], [content_layout_name], [content_layout_style], [content_filename_sync_with_editor], [content_body_filename], [visible_on_frontend], [is_special], [require_login], [active_site_template], [expires_on]) VALUES (3601,'post','cable-flex-clips-dual-screw-1-2inch-depth-wht-bag-100',NULL,3484,'Cable Flex Clips, Dual, screw, 1-2inch depth, Wht, Bag-100','&lt;p&gt;&amp;bull; Superior design avoids stress on the cable &amp;amp; stress on the clip&lt;br /&gt;
&amp;bull; Integrity of signal is unimpeded &amp;amp; life of&lt;br /&gt;
&amp;nbsp; clip is virtually unmatched&lt;br /&gt;
&amp;bull; Manufactured from a pliable, durable polyethylene&lt;br /&gt;
&amp;bull; Wraps around cable without exerting&lt;br /&gt;
&amp;nbsp; pressure on coax&lt;br /&gt;
&amp;bull; Adjustable teeth&lt;br /&gt;
&amp;bull; Each clip can accommodate additional&lt;br /&gt;
&amp;nbsp; ground or messenger wire&lt;br /&gt;
&amp;bull; Screws eliminate chance of accidental damage from tools&lt;br /&gt;
&amp;bull; Designed for ground wire--penetrates&lt;br /&gt;
&amp;nbsp; the surface 1/2&amp;rdquo; &lt;br /&gt;
&amp;bull; Holds dual cable&lt;br /&gt;
&amp;bull; White in color&lt;br /&gt;
&amp;bull; Bag of 100&lt;/p&gt;','2012-02-19 19:12:14','y',NULL,NULL,NULL,'n',NULL,NULL,NULL,NULL,'2012-02-15 17:25:07','n',NULL,NULL,12,12,'y','y',NULL,NULL,'n',NULL,'y','n','n',NULL,NULL)
INSERT INTO [firecms_content] ( [id], [content_type], [content_url], [content_filename], [content_parent], [content_title], [content_body], [updated_on], [is_active], [content_subtype], [content_subtype_value], [content_layout_file], [is_home], [content_meta_title], [content_meta_description], [content_meta_keywords], [content_meta_other_code], [created_on], [is_featured], [original_link], [content_description], [created_by], [edited_by], [is_pinged], [comments_enabled], [content_layout_name], [content_layout_style], [content_filename_sync_with_editor], [content_body_filename], [visible_on_frontend], [is_special], [require_login], [active_site_template], [expires_on]) VALUES (3602,'post','mount-dbs-tennamount',NULL,3484,'Mount, DBS TennaMount','&lt;p&gt;&amp;bull; Tennamount&amp;trade; Mounts Easily on&lt;br /&gt;
&amp;bull; Guttered or Non-Guttered Houses&lt;br /&gt;
&amp;bull; Stack Up To Four&lt;br /&gt;
&amp;bull; VHF/UHF YAGI Antennas&lt;br /&gt;
&amp;bull; Dealer supplies 5 or 10 foot mast &amp;amp; Yagi style antennas&lt;br /&gt;
&amp;nbsp; Your Best Value in a VHF/UHF Antenna mounting bracket.&lt;br /&gt;
&amp;bull; Mount up to 4 Yagi Antennas on the Tennamount&amp;trade; DBS&lt;br /&gt;
&amp;nbsp; mounting system.&lt;br /&gt;
&amp;bull; Adjustable to fit any roof overhang&lt;br /&gt;
&amp;bull; Includes all mounting hardware&lt;/p&gt;','2012-02-19 19:12:15','y',NULL,NULL,NULL,'n',NULL,NULL,NULL,NULL,'2012-02-15 17:25:08','n',NULL,NULL,12,12,'y','y',NULL,NULL,'n',NULL,'y','n','n',NULL,NULL)
INSERT INTO [firecms_content] ( [id], [content_type], [content_url], [content_filename], [content_parent], [content_title], [content_body], [updated_on], [is_active], [content_subtype], [content_subtype_value], [content_layout_file], [is_home], [content_meta_title], [content_meta_description], [content_meta_keywords], [content_meta_other_code], [created_on], [is_featured], [original_link], [content_description], [created_by], [edited_by], [is_pinged], [comments_enabled], [content_layout_name], [content_layout_style], [content_filename_sync_with_editor], [content_body_filename], [visible_on_frontend], [is_special], [require_login], [active_site_template], [expires_on]) VALUES (3603,'post','compass-lensatic-liquid-filled',NULL,3484,'Compass, Lensatic-Liquid Filled','&lt;p&gt;&amp;nbsp;&lt;/p&gt;
&lt;div style=&quot;margin: 0in 0in 0pt; line-height: normal&quot;&gt;&lt;span style=&quot;color: black&quot;&gt;&amp;bull;The Liquid Filled design offers quick reads ensuring reliability and accuracy. &lt;br /&gt;
&amp;bull; Sturdy Metal Case for durable protection.&lt;br /&gt;
&amp;bull; Floating Luminous Dial provides easy to read calculations for efficient operation.&lt;br /&gt;
&amp;bull; Adjustable Luminous Marching Line offers essential features for safety and effectiveness. &amp;bull; Thumb Hold &amp;amp; Magnifying Viewer provides comfort and ease for optimal performance&lt;br /&gt;
&amp;bull; Sighting Hairline feature is ideal for locating positions on map or field for precise installation.&amp;nbsp;&lt;/span&gt;&lt;/div&gt;','2012-02-19 19:12:16','y',NULL,NULL,NULL,'n',NULL,NULL,NULL,NULL,'2012-02-15 17:25:10','n',NULL,NULL,12,12,'y','y',NULL,NULL,'n',NULL,'y','n','n',NULL,NULL)
INSERT INTO [firecms_content] ( [id], [content_type], [content_url], [content_filename], [content_parent], [content_title], [content_body], [updated_on], [is_active], [content_subtype], [content_subtype_value], [content_layout_file], [is_home], [content_meta_title], [content_meta_description], [content_meta_keywords], [content_meta_other_code], [created_on], [is_featured], [original_link], [content_description], [created_by], [edited_by], [is_pinged], [comments_enabled], [content_layout_name], [content_layout_style], [content_filename_sync_with_editor], [content_body_filename], [visible_on_frontend], [is_special], [require_login], [active_site_template], [expires_on]) VALUES (3604,'post','digiair-meter-off-air-kit',NULL,3484,'Digiair, Meter Off-Air Kit','','2012-02-19 19:12:16','y',NULL,NULL,NULL,'n',NULL,NULL,NULL,NULL,'2012-02-15 17:25:11','n',NULL,NULL,12,12,'y','y',NULL,NULL,'n',NULL,'y','n','n',NULL,NULL)
INSERT INTO [firecms_content] ( [id], [content_type], [content_url], [content_filename], [content_parent], [content_title], [content_body], [updated_on], [is_active], [content_subtype], [content_subtype_value], [content_layout_file], [is_home], [content_meta_title], [content_meta_description], [content_meta_keywords], [content_meta_other_code], [created_on], [is_featured], [original_link], [content_description], [created_by], [edited_by], [is_pinged], [comments_enabled], [content_layout_name], [content_layout_style], [content_filename_sync_with_editor], [content_body_filename], [visible_on_frontend], [is_special], [require_login], [active_site_template], [expires_on]) VALUES (3605,'post','cable-belden-rg11-sngl-plenum-60percent-braid',NULL,3484,'Cable, Belden RG11 Sngl Plenum 60% Braid','&lt;a href=&quot;/webstore/spec-sheets/1523AP.pdf&quot; target=&quot;_blank&quot;&gt;&lt;img border=&quot;0&quot; alt=&quot;&quot; src=&quot;/webstore/images/spec-button.jpg&quot; /&gt;&lt;/a&gt;','2012-02-19 19:12:17','y',NULL,NULL,NULL,'n',NULL,NULL,NULL,NULL,'2012-02-15 17:25:13','n',NULL,NULL,12,12,'y','y',NULL,NULL,'n',NULL,'y','n','n',NULL,NULL)
INSERT INTO [firecms_content] ( [id], [content_type], [content_url], [content_filename], [content_parent], [content_title], [content_body], [updated_on], [is_active], [content_subtype], [content_subtype_value], [content_layout_file], [is_home], [content_meta_title], [content_meta_description], [content_meta_keywords], [content_meta_other_code], [created_on], [is_featured], [original_link], [content_description], [created_by], [edited_by], [is_pinged], [comments_enabled], [content_layout_name], [content_layout_style], [content_filename_sync_with_editor], [content_body_filename], [visible_on_frontend], [is_special], [require_login], [active_site_template], [expires_on]) VALUES (3606,'post','adaptor-charger-dc-dg2',NULL,3484,'Adaptor-Charger  DC DG2','','2012-02-19 19:12:18','y',NULL,NULL,NULL,'n',NULL,NULL,NULL,NULL,'2012-02-15 17:25:15','n',NULL,NULL,12,12,'y','y',NULL,NULL,'n',NULL,'y','n','n',NULL,NULL)
INSERT INTO [firecms_content] ( [id], [content_type], [content_url], [content_filename], [content_parent], [content_title], [content_body], [updated_on], [is_active], [content_subtype], [content_subtype_value], [content_layout_file], [is_home], [content_meta_title], [content_meta_description], [content_meta_keywords], [content_meta_other_code], [created_on], [is_featured], [original_link], [content_description], [created_by], [edited_by], [is_pinged], [comments_enabled], [content_layout_name], [content_layout_style], [content_filename_sync_with_editor], [content_body_filename], [visible_on_frontend], [is_special], [require_login], [active_site_template], [expires_on]) VALUES (3607,'post','holland-diplexer-2-amp',NULL,3484,'Holland, Diplexer, 2 Amp','&lt;p&gt;&amp;nbsp;&lt;/p&gt;
&lt;div style=&quot;margin: 0in 0in 10pt&quot;&gt;&lt;span style=&quot;color: #333333&quot;&gt;This high performance satellite/off-air and CATV diplexer allows you to combine (or separating if used in the reverse ) off-air / CATV signals with satellite signals to a common cable. It separates TV and satellite signals prior to satellite receiver input and filters harmonics in the off-air spectrum from satellite conversion devices. Thisdiplexer is specifically designed for Dish Network&amp;rsquo;s DP44 installations, requiring continuous 2 amp power passing.&lt;/span&gt;&lt;/div&gt;
&lt;ul&gt;
    &lt;li&gt;Low Insertion Loss&lt;/li&gt;
    &lt;li&gt;High Return Loss&lt;/li&gt;
    &lt;li&gt;Increased Stop Band Isolation&lt;/li&gt;
    &lt;li&gt;Designed Specifically for DP44 Switch&lt;/li&gt;
    &lt;li&gt;Color Coded Insulators&lt;/li&gt;
    &lt;li&gt;Dish Approved&lt;/li&gt;
&lt;/ul&gt;','2012-02-19 19:12:19','y',NULL,NULL,NULL,'n',NULL,NULL,NULL,NULL,'2012-02-15 17:25:16','n',NULL,NULL,12,12,'y','y',NULL,NULL,'n',NULL,'y','n','n',NULL,NULL)
INSERT INTO [firecms_content] ( [id], [content_type], [content_url], [content_filename], [content_parent], [content_title], [content_body], [updated_on], [is_active], [content_subtype], [content_subtype_value], [content_layout_file], [is_home], [content_meta_title], [content_meta_description], [content_meta_keywords], [content_meta_other_code], [created_on], [is_featured], [original_link], [content_description], [created_by], [edited_by], [is_pinged], [comments_enabled], [content_layout_name], [content_layout_style], [content_filename_sync_with_editor], [content_body_filename], [visible_on_frontend], [is_special], [require_login], [active_site_template], [expires_on]) VALUES (3608,'post','winegard-30-inch-dish-universal-feed',NULL,3484,'Winegard, 30 Inch Dish(Universal Feed)','&lt;a href=&quot;/webstore/spec-sheets/DS2076.pdf&quot; target=&quot;_blank&quot;&gt;&lt;img border=&quot;0&quot; alt=&quot;&quot; src=&quot;/webstore/images/spec-button.jpg&quot; /&gt;&lt;/a&gt;','2012-02-19 19:12:20','y',NULL,NULL,NULL,'n',NULL,NULL,NULL,NULL,'2012-02-15 17:25:18','n',NULL,NULL,12,12,'y','y',NULL,NULL,'n',NULL,'y','n','n',NULL,NULL)
INSERT INTO [firecms_content] ( [id], [content_type], [content_url], [content_filename], [content_parent], [content_title], [content_body], [updated_on], [is_active], [content_subtype], [content_subtype_value], [content_layout_file], [is_home], [content_meta_title], [content_meta_description], [content_meta_keywords], [content_meta_other_code], [created_on], [is_featured], [original_link], [content_description], [created_by], [edited_by], [is_pinged], [comments_enabled], [content_layout_name], [content_layout_style], [content_filename_sync_with_editor], [content_body_filename], [visible_on_frontend], [is_special], [require_login], [active_site_template], [expires_on]) VALUES (3609,'post','winegard-30-inch-dish-d-tube-feed',NULL,3484,'Winegard, 30 Inch Dish(D Tube Feed)','&lt;p&gt;&amp;nbsp;&lt;/p&gt;
&lt;div style=&quot;margin: 0in 0in 10pt&quot;&gt;&lt;span&gt;This 30&amp;rdquo; satellite receiving antenna dish system utilizes a D-tube feed support.&amp;nbsp;This dish is designed using the CAD-CAM process.&amp;nbsp;Galvanized steel, used in the manufacturing process, is carbon sheet steel with a zinc-iron alloy coating.&amp;nbsp;Corrosion resistant, these alloys react to scratches and other damage through galvanic action between steel and zinc &amp;ndash; the zinc protects breaks in the coating and prevents further damage after installation.&lt;/span&gt;&lt;/div&gt;','2012-02-19 19:12:21','y',NULL,NULL,NULL,'n',NULL,NULL,NULL,NULL,'2012-02-15 17:25:20','n',NULL,NULL,12,12,'y','y',NULL,NULL,'n',NULL,'y','n','n',NULL,NULL)
INSERT INTO [firecms_content] ( [id], [content_type], [content_url], [content_filename], [content_parent], [content_title], [content_body], [updated_on], [is_active], [content_subtype], [content_subtype_value], [content_layout_file], [is_home], [content_meta_title], [content_meta_description], [content_meta_keywords], [content_meta_other_code], [created_on], [is_featured], [original_link], [content_description], [created_by], [edited_by], [is_pinged], [comments_enabled], [content_layout_name], [content_layout_style], [content_filename_sync_with_editor], [content_body_filename], [visible_on_frontend], [is_special], [require_login], [active_site_template], [expires_on]) VALUES (3610,'post','winegard-j-pipe-mount-and-foot-1-5-8-od-39-inches',NULL,3484,'Winegard, J Pipe Mount and Foot(1 5-8 OD, 39 Inches)','&lt;p&gt;&amp;nbsp;&lt;/p&gt;
&lt;div style=&quot;margin: 0in 0in 10pt&quot;&gt;&lt;span style=&quot;color: #231f20&quot;&gt;This pipe mount kit is designed to fit on the&lt;/span&gt; &lt;span style=&quot;color: #231f20&quot;&gt;side of a house.&amp;nbsp;Either end of the mast can be &lt;span style=&quot;color: #231f20&quot;&gt;attached to the base, depending on how much&lt;/span&gt; &lt;span style=&quot;color: #231f20&quot;&gt;clearance is required for the antenna. Not recommended for antennas with a boom length longer than 40&amp;quot;.&amp;nbsp;Lag bolts are not included.&amp;nbsp;DIRECTV approved.&lt;/span&gt;&lt;/span&gt;&lt;/div&gt;
&lt;div style=&quot;text-indent: -0.25in; margin: 0in 0in 0pt 0.5in&quot;&gt;&lt;span&gt;&amp;middot;&lt;span style=&quot;font: 7pt ''Times New Roman''&quot;&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp; &lt;/span&gt;&lt;/span&gt;&lt;span style=&quot;color: #231f20&quot;&gt;Locate a stud in the area to be installed.&lt;/span&gt;&lt;/div&gt;
&lt;div style=&quot;text-indent: -0.25in; margin: 0in 0in 0pt 0.5in&quot;&gt;&lt;span&gt;&amp;middot;&lt;span style=&quot;font: 7pt ''Times New Roman''&quot;&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp; &lt;/span&gt;&lt;/span&gt;&lt;span style=&quot;color: #231f20&quot;&gt;Drill a small pilot hole, to help start threading the lag bolts (5/16 x 3&amp;quot; recommended).&lt;/span&gt;&lt;/div&gt;
&lt;div style=&quot;text-indent: -0.25in; margin: 0in 0in 0pt 0.5in&quot;&gt;&lt;span&gt;&amp;middot;&lt;span style=&quot;font: 7pt ''Times New Roman''&quot;&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp; &lt;/span&gt;&lt;/span&gt;&lt;span style=&quot;color: #231f20&quot;&gt;Apply silicone sealant to back of the base to seal holes.&lt;/span&gt;&lt;/div&gt;
&lt;div style=&quot;text-indent: -0.25in; margin: 0in 0in 10pt 0.5in&quot;&gt;&lt;span&gt;&amp;middot;&lt;span style=&quot;font: 7pt ''Times New Roman''&quot;&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp; &lt;/span&gt;&lt;/span&gt;&lt;span style=&quot;color: #231f20&quot;&gt;Using lag bolts tighten the base to the wall. Make sure mast is plumb.&lt;/span&gt;&lt;/div&gt;','2012-02-19 19:12:22','y',NULL,NULL,NULL,'n',NULL,NULL,NULL,NULL,'2012-02-15 17:25:22','n',NULL,NULL,12,12,'y','y',NULL,NULL,'n',NULL,'y','n','n',NULL,NULL)
INSERT INTO [firecms_content] ( [id], [content_type], [content_url], [content_filename], [content_parent], [content_title], [content_body], [updated_on], [is_active], [content_subtype], [content_subtype_value], [content_layout_file], [is_home], [content_meta_title], [content_meta_description], [content_meta_keywords], [content_meta_other_code], [created_on], [is_featured], [original_link], [content_description], [created_by], [edited_by], [is_pinged], [comments_enabled], [content_layout_name], [content_layout_style], [content_filename_sync_with_editor], [content_body_filename], [visible_on_frontend], [is_special], [require_login], [active_site_template], [expires_on]) VALUES (3611,'post','winegard-18-inch-dish-d-tube-feed',NULL,3484,'Winegard, 18 Inch Dish(D Tube Feed)','&lt;ul style=&quot;line-height: 100%; margin-top: 8px; font-family: verdana; margin-bottom: 8px; color: #000099; margin-left: 2px; font-size: 8pt&quot;&gt;
    &lt;li style=&quot;font-size: 8pt&quot;&gt;&lt;font color=&quot;#000099&quot; size=&quot;1&quot; face=&quot;verdana&quot;&gt;Reflectors are manufactured from galvanized steel, finished with a 7-step, textured thermoset powder coat&lt;/font&gt;&lt;/li&gt;
&lt;/ul&gt;
&lt;ul style=&quot;line-height: 100%; margin-top: 8px; font-family: verdana; margin-bottom: 8px; color: #000099; margin-left: 2px; font-size: 8pt&quot;&gt;
    &lt;li style=&quot;font-size: 8pt&quot;&gt;&lt;font color=&quot;#000099&quot; size=&quot;1&quot; face=&quot;verdana&quot;&gt;Reflector: 18&amp;quot; inches (46cm)&lt;/font&gt;&lt;/li&gt;
&lt;/ul&gt;
&lt;ul style=&quot;line-height: 100%; margin-top: 8px; font-family: verdana; margin-bottom: 8px; color: #000099; margin-left: 2px; font-size: 8pt&quot;&gt;
    &lt;li style=&quot;font-size: 8pt&quot;&gt;&lt;font color=&quot;#000099&quot; size=&quot;1&quot; face=&quot;verdana&quot;&gt;compatible with Dish Network and DIRECTV LNBs&lt;/font&gt;&lt;/li&gt;
&lt;/ul&gt;
&lt;ul style=&quot;line-height: 100%; margin-top: 8px; font-family: verdana; margin-bottom: 8px; color: #000099; margin-left: 2px; font-size: 8pt&quot;&gt;
    &lt;li style=&quot;font-size: 8pt&quot;&gt;&lt;font color=&quot;#000099&quot; size=&quot;1&quot; face=&quot;verdana&quot;&gt;&lt;b&gt;LNBF NOT INCLUDED&lt;/b&gt;&lt;/font&gt;&lt;/li&gt;
&lt;/ul&gt;','2012-02-19 19:12:22','y',NULL,NULL,NULL,'n',NULL,NULL,NULL,NULL,'2012-02-15 17:25:23','n',NULL,NULL,12,12,'y','y',NULL,NULL,'n',NULL,'y','n','n',NULL,NULL)
INSERT INTO [firecms_content] ( [id], [content_type], [content_url], [content_filename], [content_parent], [content_title], [content_body], [updated_on], [is_active], [content_subtype], [content_subtype_value], [content_layout_file], [is_home], [content_meta_title], [content_meta_description], [content_meta_keywords], [content_meta_other_code], [created_on], [is_featured], [original_link], [content_description], [created_by], [edited_by], [is_pinged], [comments_enabled], [content_layout_name], [content_layout_style], [content_filename_sync_with_editor], [content_body_filename], [visible_on_frontend], [is_special], [require_login], [active_site_template], [expires_on]) VALUES (3612,'post','winegard-24-inch-dish-d-tube-feed',NULL,3484,'Winegard, 24 Inch Dish(D Tube Feed)','&lt;p&gt;&amp;nbsp;&lt;/p&gt;
&lt;div style=&quot;text-indent: -0.25in; margin: 0in 0in 0pt 0.5in&quot;&gt;&lt;span style=&quot;font-size: 10pt&quot;&gt;&amp;middot;&lt;span style=&quot;font: 7pt ''Times New Roman''&quot;&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp; &lt;/span&gt;&lt;/span&gt;&lt;span style=&quot;font-size: 10pt&quot;&gt;Antenna width 64 cm (25.3 in.)&lt;/span&gt;&lt;/div&gt;
&lt;div style=&quot;text-indent: -0.25in; margin: 0in 0in 0pt 0.5in&quot;&gt;&lt;span style=&quot;font-size: 10pt&quot;&gt;&amp;middot;&lt;span style=&quot;font: 7pt ''Times New Roman''&quot;&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp; &lt;/span&gt;&lt;/span&gt;&lt;span style=&quot;font-size: 10pt&quot;&gt;Galvanized steel 21 ga. &lt;/span&gt;&lt;/div&gt;
&lt;div style=&quot;text-indent: -0.25in; margin: 0in 0in 0pt 0.5in&quot;&gt;&lt;span style=&quot;font-size: 10pt&quot;&gt;&amp;middot;&lt;span style=&quot;font: 7pt ''Times New Roman''&quot;&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp; &lt;/span&gt;&lt;/span&gt;&lt;span style=&quot;font-size: 10pt&quot;&gt;Weight, antenna only 2.85 Kg. (5 lbs.) &lt;/span&gt;&lt;/div&gt;
&lt;div style=&quot;text-indent: -0.25in; margin: 0in 0in 0pt 0.5in&quot;&gt;&lt;span style=&quot;font-size: 10pt&quot;&gt;&amp;middot;&lt;span style=&quot;font: 7pt ''Times New Roman''&quot;&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp; &lt;/span&gt;&lt;/span&gt;&lt;span style=&quot;font-size: 10pt&quot;&gt;F/D .59 &lt;/span&gt;&lt;/div&gt;
&lt;div style=&quot;text-indent: -0.25in; margin: 0in 0in 0pt 0.5in&quot;&gt;&lt;span style=&quot;font-size: 10pt&quot;&gt;&amp;middot;&lt;span style=&quot;font: 7pt ''Times New Roman''&quot;&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp; &lt;/span&gt;&lt;/span&gt;&lt;span style=&quot;font-size: 10pt&quot;&gt;Offset angle 24&amp;deg; &lt;/span&gt;&lt;/div&gt;
&lt;div style=&quot;text-indent: -0.25in; margin: 0in 0in 0pt 0.5in&quot;&gt;&lt;span style=&quot;font-size: 10pt&quot;&gt;&amp;middot;&lt;span style=&quot;font: 7pt ''Times New Roman''&quot;&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp; &lt;/span&gt;&lt;/span&gt;&lt;span style=&quot;font-size: 10pt&quot;&gt;Focal distance 35.2 cm (13.87 in.) &lt;/span&gt;&lt;/div&gt;
&lt;div style=&quot;text-indent: -0.25in; margin: 0in 0in 0pt 0.5in&quot;&gt;&lt;span style=&quot;font-size: 10pt&quot;&gt;&amp;middot;&lt;span style=&quot;font: 7pt ''Times New Roman''&quot;&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp; &lt;/span&gt;&lt;/span&gt;&lt;span style=&quot;font-size: 10pt&quot;&gt;Surface accuracy .014 - .018 in. ave. &lt;/span&gt;&lt;/div&gt;
&lt;div style=&quot;text-indent: -0.25in; margin: 0in 0in 0pt 0.5in&quot;&gt;&lt;span style=&quot;font-size: 10pt&quot;&gt;&amp;middot;&lt;span style=&quot;font: 7pt ''Times New Roman''&quot;&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp; &lt;/span&gt;&lt;/span&gt;&lt;span style=&quot;font-size: 10pt&quot;&gt;Elevation range 10&amp;deg; to 90&amp;deg; &lt;/span&gt;&lt;/div&gt;
&lt;div style=&quot;text-indent: -0.25in; margin: 0in 0in 0pt 0.5in&quot;&gt;&lt;span style=&quot;font-size: 10pt&quot;&gt;&amp;middot;&lt;span style=&quot;font: 7pt ''Times New Roman''&quot;&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp; &lt;/span&gt;&lt;/span&gt;&lt;span style=&quot;font-size: 10pt&quot;&gt;Mounting options Universal &lt;/span&gt;&lt;/div&gt;','2012-02-19 19:12:23','y',NULL,NULL,NULL,'n',NULL,NULL,NULL,NULL,'2012-02-15 17:25:25','n',NULL,NULL,12,12,'y','y',NULL,NULL,'n',NULL,'y','n','n',NULL,NULL)
INSERT INTO [firecms_content] ( [id], [content_type], [content_url], [content_filename], [content_parent], [content_title], [content_body], [updated_on], [is_active], [content_subtype], [content_subtype_value], [content_layout_file], [is_home], [content_meta_title], [content_meta_description], [content_meta_keywords], [content_meta_other_code], [created_on], [is_featured], [original_link], [content_description], [created_by], [edited_by], [is_pinged], [comments_enabled], [content_layout_name], [content_layout_style], [content_filename_sync_with_editor], [content_body_filename], [visible_on_frontend], [is_special], [require_login], [active_site_template], [expires_on]) VALUES (3613,'post','winegard-24-inch-dish-rectangular-feed',NULL,3484,'Winegard, 24 Inch Dish(Rectangular Feed)','&lt;a href=&quot;/webstore/spec-sheets/DS4062.pdf&quot; target=&quot;_blank&quot;&gt;&lt;img border=&quot;0&quot; alt=&quot;&quot; src=&quot;/webstore/images/spec-button.jpg&quot; /&gt;&lt;/a&gt;','2012-02-19 19:12:24','y',NULL,NULL,NULL,'n',NULL,NULL,NULL,NULL,'2012-02-15 17:25:27','n',NULL,NULL,12,12,'y','y',NULL,NULL,'n',NULL,'y','n','n',NULL,NULL)
INSERT INTO [firecms_content] ( [id], [content_type], [content_url], [content_filename], [content_parent], [content_title], [content_body], [updated_on], [is_active], [content_subtype], [content_subtype_value], [content_layout_file], [is_home], [content_meta_title], [content_meta_description], [content_meta_keywords], [content_meta_other_code], [created_on], [is_featured], [original_link], [content_description], [created_by], [edited_by], [is_pinged], [comments_enabled], [content_layout_name], [content_layout_style], [content_filename_sync_with_editor], [content_body_filename], [visible_on_frontend], [is_special], [require_login], [active_site_template], [expires_on]) VALUES (3614,'post','winegard-nprm-frame-for-18inch-and-24inch-dish',NULL,3484,'Winegard, NPRM Frame For 18inch and 24inch Dish','&lt;a href=&quot;/webstore/spec-sheets/DS5046.pdf&quot; target=&quot;_blank&quot;&gt;&lt;img border=&quot;0&quot; alt=&quot;&quot; src=&quot;/webstore/images/spec-button.jpg&quot; /&gt;&lt;/a&gt;','2012-02-19 19:12:25','y',NULL,NULL,NULL,'n',NULL,NULL,NULL,NULL,'2012-02-15 17:25:28','n',NULL,NULL,12,12,'y','y',NULL,NULL,'n',NULL,'y','n','n',NULL,NULL)
INSERT INTO [firecms_content] ( [id], [content_type], [content_url], [content_filename], [content_parent], [content_title], [content_body], [updated_on], [is_active], [content_subtype], [content_subtype_value], [content_layout_file], [is_home], [content_meta_title], [content_meta_description], [content_meta_keywords], [content_meta_other_code], [created_on], [is_featured], [original_link], [content_description], [created_by], [edited_by], [is_pinged], [comments_enabled], [content_layout_name], [content_layout_style], [content_filename_sync_with_editor], [content_body_filename], [visible_on_frontend], [is_special], [require_login], [active_site_template], [expires_on]) VALUES (3615,'post','winegard-post-kit-for-30inch-dish-on-ds5046-nprm-frame-1-5-8od',NULL,3484,'Winegard,Post Kit For 30inch Dish on DS5046 NPRM Frame(1 5-8OD)','&lt;a href=&quot;/webstore/spec-sheets/DS5146.pdf&quot; target=&quot;_blank&quot;&gt;&lt;img border=&quot;0&quot; alt=&quot;&quot; src=&quot;/webstore/images/spec-button.jpg&quot; /&gt;&lt;/a&gt;','2012-02-19 19:12:26','y',NULL,NULL,NULL,'n',NULL,NULL,NULL,NULL,'2012-02-15 17:25:30','n',NULL,NULL,12,12,'y','y',NULL,NULL,'n',NULL,'y','n','n',NULL,NULL)
INSERT INTO [firecms_content] ( [id], [content_type], [content_url], [content_filename], [content_parent], [content_title], [content_body], [updated_on], [is_active], [content_subtype], [content_subtype_value], [content_layout_file], [is_home], [content_meta_title], [content_meta_description], [content_meta_keywords], [content_meta_other_code], [created_on], [is_featured], [original_link], [content_description], [created_by], [edited_by], [is_pinged], [comments_enabled], [content_layout_name], [content_layout_style], [content_filename_sync_with_editor], [content_body_filename], [visible_on_frontend], [is_special], [require_login], [active_site_template], [expires_on]) VALUES (3616,'post','connector-digicon-s-series-rg59-red-bag-of-100',NULL,3484,'Connector, Digicon S Series RG59-Red  (bag of 100)','&lt;p&gt;&amp;bull; Premium RG59 F-connectors for RG59 67% braid thru RG59 trishield&lt;br /&gt;
&amp;nbsp; coaxial cable.&lt;br /&gt;
&amp;bull; Digicon coax connectors terminate with a conical compression&lt;br /&gt;
&amp;bull; The 360 degree conical compression seals connector around cable jacket&lt;br /&gt;
&amp;bull; Digicon S Series die creates compression sledge in connector&lt;br /&gt;
&amp;bull; Digicon connectors include a machined brass sealing ring located&lt;br /&gt;
&amp;nbsp; in the final compression taper to create a permanent water barrier at&lt;br /&gt;
&amp;nbsp; the connector base.&lt;br /&gt;
&amp;bull; A lubricated internal O-ring prevents moisture migration at the mandrel nut.&lt;br /&gt;
&amp;bull; A large ferrule contact surface ensures a proper RF and digital interface.&lt;br /&gt;
&amp;bull; The free-spinning brass tin-plated body &amp;amp; nickel- plated nut provide&lt;br /&gt;
&amp;nbsp; superior corrosion resistance &amp;amp; durability.&lt;br /&gt;
&amp;bull; The connector nut is 50 percent larger, 20 percent deeper, with 30&lt;br /&gt;
&amp;nbsp; percent more threads for a more firm grip &amp;amp; to provide a secure weathertight&lt;br /&gt;
&amp;nbsp; connection. Exceeds SCTE &amp;amp; Telcordia specifications.&lt;br /&gt;
&amp;bull; Bag of 100&lt;/p&gt;','2012-02-19 19:12:27','y',NULL,NULL,NULL,'n',NULL,NULL,NULL,NULL,'2012-02-15 17:25:31','n',NULL,NULL,12,12,'y','y',NULL,NULL,'n',NULL,'y','n','n',NULL,NULL)
INSERT INTO [firecms_content] ( [id], [content_type], [content_url], [content_filename], [content_parent], [content_title], [content_body], [updated_on], [is_active], [content_subtype], [content_subtype_value], [content_layout_file], [is_home], [content_meta_title], [content_meta_description], [content_meta_keywords], [content_meta_other_code], [created_on], [is_featured], [original_link], [content_description], [created_by], [edited_by], [is_pinged], [comments_enabled], [content_layout_name], [content_layout_style], [content_filename_sync_with_editor], [content_body_filename], [visible_on_frontend], [is_special], [require_login], [active_site_template], [expires_on]) VALUES (3617,'post','connector-digicon-s-series-rg6-blue-bag-of-100',NULL,3484,'Connector, Digicon S Series RG6-Blue.  (bag of 100)','&lt;ul&gt;
    &lt;li&gt;Premium RG6 F-connectors for RG6 60% braid thru RG6 tri-shield coaxial cable&lt;/li&gt;
    &lt;li&gt;Digicon coax connectors terminate with a conical compression&lt;/li&gt;
    &lt;li&gt;The 360 degree conical compression seals connector around cable jacket&lt;/li&gt;
    &lt;li&gt;Digicon S Series die creates compression sledge in connector&lt;/li&gt;
    &lt;li&gt;Digicon connectors include a unique machined brass sealing ring located in the final compression taper to create a permanent water barrier at connector base.&lt;/li&gt;
    &lt;li&gt;A lubricated internal O-ring prevents moisture migration at the mandrel nut.&lt;/li&gt;
    &lt;li&gt;A large ferrule contact surface ensures a proper RF &amp;amp; digital interface.&lt;/li&gt;
    &lt;li&gt;The free-spinning brass tin-plated body &amp;amp; nickel-plated nut provide superior corrosion resistance &amp;amp; durability&lt;/li&gt;
    &lt;li&gt;The connector nut is 50 percent larger, 20 percent deeper, with 30 percent more threads for a firmer grip &amp;amp; to provide a secure weathertight connection.&lt;/li&gt;
    &lt;li&gt;Bag of 100&lt;/li&gt;
&lt;/ul&gt;
&lt;p&gt;&amp;nbsp;&lt;/p&gt;','2012-02-19 19:12:28','y',NULL,NULL,NULL,'n',NULL,NULL,NULL,NULL,'2012-02-15 17:25:34','n',NULL,NULL,12,12,'y','y',NULL,NULL,'n',NULL,'y','n','n',NULL,NULL)
INSERT INTO [firecms_content] ( [id], [content_type], [content_url], [content_filename], [content_parent], [content_title], [content_body], [updated_on], [is_active], [content_subtype], [content_subtype_value], [content_layout_file], [is_home], [content_meta_title], [content_meta_description], [content_meta_keywords], [content_meta_other_code], [created_on], [is_featured], [original_link], [content_description], [created_by], [edited_by], [is_pinged], [comments_enabled], [content_layout_name], [content_layout_style], [content_filename_sync_with_editor], [content_body_filename], [visible_on_frontend], [is_special], [require_login], [active_site_template], [expires_on]) VALUES (3618,'post','connector-digicon-s-series-rg6-quad-bag-of-100',NULL,3484,'Connector, Digicon S Series RG6 - QUAD  (bag of 100)','&lt;p&gt;&amp;bull; Premium F-connectors forRG6 tri-shield &amp;amp; RG6 quad-shield coax cable&lt;br /&gt;
&amp;bull; Digicon coax connectors terminate with conical compression&lt;br /&gt;
&amp;bull; 360 degree conical compression seals connector around cable jacket&lt;br /&gt;
&amp;bull; Digicon S Series die creates compression sedge in connector&lt;br /&gt;
&amp;bull; Digicon connectors include a unique machined brass sealing ring located&lt;br /&gt;
&amp;nbsp; in final compression taper to create a permanent water barrier at connector&amp;nbsp; base&lt;br /&gt;
&amp;bull; Lubricated internal O-ring prevents moisture migration at mandrel nut&lt;br /&gt;
&amp;bull; Large ferrule contact surface ensures a proper RF &amp;amp; digital interface&lt;br /&gt;
&amp;bull; The free-spinning brass tin-plated body and nickel-plated nut provide superior&lt;br /&gt;
&amp;nbsp; corrosion resistance and durability.&lt;br /&gt;
&amp;bull; The connector nut is 50 percent larger, 20 percent deeper, with 30&lt;br /&gt;
&amp;nbsp; percent more threads for a more firm grip and to provide a secure weathertight&lt;br /&gt;
&amp;nbsp; connection. Exceeds SCTE and Telcordia specifications.&lt;br /&gt;
&amp;bull; Bag of 100&lt;br /&gt;
&lt;br /&gt;
&amp;nbsp;&lt;/p&gt;','2012-02-19 19:12:28','y',NULL,NULL,NULL,'n',NULL,NULL,NULL,NULL,'2012-02-15 17:25:36','n',NULL,NULL,12,12,'y','y',NULL,NULL,'n',NULL,'y','n','n',NULL,NULL)
INSERT INTO [firecms_content] ( [id], [content_type], [content_url], [content_filename], [content_parent], [content_title], [content_body], [updated_on], [is_active], [content_subtype], [content_subtype_value], [content_layout_file], [is_home], [content_meta_title], [content_meta_description], [content_meta_keywords], [content_meta_other_code], [created_on], [is_featured], [original_link], [content_description], [created_by], [edited_by], [is_pinged], [comments_enabled], [content_layout_name], [content_layout_style], [content_filename_sync_with_editor], [content_body_filename], [visible_on_frontend], [is_special], [require_login], [active_site_template], [expires_on]) VALUES (3619,'post','dtv-87-cm-odu-101-and-95-degrees',NULL,3484,'DTV 87 cm ODU (101 and 95 Degrees)','','2012-02-19 19:12:29','y',NULL,NULL,NULL,'n',NULL,NULL,NULL,NULL,'2012-02-15 17:25:37','n',NULL,NULL,12,12,'y','y',NULL,NULL,'n',NULL,'y','n','n',NULL,NULL)
INSERT INTO [firecms_content] ( [id], [content_type], [content_url], [content_filename], [content_parent], [content_title], [content_body], [updated_on], [is_active], [content_subtype], [content_subtype_value], [content_layout_file], [is_home], [content_meta_title], [content_meta_description], [content_meta_keywords], [content_meta_other_code], [created_on], [is_featured], [original_link], [content_description], [created_by], [edited_by], [is_pinged], [comments_enabled], [content_layout_name], [content_layout_style], [content_filename_sync_with_editor], [content_body_filename], [visible_on_frontend], [is_special], [require_login], [active_site_template], [expires_on]) VALUES (3620,'post','applied-instruments-dual-satellite-meter-w-switch-control',NULL,3484,'Applied Instruments, Dual Satellite Meter w-Switch Control','&lt;p&gt;&amp;bull; 22 kHz switch contro&lt;br /&gt;
&amp;bull; Field replaceable connectors&lt;/p&gt;','2012-02-19 19:12:30','y',NULL,NULL,NULL,'n',NULL,NULL,NULL,NULL,'2012-02-15 17:25:39','n',NULL,NULL,12,12,'y','y',NULL,NULL,'n',NULL,'y','n','n',NULL,NULL)
INSERT INTO [firecms_content] ( [id], [content_type], [content_url], [content_filename], [content_parent], [content_title], [content_body], [updated_on], [is_active], [content_subtype], [content_subtype_value], [content_layout_file], [is_home], [content_meta_title], [content_meta_description], [content_meta_keywords], [content_meta_other_code], [created_on], [is_featured], [original_link], [content_description], [created_by], [edited_by], [is_pinged], [comments_enabled], [content_layout_name], [content_layout_style], [content_filename_sync_with_editor], [content_body_filename], [visible_on_frontend], [is_special], [require_login], [active_site_template], [expires_on]) VALUES (3621,'post','eave-mount-bracket',NULL,3484,'Eave Mount Bracket','Mount, Eave, DBS, Adjustable ','2012-02-19 19:12:31','y',NULL,NULL,NULL,'n',NULL,NULL,NULL,NULL,'2012-02-15 17:25:41','n',NULL,NULL,12,12,'y','y',NULL,NULL,'n',NULL,'y','n','n',NULL,NULL)
INSERT INTO [firecms_content] ( [id], [content_type], [content_url], [content_filename], [content_parent], [content_title], [content_body], [updated_on], [is_active], [content_subtype], [content_subtype_value], [content_layout_file], [is_home], [content_meta_title], [content_meta_description], [content_meta_keywords], [content_meta_other_code], [created_on], [is_featured], [original_link], [content_description], [created_by], [edited_by], [is_pinged], [comments_enabled], [content_layout_name], [content_layout_style], [content_filename_sync_with_editor], [content_body_filename], [visible_on_frontend], [is_special], [require_login], [active_site_template], [expires_on]) VALUES (3622,'post','ppc-compression-f-connector-universal-rg6-bag-of-50',NULL,3484,'PPC, Compression F Connector, Universal(RG6)(Bag of 50)','&lt;ul&gt;
    &lt;li&gt;Bandwidth: 0 MHz to 3 GHz&amp;nbsp;&lt;/li&gt;
    &lt;li&gt;Impedance: 75 Ohms&amp;nbsp;&lt;/li&gt;
    &lt;li&gt;Return Loss: Minimum -30 dB to 3 GHz&amp;nbsp;&lt;/li&gt;
    &lt;li&gt;Insertion Loss: Less than .10 dB to 3 GHz&amp;nbsp;&lt;/li&gt;
    &lt;li&gt;Operating Voltage: 90 V (at 60 Hz continuous AC)&amp;nbsp;&lt;/li&gt;
    &lt;li&gt;Operating Temperature: -40&amp;deg;F to +140&amp;deg;F ( -40&amp;deg;C to +60&amp;deg;C)&lt;/li&gt;
    &lt;li&gt;Cable Retention: 45 lbs (20.41 kg) minimum&amp;nbsp;&lt;/li&gt;
    &lt;li&gt;Meets or exceeds all SCTE Specifications&lt;/li&gt;
&lt;/ul&gt;','2012-02-19 19:12:32','y',NULL,NULL,NULL,'n',NULL,NULL,NULL,NULL,'2012-02-15 17:25:42','n',NULL,NULL,12,12,'y','y',NULL,NULL,'n',NULL,'y','n','n',NULL,NULL)
INSERT INTO [firecms_content] ( [id], [content_type], [content_url], [content_filename], [content_parent], [content_title], [content_body], [updated_on], [is_active], [content_subtype], [content_subtype_value], [content_layout_file], [is_home], [content_meta_title], [content_meta_description], [content_meta_keywords], [content_meta_other_code], [created_on], [is_featured], [original_link], [content_description], [created_by], [edited_by], [is_pinged], [comments_enabled], [content_layout_name], [content_layout_style], [content_filename_sync_with_editor], [content_body_filename], [visible_on_frontend], [is_special], [require_login], [active_site_template], [expires_on]) VALUES (3623,'post','cable-bel-rg6-sc-center-reel-1000ft-blk',NULL,3484,'Cable, Bel RG6 SC Center Reel 1000Ft Blk','&lt;p&gt;
&lt;table cellspacing=&quot;0&quot; cellpadding=&quot;0&quot; width=&quot;90%&quot; border=&quot;0&quot;&gt;
    &lt;tbody&gt;
        &lt;tr&gt;
            &lt;td class=&quot;catMainTxt&quot;&gt;Put Ups and Colors:&lt;/td&gt;
        &lt;/tr&gt;
    &lt;/tbody&gt;
&lt;/table&gt;
&lt;/p&gt;
&lt;div class=&quot;putUpsCls&quot; id=&quot;putUpsDiv&quot;&gt;
&lt;table class=&quot;tblOuterCls&quot; cellspacing=&quot;0&quot; cellpadding=&quot;0&quot;&gt;
    &lt;tbody&gt;
        &lt;tr&gt;
            &lt;td&gt;
            &lt;table class=&quot;tblInnerCls&quot; cellspacing=&quot;1&quot; cellpadding=&quot;5&quot;&gt;
                &lt;tbody&gt;
                    &lt;tr&gt;
                        &lt;td class=&quot;tblHdrCls&quot;&gt;Item #&lt;/td&gt;
                        &lt;td class=&quot;tblHdrCls&quot;&gt;Putup&lt;/td&gt;
                        &lt;td class=&quot;tblHdrCls&quot;&gt;Ship Weight&lt;/td&gt;
                        &lt;td class=&quot;tblHdrCls&quot;&gt;Color&lt;/td&gt;
                        &lt;td class=&quot;tblHdrCls&quot;&gt;Notes&lt;/td&gt;
                        &lt;td class=&quot;tblHdrCls&quot;&gt;Item Desc&lt;/td&gt;
                    &lt;/tr&gt;
                    &lt;tr&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;1829AC 0101000&lt;/td&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;1,000 FT&lt;/td&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;31.000 LB&lt;/td&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;BLACK&lt;/td&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;C&lt;/td&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;#18 GIFHDLDPE SH FR PVC&lt;/td&gt;
                    &lt;/tr&gt;
                &lt;/tbody&gt;
            &lt;/table&gt;
            &lt;/td&gt;
        &lt;/tr&gt;
    &lt;/tbody&gt;
&lt;/table&gt;
&lt;table class=&quot;notesCls&quot; cellspacing=&quot;0&quot; cellpadding=&quot;0&quot;&gt;
    &lt;tbody&gt;
        &lt;tr&gt;
            &lt;td class=&quot;boldCls&quot;&gt;Notes:&lt;/td&gt;
            &lt;td&gt;&amp;nbsp;&lt;/td&gt;
        &lt;/tr&gt;
        &lt;tr&gt;
            &lt;td&gt;C = CRATE REEL PUT-UP.&lt;br /&gt;
            &amp;nbsp;&lt;/td&gt;
        &lt;/tr&gt;
    &lt;/tbody&gt;
&lt;/table&gt;
&lt;/div&gt;
&lt;div class=&quot;catMain&quot;&gt;Physical Characteristics (Overall)&lt;/div&gt;
&lt;div class=&quot;catSub lvl1&quot;&gt;Conductor&lt;/div&gt;
&lt;div class=&quot;attribNm lvl2&quot;&gt;AWG:&lt;/div&gt;
&lt;div class=&quot;lvl3&quot;&gt;
&lt;table class=&quot;tblOuterCls&quot; cellspacing=&quot;0&quot; cellpadding=&quot;0&quot; border=&quot;0&quot;&gt;
    &lt;tbody&gt;
        &lt;tr&gt;
            &lt;td&gt;
            &lt;table class=&quot;tblInnerCls&quot; cellspacing=&quot;1&quot; cellpadding=&quot;0&quot;&gt;
                &lt;tbody&gt;
                    &lt;tr&gt;
                        &lt;td class=&quot;tblHdrCls&quot;&gt;# Coax&lt;/td&gt;
                        &lt;td class=&quot;tblHdrCls&quot;&gt;AWG&lt;/td&gt;
                        &lt;td class=&quot;tblHdrCls&quot;&gt;Stranding&lt;/td&gt;
                        &lt;td class=&quot;tblHdrCls&quot;&gt;Conductor Material&lt;/td&gt;
                        &lt;td class=&quot;tblHdrCls&quot;&gt;Dia. (in.)&lt;/td&gt;
                    &lt;/tr&gt;
                    &lt;tr&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;1&lt;/td&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;18&lt;/td&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;Solid&lt;/td&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;BCAC - Bare Copper w/Anti-Corrosion Treatment&lt;/td&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;.040&lt;/td&gt;
                    &lt;/tr&gt;
                &lt;/tbody&gt;
            &lt;/table&gt;
            &lt;/td&gt;
        &lt;/tr&gt;
    &lt;/tbody&gt;
&lt;/table&gt;
&lt;/div&gt;
&lt;div class=&quot;attribTabCls&quot;&gt;
&lt;table width=&quot;95%&quot; border=&quot;0&quot;&gt;
    &lt;tbody&gt;
        &lt;tr&gt;
            &lt;td class=&quot;attribNm&quot; style=&quot;width: 40%&quot;&gt;Corrosion Resistance:&lt;/td&gt;
            &lt;td class=&quot;attribVal&quot; style=&quot;width: 60%&quot;&gt;Yes&lt;/td&gt;
        &lt;/tr&gt;
    &lt;/tbody&gt;
&lt;/table&gt;
&lt;/div&gt;
&lt;div class=&quot;catSub lvl1&quot;&gt;Insulation&lt;/div&gt;
&lt;div class=&quot;attribNm lvl2&quot;&gt;Insulation Material:&lt;/div&gt;
&lt;div class=&quot;lvl3&quot;&gt;
&lt;table class=&quot;tblOuterCls&quot; cellspacing=&quot;0&quot; cellpadding=&quot;0&quot; border=&quot;0&quot;&gt;
    &lt;tbody&gt;
        &lt;tr&gt;
            &lt;td&gt;
            &lt;table class=&quot;tblInnerCls&quot; cellspacing=&quot;1&quot; cellpadding=&quot;0&quot;&gt;
                &lt;tbody&gt;
                    &lt;tr&gt;
                        &lt;td class=&quot;tblHdrCls&quot;&gt;Insulation Material&lt;/td&gt;
                        &lt;td class=&quot;tblHdrCls&quot;&gt;Dia. (in.)&lt;/td&gt;
                    &lt;/tr&gt;
                    &lt;tr&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;Gas-injected FPE - Foam Polyethylene&lt;/td&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;.180&lt;/td&gt;
                    &lt;/tr&gt;
                &lt;/tbody&gt;
            &lt;/table&gt;
            &lt;/td&gt;
        &lt;/tr&gt;
    &lt;/tbody&gt;
&lt;/table&gt;
&lt;/div&gt;
&lt;div class=&quot;catSub lvl1&quot;&gt;Outer Shield&lt;/div&gt;
&lt;div class=&quot;attribNm lvl2&quot;&gt;Outer Shield Material:&lt;/div&gt;
&lt;div class=&quot;lvl3&quot;&gt;
&lt;table class=&quot;tblOuterCls&quot; cellspacing=&quot;0&quot; cellpadding=&quot;0&quot; border=&quot;0&quot;&gt;
    &lt;tbody&gt;
        &lt;tr&gt;
            &lt;td&gt;
            &lt;table class=&quot;tblInnerCls&quot; cellspacing=&quot;1&quot; cellpadding=&quot;0&quot;&gt;
                &lt;tbody&gt;
                    &lt;tr&gt;
                        &lt;td class=&quot;tblHdrCls&quot;&gt;Layer #&lt;/td&gt;
                        &lt;td class=&quot;tblHdrCls&quot;&gt;Outer Shield Trade Name&lt;/td&gt;
                        &lt;td class=&quot;tblHdrCls&quot;&gt;Type&lt;/td&gt;
                        &lt;td class=&quot;tblHdrCls&quot;&gt;Outer Shield Material&lt;/td&gt;
                        &lt;td class=&quot;tblHdrCls&quot;&gt;Coverage (%)&lt;/td&gt;
                    &lt;/tr&gt;
                    &lt;tr&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;1&lt;/td&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;Bonded Duofoil&amp;reg;&lt;/td&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;Tape&lt;/td&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;Bonded Aluminum Foil-Polyester Tape-Aluminum Foil&lt;/td&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;100&lt;/td&gt;
                    &lt;/tr&gt;
                    &lt;tr&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;2&lt;/td&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;&amp;nbsp;&lt;/td&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;Braid&lt;/td&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;AL - Aluminum&lt;/td&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;60&lt;/td&gt;
                    &lt;/tr&gt;
                &lt;/tbody&gt;
            &lt;/table&gt;
            &lt;/td&gt;
        &lt;/tr&gt;
    &lt;/tbody&gt;
&lt;/table&gt;
&lt;/div&gt;
&lt;div class=&quot;catSub lvl1&quot;&gt;Outer Jacket&lt;/div&gt;
&lt;div class=&quot;attribNm lvl2&quot;&gt;Outer Jacket Material:&lt;/div&gt;
&lt;div class=&quot;lvl3&quot;&gt;
&lt;table class=&quot;tblOuterCls&quot; cellspacing=&quot;0&quot; cellpadding=&quot;0&quot; border=&quot;0&quot;&gt;
    &lt;tbody&gt;
        &lt;tr&gt;
            &lt;td&gt;
            &lt;table class=&quot;tblInnerCls&quot; cellspacing=&quot;1&quot; cellpadding=&quot;0&quot;&gt;
                &lt;tbody&gt;
                    &lt;tr&gt;
                        &lt;td class=&quot;tblHdrCls&quot;&gt;Outer Jacket Material&lt;/td&gt;
                    &lt;/tr&gt;
                    &lt;tr&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;PVC - Polyvinyl Chloride&lt;/td&gt;
                    &lt;/tr&gt;
                &lt;/tbody&gt;
            &lt;/table&gt;
            &lt;/td&gt;
        &lt;/tr&gt;
    &lt;/tbody&gt;
&lt;/table&gt;
&lt;/div&gt;
&lt;div class=&quot;catSub lvl1&quot;&gt;Overall Cabling&lt;/div&gt;
&lt;div class=&quot;attribTabCls&quot;&gt;
&lt;table width=&quot;95%&quot; border=&quot;0&quot;&gt;
    &lt;tbody&gt;
        &lt;tr&gt;
            &lt;td class=&quot;attribNm&quot; style=&quot;width: 40%&quot;&gt;Overall Nominal Diameter:&lt;/td&gt;
            &lt;td class=&quot;attribVal&quot; style=&quot;width: 60%&quot;&gt;0.270 in.&lt;/td&gt;
        &lt;/tr&gt;
    &lt;/tbody&gt;
&lt;/table&gt;
&lt;/div&gt;
&lt;div class=&quot;catMain&quot;&gt;Mechanical Characteristics (Overall)&lt;/div&gt;
&lt;div class=&quot;attribTabCls&quot;&gt;
&lt;table width=&quot;95%&quot; border=&quot;0&quot;&gt;
    &lt;tbody&gt;
        &lt;tr&gt;
            &lt;td class=&quot;attribNm&quot; style=&quot;width: 40%&quot;&gt;Installation Temperature Range:&lt;/td&gt;
            &lt;td class=&quot;attribVal&quot; style=&quot;width: 60%&quot;&gt;-30&amp;deg;C To +80&amp;deg;C&lt;/td&gt;
        &lt;/tr&gt;
    &lt;/tbody&gt;
&lt;/table&gt;
&lt;/div&gt;
&lt;div class=&quot;attribTabCls&quot;&gt;
&lt;table width=&quot;95%&quot; border=&quot;0&quot;&gt;
    &lt;tbody&gt;
        &lt;tr&gt;
            &lt;td class=&quot;attribNm&quot; style=&quot;width: 40%&quot;&gt;Operating Temperature Range:&lt;/td&gt;
            &lt;td class=&quot;attribVal&quot; style=&quot;width: 60%&quot;&gt;-40&amp;deg;C To +80&amp;deg;C&lt;/td&gt;
        &lt;/tr&gt;
    &lt;/tbody&gt;
&lt;/table&gt;
&lt;/div&gt;
&lt;div class=&quot;attribTabCls&quot;&gt;
&lt;table width=&quot;95%&quot; border=&quot;0&quot;&gt;
    &lt;tbody&gt;
        &lt;tr&gt;
            &lt;td class=&quot;attribNm&quot; style=&quot;width: 40%&quot;&gt;Bulk Cable Weight:&lt;/td&gt;
            &lt;td class=&quot;attribVal&quot; style=&quot;width: 60%&quot;&gt;30 lbs/1000 ft.&lt;/td&gt;
        &lt;/tr&gt;
    &lt;/tbody&gt;
&lt;/table&gt;
&lt;/div&gt;
&lt;div class=&quot;attribTabCls&quot;&gt;
&lt;table width=&quot;95%&quot; border=&quot;0&quot;&gt;
    &lt;tbody&gt;
        &lt;tr&gt;
            &lt;td class=&quot;attribNm&quot; style=&quot;width: 40%&quot;&gt;Max. Recommended Pulling Tension:&lt;/td&gt;
            &lt;td class=&quot;attribVal&quot; style=&quot;width: 60%&quot;&gt;80 lbs.&lt;/td&gt;
        &lt;/tr&gt;
    &lt;/tbody&gt;
&lt;/table&gt;
&lt;/div&gt;
&lt;div class=&quot;attribTabCls&quot;&gt;
&lt;table width=&quot;95%&quot; border=&quot;0&quot;&gt;
    &lt;tbody&gt;
        &lt;tr&gt;
            &lt;td class=&quot;attribNm&quot; style=&quot;width: 40%&quot;&gt;Min. Bend Radius (Install)/Minor Axis:&lt;/td&gt;
            &lt;td class=&quot;attribVal&quot; style=&quot;width: 60%&quot;&gt;2 in.&lt;/td&gt;
        &lt;/tr&gt;
    &lt;/tbody&gt;
&lt;/table&gt;
&lt;/div&gt;
&lt;div class=&quot;catMain&quot;&gt;Applicable Specifications and Agency Compliance (Overall)&lt;/div&gt;
&lt;div class=&quot;catSub lvl1&quot;&gt;Applicable Standards &amp;amp; Environmental Programs&lt;/div&gt;
&lt;div class=&quot;attribTabCls&quot;&gt;
&lt;table width=&quot;95%&quot; border=&quot;0&quot;&gt;
    &lt;tbody&gt;
        &lt;tr&gt;
            &lt;td class=&quot;attribNm&quot; style=&quot;width: 40%&quot;&gt;NEC/(UL) Specification:&lt;/td&gt;
            &lt;td class=&quot;attribVal&quot; style=&quot;width: 60%&quot;&gt;CATV, CM&lt;/td&gt;
        &lt;/tr&gt;
    &lt;/tbody&gt;
&lt;/table&gt;
&lt;/div&gt;
&lt;div class=&quot;attribTabCls&quot;&gt;
&lt;table width=&quot;95%&quot; border=&quot;0&quot;&gt;
    &lt;tbody&gt;
        &lt;tr&gt;
            &lt;td class=&quot;attribNm&quot; style=&quot;width: 40%&quot;&gt;CEC/C(UL) Specification:&lt;/td&gt;
            &lt;td class=&quot;attribVal&quot; style=&quot;width: 60%&quot;&gt;CM&lt;/td&gt;
        &lt;/tr&gt;
    &lt;/tbody&gt;
&lt;/table&gt;
&lt;/div&gt;
&lt;div class=&quot;attribTabCls&quot;&gt;
&lt;table width=&quot;95%&quot; border=&quot;0&quot;&gt;
    &lt;tbody&gt;
        &lt;tr&gt;
            &lt;td class=&quot;attribNm&quot; style=&quot;width: 40%&quot;&gt;EU CE Mark:&lt;/td&gt;
            &lt;td class=&quot;attribVal&quot; style=&quot;width: 60%&quot;&gt;Yes&lt;/td&gt;
        &lt;/tr&gt;
    &lt;/tbody&gt;
&lt;/table&gt;
&lt;/div&gt;
&lt;div class=&quot;attribTabCls&quot;&gt;
&lt;table width=&quot;95%&quot; border=&quot;0&quot;&gt;
    &lt;tbody&gt;
        &lt;tr&gt;
            &lt;td class=&quot;attribNm&quot; style=&quot;width: 40%&quot;&gt;EU Directive 2000/53/EC (ELV):&lt;/td&gt;
            &lt;td class=&quot;attribVal&quot; style=&quot;width: 60%&quot;&gt;Yes&lt;/td&gt;
        &lt;/tr&gt;
    &lt;/tbody&gt;
&lt;/table&gt;
&lt;/div&gt;
&lt;div class=&quot;attribTabCls&quot;&gt;
&lt;table width=&quot;95%&quot; border=&quot;0&quot;&gt;
    &lt;tbody&gt;
        &lt;tr&gt;
            &lt;td class=&quot;attribNm&quot; style=&quot;width: 40%&quot;&gt;EU Directive 2002/95/EC (RoHS):&lt;/td&gt;
            &lt;td class=&quot;attribVal&quot; style=&quot;width: 60%&quot;&gt;Yes&lt;/td&gt;
        &lt;/tr&gt;
    &lt;/tbody&gt;
&lt;/table&gt;
&lt;/div&gt;
&lt;div class=&quot;attribTabCls&quot;&gt;
&lt;table width=&quot;95%&quot; border=&quot;0&quot;&gt;
    &lt;tbody&gt;
        &lt;tr&gt;
            &lt;td class=&quot;attribNm&quot; style=&quot;width: 40%&quot;&gt;EU RoHS Compliance Date (mm/dd/yyyy):&lt;/td&gt;
            &lt;td class=&quot;attribVal&quot; style=&quot;width: 60%&quot;&gt;01/01/2004&lt;/td&gt;
        &lt;/tr&gt;
    &lt;/tbody&gt;
&lt;/table&gt;
&lt;/div&gt;
&lt;div class=&quot;attribTabCls&quot;&gt;
&lt;table width=&quot;95%&quot; border=&quot;0&quot;&gt;
    &lt;tbody&gt;
        &lt;tr&gt;
            &lt;td class=&quot;attribNm&quot; style=&quot;width: 40%&quot;&gt;EU Directive 2002/96/EC (WEEE):&lt;/td&gt;
            &lt;td class=&quot;attribVal&quot; style=&quot;width: 60%&quot;&gt;Yes&lt;/td&gt;
        &lt;/tr&gt;
    &lt;/tbody&gt;
&lt;/table&gt;
&lt;/div&gt;
&lt;div class=&quot;attribTabCls&quot;&gt;
&lt;table width=&quot;95%&quot; border=&quot;0&quot;&gt;
    &lt;tbody&gt;
        &lt;tr&gt;
            &lt;td class=&quot;attribNm&quot; style=&quot;width: 40%&quot;&gt;EU Directive 2003/11/EC (BFR):&lt;/td&gt;
            &lt;td class=&quot;attribVal&quot; style=&quot;width: 60%&quot;&gt;Yes&lt;/td&gt;
        &lt;/tr&gt;
    &lt;/tbody&gt;
&lt;/table&gt;
&lt;/div&gt;
&lt;div class=&quot;attribTabCls&quot;&gt;
&lt;table width=&quot;95%&quot; border=&quot;0&quot;&gt;
    &lt;tbody&gt;
        &lt;tr&gt;
            &lt;td class=&quot;attribNm&quot; style=&quot;width: 40%&quot;&gt;CA Prop 65 (CJ for Wire &amp;amp; Cable):&lt;/td&gt;
            &lt;td class=&quot;attribVal&quot; style=&quot;width: 60%&quot;&gt;Yes&lt;/td&gt;
        &lt;/tr&gt;
    &lt;/tbody&gt;
&lt;/table&gt;
&lt;/div&gt;
&lt;div class=&quot;attribTabCls&quot;&gt;
&lt;table width=&quot;95%&quot; border=&quot;0&quot;&gt;
    &lt;tbody&gt;
        &lt;tr&gt;
            &lt;td class=&quot;attribNm&quot; style=&quot;width: 40%&quot;&gt;MII Order #39 (China RoHS):&lt;/td&gt;
            &lt;td class=&quot;attribVal&quot; style=&quot;width: 60%&quot;&gt;Yes&lt;/td&gt;
        &lt;/tr&gt;
    &lt;/tbody&gt;
&lt;/table&gt;
&lt;/div&gt;
&lt;div class=&quot;attribTabCls&quot;&gt;
&lt;table width=&quot;95%&quot; border=&quot;0&quot;&gt;
    &lt;tbody&gt;
        &lt;tr&gt;
            &lt;td class=&quot;attribNm&quot; style=&quot;width: 40%&quot;&gt;Series Type:&lt;/td&gt;
            &lt;td class=&quot;attribVal&quot; style=&quot;width: 60%&quot;&gt;Series 6&lt;/td&gt;
        &lt;/tr&gt;
    &lt;/tbody&gt;
&lt;/table&gt;
&lt;/div&gt;
&lt;div class=&quot;catSub lvl1&quot;&gt;Flame Test&lt;/div&gt;
&lt;div class=&quot;attribTabCls&quot;&gt;
&lt;table width=&quot;95%&quot; border=&quot;0&quot;&gt;
    &lt;tbody&gt;
        &lt;tr&gt;
            &lt;td class=&quot;attribNm&quot; style=&quot;width: 40%&quot;&gt;UL Flame Test:&lt;/td&gt;
            &lt;td class=&quot;attribVal&quot; style=&quot;width: 60%&quot;&gt;UL1685 UL Loading&lt;/td&gt;
        &lt;/tr&gt;
    &lt;/tbody&gt;
&lt;/table&gt;
&lt;/div&gt;
&lt;div class=&quot;catSub lvl1&quot;&gt;Plenum/Non-Plenum&lt;/div&gt;
&lt;div class=&quot;attribTabCls&quot;&gt;
&lt;table width=&quot;95%&quot; border=&quot;0&quot;&gt;
    &lt;tbody&gt;
        &lt;tr&gt;
            &lt;td class=&quot;attribNm&quot; style=&quot;width: 40%&quot;&gt;Plenum (Y/N):&lt;/td&gt;
            &lt;td class=&quot;attribVal&quot; style=&quot;width: 60%&quot;&gt;No&lt;/td&gt;
        &lt;/tr&gt;
    &lt;/tbody&gt;
&lt;/table&gt;
&lt;/div&gt;
&lt;div class=&quot;catMain&quot;&gt;Electrical Characteristics (Overall)&lt;/div&gt;
&lt;div class=&quot;attribNm lvl1&quot;&gt;Nom. Characteristic Impedance:&lt;/div&gt;
&lt;div class=&quot;lvl2&quot;&gt;
&lt;table class=&quot;tblOuterCls&quot; cellspacing=&quot;0&quot; cellpadding=&quot;0&quot; border=&quot;0&quot;&gt;
    &lt;tbody&gt;
        &lt;tr&gt;
            &lt;td&gt;
            &lt;table class=&quot;tblInnerCls&quot; cellspacing=&quot;1&quot; cellpadding=&quot;0&quot;&gt;
                &lt;tbody&gt;
                    &lt;tr&gt;
                        &lt;td class=&quot;tblHdrCls&quot;&gt;Impedance (Ohm)&lt;/td&gt;
                        &lt;td class=&quot;tblHdrCls&quot;&gt;Tolerance (Ohms)&lt;/td&gt;
                    &lt;/tr&gt;
                    &lt;tr&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;75&lt;/td&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;&amp;plusmn; 3&lt;/td&gt;
                    &lt;/tr&gt;
                &lt;/tbody&gt;
            &lt;/table&gt;
            &lt;/td&gt;
        &lt;/tr&gt;
    &lt;/tbody&gt;
&lt;/table&gt;
&lt;/div&gt;
&lt;div class=&quot;attribNm lvl1&quot;&gt;Nom. Inductance:&lt;/div&gt;
&lt;div class=&quot;lvl2&quot;&gt;
&lt;table class=&quot;tblOuterCls&quot; cellspacing=&quot;0&quot; cellpadding=&quot;0&quot; border=&quot;0&quot;&gt;
    &lt;tbody&gt;
        &lt;tr&gt;
            &lt;td&gt;
            &lt;table class=&quot;tblInnerCls&quot; cellspacing=&quot;1&quot; cellpadding=&quot;0&quot;&gt;
                &lt;tbody&gt;
                    &lt;tr&gt;
                        &lt;td class=&quot;tblHdrCls&quot;&gt;Inductance (&amp;micro;H/ft)&lt;/td&gt;
                    &lt;/tr&gt;
                    &lt;tr&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;.097&lt;/td&gt;
                    &lt;/tr&gt;
                &lt;/tbody&gt;
            &lt;/table&gt;
            &lt;/td&gt;
        &lt;/tr&gt;
    &lt;/tbody&gt;
&lt;/table&gt;
&lt;/div&gt;
&lt;div class=&quot;attribNm lvl1&quot;&gt;Nom. Capacitance Conductor to Shield:&lt;/div&gt;
&lt;div class=&quot;lvl2&quot;&gt;
&lt;table class=&quot;tblOuterCls&quot; cellspacing=&quot;0&quot; cellpadding=&quot;0&quot; border=&quot;0&quot;&gt;
    &lt;tbody&gt;
        &lt;tr&gt;
            &lt;td&gt;
            &lt;table class=&quot;tblInnerCls&quot; cellspacing=&quot;1&quot; cellpadding=&quot;0&quot;&gt;
                &lt;tbody&gt;
                    &lt;tr&gt;
                        &lt;td class=&quot;tblHdrCls&quot;&gt;Capacitance (pF/ft)&lt;/td&gt;
                    &lt;/tr&gt;
                    &lt;tr&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;16.2&lt;/td&gt;
                    &lt;/tr&gt;
                &lt;/tbody&gt;
            &lt;/table&gt;
            &lt;/td&gt;
        &lt;/tr&gt;
    &lt;/tbody&gt;
&lt;/table&gt;
&lt;/div&gt;
&lt;div class=&quot;attribNm lvl1&quot;&gt;Nominal Velocity of Propagation:&lt;/div&gt;
&lt;div class=&quot;lvl2&quot;&gt;
&lt;table class=&quot;tblOuterCls&quot; cellspacing=&quot;0&quot; cellpadding=&quot;0&quot; border=&quot;0&quot;&gt;
    &lt;tbody&gt;
        &lt;tr&gt;
            &lt;td&gt;
            &lt;table class=&quot;tblInnerCls&quot; cellspacing=&quot;1&quot; cellpadding=&quot;0&quot;&gt;
                &lt;tbody&gt;
                    &lt;tr&gt;
                        &lt;td class=&quot;tblHdrCls&quot;&gt;VP (%)&lt;/td&gt;
                    &lt;/tr&gt;
                    &lt;tr&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;83&lt;/td&gt;
                    &lt;/tr&gt;
                &lt;/tbody&gt;
            &lt;/table&gt;
            &lt;/td&gt;
        &lt;/tr&gt;
    &lt;/tbody&gt;
&lt;/table&gt;
&lt;/div&gt;
&lt;div class=&quot;attribNm lvl1&quot;&gt;Nominal Delay:&lt;/div&gt;
&lt;div class=&quot;lvl2&quot;&gt;
&lt;table class=&quot;tblOuterCls&quot; cellspacing=&quot;0&quot; cellpadding=&quot;0&quot; border=&quot;0&quot;&gt;
    &lt;tbody&gt;
        &lt;tr&gt;
            &lt;td&gt;
            &lt;table class=&quot;tblInnerCls&quot; cellspacing=&quot;1&quot; cellpadding=&quot;0&quot;&gt;
                &lt;tbody&gt;
                    &lt;tr&gt;
                        &lt;td class=&quot;tblHdrCls&quot;&gt;Delay (ns/ft)&lt;/td&gt;
                    &lt;/tr&gt;
                    &lt;tr&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;1.2&lt;/td&gt;
                    &lt;/tr&gt;
                &lt;/tbody&gt;
            &lt;/table&gt;
            &lt;/td&gt;
        &lt;/tr&gt;
    &lt;/tbody&gt;
&lt;/table&gt;
&lt;/div&gt;
&lt;div class=&quot;attribNm lvl1&quot;&gt;Nom. Conductor DC Resistance:&lt;/div&gt;
&lt;div class=&quot;lvl2&quot;&gt;
&lt;table class=&quot;tblOuterCls&quot; cellspacing=&quot;0&quot; cellpadding=&quot;0&quot; border=&quot;0&quot;&gt;
    &lt;tbody&gt;
        &lt;tr&gt;
            &lt;td&gt;
            &lt;table class=&quot;tblInnerCls&quot; cellspacing=&quot;1&quot; cellpadding=&quot;0&quot;&gt;
                &lt;tbody&gt;
                    &lt;tr&gt;
                        &lt;td class=&quot;tblHdrCls&quot;&gt;DCR @ 20&amp;deg;C (Ohm/1000 ft)&lt;/td&gt;
                    &lt;/tr&gt;
                    &lt;tr&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;6.4&lt;/td&gt;
                    &lt;/tr&gt;
                &lt;/tbody&gt;
            &lt;/table&gt;
            &lt;/td&gt;
        &lt;/tr&gt;
    &lt;/tbody&gt;
&lt;/table&gt;
&lt;/div&gt;
&lt;div class=&quot;attribNm lvl1&quot;&gt;Nominal Outer Shield DC Resistance:&lt;/div&gt;
&lt;div class=&quot;lvl2&quot;&gt;
&lt;table class=&quot;tblOuterCls&quot; cellspacing=&quot;0&quot; cellpadding=&quot;0&quot; border=&quot;0&quot;&gt;
    &lt;tbody&gt;
        &lt;tr&gt;
            &lt;td&gt;
            &lt;table class=&quot;tblInnerCls&quot; cellspacing=&quot;1&quot; cellpadding=&quot;0&quot;&gt;
                &lt;tbody&gt;
                    &lt;tr&gt;
                        &lt;td class=&quot;tblHdrCls&quot;&gt;DCR @ 20&amp;deg;C (Ohm/1000 ft)&lt;/td&gt;
                    &lt;/tr&gt;
                    &lt;tr&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;9&lt;/td&gt;
                    &lt;/tr&gt;
                &lt;/tbody&gt;
            &lt;/table&gt;
            &lt;/td&gt;
        &lt;/tr&gt;
    &lt;/tbody&gt;
&lt;/table&gt;
&lt;/div&gt;
&lt;div class=&quot;attribNm lvl1&quot;&gt;Nom. Attenuation:&lt;/div&gt;
&lt;div class=&quot;lvl2&quot;&gt;
&lt;table class=&quot;tblOuterCls&quot; cellspacing=&quot;0&quot; cellpadding=&quot;0&quot; border=&quot;0&quot;&gt;
    &lt;tbody&gt;
        &lt;tr&gt;
            &lt;td&gt;
            &lt;table class=&quot;tblInnerCls&quot; cellspacing=&quot;1&quot; cellpadding=&quot;0&quot;&gt;
                &lt;tbody&gt;
                    &lt;tr&gt;
                        &lt;td class=&quot;tblHdrCls&quot;&gt;Freq. (MHz)&lt;/td&gt;
                        &lt;td class=&quot;tblHdrCls&quot;&gt;Attenuation (dB/100 ft.)&lt;/td&gt;
                    &lt;/tr&gt;
                    &lt;tr&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;5&lt;/td&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;.5&lt;/td&gt;
                    &lt;/tr&gt;
                    &lt;tr&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;55&lt;/td&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;1.4&lt;/td&gt;
                    &lt;/tr&gt;
                    &lt;tr&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;211&lt;/td&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;2.6&lt;/td&gt;
                    &lt;/tr&gt;
                    &lt;tr&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;500&lt;/td&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;4.1&lt;/td&gt;
                    &lt;/tr&gt;
                    &lt;tr&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;750&lt;/td&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;5.1&lt;/td&gt;
                    &lt;/tr&gt;
                    &lt;tr&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;862&lt;/td&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;5.5&lt;/td&gt;
                    &lt;/tr&gt;
                    &lt;tr&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;1000&lt;/td&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;6.0&lt;/td&gt;
                    &lt;/tr&gt;
                    &lt;tr&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;1450&lt;/td&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;7.8&lt;/td&gt;
                    &lt;/tr&gt;
                    &lt;tr&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;1800&lt;/td&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;8.6&lt;/td&gt;
                    &lt;/tr&gt;
                    &lt;tr&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;2250&lt;/td&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;9.8&lt;/td&gt;
                    &lt;/tr&gt;
                    &lt;tr&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;3000&lt;/td&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;11.3&lt;/td&gt;
                    &lt;/tr&gt;
                &lt;/tbody&gt;
            &lt;/table&gt;
            &lt;/td&gt;
        &lt;/tr&gt;
    &lt;/tbody&gt;
&lt;/table&gt;
&lt;/div&gt;
&lt;div class=&quot;attribNm lvl1&quot;&gt;Max. Attenuation:&lt;/div&gt;
&lt;div class=&quot;lvl2&quot;&gt;
&lt;table class=&quot;tblOuterCls&quot; cellspacing=&quot;0&quot; cellpadding=&quot;0&quot; border=&quot;0&quot;&gt;
    &lt;tbody&gt;
        &lt;tr&gt;
            &lt;td&gt;
            &lt;table class=&quot;tblInnerCls&quot; cellspacing=&quot;1&quot; cellpadding=&quot;0&quot;&gt;
                &lt;tbody&gt;
                    &lt;tr&gt;
                        &lt;td class=&quot;tblHdrCls&quot;&gt;Freq. (MHz)&lt;/td&gt;
                        &lt;td class=&quot;tblHdrCls&quot;&gt;Attenuation (dB/100 ft.)&lt;/td&gt;
                    &lt;/tr&gt;
                    &lt;tr&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;5&lt;/td&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;0.67&lt;/td&gt;
                    &lt;/tr&gt;
                    &lt;tr&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;55&lt;/td&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;1.60&lt;/td&gt;
                    &lt;/tr&gt;
                    &lt;tr&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;211&lt;/td&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;2.87&lt;/td&gt;
                    &lt;/tr&gt;
                    &lt;tr&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;500&lt;/td&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;4.48&lt;/td&gt;
                    &lt;/tr&gt;
                    &lt;tr&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;750&lt;/td&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;5.59&lt;/td&gt;
                    &lt;/tr&gt;
                    &lt;tr&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;862&lt;/td&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;5.98&lt;/td&gt;
                    &lt;/tr&gt;
                    &lt;tr&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;1000&lt;/td&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;6.54&lt;/td&gt;
                    &lt;/tr&gt;
                    &lt;tr&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;1450&lt;/td&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;8.00&lt;/td&gt;
                    &lt;/tr&gt;
                    &lt;tr&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;1800&lt;/td&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;8.80&lt;/td&gt;
                    &lt;/tr&gt;
                    &lt;tr&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;2250&lt;/td&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;10.0&lt;/td&gt;
                    &lt;/tr&gt;
                    &lt;tr&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;3000&lt;/td&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;11.9&lt;/td&gt;
                    &lt;/tr&gt;
                &lt;/tbody&gt;
            &lt;/table&gt;
            &lt;/td&gt;
        &lt;/tr&gt;
    &lt;/tbody&gt;
&lt;/table&gt;
&lt;/div&gt;
&lt;div class=&quot;attribNm lvl1&quot;&gt;Max. Operating Voltage - UL:&lt;/div&gt;
&lt;div class=&quot;lvl2&quot;&gt;
&lt;table class=&quot;tblOuterCls&quot; cellspacing=&quot;0&quot; cellpadding=&quot;0&quot; border=&quot;0&quot;&gt;
    &lt;tbody&gt;
        &lt;tr&gt;
            &lt;td&gt;
            &lt;table class=&quot;tblInnerCls&quot; cellspacing=&quot;1&quot; cellpadding=&quot;0&quot;&gt;
                &lt;tbody&gt;
                    &lt;tr&gt;
                        &lt;td class=&quot;tblHdrCls&quot;&gt;Voltage&lt;/td&gt;
                    &lt;/tr&gt;
                    &lt;tr&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;300 V RMS&lt;/td&gt;
                    &lt;/tr&gt;
                &lt;/tbody&gt;
            &lt;/table&gt;
            &lt;/td&gt;
        &lt;/tr&gt;
    &lt;/tbody&gt;
&lt;/table&gt;
&lt;/div&gt;
&lt;div class=&quot;attribNm lvl1&quot;&gt;Minimum Structural Return Loss:&lt;/div&gt;
&lt;div class=&quot;lvl2&quot;&gt;
&lt;table class=&quot;tblOuterCls&quot; cellspacing=&quot;0&quot; cellpadding=&quot;0&quot; border=&quot;0&quot;&gt;
    &lt;tbody&gt;
        &lt;tr&gt;
            &lt;td&gt;
            &lt;table class=&quot;tblInnerCls&quot; cellspacing=&quot;1&quot; cellpadding=&quot;0&quot;&gt;
                &lt;tbody&gt;
                    &lt;tr&gt;
                        &lt;td class=&quot;tblHdrCls&quot;&gt;Start Freq. (MHz)&lt;/td&gt;
                        &lt;td class=&quot;tblHdrCls&quot;&gt;Stop Freq. (MHz)&lt;/td&gt;
                        &lt;td class=&quot;tblHdrCls&quot;&gt;Min. SRL (dB)&lt;/td&gt;
                    &lt;/tr&gt;
                    &lt;tr&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;950&lt;/td&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;2250&lt;/td&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;15&lt;/td&gt;
                    &lt;/tr&gt;
                    &lt;tr&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;2250&lt;/td&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;3000&lt;/td&gt;
                        &lt;td class=&quot;tblTxtCls&quot;&gt;10&lt;/td&gt;
                    &lt;/tr&gt;
                &lt;/tbody&gt;
            &lt;/table&gt;
            &lt;/td&gt;
        &lt;/tr&gt;
    &lt;/tbody&gt;
&lt;/table&gt;
&lt;/div&gt;
&lt;div class=&quot;catSub lvl1&quot;&gt;Sweep Test&lt;/div&gt;
&lt;div class=&quot;attribTabCls&quot;&gt;
&lt;table width=&quot;95%&quot; border=&quot;0&quot;&gt;
    &lt;tbody&gt;
        &lt;tr&gt;
            &lt;td class=&quot;attribNm&quot; style=&quot;width: 40%&quot;&gt;Sweep Testing:&lt;/td&gt;
            &lt;td class=&quot;attribVal&quot; style=&quot;width: 60%&quot;&gt;950 MHz - 3 GHz&lt;/td&gt;
        &lt;/tr&gt;
    &lt;/tbody&gt;
&lt;/table&gt;
&lt;/div&gt;
&lt;div style=&quot;font-size: 9pt; margin-left: 2.5%; width: 94%; color: #053177; margin-right: 2.5%; font-family: Arial, Helvetica, sans-serif&quot;&gt;Revision Number: 4 &amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;Revision Date: 04-25-2008&lt;/div&gt;','2012-02-19 19:12:33','y',NULL,NULL,NULL,'n',NULL,NULL,NULL,NULL,'2012-02-15 17:25:44','n',NULL,NULL,12,12,'y','y',NULL,NULL,'n',NULL,'y','n','n',NULL,NULL)
INSERT INTO [firecms_content] ( [id], [content_type], [content_url], [content_filename], [content_parent], [content_title], [content_body], [updated_on], [is_active], [content_subtype], [content_subtype_value], [content_layout_file], [is_home], [content_meta_title], [content_meta_description], [content_meta_keywords], [content_meta_other_code], [created_on], [is_featured], [original_link], [content_description], [created_by], [edited_by], [is_pinged], [comments_enabled], [content_layout_name], [content_layout_style], [content_filename_sync_with_editor], [content_body_filename], [visible_on_frontend], [is_special], [require_login], [active_site_template], [expires_on]) VALUES (3624,'post','easy-up-pitch-pad-kit-3-pads-6-5-16inch-x-2-1-2inch-lag-screws',NULL,3484,'Easy Up, Pitch Pad Kit, 3 Pads, 6 -5-16inch x 2 1-2inch Lag Screws','&lt;p&gt;&lt;font face=&quot;TimesNewRomanPSMT&quot; color=&quot;#1e201b&quot;&gt;&lt;font face=&quot;TimesNewRomanPSMT&quot; color=&quot;#1e201b&quot;&gt;
&lt;p align=&quot;left&quot;&gt;&amp;nbsp;&lt;/p&gt;
&lt;/font&gt;&lt;/font&gt;&lt;/p&gt;
&lt;p align=&quot;left&quot;&gt;&lt;font face=&quot;Swiss721BT-LightCondensed&quot; size=&quot;2&quot;&gt;&lt;font face=&quot;Swiss721BT-LightCondensed&quot; size=&quot;2&quot;&gt;&lt;span id=&quot;1260218507094E&quot; style=&quot;display: none&quot;&gt;&amp;bull; For 2&amp;rsquo; Roof Tower&lt;br /&gt;
&amp;bull; Includes Bolts &amp;amp; Sealant Pads&lt;br /&gt;
&amp;bull; Prevents Leaks&lt;/span&gt;&lt;/font&gt;&lt;/font&gt;&lt;/p&gt;','2012-02-19 19:12:33','y',NULL,NULL,NULL,'n',NULL,NULL,NULL,NULL,'2012-02-15 17:25:46','n',NULL,NULL,12,12,'y','y',NULL,NULL,'n',NULL,'y','n','n',NULL,NULL)
INSERT INTO [firecms_content] ( [id], [content_type], [content_url], [content_filename], [content_parent], [content_title], [content_body], [updated_on], [is_active], [content_subtype], [content_subtype_value], [content_layout_file], [is_home], [content_meta_title], [content_meta_description], [content_meta_keywords], [content_meta_other_code], [created_on], [is_featured], [original_link], [content_description], [created_by], [edited_by], [is_pinged], [comments_enabled], [content_layout_name], [content_layout_style], [content_filename_sync_with_editor], [content_body_filename], [visible_on_frontend], [is_special], [require_login], [active_site_template], [expires_on]) VALUES (3625,'post','attachment-kit-for-fib107',NULL,3484,'Attachment Kit For FIB107','&lt;p&gt;&amp;bull; For Fish Rod FIB107 attachments are 1/4&amp;quot;&lt;/p&gt;
&lt;p&gt;&lt;strong&gt;INCLUDES:&lt;br /&gt;
&lt;/strong&gt;&amp;nbsp;&amp;bull; 1 Magnetic head&lt;br /&gt;
&amp;nbsp;&amp;bull; 1 Fish hook&lt;br /&gt;
&amp;nbsp;&amp;bull; 2 Tips&lt;br /&gt;
&amp;nbsp;&amp;bull; 1 Pull ring&lt;br /&gt;
&amp;nbsp;&amp;bull; 1 Directional tool&lt;br /&gt;
&amp;nbsp;&amp;bull; 3 Dividers&lt;br /&gt;
&amp;nbsp;&amp;bull; 1 Ball chain (10&amp;rdquo;)&lt;/p&gt;','2012-02-19 19:12:34','y',NULL,NULL,NULL,'n',NULL,NULL,NULL,NULL,'2012-02-15 17:25:48','n',NULL,NULL,12,12,'y','y',NULL,NULL,'n',NULL,'y','n','n',NULL,NULL)
CREATE TABLE [firecms_content_custom_fields] ([id] BIGINT,[to_table] VARCHAR(1500),[to_table_id] VARCHAR(155),[custom_field_name] TEXT,[custom_field_value] TEXT,[field_order] BIGINT,[custom_field_type] VARCHAR(1500),[custom_field_required] VARCHAR(100),[custom_field_values] TEXT,[field_for] VARCHAR(150),[custom_field_field_for] VARCHAR(150),[custom_field_is_active] CHAR(1),[custom_field_help_text] TEXT
);
CREATE TABLE [firecms_countries] ([id] BIGINT,[code] CHAR(3),[name] CHAR(52),[continent] CHAR(13),[surfacearea] REAL,[population] BIGINT,[localname] CHAR(252)
);
INSERT INTO [firecms_countries] ( [id], [code], [name], [continent], [surfacearea], [population], [localname]) VALUES (1,'AFG','Afghanistan','Asia',652090,22720000,'Afganistan/Afqanestan')
INSERT INTO [firecms_countries] ( [id], [code], [name], [continent], [surfacearea], [population], [localname]) VALUES (2,'NLD','Netherlands','Europe',41526,15864000,'Nederland')
INSERT INTO [firecms_countries] ( [id], [code], [name], [continent], [surfacearea], [population], [localname]) VALUES (3,'ANT','Netherlands Antilles','North America',800,217000,'Nederlandse Antillen')
INSERT INTO [firecms_countries] ( [id], [code], [name], [continent], [surfacearea], [population], [localname]) VALUES (4,'ALB','Albania','Europe',28748,3401200,'Shqipëria')
INSERT INTO [firecms_countries] ( [id], [code], [name], [continent], [surfacearea], [population], [localname]) VALUES (5,'DZA','Algeria','Africa',2381741,31471000,'Al-Jaza’ir/Algérie')
INSERT INTO [firecms_countries] ( [id], [code], [name], [continent], [surfacearea], [population], [localname]) VALUES (6,'ASM','American Samoa','Oceania',199,68000,'Amerika Samoa')
INSERT INTO [firecms_countries] ( [id], [code], [name], [continent], [surfacearea], [population], [localname]) VALUES (7,'AND','Andorra','Europe',468,78000,'Andorra')
INSERT INTO [firecms_countries] ( [id], [code], [name], [continent], [surfacearea], [population], [localname]) VALUES (8,'AGO','Angola','Africa',1246700,12878000,'Angola')
INSERT INTO [firecms_countries] ( [id], [code], [name], [continent], [surfacearea], [population], [localname]) VALUES (9,'AIA','Anguilla','North America',96,8000,'Anguilla')
INSERT INTO [firecms_countries] ( [id], [code], [name], [continent], [surfacearea], [population], [localname]) VALUES (10,'ATG','Antigua and Barbuda','North America',442,68000,'Antigua and Barbuda')
INSERT INTO [firecms_countries] ( [id], [code], [name], [continent], [surfacearea], [population], [localname]) VALUES (11,'ARE','United Arab Emirates','Asia',83600,2441000,'Al-Imarat al-´Arabiya al-Muttahida')
INSERT INTO [firecms_countries] ( [id], [code], [name], [continent], [surfacearea], [population], [localname]) VALUES (12,'ARG','Argentina','South America',2780400,37032000,'Argentina')
INSERT INTO [firecms_countries] ( [id], [code], [name], [continent], [surfacearea], [population], [localname]) VALUES (13,'ARM','Armenia','Asia',29800,3520000,'Hajastan')
INSERT INTO [firecms_countries] ( [id], [code], [name], [continent], [surfacearea], [population], [localname]) VALUES (14,'ABW','Aruba','North America',193,103000,'Aruba')
INSERT INTO [firecms_countries] ( [id], [code], [name], [continent], [surfacearea], [population], [localname]) VALUES (15,'AUS','Australia','Oceania',7741220,18886000,'Australia')
INSERT INTO [firecms_countries] ( [id], [code], [name], [continent], [surfacearea], [population], [localname]) VALUES (16,'AZE','Azerbaijan','Asia',86600,7734000,'Azärbaycan')
INSERT INTO [firecms_countries] ( [id], [code], [name], [continent], [surfacearea], [population], [localname]) VALUES (17,'BHS','Bahamas','North America',13878,307000,'The Bahamas')
INSERT INTO [firecms_countries] ( [id], [code], [name], [continent], [surfacearea], [population], [localname]) VALUES (18,'BHR','Bahrain','Asia',694,617000,'Al-Bahrayn')
INSERT INTO [firecms_countries] ( [id], [code], [name], [continent], [surfacearea], [population], [localname]) VALUES (19,'BGD','Bangladesh','Asia',143998,129155000,'Bangladesh')
INSERT INTO [firecms_countries] ( [id], [code], [name], [continent], [surfacearea], [population], [localname]) VALUES (20,'BRB','Barbados','North America',430,270000,'Barbados')
INSERT INTO [firecms_countries] ( [id], [code], [name], [continent], [surfacearea], [population], [localname]) VALUES (21,'BEL','Belgium','Europe',30518,10239000,'België/Belgique')
INSERT INTO [firecms_countries] ( [id], [code], [name], [continent], [surfacearea], [population], [localname]) VALUES (22,'BLZ','Belize','North America',22696,241000,'Belize')
INSERT INTO [firecms_countries] ( [id], [code], [name], [continent], [surfacearea], [population], [localname]) VALUES (23,'BEN','Benin','Africa',112622,6097000,'Bénin')
INSERT INTO [firecms_countries] ( [id], [code], [name], [continent], [surfacearea], [population], [localname]) VALUES (24,'BMU','Bermuda','North America',53,65000,'Bermuda')
INSERT INTO [firecms_countries] ( [id], [code], [name], [continent], [surfacearea], [population], [localname]) VALUES (25,'BTN','Bhutan','Asia',47000,2124000,'Druk-Yul')
INSERT INTO [firecms_countries] ( [id], [code], [name], [continent], [surfacearea], [population], [localname]) VALUES (26,'BOL','Bolivia','South America',1098581,8329000,'Bolivia')
INSERT INTO [firecms_countries] ( [id], [code], [name], [continent], [surfacearea], [population], [localname]) VALUES (27,'BIH','Bosnia and Herzegovina','Europe',51197,3972000,'Bosna i Hercegovina')
INSERT INTO [firecms_countries] ( [id], [code], [name], [continent], [surfacearea], [population], [localname]) VALUES (28,'BWA','Botswana','Africa',581730,1622000,'Botswana')
INSERT INTO [firecms_countries] ( [id], [code], [name], [continent], [surfacearea], [population], [localname]) VALUES (29,'BRA','Brazil','South America',8547403,170115000,'Brasil')
INSERT INTO [firecms_countries] ( [id], [code], [name], [continent], [surfacearea], [population], [localname]) VALUES (30,'GBR','United Kingdom','Europe',242900,59623400,'United Kingdom')
INSERT INTO [firecms_countries] ( [id], [code], [name], [continent], [surfacearea], [population], [localname]) VALUES (31,'VGB','Virgin Islands, British','North America',151,21000,'British Virgin Islands')
INSERT INTO [firecms_countries] ( [id], [code], [name], [continent], [surfacearea], [population], [localname]) VALUES (32,'BRN','Brunei','Asia',5765,328000,'Brunei Darussalam')
INSERT INTO [firecms_countries] ( [id], [code], [name], [continent], [surfacearea], [population], [localname]) VALUES (33,'BGR','Bulgaria','Europe',110994,8190900,'Balgarija')
INSERT INTO [firecms_countries] ( [id], [code], [name], [continent], [surfacearea], [population], [localname]) VALUES (34,'BFA','Burkina Faso','Africa',274000,11937000,'Burkina Faso')
INSERT INTO [firecms_countries] ( [id], [code], [name], [continent], [surfacearea], [population], [localname]) VALUES (35,'BDI','Burundi','Africa',27834,6695000,'Burundi/Uburundi')
INSERT INTO [firecms_countries] ( [id], [code], [name], [continent], [surfacearea], [population], [localname]) VALUES (36,'CYM','Cayman Islands','North America',264,38000,'Cayman Islands')
INSERT INTO [firecms_countries] ( [id], [code], [name], [continent], [surfacearea], [population], [localname]) VALUES (37,'CHL','Chile','South America',756626,15211000,'Chile')
INSERT INTO [firecms_countries] ( [id], [code], [name], [continent], [surfacearea], [population], [localname]) VALUES (38,'COK','Cook Islands','Oceania',236,20000,'The Cook Islands')
INSERT INTO [firecms_countries] ( [id], [code], [name], [continent], [surfacearea], [population], [localname]) VALUES (39,'CRI','Costa Rica','North America',51100,4023000,'Costa Rica')
INSERT INTO [firecms_countries] ( [id], [code], [name], [continent], [surfacearea], [population], [localname]) VALUES (40,'DJI','Djibouti','Africa',23200,638000,'Djibouti/Jibuti')
INSERT INTO [firecms_countries] ( [id], [code], [name], [continent], [surfacearea], [population], [localname]) VALUES (41,'DMA','Dominica','North America',751,71000,'Dominica')
INSERT INTO [firecms_countries] ( [id], [code], [name], [continent], [surfacearea], [population], [localname]) VALUES (42,'DOM','Dominican Republic','North America',48511,8495000,'República Dominicana')
INSERT INTO [firecms_countries] ( [id], [code], [name], [continent], [surfacearea], [population], [localname]) VALUES (43,'ECU','Ecuador','South America',283561,12646000,'Ecuador')
INSERT INTO [firecms_countries] ( [id], [code], [name], [continent], [surfacearea], [population], [localname]) VALUES (44,'EGY','Egypt','Africa',1001449,68470000,'Misr')
INSERT INTO [firecms_countries] ( [id], [code], [name], [continent], [surfacearea], [population], [localname]) VALUES (45,'SLV','El Salvador','North America',21041,6276000,'El Salvador')
INSERT INTO [firecms_countries] ( [id], [code], [name], [continent], [surfacearea], [population], [localname]) VALUES (46,'ERI','Eritrea','Africa',117600,3850000,'Ertra')
INSERT INTO [firecms_countries] ( [id], [code], [name], [continent], [surfacearea], [population], [localname]) VALUES (47,'ESP','Spain','Europe',505992,39441700,'España')
INSERT INTO [firecms_countries] ( [id], [code], [name], [continent], [surfacearea], [population], [localname]) VALUES (48,'ZAF','South Africa','Africa',1221037,40377000,'South Africa')
INSERT INTO [firecms_countries] ( [id], [code], [name], [continent], [surfacearea], [population], [localname]) VALUES (49,'ETH','Ethiopia','Africa',1104300,62565000,'YeItyop´iya')
INSERT INTO [firecms_countries] ( [id], [code], [name], [continent], [surfacearea], [population], [localname]) VALUES (50,'FLK','Falkland Islands','South America',12173,2000,'Falkland Islands')
CREATE TABLE [firecms_cronjobs] ([id] BIGINT,[cronjob_group] VARCHAR(1500),[cronjob_name] VARCHAR(1500),[model_name] VARCHAR(1500),[function_to_execute] VARCHAR(1500),[interval_minutes] BIGINT,[is_active] BIGINT,[updated_on] DATETIME,[last_run] DATETIME
);
CREATE TABLE [firecms_followers] ([id] BIGINT,[follower_id] BIGINT,[user_id] BIGINT,[is_special] CHAR(1),[updated_on] DATETIME,[created_on] DATETIME,[created_by] BIGINT,[edited_by] BIGINT,[is_approved] CHAR(1)
);
INSERT INTO [firecms_followers] ( [id], [follower_id], [user_id], [is_special], [updated_on], [created_on], [created_by], [edited_by], [is_approved]) VALUES (2,1738,1761,'n','2010-12-18 09:25:17','2010-12-18 09:25:17',1761,1761,'n')
INSERT INTO [firecms_followers] ( [id], [follower_id], [user_id], [is_special], [updated_on], [created_on], [created_by], [edited_by], [is_approved]) VALUES (7,1726,1757,'n','2010-12-18 09:35:49','2010-12-18 09:35:49',1757,1757,'y')
INSERT INTO [firecms_followers] ( [id], [follower_id], [user_id], [is_special], [updated_on], [created_on], [created_by], [edited_by], [is_approved]) VALUES (8,1732,1757,'n','2010-12-18 09:35:50','2010-12-18 09:35:50',1757,1757,'y')
INSERT INTO [firecms_followers] ( [id], [follower_id], [user_id], [is_special], [updated_on], [created_on], [created_by], [edited_by], [is_approved]) VALUES (9,48,1757,'n','2010-12-18 09:35:51','2010-12-18 09:35:51',1757,1757,'n')
INSERT INTO [firecms_followers] ( [id], [follower_id], [user_id], [is_special], [updated_on], [created_on], [created_by], [edited_by], [is_approved]) VALUES (10,582,1757,'n','2010-12-18 09:35:51','2010-12-18 09:35:51',1757,1757,'n')
INSERT INTO [firecms_followers] ( [id], [follower_id], [user_id], [is_special], [updated_on], [created_on], [created_by], [edited_by], [is_approved]) VALUES (11,1771,1757,'n','2010-12-18 04:54:38','2010-12-18 04:54:38',1757,1757,'n')
INSERT INTO [firecms_followers] ( [id], [follower_id], [user_id], [is_special], [updated_on], [created_on], [created_by], [edited_by], [is_approved]) VALUES (13,1731,1757,'n','2010-12-18 04:54:41','2010-12-18 04:54:41',1757,1757,'y')
INSERT INTO [firecms_followers] ( [id], [follower_id], [user_id], [is_special], [updated_on], [created_on], [created_by], [edited_by], [is_approved]) VALUES (14,51,1757,'n','2010-12-18 04:54:50','2010-12-18 04:54:50',1757,1757,'n')
INSERT INTO [firecms_followers] ( [id], [follower_id], [user_id], [is_special], [updated_on], [created_on], [created_by], [edited_by], [is_approved]) VALUES (15,386,1757,'n','2010-12-18 04:54:53','2010-12-18 04:54:53',1757,1757,'n')
INSERT INTO [firecms_followers] ( [id], [follower_id], [user_id], [is_special], [updated_on], [created_on], [created_by], [edited_by], [is_approved]) VALUES (18,1769,1757,'n','2010-12-19 06:54:48','2010-12-19 06:54:48',1757,1757,'y')
INSERT INTO [firecms_followers] ( [id], [follower_id], [user_id], [is_special], [updated_on], [created_on], [created_by], [edited_by], [is_approved]) VALUES (19,1757,1769,'n','2010-12-19 06:55:03','2010-12-19 06:55:03',1769,1769,'y')
INSERT INTO [firecms_followers] ( [id], [follower_id], [user_id], [is_special], [updated_on], [created_on], [created_by], [edited_by], [is_approved]) VALUES (26,1760,1757,'n','2010-12-19 08:46:56','2010-12-19 08:46:56',1757,1757,'n')
INSERT INTO [firecms_followers] ( [id], [follower_id], [user_id], [is_special], [updated_on], [created_on], [created_by], [edited_by], [is_approved]) VALUES (29,1773,1774,'n','2010-12-19 03:21:36','2010-12-19 03:21:36',1774,1774,'n')
INSERT INTO [firecms_followers] ( [id], [follower_id], [user_id], [is_special], [updated_on], [created_on], [created_by], [edited_by], [is_approved]) VALUES (35,1774,1731,'n','2010-12-20 03:43:42','2010-12-20 03:43:31',1731,1731,'n')
INSERT INTO [firecms_followers] ( [id], [follower_id], [user_id], [is_special], [updated_on], [created_on], [created_by], [edited_by], [is_approved]) VALUES (36,1769,1731,'n','2010-12-20 03:43:48','2010-12-20 03:43:48',1731,1731,'y')
INSERT INTO [firecms_followers] ( [id], [follower_id], [user_id], [is_special], [updated_on], [created_on], [created_by], [edited_by], [is_approved]) VALUES (37,1773,1731,'n','2010-12-20 03:43:53','2010-12-20 03:43:53',1731,1731,'n')
INSERT INTO [firecms_followers] ( [id], [follower_id], [user_id], [is_special], [updated_on], [created_on], [created_by], [edited_by], [is_approved]) VALUES (38,1757,1731,'n','2010-12-20 06:01:53','2010-12-20 06:01:42',1731,1731,'y')
INSERT INTO [firecms_followers] ( [id], [follower_id], [user_id], [is_special], [updated_on], [created_on], [created_by], [edited_by], [is_approved]) VALUES (42,1735,1726,'n','2010-12-20 09:59:32','2010-12-20 09:59:32',1726,1726,'y')
INSERT INTO [firecms_followers] ( [id], [follower_id], [user_id], [is_special], [updated_on], [created_on], [created_by], [edited_by], [is_approved]) VALUES (43,1774,1726,'n','2010-12-20 10:01:13','2010-12-20 10:01:13',1726,1726,'y')
INSERT INTO [firecms_followers] ( [id], [follower_id], [user_id], [is_special], [updated_on], [created_on], [created_by], [edited_by], [is_approved]) VALUES (44,1757,1726,'n','2010-12-26 06:20:31','2010-12-20 10:01:24',1726,1726,'y')
INSERT INTO [firecms_followers] ( [id], [follower_id], [user_id], [is_special], [updated_on], [created_on], [created_by], [edited_by], [is_approved]) VALUES (46,1759,1761,'n','2010-12-20 10:35:10','2010-12-20 10:35:10',1761,1761,'n')
INSERT INTO [firecms_followers] ( [id], [follower_id], [user_id], [is_special], [updated_on], [created_on], [created_by], [edited_by], [is_approved]) VALUES (47,1738,1726,'n','2010-12-20 06:00:16','2010-12-20 06:00:16',1726,1726,'n')
INSERT INTO [firecms_followers] ( [id], [follower_id], [user_id], [is_special], [updated_on], [created_on], [created_by], [edited_by], [is_approved]) VALUES (48,1774,1757,'n','2010-12-21 03:08:18','2010-12-21 03:08:18',1757,1757,'y')
INSERT INTO [firecms_followers] ( [id], [follower_id], [user_id], [is_special], [updated_on], [created_on], [created_by], [edited_by], [is_approved]) VALUES (58,1766,1726,'n','2010-12-21 11:06:31','2010-12-21 11:06:31',1726,1726,'y')
INSERT INTO [firecms_followers] ( [id], [follower_id], [user_id], [is_special], [updated_on], [created_on], [created_by], [edited_by], [is_approved]) VALUES (59,1775,1726,'n','2010-12-21 11:09:31','2010-12-21 11:09:31',1726,1726,'n')
INSERT INTO [firecms_followers] ( [id], [follower_id], [user_id], [is_special], [updated_on], [created_on], [created_by], [edited_by], [is_approved]) VALUES (60,1791,1758,'n','2010-12-21 11:57:52','2010-12-21 11:57:52',1758,1758,'n')
INSERT INTO [firecms_followers] ( [id], [follower_id], [user_id], [is_special], [updated_on], [created_on], [created_by], [edited_by], [is_approved]) VALUES (61,1795,1761,'n','2010-12-21 02:37:39','2010-12-21 02:37:39',1761,1761,'n')
INSERT INTO [firecms_followers] ( [id], [follower_id], [user_id], [is_special], [updated_on], [created_on], [created_by], [edited_by], [is_approved]) VALUES (62,1726,1766,'n','2010-12-22 03:31:30','2010-12-22 03:31:30',1766,1766,'y')
INSERT INTO [firecms_followers] ( [id], [follower_id], [user_id], [is_special], [updated_on], [created_on], [created_by], [edited_by], [is_approved]) VALUES (66,1774,1769,'n','2010-12-22 10:17:12','2010-12-22 10:17:12',1769,1769,'y')
INSERT INTO [firecms_followers] ( [id], [follower_id], [user_id], [is_special], [updated_on], [created_on], [created_by], [edited_by], [is_approved]) VALUES (67,1731,1769,'n','2010-12-22 10:17:21','2010-12-22 10:17:21',1769,1769,'y')
INSERT INTO [firecms_followers] ( [id], [follower_id], [user_id], [is_special], [updated_on], [created_on], [created_by], [edited_by], [is_approved]) VALUES (69,126,1761,'n','2010-12-22 07:10:32','2010-12-22 07:10:32',1761,1761,'n')
INSERT INTO [firecms_followers] ( [id], [follower_id], [user_id], [is_special], [updated_on], [created_on], [created_by], [edited_by], [is_approved]) VALUES (70,26,1761,'n','2010-12-22 07:13:29','2010-12-22 07:13:19',1761,1761,'n')
INSERT INTO [firecms_followers] ( [id], [follower_id], [user_id], [is_special], [updated_on], [created_on], [created_by], [edited_by], [is_approved]) VALUES (71,1766,1761,'n','2010-12-22 07:36:03','2010-12-22 07:36:03',1761,1761,'n')
INSERT INTO [firecms_followers] ( [id], [follower_id], [user_id], [is_special], [updated_on], [created_on], [created_by], [edited_by], [is_approved]) VALUES (72,1726,1761,'n','2010-12-29 04:26:01','2010-12-22 07:36:27',1761,1761,'y')
INSERT INTO [firecms_followers] ( [id], [follower_id], [user_id], [is_special], [updated_on], [created_on], [created_by], [edited_by], [is_approved]) VALUES (73,1798,1757,'n','2010-12-23 07:20:33','2010-12-23 07:20:33',1757,1757,'n')
INSERT INTO [firecms_followers] ( [id], [follower_id], [user_id], [is_special], [updated_on], [created_on], [created_by], [edited_by], [is_approved]) VALUES (74,1761,1726,'n','2010-12-23 11:47:35','2010-12-23 11:47:23',1726,1726,'y')
INSERT INTO [firecms_followers] ( [id], [follower_id], [user_id], [is_special], [updated_on], [created_on], [created_by], [edited_by], [is_approved]) VALUES (75,1774,1761,'n','2010-12-25 05:35:50','2010-12-25 05:35:39',1761,1761,'n')
INSERT INTO [firecms_followers] ( [id], [follower_id], [user_id], [is_special], [updated_on], [created_on], [created_by], [edited_by], [is_approved]) VALUES (77,1731,1726,'n','2010-12-26 07:51:14','2010-12-26 07:50:54',1726,1726,'n')
INSERT INTO [firecms_followers] ( [id], [follower_id], [user_id], [is_special], [updated_on], [created_on], [created_by], [edited_by], [is_approved]) VALUES (99,1761,1498,'n','2010-12-27 11:07:01','2010-12-27 11:07:01',1498,1498,'y')
INSERT INTO [firecms_followers] ( [id], [follower_id], [user_id], [is_special], [updated_on], [created_on], [created_by], [edited_by], [is_approved]) VALUES (100,1726,1498,'n','2010-12-27 11:07:10','2010-12-27 11:07:10',1498,1498,'y')
INSERT INTO [firecms_followers] ( [id], [follower_id], [user_id], [is_special], [updated_on], [created_on], [created_by], [edited_by], [is_approved]) VALUES (101,1731,1498,'n','2010-12-27 11:23:53','2010-12-27 11:23:53',1498,1498,'n')
INSERT INTO [firecms_followers] ( [id], [follower_id], [user_id], [is_special], [updated_on], [created_on], [created_by], [edited_by], [is_approved]) VALUES (102,1795,1498,'n','2010-12-27 11:24:12','2010-12-27 11:24:12',1498,1498,'n')
INSERT INTO [firecms_followers] ( [id], [follower_id], [user_id], [is_special], [updated_on], [created_on], [created_by], [edited_by], [is_approved]) VALUES (103,1774,1498,'n','2010-12-27 11:24:22','2010-12-27 11:24:22',1498,1498,'n')
INSERT INTO [firecms_followers] ( [id], [follower_id], [user_id], [is_special], [updated_on], [created_on], [created_by], [edited_by], [is_approved]) VALUES (106,1498,1761,'n','2010-12-27 12:22:40','2010-12-27 12:22:40',1761,1761,'y')
INSERT INTO [firecms_followers] ( [id], [follower_id], [user_id], [is_special], [updated_on], [created_on], [created_by], [edited_by], [is_approved]) VALUES (108,1787,1801,'n','2010-12-27 03:20:37','2010-12-27 03:20:37',1801,1801,'n')
INSERT INTO [firecms_followers] ( [id], [follower_id], [user_id], [is_special], [updated_on], [created_on], [created_by], [edited_by], [is_approved]) VALUES (109,1731,1801,'n','2010-12-27 03:20:49','2010-12-27 03:20:49',1801,1801,'n')
INSERT INTO [firecms_followers] ( [id], [follower_id], [user_id], [is_special], [updated_on], [created_on], [created_by], [edited_by], [is_approved]) VALUES (112,21,1802,'n','2010-12-28 11:06:05','2010-12-28 11:06:05',1802,1802,'y')
INSERT INTO [firecms_followers] ( [id], [follower_id], [user_id], [is_special], [updated_on], [created_on], [created_by], [edited_by], [is_approved]) VALUES (113,1797,1802,'n','2010-12-28 11:06:39','2010-12-28 11:06:39',1802,1802,'n')
INSERT INTO [firecms_followers] ( [id], [follower_id], [user_id], [is_special], [updated_on], [created_on], [created_by], [edited_by], [is_approved]) VALUES (114,1800,1802,'n','2010-12-28 12:11:04','2010-12-28 12:11:04',1802,1802,'n')
INSERT INTO [firecms_followers] ( [id], [follower_id], [user_id], [is_special], [updated_on], [created_on], [created_by], [edited_by], [is_approved]) VALUES (115,1766,1802,'n','2010-12-28 12:14:19','2010-12-28 12:14:19',1802,1802,'n')
CREATE TABLE [firecms_forms] ([id] BIGINT,[to_table] VARCHAR(1500),[to_table_id] BIGINT,[updated_on] DATETIME,[created_on] DATETIME,[form_title] TEXT,[form_values] TEXT,[original_link] TEXT,[ip_address] VARCHAR(1500),[created_by] BIGINT,[edited_by] BIGINT
);
INSERT INTO [firecms_forms] ( [id], [to_table], [to_table_id], [updated_on], [created_on], [form_title], [form_values], [original_link], [ip_address], [created_by], [edited_by]) VALUES (1,NULL,NULL,'2012-08-10 11:01:33','2012-08-10 11:01:33','????? ?? ??????? ???? ???? ?????? 114 ?? ??? 174 ???? ??? ????? ??????? ??????? ????????? ????? ???? ?? ????? ?????? ?????? ??? ?? ????? ????? ??? ??? ?????? ??? ??? ???????. ??? ??? ?????? ?????? ????? ?????? ????? ?????? ?? ??????? ??????? (',NULL,NULL,NULL,14,14)
INSERT INTO [firecms_forms] ( [id], [to_table], [to_table_id], [updated_on], [created_on], [form_title], [form_values], [original_link], [ip_address], [created_by], [edited_by]) VALUES (2,NULL,NULL,'2012-08-10 11:06:22','2012-08-10 11:06:22','????? ?? ??????? ???? ???? ?????? 114 ?? ??? 174 ???? ??? ????? ??????? ??????? ????????? ????? ???? ?? ????? ?????? ?????? ??? ?? ????? ????? ??? ??? ?????? ??? ??? ???????. ??? ??? ?????? ?????? ????? ?????? ????? ?????? ?? ??????? ??????? (',NULL,NULL,NULL,14,14)
INSERT INTO [firecms_forms] ( [id], [to_table], [to_table_id], [updated_on], [created_on], [form_title], [form_values], [original_link], [ip_address], [created_by], [edited_by]) VALUES (3,NULL,NULL,'2012-08-10 11:08:33','2012-08-10 11:08:33','????? ?? ??????? ???? ???? ?????? 114 ?? ??? 174 ???? ??? ????? ??????? ??????? ????????? ????? ???? ?? ????? ?????? ?????? ??? ?? ????? ????? ??? ??? ?????? ??? ??? ???????. ??? ??? ?????? ?????? ????? ?????? ????? ?????? ?? ??????? ??????? (',NULL,NULL,NULL,14,14)
INSERT INTO [firecms_forms] ( [id], [to_table], [to_table_id], [updated_on], [created_on], [form_title], [form_values], [original_link], [ip_address], [created_by], [edited_by]) VALUES (4,NULL,NULL,'2012-08-10 11:36:34','2012-08-10 11:36:34','',NULL,NULL,NULL,14,14)
INSERT INTO [firecms_forms] ( [id], [to_table], [to_table_id], [updated_on], [created_on], [form_title], [form_values], [original_link], [ip_address], [created_by], [edited_by]) VALUES (5,NULL,NULL,'2012-08-10 11:36:39','2012-08-10 11:36:39','',NULL,NULL,NULL,14,14)
INSERT INTO [firecms_forms] ( [id], [to_table], [to_table_id], [updated_on], [created_on], [form_title], [form_values], [original_link], [ip_address], [created_by], [edited_by]) VALUES (6,NULL,NULL,'2012-08-10 11:36:39','2012-08-10 11:36:39','',NULL,NULL,NULL,14,14)
INSERT INTO [firecms_forms] ( [id], [to_table], [to_table_id], [updated_on], [created_on], [form_title], [form_values], [original_link], [ip_address], [created_by], [edited_by]) VALUES (7,NULL,NULL,'2012-08-10 11:36:40','2012-08-10 11:36:40','',NULL,NULL,NULL,14,14)
INSERT INTO [firecms_forms] ( [id], [to_table], [to_table_id], [updated_on], [created_on], [form_title], [form_values], [original_link], [ip_address], [created_by], [edited_by]) VALUES (8,NULL,NULL,'2012-08-10 11:36:40','2012-08-10 11:36:40','',NULL,NULL,NULL,14,14)
INSERT INTO [firecms_forms] ( [id], [to_table], [to_table_id], [updated_on], [created_on], [form_title], [form_values], [original_link], [ip_address], [created_by], [edited_by]) VALUES (9,NULL,NULL,'2012-08-10 11:37:50','2012-08-10 11:37:50','',NULL,NULL,NULL,14,14)
CREATE TABLE [firecms_geodata] ([id] BIGINT,[to_table] VARCHAR(1500),[to_table_id] BIGINT,[geodata_country_code] TEXT,[geodata_country] TEXT,[geodata_area] TEXT,[geodata_address] TEXT,[geodata_lat] TEXT,[geodata_lng] TEXT,[updated_on] DATETIME,[created_on] DATETIME,[geodata_title] TEXT,[geodata_note] TEXT,[geodata_city] TEXT,[geodata_mapzoom] TEXT,[geodata_maptype] TEXT,[geodata_mapcenter_lat] TEXT,[geodata_mapcenter_lng] TEXT,[geodata_map_height] TEXT,[geodata_map_width] TEXT
);
INSERT INTO [firecms_geodata] ( [id], [to_table], [to_table_id], [geodata_country_code], [geodata_country], [geodata_area], [geodata_address], [geodata_lat], [geodata_lng], [updated_on], [created_on], [geodata_title], [geodata_note], [geodata_city], [geodata_mapzoom], [geodata_maptype], [geodata_mapcenter_lat], [geodata_mapcenter_lng], [geodata_map_height], [geodata_map_width]) VALUES (41,'table_content',0,'BG','Bulgaria','?????-???','3A ?? ????????, Sofia, Bulgaria','42.659778','23.341337','2009-02-10 05:06:13','2009-02-10 05:06:13','','','Sofia',NULL,NULL,NULL,NULL,NULL,NULL)
INSERT INTO [firecms_geodata] ( [id], [to_table], [to_table_id], [geodata_country_code], [geodata_country], [geodata_area], [geodata_address], [geodata_lat], [geodata_lng], [updated_on], [created_on], [geodata_title], [geodata_note], [geodata_city], [geodata_mapzoom], [geodata_maptype], [geodata_mapcenter_lat], [geodata_mapcenter_lng], [geodata_map_height], [geodata_map_width]) VALUES (50,'table_taxonomy',11290,'BG','Bulgaria','?????-???','??. ????????????, Sofia, Bulgaria','42.697619','23.322324','2009-02-12 05:48:25','2009-02-12 05:48:25','Sofia','????? ? ?????','Sofia','12',NULL,NULL,NULL,NULL,NULL)
INSERT INTO [firecms_geodata] ( [id], [to_table], [to_table_id], [geodata_country_code], [geodata_country], [geodata_area], [geodata_address], [geodata_lat], [geodata_lng], [updated_on], [created_on], [geodata_title], [geodata_note], [geodata_city], [geodata_mapzoom], [geodata_maptype], [geodata_mapcenter_lat], [geodata_mapcenter_lng], [geodata_map_height], [geodata_map_width]) VALUES (54,'table_taxonomy',11291,'BG','Bulgaria','??????','8-16 ??. ???. ?????, Plovdiv, Bulgaria','42.143846','24.749686','2009-02-12 06:45:00','2009-02-12 06:45:00','Imoti v Plovdiv','','Plovdiv','13',NULL,NULL,NULL,NULL,NULL)
INSERT INTO [firecms_geodata] ( [id], [to_table], [to_table_id], [geodata_country_code], [geodata_country], [geodata_area], [geodata_address], [geodata_lat], [geodata_lng], [updated_on], [created_on], [geodata_title], [geodata_note], [geodata_city], [geodata_mapzoom], [geodata_maptype], [geodata_mapcenter_lat], [geodata_mapcenter_lng], [geodata_map_height], [geodata_map_width]) VALUES (56,'table_taxonomy',11426,'BG','Bulgaria','??????','2 ??. ???? ????? I, Burgas, Bulgaria','42.497678','27.470025','2009-02-12 07:11:09','2009-02-12 07:11:09','','','Burgas','13',NULL,NULL,NULL,NULL,NULL)
INSERT INTO [firecms_geodata] ( [id], [to_table], [to_table_id], [geodata_country_code], [geodata_country], [geodata_area], [geodata_address], [geodata_lat], [geodata_lng], [updated_on], [created_on], [geodata_title], [geodata_note], [geodata_city], [geodata_mapzoom], [geodata_maptype], [geodata_mapcenter_lat], [geodata_mapcenter_lng], [geodata_map_height], [geodata_map_width]) VALUES (57,'table_taxonomy',11425,'BG','Bulgaria','??????','2-10 ??. ???? ????? I, Burgas, Bulgaria','42.497664','27.469917','2009-02-12 07:12:08','2009-02-12 07:12:08','','','Burgas','13',NULL,NULL,NULL,NULL,NULL)
INSERT INTO [firecms_geodata] ( [id], [to_table], [to_table_id], [geodata_country_code], [geodata_country], [geodata_area], [geodata_address], [geodata_lat], [geodata_lng], [updated_on], [created_on], [geodata_title], [geodata_note], [geodata_city], [geodata_mapzoom], [geodata_maptype], [geodata_mapcenter_lat], [geodata_mapcenter_lng], [geodata_map_height], [geodata_map_width]) VALUES (59,'table_content',12125,'BG','Bulgaria','??????','36-44 ???. ??????????, Burgas, Bulgaria','42.496656','27.480154','2009-02-12 07:17:18','2009-02-12 07:17:18','','','Burgas','14',NULL,NULL,NULL,NULL,NULL)
INSERT INTO [firecms_geodata] ( [id], [to_table], [to_table_id], [geodata_country_code], [geodata_country], [geodata_area], [geodata_address], [geodata_lat], [geodata_lng], [updated_on], [created_on], [geodata_title], [geodata_note], [geodata_city], [geodata_mapzoom], [geodata_maptype], [geodata_mapcenter_lat], [geodata_mapcenter_lng], [geodata_map_height], [geodata_map_width]) VALUES (61,'table_taxonomy',11436,'BG','Bulgaria','????','1 ??. ??????, Vidin, Bulgaria','43.993345','22.875693','2009-02-12 07:42:21','2009-02-12 07:42:21','','','Vidin','14',NULL,NULL,NULL,NULL,NULL)
INSERT INTO [firecms_geodata] ( [id], [to_table], [to_table_id], [geodata_country_code], [geodata_country], [geodata_area], [geodata_address], [geodata_lat], [geodata_lng], [updated_on], [created_on], [geodata_title], [geodata_note], [geodata_city], [geodata_mapzoom], [geodata_maptype], [geodata_mapcenter_lat], [geodata_mapcenter_lng], [geodata_map_height], [geodata_map_width]) VALUES (64,'table_taxonomy',11449,'BG','Bulgaria','??????','160 ???. 3-?? ????, Montana, Bulgaria','43.415687','23.226828','2009-02-13 12:56:12','2009-02-13 12:56:12','','','Montana','14',NULL,NULL,NULL,NULL,NULL)
INSERT INTO [firecms_geodata] ( [id], [to_table], [to_table_id], [geodata_country_code], [geodata_country], [geodata_area], [geodata_address], [geodata_lat], [geodata_lng], [updated_on], [created_on], [geodata_title], [geodata_note], [geodata_city], [geodata_mapzoom], [geodata_maptype], [geodata_mapcenter_lat], [geodata_mapcenter_lng], [geodata_map_height], [geodata_map_width]) VALUES (66,'table_taxonomy',11450,'BG','Bulgaria','????','29-37 ???. ?????? ?????, Vratsa, Bulgaria','43.203925','23.548772','2009-02-13 01:10:47','2009-02-13 01:10:47','','','Vratsa','14',NULL,NULL,NULL,NULL,NULL)
INSERT INTO [firecms_geodata] ( [id], [to_table], [to_table_id], [geodata_country_code], [geodata_country], [geodata_area], [geodata_address], [geodata_lat], [geodata_lng], [updated_on], [created_on], [geodata_title], [geodata_note], [geodata_city], [geodata_mapzoom], [geodata_maptype], [geodata_mapcenter_lat], [geodata_mapcenter_lng], [geodata_map_height], [geodata_map_width]) VALUES (67,'table_taxonomy',11458,'BG','Bulgaria','?????','??. ??. ????? ? ???????, Pleven, Bulgaria','43.408240','24.620305','2009-02-13 01:13:55','2009-02-13 01:13:55','','','Pleven','14',NULL,NULL,NULL,NULL,NULL)
INSERT INTO [firecms_geodata] ( [id], [to_table], [to_table_id], [geodata_country_code], [geodata_country], [geodata_area], [geodata_address], [geodata_lat], [geodata_lng], [updated_on], [created_on], [geodata_title], [geodata_note], [geodata_city], [geodata_mapzoom], [geodata_maptype], [geodata_mapcenter_lat], [geodata_mapcenter_lng], [geodata_map_height], [geodata_map_width]) VALUES (68,'table_taxonomy',11470,'BG','Bulgaria','Tirnovo','2 ???. ????????, Veliko Tarnovo, Bulgaria','43.078556','25.627157','2009-02-13 01:28:22','2009-02-13 01:28:22','','','Veliko Tarnovo','14',NULL,NULL,NULL,NULL,NULL)
INSERT INTO [firecms_geodata] ( [id], [to_table], [to_table_id], [geodata_country_code], [geodata_country], [geodata_area], [geodata_address], [geodata_lat], [geodata_lng], [updated_on], [created_on], [geodata_title], [geodata_note], [geodata_city], [geodata_mapzoom], [geodata_maptype], [geodata_mapcenter_lat], [geodata_mapcenter_lng], [geodata_map_height], [geodata_map_width]) VALUES (69,'table_taxonomy',11471,'BG','Bulgaria','Tirnovo','2 ???. ????????, Veliko Tarnovo, Bulgaria','43.078556','25.627157','2009-02-13 01:28:54','2009-02-13 01:28:54','','','Veliko Tarnovo','14',NULL,NULL,NULL,NULL,NULL)
INSERT INTO [firecms_geodata] ( [id], [to_table], [to_table_id], [geodata_country_code], [geodata_country], [geodata_area], [geodata_address], [geodata_lat], [geodata_lng], [updated_on], [created_on], [geodata_title], [geodata_note], [geodata_city], [geodata_mapzoom], [geodata_maptype], [geodata_mapcenter_lat], [geodata_mapcenter_lng], [geodata_map_height], [geodata_map_width]) VALUES (70,'table_taxonomy',11472,'BG','Bulgaria','???','2 ??. ????? ?. ??????, Ruse, Bulgaria','43.849378','25.954253','2009-02-13 01:33:49','2009-02-13 01:33:49','','','Ruse','14',NULL,NULL,NULL,NULL,NULL)
INSERT INTO [firecms_geodata] ( [id], [to_table], [to_table_id], [geodata_country_code], [geodata_country], [geodata_area], [geodata_address], [geodata_lat], [geodata_lng], [updated_on], [created_on], [geodata_title], [geodata_note], [geodata_city], [geodata_mapzoom], [geodata_maptype], [geodata_mapcenter_lat], [geodata_mapcenter_lng], [geodata_map_height], [geodata_map_width]) VALUES (71,'table_taxonomy',11473,'BG','Bulgaria','??????','???. ????????, Razgrad, Bulgaria','43.525002','26.523127','2009-02-13 01:38:48','2009-02-13 01:38:48','','','Razgrad','14',NULL,NULL,NULL,NULL,NULL)
INSERT INTO [firecms_geodata] ( [id], [to_table], [to_table_id], [geodata_country_code], [geodata_country], [geodata_area], [geodata_address], [geodata_lat], [geodata_lng], [updated_on], [created_on], [geodata_title], [geodata_note], [geodata_city], [geodata_mapzoom], [geodata_maptype], [geodata_mapcenter_lat], [geodata_mapcenter_lng], [geodata_map_height], [geodata_map_width]) VALUES (72,'table_taxonomy',11474,'BG','Bulgaria','???????','29 ???. ??? ?????? ??????, Silistra, Bulgaria','44.117108','27.260722','2009-02-13 01:42:11','2009-02-13 01:42:11','','','Silistra','14',NULL,NULL,NULL,NULL,NULL)
INSERT INTO [firecms_geodata] ( [id], [to_table], [to_table_id], [geodata_country_code], [geodata_country], [geodata_area], [geodata_address], [geodata_lat], [geodata_lng], [updated_on], [created_on], [geodata_title], [geodata_note], [geodata_city], [geodata_mapzoom], [geodata_maptype], [geodata_mapcenter_lat], [geodata_mapcenter_lng], [geodata_map_height], [geodata_map_width]) VALUES (73,'table_taxonomy',11475,'BG','Bulgaria','??????','10 ??. ????????????, Dobrich, Bulgaria','43.569022','27.827242','2009-02-13 01:45:14','2009-02-13 01:45:14','','','Dobrich','14',NULL,NULL,NULL,NULL,NULL)
INSERT INTO [firecms_geodata] ( [id], [to_table], [to_table_id], [geodata_country_code], [geodata_country], [geodata_area], [geodata_address], [geodata_lat], [geodata_lng], [updated_on], [created_on], [geodata_title], [geodata_note], [geodata_city], [geodata_mapzoom], [geodata_maptype], [geodata_mapcenter_lat], [geodata_mapcenter_lng], [geodata_map_height], [geodata_map_width]) VALUES (75,'table_taxonomy',11476,'BG','Bulgaria','????','2 ??. ???? ????????, Shumen, Bulgaria','43.270125','26.923382','2009-02-13 02:06:02','2009-02-13 02:06:02','','','Shumen','14',NULL,NULL,NULL,NULL,NULL)
INSERT INTO [firecms_geodata] ( [id], [to_table], [to_table_id], [geodata_country_code], [geodata_country], [geodata_area], [geodata_address], [geodata_lat], [geodata_lng], [updated_on], [created_on], [geodata_title], [geodata_note], [geodata_city], [geodata_mapzoom], [geodata_maptype], [geodata_mapcenter_lat], [geodata_mapcenter_lng], [geodata_map_height], [geodata_map_width]) VALUES (76,'table_taxonomy',11477,'BG','Bulgaria','????','1 ??. ??????, Varna, Bulgaria','43.216645','27.911806','2009-02-13 02:08:05','2009-02-13 02:08:05','','','Varna','14',NULL,NULL,NULL,NULL,NULL)
INSERT INTO [firecms_geodata] ( [id], [to_table], [to_table_id], [geodata_country_code], [geodata_country], [geodata_area], [geodata_address], [geodata_lat], [geodata_lng], [updated_on], [created_on], [geodata_title], [geodata_note], [geodata_city], [geodata_mapzoom], [geodata_maptype], [geodata_mapcenter_lat], [geodata_mapcenter_lng], [geodata_map_height], [geodata_map_width]) VALUES (77,'table_taxonomy',11478,'BG','Bulgaria','????????','??. ?????? ???????, Turgovishte, Bulgaria','43.247125','26.572819','2009-02-13 02:10:32','2009-02-13 02:10:32','','','Turgovishte','14',NULL,NULL,NULL,NULL,NULL)
INSERT INTO [firecms_geodata] ( [id], [to_table], [to_table_id], [geodata_country_code], [geodata_country], [geodata_area], [geodata_address], [geodata_lat], [geodata_lng], [updated_on], [created_on], [geodata_title], [geodata_note], [geodata_city], [geodata_mapzoom], [geodata_maptype], [geodata_mapcenter_lat], [geodata_mapcenter_lng], [geodata_map_height], [geodata_map_width]) VALUES (78,'table_taxonomy',11479,'BG','Bulgaria','??????','4 ??. ?????, Gabrovo, Bulgaria','42.874224','25.318938','2009-02-13 02:12:21','2009-02-13 02:12:21','','','Gabrovo','14',NULL,NULL,NULL,NULL,NULL)
INSERT INTO [firecms_geodata] ( [id], [to_table], [to_table_id], [geodata_country_code], [geodata_country], [geodata_area], [geodata_address], [geodata_lat], [geodata_lng], [updated_on], [created_on], [geodata_title], [geodata_note], [geodata_city], [geodata_mapzoom], [geodata_maptype], [geodata_mapcenter_lat], [geodata_mapcenter_lng], [geodata_map_height], [geodata_map_width]) VALUES (79,'table_taxonomy',11480,'BG','Bulgaria','?????','20 ??. ?????????, Lovech, Bulgaria','43.134838','24.717262','2009-02-13 02:32:16','2009-02-13 02:32:16','','','Lovech','14',NULL,NULL,NULL,NULL,NULL)
INSERT INTO [firecms_geodata] ( [id], [to_table], [to_table_id], [geodata_country_code], [geodata_country], [geodata_area], [geodata_address], [geodata_lat], [geodata_lng], [updated_on], [created_on], [geodata_title], [geodata_note], [geodata_city], [geodata_mapzoom], [geodata_maptype], [geodata_mapcenter_lat], [geodata_mapcenter_lng], [geodata_map_height], [geodata_map_width]) VALUES (80,'table_taxonomy',11481,'BG','Bulgaria','?????','20 ??. ?????????, Lovech, Bulgaria','43.134838','24.717262','2009-02-13 02:32:37','2009-02-13 02:32:37','','','Lovech','14',NULL,NULL,NULL,NULL,NULL)
INSERT INTO [firecms_geodata] ( [id], [to_table], [to_table_id], [geodata_country_code], [geodata_country], [geodata_area], [geodata_address], [geodata_lat], [geodata_lng], [updated_on], [created_on], [geodata_title], [geodata_note], [geodata_city], [geodata_mapzoom], [geodata_maptype], [geodata_mapcenter_lat], [geodata_mapcenter_lng], [geodata_map_height], [geodata_map_width]) VALUES (81,'table_taxonomy',11482,'BG','Bulgaria','?????','??. ???????, Pernik, Bulgaria','42.610232','23.032138','2009-02-13 02:35:19','2009-02-13 02:35:19','','','Pernik','14',NULL,NULL,NULL,NULL,NULL)
INSERT INTO [firecms_geodata] ( [id], [to_table], [to_table_id], [geodata_country_code], [geodata_country], [geodata_area], [geodata_address], [geodata_lat], [geodata_lng], [updated_on], [created_on], [geodata_title], [geodata_note], [geodata_city], [geodata_mapzoom], [geodata_maptype], [geodata_mapcenter_lat], [geodata_mapcenter_lng], [geodata_map_height], [geodata_map_width]) VALUES (82,'table_taxonomy',11483,'BG','Bulgaria','????????','??. ????? ??????????, Kyustendil, Bulgaria','42.281243','22.688237','2009-02-13 02:39:29','2009-02-13 02:39:29','','','Kyustendil','14',NULL,NULL,NULL,NULL,NULL)
INSERT INTO [firecms_geodata] ( [id], [to_table], [to_table_id], [geodata_country_code], [geodata_country], [geodata_area], [geodata_address], [geodata_lat], [geodata_lng], [updated_on], [created_on], [geodata_title], [geodata_note], [geodata_city], [geodata_mapzoom], [geodata_maptype], [geodata_mapcenter_lat], [geodata_mapcenter_lng], [geodata_map_height], [geodata_map_width]) VALUES (83,'table_taxonomy',11484,'BG','Bulgaria','??????????','??. ?????? ?. ????????, Bansko, Bulgaria','41.838022','23.489028','2009-02-13 02:45:11','2009-02-13 02:45:11','','','Bansko','14',NULL,NULL,NULL,NULL,NULL)
INSERT INTO [firecms_geodata] ( [id], [to_table], [to_table_id], [geodata_country_code], [geodata_country], [geodata_area], [geodata_address], [geodata_lat], [geodata_lng], [updated_on], [created_on], [geodata_title], [geodata_note], [geodata_city], [geodata_mapzoom], [geodata_maptype], [geodata_mapcenter_lat], [geodata_mapcenter_lng], [geodata_map_height], [geodata_map_width]) VALUES (84,'table_taxonomy',11485,'BG','Bulgaria','????????','2 ??. ???? ????????, Pazardzhik, Bulgaria','42.191316','24.332133','2009-02-13 02:47:40','2009-02-13 02:47:40','','','Pazardzhik','14',NULL,NULL,NULL,NULL,NULL)
INSERT INTO [firecms_geodata] ( [id], [to_table], [to_table_id], [geodata_country_code], [geodata_country], [geodata_area], [geodata_address], [geodata_lat], [geodata_lng], [updated_on], [created_on], [geodata_title], [geodata_note], [geodata_city], [geodata_mapzoom], [geodata_maptype], [geodata_mapcenter_lat], [geodata_mapcenter_lng], [geodata_map_height], [geodata_map_width]) VALUES (85,'table_taxonomy',11486,'BG','Bulgaria','??????','13 ??. ???. ?????, Plovdiv, Bulgaria','42.143841','24.749561','2009-02-13 02:54:53','2009-02-13 02:54:53','','','Plovdiv','14',NULL,NULL,NULL,NULL,NULL)
INSERT INTO [firecms_geodata] ( [id], [to_table], [to_table_id], [geodata_country_code], [geodata_country], [geodata_area], [geodata_address], [geodata_lat], [geodata_lng], [updated_on], [created_on], [geodata_title], [geodata_note], [geodata_city], [geodata_mapzoom], [geodata_maptype], [geodata_mapcenter_lat], [geodata_mapcenter_lng], [geodata_map_height], [geodata_map_width]) VALUES (86,'table_taxonomy',11487,'BG','Bulgaria','????? ?????','81 ??. ????? ??????? ??????, Stara Zagora, Bulgaria','42.423917','25.624902','2009-02-13 02:59:40','2009-02-13 02:59:40','','','Stara Zagora','14',NULL,NULL,NULL,NULL,NULL)
INSERT INTO [firecms_geodata] ( [id], [to_table], [to_table_id], [geodata_country_code], [geodata_country], [geodata_area], [geodata_address], [geodata_lat], [geodata_lng], [updated_on], [created_on], [geodata_title], [geodata_note], [geodata_city], [geodata_mapzoom], [geodata_maptype], [geodata_mapcenter_lat], [geodata_mapcenter_lng], [geodata_map_height], [geodata_map_width]) VALUES (87,'table_taxonomy',11488,'BG','Bulgaria','?????','1 ??. ?????? ???? ????????, Sliven, Bulgaria','42.681730','26.315506','2009-02-13 03:04:05','2009-02-13 03:04:05','','','Sliven','14',NULL,NULL,NULL,NULL,NULL)
INSERT INTO [firecms_geodata] ( [id], [to_table], [to_table_id], [geodata_country_code], [geodata_country], [geodata_area], [geodata_address], [geodata_lat], [geodata_lng], [updated_on], [created_on], [geodata_title], [geodata_note], [geodata_city], [geodata_mapzoom], [geodata_maptype], [geodata_mapcenter_lat], [geodata_mapcenter_lng], [geodata_map_height], [geodata_map_width]) VALUES (88,'table_taxonomy',11489,'BG','Bulgaria','????','13 ??. ?????? ?. ????????, Yambol, Bulgaria','42.483769','26.510772','2009-02-13 03:16:12','2009-02-13 03:16:12','','','Yambol','14',NULL,NULL,NULL,NULL,NULL)
INSERT INTO [firecms_geodata] ( [id], [to_table], [to_table_id], [geodata_country_code], [geodata_country], [geodata_area], [geodata_address], [geodata_lat], [geodata_lng], [updated_on], [created_on], [geodata_title], [geodata_note], [geodata_city], [geodata_mapzoom], [geodata_maptype], [geodata_mapcenter_lat], [geodata_mapcenter_lng], [geodata_map_height], [geodata_map_width]) VALUES (89,'table_taxonomy',11490,'BG','Bulgaria','??????','10 ??. ?????, Haskovo, Bulgaria','41.931277','25.557213','2009-02-13 03:18:10','2009-02-13 03:18:10','','','Haskovo','14',NULL,NULL,NULL,NULL,NULL)
INSERT INTO [firecms_geodata] ( [id], [to_table], [to_table_id], [geodata_country_code], [geodata_country], [geodata_area], [geodata_address], [geodata_lat], [geodata_lng], [updated_on], [created_on], [geodata_title], [geodata_note], [geodata_city], [geodata_mapzoom], [geodata_maptype], [geodata_mapcenter_lat], [geodata_mapcenter_lng], [geodata_map_height], [geodata_map_width]) VALUES (90,'table_taxonomy',11491,'BG','Bulgaria','???????','???. ????????, Kardzali, Bulgaria','41.642746','25.368718','2009-02-13 03:20:17','2009-02-13 03:20:17','','','Kardzali','14',NULL,NULL,NULL,NULL,NULL)
INSERT INTO [firecms_geodata] ( [id], [to_table], [to_table_id], [geodata_country_code], [geodata_country], [geodata_area], [geodata_address], [geodata_lat], [geodata_lng], [updated_on], [created_on], [geodata_title], [geodata_note], [geodata_city], [geodata_mapzoom], [geodata_maptype], [geodata_mapcenter_lat], [geodata_mapcenter_lng], [geodata_map_height], [geodata_map_width]) VALUES (91,'table_taxonomy',11492,'BG','Bulgaria','?????','866, Smolyan, Bulgaria','41.574813','24.710876','2009-02-13 03:22:09','2009-02-13 03:22:09','','','Smolyan','14',NULL,NULL,NULL,NULL,NULL)
INSERT INTO [firecms_geodata] ( [id], [to_table], [to_table_id], [geodata_country_code], [geodata_country], [geodata_area], [geodata_address], [geodata_lat], [geodata_lng], [updated_on], [created_on], [geodata_title], [geodata_note], [geodata_city], [geodata_mapzoom], [geodata_maptype], [geodata_mapcenter_lat], [geodata_mapcenter_lng], [geodata_map_height], [geodata_map_width]) VALUES (94,'table_taxonomy',11494,'IT','Italy','Sicily','81 ??. ????? ??????? ??????, Stara Zagora, Bulgaria','40.913513','5.800781','2009-02-13 04:44:32','2009-02-13 04:44:32','','','Stara Zagora','4',NULL,NULL,NULL,NULL,NULL)
INSERT INTO [firecms_geodata] ( [id], [to_table], [to_table_id], [geodata_country_code], [geodata_country], [geodata_area], [geodata_address], [geodata_lat], [geodata_lng], [updated_on], [created_on], [geodata_title], [geodata_note], [geodata_city], [geodata_mapzoom], [geodata_maptype], [geodata_mapcenter_lat], [geodata_mapcenter_lng], [geodata_map_height], [geodata_map_width]) VALUES (95,'table_taxonomy',11503,'CN','China','Qinghai','???, ???????, Qinghai, China','34.047863','100.619655','2009-02-13 04:44:45','2009-02-13 04:44:45','','','???????','14',NULL,NULL,NULL,NULL,NULL)
INSERT INTO [firecms_geodata] ( [id], [to_table], [to_table_id], [geodata_country_code], [geodata_country], [geodata_area], [geodata_address], [geodata_lat], [geodata_lng], [updated_on], [created_on], [geodata_title], [geodata_note], [geodata_city], [geodata_mapzoom], [geodata_maptype], [geodata_mapcenter_lat], [geodata_mapcenter_lng], [geodata_map_height], [geodata_map_width]) VALUES (96,'table_content',12128,'BG','Bulgaria','?????-???','615 ??. ????? ???????, Sofia, Bulgaria','42.711706','23.248680','2009-02-13 04:47:30','2009-02-13 04:47:30','','','Sofia','14',NULL,NULL,NULL,NULL,NULL)
INSERT INTO [firecms_geodata] ( [id], [to_table], [to_table_id], [geodata_country_code], [geodata_country], [geodata_area], [geodata_address], [geodata_lat], [geodata_lng], [updated_on], [created_on], [geodata_title], [geodata_note], [geodata_city], [geodata_mapzoom], [geodata_maptype], [geodata_mapcenter_lat], [geodata_mapcenter_lng], [geodata_map_height], [geodata_map_width]) VALUES (101,'table_content',14770,'TH','Thailand','Prachuap Khiri Khan','Naep Khehat, Hua Hin, Hua Hin, Prachuap Khiri Khan, Thailand','12.577780','99.956325','2009-03-18 02:31:03','2009-03-18 02:31:03','','','Hua Hin','14',NULL,NULL,NULL,NULL,NULL)
INSERT INTO [firecms_geodata] ( [id], [to_table], [to_table_id], [geodata_country_code], [geodata_country], [geodata_area], [geodata_address], [geodata_lat], [geodata_lng], [updated_on], [created_on], [geodata_title], [geodata_note], [geodata_city], [geodata_mapzoom], [geodata_maptype], [geodata_mapcenter_lat], [geodata_mapcenter_lng], [geodata_map_height], [geodata_map_width]) VALUES (103,'table_content',15864,'BG','Bulgaria','?????-???','67-68 ?? ??????, Sofia, Bulgaria','42.681750','23.351100','2009-03-20 05:04:00','2009-03-20 05:04:00','','','Sofia','14',NULL,NULL,NULL,NULL,NULL)
CREATE TABLE [firecms_groups] ([id] BIGINT,[group_name] VARCHAR(1500),[group_to_table] VARCHAR(1500),[group_to_table_id] BIGINT,[is_active] BIGINT,[updated_on] DATETIME
);
CREATE TABLE [firecms_media] ([id] BIGINT,[to_table] VARCHAR(1500),[to_table_id] BIGINT,[media_type] VARCHAR(1500),[media_order] BIGINT,[filename] VARCHAR(1500),[updated_on] DATETIME,[created_on] DATETIME,[media_name] TEXT,[media_description] TEXT,[created_by] BIGINT,[edited_by] BIGINT,[queue_id] TEXT,[is_special] CHAR(1),[is_banner] CHAR(1),[embed_code] VARCHAR(1500),[original_link] VARCHAR(1500),[collection] VARCHAR(1500)
);
INSERT INTO [firecms_media] ( [id], [to_table], [to_table_id], [media_type], [media_order], [filename], [updated_on], [created_on], [media_name], [media_description], [created_by], [edited_by], [queue_id], [is_special], [is_banner], [embed_code], [original_link], [collection]) VALUES (7602,'table_content',3586,'picture',1,'ds5002.jpg','2012-02-15 17:21:39','2012-02-15 17:21:39',NULL,NULL,12,12,NULL,'n','n',NULL,NULL,NULL)
INSERT INTO [firecms_media] ( [id], [to_table], [to_table_id], [media_type], [media_order], [filename], [updated_on], [created_on], [media_name], [media_description], [created_by], [edited_by], [queue_id], [is_special], [is_banner], [embed_code], [original_link], [collection]) VALUES (7603,'table_content',3587,'picture',1,'pvaf90.jpg','2012-02-15 17:21:40','2012-02-15 17:21:40',NULL,NULL,12,12,NULL,'n','n',NULL,NULL,NULL)
INSERT INTO [firecms_media] ( [id], [to_table], [to_table_id], [media_type], [media_order], [filename], [updated_on], [created_on], [media_name], [media_description], [created_by], [edited_by], [queue_id], [is_special], [is_banner], [embed_code], [original_link], [collection]) VALUES (7604,'table_content',3586,'picture',2,'a1aff3a1879c438b5523b6e8ba115ac7ds5002.jpg','2012-02-15 17:22:13','2012-02-15 17:22:13',NULL,NULL,12,12,NULL,'n','n',NULL,NULL,NULL)
INSERT INTO [firecms_media] ( [id], [to_table], [to_table_id], [media_type], [media_order], [filename], [updated_on], [created_on], [media_name], [media_description], [created_by], [edited_by], [queue_id], [is_special], [is_banner], [embed_code], [original_link], [collection]) VALUES (7605,'table_content',3587,'picture',2,'c543c93bcbf93b663a24828a8263dac3pvaf90.jpg','2012-02-15 17:22:14','2012-02-15 17:22:14',NULL,NULL,12,12,NULL,'n','n',NULL,NULL,NULL)
INSERT INTO [firecms_media] ( [id], [to_table], [to_table_id], [media_type], [media_order], [filename], [updated_on], [created_on], [media_name], [media_description], [created_by], [edited_by], [queue_id], [is_special], [is_banner], [embed_code], [original_link], [collection]) VALUES (7606,'table_content',3586,'picture',3,'a1aff3a1879c438b5523b6e8ba115ac7ds5002.jpg','2012-02-15 17:22:28','2012-02-15 17:22:28',NULL,NULL,12,12,NULL,'n','n',NULL,NULL,NULL)
INSERT INTO [firecms_media] ( [id], [to_table], [to_table_id], [media_type], [media_order], [filename], [updated_on], [created_on], [media_name], [media_description], [created_by], [edited_by], [queue_id], [is_special], [is_banner], [embed_code], [original_link], [collection]) VALUES (7607,'table_content',3587,'picture',3,'c543c93bcbf93b663a24828a8263dac3pvaf90.jpg','2012-02-15 17:22:28','2012-02-15 17:22:28',NULL,NULL,12,12,NULL,'n','n',NULL,NULL,NULL)
INSERT INTO [firecms_media] ( [id], [to_table], [to_table_id], [media_type], [media_order], [filename], [updated_on], [created_on], [media_name], [media_description], [created_by], [edited_by], [queue_id], [is_special], [is_banner], [embed_code], [original_link], [collection]) VALUES (7608,'table_content',3588,'picture',1,'coax.jpg','2012-02-15 17:22:52','2012-02-15 17:22:52',NULL,NULL,12,12,NULL,'n','n',NULL,NULL,NULL)
INSERT INTO [firecms_media] ( [id], [to_table], [to_table_id], [media_type], [media_order], [filename], [updated_on], [created_on], [media_name], [media_description], [created_by], [edited_by], [queue_id], [is_special], [is_banner], [embed_code], [original_link], [collection]) VALUES (7609,'table_content',3589,'picture',1,'cs14x134.jpg','2012-02-15 17:22:55','2012-02-15 17:22:55',NULL,NULL,12,12,NULL,'n','n',NULL,NULL,NULL)
INSERT INTO [firecms_media] ( [id], [to_table], [to_table_id], [media_type], [media_order], [filename], [updated_on], [created_on], [media_name], [media_description], [created_by], [edited_by], [queue_id], [is_special], [is_banner], [embed_code], [original_link], [collection]) VALUES (7610,'table_content',3590,'picture',1,'ct11blk.jpg','2012-02-15 17:22:56','2012-02-15 17:22:56',NULL,NULL,12,12,NULL,'n','n',NULL,NULL,NULL)
INSERT INTO [firecms_media] ( [id], [to_table], [to_table_id], [media_type], [media_order], [filename], [updated_on], [created_on], [media_name], [media_description], [created_by], [edited_by], [queue_id], [is_special], [is_banner], [embed_code], [original_link], [collection]) VALUES (7611,'table_content',3591,'picture',1,'ct11wht.jpg','2012-02-15 17:22:58','2012-02-15 17:22:58',NULL,NULL,12,12,NULL,'n','n',NULL,NULL,NULL)
INSERT INTO [firecms_media] ( [id], [to_table], [to_table_id], [media_type], [media_order], [filename], [updated_on], [created_on], [media_name], [media_description], [created_by], [edited_by], [queue_id], [is_special], [is_banner], [embed_code], [original_link], [collection]) VALUES (7612,'table_content',3592,'picture',1,'ct14blk.jpg','2012-02-15 17:23:00','2012-02-15 17:23:00',NULL,NULL,12,12,NULL,'n','n',NULL,NULL,NULL)
INSERT INTO [firecms_media] ( [id], [to_table], [to_table_id], [media_type], [media_order], [filename], [updated_on], [created_on], [media_name], [media_description], [created_by], [edited_by], [queue_id], [is_special], [is_banner], [embed_code], [original_link], [collection]) VALUES (7613,'table_content',3593,'picture',1,'ct14wht.jpg','2012-02-15 17:23:02','2012-02-15 17:23:02',NULL,NULL,12,12,NULL,'n','n',NULL,NULL,NULL)
INSERT INTO [firecms_media] ( [id], [to_table], [to_table_id], [media_type], [media_order], [filename], [updated_on], [created_on], [media_name], [media_description], [created_by], [edited_by], [queue_id], [is_special], [is_banner], [embed_code], [original_link], [collection]) VALUES (7614,'table_content',3594,'picture',1,'ct7blk.jpg','2012-02-15 17:23:03','2012-02-15 17:23:03',NULL,NULL,12,12,NULL,'n','n',NULL,NULL,NULL)
INSERT INTO [firecms_media] ( [id], [to_table], [to_table_id], [media_type], [media_order], [filename], [updated_on], [created_on], [media_name], [media_description], [created_by], [edited_by], [queue_id], [is_special], [is_banner], [embed_code], [original_link], [collection]) VALUES (7615,'table_content',3595,'picture',1,'ct7wht.jpg','2012-02-15 17:23:05','2012-02-15 17:23:05',NULL,NULL,12,12,NULL,'n','n',NULL,NULL,NULL)
INSERT INTO [firecms_media] ( [id], [to_table], [to_table_id], [media_type], [media_order], [filename], [updated_on], [created_on], [media_name], [media_description], [created_by], [edited_by], [queue_id], [is_special], [is_banner], [embed_code], [original_link], [collection]) VALUES (7616,'table_content',3596,'picture',1,'ct7blk-mh.jpg','2012-02-15 17:23:07','2012-02-15 17:23:07',NULL,NULL,12,12,NULL,'n','n',NULL,NULL,NULL)
INSERT INTO [firecms_media] ( [id], [to_table], [to_table_id], [media_type], [media_order], [filename], [updated_on], [created_on], [media_name], [media_description], [created_by], [edited_by], [queue_id], [is_special], [is_banner], [embed_code], [original_link], [collection]) VALUES (7617,'table_content',3597,'picture',1,'cx200.jpg','2012-02-15 17:23:08','2012-02-15 17:23:08',NULL,NULL,12,12,NULL,'n','n',NULL,NULL,NULL)
INSERT INTO [firecms_media] ( [id], [to_table], [to_table_id], [media_type], [media_order], [filename], [updated_on], [created_on], [media_name], [media_description], [created_by], [edited_by], [queue_id], [is_special], [is_banner], [embed_code], [original_link], [collection]) VALUES (7619,'table_content',3599,'picture',1,'d4blk.jpg','2012-02-15 17:23:12','2012-02-15 17:23:12',NULL,NULL,12,12,NULL,'n','n',NULL,NULL,NULL)
INSERT INTO [firecms_media] ( [id], [to_table], [to_table_id], [media_type], [media_order], [filename], [updated_on], [created_on], [media_name], [media_description], [created_by], [edited_by], [queue_id], [is_special], [is_banner], [embed_code], [original_link], [collection]) VALUES (7978,'table_content',3938,'picture',1,'69110d5ee26e67754aed31f3381c6f0bcmp6.jpg','2012-02-19 15:33:26','2012-02-19 15:33:26',NULL,NULL,12,12,NULL,'n','n',NULL,NULL,NULL)
INSERT INTO [firecms_media] ( [id], [to_table], [to_table_id], [media_type], [media_order], [filename], [updated_on], [created_on], [media_name], [media_description], [created_by], [edited_by], [queue_id], [is_special], [is_banner], [embed_code], [original_link], [collection]) VALUES (7621,'table_content',3601,'picture',1,'d4wht.jpg','2012-02-15 17:25:08','2012-02-15 17:25:08',NULL,NULL,12,12,NULL,'n','n',NULL,NULL,NULL)
INSERT INTO [firecms_media] ( [id], [to_table], [to_table_id], [media_type], [media_order], [filename], [updated_on], [created_on], [media_name], [media_description], [created_by], [edited_by], [queue_id], [is_special], [is_banner], [embed_code], [original_link], [collection]) VALUES (7622,'table_content',3602,'picture',1,'dbstm.jpg','2012-02-15 17:25:09','2012-02-15 17:25:09',NULL,NULL,12,12,NULL,'n','n',NULL,NULL,NULL)
INSERT INTO [firecms_media] ( [id], [to_table], [to_table_id], [media_type], [media_order], [filename], [updated_on], [created_on], [media_name], [media_description], [created_by], [edited_by], [queue_id], [is_special], [is_banner], [embed_code], [original_link], [collection]) VALUES (8028,'table_content',3934,'picture',3,'da7472b2f07074c1357afe4cd48f7446h25mnt.jpg','2012-02-19 15:37:23','2012-02-19 15:37:23',NULL,NULL,12,12,NULL,'n','n',NULL,NULL,NULL)
INSERT INTO [firecms_media] ( [id], [to_table], [to_table_id], [media_type], [media_order], [filename], [updated_on], [created_on], [media_name], [media_description], [created_by], [edited_by], [queue_id], [is_special], [is_banner], [embed_code], [original_link], [collection]) VALUES (7624,'table_content',3604,'picture',1,'digiairpro.jpg','2012-02-15 17:25:13','2012-02-15 17:25:13',NULL,NULL,12,12,NULL,'n','n',NULL,NULL,NULL)
INSERT INTO [firecms_media] ( [id], [to_table], [to_table_id], [media_type], [media_order], [filename], [updated_on], [created_on], [media_name], [media_description], [created_by], [edited_by], [queue_id], [is_special], [is_banner], [embed_code], [original_link], [collection]) VALUES (7979,'table_content',3939,'picture',1,'fb1e85c06631880db7d475ef2c3a5df4cmp6q.jpg','2012-02-19 15:33:27','2012-02-19 15:33:27',NULL,NULL,12,12,NULL,'n','n',NULL,NULL,NULL)
INSERT INTO [firecms_media] ( [id], [to_table], [to_table_id], [media_type], [media_order], [filename], [updated_on], [created_on], [media_name], [media_description], [created_by], [edited_by], [queue_id], [is_special], [is_banner], [embed_code], [original_link], [collection]) VALUES (7627,'table_content',3607,'picture',1,'dpd2.jpg','2012-02-15 17:25:18','2012-02-15 17:25:18',NULL,NULL,12,12,NULL,'n','n',NULL,NULL,NULL)
INSERT INTO [firecms_media] ( [id], [to_table], [to_table_id], [media_type], [media_order], [filename], [updated_on], [created_on], [media_name], [media_description], [created_by], [edited_by], [queue_id], [is_special], [is_banner], [embed_code], [original_link], [collection]) VALUES (7628,'table_content',3608,'picture',1,'ds2076.jpg','2012-02-15 17:25:20','2012-02-15 17:25:20',NULL,NULL,12,12,NULL,'n','n',NULL,NULL,NULL)
INSERT INTO [firecms_media] ( [id], [to_table], [to_table_id], [media_type], [media_order], [filename], [updated_on], [created_on], [media_name], [media_description], [created_by], [edited_by], [queue_id], [is_special], [is_banner], [embed_code], [original_link], [collection]) VALUES (7629,'table_content',3609,'picture',1,'ds2077.jpg','2012-02-15 17:25:21','2012-02-15 17:25:21',NULL,NULL,12,12,NULL,'n','n',NULL,NULL,NULL)
INSERT INTO [firecms_media] ( [id], [to_table], [to_table_id], [media_type], [media_order], [filename], [updated_on], [created_on], [media_name], [media_description], [created_by], [edited_by], [queue_id], [is_special], [is_banner], [embed_code], [original_link], [collection]) VALUES (7630,'table_content',3610,'picture',1,'ds3000.jpg','2012-02-15 17:25:22','2012-02-15 17:25:22',NULL,NULL,12,12,NULL,'n','n',NULL,NULL,NULL)
INSERT INTO [firecms_media] ( [id], [to_table], [to_table_id], [media_type], [media_order], [filename], [updated_on], [created_on], [media_name], [media_description], [created_by], [edited_by], [queue_id], [is_special], [is_banner], [embed_code], [original_link], [collection]) VALUES (7631,'table_content',3611,'picture',1,'ds4047.jpg','2012-02-15 17:25:24','2012-02-15 17:25:24',NULL,NULL,12,12,NULL,'n','n',NULL,NULL,NULL)
INSERT INTO [firecms_media] ( [id], [to_table], [to_table_id], [media_type], [media_order], [filename], [updated_on], [created_on], [media_name], [media_description], [created_by], [edited_by], [queue_id], [is_special], [is_banner], [embed_code], [original_link], [collection]) VALUES (7632,'table_content',3612,'picture',1,'ds4061.jpg','2012-02-15 17:25:26','2012-02-15 17:25:26',NULL,NULL,12,12,NULL,'n','n',NULL,NULL,NULL)
INSERT INTO [firecms_media] ( [id], [to_table], [to_table_id], [media_type], [media_order], [filename], [updated_on], [created_on], [media_name], [media_description], [created_by], [edited_by], [queue_id], [is_special], [is_banner], [embed_code], [original_link], [collection]) VALUES (7633,'table_content',3613,'picture',1,'ds4062.jpg','2012-02-15 17:25:28','2012-02-15 17:25:28',NULL,NULL,12,12,NULL,'n','n',NULL,NULL,NULL)
INSERT INTO [firecms_media] ( [id], [to_table], [to_table_id], [media_type], [media_order], [filename], [updated_on], [created_on], [media_name], [media_description], [created_by], [edited_by], [queue_id], [is_special], [is_banner], [embed_code], [original_link], [collection]) VALUES (8029,'table_content',3935,'picture',3,'d31bceccc17e362c3094c79ae330cd8adtvswpkit.jpg','2012-02-19 15:37:23','2012-02-19 15:37:23',NULL,NULL,12,12,NULL,'n','n',NULL,NULL,NULL)
INSERT INTO [firecms_media] ( [id], [to_table], [to_table_id], [media_type], [media_order], [filename], [updated_on], [created_on], [media_name], [media_description], [created_by], [edited_by], [queue_id], [is_special], [is_banner], [embed_code], [original_link], [collection]) VALUES (7636,'table_content',3616,'picture',1,'ds59.jpg','2012-02-15 17:25:33','2012-02-15 17:25:33',NULL,NULL,12,12,NULL,'n','n',NULL,NULL,NULL)
INSERT INTO [firecms_media] ( [id], [to_table], [to_table_id], [media_type], [media_order], [filename], [updated_on], [created_on], [media_name], [media_description], [created_by], [edited_by], [queue_id], [is_special], [is_banner], [embed_code], [original_link], [collection]) VALUES (7637,'table_content',3617,'picture',1,'ds6.jpg','2012-02-15 17:25:35','2012-02-15 17:25:35',NULL,NULL,12,12,NULL,'n','n',NULL,NULL,NULL)
INSERT INTO [firecms_media] ( [id], [to_table], [to_table_id], [media_type], [media_order], [filename], [updated_on], [created_on], [media_name], [media_description], [created_by], [edited_by], [queue_id], [is_special], [is_banner], [embed_code], [original_link], [collection]) VALUES (7638,'table_content',3618,'picture',1,'ds6q.jpg','2012-02-15 17:25:37','2012-02-15 17:25:37',NULL,NULL,12,12,NULL,'n','n',NULL,NULL,NULL)
INSERT INTO [firecms_media] ( [id], [to_table], [to_table_id], [media_type], [media_order], [filename], [updated_on], [created_on], [media_name], [media_description], [created_by], [edited_by], [queue_id], [is_special], [is_banner], [embed_code], [original_link], [collection]) VALUES (7639,'table_content',3619,'picture',1,'dtv87odu.jpg','2012-02-15 17:25:38','2012-02-15 17:25:38',NULL,NULL,12,12,NULL,'n','n',NULL,NULL,NULL)
INSERT INTO [firecms_media] ( [id], [to_table], [to_table_id], [media_type], [media_order], [filename], [updated_on], [created_on], [media_name], [media_description], [created_by], [edited_by], [queue_id], [is_special], [is_banner], [embed_code], [original_link], [collection]) VALUES (7640,'table_content',3620,'picture',1,'dualbuddy.jpg','2012-02-15 17:25:40','2012-02-15 17:25:40',NULL,NULL,12,12,NULL,'n','n',NULL,NULL,NULL)
INSERT INTO [firecms_media] ( [id], [to_table], [to_table_id], [media_type], [media_order], [filename], [updated_on], [created_on], [media_name], [media_description], [created_by], [edited_by], [queue_id], [is_special], [is_banner], [embed_code], [original_link], [collection]) VALUES (7642,'table_content',3622,'picture',1,'ex6xl.jpg','2012-02-15 17:25:44','2012-02-15 17:25:44',NULL,NULL,12,12,NULL,'n','n',NULL,NULL,NULL)
INSERT INTO [firecms_media] ( [id], [to_table], [to_table_id], [media_type], [media_order], [filename], [updated_on], [created_on], [media_name], [media_description], [created_by], [edited_by], [queue_id], [is_special], [is_banner], [embed_code], [original_link], [collection]) VALUES (7643,'table_content',3623,'picture',1,'1829ac10blk.jpg','2012-02-15 17:25:46','2012-02-15 17:25:46',NULL,NULL,12,12,NULL,'n','n',NULL,NULL,NULL)
INSERT INTO [firecms_media] ( [id], [to_table], [to_table_id], [media_type], [media_order], [filename], [updated_on], [created_on], [media_name], [media_description], [created_by], [edited_by], [queue_id], [is_special], [is_banner], [embed_code], [original_link], [collection]) VALUES (7644,'table_content',3624,'picture',1,'ezp10.jpg','2012-02-15 17:25:47','2012-02-15 17:25:47',NULL,NULL,12,12,NULL,'n','n',NULL,NULL,NULL)
INSERT INTO [firecms_media] ( [id], [to_table], [to_table_id], [media_type], [media_order], [filename], [updated_on], [created_on], [media_name], [media_description], [created_by], [edited_by], [queue_id], [is_special], [is_banner], [embed_code], [original_link], [collection]) VALUES (7645,'table_content',3625,'picture',1,'fib120.jpg','2012-02-15 17:25:49','2012-02-15 17:25:49',NULL,NULL,12,12,NULL,'n','n',NULL,NULL,NULL)
INSERT INTO [firecms_media] ( [id], [to_table], [to_table_id], [media_type], [media_order], [filename], [updated_on], [created_on], [media_name], [media_description], [created_by], [edited_by], [queue_id], [is_special], [is_banner], [embed_code], [original_link], [collection]) VALUES (7994,'table_content',3930,'picture',2,'01ad1ff49d33b118028f20c2716fdeedwpsnpisc-05.jpg','2012-02-19 15:34:14','2012-02-19 15:34:14',NULL,NULL,12,12,NULL,'n','n',NULL,NULL,NULL)
INSERT INTO [firecms_media] ( [id], [to_table], [to_table_id], [media_type], [media_order], [filename], [updated_on], [created_on], [media_name], [media_description], [created_by], [edited_by], [queue_id], [is_special], [is_banner], [embed_code], [original_link], [collection]) VALUES (7943,'table_content',3660,'picture',2,'1839ac.jpg','2012-02-19 13:47:11','2012-02-19 13:47:11',NULL,NULL,12,12,NULL,'n','n',NULL,NULL,NULL)
INSERT INTO [firecms_media] ( [id], [to_table], [to_table_id], [media_type], [media_order], [filename], [updated_on], [created_on], [media_name], [media_description], [created_by], [edited_by], [queue_id], [is_special], [is_banner], [embed_code], [original_link], [collection]) VALUES (7650,'table_content',3630,'picture',1,'1829ac10ublk.jpg','2012-02-15 17:25:59','2012-02-15 17:25:59',NULL,NULL,12,12,NULL,'n','n',NULL,NULL,NULL)
INSERT INTO [firecms_media] ( [id], [to_table], [to_table_id], [media_type], [media_order], [filename], [updated_on], [created_on], [media_name], [media_description], [created_by], [edited_by], [queue_id], [is_special], [is_banner], [embed_code], [original_link], [collection]) VALUES (8025,'table_content',3933,'picture',4,'1dab35cbad5d6e406ea660614f757535grdblk2r1-05.jpg','2012-02-19 15:37:22','2012-02-19 15:37:22',NULL,NULL,12,12,NULL,'n','n',NULL,NULL,NULL)
INSERT INTO [firecms_media] ( [id], [to_table], [to_table_id], [media_type], [media_order], [filename], [updated_on], [created_on], [media_name], [media_description], [created_by], [edited_by], [queue_id], [is_special], [is_banner], [embed_code], [original_link], [collection]) VALUES (8026,'table_content',3918,'picture',3,'ae0d0c5b4b85e73656bb9b98419cace1grdblk4r1-05.jpg','2012-02-19 15:37:22','2012-02-19 15:37:22',NULL,NULL,12,12,NULL,'n','n',NULL,NULL,NULL)
INSERT INTO [firecms_media] ( [id], [to_table], [to_table_id], [media_type], [media_order], [filename], [updated_on], [created_on], [media_name], [media_description], [created_by], [edited_by], [queue_id], [is_special], [is_banner], [embed_code], [original_link], [collection]) VALUES (7653,'table_content',3633,'picture',1,'fnw10.jpg','2012-02-15 17:26:05','2012-02-15 17:26:05',NULL,NULL,12,12,NULL,'n','n',NULL,NULL,NULL)
INSERT INTO [firecms_media] ( [id], [to_table], [to_table_id], [media_type], [media_order], [filename], [updated_on], [created_on], [media_name], [media_description], [created_by], [edited_by], [queue_id], [is_special], [is_banner], [embed_code], [original_link], [collection]) VALUES (7655,'table_content',3635,'picture',1,'fs59bncu.jpg','2012-02-15 17:26:09','2012-02-15 17:26:09',NULL,NULL,12,12,NULL,'n','n',NULL,NULL,NULL)
INSERT INTO [firecms_media] ( [id], [to_table], [to_table_id], [media_type], [media_order], [filename], [updated_on], [created_on], [media_name], [media_description], [created_by], [edited_by], [queue_id], [is_special], [is_banner], [embed_code], [original_link], [collection]) VALUES (7656,'table_content',3636,'picture',1,'fs6bnc.jpg','2012-02-15 17:26:11','2012-02-15 17:26:11',NULL,NULL,12,12,NULL,'n','n',NULL,NULL,NULL)
INSERT INTO [firecms_media] ( [id], [to_table], [to_table_id], [media_type], [media_order], [filename], [updated_on], [created_on], [media_name], [media_description], [created_by], [edited_by], [queue_id], [is_special], [is_banner], [embed_code], [original_link], [collection]) VALUES (7657,'table_content',3637,'picture',1,'fs6rcau.jpg','2012-02-15 17:26:13','2012-02-15 17:26:13',NULL,NULL,12,12,NULL,'n','n',NULL,NULL,NULL)
INSERT INTO [firecms_media] ( [id], [to_table], [to_table_id], [media_type], [media_order], [filename], [updated_on], [created_on], [media_name], [media_description], [created_by], [edited_by], [queue_id], [is_special], [is_banner], [embed_code], [original_link], [collection]) VALUES (7658,'table_content',3638,'picture',1,'fscr-bk.jpg','2012-02-15 17:26:14','2012-02-15 17:26:14',NULL,NULL,12,12,NULL,'n','n',NULL,NULL,NULL)
CREATE TABLE [firecms_menus] ([id] BIGINT,[item_type] VARCHAR(150),[item_parent] BIGINT,[item_title] VARCHAR(1500),[content_id] BIGINT,[is_active] CHAR(1),[updated_on] DATETIME,[position] BIGINT,[menu_description] TEXT,[menu_title] TEXT,[menu_url] TEXT,[taxonomy_id] BIGINT,[menu_unique_id] VARCHAR(150)
);
INSERT INTO [firecms_menus] ( [id], [item_type], [item_parent], [item_title], [content_id], [is_active], [updated_on], [position], [menu_description], [menu_title], [menu_url], [taxonomy_id], [menu_unique_id]) VALUES (807,'menu',0,'Main menu',NULL,'y','2011-03-24 12:20:54',9999,'Main menu','Main menu',NULL,NULL,'main_menu')
INSERT INTO [firecms_menus] ( [id], [item_type], [item_parent], [item_title], [content_id], [is_active], [updated_on], [position], [menu_description], [menu_title], [menu_url], [taxonomy_id], [menu_unique_id]) VALUES (810,'menu_item',808,'dealers',749,'y','2011-03-24 12:22:12',1,NULL,NULL,NULL,NULL,NULL)
INSERT INTO [firecms_menus] ( [id], [item_type], [item_parent], [item_title], [content_id], [is_active], [updated_on], [position], [menu_description], [menu_title], [menu_url], [taxonomy_id], [menu_unique_id]) VALUES (811,'menu_item',808,'blog',564,'y','2011-03-24 12:22:12',2,NULL,NULL,NULL,NULL,NULL)
INSERT INTO [firecms_menus] ( [id], [item_type], [item_parent], [item_title], [content_id], [is_active], [updated_on], [position], [menu_description], [menu_title], [menu_url], [taxonomy_id], [menu_unique_id]) VALUES (844,'menu',0,NULL,NULL,'y','2011-04-19 18:50:02',9999,NULL,NULL,NULL,NULL,'header_menu')
INSERT INTO [firecms_menus] ( [id], [item_type], [item_parent], [item_title], [content_id], [is_active], [updated_on], [position], [menu_description], [menu_title], [menu_url], [taxonomy_id], [menu_unique_id]) VALUES (871,'menu_item',807,'Home',1,'y','2012-02-23 14:34:29',1,NULL,NULL,NULL,NULL,NULL)
INSERT INTO [firecms_menus] ( [id], [item_type], [item_parent], [item_title], [content_id], [is_active], [updated_on], [position], [menu_description], [menu_title], [menu_url], [taxonomy_id], [menu_unique_id]) VALUES (879,'menu',0,NULL,NULL,'y','2011-08-30 16:02:01',9999,NULL,NULL,NULL,NULL,'nav')
INSERT INTO [firecms_menus] ( [id], [item_type], [item_parent], [item_title], [content_id], [is_active], [updated_on], [position], [menu_description], [menu_title], [menu_url], [taxonomy_id], [menu_unique_id]) VALUES (910,'menu_item',807,'Jobs',3484,'y','2012-03-28 12:19:13',2,NULL,NULL,NULL,NULL,NULL)
CREATE TABLE [firecms_messages] ([id] BIGINT,[parent_id] BIGINT,[from_user] BIGINT,[to_user] BIGINT,[subject] VARCHAR(150),[message] TEXT,[is_read] CHAR(1),[deleted_from_sender] CHAR(1),[deleted_from_receiver] CHAR(1),[updated_on] DATETIME,[created_on] DATETIME,[created_by] BIGINT,[edited_by] BIGINT
);
INSERT INTO [firecms_messages] ( [id], [parent_id], [from_user], [to_user], [subject], [message], [is_read], [deleted_from_sender], [deleted_from_receiver], [updated_on], [created_on], [created_by], [edited_by]) VALUES (1,0,1726,1731,'hey','Hey Brandon, whats up ?','n','n','n','2010-12-28 02:28:08','2010-12-28 02:28:08',1726,1726)
INSERT INTO [firecms_messages] ( [id], [parent_id], [from_user], [to_user], [subject], [message], [is_read], [deleted_from_sender], [deleted_from_receiver], [updated_on], [created_on], [created_by], [edited_by]) VALUES (2,0,2,1801,'d','hy','y','n','n','2010-12-29 03:20:37','2010-12-29 03:20:18',2,1801)
INSERT INTO [firecms_messages] ( [id], [parent_id], [from_user], [to_user], [subject], [message], [is_read], [deleted_from_sender], [deleted_from_receiver], [updated_on], [created_on], [created_by], [edited_by]) VALUES (3,2,1801,2,NULL,'dff','y','n','n','2010-12-29 03:20:59','2010-12-29 03:20:40',1801,2)
INSERT INTO [firecms_messages] ( [id], [parent_id], [from_user], [to_user], [subject], [message], [is_read], [deleted_from_sender], [deleted_from_receiver], [updated_on], [created_on], [created_by], [edited_by]) VALUES (4,0,0,0,NULL,'','n','n','n','2010-12-29 03:20:40','2010-12-29 03:20:40',1801,1801)
INSERT INTO [firecms_messages] ( [id], [parent_id], [from_user], [to_user], [subject], [message], [is_read], [deleted_from_sender], [deleted_from_receiver], [updated_on], [created_on], [created_by], [edited_by]) VALUES (5,0,1,1761,'msg','Are you selling this for 25$ {SITE_URL}toy-swap/ps3-playstation','y','n','n','2010-12-31 12:09:41','2010-12-30 09:41:41',1,1761)
INSERT INTO [firecms_messages] ( [id], [parent_id], [from_user], [to_user], [subject], [message], [is_read], [deleted_from_sender], [deleted_from_receiver], [updated_on], [created_on], [created_by], [edited_by]) VALUES (6,0,1726,1300,'Talia','hey Talia....how is it going ?','y','n','n','2010-12-30 07:00:13','2010-12-30 11:54:10',1726,1300)
INSERT INTO [firecms_messages] ( [id], [parent_id], [from_user], [to_user], [subject], [message], [is_read], [deleted_from_sender], [deleted_from_receiver], [updated_on], [created_on], [created_by], [edited_by]) VALUES (7,0,1819,1096,'Hello','hey abby how are you want to be friends?','n','n','n','2010-12-30 10:10:42','2010-12-30 10:10:42',1819,1819)
INSERT INTO [firecms_messages] ( [id], [parent_id], [from_user], [to_user], [subject], [message], [is_read], [deleted_from_sender], [deleted_from_receiver], [updated_on], [created_on], [created_by], [edited_by]) VALUES (8,0,1819,521,'hello','hey emily wats up?','n','n','n','2010-12-30 10:16:40','2010-12-30 10:16:40',1819,1819)
INSERT INTO [firecms_messages] ( [id], [parent_id], [from_user], [to_user], [subject], [message], [is_read], [deleted_from_sender], [deleted_from_receiver], [updated_on], [created_on], [created_by], [edited_by]) VALUES (9,0,1819,1284,'hi','hello love the new Skid-e-kids!!','y','n','n','2011-01-01 01:28:36','2010-12-30 10:32:29',1819,1284)
INSERT INTO [firecms_messages] ( [id], [parent_id], [from_user], [to_user], [subject], [message], [is_read], [deleted_from_sender], [deleted_from_receiver], [updated_on], [created_on], [created_by], [edited_by]) VALUES (10,5,1761,1,NULL,'maybe...who wants to know..?','y','n','n','2011-01-01 01:24:45','2010-12-31 12:10:11',1761,1)
INSERT INTO [firecms_messages] ( [id], [parent_id], [from_user], [to_user], [subject], [message], [is_read], [deleted_from_sender], [deleted_from_receiver], [updated_on], [created_on], [created_by], [edited_by]) VALUES (11,0,0,0,NULL,'','n','n','n','2010-12-31 12:10:11','2010-12-31 12:10:11',1761,1761)
INSERT INTO [firecms_messages] ( [id], [parent_id], [from_user], [to_user], [subject], [message], [is_read], [deleted_from_sender], [deleted_from_receiver], [updated_on], [created_on], [created_by], [edited_by]) VALUES (13,0,1680,1761,'how much','hello how much','y','n','n','2011-01-01 08:50:13','2010-12-31 07:37:23',1680,1761)
INSERT INTO [firecms_messages] ( [id], [parent_id], [from_user], [to_user], [subject], [message], [is_read], [deleted_from_sender], [deleted_from_receiver], [updated_on], [created_on], [created_by], [edited_by]) VALUES (14,9,1284,1819,NULL,'Meee toooo! I love this site..','y','n','n','2011-01-01 02:55:51','2011-01-01 01:29:25',1284,1819)
INSERT INTO [firecms_messages] ( [id], [parent_id], [from_user], [to_user], [subject], [message], [is_read], [deleted_from_sender], [deleted_from_receiver], [updated_on], [created_on], [created_by], [edited_by]) VALUES (15,0,0,0,NULL,'','n','n','n','2011-01-01 01:29:25','2011-01-01 01:29:25',1284,1284)
INSERT INTO [firecms_messages] ( [id], [parent_id], [from_user], [to_user], [subject], [message], [is_read], [deleted_from_sender], [deleted_from_receiver], [updated_on], [created_on], [created_by], [edited_by]) VALUES (16,9,1819,1284,NULL,'yeah but it erased my old account so i had to make a new one :(','y','n','n','2011-01-01 02:58:05','2011-01-01 02:56:27',1819,1284)
INSERT INTO [firecms_messages] ( [id], [parent_id], [from_user], [to_user], [subject], [message], [is_read], [deleted_from_sender], [deleted_from_receiver], [updated_on], [created_on], [created_by], [edited_by]) VALUES (17,0,0,0,NULL,'','n','n','n','2011-01-01 02:56:27','2011-01-01 02:56:27',1819,1819)
INSERT INTO [firecms_messages] ( [id], [parent_id], [from_user], [to_user], [subject], [message], [is_read], [deleted_from_sender], [deleted_from_receiver], [updated_on], [created_on], [created_by], [edited_by]) VALUES (18,9,1284,1819,NULL,'Yeah , me too. I had to create a new one... which is okay though.. new year new friends LOL....','y','n','n','2011-01-01 03:05:17','2011-01-01 02:59:16',1284,1819)
INSERT INTO [firecms_messages] ( [id], [parent_id], [from_user], [to_user], [subject], [message], [is_read], [deleted_from_sender], [deleted_from_receiver], [updated_on], [created_on], [created_by], [edited_by]) VALUES (19,0,0,0,NULL,'','n','n','n','2011-01-01 02:59:16','2011-01-01 02:59:16',1284,1284)
INSERT INTO [firecms_messages] ( [id], [parent_id], [from_user], [to_user], [subject], [message], [is_read], [deleted_from_sender], [deleted_from_receiver], [updated_on], [created_on], [created_by], [edited_by]) VALUES (20,9,1819,1284,NULL,'yeah, lol this is just like facebook my mom has facebook so im kindof jealous but htis is awesome!!','y','n','n','2011-01-01 03:07:01','2011-01-01 03:07:01',1819,1819)
INSERT INTO [firecms_messages] ( [id], [parent_id], [from_user], [to_user], [subject], [message], [is_read], [deleted_from_sender], [deleted_from_receiver], [updated_on], [created_on], [created_by], [edited_by]) VALUES (21,0,0,0,NULL,'','n','n','n','2011-01-01 03:07:01','2011-01-01 03:07:01',1819,1819)
INSERT INTO [firecms_messages] ( [id], [parent_id], [from_user], [to_user], [subject], [message], [is_read], [deleted_from_sender], [deleted_from_receiver], [updated_on], [created_on], [created_by], [edited_by]) VALUES (22,9,1819,1819,NULL,'sorry i spelled (this) wrong','y','n','n','2011-01-01 03:26:40','2011-01-01 03:14:22',1819,1819)
INSERT INTO [firecms_messages] ( [id], [parent_id], [from_user], [to_user], [subject], [message], [is_read], [deleted_from_sender], [deleted_from_receiver], [updated_on], [created_on], [created_by], [edited_by]) VALUES (23,0,0,0,NULL,'','n','n','n','2011-01-01 03:14:22','2011-01-01 03:14:22',1819,1819)
INSERT INTO [firecms_messages] ( [id], [parent_id], [from_user], [to_user], [subject], [message], [is_read], [deleted_from_sender], [deleted_from_receiver], [updated_on], [created_on], [created_by], [edited_by]) VALUES (24,0,1819,457,'awesome','love your dog shes so cute!
','y','n','n','2011-01-08 07:20:02','2011-01-01 03:25:34',1819,457)
INSERT INTO [firecms_messages] ( [id], [parent_id], [from_user], [to_user], [subject], [message], [is_read], [deleted_from_sender], [deleted_from_receiver], [updated_on], [created_on], [created_by], [edited_by]) VALUES (25,0,1223,1388,'photo ','erin at first why did you steel my picture you know the one that had a girl uhhh that was me and how did u get it','n','n','n','2011-01-01 07:19:34','2011-01-01 07:19:34',1223,1223)
INSERT INTO [firecms_messages] ( [id], [parent_id], [from_user], [to_user], [subject], [message], [is_read], [deleted_from_sender], [deleted_from_receiver], [updated_on], [created_on], [created_by], [edited_by]) VALUES (26,9,1284,1819,NULL,'Yeah, I know.... My Mom said this is better than Facebook.','y','n','n','2011-01-02 03:35:22','2011-01-01 07:39:16',1284,1819)
INSERT INTO [firecms_messages] ( [id], [parent_id], [from_user], [to_user], [subject], [message], [is_read], [deleted_from_sender], [deleted_from_receiver], [updated_on], [created_on], [created_by], [edited_by]) VALUES (27,0,0,0,NULL,'','n','n','n','2011-01-01 07:39:16','2011-01-01 07:39:16',1284,1284)
INSERT INTO [firecms_messages] ( [id], [parent_id], [from_user], [to_user], [subject], [message], [is_read], [deleted_from_sender], [deleted_from_receiver], [updated_on], [created_on], [created_by], [edited_by]) VALUES (28,9,1819,1284,NULL,'yeah, but wish they had more games like facebook!!','y','n','n','2011-01-02 09:05:54','2011-01-01 10:06:01',1819,1284)
INSERT INTO [firecms_messages] ( [id], [parent_id], [from_user], [to_user], [subject], [message], [is_read], [deleted_from_sender], [deleted_from_receiver], [updated_on], [created_on], [created_by], [edited_by]) VALUES (29,0,0,0,NULL,'','n','n','n','2011-01-01 10:06:01','2011-01-01 10:06:01',1819,1819)
INSERT INTO [firecms_messages] ( [id], [parent_id], [from_user], [to_user], [subject], [message], [is_read], [deleted_from_sender], [deleted_from_receiver], [updated_on], [created_on], [created_by], [edited_by]) VALUES (30,9,1284,1819,NULL,'My Mom said that by next week, they would probably have more games that facebook..','y','n','n','2011-01-02 03:35:33','2011-01-02 09:06:48',1284,1819)
INSERT INTO [firecms_messages] ( [id], [parent_id], [from_user], [to_user], [subject], [message], [is_read], [deleted_from_sender], [deleted_from_receiver], [updated_on], [created_on], [created_by], [edited_by]) VALUES (31,0,0,0,NULL,'','n','n','n','2011-01-02 09:06:48','2011-01-02 09:06:48',1284,1284)
INSERT INTO [firecms_messages] ( [id], [parent_id], [from_user], [to_user], [subject], [message], [is_read], [deleted_from_sender], [deleted_from_receiver], [updated_on], [created_on], [created_by], [edited_by]) VALUES (32,0,1701,0,'sss','hi there. this is great improvement on this site','n','n','n','2011-01-02 12:29:05','2011-01-02 12:29:05',1701,1701)
INSERT INTO [firecms_messages] ( [id], [parent_id], [from_user], [to_user], [subject], [message], [is_read], [deleted_from_sender], [deleted_from_receiver], [updated_on], [created_on], [created_by], [edited_by]) VALUES (33,9,1819,1284,NULL,'you ave a sister right? or not because in your picture theres 2 of you ','y','n','n','2011-01-02 05:31:12','2011-01-02 03:36:15',1819,1284)
INSERT INTO [firecms_messages] ( [id], [parent_id], [from_user], [to_user], [subject], [message], [is_read], [deleted_from_sender], [deleted_from_receiver], [updated_on], [created_on], [created_by], [edited_by]) VALUES (34,0,0,0,NULL,'','n','n','n','2011-01-02 03:36:15','2011-01-02 03:36:15',1819,1819)
INSERT INTO [firecms_messages] ( [id], [parent_id], [from_user], [to_user], [subject], [message], [is_read], [deleted_from_sender], [deleted_from_receiver], [updated_on], [created_on], [created_by], [edited_by]) VALUES (35,9,1819,1819,NULL,'hey, how do you post those videos on the dashboard?
','y','n','n','2011-01-02 05:31:12','2011-01-02 04:33:01',1819,1284)
INSERT INTO [firecms_messages] ( [id], [parent_id], [from_user], [to_user], [subject], [message], [is_read], [deleted_from_sender], [deleted_from_receiver], [updated_on], [created_on], [created_by], [edited_by]) VALUES (36,0,0,0,NULL,'','n','n','n','2011-01-02 04:33:01','2011-01-02 04:33:01',1819,1819)
INSERT INTO [firecms_messages] ( [id], [parent_id], [from_user], [to_user], [subject], [message], [is_read], [deleted_from_sender], [deleted_from_receiver], [updated_on], [created_on], [created_by], [edited_by]) VALUES (37,9,1284,1819,NULL,'Go and copy the url of the video you want,,,,,,,  come back to you page .....click.. go to my videos.........click add new videos........then click post to my videos,,,,,,,,paste your video to...where it says,,,,,,, Paste your video url........... and then click....... add your video........you are done, and it will show up on your page,,  I hope this will help, my mom showed me how to do it....','y','n','n','2011-01-02 05:50:39','2011-01-02 05:39:37',1284,1819)
INSERT INTO [firecms_messages] ( [id], [parent_id], [from_user], [to_user], [subject], [message], [is_read], [deleted_from_sender], [deleted_from_receiver], [updated_on], [created_on], [created_by], [edited_by]) VALUES (38,0,0,0,NULL,'','n','n','n','2011-01-02 05:39:37','2011-01-02 05:39:37',1284,1284)
INSERT INTO [firecms_messages] ( [id], [parent_id], [from_user], [to_user], [subject], [message], [is_read], [deleted_from_sender], [deleted_from_receiver], [updated_on], [created_on], [created_by], [edited_by]) VALUES (39,9,1819,1284,NULL,'thanks alot it worked!!! you are a nice friend!!','y','n','n','2011-01-02 05:54:10','2011-01-02 05:51:36',1819,1284)
INSERT INTO [firecms_messages] ( [id], [parent_id], [from_user], [to_user], [subject], [message], [is_read], [deleted_from_sender], [deleted_from_receiver], [updated_on], [created_on], [created_by], [edited_by]) VALUES (40,0,0,0,NULL,'','n','n','n','2011-01-02 05:51:36','2011-01-02 05:51:36',1819,1819)
INSERT INTO [firecms_messages] ( [id], [parent_id], [from_user], [to_user], [subject], [message], [is_read], [deleted_from_sender], [deleted_from_receiver], [updated_on], [created_on], [created_by], [edited_by]) VALUES (41,0,1717,1762,'Cool','Hi Wanna Watch a Movie




































','n','n','n','2011-01-02 11:04:06','2011-01-02 11:04:06',1717,1717)
INSERT INTO [firecms_messages] ( [id], [parent_id], [from_user], [to_user], [subject], [message], [is_read], [deleted_from_sender], [deleted_from_receiver], [updated_on], [created_on], [created_by], [edited_by]) VALUES (42,0,2,17,'test ','hey','y','n','n','2011-01-03 07:23:46','2011-01-03 07:23:33',2,17)
INSERT INTO [firecms_messages] ( [id], [parent_id], [from_user], [to_user], [subject], [message], [is_read], [deleted_from_sender], [deleted_from_receiver], [updated_on], [created_on], [created_by], [edited_by]) VALUES (43,0,2,17,'1','1','y','n','n','2011-01-03 12:37:05','2011-01-03 07:24:04',2,17)
INSERT INTO [firecms_messages] ( [id], [parent_id], [from_user], [to_user], [subject], [message], [is_read], [deleted_from_sender], [deleted_from_receiver], [updated_on], [created_on], [created_by], [edited_by]) VALUES (44,13,1761,1680,NULL,'Which one ?','n','n','n','2011-01-03 12:19:13','2011-01-03 12:19:13',1761,1761)
INSERT INTO [firecms_messages] ( [id], [parent_id], [from_user], [to_user], [subject], [message], [is_read], [deleted_from_sender], [deleted_from_receiver], [updated_on], [created_on], [created_by], [edited_by]) VALUES (45,0,0,0,NULL,'','n','n','n','2011-01-03 12:19:13','2011-01-03 12:19:13',1761,1761)
INSERT INTO [firecms_messages] ( [id], [parent_id], [from_user], [to_user], [subject], [message], [is_read], [deleted_from_sender], [deleted_from_receiver], [updated_on], [created_on], [created_by], [edited_by]) VALUES (46,0,1275,1274,'hi','hey','y','n','n','2011-01-04 04:26:39','2011-01-04 04:25:58',1275,1274)
INSERT INTO [firecms_messages] ( [id], [parent_id], [from_user], [to_user], [subject], [message], [is_read], [deleted_from_sender], [deleted_from_receiver], [updated_on], [created_on], [created_by], [edited_by]) VALUES (47,46,1274,1275,NULL,'heylow','y','y','n','2011-01-04 04:28:46','2011-01-04 04:26:51',1274,1274)
INSERT INTO [firecms_messages] ( [id], [parent_id], [from_user], [to_user], [subject], [message], [is_read], [deleted_from_sender], [deleted_from_receiver], [updated_on], [created_on], [created_by], [edited_by]) VALUES (48,0,0,0,NULL,'','n','n','n','2011-01-04 04:26:51','2011-01-04 04:26:51',1274,1274)
INSERT INTO [firecms_messages] ( [id], [parent_id], [from_user], [to_user], [subject], [message], [is_read], [deleted_from_sender], [deleted_from_receiver], [updated_on], [created_on], [created_by], [edited_by]) VALUES (49,0,1320,1274,'hi','hi ','y','n','n','2011-01-04 04:28:33','2011-01-04 04:28:06',1320,1274)
INSERT INTO [firecms_messages] ( [id], [parent_id], [from_user], [to_user], [subject], [message], [is_read], [deleted_from_sender], [deleted_from_receiver], [updated_on], [created_on], [created_by], [edited_by]) VALUES (50,49,1274,1320,NULL,'hey','y','y','n','2011-01-04 09:16:09','2011-01-04 04:28:39',1274,1274)
INSERT INTO [firecms_messages] ( [id], [parent_id], [from_user], [to_user], [subject], [message], [is_read], [deleted_from_sender], [deleted_from_receiver], [updated_on], [created_on], [created_by], [edited_by]) VALUES (51,0,0,0,NULL,'','n','n','n','2011-01-04 04:28:39','2011-01-04 04:28:39',1274,1274)
CREATE TABLE [firecms_options] ([id] BIGINT,[option_key] VARCHAR(1500),[option_value] TEXT,[updated_on] DATETIME,[created_on] DATETIME,[option_group] VARCHAR(1500),[position] BIGINT,[option_key2] VARCHAR(1500),[option_value2] TEXT,[name] TEXT,[help] TEXT,[type] VARCHAR(1500),[module] VARCHAR(1500)
);
INSERT INTO [firecms_options] ( [id], [option_key], [option_value], [updated_on], [created_on], [option_group], [position], [option_key2], [option_value2], [name], [help], [type], [module]) VALUES (4505,'curent_template','jobs','2011-06-01 19:31:48','2011-05-30 10:57:00','',NULL,'','','Site template','Select the curent site template','select_template','')
INSERT INTO [firecms_options] ( [id], [option_key], [option_value], [updated_on], [created_on], [option_group], [position], [option_key2], [option_value2], [name], [help], [type], [module]) VALUES (5,'admin_default_items_per_page','100','2009-08-25 05:29:29','2009-01-29 08:30:37','paging',NULL,NULL,NULL,NULL,NULL,NULL,NULL)
INSERT INTO [firecms_options] ( [id], [option_key], [option_value], [updated_on], [created_on], [option_group], [position], [option_key2], [option_value2], [name], [help], [type], [module]) VALUES (8,'google_maps_api_key','ABQIAAAArYOnPKt1MSTaV0rI1ixJYBRu16aaRByz8-en9wATTWCp_Nn-TBTOuGdfGO84bCA_AYpaNEuV6FftPg','2009-08-21 08:15:37','2009-02-07 08:25:46','google',NULL,NULL,NULL,NULL,NULL,NULL,NULL)
INSERT INTO [firecms_options] ( [id], [option_key], [option_value], [updated_on], [created_on], [option_group], [position], [option_key2], [option_value2], [name], [help], [type], [module]) VALUES (10,'rss_language','EN','2009-05-21 02:32:05','2009-03-05 03:10:09','rss',NULL,NULL,NULL,NULL,NULL,NULL,NULL)
INSERT INTO [firecms_options] ( [id], [option_key], [option_value], [updated_on], [created_on], [option_group], [position], [option_key2], [option_value2], [name], [help], [type], [module]) VALUES (11,'creator_email','info@microweber.com','2010-11-04 08:58:57','2009-03-05 03:12:30','rss',NULL,NULL,'',NULL,NULL,NULL,NULL)
INSERT INTO [firecms_options] ( [id], [option_key], [option_value], [updated_on], [created_on], [option_group], [position], [option_key2], [option_value2], [name], [help], [type], [module]) VALUES (4116,'rss_title','RSS Feed','2011-03-18 05:18:11','2011-03-18 05:18:11',NULL,NULL,NULL,'','RSS title','Set the RSS feed title','text','')
INSERT INTO [firecms_options] ( [id], [option_key], [option_value], [updated_on], [created_on], [option_group], [position], [option_key2], [option_value2], [name], [help], [type], [module]) VALUES (4117,'mailform_subject','[Mailform]','2011-03-18 05:18:11','2011-03-18 05:18:11',NULL,NULL,NULL,'','Default subject from the site mailforms','Type the desired subject','text','')
INSERT INTO [firecms_options] ( [id], [option_key], [option_value], [updated_on], [created_on], [option_group], [position], [option_key2], [option_value2], [name], [help], [type], [module]) VALUES (21,'content_types','inherit, posts,
shop, videos','2010-11-25 11:52:30','2009-08-15 03:12:56','advanced2',NULL,NULL,'',NULL,NULL,NULL,NULL)
INSERT INTO [firecms_options] ( [id], [option_key], [option_value], [updated_on], [created_on], [option_group], [position], [option_key2], [option_value2], [name], [help], [type], [module]) VALUES (709,'mailform_autoreply_order','Order confirmation {order_id}','2011-02-10 05:52:46','2009-10-11 01:47:10','mailform',NULL,'','Your order (id: {order_id}) has been placed, but it will not be processed until we recieve payment confirmation from Paypal.   Skidekids ',NULL,NULL,NULL,NULL)
INSERT INTO [firecms_options] ( [id], [option_key], [option_value], [updated_on], [created_on], [option_group], [position], [option_key2], [option_value2], [name], [help], [type], [module]) VALUES (4005,'ecnryption_hash','1045215363381963791','2010-10-24 01:50:14','2010-10-24 01:50:14','security',NULL,NULL,NULL,NULL,NULL,NULL,NULL)
INSERT INTO [firecms_options] ( [id], [option_key], [option_value], [updated_on], [created_on], [option_group], [position], [option_key2], [option_value2], [name], [help], [type], [module]) VALUES (4006,'accounts_expiration','31 days','2011-02-10 05:52:35','2010-12-13 02:30:43','users',NULL,'','',NULL,NULL,NULL,NULL)
INSERT INTO [firecms_options] ( [id], [option_key], [option_value], [updated_on], [created_on], [option_group], [position], [option_key2], [option_value2], [name], [help], [type], [module]) VALUES (4010,'copyright_text_footer','','2011-06-10 16:44:48',NULL,'',0,'editable_region','',NULL,NULL,NULL,NULL)
INSERT INTO [firecms_options] ( [id], [option_key], [option_value], [updated_on], [created_on], [option_group], [position], [option_key2], [option_value2], [name], [help], [type], [module]) VALUES (4011,'foot_text1','','2011-06-10 16:45:43',NULL,'',0,'editable_region','',NULL,NULL,NULL,NULL)
INSERT INTO [firecms_options] ( [id], [option_key], [option_value], [updated_on], [created_on], [option_group], [position], [option_key2], [option_value2], [name], [help], [type], [module]) VALUES (4012,'welcome_text','','2011-06-10 16:44:56',NULL,'',0,'editable_region','',NULL,NULL,NULL,NULL)
INSERT INTO [firecms_options] ( [id], [option_key], [option_value], [updated_on], [created_on], [option_group], [position], [option_key2], [option_value2], [name], [help], [type], [module]) VALUES (4533,'mailform_to','peter@ooyes.net','2011-06-28 15:12:47','2011-06-10 17:20:57','',NULL,'','',NULL,NULL,NULL,NULL)
INSERT INTO [firecms_options] ( [id], [option_key], [option_value], [updated_on], [created_on], [option_group], [position], [option_key2], [option_value2], [name], [help], [type], [module]) VALUES (4121,NULL,NULL,'2011-04-29 18:29:09','2011-04-29 18:29:09','module_27348085',NULL,NULL,NULL,NULL,NULL,NULL,'media/gallery')
INSERT INTO [firecms_options] ( [id], [option_key], [option_value], [updated_on], [created_on], [option_group], [position], [option_key2], [option_value2], [name], [help], [type], [module]) VALUES (4122,NULL,'post','2011-04-29 18:43:02','2011-04-29 18:43:02','module_29362397',NULL,NULL,NULL,NULL,NULL,NULL,'media/gallery')
INSERT INTO [firecms_options] ( [id], [option_key], [option_value], [updated_on], [created_on], [option_group], [position], [option_key2], [option_value2], [name], [help], [type], [module]) VALUES (4496,'description','ssaaaads','2011-05-27 10:46:19','2011-05-27 10:46:19','content-category_tree',NULL,NULL,NULL,NULL,NULL,NULL,NULL)
INSERT INTO [firecms_options] ( [id], [option_key], [option_value], [updated_on], [created_on], [option_group], [position], [option_key2], [option_value2], [name], [help], [type], [module]) VALUES (4507,'name','???','2011-06-08 21:31:56','2011-06-08 21:31:56','cart_items',NULL,NULL,NULL,NULL,NULL,NULL,NULL)
INSERT INTO [firecms_options] ( [id], [option_key], [option_value], [updated_on], [created_on], [option_group], [position], [option_key2], [option_value2], [name], [help], [type], [module]) VALUES (4518,'default_items_per_page','30','2012-02-19 18:26:21','2011-06-09 18:23:45','paging',NULL,'','',NULL,'',NULL,NULL)
INSERT INTO [firecms_options] ( [id], [option_key], [option_value], [updated_on], [created_on], [option_group], [position], [option_key2], [option_value2], [name], [help], [type], [module]) VALUES (4603,'content_meta_title','','2011-08-30 16:00:44','2011-08-30 16:00:44',NULL,NULL,NULL,'','Meta title','Edit the default meta title','text','')
INSERT INTO [firecms_options] ( [id], [option_key], [option_value], [updated_on], [created_on], [option_group], [position], [option_key2], [option_value2], [name], [help], [type], [module]) VALUES (4604,'content_meta_description','','2011-08-30 16:00:45','2011-08-30 16:00:45',NULL,NULL,NULL,'','Meta description','Edit the default meta description','text','')
INSERT INTO [firecms_options] ( [id], [option_key], [option_value], [updated_on], [created_on], [option_group], [position], [option_key2], [option_value2], [name], [help], [type], [module]) VALUES (4605,'content_meta_keywords','','2011-08-30 16:00:45','2011-08-30 16:00:45',NULL,NULL,NULL,'','Meta keywords','Edit the default meta keywords','text','')
INSERT INTO [firecms_options] ( [id], [option_key], [option_value], [updated_on], [created_on], [option_group], [position], [option_key2], [option_value2], [name], [help], [type], [module]) VALUES (4606,'forgot_pass_email_from','noreply@microweber.com','2011-08-30 16:00:45','2011-08-30 16:00:45',NULL,NULL,NULL,'','Forgot password email from','We will send the reset password link from this email','email','')
INSERT INTO [firecms_options] ( [id], [option_key], [option_value], [updated_on], [created_on], [option_group], [position], [option_key2], [option_value2], [name], [help], [type], [module]) VALUES (4607,'content_meta_title','','2011-09-02 18:19:38','2011-09-02 18:19:38',NULL,NULL,NULL,'','Meta title','Edit the default meta title','text','')
INSERT INTO [firecms_options] ( [id], [option_key], [option_value], [updated_on], [created_on], [option_group], [position], [option_key2], [option_value2], [name], [help], [type], [module]) VALUES (4608,'content_meta_description','','2011-09-02 18:19:38','2011-09-02 18:19:38',NULL,NULL,NULL,'','Meta description','Edit the default meta description','text','')
INSERT INTO [firecms_options] ( [id], [option_key], [option_value], [updated_on], [created_on], [option_group], [position], [option_key2], [option_value2], [name], [help], [type], [module]) VALUES (4609,'content_meta_keywords','','2011-09-02 18:19:38','2011-09-02 18:19:38',NULL,NULL,NULL,'','Meta keywords','Edit the default meta keywords','text','')
INSERT INTO [firecms_options] ( [id], [option_key], [option_value], [updated_on], [created_on], [option_group], [position], [option_key2], [option_value2], [name], [help], [type], [module]) VALUES (4610,'content_meta_title','','2011-09-02 18:28:32','2011-09-02 18:28:32',NULL,NULL,NULL,'','Meta title','Edit the default meta title','text','')
INSERT INTO [firecms_options] ( [id], [option_key], [option_value], [updated_on], [created_on], [option_group], [position], [option_key2], [option_value2], [name], [help], [type], [module]) VALUES (4611,'content_meta_description','','2011-09-02 18:28:32','2011-09-02 18:28:32',NULL,NULL,NULL,'','Meta description','Edit the default meta description','text','')
INSERT INTO [firecms_options] ( [id], [option_key], [option_value], [updated_on], [created_on], [option_group], [position], [option_key2], [option_value2], [name], [help], [type], [module]) VALUES (4612,'content_meta_keywords','','2011-09-02 18:28:32','2011-09-02 18:28:32',NULL,NULL,NULL,'','Meta keywords','Edit the default meta keywords','text','')
INSERT INTO [firecms_options] ( [id], [option_key], [option_value], [updated_on], [created_on], [option_group], [position], [option_key2], [option_value2], [name], [help], [type], [module]) VALUES (4613,'content_meta_title','','2011-09-08 12:50:25','2011-09-08 12:50:25',NULL,NULL,NULL,'','Meta title','Edit the default meta title','text','')
INSERT INTO [firecms_options] ( [id], [option_key], [option_value], [updated_on], [created_on], [option_group], [position], [option_key2], [option_value2], [name], [help], [type], [module]) VALUES (4614,'content_meta_description','','2011-09-08 12:50:25','2011-09-08 12:50:25',NULL,NULL,NULL,'','Meta description','Edit the default meta description','text','')
INSERT INTO [firecms_options] ( [id], [option_key], [option_value], [updated_on], [created_on], [option_group], [position], [option_key2], [option_value2], [name], [help], [type], [module]) VALUES (4615,'content_meta_keywords','','2011-09-08 12:50:25','2011-09-08 12:50:25',NULL,NULL,NULL,'','Meta keywords','Edit the default meta keywords','text','')
INSERT INTO [firecms_options] ( [id], [option_key], [option_value], [updated_on], [created_on], [option_group], [position], [option_key2], [option_value2], [name], [help], [type], [module]) VALUES (4616,'content_meta_title','','2011-10-18 13:05:46','2011-10-18 13:05:46',NULL,NULL,NULL,'','Meta title','Edit the default meta title','text','')
INSERT INTO [firecms_options] ( [id], [option_key], [option_value], [updated_on], [created_on], [option_group], [position], [option_key2], [option_value2], [name], [help], [type], [module]) VALUES (4617,'content_meta_description','','2011-10-18 13:05:46','2011-10-18 13:05:46',NULL,NULL,NULL,'','Meta description','Edit the default meta description','text','')
INSERT INTO [firecms_options] ( [id], [option_key], [option_value], [updated_on], [created_on], [option_group], [position], [option_key2], [option_value2], [name], [help], [type], [module]) VALUES (4618,'content_meta_keywords','','2011-10-18 13:05:46','2011-10-18 13:05:46',NULL,NULL,NULL,'','Meta keywords','Edit the default meta keywords','text','')
INSERT INTO [firecms_options] ( [id], [option_key], [option_value], [updated_on], [created_on], [option_group], [position], [option_key2], [option_value2], [name], [help], [type], [module]) VALUES (4619,'content_meta_title','','2011-10-18 13:07:43','2011-10-18 13:07:43',NULL,NULL,NULL,'','Meta title','Edit the default meta title','text','')
INSERT INTO [firecms_options] ( [id], [option_key], [option_value], [updated_on], [created_on], [option_group], [position], [option_key2], [option_value2], [name], [help], [type], [module]) VALUES (4620,'content_meta_title','','2011-10-18 13:07:43','2011-10-18 13:07:43',NULL,NULL,NULL,'','Meta title','Edit the default meta title','text','')
INSERT INTO [firecms_options] ( [id], [option_key], [option_value], [updated_on], [created_on], [option_group], [position], [option_key2], [option_value2], [name], [help], [type], [module]) VALUES (4621,'content_meta_description','','2011-10-18 13:07:43','2011-10-18 13:07:43',NULL,NULL,NULL,'','Meta description','Edit the default meta description','text','')
INSERT INTO [firecms_options] ( [id], [option_key], [option_value], [updated_on], [created_on], [option_group], [position], [option_key2], [option_value2], [name], [help], [type], [module]) VALUES (4622,'content_meta_description','','2011-10-18 13:07:43','2011-10-18 13:07:43',NULL,NULL,NULL,'','Meta description','Edit the default meta description','text','')
INSERT INTO [firecms_options] ( [id], [option_key], [option_value], [updated_on], [created_on], [option_group], [position], [option_key2], [option_value2], [name], [help], [type], [module]) VALUES (4623,'content_meta_keywords','','2011-10-18 13:07:43','2011-10-18 13:07:43',NULL,NULL,NULL,'','Meta keywords','Edit the default meta keywords','text','')
INSERT INTO [firecms_options] ( [id], [option_key], [option_value], [updated_on], [created_on], [option_group], [position], [option_key2], [option_value2], [name], [help], [type], [module]) VALUES (4624,'content_meta_keywords','','2011-10-18 13:07:43','2011-10-18 13:07:43',NULL,NULL,NULL,'','Meta keywords','Edit the default meta keywords','text','')
INSERT INTO [firecms_options] ( [id], [option_key], [option_value], [updated_on], [created_on], [option_group], [position], [option_key2], [option_value2], [name], [help], [type], [module]) VALUES (4625,'content_meta_title','','2011-10-18 14:26:23','2011-10-18 14:26:23',NULL,NULL,NULL,'','Meta title','Edit the default meta title','text','')
INSERT INTO [firecms_options] ( [id], [option_key], [option_value], [updated_on], [created_on], [option_group], [position], [option_key2], [option_value2], [name], [help], [type], [module]) VALUES (4626,'content_meta_description','','2011-10-18 14:26:23','2011-10-18 14:26:23',NULL,NULL,NULL,'','Meta description','Edit the default meta description','text','')
INSERT INTO [firecms_options] ( [id], [option_key], [option_value], [updated_on], [created_on], [option_group], [position], [option_key2], [option_value2], [name], [help], [type], [module]) VALUES (4627,'content_meta_keywords','','2011-10-18 14:26:23','2011-10-18 14:26:23',NULL,NULL,NULL,'','Meta keywords','Edit the default meta keywords','text','')
INSERT INTO [firecms_options] ( [id], [option_key], [option_value], [updated_on], [created_on], [option_group], [position], [option_key2], [option_value2], [name], [help], [type], [module]) VALUES (4628,'content_meta_title','','2011-10-19 10:14:44','2011-10-19 10:14:44',NULL,NULL,NULL,'','Meta title','Edit the default meta title','text','')
INSERT INTO [firecms_options] ( [id], [option_key], [option_value], [updated_on], [created_on], [option_group], [position], [option_key2], [option_value2], [name], [help], [type], [module]) VALUES (4629,'content_meta_description','','2011-10-19 10:14:44','2011-10-19 10:14:44',NULL,NULL,NULL,'','Meta description','Edit the default meta description','text','')
INSERT INTO [firecms_options] ( [id], [option_key], [option_value], [updated_on], [created_on], [option_group], [position], [option_key2], [option_value2], [name], [help], [type], [module]) VALUES (4630,'content_meta_keywords','','2011-10-19 10:14:44','2011-10-19 10:14:44',NULL,NULL,NULL,'','Meta keywords','Edit the default meta keywords','text','')
INSERT INTO [firecms_options] ( [id], [option_key], [option_value], [updated_on], [created_on], [option_group], [position], [option_key2], [option_value2], [name], [help], [type], [module]) VALUES (4631,'content_meta_title','','2011-11-03 11:14:51','2011-11-03 11:14:51',NULL,NULL,NULL,'','Meta title','Edit the default meta title','text','')
INSERT INTO [firecms_options] ( [id], [option_key], [option_value], [updated_on], [created_on], [option_group], [position], [option_key2], [option_value2], [name], [help], [type], [module]) VALUES (4632,'content_meta_description','','2011-11-03 11:14:51','2011-11-03 11:14:51',NULL,NULL,NULL,'','Meta description','Edit the default meta description','text','')
CREATE TABLE [firecms_reports] ([id] BIGINT,[to_table] VARCHAR(150),[to_table_id] BIGINT,[created_on] DATETIME,[user_ip] VARCHAR(1500),[session_id] VARCHAR(1500),[created_by] BIGINT,[updated_on] DATETIME
);
CREATE TABLE [firecms_sessions] ([session_id] VARCHAR(40),[ip_address] VARCHAR(16),[user_agent] VARCHAR(50),[last_activity] BIGINT,[session_data] TEXT,[user_data] TEXT
);
INSERT INTO [firecms_sessions] ( [session_id], [ip_address], [user_agent], [last_activity], [session_data], [user_data]) VALUES ('fac48a13e0bae2c6a13c520e4a320b82','127.0.0.1','Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/53',1335935620,'','')
CREATE TABLE [firecms_taxonomy] ([id] BIGINT,[taxonomy_type] VARCHAR(1500),[taxonomy_value] VARCHAR(1500),[parent_id] BIGINT,[content_type] VARCHAR(1500),[updated_on] DATETIME,[taxonomy_description] TEXT,[to_table] VARCHAR(1500),[to_table_id] BIGINT,[created_on] DATETIME,[position] BIGINT,[content_body] TEXT,[created_by] BIGINT,[edited_by] BIGINT,[users_can_create_subcategories] CHAR(1),[users_can_create_subcategories_user_level_required] BIGINT,[users_can_create_content] CHAR(1),[users_can_create_content_user_level_required] BIGINT,[taxonomy_content_type] VARCHAR(1500),[taxonomy_silo_keywords] TEXT
);
INSERT INTO [firecms_taxonomy] ( [id], [taxonomy_type], [taxonomy_value], [parent_id], [content_type], [updated_on], [taxonomy_description], [to_table], [to_table_id], [created_on], [position], [content_body], [created_by], [edited_by], [users_can_create_subcategories], [users_can_create_subcategories_user_level_required], [users_can_create_content], [users_can_create_content_user_level_required], [taxonomy_content_type], [taxonomy_silo_keywords]) VALUES (100002038,'category','Jobs',0,'','2012-03-27 16:15:22','',NULL,NULL,'2012-01-29 20:35:27',1,'',12,14,'n',0,'y',NULL,NULL,NULL)
INSERT INTO [firecms_taxonomy] ( [id], [taxonomy_type], [taxonomy_value], [parent_id], [content_type], [updated_on], [taxonomy_description], [to_table], [to_table_id], [created_on], [position], [content_body], [created_by], [edited_by], [users_can_create_subcategories], [users_can_create_subcategories_user_level_required], [users_can_create_content], [users_can_create_content_user_level_required], [taxonomy_content_type], [taxonomy_silo_keywords]) VALUES (100002163,'category','Doctor',100002038,'','2012-03-27 16:15:49','',NULL,NULL,'2012-03-27 16:15:49',999,'',14,14,'',0,'',NULL,NULL,NULL)
INSERT INTO [firecms_taxonomy] ( [id], [taxonomy_type], [taxonomy_value], [parent_id], [content_type], [updated_on], [taxonomy_description], [to_table], [to_table_id], [created_on], [position], [content_body], [created_by], [edited_by], [users_can_create_subcategories], [users_can_create_subcategories_user_level_required], [users_can_create_content], [users_can_create_content_user_level_required], [taxonomy_content_type], [taxonomy_silo_keywords]) VALUES (100002164,'category','test',100002038,'','2012-06-01 18:20:05','',NULL,NULL,'2012-06-01 18:20:05',999,'',14,14,'',0,'',NULL,NULL,NULL)
CREATE TABLE [firecms_taxonomy_items] ([id] BIGINT,[parent_id] BIGINT,[to_table] VARCHAR(35),[to_table_id] BIGINT,[content_type] VARCHAR(35),[taxonomy_type] VARCHAR(35)
);
INSERT INTO [firecms_taxonomy_items] ( [id], [parent_id], [to_table], [to_table_id], [content_type], [taxonomy_type]) VALUES (7913,0,'table_content',3309,'post','category_item')
INSERT INTO [firecms_taxonomy_items] ( [id], [parent_id], [to_table], [to_table_id], [content_type], [taxonomy_type]) VALUES (8017,0,'table_content',3322,'post','category_item')
INSERT INTO [firecms_taxonomy_items] ( [id], [parent_id], [to_table], [to_table_id], [content_type], [taxonomy_type]) VALUES (7865,0,'table_content',3293,'post','category_item')
INSERT INTO [firecms_taxonomy_items] ( [id], [parent_id], [to_table], [to_table_id], [content_type], [taxonomy_type]) VALUES (7866,0,'table_content',3293,'post','category_item')
INSERT INTO [firecms_taxonomy_items] ( [id], [parent_id], [to_table], [to_table_id], [content_type], [taxonomy_type]) VALUES (7873,0,'table_content',3295,'post','category_item')
INSERT INTO [firecms_taxonomy_items] ( [id], [parent_id], [to_table], [to_table_id], [content_type], [taxonomy_type]) VALUES (7874,0,'table_content',3295,'post','category_item')
INSERT INTO [firecms_taxonomy_items] ( [id], [parent_id], [to_table], [to_table_id], [content_type], [taxonomy_type]) VALUES (7875,0,'table_content',3296,'post','category_item')
INSERT INTO [firecms_taxonomy_items] ( [id], [parent_id], [to_table], [to_table_id], [content_type], [taxonomy_type]) VALUES (7876,0,'table_content',3296,'post','category_item')
INSERT INTO [firecms_taxonomy_items] ( [id], [parent_id], [to_table], [to_table_id], [content_type], [taxonomy_type]) VALUES (7877,0,'table_content',3297,'post','category_item')
INSERT INTO [firecms_taxonomy_items] ( [id], [parent_id], [to_table], [to_table_id], [content_type], [taxonomy_type]) VALUES (7878,0,'table_content',3297,'post','category_item')
INSERT INTO [firecms_taxonomy_items] ( [id], [parent_id], [to_table], [to_table_id], [content_type], [taxonomy_type]) VALUES (7879,0,'table_content',3298,'post','category_item')
INSERT INTO [firecms_taxonomy_items] ( [id], [parent_id], [to_table], [to_table_id], [content_type], [taxonomy_type]) VALUES (7880,0,'table_content',3298,'post','category_item')
INSERT INTO [firecms_taxonomy_items] ( [id], [parent_id], [to_table], [to_table_id], [content_type], [taxonomy_type]) VALUES (7887,0,'table_content',3302,'post','category_item')
INSERT INTO [firecms_taxonomy_items] ( [id], [parent_id], [to_table], [to_table_id], [content_type], [taxonomy_type]) VALUES (9475,0,'table_content',3446,'post','category_item')
INSERT INTO [firecms_taxonomy_items] ( [id], [parent_id], [to_table], [to_table_id], [content_type], [taxonomy_type]) VALUES (8994,1681,'table_content',3382,'post','category_item')
INSERT INTO [firecms_taxonomy_items] ( [id], [parent_id], [to_table], [to_table_id], [content_type], [taxonomy_type]) VALUES (9463,0,'table_content',3433,'post','category_item')
INSERT INTO [firecms_taxonomy_items] ( [id], [parent_id], [to_table], [to_table_id], [content_type], [taxonomy_type]) VALUES (9307,1681,'table_content',3393,'post','category_item')
INSERT INTO [firecms_taxonomy_items] ( [id], [parent_id], [to_table], [to_table_id], [content_type], [taxonomy_type]) VALUES (9267,1681,'table_content',3392,'post','category_item')
INSERT INTO [firecms_taxonomy_items] ( [id], [parent_id], [to_table], [to_table_id], [content_type], [taxonomy_type]) VALUES (9263,1681,'table_content',3391,'post','category_item')
INSERT INTO [firecms_taxonomy_items] ( [id], [parent_id], [to_table], [to_table_id], [content_type], [taxonomy_type]) VALUES (9305,1681,'table_content',3394,'post','category_item')
INSERT INTO [firecms_taxonomy_items] ( [id], [parent_id], [to_table], [to_table_id], [content_type], [taxonomy_type]) VALUES (9476,0,'table_content',3447,'post','category_item')
INSERT INTO [firecms_taxonomy_items] ( [id], [parent_id], [to_table], [to_table_id], [content_type], [taxonomy_type]) VALUES (9538,100002038,'table_content',3485,'post','category_item')
INSERT INTO [firecms_taxonomy_items] ( [id], [parent_id], [to_table], [to_table_id], [content_type], [taxonomy_type]) VALUES (9499,0,'table_content',3452,'post','category_item')
INSERT INTO [firecms_taxonomy_items] ( [id], [parent_id], [to_table], [to_table_id], [content_type], [taxonomy_type]) VALUES (9537,100002038,'table_content',3485,'post','category_item')
INSERT INTO [firecms_taxonomy_items] ( [id], [parent_id], [to_table], [to_table_id], [content_type], [taxonomy_type]) VALUES (9534,100002038,'table_content',3486,'post','category_item')
INSERT INTO [firecms_taxonomy_items] ( [id], [parent_id], [to_table], [to_table_id], [content_type], [taxonomy_type]) VALUES (9533,0,'table_content',3486,'post','category_item')
INSERT INTO [firecms_taxonomy_items] ( [id], [parent_id], [to_table], [to_table_id], [content_type], [taxonomy_type]) VALUES (9594,100002038,'table_content',3487,'post','category_item')
INSERT INTO [firecms_taxonomy_items] ( [id], [parent_id], [to_table], [to_table_id], [content_type], [taxonomy_type]) VALUES (9544,100002041,'table_content',3488,'post','category_item')
INSERT INTO [firecms_taxonomy_items] ( [id], [parent_id], [to_table], [to_table_id], [content_type], [taxonomy_type]) VALUES (9543,100002041,'table_content',3488,'post','category_item')
INSERT INTO [firecms_taxonomy_items] ( [id], [parent_id], [to_table], [to_table_id], [content_type], [taxonomy_type]) VALUES (9593,100002041,'table_content',3487,'post','category_item')
INSERT INTO [firecms_taxonomy_items] ( [id], [parent_id], [to_table], [to_table_id], [content_type], [taxonomy_type]) VALUES (9545,100002038,'table_content',3488,'post','category_item')
INSERT INTO [firecms_taxonomy_items] ( [id], [parent_id], [to_table], [to_table_id], [content_type], [taxonomy_type]) VALUES (9546,100002038,'table_content',3488,'post','category_item')
INSERT INTO [firecms_taxonomy_items] ( [id], [parent_id], [to_table], [to_table_id], [content_type], [taxonomy_type]) VALUES (9586,100002038,'table_content',3490,'post','category_item')
INSERT INTO [firecms_taxonomy_items] ( [id], [parent_id], [to_table], [to_table_id], [content_type], [taxonomy_type]) VALUES (9585,100002042,'table_content',3490,'post','category_item')
INSERT INTO [firecms_taxonomy_items] ( [id], [parent_id], [to_table], [to_table_id], [content_type], [taxonomy_type]) VALUES (9584,100002039,'table_content',3490,'post','category_item')
INSERT INTO [firecms_taxonomy_items] ( [id], [parent_id], [to_table], [to_table_id], [content_type], [taxonomy_type]) VALUES (9583,100002041,'table_content',3490,'post','category_item')
INSERT INTO [firecms_taxonomy_items] ( [id], [parent_id], [to_table], [to_table_id], [content_type], [taxonomy_type]) VALUES (9590,100002038,'table_content',3489,'post','category_item')
INSERT INTO [firecms_taxonomy_items] ( [id], [parent_id], [to_table], [to_table_id], [content_type], [taxonomy_type]) VALUES (9589,100002042,'table_content',3489,'post','category_item')
INSERT INTO [firecms_taxonomy_items] ( [id], [parent_id], [to_table], [to_table_id], [content_type], [taxonomy_type]) VALUES (9588,100002039,'table_content',3489,'post','category_item')
INSERT INTO [firecms_taxonomy_items] ( [id], [parent_id], [to_table], [to_table_id], [content_type], [taxonomy_type]) VALUES (9587,100002041,'table_content',3489,'post','category_item')
INSERT INTO [firecms_taxonomy_items] ( [id], [parent_id], [to_table], [to_table_id], [content_type], [taxonomy_type]) VALUES (9578,100002038,'table_content',3492,'post','category_item')
INSERT INTO [firecms_taxonomy_items] ( [id], [parent_id], [to_table], [to_table_id], [content_type], [taxonomy_type]) VALUES (9582,100002038,'table_content',3491,'post','category_item')
INSERT INTO [firecms_taxonomy_items] ( [id], [parent_id], [to_table], [to_table_id], [content_type], [taxonomy_type]) VALUES (9577,100002039,'table_content',3492,'post','category_item')
INSERT INTO [firecms_taxonomy_items] ( [id], [parent_id], [to_table], [to_table_id], [content_type], [taxonomy_type]) VALUES (9581,100002039,'table_content',3491,'post','category_item')
INSERT INTO [firecms_taxonomy_items] ( [id], [parent_id], [to_table], [to_table_id], [content_type], [taxonomy_type]) VALUES (9576,100002041,'table_content',3492,'post','category_item')
INSERT INTO [firecms_taxonomy_items] ( [id], [parent_id], [to_table], [to_table_id], [content_type], [taxonomy_type]) VALUES (9575,100002040,'table_content',3492,'post','category_item')
INSERT INTO [firecms_taxonomy_items] ( [id], [parent_id], [to_table], [to_table_id], [content_type], [taxonomy_type]) VALUES (9580,100002041,'table_content',3491,'post','category_item')
INSERT INTO [firecms_taxonomy_items] ( [id], [parent_id], [to_table], [to_table_id], [content_type], [taxonomy_type]) VALUES (9579,100002040,'table_content',3491,'post','category_item')
INSERT INTO [firecms_taxonomy_items] ( [id], [parent_id], [to_table], [to_table_id], [content_type], [taxonomy_type]) VALUES (9563,0,'table_content',3493,'post','category_item')
INSERT INTO [firecms_taxonomy_items] ( [id], [parent_id], [to_table], [to_table_id], [content_type], [taxonomy_type]) VALUES (9564,0,'table_content',3494,'post','category_item')
CREATE TABLE [firecms_users] ([id] BIGINT,[username] VARCHAR(450),[password] VARCHAR(450),[email] VARCHAR(450),[is_active] CHAR(1),[is_admin] CHAR(1),[updated_on] DATETIME,[created_on] DATETIME,[first_name] VARCHAR(450),[last_name] VARCHAR(450),[user_information] TEXT,[is_verified] CHAR(1),[fb_uid] VARCHAR(50),[created_by] BIGINT,[edited_by] BIGINT,[parent_id] BIGINT,[expires_on] DATETIME,[subscr_id] VARCHAR(50),[is_public] CHAR(1),[role] VARCHAR(250)
);
INSERT INTO [firecms_users] ( [id], [username], [password], [email], [is_active], [is_admin], [updated_on], [created_on], [first_name], [last_name], [user_information], [is_verified], [fb_uid], [created_by], [edited_by], [parent_id], [expires_on], [subscr_id], [is_public], [role]) VALUES (10,'admin','digi82','ebuxton@digital-connections.tv','y','y','2012-02-08 06:41:20','2011-06-15 16:43:18','Ed','Buxton',NULL,'n',NULL,10,10,NULL,'2011-07-16 16:43:18',NULL,'y',NULL)
INSERT INTO [firecms_users] ( [id], [username], [password], [email], [is_active], [is_admin], [updated_on], [created_on], [first_name], [last_name], [user_information], [is_verified], [fb_uid], [created_by], [edited_by], [parent_id], [expires_on], [subscr_id], [is_public], [role]) VALUES (12,'peter','peter1','peter','y','y','2011-06-15 16:45:38','2011-06-15 16:45:38','peter','peter',NULL,'n',NULL,10,10,NULL,'2011-07-16 16:45:38',NULL,'y',NULL)
INSERT INTO [firecms_users] ( [id], [username], [password], [email], [is_active], [is_admin], [updated_on], [created_on], [first_name], [last_name], [user_information], [is_verified], [fb_uid], [created_by], [edited_by], [parent_id], [expires_on], [subscr_id], [is_public], [role]) VALUES (13,'peter1','peter1','peter1','y','y','2011-06-15 16:46:02','2011-06-15 16:46:02','peter1','peter1',NULL,'n',NULL,10,10,NULL,'2011-07-16 16:46:02',NULL,'y',NULL)
INSERT INTO [firecms_users] ( [id], [username], [password], [email], [is_active], [is_admin], [updated_on], [created_on], [first_name], [last_name], [user_information], [is_verified], [fb_uid], [created_by], [edited_by], [parent_id], [expires_on], [subscr_id], [is_public], [role]) VALUES (14,'boris','123456','sokolov.boris@gmail.com','y','y','2012-03-27 14:56:28','2011-06-15 17:18:23','boris','sokolov',NULL,'n',NULL,14,14,NULL,'2011-07-16 17:18:23',NULL,'y','company')
INSERT INTO [firecms_users] ( [id], [username], [password], [email], [is_active], [is_admin], [updated_on], [created_on], [first_name], [last_name], [user_information], [is_verified], [fb_uid], [created_by], [edited_by], [parent_id], [expires_on], [subscr_id], [is_public], [role]) VALUES (15,'sdfdsfsdf','1','peter@ooyes.net','n','n','2012-02-27 17:08:27','2012-02-27 15:20:43','sadasd','',NULL,'n',NULL,15,15,NULL,'2012-03-29 15:20:43',NULL,'y',NULL)
CREATE TABLE [firecms_users_activities] ([id] BIGINT,[user_id] BIGINT,[message] TEXT,[updated_on] DATETIME,[created_on] DATETIME,[created_by] BIGINT,[edited_by] BIGINT
);
CREATE TABLE [firecms_users_log] ([id] BIGINT,[to_table_id] BIGINT,[user_id] BIGINT,[to_table] VARCHAR(250),[session_id] VARCHAR(250),[user_ip] VARCHAR(40),[created_on] DATETIME,[is_read] CHAR(1),[notifications_parsed] CHAR(1),[rel_table] VARCHAR(250),[rel_table_id] BIGINT,[log_parsed] CHAR(1),[created_by] BIGINT,[edited_by] BIGINT
);
INSERT INTO [firecms_users_log] ( [id], [to_table_id], [user_id], [to_table], [session_id], [user_ip], [created_on], [is_read], [notifications_parsed], [rel_table], [rel_table_id], [log_parsed], [created_by], [edited_by]) VALUES (1,1,14,'table_content','001ba57178fdb710883d4bc9524e93ba','fe80::a96b:e3bc:4abf:6b6e','2011-08-30 03:52:12','n','n','table_content',1,NULL,0,14)
INSERT INTO [firecms_users_log] ( [id], [to_table_id], [user_id], [to_table], [session_id], [user_ip], [created_on], [is_read], [notifications_parsed], [rel_table], [rel_table_id], [log_parsed], [created_by], [edited_by]) VALUES (10,3399,14,'table_content','001ba57178fdb710883d4bc9524e93ba','fe80::a96b:e3bc:4abf:6b6e','2011-08-30 04:12:54','n','n','table_content',3399,NULL,0,14)
INSERT INTO [firecms_users_log] ( [id], [to_table_id], [user_id], [to_table], [session_id], [user_ip], [created_on], [is_read], [notifications_parsed], [rel_table], [rel_table_id], [log_parsed], [created_by], [edited_by]) VALUES (11,3399,14,'table_content','001ba57178fdb710883d4bc9524e93ba','fe80::a96b:e3bc:4abf:6b6e','2011-08-30 04:14:34','n','n','table_content',3399,NULL,0,14)
INSERT INTO [firecms_users_log] ( [id], [to_table_id], [user_id], [to_table], [session_id], [user_ip], [created_on], [is_read], [notifications_parsed], [rel_table], [rel_table_id], [log_parsed], [created_by], [edited_by]) VALUES (12,3403,14,'table_content','3ffe967e52912a56f5a067de8bf8a230','fe80::a96b:e3bc:4abf:6b6e','2011-08-30 04:52:01','n','n','table_content',3403,NULL,0,14)
INSERT INTO [firecms_users_log] ( [id], [to_table_id], [user_id], [to_table], [session_id], [user_ip], [created_on], [is_read], [notifications_parsed], [rel_table], [rel_table_id], [log_parsed], [created_by], [edited_by]) VALUES (13,3403,14,'table_content','3ffe967e52912a56f5a067de8bf8a230','fe80::a96b:e3bc:4abf:6b6e','2011-08-30 04:52:01','n','n','table_content',3403,NULL,0,14)
INSERT INTO [firecms_users_log] ( [id], [to_table_id], [user_id], [to_table], [session_id], [user_ip], [created_on], [is_read], [notifications_parsed], [rel_table], [rel_table_id], [log_parsed], [created_by], [edited_by]) VALUES (14,3403,14,'table_content','3ffe967e52912a56f5a067de8bf8a230','fe80::a96b:e3bc:4abf:6b6e','2011-08-30 04:52:34','n','n','table_content',3403,NULL,0,14)
INSERT INTO [firecms_users_log] ( [id], [to_table_id], [user_id], [to_table], [session_id], [user_ip], [created_on], [is_read], [notifications_parsed], [rel_table], [rel_table_id], [log_parsed], [created_by], [edited_by]) VALUES (15,3403,14,'table_content','3ffe967e52912a56f5a067de8bf8a230','fe80::a96b:e3bc:4abf:6b6e','2011-08-30 04:52:34','n','n','table_content',3403,NULL,0,14)
INSERT INTO [firecms_users_log] ( [id], [to_table_id], [user_id], [to_table], [session_id], [user_ip], [created_on], [is_read], [notifications_parsed], [rel_table], [rel_table_id], [log_parsed], [created_by], [edited_by]) VALUES (16,3398,14,'table_content','3ffe967e52912a56f5a067de8bf8a230','fe80::a96b:e3bc:4abf:6b6e','2011-08-30 05:02:50','n','n','table_content',3398,NULL,0,14)
INSERT INTO [firecms_users_log] ( [id], [to_table_id], [user_id], [to_table], [session_id], [user_ip], [created_on], [is_read], [notifications_parsed], [rel_table], [rel_table_id], [log_parsed], [created_by], [edited_by]) VALUES (17,3398,14,'table_content','3ffe967e52912a56f5a067de8bf8a230','fe80::a96b:e3bc:4abf:6b6e','2011-08-30 05:02:50','n','n','table_content',3398,NULL,0,14)
INSERT INTO [firecms_users_log] ( [id], [to_table_id], [user_id], [to_table], [session_id], [user_ip], [created_on], [is_read], [notifications_parsed], [rel_table], [rel_table_id], [log_parsed], [created_by], [edited_by]) VALUES (18,3398,14,'table_content','3ffe967e52912a56f5a067de8bf8a230','fe80::a96b:e3bc:4abf:6b6e','2011-08-30 05:03:04','n','n','table_content',3398,NULL,0,14)
INSERT INTO [firecms_users_log] ( [id], [to_table_id], [user_id], [to_table], [session_id], [user_ip], [created_on], [is_read], [notifications_parsed], [rel_table], [rel_table_id], [log_parsed], [created_by], [edited_by]) VALUES (19,3398,14,'table_content','3ffe967e52912a56f5a067de8bf8a230','fe80::a96b:e3bc:4abf:6b6e','2011-08-30 05:03:04','n','n','table_content',3398,NULL,0,14)
INSERT INTO [firecms_users_log] ( [id], [to_table_id], [user_id], [to_table], [session_id], [user_ip], [created_on], [is_read], [notifications_parsed], [rel_table], [rel_table_id], [log_parsed], [created_by], [edited_by]) VALUES (20,3397,14,'table_content','c03cf84b4c74d55e2b429aaffae1411e','::1','2011-09-02 01:57:10','n','n','table_content',3397,NULL,0,14)
INSERT INTO [firecms_users_log] ( [id], [to_table_id], [user_id], [to_table], [session_id], [user_ip], [created_on], [is_read], [notifications_parsed], [rel_table], [rel_table_id], [log_parsed], [created_by], [edited_by]) VALUES (21,3397,14,'table_content','c03cf84b4c74d55e2b429aaffae1411e','::1','2011-09-02 01:57:10','n','n','table_content',3397,NULL,0,14)
INSERT INTO [firecms_users_log] ( [id], [to_table_id], [user_id], [to_table], [session_id], [user_ip], [created_on], [is_read], [notifications_parsed], [rel_table], [rel_table_id], [log_parsed], [created_by], [edited_by]) VALUES (22,3402,14,'table_content','c03cf84b4c74d55e2b429aaffae1411e','::1','2011-09-02 01:57:57','n','n','table_content',3402,NULL,0,14)
INSERT INTO [firecms_users_log] ( [id], [to_table_id], [user_id], [to_table], [session_id], [user_ip], [created_on], [is_read], [notifications_parsed], [rel_table], [rel_table_id], [log_parsed], [created_by], [edited_by]) VALUES (23,3402,14,'table_content','c03cf84b4c74d55e2b429aaffae1411e','::1','2011-09-02 01:57:57','n','n','table_content',3402,NULL,0,14)
INSERT INTO [firecms_users_log] ( [id], [to_table_id], [user_id], [to_table], [session_id], [user_ip], [created_on], [is_read], [notifications_parsed], [rel_table], [rel_table_id], [log_parsed], [created_by], [edited_by]) VALUES (24,3402,14,'table_content','c03cf84b4c74d55e2b429aaffae1411e','::1','2011-09-02 02:05:18','n','n','table_content',3402,NULL,0,14)
INSERT INTO [firecms_users_log] ( [id], [to_table_id], [user_id], [to_table], [session_id], [user_ip], [created_on], [is_read], [notifications_parsed], [rel_table], [rel_table_id], [log_parsed], [created_by], [edited_by]) VALUES (25,3402,14,'table_content','c03cf84b4c74d55e2b429aaffae1411e','::1','2011-09-02 02:05:18','n','n','table_content',3402,NULL,0,14)
INSERT INTO [firecms_users_log] ( [id], [to_table_id], [user_id], [to_table], [session_id], [user_ip], [created_on], [is_read], [notifications_parsed], [rel_table], [rel_table_id], [log_parsed], [created_by], [edited_by]) VALUES (26,3402,14,'table_content','c03cf84b4c74d55e2b429aaffae1411e','::1','2011-09-02 02:06:34','n','n','table_content',3402,NULL,0,14)
INSERT INTO [firecms_users_log] ( [id], [to_table_id], [user_id], [to_table], [session_id], [user_ip], [created_on], [is_read], [notifications_parsed], [rel_table], [rel_table_id], [log_parsed], [created_by], [edited_by]) VALUES (27,3402,14,'table_content','c03cf84b4c74d55e2b429aaffae1411e','::1','2011-09-02 02:09:30','n','n','table_content',3402,NULL,0,14)
INSERT INTO [firecms_users_log] ( [id], [to_table_id], [user_id], [to_table], [session_id], [user_ip], [created_on], [is_read], [notifications_parsed], [rel_table], [rel_table_id], [log_parsed], [created_by], [edited_by]) VALUES (31,3406,14,'table_content','c03cf84b4c74d55e2b429aaffae1411e','::1','2011-09-02 02:16:23','n','n','table_content',3406,NULL,0,14)
INSERT INTO [firecms_users_log] ( [id], [to_table_id], [user_id], [to_table], [session_id], [user_ip], [created_on], [is_read], [notifications_parsed], [rel_table], [rel_table_id], [log_parsed], [created_by], [edited_by]) VALUES (36,3410,14,'table_content','c03cf84b4c74d55e2b429aaffae1411e','::1','2011-09-02 02:18:43','n','n','table_content',3410,NULL,0,14)
INSERT INTO [firecms_users_log] ( [id], [to_table_id], [user_id], [to_table], [session_id], [user_ip], [created_on], [is_read], [notifications_parsed], [rel_table], [rel_table_id], [log_parsed], [created_by], [edited_by]) VALUES (37,3410,14,'table_content','c03cf84b4c74d55e2b429aaffae1411e','::1','2011-09-02 02:18:43','n','n','table_content',3410,NULL,0,14)
INSERT INTO [firecms_users_log] ( [id], [to_table_id], [user_id], [to_table], [session_id], [user_ip], [created_on], [is_read], [notifications_parsed], [rel_table], [rel_table_id], [log_parsed], [created_by], [edited_by]) VALUES (38,3410,14,'table_content','c03cf84b4c74d55e2b429aaffae1411e','::1','2011-09-02 02:19:26','n','n','table_content',3410,NULL,0,14)
INSERT INTO [firecms_users_log] ( [id], [to_table_id], [user_id], [to_table], [session_id], [user_ip], [created_on], [is_read], [notifications_parsed], [rel_table], [rel_table_id], [log_parsed], [created_by], [edited_by]) VALUES (40,3411,14,'table_content','c03cf84b4c74d55e2b429aaffae1411e','::1','2011-09-02 02:20:46','n','n','table_content',3411,NULL,0,14)
INSERT INTO [firecms_users_log] ( [id], [to_table_id], [user_id], [to_table], [session_id], [user_ip], [created_on], [is_read], [notifications_parsed], [rel_table], [rel_table_id], [log_parsed], [created_by], [edited_by]) VALUES (42,3399,14,'table_content','d11338c5ec57e3ff1dda63242a2acbf9','::1','2011-09-02 04:31:08','n','n','table_content',3399,NULL,0,14)
INSERT INTO [firecms_users_log] ( [id], [to_table_id], [user_id], [to_table], [session_id], [user_ip], [created_on], [is_read], [notifications_parsed], [rel_table], [rel_table_id], [log_parsed], [created_by], [edited_by]) VALUES (43,3399,14,'table_content','d11338c5ec57e3ff1dda63242a2acbf9','::1','2011-09-02 04:33:12','n','n','table_content',3399,NULL,0,14)
INSERT INTO [firecms_users_log] ( [id], [to_table_id], [user_id], [to_table], [session_id], [user_ip], [created_on], [is_read], [notifications_parsed], [rel_table], [rel_table_id], [log_parsed], [created_by], [edited_by]) VALUES (44,3411,14,'table_content','d11338c5ec57e3ff1dda63242a2acbf9','::1','2011-09-02 04:35:36','n','n','table_content',3411,NULL,0,14)
INSERT INTO [firecms_users_log] ( [id], [to_table_id], [user_id], [to_table], [session_id], [user_ip], [created_on], [is_read], [notifications_parsed], [rel_table], [rel_table_id], [log_parsed], [created_by], [edited_by]) VALUES (45,3411,14,'table_content','d11338c5ec57e3ff1dda63242a2acbf9','::1','2011-09-02 04:36:39','n','n','table_content',3411,NULL,0,14)
INSERT INTO [firecms_users_log] ( [id], [to_table_id], [user_id], [to_table], [session_id], [user_ip], [created_on], [is_read], [notifications_parsed], [rel_table], [rel_table_id], [log_parsed], [created_by], [edited_by]) VALUES (46,3411,14,'table_content','d11338c5ec57e3ff1dda63242a2acbf9','::1','2011-09-02 04:37:06','n','n','table_content',3411,NULL,0,14)
INSERT INTO [firecms_users_log] ( [id], [to_table_id], [user_id], [to_table], [session_id], [user_ip], [created_on], [is_read], [notifications_parsed], [rel_table], [rel_table_id], [log_parsed], [created_by], [edited_by]) VALUES (47,3411,14,'table_content','d11338c5ec57e3ff1dda63242a2acbf9','::1','2011-09-02 04:39:03','n','n','table_content',3411,NULL,0,14)
INSERT INTO [firecms_users_log] ( [id], [to_table_id], [user_id], [to_table], [session_id], [user_ip], [created_on], [is_read], [notifications_parsed], [rel_table], [rel_table_id], [log_parsed], [created_by], [edited_by]) VALUES (48,3411,14,'table_content','d11338c5ec57e3ff1dda63242a2acbf9','::1','2011-09-02 04:47:51','n','n','table_content',3411,NULL,0,14)
INSERT INTO [firecms_users_log] ( [id], [to_table_id], [user_id], [to_table], [session_id], [user_ip], [created_on], [is_read], [notifications_parsed], [rel_table], [rel_table_id], [log_parsed], [created_by], [edited_by]) VALUES (49,3397,14,'table_content','80528eecdeece0b000de48aeb9a80882','::1','2011-09-02 05:59:20','n','n','table_content',3397,NULL,0,14)
INSERT INTO [firecms_users_log] ( [id], [to_table_id], [user_id], [to_table], [session_id], [user_ip], [created_on], [is_read], [notifications_parsed], [rel_table], [rel_table_id], [log_parsed], [created_by], [edited_by]) VALUES (50,3397,14,'table_content','c7737ed2b053e20a922b25731fe97b6a','::1','2011-09-02 06:34:44','n','n','table_content',3397,NULL,0,14)
INSERT INTO [firecms_users_log] ( [id], [to_table_id], [user_id], [to_table], [session_id], [user_ip], [created_on], [is_read], [notifications_parsed], [rel_table], [rel_table_id], [log_parsed], [created_by], [edited_by]) VALUES (59,3417,14,'table_content','c7737ed2b053e20a922b25731fe97b6a','::1','2011-09-02 06:37:38','n','n','table_content',3417,NULL,0,14)
INSERT INTO [firecms_users_log] ( [id], [to_table_id], [user_id], [to_table], [session_id], [user_ip], [created_on], [is_read], [notifications_parsed], [rel_table], [rel_table_id], [log_parsed], [created_by], [edited_by]) VALUES (60,3416,14,'table_content','c7737ed2b053e20a922b25731fe97b6a','::1','2011-09-02 06:37:43','n','n','table_content',3416,NULL,0,14)
INSERT INTO [firecms_users_log] ( [id], [to_table_id], [user_id], [to_table], [session_id], [user_ip], [created_on], [is_read], [notifications_parsed], [rel_table], [rel_table_id], [log_parsed], [created_by], [edited_by]) VALUES (61,3415,14,'table_content','c7737ed2b053e20a922b25731fe97b6a','::1','2011-09-02 06:37:47','n','n','table_content',3415,NULL,0,14)
INSERT INTO [firecms_users_log] ( [id], [to_table_id], [user_id], [to_table], [session_id], [user_ip], [created_on], [is_read], [notifications_parsed], [rel_table], [rel_table_id], [log_parsed], [created_by], [edited_by]) VALUES (62,3414,14,'table_content','c7737ed2b053e20a922b25731fe97b6a','::1','2011-09-02 06:37:52','n','n','table_content',3414,NULL,0,14)
INSERT INTO [firecms_users_log] ( [id], [to_table_id], [user_id], [to_table], [session_id], [user_ip], [created_on], [is_read], [notifications_parsed], [rel_table], [rel_table_id], [log_parsed], [created_by], [edited_by]) VALUES (63,3418,14,'table_content','c7737ed2b053e20a922b25731fe97b6a','::1','2011-09-02 06:37:58','n','n','table_content',3418,NULL,0,14)
INSERT INTO [firecms_users_log] ( [id], [to_table_id], [user_id], [to_table], [session_id], [user_ip], [created_on], [is_read], [notifications_parsed], [rel_table], [rel_table_id], [log_parsed], [created_by], [edited_by]) VALUES (64,3419,14,'table_content','c7737ed2b053e20a922b25731fe97b6a','::1','2011-09-02 06:38:03','n','n','table_content',3419,NULL,0,14)
INSERT INTO [firecms_users_log] ( [id], [to_table_id], [user_id], [to_table], [session_id], [user_ip], [created_on], [is_read], [notifications_parsed], [rel_table], [rel_table_id], [log_parsed], [created_by], [edited_by]) VALUES (65,3402,14,'table_content','c7737ed2b053e20a922b25731fe97b6a','::1','2011-09-02 06:43:23','n','n','table_content',3402,NULL,0,14)
INSERT INTO [firecms_users_log] ( [id], [to_table_id], [user_id], [to_table], [session_id], [user_ip], [created_on], [is_read], [notifications_parsed], [rel_table], [rel_table_id], [log_parsed], [created_by], [edited_by]) VALUES (66,3402,14,'table_content','c7737ed2b053e20a922b25731fe97b6a','::1','2011-09-02 06:45:41','n','n','table_content',3402,NULL,0,14)
INSERT INTO [firecms_users_log] ( [id], [to_table_id], [user_id], [to_table], [session_id], [user_ip], [created_on], [is_read], [notifications_parsed], [rel_table], [rel_table_id], [log_parsed], [created_by], [edited_by]) VALUES (67,3398,14,'table_content','c7737ed2b053e20a922b25731fe97b6a','::1','2011-09-02 06:50:57','n','n','table_content',3398,NULL,0,14)
INSERT INTO [firecms_users_log] ( [id], [to_table_id], [user_id], [to_table], [session_id], [user_ip], [created_on], [is_read], [notifications_parsed], [rel_table], [rel_table_id], [log_parsed], [created_by], [edited_by]) VALUES (68,3399,14,'table_content','c7737ed2b053e20a922b25731fe97b6a','::1','2011-09-02 06:55:27','n','n','table_content',3399,NULL,0,14)
INSERT INTO [firecms_users_log] ( [id], [to_table_id], [user_id], [to_table], [session_id], [user_ip], [created_on], [is_read], [notifications_parsed], [rel_table], [rel_table_id], [log_parsed], [created_by], [edited_by]) VALUES (69,1,14,'table_content','c7737ed2b053e20a922b25731fe97b6a','::1','2011-09-02 07:02:21','n','n','table_content',1,NULL,0,14)
INSERT INTO [firecms_users_log] ( [id], [to_table_id], [user_id], [to_table], [session_id], [user_ip], [created_on], [is_read], [notifications_parsed], [rel_table], [rel_table_id], [log_parsed], [created_by], [edited_by]) VALUES (70,3402,14,'table_content','c7737ed2b053e20a922b25731fe97b6a','::1','2011-09-02 07:22:56','n','n','table_content',3402,NULL,0,14)
INSERT INTO [firecms_users_log] ( [id], [to_table_id], [user_id], [to_table], [session_id], [user_ip], [created_on], [is_read], [notifications_parsed], [rel_table], [rel_table_id], [log_parsed], [created_by], [edited_by]) VALUES (71,1,12,'table_content','68c4b350320742de29d303de98e5f52b','fe80::a96b:e3bc:4abf:6b6e','2011-10-18 12:15:12','n','n','table_content',1,NULL,0,12)
INSERT INTO [firecms_users_log] ( [id], [to_table_id], [user_id], [to_table], [session_id], [user_ip], [created_on], [is_read], [notifications_parsed], [rel_table], [rel_table_id], [log_parsed], [created_by], [edited_by]) VALUES (72,3397,12,'table_content','68c4b350320742de29d303de98e5f52b','fe80::a96b:e3bc:4abf:6b6e','2011-10-18 12:20:46','n','n','table_content',3397,NULL,0,12)
INSERT INTO [firecms_users_log] ( [id], [to_table_id], [user_id], [to_table], [session_id], [user_ip], [created_on], [is_read], [notifications_parsed], [rel_table], [rel_table_id], [log_parsed], [created_by], [edited_by]) VALUES (73,3421,12,'table_content','68c4b350320742de29d303de98e5f52b','fe80::a96b:e3bc:4abf:6b6e','2011-10-18 12:21:39','n','n','table_content',3421,NULL,12,12)
INSERT INTO [firecms_users_log] ( [id], [to_table_id], [user_id], [to_table], [session_id], [user_ip], [created_on], [is_read], [notifications_parsed], [rel_table], [rel_table_id], [log_parsed], [created_by], [edited_by]) VALUES (74,3421,12,'table_content','68c4b350320742de29d303de98e5f52b','fe80::a96b:e3bc:4abf:6b6e','2011-10-18 12:21:53','n','n','table_content',3421,NULL,0,12)
INSERT INTO [firecms_users_log] ( [id], [to_table_id], [user_id], [to_table], [session_id], [user_ip], [created_on], [is_read], [notifications_parsed], [rel_table], [rel_table_id], [log_parsed], [created_by], [edited_by]) VALUES (75,1,12,'table_content','62f0fb9060da3eaf5dac3dba6b10d8b1','fe80::a96b:e3bc:4abf:6b6e','2011-10-18 01:05:30','n','n','table_content',1,NULL,0,12)
CREATE TABLE [firecms_users_notifications] ([id] BIGINT,[from_user] BIGINT,[log_id] BIGINT,[to_user] BIGINT,[type] VARCHAR(50),[subtype] VARCHAR(50),[subtype_value] VARCHAR(50),[message] TEXT,[to_table_id] BIGINT,[is_read] CHAR(1),[to_table] VARCHAR(20),[updated_on] DATETIME,[created_on] DATETIME,[created_by] BIGINT,[edited_by] BIGINT
);
CREATE TABLE [firecms_users_statuses] ([id] BIGINT,[user_id] BIGINT,[status] TEXT,[updated_on] DATETIME,[created_on] DATETIME,[created_by] BIGINT,[edited_by] BIGINT
);
CREATE TABLE [firecms_votes] ([id] BIGINT,[to_table] VARCHAR(1500),[to_table_id] BIGINT,[created_on] DATETIME,[user_ip] VARCHAR(1500),[session_id] VARCHAR(1500),[created_by] BIGINT
);
INSERT INTO [firecms_votes] ( [id], [to_table], [to_table_id], [created_on], [user_ip], [session_id], [created_by]) VALUES (126,'table_content',258,'2010-12-17 12:28:29','','10295c341c7915b5eecd0cbaa970b834',1761)
INSERT INTO [firecms_votes] ( [id], [to_table], [to_table_id], [created_on], [user_ip], [session_id], [created_by]) VALUES (128,'table_votes',126,'2010-12-17 07:48:59','','67d72a2002e3e5424e62090a619d9cc2',1726)
INSERT INTO [firecms_votes] ( [id], [to_table], [to_table_id], [created_on], [user_ip], [session_id], [created_by]) VALUES (129,'table_users',1738,'2010-12-18 09:25:27','','e35349466a3a00af47de2b1b8330bd92',1761)
INSERT INTO [firecms_votes] ( [id], [to_table], [to_table_id], [created_on], [user_ip], [session_id], [created_by]) VALUES (191,'table_content',547,'2010-12-23 03:00:42','','50fc2cc16742c379e3bfe491d702b300',1761)
INSERT INTO [firecms_votes] ( [id], [to_table], [to_table_id], [created_on], [user_ip], [session_id], [created_by]) VALUES (131,'table_comments',190,'2010-12-18 10:24:53','','1f0b3ff4b2eedc18483d9712a15ceb7c',1731)
INSERT INTO [firecms_votes] ( [id], [to_table], [to_table_id], [created_on], [user_ip], [session_id], [created_by]) VALUES (190,'table_content',546,'2010-12-23 02:54:55','','50fc2cc16742c379e3bfe491d702b300',1761)
INSERT INTO [firecms_votes] ( [id], [to_table], [to_table_id], [created_on], [user_ip], [session_id], [created_by]) VALUES (245,'table_content',366,'2010-12-29 06:48:33','77.70.8.202','e3f299b91d1da6654d756081a4b3675b',0)
INSERT INTO [firecms_votes] ( [id], [to_table], [to_table_id], [created_on], [user_ip], [session_id], [created_by]) VALUES (135,'table_users_statuses',44,'2010-12-19 06:25:47','','adb1081263f8f91af6ed329d4a2b2e78',1757)
INSERT INTO [firecms_votes] ( [id], [to_table], [to_table_id], [created_on], [user_ip], [session_id], [created_by]) VALUES (138,'table_users_statuses',51,'2010-12-19 06:48:57','','adb1081263f8f91af6ed329d4a2b2e78',1757)
INSERT INTO [firecms_votes] ( [id], [to_table], [to_table_id], [created_on], [user_ip], [session_id], [created_by]) VALUES (140,'table_users_statuses',55,'2010-12-19 06:55:25','','adb1081263f8f91af6ed329d4a2b2e78',1757)
INSERT INTO [firecms_votes] ( [id], [to_table], [to_table_id], [created_on], [user_ip], [session_id], [created_by]) VALUES (141,'table_users_statuses',51,'2010-12-19 06:55:48','','6bbdba78a8f9e7993dd423997a6dfb08',1769)
INSERT INTO [firecms_votes] ( [id], [to_table], [to_table_id], [created_on], [user_ip], [session_id], [created_by]) VALUES (142,'table_users_statuses',53,'2010-12-19 07:13:26','','6bbdba78a8f9e7993dd423997a6dfb08',1769)
INSERT INTO [firecms_votes] ( [id], [to_table], [to_table_id], [created_on], [user_ip], [session_id], [created_by]) VALUES (143,'table_users_statuses',58,'2010-12-19 07:26:05','','4dbc0323449640a333a662fbc8881820',1774)
INSERT INTO [firecms_votes] ( [id], [to_table], [to_table_id], [created_on], [user_ip], [session_id], [created_by]) VALUES (234,'table_content',303,'2010-12-28 02:26:12','','e7184c6fa141ef45b49d42f9dfba364b',1757)
INSERT INTO [firecms_votes] ( [id], [to_table], [to_table_id], [created_on], [user_ip], [session_id], [created_by]) VALUES (146,'table_followers',19,'2010-12-20 05:34:10','','d229916517ebb3fc0627505c5f1e1b69',1774)
INSERT INTO [firecms_votes] ( [id], [to_table], [to_table_id], [created_on], [user_ip], [session_id], [created_by]) VALUES (147,'table_users',1769,'2010-12-20 05:34:17','','d229916517ebb3fc0627505c5f1e1b69',1774)
INSERT INTO [firecms_votes] ( [id], [to_table], [to_table_id], [created_on], [user_ip], [session_id], [created_by]) VALUES (189,'table_content',387,'2010-12-23 07:21:26','','e5755d54efe94142f8a0119e24b3d3af',1757)
INSERT INTO [firecms_votes] ( [id], [to_table], [to_table_id], [created_on], [user_ip], [session_id], [created_by]) VALUES (155,'table_content',307,'2010-12-20 10:55:03','','41403a4379d20b166278c595ddaf15c9',1757)
INSERT INTO [firecms_votes] ( [id], [to_table], [to_table_id], [created_on], [user_ip], [session_id], [created_by]) VALUES (162,'table_content',419,'2010-12-21 10:56:21','','bc00cec344d11450d1f44f4cc1ee66a8',1726)
INSERT INTO [firecms_votes] ( [id], [to_table], [to_table_id], [created_on], [user_ip], [session_id], [created_by]) VALUES (164,'table_content',231,'2010-12-21 11:00:11','','bc00cec344d11450d1f44f4cc1ee66a8',1726)
INSERT INTO [firecms_votes] ( [id], [to_table], [to_table_id], [created_on], [user_ip], [session_id], [created_by]) VALUES (165,'table_users_statuses',88,'2010-12-21 11:05:27','','bc00cec344d11450d1f44f4cc1ee66a8',1726)
INSERT INTO [firecms_votes] ( [id], [to_table], [to_table_id], [created_on], [user_ip], [session_id], [created_by]) VALUES (166,'table_users_statuses',89,'2010-12-21 11:05:31','','bc00cec344d11450d1f44f4cc1ee66a8',1726)
INSERT INTO [firecms_votes] ( [id], [to_table], [to_table_id], [created_on], [user_ip], [session_id], [created_by]) VALUES (172,'table_content',513,'2010-12-21 11:58:51','','965ed53f40d084d537c0a986288b3d35',1758)
INSERT INTO [firecms_votes] ( [id], [to_table], [to_table_id], [created_on], [user_ip], [session_id], [created_by]) VALUES (173,'table_content',372,'2010-12-21 12:01:14','77.70.8.202','aaa28b0162cd54e5bf07981153852da5',0)
INSERT INTO [firecms_votes] ( [id], [to_table], [to_table_id], [created_on], [user_ip], [session_id], [created_by]) VALUES (186,'table_comments',247,'2010-12-22 07:39:14','','ac87ce11f077b8cd8d3650029a52953b',1761)
INSERT INTO [firecms_votes] ( [id], [to_table], [to_table_id], [created_on], [user_ip], [session_id], [created_by]) VALUES (184,'table_users_statuses',102,'2010-12-22 08:40:01','','63a951687dbd51a1d3f5a21a4161a391',1766)
INSERT INTO [firecms_votes] ( [id], [to_table], [to_table_id], [created_on], [user_ip], [session_id], [created_by]) VALUES (185,'table_users_statuses',88,'2010-12-22 10:39:22','','76d696bf8dc592803408bb48a4246584',1769)
INSERT INTO [firecms_votes] ( [id], [to_table], [to_table_id], [created_on], [user_ip], [session_id], [created_by]) VALUES (194,'table_content',261,'2010-12-23 08:45:18','','25a725580cd36228e95a2c1d18ec4a48',1761)
INSERT INTO [firecms_votes] ( [id], [to_table], [to_table_id], [created_on], [user_ip], [session_id], [created_by]) VALUES (195,'table_content',231,'2010-12-23 08:45:44','','25a725580cd36228e95a2c1d18ec4a48',1761)
INSERT INTO [firecms_votes] ( [id], [to_table], [to_table_id], [created_on], [user_ip], [session_id], [created_by]) VALUES (196,'table_content',420,'2010-12-23 08:46:01','','25a725580cd36228e95a2c1d18ec4a48',1761)
INSERT INTO [firecms_votes] ( [id], [to_table], [to_table_id], [created_on], [user_ip], [session_id], [created_by]) VALUES (197,'table_content',271,'2010-12-23 08:46:31','','25a725580cd36228e95a2c1d18ec4a48',1761)
INSERT INTO [firecms_votes] ( [id], [to_table], [to_table_id], [created_on], [user_ip], [session_id], [created_by]) VALUES (198,'table_content',419,'2010-12-23 08:46:57','','25a725580cd36228e95a2c1d18ec4a48',1761)
INSERT INTO [firecms_votes] ( [id], [to_table], [to_table_id], [created_on], [user_ip], [session_id], [created_by]) VALUES (199,'table_content',275,'2010-12-23 08:47:12','','25a725580cd36228e95a2c1d18ec4a48',1761)
INSERT INTO [firecms_votes] ( [id], [to_table], [to_table_id], [created_on], [user_ip], [session_id], [created_by]) VALUES (200,'table_content',276,'2010-12-23 08:47:30','','25a725580cd36228e95a2c1d18ec4a48',1761)
INSERT INTO [firecms_votes] ( [id], [to_table], [to_table_id], [created_on], [user_ip], [session_id], [created_by]) VALUES (201,'table_content',426,'2010-12-23 08:48:00','','25a725580cd36228e95a2c1d18ec4a48',1761)
INSERT INTO [firecms_votes] ( [id], [to_table], [to_table_id], [created_on], [user_ip], [session_id], [created_by]) VALUES (202,'table_content',418,'2010-12-23 08:48:19','','25a725580cd36228e95a2c1d18ec4a48',1761)
INSERT INTO [firecms_votes] ( [id], [to_table], [to_table_id], [created_on], [user_ip], [session_id], [created_by]) VALUES (203,'table_content',383,'2010-12-24 02:54:12','','eaa63a2ac987548302b8c1ec9fccdeb8',1761)
INSERT INTO [firecms_votes] ( [id], [to_table], [to_table_id], [created_on], [user_ip], [session_id], [created_by]) VALUES (206,'table_content',397,'2010-12-27 07:30:43','','cd19fbfdcd7799c5338f9c7e9c242ae8',1761)
INSERT INTO [firecms_votes] ( [id], [to_table], [to_table_id], [created_on], [user_ip], [session_id], [created_by]) VALUES (214,'table_users_statuses',107,'2010-12-27 09:03:17','','82d12ad7d35952491443dea718b29cbd',1802)
INSERT INTO [firecms_votes] ( [id], [to_table], [to_table_id], [created_on], [user_ip], [session_id], [created_by]) VALUES (215,'table_content',560,'2010-12-27 10:22:55','','61ec5b52de5ca4c1351feaece5be55dd',1802)
INSERT INTO [firecms_votes] ( [id], [to_table], [to_table_id], [created_on], [user_ip], [session_id], [created_by]) VALUES (216,'table_content',560,'2010-12-27 10:48:20','','eb338344c3540d505f31fee4088111b7',1761)
INSERT INTO [firecms_votes] ( [id], [to_table], [to_table_id], [created_on], [user_ip], [session_id], [created_by]) VALUES (219,'table_content',366,'2010-12-27 02:08:49','77.70.8.202','56d8fed4d98ed122cb2287d5ccfe6bad',0)
INSERT INTO [firecms_votes] ( [id], [to_table], [to_table_id], [created_on], [user_ip], [session_id], [created_by]) VALUES (220,'table_comments',277,'2010-12-27 03:16:43','','4b95431310f5d2545076a4c2bdfdd277',1801)
INSERT INTO [firecms_votes] ( [id], [to_table], [to_table_id], [created_on], [user_ip], [session_id], [created_by]) VALUES (222,'table_comments',280,'2010-12-27 04:17:39','24.126.170.98','d655343cffdadefd44123a967984dabf',0)
INSERT INTO [firecms_votes] ( [id], [to_table], [to_table_id], [created_on], [user_ip], [session_id], [created_by]) VALUES (223,'table_comments',283,'2010-12-27 06:26:30','','99228dd1eddef2e23e871910a8a94674',1761)
INSERT INTO [firecms_votes] ( [id], [to_table], [to_table_id], [created_on], [user_ip], [session_id], [created_by]) VALUES (229,'table_content',418,'2010-12-28 11:09:27','','e27a4ad1c3a59bbc2b6fd854533dd798',1802)
INSERT INTO [firecms_votes] ( [id], [to_table], [to_table_id], [created_on], [user_ip], [session_id], [created_by]) VALUES (230,'table_comments',288,'2010-12-28 12:35:31','','96b4c4961c4806cac5eece9af4498b53',1801)
INSERT INTO [firecms_votes] ( [id], [to_table], [to_table_id], [created_on], [user_ip], [session_id], [created_by]) VALUES (231,'table_content',300,'2010-12-28 12:35:57','','96b4c4961c4806cac5eece9af4498b53',1801)
INSERT INTO [firecms_votes] ( [id], [to_table], [to_table_id], [created_on], [user_ip], [session_id], [created_by]) VALUES (232,'table_content',301,'2010-12-28 12:36:36','','96b4c4961c4806cac5eece9af4498b53',1801)
INSERT INTO [firecms_votes] ( [id], [to_table], [to_table_id], [created_on], [user_ip], [session_id], [created_by]) VALUES (233,'table_content',315,'2010-12-28 12:37:11','','96b4c4961c4806cac5eece9af4498b53',1801)
