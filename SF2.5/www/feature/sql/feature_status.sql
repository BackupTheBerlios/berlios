--
-- Selected TOC Entries:
--
\connect - postgres
--
-- TOC Entry ID 2 (OID 2851226)
--
-- Name: feature_status Type: TABLE Owner: postgres
--

CREATE SEQUENCE "feature_status_pk_seq" start 1 increment 1 maxvalue 2147483647 minvalue 1  cache 1 ;

CREATE TABLE "feature_status" (
	"feature_status_id" integer DEFAULT nextval('feature_status_pk_seq'::text) NOT NULL,
	"status_name" text,
	Constraint "feature_status_pkey" Primary Key ("feature_status_id")
);

--
-- TOC Entry ID 3 (OID 3127624)
--
-- Name: "RI_ConstraintTrigger_3127623" Type: TRIGGER Owner: postgres
--

CREATE CONSTRAINT TRIGGER "feature_status_id_fk" AFTER DELETE ON "feature_status"  FROM "feature" NOT DEFERRABLE INITIALLY IMMEDIATE FOR EACH ROW EXECUTE PROCEDURE "RI_FKey_noaction_del" ('feature_status_id_fk', 'feature', 'feature_status', 'FULL', 'feature_status_id', 'feature_status_id');

--
-- TOC Entry ID 4 (OID 3127626)
--
-- Name: "RI_ConstraintTrigger_3127625" Type: TRIGGER Owner: postgres
--

CREATE CONSTRAINT TRIGGER "feature_status_id_fk" AFTER UPDATE ON "feature_status"  FROM "feature" NOT DEFERRABLE INITIALLY IMMEDIATE FOR EACH ROW EXECUTE PROCEDURE "RI_FKey_noaction_upd" ('feature_status_id_fk', 'feature', 'feature_status', 'FULL', 'feature_status_id', 'feature_status_id');

