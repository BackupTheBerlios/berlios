--
-- Selected TOC Entries:
--
\connect - postgres
--
-- TOC Entry ID 2 (OID 2851063)
--
-- Name: feature_category Type: TABLE Owner: postgres
--

CREATE SEQUENCE "feature_category_pk_seq" start 10000 increment 1 maxvalue 2147483647 minvalue 1  cache 1 ;

CREATE TABLE "feature_category" (
	"feature_category_id" integer DEFAULT nextval('feature_category_pk_seq'::text) NOT NULL,
	"group_id" integer DEFAULT '0' NOT NULL,
	"category_name" text DEFAULT '' NOT NULL,
	Constraint "feature_category_pkey" Primary Key ("feature_category_id")
);

--
-- TOC Entry ID 3 (OID 2851063)
--
-- Name: "feature_group_group_id" Type: INDEX Owner: postgres
--

CREATE  INDEX "feature_group_group_id" on "feature_category" using btree ( "group_id" "int4_ops" );

--
-- TOC Entry ID 4 (OID 3127630)
--
-- Name: "RI_ConstraintTrigger_3127629" Type: TRIGGER Owner: postgres
--

CREATE CONSTRAINT TRIGGER "feature_category_id_fk" AFTER DELETE ON "feature_category"  FROM "feature" NOT DEFERRABLE INITIALLY IMMEDIATE FOR EACH ROW EXECUTE PROCEDURE "RI_FKey_noaction_del" ('feature_category_id_fk', 'feature', 'feature_category', 'FULL', 'feature_category_id', 'feature_category_id');

--
-- TOC Entry ID 5 (OID 3127632)
--
-- Name: "RI_ConstraintTrigger_3127631" Type: TRIGGER Owner: postgres
--

CREATE CONSTRAINT TRIGGER "feature_category_id_fk" AFTER UPDATE ON "feature_category"  FROM "feature" NOT DEFERRABLE INITIALLY IMMEDIATE FOR EACH ROW EXECUTE PROCEDURE "RI_FKey_noaction_upd" ('feature_category_id_fk', 'feature', 'feature_category', 'FULL', 'feature_category_id', 'feature_category_id');

