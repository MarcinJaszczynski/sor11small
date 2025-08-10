CREATE TABLE IF NOT EXISTS "event_template_program_point_child"(
  "id" integer primary key autoincrement not null,
  "event_template_id" integer not null,
  "program_point_id" integer not null,
  "include_in_program" tinyint(1) not null default '1',
  "include_in_calculation" tinyint(1) not null default '1',
  "active" tinyint(1) not null default '1',
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("event_template_id") references "event_templates"("id") on delete cascade,
  foreign key("program_point_id") references "event_template_program_points"("id") on delete cascade
);
CREATE UNIQUE INDEX "etppc_unique" on "event_template_program_point_child"(
  "event_template_id",
  "program_point_id"
);
CREATE TABLE IF NOT EXISTS "event_template_program_point_child_pivot"(
  "id" integer primary key autoincrement not null,
  "event_template_id" integer not null,
  "program_point_child_id" integer not null,
  "include_in_program" tinyint(1) not null default '1',
  "include_in_calculation" tinyint(1) not null default '1',
  "active" tinyint(1) not null default '1',
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("event_template_id") references "event_templates"("id") on delete cascade,
  foreign key("program_point_child_id") references "event_template_program_points"("id") on delete cascade
);
CREATE UNIQUE INDEX "et_child_unique" on "event_template_program_point_child_pivot"(
  "event_template_id",
  "program_point_child_id"
);
CREATE TABLE IF NOT EXISTS "places"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "description" text,
  "tags" text,
  "starting_place" tinyint(1) not null default '1',
  "created_at" datetime,
  "updated_at" datetime,
  "latitude" numeric,
  "longitude" numeric
);
CREATE TABLE IF NOT EXISTS "place_distances"(
  "id" integer primary key autoincrement not null,
  "from_place_id" integer not null,
  "to_place_id" integer not null,
  "distance_km" float,
  "api_source" varchar,
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("from_place_id") references "places"("id") on delete cascade,
  foreign key("to_place_id") references "places"("id") on delete cascade
);
CREATE UNIQUE INDEX "place_distance_unique" on "place_distances"(
  "from_place_id",
  "to_place_id"
);
CREATE TABLE IF NOT EXISTS "sessions"(
  "id" TEXT,
  "user_id" REAL,
  "ip_address" TEXT,
  "user_agent" TEXT,
  "payload" TEXT,
  "last_activity" INTEGER
);
CREATE TABLE IF NOT EXISTS "model_has_permissions"(
  "permission_id" TEXT,
  "model_type" TEXT,
  "model_id" TEXT
);
CREATE TABLE IF NOT EXISTS "transport_types"(
  "id" TEXT,
  "name" TEXT,
  "description" TEXT,
  "created_at" TEXT,
  "updated_at" TEXT
);
CREATE TABLE IF NOT EXISTS "role_has_permissions"(
  "permission_id" INTEGER,
  "role_id" INTEGER
);
CREATE TABLE IF NOT EXISTS "cache"("key" TEXT,
"value" TEXT,
"expiration" INTEGER);
CREATE TABLE IF NOT EXISTS "failed_jobs"(
  "id" TEXT,
  "uuid" TEXT,
  "connection" TEXT,
  "queue" TEXT,
  "payload" TEXT,
  "exception" TEXT,
  "failed_at" TEXT
);
CREATE TABLE IF NOT EXISTS "jobs"(
  "id" TEXT,
  "queue" TEXT,
  "payload" TEXT,
  "attempts" TEXT,
  "reserved_at" TEXT,
  "available_at" TEXT,
  "created_at" TEXT
);
CREATE TABLE IF NOT EXISTS "job_batches"(
  "id" TEXT,
  "name" TEXT,
  "total_jobs" TEXT,
  "pending_jobs" TEXT,
  "failed_jobs" TEXT,
  "failed_job_ids" TEXT,
  "options" TEXT,
  "cancelled_at" TEXT,
  "created_at" TEXT,
  "finished_at" TEXT
);
CREATE TABLE IF NOT EXISTS "contract_templates"(
  "id" TEXT,
  "name" TEXT,
  "content" TEXT,
  "created_at" TEXT,
  "updated_at" TEXT
);
CREATE TABLE IF NOT EXISTS "model_has_roles"(
  "role_id" INTEGER,
  "model_type" TEXT,
  "model_id" INTEGER
);
CREATE TABLE IF NOT EXISTS "password_reset_tokens"(
  "email" TEXT,
  "token" TEXT,
  "created_at" TEXT
);
CREATE TABLE IF NOT EXISTS "migrations"("id" INTEGER,
"migration" TEXT,
"batch" INTEGER);
CREATE TABLE IF NOT EXISTS "cache_locks"("key" TEXT,
"owner" TEXT,
"expiration" TEXT);
CREATE UNIQUE INDEX cache_key_unique ON cache(key);
CREATE TABLE IF NOT EXISTS "event_template_starting_place_availability"(
  "id" integer primary key autoincrement not null,
  "event_template_id" integer not null,
  "start_place_id" integer not null,
  "end_place_id" integer not null,
  "available" tinyint(1) not null default '0',
  "note" text,
  "created_at" datetime,
  "updated_at" datetime
);
CREATE TABLE IF NOT EXISTS "taxes"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "percentage" numeric not null,
  "apply_to_base" tinyint(1) not null default '0',
  "apply_to_markup" tinyint(1) not null default '0',
  "is_active" tinyint(1) not null default '1',
  "description" text,
  "created_at" datetime,
  "updated_at" datetime
);
CREATE TABLE IF NOT EXISTS "event_template_tax"(
  "id" integer primary key autoincrement not null,
  "event_template_id" integer not null,
  "tax_id" integer not null,
  "created_at" datetime,
  "updated_at" datetime
);
CREATE UNIQUE INDEX "event_template_tax_event_template_id_tax_id_unique" on "event_template_tax"(
  "event_template_id",
  "tax_id"
);
CREATE INDEX "event_template_tax_event_template_id_index" on "event_template_tax"(
  "event_template_id"
);
CREATE INDEX "event_template_tax_tax_id_index" on "event_template_tax"(
  "tax_id"
);
CREATE TABLE IF NOT EXISTS "event_templates"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "slug" varchar not null,
  "duration_days" integer,
  "featured_image" text,
  "event_description" text,
  "gallery" text,
  "office_description" text,
  "notes" text,
  "created_at" datetime,
  "updated_at" datetime,
  "deleted_at" datetime,
  "transfer_km" numeric,
  "program_km" numeric,
  "transfer_km2" text,
  "program_km2" text,
  "bus_id" numeric,
  "markup_id" numeric,
  "is_active" tinyint(1) not null default '1',
  "seo_title" text,
  "seo_description" text,
  "seo_keywords" text,
  "seo_canonical" text,
  "seo_og_title" text,
  "seo_og_description" text,
  "seo_og_image" text,
  "seo_twitter_title" text,
  "seo_twitter_description" text,
  "seo_twitter_image" text,
  "seo_schema" text,
  "subtitle" varchar,
  "transport_notes" text,
  "start_place_id" integer,
  "end_place_id" integer
);
CREATE TABLE IF NOT EXISTS "event_template_event_template_program_point"(
  "id" integer primary key autoincrement not null,
  "event_template_id" integer not null,
  "event_template_program_point_id" integer not null,
  "day" integer not null,
  "order" integer not null,
  "notes" text,
  "include_in_program" tinyint(1) not null default '1',
  "include_in_calculation" tinyint(1) not null default '1',
  "active" tinyint(1) not null default '1',
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("event_template_id") references "event_templates"("id") on delete cascade,
  foreign key("event_template_program_point_id") references "event_template_program_points"("id") on delete cascade
);
CREATE INDEX "event_template_event_template_program_point_temp_event_template_id_day_order_index" on "event_template_event_template_program_point"(
  "event_template_id",
  "day",
  "order"
);
CREATE TABLE IF NOT EXISTS "users"(
  "id" INTEGER PRIMARY KEY AUTOINCREMENT,
  "name" TEXT,
  "email" TEXT,
  "email_verified_at" TEXT,
  "password" TEXT,
  "remember_token" TEXT,
  "created_at" TEXT,
  "updated_at" TEXT,
  "type" TEXT,
  "status" TEXT
);
CREATE TABLE IF NOT EXISTS "roles"(
  "id" INTEGER PRIMARY KEY AUTOINCREMENT,
  "name" TEXT,
  "guard_name" TEXT,
  "created_at" TEXT,
  "updated_at" TEXT
);
CREATE TABLE IF NOT EXISTS "permissions"(
  "id" INTEGER PRIMARY KEY AUTOINCREMENT,
  "name" TEXT,
  "guard_name" TEXT,
  "created_at" TEXT,
  "updated_at" TEXT
);
CREATE TABLE IF NOT EXISTS "tags"(
  "id" INTEGER PRIMARY KEY AUTOINCREMENT,
  "name" TEXT,
  "description" TEXT,
  "visibility" TEXT,
  "status" TEXT,
  "created_at" TEXT,
  "updated_at" TEXT,
  "deleted_at" TEXT
);
CREATE TABLE IF NOT EXISTS "currencies"(
  "id" INTEGER PRIMARY KEY AUTOINCREMENT,
  "name" TEXT,
  "symbol" TEXT,
  "exchange_rate" REAL,
  "last_updated_at" TEXT,
  "created_at" TEXT,
  "updated_at" TEXT
);
CREATE TABLE IF NOT EXISTS "buses"(
  "id" INTEGER PRIMARY KEY AUTOINCREMENT,
  "name" TEXT,
  "description" TEXT,
  "capacity" INTEGER,
  "package_price_per_day" INTEGER,
  "package_km_per_day" INTEGER,
  "extra_km_price" REAL,
  "created_at" TEXT,
  "updated_at" TEXT,
  "currency" TEXT,
  "convert_to_pln" INTEGER
);
CREATE TABLE IF NOT EXISTS "markups"(
  "id" INTEGER PRIMARY KEY AUTOINCREMENT,
  "name" TEXT,
  "description" TEXT,
  "percent" INTEGER,
  "discount_percent" INTEGER,
  "discount_start" TEXT,
  "discount_end" TEXT,
  "is_default" INTEGER,
  "min_daily_amount_pln" INTEGER,
  "created_at" TEXT,
  "updated_at" TEXT
);
CREATE TABLE IF NOT EXISTS "insurances"(
  "id" INTEGER PRIMARY KEY AUTOINCREMENT,
  "name" TEXT,
  "description" TEXT,
  "price_per_person" REAL,
  "active" INTEGER,
  "created_at" TEXT,
  "updated_at" TEXT,
  "deleted_at" TEXT,
  "insurance_per_day" REAL,
  "insurance_per_person" REAL,
  "insurance_enabled" REAL
);
CREATE TABLE IF NOT EXISTS "hotel_rooms"(
  "id" INTEGER PRIMARY KEY AUTOINCREMENT,
  "name" TEXT,
  "description" TEXT,
  "notes" TEXT,
  "people_count" INTEGER,
  "price" INTEGER,
  "currency" TEXT,
  "convert_to_pln" INTEGER,
  "created_at" TEXT,
  "updated_at" TEXT,
  "capacity" TEXT,
  "standard" TEXT
);
CREATE TABLE IF NOT EXISTS "contractors"(
  "id" INTEGER PRIMARY KEY AUTOINCREMENT,
  "name" TEXT,
  "street" TEXT,
  "house_number" TEXT,
  "city" TEXT,
  "postal_code" TEXT,
  "status" TEXT,
  "office_notes" TEXT,
  "created_at" TEXT,
  "updated_at" TEXT,
  "deleted_at" TEXT
);
CREATE TABLE IF NOT EXISTS "contacts"(
  "id" INTEGER PRIMARY KEY AUTOINCREMENT,
  "first_name" TEXT,
  "last_name" TEXT,
  "phone" TEXT,
  "email" TEXT,
  "notes" TEXT,
  "created_at" TEXT,
  "updated_at" TEXT,
  "deleted_at" TEXT
);
CREATE TABLE IF NOT EXISTS "payment_types"(
  "id" INTEGER PRIMARY KEY AUTOINCREMENT,
  "name" TEXT,
  "description" TEXT,
  "created_at" TEXT,
  "updated_at" TEXT
);
CREATE TABLE IF NOT EXISTS "payment_statuses"(
  "id" INTEGER PRIMARY KEY AUTOINCREMENT,
  "name" TEXT,
  "created_at" TEXT,
  "updated_at" TEXT
);
CREATE TABLE IF NOT EXISTS "payers"(
  "id" INTEGER PRIMARY KEY AUTOINCREMENT,
  "name" TEXT,
  "description" TEXT,
  "created_at" TEXT,
  "updated_at" TEXT
);
CREATE TABLE IF NOT EXISTS "task_statuses"(
  "id" INTEGER PRIMARY KEY AUTOINCREMENT,
  "name" TEXT,
  "color" TEXT,
  "icon" TEXT,
  "order" INTEGER,
  "is_default" INTEGER,
  "created_at" TEXT,
  "updated_at" TEXT
);
CREATE TABLE IF NOT EXISTS "todo_statuses"(
  "id" INTEGER PRIMARY KEY AUTOINCREMENT,
  "name" TEXT,
  "color" TEXT,
  "bgcolor" TEXT,
  "created_at" TEXT,
  "updated_at" TEXT,
  "deleted_at" TEXT
);
CREATE TABLE IF NOT EXISTS "events"(
  "id" INTEGER PRIMARY KEY AUTOINCREMENT,
  "event_template_id" INTEGER,
  "name" TEXT,
  "client_name" TEXT,
  "client_email" TEXT,
  "client_phone" TEXT,
  "start_date" TEXT,
  "end_date" TEXT,
  "participant_count" INTEGER,
  "total_cost" INTEGER,
  "status" TEXT,
  "notes" TEXT,
  "created_by" INTEGER,
  "assigned_to" REAL,
  "created_at" TEXT,
  "updated_at" TEXT,
  "duration_days" INTEGER,
  "transfer_km" INTEGER,
  "program_km" INTEGER,
  "bus_id" REAL,
  "markup_id" REAL
);
CREATE TABLE IF NOT EXISTS "event_template_qties"(
  "id" INTEGER PRIMARY KEY AUTOINCREMENT,
  "qty" INTEGER,
  "created_at" TEXT,
  "updated_at" TEXT,
  "gratis" INTEGER,
  "staff" INTEGER,
  "driver" INTEGER
);
CREATE TABLE IF NOT EXISTS "event_template_program_points"(
  "id" INTEGER PRIMARY KEY AUTOINCREMENT,
  "name" TEXT,
  "description" TEXT,
  "office_notes" TEXT,
  "pilot_notes" TEXT,
  "duration_hours" INTEGER,
  "duration_minutes" INTEGER,
  "featured_image" TEXT,
  "gallery_images" TEXT,
  "unit_price" REAL,
  "group_size" INTEGER,
  "currency_id" INTEGER,
  "parent_id" TEXT,
  "created_at" TEXT,
  "updated_at" TEXT,
  "convert_to_pln" INTEGER,
  "order" INTEGER,
  "featured_image_original_name" TEXT
);
CREATE TABLE IF NOT EXISTS "event_template_program_point_parent"(
  "id" INTEGER PRIMARY KEY AUTOINCREMENT,
  "parent_id" INTEGER,
  "child_id" INTEGER,
  "order" INTEGER,
  "created_at" TEXT,
  "updated_at" TEXT
);
CREATE TABLE IF NOT EXISTS "event_template_program_point_tag"(
  "id" INTEGER PRIMARY KEY AUTOINCREMENT,
  "event_template_program_point_id" INTEGER,
  "tag_id" INTEGER,
  "created_at" TEXT,
  "updated_at" TEXT
);
CREATE TABLE IF NOT EXISTS "event_template_tag"(
  "id" INTEGER PRIMARY KEY AUTOINCREMENT,
  "event_template_id" INTEGER,
  "tag_id" INTEGER,
  "created_at" TEXT,
  "updated_at" TEXT
);
CREATE TABLE IF NOT EXISTS "event_template_hotel_days"(
  "id" INTEGER PRIMARY KEY AUTOINCREMENT,
  "event_template_id" INTEGER,
  "day" INTEGER,
  "hotel_room_ids_qty" TEXT,
  "hotel_room_ids_gratis" TEXT,
  "hotel_room_ids_staff" TEXT,
  "hotel_room_ids_driver" TEXT,
  "notes" TEXT,
  "created_at" TEXT,
  "updated_at" TEXT
);
CREATE TABLE IF NOT EXISTS "event_template_day_insurance"(
  "id" INTEGER PRIMARY KEY AUTOINCREMENT,
  "event_template_id" INTEGER,
  "day" INTEGER,
  "insurance_id" INTEGER,
  "created_at" TEXT,
  "updated_at" TEXT
);
CREATE TABLE IF NOT EXISTS "event_template_price_per_person"(
  "id" INTEGER PRIMARY KEY AUTOINCREMENT,
  "event_template_id" INTEGER,
  "event_template_qty_id" INTEGER,
  "currency_id" INTEGER,
  "price_per_person" INTEGER,
  "created_at" TEXT,
  "updated_at" TEXT,
  "start_place_id" INTEGER,
  "transport_cost" numeric,
  "price_base" numeric,
  "markup_amount" numeric,
  "tax_amount" numeric,
  "price_with_tax" numeric,
  "tax_breakdown" TEXT
);
CREATE TABLE IF NOT EXISTS "event_program_points"(
  "id" INTEGER PRIMARY KEY AUTOINCREMENT,
  "event_id" INTEGER,
  "event_template_program_point_id" INTEGER,
  "day" INTEGER,
  "order" REAL,
  "unit_price" INTEGER,
  "quantity" INTEGER,
  "total_price" INTEGER,
  "notes" TEXT,
  "include_in_program" INTEGER,
  "include_in_calculation" INTEGER,
  "active" INTEGER,
  "created_at" TEXT,
  "updated_at" TEXT
);
CREATE TABLE IF NOT EXISTS "event_snapshots"(
  "id" INTEGER PRIMARY KEY AUTOINCREMENT,
  "event_id" INTEGER,
  "type" TEXT,
  "name" TEXT,
  "description" TEXT,
  "event_data" TEXT,
  "program_points" TEXT,
  "calculations" TEXT,
  "currency_rates" TEXT,
  "total_cost_snapshot" INTEGER,
  "created_by" INTEGER,
  "snapshot_date" TEXT,
  "created_at" TEXT,
  "updated_at" TEXT
);
CREATE TABLE IF NOT EXISTS "event_histories"(
  "id" INTEGER PRIMARY KEY AUTOINCREMENT,
  "event_id" INTEGER,
  "user_id" INTEGER,
  "action" TEXT,
  "field" TEXT,
  "old_value" TEXT,
  "new_value" TEXT,
  "description" TEXT,
  "ip_address" TEXT,
  "created_at" TEXT,
  "updated_at" TEXT
);
CREATE TABLE IF NOT EXISTS "tasks"(
  "id" INTEGER PRIMARY KEY AUTOINCREMENT,
  "title" TEXT,
  "description" TEXT,
  "due_date" TEXT,
  "status_id" INTEGER,
  "priority" TEXT,
  "author_id" INTEGER,
  "assignee_id" INTEGER,
  "parent_id" REAL,
  "order" INTEGER,
  "created_at" TEXT,
  "updated_at" TEXT,
  "deleted_at" TEXT
);
CREATE TABLE IF NOT EXISTS "task_comments"(
  "id" INTEGER PRIMARY KEY AUTOINCREMENT,
  "content" TEXT,
  "task_id" INTEGER,
  "user_id" INTEGER,
  "created_at" TEXT,
  "updated_at" TEXT
);
CREATE TABLE IF NOT EXISTS "task_attachments"(
  "id" INTEGER PRIMARY KEY AUTOINCREMENT,
  "name" TEXT,
  "file_path" TEXT,
  "mime_type" TEXT,
  "size" TEXT,
  "task_id" INTEGER,
  "user_id" INTEGER,
  "created_at" TEXT,
  "updated_at" TEXT
);
CREATE TABLE IF NOT EXISTS "conversations"(
  "id" INTEGER PRIMARY KEY AUTOINCREMENT,
  "title" TEXT,
  "type" TEXT,
  "created_by" INTEGER,
  "last_message_at" TEXT,
  "created_at" TEXT,
  "updated_at" TEXT
);
CREATE TABLE IF NOT EXISTS "conversation_participants"(
  "id" INTEGER PRIMARY KEY AUTOINCREMENT,
  "conversation_id" INTEGER,
  "user_id" INTEGER,
  "joined_at" TEXT,
  "last_read_at" TEXT,
  "created_at" TEXT,
  "updated_at" TEXT
);
CREATE TABLE IF NOT EXISTS "messages"(
  "id" INTEGER PRIMARY KEY AUTOINCREMENT,
  "conversation_id" INTEGER,
  "user_id" INTEGER,
  "content" TEXT,
  "type" TEXT,
  "attachment_path" TEXT,
  "attachment_name" TEXT,
  "is_edited" INTEGER,
  "edited_at" TEXT,
  "created_at" TEXT,
  "updated_at" TEXT
);
CREATE TABLE IF NOT EXISTS "kategoria_szablonus"(
  "id" INTEGER PRIMARY KEY AUTOINCREMENT,
  "nazwa" TEXT,
  "opis" TEXT,
  "uwagi" TEXT,
  "parent_id" TEXT,
  "created_at" TEXT,
  "updated_at" TEXT
);
CREATE TABLE IF NOT EXISTS "contact_contractor"(
  "id" INTEGER PRIMARY KEY AUTOINCREMENT,
  "contractor_id" INTEGER,
  "contact_id" INTEGER,
  "created_at" TEXT,
  "updated_at" TEXT
);
CREATE TABLE IF NOT EXISTS "hotel_room_tag"(
  "id" INTEGER PRIMARY KEY AUTOINCREMENT,
  "hotel_room_id" TEXT,
  "tag_id" TEXT,
  "created_at" TEXT,
  "updated_at" TEXT
);

INSERT INTO migrations VALUES(1,'0001_01_01_000000_create_users_table',1);
INSERT INTO migrations VALUES(2,'0001_01_01_000001_create_cache_table',1);
INSERT INTO migrations VALUES(3,'0001_01_01_000002_create_jobs_table',1);
INSERT INTO migrations VALUES(4,'2024_03_20_create_task_attachments_table',1);
INSERT INTO migrations VALUES(5,'2024_03_20_create_task_comments_table',1);
INSERT INTO migrations VALUES(6,'2024_03_20_create_task_statuses_table',1);
INSERT INTO migrations VALUES(7,'2024_03_20_create_tasks_table',1);
INSERT INTO migrations VALUES(8,'2024_05_20_000000_create_contract_templates_table',1);
INSERT INTO migrations VALUES(9,'2025_04_14_093247_add_type_and_status_to_users_table',1);
INSERT INTO migrations VALUES(10,'2025_04_22_074741_create_permission_tables',1);
INSERT INTO migrations VALUES(11,'2025_04_22_085332_create_event_templates_table',1);
INSERT INTO migrations VALUES(12,'2025_04_22_090643_add_deleted_at_to_event_templates_table',1);
INSERT INTO migrations VALUES(13,'2025_04_22_110915_create_tags_table',1);
INSERT INTO migrations VALUES(14,'2025_04_22_111706_add_deleted_at_to_tagstags_table',1);
INSERT INTO migrations VALUES(15,'2025_04_22_112357_add_deleted_at_to_tags_table',1);
INSERT INTO migrations VALUES(16,'2025_04_22_112919_create_event_template_tag_table',1);
INSERT INTO migrations VALUES(17,'2025_04_22_123717_create_todo_statuses_table',1);
INSERT INTO migrations VALUES(18,'2025_04_22_124217_add_columns_to_todo_statuses_table',1);
INSERT INTO migrations VALUES(19,'2025_04_24_072351_create_contractors_table',1);
INSERT INTO migrations VALUES(20,'2025_04_24_073909_create_contacts_table',1);
INSERT INTO migrations VALUES(21,'2025_04_24_073926_create_contact_contractor_table',1);
INSERT INTO migrations VALUES(22,'2025_05_06_083741_create_currencies_table',1);
INSERT INTO migrations VALUES(23,'2025_05_06_084923_create_event_template_program_points_table',1);
INSERT INTO migrations VALUES(24,'2025_05_06_093612_add_convert_to_pln_to_event_template_program_points_table',1);
INSERT INTO migrations VALUES(25,'2025_05_06_124739_create_event_template_program_point_tag_table',1);
INSERT INTO migrations VALUES(26,'2025_05_12_081106_create_event_template_event_template_program_point_table',1);
INSERT INTO migrations VALUES(27,'2025_05_23_095943_create_event_template_qties_table',1);
INSERT INTO migrations VALUES(28,'2025_05_28_063221_create_kategoria_szablonus_table',1);
INSERT INTO migrations VALUES(29,'2025_05_28_081921_drop_role_user_type_table',1);
INSERT INTO migrations VALUES(30,'2025_05_28_081921_drop_role_user_types_table',1);
INSERT INTO migrations VALUES(31,'2025_05_28_081921_drop_user_types_table',1);
INSERT INTO migrations VALUES(32,'2025_05_30_000001_add_order_to_event_template_program_points_table',1);
INSERT INTO migrations VALUES(33,'2025_05_30_100000_create_event_template_program_point_parent_table',1);
INSERT INTO migrations VALUES(34,'2025_06_02_000000_create_payment_types_table',1);
INSERT INTO migrations VALUES(35,'2025_06_02_000001_create_payers_table',1);
INSERT INTO migrations VALUES(36,'2025_06_02_000002_create_payment_statuses_table',1);
INSERT INTO migrations VALUES(37,'2025_06_02_100000_create_et_price_per_person_table',1);
INSERT INTO migrations VALUES(38,'2025_06_02_100000_create_event_template_price_per_person_table',1);
INSERT INTO migrations VALUES(39,'2025_06_09_065516_create_transport_costs_table',1);
INSERT INTO migrations VALUES(40,'2025_06_09_072831_create_insurances_table',1);
INSERT INTO migrations VALUES(41,'2025_06_09_080000_create_event_template_day_insurance_table',1);
INSERT INTO migrations VALUES(42,'2025_06_18_000000_create_transport_types_table',1);
INSERT INTO migrations VALUES(43,'2025_06_21_000001_add_gratis_and_staff_to_event_template_qties_table',1);
INSERT INTO migrations VALUES(44,'2025_06_21_000001_create_event_template_hotel_days_table',1);
INSERT INTO migrations VALUES(45,'2025_06_21_000002_add_driver_and_change_staff_default_in_event_template_qties_table',1);
INSERT INTO migrations VALUES(46,'2025_06_21_000003_create_buses_table',1);
INSERT INTO migrations VALUES(53,'2025_06_21_000004_add_currency_and_convert_to_pln_to_buses_table',2);
INSERT INTO migrations VALUES(54,'2025_06_21_000005_add_event_code_and_km_to_event_templates_table',2);
INSERT INTO migrations VALUES(55,'2025_06_21_000008_add_km_to_event_templates_table',3);
INSERT INTO migrations VALUES(56,'2025_06_21_000009_add_bus_id_to_event_templates_table',3);
INSERT INTO migrations VALUES(57,'2025_06_21_000010_create_hotel_rooms_table',3);
INSERT INTO migrations VALUES(58,'2025_06_21_000011_create_hotel_room_tag_table',3);
INSERT INTO migrations VALUES(59,'2025_06_21_000012_drop_transport_costs_table',3);
INSERT INTO migrations VALUES(60,'2025_06_22_000001_add_capacity_and_standard_to_hotel_rooms_table',3);
INSERT INTO migrations VALUES(61,'2025_06_25_000000_create_markups_table',3);
INSERT INTO migrations VALUES(62,'2025_06_25_100000_add_markup_id_to_event_templates_table',4);
INSERT INTO migrations VALUES(63,'2025_06_25_173130_add_featured_image_original_name_to_event_template_program_points_table',5);
INSERT INTO migrations VALUES(64,'2025_06_26_114609_add_status_to_event_templates_table',6);
INSERT INTO migrations VALUES(65,'2025_06_26_122905_create_conversations_table',7);
INSERT INTO migrations VALUES(66,'2025_06_26_122925_create_conversation_participants_table',7);
INSERT INTO migrations VALUES(67,'2025_06_26_122939_create_messages_table',7);
INSERT INTO migrations VALUES(68,'2025_06_26_125402_rename_name_to_title_in_conversations_table',8);
INSERT INTO migrations VALUES(69,'2025_01_26_140000_create_events_table',9);
INSERT INTO migrations VALUES(70,'2025_01_26_140001_create_event_program_points_table',9);
INSERT INTO migrations VALUES(71,'2025_01_26_140002_create_event_histories_table',9);
INSERT INTO migrations VALUES(72,'2025_01_26_150000_create_event_snapshots_table',10);
INSERT INTO migrations VALUES(73,'2025_06_26_175843_add_additional_fields_to_events_table',11);
INSERT INTO migrations VALUES(74,'2025_07_16_000000_add_seo_fields_to_event_templates_table',12);
INSERT INTO migrations VALUES(75,'2025_07_18_000001_create_event_template_program_point_child_pivot_table',13);
INSERT INTO migrations VALUES(77,'2025_07_18_000002_create_event_template_program_point_child_pivot_table',14);
INSERT INTO migrations VALUES(78,'2025_07_21_000001_add_insurance_fields_to_insurances_table',15);
INSERT INTO migrations VALUES(79,'2025_07_21_100000_add_unique_constraint_to_event_template_day_insurance_table',16);
INSERT INTO migrations VALUES(80,'2025_07_21_000001_create_places_table',17);
INSERT INTO migrations VALUES(81,'2025_07_21_000002_create_place_distances_table',18);
INSERT INTO migrations VALUES(82,'2025_07_21_000003_add_latitude_longitude_to_places_table',18);
INSERT INTO migrations VALUES(83,'2025_07_25_051731_add_subtitle_to_event_templates_table',19);
INSERT INTO migrations VALUES(84,'2025_07_25_052858_add_transport_notes_to_event_templates_table',20);
INSERT INTO migrations VALUES(85,'2025_07_25_000001_add_places_to_event_templates_table',21);
INSERT INTO migrations VALUES(86,'2025_07_25_000002_fix_places_in_event_templates_table',22);
INSERT INTO migrations VALUES(87,'2025_07_25_000001_create_event_template_starting_place_availability_table',23);
INSERT INTO migrations VALUES(88,'2025_07_25_000002_add_start_place_to_event_template_price_per_person',24);
INSERT INTO migrations VALUES(89,'2025_07_25_091916_create_taxes_table',25);
INSERT INTO migrations VALUES(90,'2025_07_25_092744_create_event_template_tax_table',26);
INSERT INTO migrations VALUES(91,'2025_07_25_093528_fix_event_templates_primary_key',26);
INSERT INTO migrations VALUES(92,'2025_07_25_094251_add_tax_fields_to_event_template_price_per_person',27);
INSERT INTO migrations VALUES(93,'2025_07_27_180538_fix_event_templates_primary_key_autoincrement',28);
INSERT INTO migrations VALUES(94,'2025_07_27_194825_fix_event_template_pivot_primary_key_v2',29);
