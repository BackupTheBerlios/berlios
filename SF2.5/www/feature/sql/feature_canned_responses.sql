--
-- Selected TOC Entries:
--
\connect - postgres
--
-- TOC Entry ID 2 (OID 2851011)
--
-- Name: feature_canned_responses Type: TABLE Owner: postgres
--

CREATE SEQUENCE "feature_canned_responses_pk_seq" start 1 increment 1 maxvalue 2147483647 minvalue 1  cache 1 ;

CREATE TABLE "feature_canned_responses" (
	"feature_canned_id" integer DEFAULT nextval('feature_canned_responses_pk_seq'::text) NOT NULL,
	"group_id" integer DEFAULT '0' NOT NULL,
	"title" text,
	"body" text,
	Constraint "feature_canned_responses_pkey" Primary Key ("feature_canned_id")
);

--
-- TOC Entry ID 3 (OID 2851011)
--
-- Name: "feature_canned_response_group_i" Type: INDEX Owner: postgres
--

CREATE  INDEX "feature_canned_response_group_i" on "feature_canned_responses" using btree ( "group_id" "int4_ops" );

