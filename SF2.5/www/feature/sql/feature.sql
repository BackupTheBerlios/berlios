--
-- Selected TOC Entries:
--
\connect - postgres
--
-- TOC Entry ID 2 (OID 2850946)
--
-- Name: feature Type: TABLE Owner: postgres
--
CREATE SEQUENCE "feature_pk_seq" start 1 increment 1 maxvalue 2147483647 minvalue 1  cache 1 ;

CREATE TABLE "feature" (
	"feature_id" integer DEFAULT nextval('feature_pk_seq'::text) NOT NULL,
	"group_id" integer DEFAULT '0' NOT NULL,
	"feature_status_id" integer DEFAULT '0' NOT NULL,
	"feature_category_id" integer DEFAULT '0' NOT NULL,
	"priority" integer DEFAULT '0' NOT NULL,
	"submitted_by" integer DEFAULT '0' NOT NULL,
	"assigned_to" integer DEFAULT '0' NOT NULL,
	"open_date" integer DEFAULT '0' NOT NULL,
	"summary" text,
	"close_date" integer DEFAULT '0' NOT NULL,
	Constraint "feature_pkey" Primary Key ("feature_id")
);

--
-- TOC Entry ID 3 (OID 2850946)
--
-- Name: "feature_group_id" Type: INDEX Owner: postgres
--

CREATE  INDEX "feature_group_id" on "feature" using btree ( "group_id" "int4_ops" );

--
-- TOC Entry ID 4 (OID 3127622)
--
-- Name: "RI_ConstraintTrigger_3127621" Type: TRIGGER Owner: postgres
--

CREATE CONSTRAINT TRIGGER "feature_status_id_fk" AFTER INSERT OR UPDATE ON "feature"  FROM "feature_status" NOT DEFERRABLE INITIALLY IMMEDIATE FOR EACH ROW EXECUTE PROCEDURE "RI_FKey_check_ins" ('feature_status_id_fk', 'feature', 'feature_status', 'FULL', 'feature_status_id', 'feature_status_id');

--
-- TOC Entry ID 5 (OID 3127628)
--
-- Name: "RI_ConstraintTrigger_3127627" Type: TRIGGER Owner: postgres
--

CREATE CONSTRAINT TRIGGER "feature_category_id_fk" AFTER INSERT OR UPDATE ON "feature"  FROM "feature_category" NOT DEFERRABLE INITIALLY IMMEDIATE FOR EACH ROW EXECUTE PROCEDURE "RI_FKey_check_ins" ('feature_category_id_fk', 'feature', 'feature_category', 'FULL', 'feature_category_id', 'feature_category_id');

--
-- TOC Entry ID 6 (OID 3127634)
--
-- Name: "RI_ConstraintTrigger_3127633" Type: TRIGGER Owner: postgres
--

CREATE CONSTRAINT TRIGGER "feature_submitted_by_fk" AFTER INSERT OR UPDATE ON "feature"  FROM "users" NOT DEFERRABLE INITIALLY IMMEDIATE FOR EACH ROW EXECUTE PROCEDURE "RI_FKey_check_ins" ('feature_submitted_by_fk', 'feature', 'users', 'FULL', 'submitted_by', 'user_id');

--
-- TOC Entry ID 7 (OID 3127640)
--
-- Name: "RI_ConstraintTrigger_3127639" Type: TRIGGER Owner: postgres
--

CREATE CONSTRAINT TRIGGER "feature_assigned_to_fk" AFTER INSERT OR UPDATE ON "feature"  FROM "users" NOT DEFERRABLE INITIALLY IMMEDIATE FOR EACH ROW EXECUTE PROCEDURE "RI_FKey_check_ins" ('feature_assigned_to_fk', 'feature', 'users', 'FULL', 'assigned_to', 'user_id');

--
-- TOC Entry ID 451 (OID 3127636)
--
-- Name: "RI_ConstraintTrigger_3127635" Type: TRIGGER Owner: postgres
--

CREATE CONSTRAINT TRIGGER "feature_submitted_by_fk" AFTER DELETE ON "users"  FROM "feature" NOT DEFERRABLE INITIALLY IMMEDIATE FOR EACH ROW EXECUTE PROCEDURE "RI_FKey_noaction_del" ('feature_submitted_by_fk', 'feature', 'users', 'FULL', 'submitted_by', 'user_id');

--
-- TOC Entry ID 452 (OID 3127638)
--
-- Name: "RI_ConstraintTrigger_3127637" Type: TRIGGER Owner: postgres
--

CREATE CONSTRAINT TRIGGER "feature_submitted_by_fk" AFTER UPDATE ON "users"  FROM "feature" NOT DEFERRABLE INITIALLY IMMEDIATE FOR EACH ROW EXECUTE PROCEDURE "RI_FKey_noaction_upd" ('feature_submitted_by_fk', 'feature', 'users', 'FULL', 'submitted_by', 'user_id');

--
-- TOC Entry ID 439 (OID 3127640)
--
-- Name: "RI_ConstraintTrigger_3127639" Type: TRIGGER Owner: postgres
--
--
-- TOC Entry ID 453 (OID 3127642)
--
-- Name: "RI_ConstraintTrigger_3127641" Type: TRIGGER Owner: postgres
--

CREATE CONSTRAINT TRIGGER "feature_assigned_to_fk" AFTER DELETE ON "users"  FROM "feature" NOT DEFERRABLE INITIALLY IMMEDIATE FOR EACH ROW EXECUTE PROCEDURE "RI_FKey_noaction_del" ('feature_assigned_to_fk', 'feature', 'users', 'FULL', 'assigned_to', 'user_id');

--
-- TOC Entry ID 460 (OID 3127644)
--
-- Name: "RI_ConstraintTrigger_3127643" Type: TRIGGER Owner: postgres
--

CREATE CONSTRAINT TRIGGER "feature_assigned_to_fk" AFTER UPDATE ON "users"  FROM "feature" NOT DEFERRABLE INITIALLY IMMEDIATE FOR EACH ROW EXECUTE PROCEDURE "RI_FKey_noaction_upd" ('feature_assigned_to_fk', 'feature', 'users', 'FULL', 'assigned_to', 'user_id');
