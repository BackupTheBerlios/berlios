--
-- Selected TOC Entries:
--
\connect - postgres
--
-- TOC Entry ID 2 (OID 2851115)
--
-- Name: feature_history Type: TABLE Owner: postgres
--

CREATE SEQUENCE "feature_history_pk_seq" start 1 increment 1 maxvalue 2147483647 minvalue 1  cache 1 ;

CREATE TABLE "feature_history" (
	"feature_history_id" integer DEFAULT nextval('feature_history_pk_seq'::text) NOT NULL,
	"feature_id" integer DEFAULT '0' NOT NULL,
	"field_name" text DEFAULT '' NOT NULL,
	"old_value" text DEFAULT '' NOT NULL,
	"mod_by" integer DEFAULT '0' NOT NULL,
	"date" integer,
	Constraint "feature_history_pkey" Primary Key ("feature_history_id")
);

--
-- TOC Entry ID 3 (OID 2851115)
--
-- Name: "feature_history_feature_id" Type: INDEX Owner: postgres
--

CREATE  INDEX "feature_history_feature_id" on "feature_history" using btree ( "feature_id" "int4_ops" );

