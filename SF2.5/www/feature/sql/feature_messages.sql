--
-- Selected TOC Entries:
--
\connect - postgres
--
-- TOC Entry ID 2 (OID 2851172)
--
-- Name: feature_messages Type: TABLE Owner: postgres
--

CREATE SEQUENCE "feature_messages_pk_seq" start 1 increment 1 maxvalue 2147483647 minvalue 1  cache 1 ;

CREATE TABLE "feature_messages" (
	"feature_message_id" integer DEFAULT nextval('feature_messages_pk_seq'::text) NOT NULL,
	"feature_id" integer DEFAULT '0' NOT NULL,
	"from_email" text,
	"date" integer DEFAULT '0' NOT NULL,
	"body" text,
	Constraint "feature_messages_pkey" Primary Key ("feature_message_id")
);

--
-- TOC Entry ID 3 (OID 2851172)
--
-- Name: "feature_messages_feature_id" Type: INDEX Owner: postgres
--

CREATE  INDEX "feature_messages_feature_id" on "feature_messages" using btree ( "feature_id" "int4_ops" );

