--
-- PostgreSQL database dump
--

-- Dumped from database version 10.20
-- Dumped by pg_dump version 10.20

-- Started on 2023-03-27 18:58:20

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- TOC entry 9 (class 2615 OID 16396)
-- Name: lego; Type: SCHEMA; Schema: -; Owner: jargoth
--

CREATE SCHEMA lego;


ALTER SCHEMA lego OWNER TO jargoth;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- TOC entry 211 (class 1259 OID 16610)
-- Name: bricklink; Type: TABLE; Schema: lego; Owner: jargoth
--

CREATE TABLE lego.bricklink (
    design integer NOT NULL,
    color integer NOT NULL,
    "user" integer NOT NULL,
    container character varying(20) NOT NULL,
    sell integer DEFAULT 0 NOT NULL,
    lotid integer,
    date_added timestamp with time zone DEFAULT now() NOT NULL
);


ALTER TABLE lego.bricklink OWNER TO jargoth;

--
-- TOC entry 207 (class 1259 OID 16479)
-- Name: bricklink_color; Type: TABLE; Schema: lego; Owner: jargoth
--

CREATE TABLE lego.bricklink_color (
    color integer NOT NULL,
    bricklink integer NOT NULL
);


ALTER TABLE lego.bricklink_color OWNER TO jargoth;

--
-- TOC entry 205 (class 1259 OID 16464)
-- Name: bricklink_design; Type: TABLE; Schema: lego; Owner: jargoth
--

CREATE TABLE lego.bricklink_design (
    design integer NOT NULL,
    bricklink character varying(16) NOT NULL,
    type character(1) DEFAULT 'P'::bpchar NOT NULL,
    obsolete boolean DEFAULT false,
    deletion boolean DEFAULT false
);


ALTER TABLE lego.bricklink_design OWNER TO jargoth;

--
-- TOC entry 212 (class 1259 OID 16645)
-- Name: bricklink_error; Type: TABLE; Schema: lego; Owner: jargoth
--

CREATE TABLE lego.bricklink_error (
    design integer NOT NULL,
    color integer NOT NULL,
    "time" timestamp with time zone
);


ALTER TABLE lego.bricklink_error OWNER TO jargoth;

--
-- TOC entry 215 (class 1259 OID 16697)
-- Name: bricklink_price; Type: TABLE; Schema: lego; Owner: jargoth
--

CREATE TABLE lego.bricklink_price (
    design character varying(20) NOT NULL,
    color integer NOT NULL,
    design_type character(1) NOT NULL,
    last6_used_usd character varying(20),
    last6_new_usd character varying(20),
    date timestamp with time zone
);


ALTER TABLE lego.bricklink_price OWNER TO jargoth;

--
-- TOC entry 221 (class 1259 OID 38024)
-- Name: bricklink_xml_seq; Type: SEQUENCE; Schema: lego; Owner: jargoth
--

CREATE SEQUENCE lego.bricklink_xml_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE lego.bricklink_xml_seq OWNER TO jargoth;

--
-- TOC entry 220 (class 1259 OID 38006)
-- Name: bricklink_xml; Type: TABLE; Schema: lego; Owner: jargoth
--

CREATE TABLE lego.bricklink_xml (
    data character varying(512) NOT NULL,
    "user" integer NOT NULL,
    read boolean DEFAULT false,
    id integer DEFAULT nextval('lego.bricklink_xml_seq'::regclass) NOT NULL
);


ALTER TABLE lego.bricklink_xml OWNER TO jargoth;

--
-- TOC entry 213 (class 1259 OID 16660)
-- Name: bricklink_xml_generate; Type: TABLE; Schema: lego; Owner: jargoth
--

CREATE TABLE lego.bricklink_xml_generate (
    "user" integer NOT NULL
);


ALTER TABLE lego.bricklink_xml_generate OWNER TO jargoth;

--
-- TOC entry 226 (class 1259 OID 192465)
-- Name: bricklink_xml_update_seq; Type: SEQUENCE; Schema: lego; Owner: jargoth
--

CREATE SEQUENCE lego.bricklink_xml_update_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE lego.bricklink_xml_update_seq OWNER TO jargoth;

--
-- TOC entry 227 (class 1259 OID 192467)
-- Name: bricklink_xml_update; Type: TABLE; Schema: lego; Owner: jargoth
--

CREATE TABLE lego.bricklink_xml_update (
    data character varying(512) NOT NULL,
    "user" integer NOT NULL,
    read boolean DEFAULT false,
    id integer DEFAULT nextval('lego.bricklink_xml_update_seq'::regclass) NOT NULL
);


ALTER TABLE lego.bricklink_xml_update OWNER TO jargoth;

--
-- TOC entry 214 (class 1259 OID 16670)
-- Name: bricklink_xml_update_generate; Type: TABLE; Schema: lego; Owner: jargoth
--

CREATE TABLE lego.bricklink_xml_update_generate (
    "user" integer NOT NULL
);


ALTER TABLE lego.bricklink_xml_update_generate OWNER TO jargoth;

--
-- TOC entry 201 (class 1259 OID 16415)
-- Name: color_id_seq; Type: SEQUENCE; Schema: lego; Owner: jargoth
--

CREATE SEQUENCE lego.color_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE lego.color_id_seq OWNER TO jargoth;

--
-- TOC entry 203 (class 1259 OID 16423)
-- Name: color; Type: TABLE; Schema: lego; Owner: jargoth
--

CREATE TABLE lego.color (
    id integer DEFAULT nextval('lego.color_id_seq'::regclass) NOT NULL,
    "Name" character varying NOT NULL
);


ALTER TABLE lego.color OWNER TO jargoth;

--
-- TOC entry 242 (class 1259 OID 231578)
-- Name: color_ldraw; Type: TABLE; Schema: lego; Owner: jargoth
--

CREATE TABLE lego.color_ldraw (
    color integer,
    ldraw character varying(16) NOT NULL
);


ALTER TABLE lego.color_ldraw OWNER TO jargoth;

--
-- TOC entry 209 (class 1259 OID 16504)
-- Name: container; Type: TABLE; Schema: lego; Owner: jargoth
--

CREATE TABLE lego.container (
    id character varying(8) NOT NULL,
    "user" integer NOT NULL,
    recalculate boolean DEFAULT true,
    condition character(1) DEFAULT 'u'::bpchar,
    mark_full boolean DEFAULT false,
    "full" real,
    weight real
);


ALTER TABLE lego.container OWNER TO jargoth;

--
-- TOC entry 200 (class 1259 OID 16413)
-- Name: design_id_seq; Type: SEQUENCE; Schema: lego; Owner: jargoth
--

CREATE SEQUENCE lego.design_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE lego.design_id_seq OWNER TO jargoth;

--
-- TOC entry 202 (class 1259 OID 16417)
-- Name: design; Type: TABLE; Schema: lego; Owner: jargoth
--

CREATE TABLE lego.design (
    id integer DEFAULT nextval('lego.design_id_seq'::regclass) NOT NULL,
    description character varying(256) NOT NULL,
    hide boolean DEFAULT false NOT NULL,
    part_number character varying(10),
    bricklink timestamp with time zone DEFAULT make_timestamptz(2000, 1, 1, 0, 0, (0)::double precision),
    weight character varying(6),
    primitive boolean DEFAULT false
);


ALTER TABLE lego.design OWNER TO jargoth;

--
-- TOC entry 208 (class 1259 OID 16486)
-- Name: design_color; Type: TABLE; Schema: lego; Owner: jargoth
--

CREATE TABLE lego.design_color (
    design integer NOT NULL,
    color integer NOT NULL,
    picture_data bytea,
    picture_size integer DEFAULT 0,
    picture_type character varying(20),
    picture_55_data bytea,
    picture_55_size integer DEFAULT 0,
    picture_55_type character varying(20),
    picture_130_data bytea,
    picture_130_size integer DEFAULT 0,
    picture_130_type character varying(20),
    picture_400_data bytea,
    picture_400_size integer DEFAULT 0,
    picture_400_type character varying(20),
    approved boolean DEFAULT true,
    picture_replace boolean DEFAULT false
);


ALTER TABLE lego.design_color OWNER TO jargoth;

--
-- TOC entry 204 (class 1259 OID 16429)
-- Name: design_color_user; Type: TABLE; Schema: lego; Owner: jargoth
--

CREATE TABLE lego.design_color_user (
    design integer NOT NULL,
    color integer NOT NULL,
    "user" integer NOT NULL,
    used integer DEFAULT 0 NOT NULL,
    free integer DEFAULT 0 NOT NULL
);


ALTER TABLE lego.design_color_user OWNER TO jargoth;

--
-- TOC entry 210 (class 1259 OID 16546)
-- Name: design_color_user_container; Type: TABLE; Schema: lego; Owner: jargoth
--

CREATE TABLE lego.design_color_user_container (
    design integer NOT NULL,
    color integer NOT NULL,
    "user" integer NOT NULL,
    container character varying(8) NOT NULL,
    bricks integer DEFAULT 0 NOT NULL,
    condition character(1) DEFAULT 'u'::bpchar
);


ALTER TABLE lego.design_color_user_container OWNER TO jargoth;

--
-- TOC entry 234 (class 1259 OID 231126)
-- Name: filename_design_color; Type: TABLE; Schema: lego; Owner: jargoth
--

CREATE TABLE lego.filename_design_color (
    filename character varying(128) NOT NULL,
    design integer NOT NULL,
    color integer NOT NULL
);


ALTER TABLE lego.filename_design_color OWNER TO jargoth;

--
-- TOC entry 216 (class 1259 OID 16720)
-- Name: inventory; Type: TABLE; Schema: lego; Owner: jargoth
--

CREATE TABLE lego.inventory (
    parent_design character varying(16) NOT NULL,
    parent_color integer NOT NULL,
    parent_type character(1) NOT NULL,
    child_design character varying(16) NOT NULL,
    child_color integer NOT NULL,
    child_type character(1) NOT NULL,
    count integer
);


ALTER TABLE lego.inventory OWNER TO jargoth;

--
-- TOC entry 244 (class 1259 OID 241190)
-- Name: inventory_new; Type: TABLE; Schema: lego; Owner: jargoth
--

CREATE TABLE lego.inventory_new (
    parent_design character varying(16) NOT NULL,
    parent_type character(1) NOT NULL,
    child_design character varying(16) NOT NULL,
    child_color integer NOT NULL,
    child_type character(1) NOT NULL,
    count integer
);


ALTER TABLE lego.inventory_new OWNER TO jargoth;

--
-- TOC entry 217 (class 1259 OID 16924)
-- Name: inventory_user; Type: TABLE; Schema: lego; Owner: jargoth
--

CREATE TABLE lego.inventory_user (
    "user" integer NOT NULL,
    design character varying(16) NOT NULL,
    color integer NOT NULL,
    type character(1) NOT NULL
);


ALTER TABLE lego.inventory_user OWNER TO jargoth;

--
-- TOC entry 228 (class 1259 OID 231064)
-- Name: model_id_seq; Type: SEQUENCE; Schema: lego; Owner: jargoth
--

CREATE SEQUENCE lego.model_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE lego.model_id_seq OWNER TO jargoth;

--
-- TOC entry 229 (class 1259 OID 231066)
-- Name: model; Type: TABLE; Schema: lego; Owner: jargoth
--

CREATE TABLE lego.model (
    id integer DEFAULT nextval('lego.model_id_seq'::regclass) NOT NULL,
    creator integer NOT NULL,
    title character varying(64),
    filename character varying(128),
    submodel integer DEFAULT 0,
    date_added timestamp with time zone DEFAULT now()
);


ALTER TABLE lego.model OWNER TO jargoth;

--
-- TOC entry 233 (class 1259 OID 231116)
-- Name: model_bricks; Type: TABLE; Schema: lego; Owner: jargoth
--

CREATE TABLE lego.model_bricks (
    model integer NOT NULL,
    "row" integer NOT NULL,
    step integer,
    val1 character varying(32),
    val2 character varying(32),
    val3 character varying(32),
    val4 character varying(32),
    val5 character varying(32),
    val6 character varying(32),
    val7 character varying(32),
    val8 character varying(32),
    val9 character varying(32),
    val10 character varying(32),
    val11 character varying(32),
    val12 character varying(32),
    val13 character varying(32),
    val14 character varying(32),
    val15 character varying(32)
);


ALTER TABLE lego.model_bricks OWNER TO jargoth;

--
-- TOC entry 236 (class 1259 OID 231170)
-- Name: model_bricks_admin; Type: TABLE; Schema: lego; Owner: jargoth
--

CREATE TABLE lego.model_bricks_admin (
    model integer NOT NULL,
    "row" integer NOT NULL,
    step integer,
    brick character varying(128)
);


ALTER TABLE lego.model_bricks_admin OWNER TO jargoth;

--
-- TOC entry 230 (class 1259 OID 231080)
-- Name: model_header; Type: TABLE; Schema: lego; Owner: jargoth
--

CREATE TABLE lego.model_header (
    model integer NOT NULL,
    "row" integer NOT NULL,
    val1 character varying(32),
    val2 character varying(32),
    val3 character varying(32),
    val4 character varying(32),
    val5 character varying(32),
    val6 character varying(32),
    val7 character varying(32),
    val8 character varying(32),
    val9 character varying(32),
    val10 character varying(32),
    val11 character varying(32),
    val12 character varying(32),
    val13 character varying(32),
    val14 character varying(32),
    val15 character varying(32)
);


ALTER TABLE lego.model_header OWNER TO jargoth;

--
-- TOC entry 231 (class 1259 OID 231085)
-- Name: model_line_error; Type: TABLE; Schema: lego; Owner: jargoth
--

CREATE TABLE lego.model_line_error (
    model integer NOT NULL,
    line character varying(256),
    "row" integer NOT NULL,
    step integer
);


ALTER TABLE lego.model_line_error OWNER TO jargoth;

--
-- TOC entry 241 (class 1259 OID 231286)
-- Name: model_modelcat; Type: TABLE; Schema: lego; Owner: jargoth
--

CREATE TABLE lego.model_modelcat (
    model integer NOT NULL,
    modelcat integer NOT NULL
);


ALTER TABLE lego.model_modelcat OWNER TO jargoth;

--
-- TOC entry 232 (class 1259 OID 231102)
-- Name: model_primitives; Type: TABLE; Schema: lego; Owner: jargoth
--

CREATE TABLE lego.model_primitives (
    "row" integer NOT NULL,
    model integer NOT NULL,
    val1 character varying(32),
    val2 character varying(32),
    val3 character varying(32),
    val4 character varying(32),
    val5 character varying(32),
    val6 character varying(32),
    val7 character varying(32),
    val8 character varying(32),
    val9 character varying(32),
    val10 character varying(32),
    val11 character varying(32),
    val12 character varying(32),
    val13 character varying(32),
    val14 character varying(32),
    val15 character varying(32),
    step integer
);


ALTER TABLE lego.model_primitives OWNER TO jargoth;

--
-- TOC entry 238 (class 1259 OID 231196)
-- Name: model_step; Type: TABLE; Schema: lego; Owner: jargoth
--

CREATE TABLE lego.model_step (
    model integer NOT NULL,
    step integer NOT NULL,
    picture_data bytea,
    picture_size integer DEFAULT 0,
    picture_type character varying(128),
    picture_55_data bytea,
    picture_55_size integer DEFAULT 0,
    picture_55_type character varying(128),
    picture_130_data bytea,
    picture_130_size integer DEFAULT 0,
    picture_130_type character varying(128),
    picture_400_data bytea,
    picture_400_size integer DEFAULT 0,
    picture_400_type character varying(128),
    picture_800_data bytea,
    picture_800_size integer DEFAULT 0,
    picture_800_type character varying(128)
);


ALTER TABLE lego.model_step OWNER TO jargoth;

--
-- TOC entry 235 (class 1259 OID 231148)
-- Name: model_submodel; Type: TABLE; Schema: lego; Owner: jargoth
--

CREATE TABLE lego.model_submodel (
    model integer NOT NULL,
    "row" integer NOT NULL,
    step integer,
    submodel integer,
    val1 character varying(32),
    val2 character varying(32),
    val3 character varying(32),
    val4 character varying(32),
    val5 character varying(32),
    val6 character varying(32),
    val7 character varying(32),
    val8 character varying(32),
    val9 character varying(32),
    val10 character varying(32),
    val11 character varying(32),
    val12 character varying(32),
    val13 character varying(32),
    val14 character varying(32),
    val15 character varying(32)
);


ALTER TABLE lego.model_submodel OWNER TO jargoth;

--
-- TOC entry 240 (class 1259 OID 231280)
-- Name: modelcat; Type: TABLE; Schema: lego; Owner: jargoth
--

CREATE TABLE lego.modelcat (
    id integer NOT NULL,
    parent integer DEFAULT 0,
    title character varying(32)
);


ALTER TABLE lego.modelcat OWNER TO jargoth;

--
-- TOC entry 239 (class 1259 OID 231278)
-- Name: modelcat_id_seq; Type: SEQUENCE; Schema: lego; Owner: jargoth
--

CREATE SEQUENCE lego.modelcat_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE lego.modelcat_id_seq OWNER TO jargoth;

--
-- TOC entry 222 (class 1259 OID 118558)
-- Name: project_order_seq; Type: SEQUENCE; Schema: lego; Owner: jargoth
--

CREATE SEQUENCE lego.project_order_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE lego.project_order_seq OWNER TO jargoth;

--
-- TOC entry 223 (class 1259 OID 118560)
-- Name: project; Type: TABLE; Schema: lego; Owner: jargoth
--

CREATE TABLE lego.project (
    "order" integer DEFAULT nextval('lego.project_order_seq'::regclass) NOT NULL,
    "user" integer,
    name character varying(32)
);


ALTER TABLE lego.project OWNER TO jargoth;

--
-- TOC entry 224 (class 1259 OID 118566)
-- Name: project_bricks; Type: TABLE; Schema: lego; Owner: jargoth
--

CREATE TABLE lego.project_bricks (
    project integer NOT NULL,
    design integer NOT NULL,
    color integer NOT NULL,
    collected integer DEFAULT 0,
    needed integer
);


ALTER TABLE lego.project_bricks OWNER TO jargoth;

--
-- TOC entry 225 (class 1259 OID 191814)
-- Name: project_bricks_container; Type: TABLE; Schema: lego; Owner: jargoth
--

CREATE TABLE lego.project_bricks_container (
    project integer NOT NULL,
    design integer NOT NULL,
    color integer NOT NULL,
    "user" integer DEFAULT 1 NOT NULL,
    container character varying(10) NOT NULL,
    collected integer DEFAULT 0
);


ALTER TABLE lego.project_bricks_container OWNER TO jargoth;

--
-- TOC entry 243 (class 1259 OID 232775)
-- Name: project_model; Type: TABLE; Schema: lego; Owner: jargoth
--

CREATE TABLE lego.project_model (
    project integer NOT NULL,
    model integer NOT NULL
);


ALTER TABLE lego.project_model OWNER TO jargoth;

--
-- TOC entry 237 (class 1259 OID 231186)
-- Name: regenerate_modelpic; Type: TABLE; Schema: lego; Owner: jargoth
--

CREATE TABLE lego.regenerate_modelpic (
    model integer NOT NULL
);


ALTER TABLE lego.regenerate_modelpic OWNER TO jargoth;

--
-- TOC entry 206 (class 1259 OID 16474)
-- Name: replacing; Type: TABLE; Schema: lego; Owner: jargoth
--

CREATE TABLE lego.replacing (
    obsolete integer NOT NULL,
    replacing integer NOT NULL,
    what character varying(8) NOT NULL
);


ALTER TABLE lego.replacing OWNER TO jargoth;

--
-- TOC entry 218 (class 1259 OID 17382)
-- Name: similar_colors; Type: TABLE; Schema: lego; Owner: jargoth
--

CREATE TABLE lego.similar_colors (
    color1 integer NOT NULL,
    color2 integer NOT NULL
);


ALTER TABLE lego.similar_colors OWNER TO jargoth;

--
-- TOC entry 198 (class 1259 OID 16397)
-- Name: user_id_seq; Type: SEQUENCE; Schema: lego; Owner: jargoth
--

CREATE SEQUENCE lego.user_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE lego.user_id_seq OWNER TO jargoth;

--
-- TOC entry 199 (class 1259 OID 16399)
-- Name: user; Type: TABLE; Schema: lego; Owner: jargoth
--

CREATE TABLE lego."user" (
    id integer DEFAULT nextval('lego.user_id_seq'::regclass) NOT NULL,
    "user" character varying(20) NOT NULL,
    pwd character(32) NOT NULL,
    fname character varying(20),
    lname character varying(20),
    value character varying(20),
    weight character varying(20),
    regenerate_bricklink boolean DEFAULT false
);


ALTER TABLE lego."user" OWNER TO jargoth;

--
-- TOC entry 219 (class 1259 OID 37399)
-- Name: user_parameters; Type: TABLE; Schema: lego; Owner: jargoth
--

CREATE TABLE lego.user_parameters (
    "user" integer NOT NULL,
    parameter character varying(32) NOT NULL,
    value character varying(128)
);


ALTER TABLE lego.user_parameters OWNER TO jargoth;

--
-- TOC entry 2907 (class 2606 OID 16485)
-- Name: bricklink_color bricklink_color_bricklink_key; Type: CONSTRAINT; Schema: lego; Owner: jargoth
--

ALTER TABLE ONLY lego.bricklink_color
    ADD CONSTRAINT bricklink_color_bricklink_key UNIQUE (bricklink);


--
-- TOC entry 2909 (class 2606 OID 16483)
-- Name: bricklink_color bricklink_color_pkey; Type: CONSTRAINT; Schema: lego; Owner: jargoth
--

ALTER TABLE ONLY lego.bricklink_color
    ADD CONSTRAINT bricklink_color_pkey PRIMARY KEY (color);


--
-- TOC entry 2901 (class 2606 OID 16714)
-- Name: bricklink_design bricklink_design_bricklink_type_key; Type: CONSTRAINT; Schema: lego; Owner: jargoth
--

ALTER TABLE ONLY lego.bricklink_design
    ADD CONSTRAINT bricklink_design_bricklink_type_key UNIQUE (bricklink, type);


--
-- TOC entry 2903 (class 2606 OID 16710)
-- Name: bricklink_design bricklink_design_pkey; Type: CONSTRAINT; Schema: lego; Owner: jargoth
--

ALTER TABLE ONLY lego.bricklink_design
    ADD CONSTRAINT bricklink_design_pkey PRIMARY KEY (design);


--
-- TOC entry 2919 (class 2606 OID 16649)
-- Name: bricklink_error bricklink_error_pkey; Type: CONSTRAINT; Schema: lego; Owner: jargoth
--

ALTER TABLE ONLY lego.bricklink_error
    ADD CONSTRAINT bricklink_error_pkey PRIMARY KEY (design, color);


--
-- TOC entry 2917 (class 2606 OID 16614)
-- Name: bricklink bricklink_pkey; Type: CONSTRAINT; Schema: lego; Owner: jargoth
--

ALTER TABLE ONLY lego.bricklink
    ADD CONSTRAINT bricklink_pkey PRIMARY KEY (design, color, "user", container);


--
-- TOC entry 2925 (class 2606 OID 16701)
-- Name: bricklink_price bricklink_price_pkey; Type: CONSTRAINT; Schema: lego; Owner: jargoth
--

ALTER TABLE ONLY lego.bricklink_price
    ADD CONSTRAINT bricklink_price_pkey PRIMARY KEY (design, color, design_type);


--
-- TOC entry 2921 (class 2606 OID 16664)
-- Name: bricklink_xml_generate bricklink_xml_generate_pkey; Type: CONSTRAINT; Schema: lego; Owner: jargoth
--

ALTER TABLE ONLY lego.bricklink_xml_generate
    ADD CONSTRAINT bricklink_xml_generate_pkey PRIMARY KEY ("user");


--
-- TOC entry 2935 (class 2606 OID 38255)
-- Name: bricklink_xml bricklink_xml_pkey; Type: CONSTRAINT; Schema: lego; Owner: jargoth
--

ALTER TABLE ONLY lego.bricklink_xml
    ADD CONSTRAINT bricklink_xml_pkey PRIMARY KEY ("user", id);


--
-- TOC entry 2923 (class 2606 OID 16674)
-- Name: bricklink_xml_update_generate bricklink_xml_update_generate_pkey; Type: CONSTRAINT; Schema: lego; Owner: jargoth
--

ALTER TABLE ONLY lego.bricklink_xml_update_generate
    ADD CONSTRAINT bricklink_xml_update_generate_pkey PRIMARY KEY ("user");


--
-- TOC entry 2943 (class 2606 OID 192476)
-- Name: bricklink_xml_update bricklink_xml_update_pkey; Type: CONSTRAINT; Schema: lego; Owner: jargoth
--

ALTER TABLE ONLY lego.bricklink_xml_update
    ADD CONSTRAINT bricklink_xml_update_pkey PRIMARY KEY ("user", id);


--
-- TOC entry 2969 (class 2606 OID 231597)
-- Name: color_ldraw color_ldraw_pkey; Type: CONSTRAINT; Schema: lego; Owner: jargoth
--

ALTER TABLE ONLY lego.color_ldraw
    ADD CONSTRAINT color_ldraw_pkey PRIMARY KEY (ldraw);


--
-- TOC entry 2897 (class 2606 OID 16428)
-- Name: color color_pkey; Type: CONSTRAINT; Schema: lego; Owner: jargoth
--

ALTER TABLE ONLY lego.color
    ADD CONSTRAINT color_pkey PRIMARY KEY (id);


--
-- TOC entry 2913 (class 2606 OID 16508)
-- Name: container container_pkey; Type: CONSTRAINT; Schema: lego; Owner: jargoth
--

ALTER TABLE ONLY lego.container
    ADD CONSTRAINT container_pkey PRIMARY KEY (id, "user");


--
-- TOC entry 2891 (class 2606 OID 16406)
-- Name: user czxzs; Type: CONSTRAINT; Schema: lego; Owner: jargoth
--

ALTER TABLE ONLY lego."user"
    ADD CONSTRAINT czxzs UNIQUE ("user");


--
-- TOC entry 2911 (class 2606 OID 16490)
-- Name: design_color design_color_pkey; Type: CONSTRAINT; Schema: lego; Owner: jargoth
--

ALTER TABLE ONLY lego.design_color
    ADD CONSTRAINT design_color_pkey PRIMARY KEY (design, color);


--
-- TOC entry 2915 (class 2606 OID 16550)
-- Name: design_color_user_container design_color_user_container_pkey; Type: CONSTRAINT; Schema: lego; Owner: jargoth
--

ALTER TABLE ONLY lego.design_color_user_container
    ADD CONSTRAINT design_color_user_container_pkey PRIMARY KEY (design, color, "user", container);


--
-- TOC entry 2899 (class 2606 OID 16433)
-- Name: design_color_user design_color_user_pkey; Type: CONSTRAINT; Schema: lego; Owner: jargoth
--

ALTER TABLE ONLY lego.design_color_user
    ADD CONSTRAINT design_color_user_pkey PRIMARY KEY (design, color, "user");


--
-- TOC entry 2895 (class 2606 OID 16422)
-- Name: design design_pkey; Type: CONSTRAINT; Schema: lego; Owner: jargoth
--

ALTER TABLE ONLY lego.design
    ADD CONSTRAINT design_pkey PRIMARY KEY (id);


--
-- TOC entry 2955 (class 2606 OID 231130)
-- Name: filename_design_color filename_design_color_pkey; Type: CONSTRAINT; Schema: lego; Owner: jargoth
--

ALTER TABLE ONLY lego.filename_design_color
    ADD CONSTRAINT filename_design_color_pkey PRIMARY KEY (filename);


--
-- TOC entry 2973 (class 2606 OID 241194)
-- Name: inventory_new inventory_new_pkey; Type: CONSTRAINT; Schema: lego; Owner: jargoth
--

ALTER TABLE ONLY lego.inventory_new
    ADD CONSTRAINT inventory_new_pkey PRIMARY KEY (parent_design, parent_type, child_design, child_color, child_type);


--
-- TOC entry 2927 (class 2606 OID 16724)
-- Name: inventory inventory_pkey; Type: CONSTRAINT; Schema: lego; Owner: jargoth
--

ALTER TABLE ONLY lego.inventory
    ADD CONSTRAINT inventory_pkey PRIMARY KEY (parent_design, parent_color, parent_type, child_design, child_type, child_color);


--
-- TOC entry 2929 (class 2606 OID 20172)
-- Name: inventory_user inventory_user_pkey; Type: CONSTRAINT; Schema: lego; Owner: jargoth
--

ALTER TABLE ONLY lego.inventory_user
    ADD CONSTRAINT inventory_user_pkey PRIMARY KEY ("user", design, color, type);


--
-- TOC entry 2959 (class 2606 OID 231174)
-- Name: model_bricks_admin model_bricks_admin_pkey; Type: CONSTRAINT; Schema: lego; Owner: jargoth
--

ALTER TABLE ONLY lego.model_bricks_admin
    ADD CONSTRAINT model_bricks_admin_pkey PRIMARY KEY (model, "row");


--
-- TOC entry 2953 (class 2606 OID 231120)
-- Name: model_bricks model_bricks_pkey; Type: CONSTRAINT; Schema: lego; Owner: jargoth
--

ALTER TABLE ONLY lego.model_bricks
    ADD CONSTRAINT model_bricks_pkey PRIMARY KEY (model, "row");


--
-- TOC entry 2947 (class 2606 OID 231084)
-- Name: model_header model_header_pkey; Type: CONSTRAINT; Schema: lego; Owner: jargoth
--

ALTER TABLE ONLY lego.model_header
    ADD CONSTRAINT model_header_pkey PRIMARY KEY (model, "row");


--
-- TOC entry 2949 (class 2606 OID 231089)
-- Name: model_line_error model_line_error_pkey; Type: CONSTRAINT; Schema: lego; Owner: jargoth
--

ALTER TABLE ONLY lego.model_line_error
    ADD CONSTRAINT model_line_error_pkey PRIMARY KEY (model, "row");


--
-- TOC entry 2967 (class 2606 OID 231290)
-- Name: model_modelcat model_modelcat_pkey; Type: CONSTRAINT; Schema: lego; Owner: jargoth
--

ALTER TABLE ONLY lego.model_modelcat
    ADD CONSTRAINT model_modelcat_pkey PRIMARY KEY (modelcat, model);


--
-- TOC entry 2945 (class 2606 OID 231071)
-- Name: model model_pkey; Type: CONSTRAINT; Schema: lego; Owner: jargoth
--

ALTER TABLE ONLY lego.model
    ADD CONSTRAINT model_pkey PRIMARY KEY (id);


--
-- TOC entry 2951 (class 2606 OID 231106)
-- Name: model_primitives model_primitives_pkey; Type: CONSTRAINT; Schema: lego; Owner: jargoth
--

ALTER TABLE ONLY lego.model_primitives
    ADD CONSTRAINT model_primitives_pkey PRIMARY KEY (model, "row");


--
-- TOC entry 2963 (class 2606 OID 231204)
-- Name: model_step model_step_pkey; Type: CONSTRAINT; Schema: lego; Owner: jargoth
--

ALTER TABLE ONLY lego.model_step
    ADD CONSTRAINT model_step_pkey PRIMARY KEY (model, step);


--
-- TOC entry 2957 (class 2606 OID 231152)
-- Name: model_submodel model_submodel_pkey; Type: CONSTRAINT; Schema: lego; Owner: jargoth
--

ALTER TABLE ONLY lego.model_submodel
    ADD CONSTRAINT model_submodel_pkey PRIMARY KEY (model, "row");


--
-- TOC entry 2965 (class 2606 OID 231285)
-- Name: modelcat modelcat_pkey; Type: CONSTRAINT; Schema: lego; Owner: jargoth
--

ALTER TABLE ONLY lego.modelcat
    ADD CONSTRAINT modelcat_pkey PRIMARY KEY (id);


--
-- TOC entry 2941 (class 2606 OID 191819)
-- Name: project_bricks_container project_bricks_container_pkey; Type: CONSTRAINT; Schema: lego; Owner: jargoth
--

ALTER TABLE ONLY lego.project_bricks_container
    ADD CONSTRAINT project_bricks_container_pkey PRIMARY KEY (project, design, color, "user", container);


--
-- TOC entry 2939 (class 2606 OID 118571)
-- Name: project_bricks project_bricks_pkey; Type: CONSTRAINT; Schema: lego; Owner: jargoth
--

ALTER TABLE ONLY lego.project_bricks
    ADD CONSTRAINT project_bricks_pkey PRIMARY KEY (project, design, color);


--
-- TOC entry 2971 (class 2606 OID 232779)
-- Name: project_model project_model_pkey; Type: CONSTRAINT; Schema: lego; Owner: jargoth
--

ALTER TABLE ONLY lego.project_model
    ADD CONSTRAINT project_model_pkey PRIMARY KEY (project, model);


--
-- TOC entry 2937 (class 2606 OID 118565)
-- Name: project project_pkey; Type: CONSTRAINT; Schema: lego; Owner: jargoth
--

ALTER TABLE ONLY lego.project
    ADD CONSTRAINT project_pkey PRIMARY KEY ("order");


--
-- TOC entry 2961 (class 2606 OID 231190)
-- Name: regenerate_modelpic regenerate_modelpic_pkey; Type: CONSTRAINT; Schema: lego; Owner: jargoth
--

ALTER TABLE ONLY lego.regenerate_modelpic
    ADD CONSTRAINT regenerate_modelpic_pkey PRIMARY KEY (model);


--
-- TOC entry 2905 (class 2606 OID 16478)
-- Name: replacing replacing_pkey; Type: CONSTRAINT; Schema: lego; Owner: jargoth
--

ALTER TABLE ONLY lego.replacing
    ADD CONSTRAINT replacing_pkey PRIMARY KEY (obsolete, what);


--
-- TOC entry 2931 (class 2606 OID 17386)
-- Name: similar_colors similiar_colors_pkey; Type: CONSTRAINT; Schema: lego; Owner: jargoth
--

ALTER TABLE ONLY lego.similar_colors
    ADD CONSTRAINT similiar_colors_pkey PRIMARY KEY (color1, color2);


--
-- TOC entry 2933 (class 2606 OID 37403)
-- Name: user_parameters user_parameters_pkey; Type: CONSTRAINT; Schema: lego; Owner: jargoth
--

ALTER TABLE ONLY lego.user_parameters
    ADD CONSTRAINT user_parameters_pkey PRIMARY KEY (parameter, "user");


--
-- TOC entry 2893 (class 2606 OID 16404)
-- Name: user user_pkey; Type: CONSTRAINT; Schema: lego; Owner: jargoth
--

ALTER TABLE ONLY lego."user"
    ADD CONSTRAINT user_pkey PRIMARY KEY (id);


--
-- TOC entry 2986 (class 2606 OID 16620)
-- Name: bricklink bricklink_color_fkey; Type: FK CONSTRAINT; Schema: lego; Owner: jargoth
--

ALTER TABLE ONLY lego.bricklink
    ADD CONSTRAINT bricklink_color_fkey FOREIGN KEY (color) REFERENCES lego.color(id);


--
-- TOC entry 2988 (class 2606 OID 16630)
-- Name: bricklink bricklink_container_fkey; Type: FK CONSTRAINT; Schema: lego; Owner: jargoth
--

ALTER TABLE ONLY lego.bricklink
    ADD CONSTRAINT bricklink_container_fkey FOREIGN KEY (container, "user") REFERENCES lego.container(id, "user");


--
-- TOC entry 2977 (class 2606 OID 16469)
-- Name: bricklink_design bricklink_design_design_fkey; Type: FK CONSTRAINT; Schema: lego; Owner: jargoth
--

ALTER TABLE ONLY lego.bricklink_design
    ADD CONSTRAINT bricklink_design_design_fkey FOREIGN KEY (design) REFERENCES lego.design(id);


--
-- TOC entry 2985 (class 2606 OID 16615)
-- Name: bricklink bricklink_design_fkey; Type: FK CONSTRAINT; Schema: lego; Owner: jargoth
--

ALTER TABLE ONLY lego.bricklink
    ADD CONSTRAINT bricklink_design_fkey FOREIGN KEY (design) REFERENCES lego.design(id);


--
-- TOC entry 2990 (class 2606 OID 16655)
-- Name: bricklink_error bricklink_error_color_fkey; Type: FK CONSTRAINT; Schema: lego; Owner: jargoth
--

ALTER TABLE ONLY lego.bricklink_error
    ADD CONSTRAINT bricklink_error_color_fkey FOREIGN KEY (color) REFERENCES lego.color(id);


--
-- TOC entry 2989 (class 2606 OID 16650)
-- Name: bricklink_error bricklink_error_design_fkey; Type: FK CONSTRAINT; Schema: lego; Owner: jargoth
--

ALTER TABLE ONLY lego.bricklink_error
    ADD CONSTRAINT bricklink_error_design_fkey FOREIGN KEY (design) REFERENCES lego.design(id);


--
-- TOC entry 2993 (class 2606 OID 16702)
-- Name: bricklink_price bricklink_price_color_fkey; Type: FK CONSTRAINT; Schema: lego; Owner: jargoth
--

ALTER TABLE ONLY lego.bricklink_price
    ADD CONSTRAINT bricklink_price_color_fkey FOREIGN KEY (color) REFERENCES lego.bricklink_color(bricklink);


--
-- TOC entry 2987 (class 2606 OID 16625)
-- Name: bricklink bricklink_user_fkey; Type: FK CONSTRAINT; Schema: lego; Owner: jargoth
--

ALTER TABLE ONLY lego.bricklink
    ADD CONSTRAINT bricklink_user_fkey FOREIGN KEY ("user") REFERENCES lego."user"(id);


--
-- TOC entry 2991 (class 2606 OID 16665)
-- Name: bricklink_xml_generate bricklink_xml_generate_user_fkey; Type: FK CONSTRAINT; Schema: lego; Owner: jargoth
--

ALTER TABLE ONLY lego.bricklink_xml_generate
    ADD CONSTRAINT bricklink_xml_generate_user_fkey FOREIGN KEY ("user") REFERENCES lego."user"(id);


--
-- TOC entry 2992 (class 2606 OID 16675)
-- Name: bricklink_xml_update_generate bricklink_xml_update_generate_user_fkey; Type: FK CONSTRAINT; Schema: lego; Owner: jargoth
--

ALTER TABLE ONLY lego.bricklink_xml_update_generate
    ADD CONSTRAINT bricklink_xml_update_generate_user_fkey FOREIGN KEY ("user") REFERENCES lego."user"(id);


--
-- TOC entry 3014 (class 2606 OID 192477)
-- Name: bricklink_xml_update bricklink_xml_update_user_fkey; Type: FK CONSTRAINT; Schema: lego; Owner: jargoth
--

ALTER TABLE ONLY lego.bricklink_xml_update
    ADD CONSTRAINT bricklink_xml_update_user_fkey FOREIGN KEY ("user") REFERENCES lego."user"(id);


--
-- TOC entry 3004 (class 2606 OID 38014)
-- Name: bricklink_xml bricklink_xml_user_fkey; Type: FK CONSTRAINT; Schema: lego; Owner: jargoth
--

ALTER TABLE ONLY lego.bricklink_xml
    ADD CONSTRAINT bricklink_xml_user_fkey FOREIGN KEY ("user") REFERENCES lego."user"(id);


--
-- TOC entry 3029 (class 2606 OID 231583)
-- Name: color_ldraw color_ldraw_color_fkey; Type: FK CONSTRAINT; Schema: lego; Owner: jargoth
--

ALTER TABLE ONLY lego.color_ldraw
    ADD CONSTRAINT color_ldraw_color_fkey FOREIGN KEY (color) REFERENCES lego.color(id);


--
-- TOC entry 2980 (class 2606 OID 16509)
-- Name: container container_user_fkey; Type: FK CONSTRAINT; Schema: lego; Owner: jargoth
--

ALTER TABLE ONLY lego.container
    ADD CONSTRAINT container_user_fkey FOREIGN KEY ("user") REFERENCES lego."user"(id);


--
-- TOC entry 2979 (class 2606 OID 16496)
-- Name: design_color design_color_color_fkey; Type: FK CONSTRAINT; Schema: lego; Owner: jargoth
--

ALTER TABLE ONLY lego.design_color
    ADD CONSTRAINT design_color_color_fkey FOREIGN KEY (color) REFERENCES lego.color(id);


--
-- TOC entry 2978 (class 2606 OID 16491)
-- Name: design_color design_color_design_fkey; Type: FK CONSTRAINT; Schema: lego; Owner: jargoth
--

ALTER TABLE ONLY lego.design_color
    ADD CONSTRAINT design_color_design_fkey FOREIGN KEY (design) REFERENCES lego.design(id);


--
-- TOC entry 2975 (class 2606 OID 16439)
-- Name: design_color_user design_color_user_color_fkey; Type: FK CONSTRAINT; Schema: lego; Owner: jargoth
--

ALTER TABLE ONLY lego.design_color_user
    ADD CONSTRAINT design_color_user_color_fkey FOREIGN KEY (color) REFERENCES lego.color(id);


--
-- TOC entry 2982 (class 2606 OID 16556)
-- Name: design_color_user_container design_color_user_container_color_fkey; Type: FK CONSTRAINT; Schema: lego; Owner: jargoth
--

ALTER TABLE ONLY lego.design_color_user_container
    ADD CONSTRAINT design_color_user_container_color_fkey FOREIGN KEY (color) REFERENCES lego.color(id);


--
-- TOC entry 2984 (class 2606 OID 16566)
-- Name: design_color_user_container design_color_user_container_container_fkey; Type: FK CONSTRAINT; Schema: lego; Owner: jargoth
--

ALTER TABLE ONLY lego.design_color_user_container
    ADD CONSTRAINT design_color_user_container_container_fkey FOREIGN KEY (container, "user") REFERENCES lego.container(id, "user");


--
-- TOC entry 2981 (class 2606 OID 16551)
-- Name: design_color_user_container design_color_user_container_design_fkey; Type: FK CONSTRAINT; Schema: lego; Owner: jargoth
--

ALTER TABLE ONLY lego.design_color_user_container
    ADD CONSTRAINT design_color_user_container_design_fkey FOREIGN KEY (design) REFERENCES lego.design(id);


--
-- TOC entry 2983 (class 2606 OID 16561)
-- Name: design_color_user_container design_color_user_container_user_fkey; Type: FK CONSTRAINT; Schema: lego; Owner: jargoth
--

ALTER TABLE ONLY lego.design_color_user_container
    ADD CONSTRAINT design_color_user_container_user_fkey FOREIGN KEY ("user") REFERENCES lego."user"(id);


--
-- TOC entry 2974 (class 2606 OID 16434)
-- Name: design_color_user design_color_user_design_fkey; Type: FK CONSTRAINT; Schema: lego; Owner: jargoth
--

ALTER TABLE ONLY lego.design_color_user
    ADD CONSTRAINT design_color_user_design_fkey FOREIGN KEY (design) REFERENCES lego.design(id);


--
-- TOC entry 2976 (class 2606 OID 16444)
-- Name: design_color_user design_color_user_user_fkey; Type: FK CONSTRAINT; Schema: lego; Owner: jargoth
--

ALTER TABLE ONLY lego.design_color_user
    ADD CONSTRAINT design_color_user_user_fkey FOREIGN KEY ("user") REFERENCES lego."user"(id);


--
-- TOC entry 3021 (class 2606 OID 231136)
-- Name: filename_design_color filename_design_color_color_fkey; Type: FK CONSTRAINT; Schema: lego; Owner: jargoth
--

ALTER TABLE ONLY lego.filename_design_color
    ADD CONSTRAINT filename_design_color_color_fkey FOREIGN KEY (color) REFERENCES lego.color(id);


--
-- TOC entry 3020 (class 2606 OID 231131)
-- Name: filename_design_color filename_design_color_design_fkey; Type: FK CONSTRAINT; Schema: lego; Owner: jargoth
--

ALTER TABLE ONLY lego.filename_design_color
    ADD CONSTRAINT filename_design_color_design_fkey FOREIGN KEY (design) REFERENCES lego.design(id);


--
-- TOC entry 2996 (class 2606 OID 16735)
-- Name: inventory inventory_child_color_fkey; Type: FK CONSTRAINT; Schema: lego; Owner: jargoth
--

ALTER TABLE ONLY lego.inventory
    ADD CONSTRAINT inventory_child_color_fkey FOREIGN KEY (child_color) REFERENCES lego.bricklink_color(bricklink);


--
-- TOC entry 2995 (class 2606 OID 16730)
-- Name: inventory inventory_child_design_fkey; Type: FK CONSTRAINT; Schema: lego; Owner: jargoth
--

ALTER TABLE ONLY lego.inventory
    ADD CONSTRAINT inventory_child_design_fkey FOREIGN KEY (child_design, child_type) REFERENCES lego.bricklink_design(bricklink, type);


--
-- TOC entry 3034 (class 2606 OID 241205)
-- Name: inventory_new inventory_new_child_color_fkey; Type: FK CONSTRAINT; Schema: lego; Owner: jargoth
--

ALTER TABLE ONLY lego.inventory_new
    ADD CONSTRAINT inventory_new_child_color_fkey FOREIGN KEY (child_color) REFERENCES lego.bricklink_color(bricklink);


--
-- TOC entry 3033 (class 2606 OID 241200)
-- Name: inventory_new inventory_new_child_design_fkey; Type: FK CONSTRAINT; Schema: lego; Owner: jargoth
--

ALTER TABLE ONLY lego.inventory_new
    ADD CONSTRAINT inventory_new_child_design_fkey FOREIGN KEY (child_design, child_type) REFERENCES lego.bricklink_design(bricklink, type);


--
-- TOC entry 3032 (class 2606 OID 241195)
-- Name: inventory_new inventory_new_parent_design_fkey; Type: FK CONSTRAINT; Schema: lego; Owner: jargoth
--

ALTER TABLE ONLY lego.inventory_new
    ADD CONSTRAINT inventory_new_parent_design_fkey FOREIGN KEY (parent_design, parent_type) REFERENCES lego.bricklink_design(bricklink, type);


--
-- TOC entry 2994 (class 2606 OID 16725)
-- Name: inventory inventory_parent_color_fkey; Type: FK CONSTRAINT; Schema: lego; Owner: jargoth
--

ALTER TABLE ONLY lego.inventory
    ADD CONSTRAINT inventory_parent_color_fkey FOREIGN KEY (parent_color) REFERENCES lego.bricklink_color(bricklink);


--
-- TOC entry 2997 (class 2606 OID 16740)
-- Name: inventory inventory_parent_design_fkey; Type: FK CONSTRAINT; Schema: lego; Owner: jargoth
--

ALTER TABLE ONLY lego.inventory
    ADD CONSTRAINT inventory_parent_design_fkey FOREIGN KEY (parent_design, parent_type) REFERENCES lego.bricklink_design(bricklink, type);


--
-- TOC entry 3000 (class 2606 OID 20178)
-- Name: inventory_user inventory_user_color_fkey; Type: FK CONSTRAINT; Schema: lego; Owner: jargoth
--

ALTER TABLE ONLY lego.inventory_user
    ADD CONSTRAINT inventory_user_color_fkey FOREIGN KEY (color) REFERENCES lego.bricklink_color(bricklink);


--
-- TOC entry 2999 (class 2606 OID 20173)
-- Name: inventory_user inventory_user_design_fkey; Type: FK CONSTRAINT; Schema: lego; Owner: jargoth
--

ALTER TABLE ONLY lego.inventory_user
    ADD CONSTRAINT inventory_user_design_fkey FOREIGN KEY (design, type) REFERENCES lego.bricklink_design(bricklink, type);


--
-- TOC entry 2998 (class 2606 OID 16929)
-- Name: inventory_user inventory_user_user_fkey; Type: FK CONSTRAINT; Schema: lego; Owner: jargoth
--

ALTER TABLE ONLY lego.inventory_user
    ADD CONSTRAINT inventory_user_user_fkey FOREIGN KEY ("user") REFERENCES lego."user"(id);


--
-- TOC entry 3024 (class 2606 OID 231175)
-- Name: model_bricks_admin model_bricks_admin_model_fkey; Type: FK CONSTRAINT; Schema: lego; Owner: jargoth
--

ALTER TABLE ONLY lego.model_bricks_admin
    ADD CONSTRAINT model_bricks_admin_model_fkey FOREIGN KEY (model) REFERENCES lego.model(id);


--
-- TOC entry 3019 (class 2606 OID 231121)
-- Name: model_bricks model_bricks_model_fkey; Type: FK CONSTRAINT; Schema: lego; Owner: jargoth
--

ALTER TABLE ONLY lego.model_bricks
    ADD CONSTRAINT model_bricks_model_fkey FOREIGN KEY (model) REFERENCES lego.model(id);


--
-- TOC entry 3015 (class 2606 OID 231072)
-- Name: model model_creator_fkey; Type: FK CONSTRAINT; Schema: lego; Owner: jargoth
--

ALTER TABLE ONLY lego.model
    ADD CONSTRAINT model_creator_fkey FOREIGN KEY (creator) REFERENCES lego."user"(id);


--
-- TOC entry 3016 (class 2606 OID 231095)
-- Name: model_header model_header_model_fkey; Type: FK CONSTRAINT; Schema: lego; Owner: jargoth
--

ALTER TABLE ONLY lego.model_header
    ADD CONSTRAINT model_header_model_fkey FOREIGN KEY (model) REFERENCES lego.model(id);


--
-- TOC entry 3017 (class 2606 OID 231090)
-- Name: model_line_error model_line_error_model_fkey; Type: FK CONSTRAINT; Schema: lego; Owner: jargoth
--

ALTER TABLE ONLY lego.model_line_error
    ADD CONSTRAINT model_line_error_model_fkey FOREIGN KEY (model) REFERENCES lego.model(id);


--
-- TOC entry 3027 (class 2606 OID 231291)
-- Name: model_modelcat model_modelcat_model_fkey; Type: FK CONSTRAINT; Schema: lego; Owner: jargoth
--

ALTER TABLE ONLY lego.model_modelcat
    ADD CONSTRAINT model_modelcat_model_fkey FOREIGN KEY (model) REFERENCES lego.model(id);


--
-- TOC entry 3028 (class 2606 OID 231296)
-- Name: model_modelcat model_modelcat_modelcat_fkey; Type: FK CONSTRAINT; Schema: lego; Owner: jargoth
--

ALTER TABLE ONLY lego.model_modelcat
    ADD CONSTRAINT model_modelcat_modelcat_fkey FOREIGN KEY (modelcat) REFERENCES lego.modelcat(id);


--
-- TOC entry 3018 (class 2606 OID 231107)
-- Name: model_primitives model_primitives_model_fkey; Type: FK CONSTRAINT; Schema: lego; Owner: jargoth
--

ALTER TABLE ONLY lego.model_primitives
    ADD CONSTRAINT model_primitives_model_fkey FOREIGN KEY (model) REFERENCES lego.model(id);


--
-- TOC entry 3026 (class 2606 OID 231205)
-- Name: model_step model_step_model_fkey; Type: FK CONSTRAINT; Schema: lego; Owner: jargoth
--

ALTER TABLE ONLY lego.model_step
    ADD CONSTRAINT model_step_model_fkey FOREIGN KEY (model) REFERENCES lego.model(id);


--
-- TOC entry 3022 (class 2606 OID 231153)
-- Name: model_submodel model_submodel_model_fkey; Type: FK CONSTRAINT; Schema: lego; Owner: jargoth
--

ALTER TABLE ONLY lego.model_submodel
    ADD CONSTRAINT model_submodel_model_fkey FOREIGN KEY (model) REFERENCES lego.model(id);


--
-- TOC entry 3023 (class 2606 OID 231158)
-- Name: model_submodel model_submodel_submodel_fkey; Type: FK CONSTRAINT; Schema: lego; Owner: jargoth
--

ALTER TABLE ONLY lego.model_submodel
    ADD CONSTRAINT model_submodel_submodel_fkey FOREIGN KEY (submodel) REFERENCES lego.model(id);


--
-- TOC entry 3008 (class 2606 OID 118582)
-- Name: project_bricks project_bricks_color_fkey; Type: FK CONSTRAINT; Schema: lego; Owner: jargoth
--

ALTER TABLE ONLY lego.project_bricks
    ADD CONSTRAINT project_bricks_color_fkey FOREIGN KEY (color) REFERENCES lego.color(id);


--
-- TOC entry 3011 (class 2606 OID 191830)
-- Name: project_bricks_container project_bricks_container_color_fkey; Type: FK CONSTRAINT; Schema: lego; Owner: jargoth
--

ALTER TABLE ONLY lego.project_bricks_container
    ADD CONSTRAINT project_bricks_container_color_fkey FOREIGN KEY (color) REFERENCES lego.color(id);


--
-- TOC entry 3013 (class 2606 OID 191840)
-- Name: project_bricks_container project_bricks_container_container_fkey; Type: FK CONSTRAINT; Schema: lego; Owner: jargoth
--

ALTER TABLE ONLY lego.project_bricks_container
    ADD CONSTRAINT project_bricks_container_container_fkey FOREIGN KEY (container, "user") REFERENCES lego.container(id, "user");


--
-- TOC entry 3010 (class 2606 OID 191825)
-- Name: project_bricks_container project_bricks_container_design_fkey; Type: FK CONSTRAINT; Schema: lego; Owner: jargoth
--

ALTER TABLE ONLY lego.project_bricks_container
    ADD CONSTRAINT project_bricks_container_design_fkey FOREIGN KEY (design) REFERENCES lego.design(id);


--
-- TOC entry 3009 (class 2606 OID 191820)
-- Name: project_bricks_container project_bricks_container_project_fkey; Type: FK CONSTRAINT; Schema: lego; Owner: jargoth
--

ALTER TABLE ONLY lego.project_bricks_container
    ADD CONSTRAINT project_bricks_container_project_fkey FOREIGN KEY (project) REFERENCES lego.project("order");


--
-- TOC entry 3012 (class 2606 OID 191835)
-- Name: project_bricks_container project_bricks_container_user_fkey; Type: FK CONSTRAINT; Schema: lego; Owner: jargoth
--

ALTER TABLE ONLY lego.project_bricks_container
    ADD CONSTRAINT project_bricks_container_user_fkey FOREIGN KEY ("user") REFERENCES lego."user"(id);


--
-- TOC entry 3007 (class 2606 OID 118577)
-- Name: project_bricks project_bricks_design_fkey; Type: FK CONSTRAINT; Schema: lego; Owner: jargoth
--

ALTER TABLE ONLY lego.project_bricks
    ADD CONSTRAINT project_bricks_design_fkey FOREIGN KEY (design) REFERENCES lego.design(id);


--
-- TOC entry 3006 (class 2606 OID 118572)
-- Name: project_bricks project_bricks_project_fkey; Type: FK CONSTRAINT; Schema: lego; Owner: jargoth
--

ALTER TABLE ONLY lego.project_bricks
    ADD CONSTRAINT project_bricks_project_fkey FOREIGN KEY (project) REFERENCES lego.project("order");


--
-- TOC entry 3031 (class 2606 OID 232785)
-- Name: project_model project_model_model_fkey; Type: FK CONSTRAINT; Schema: lego; Owner: jargoth
--

ALTER TABLE ONLY lego.project_model
    ADD CONSTRAINT project_model_model_fkey FOREIGN KEY (model) REFERENCES lego.model(id);


--
-- TOC entry 3030 (class 2606 OID 232780)
-- Name: project_model project_model_project_fkey; Type: FK CONSTRAINT; Schema: lego; Owner: jargoth
--

ALTER TABLE ONLY lego.project_model
    ADD CONSTRAINT project_model_project_fkey FOREIGN KEY (project) REFERENCES lego.project("order");


--
-- TOC entry 3005 (class 2606 OID 118587)
-- Name: project project_user_fkey; Type: FK CONSTRAINT; Schema: lego; Owner: jargoth
--

ALTER TABLE ONLY lego.project
    ADD CONSTRAINT project_user_fkey FOREIGN KEY ("user") REFERENCES lego."user"(id);


--
-- TOC entry 3025 (class 2606 OID 231191)
-- Name: regenerate_modelpic regenerate_modelpic_model_fkey; Type: FK CONSTRAINT; Schema: lego; Owner: jargoth
--

ALTER TABLE ONLY lego.regenerate_modelpic
    ADD CONSTRAINT regenerate_modelpic_model_fkey FOREIGN KEY (model) REFERENCES lego.model(id);


--
-- TOC entry 3001 (class 2606 OID 17387)
-- Name: similar_colors similiar_colors_color1_fkey; Type: FK CONSTRAINT; Schema: lego; Owner: jargoth
--

ALTER TABLE ONLY lego.similar_colors
    ADD CONSTRAINT similiar_colors_color1_fkey FOREIGN KEY (color1) REFERENCES lego.color(id);


--
-- TOC entry 3002 (class 2606 OID 17392)
-- Name: similar_colors similiar_colors_color2_fkey; Type: FK CONSTRAINT; Schema: lego; Owner: jargoth
--

ALTER TABLE ONLY lego.similar_colors
    ADD CONSTRAINT similiar_colors_color2_fkey FOREIGN KEY (color2) REFERENCES lego.color(id);


--
-- TOC entry 3003 (class 2606 OID 37404)
-- Name: user_parameters user_parameters_user_fkey; Type: FK CONSTRAINT; Schema: lego; Owner: jargoth
--

ALTER TABLE ONLY lego.user_parameters
    ADD CONSTRAINT user_parameters_user_fkey FOREIGN KEY ("user") REFERENCES lego."user"(id);


--
-- TOC entry 3161 (class 0 OID 0)
-- Dependencies: 9
-- Name: SCHEMA lego; Type: ACL; Schema: -; Owner: jargoth
--

GRANT ALL ON SCHEMA lego TO web;


--
-- TOC entry 3162 (class 0 OID 0)
-- Dependencies: 211
-- Name: TABLE bricklink; Type: ACL; Schema: lego; Owner: jargoth
--

GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE lego.bricklink TO web;


--
-- TOC entry 3163 (class 0 OID 0)
-- Dependencies: 207
-- Name: TABLE bricklink_color; Type: ACL; Schema: lego; Owner: jargoth
--

GRANT SELECT ON TABLE lego.bricklink_color TO web;


--
-- TOC entry 3164 (class 0 OID 0)
-- Dependencies: 205
-- Name: TABLE bricklink_design; Type: ACL; Schema: lego; Owner: jargoth
--

GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE lego.bricklink_design TO web;


--
-- TOC entry 3165 (class 0 OID 0)
-- Dependencies: 215
-- Name: TABLE bricklink_price; Type: ACL; Schema: lego; Owner: jargoth
--

GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE lego.bricklink_price TO web;


--
-- TOC entry 3166 (class 0 OID 0)
-- Dependencies: 220
-- Name: TABLE bricklink_xml; Type: ACL; Schema: lego; Owner: jargoth
--

GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE lego.bricklink_xml TO web;


--
-- TOC entry 3167 (class 0 OID 0)
-- Dependencies: 213
-- Name: TABLE bricklink_xml_generate; Type: ACL; Schema: lego; Owner: jargoth
--

GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE lego.bricklink_xml_generate TO web;


--
-- TOC entry 3168 (class 0 OID 0)
-- Dependencies: 227
-- Name: TABLE bricklink_xml_update; Type: ACL; Schema: lego; Owner: jargoth
--

GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE lego.bricklink_xml_update TO web;


--
-- TOC entry 3169 (class 0 OID 0)
-- Dependencies: 214
-- Name: TABLE bricklink_xml_update_generate; Type: ACL; Schema: lego; Owner: jargoth
--

GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE lego.bricklink_xml_update_generate TO web;


--
-- TOC entry 3170 (class 0 OID 0)
-- Dependencies: 203
-- Name: TABLE color; Type: ACL; Schema: lego; Owner: jargoth
--

GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE lego.color TO web;


--
-- TOC entry 3171 (class 0 OID 0)
-- Dependencies: 242
-- Name: TABLE color_ldraw; Type: ACL; Schema: lego; Owner: jargoth
--

GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE lego.color_ldraw TO web;


--
-- TOC entry 3172 (class 0 OID 0)
-- Dependencies: 209
-- Name: TABLE container; Type: ACL; Schema: lego; Owner: jargoth
--

GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE lego.container TO web;


--
-- TOC entry 3173 (class 0 OID 0)
-- Dependencies: 200
-- Name: SEQUENCE design_id_seq; Type: ACL; Schema: lego; Owner: jargoth
--

GRANT ALL ON SEQUENCE lego.design_id_seq TO web;


--
-- TOC entry 3174 (class 0 OID 0)
-- Dependencies: 202
-- Name: TABLE design; Type: ACL; Schema: lego; Owner: jargoth
--

GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE lego.design TO web;


--
-- TOC entry 3175 (class 0 OID 0)
-- Dependencies: 208
-- Name: TABLE design_color; Type: ACL; Schema: lego; Owner: jargoth
--

GRANT SELECT,INSERT,UPDATE ON TABLE lego.design_color TO web;


--
-- TOC entry 3176 (class 0 OID 0)
-- Dependencies: 204
-- Name: TABLE design_color_user; Type: ACL; Schema: lego; Owner: jargoth
--

GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE lego.design_color_user TO web;


--
-- TOC entry 3177 (class 0 OID 0)
-- Dependencies: 210
-- Name: TABLE design_color_user_container; Type: ACL; Schema: lego; Owner: jargoth
--

GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE lego.design_color_user_container TO web;


--
-- TOC entry 3178 (class 0 OID 0)
-- Dependencies: 234
-- Name: TABLE filename_design_color; Type: ACL; Schema: lego; Owner: jargoth
--

GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE lego.filename_design_color TO web;


--
-- TOC entry 3179 (class 0 OID 0)
-- Dependencies: 216
-- Name: TABLE inventory; Type: ACL; Schema: lego; Owner: jargoth
--

GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE lego.inventory TO web;


--
-- TOC entry 3180 (class 0 OID 0)
-- Dependencies: 244
-- Name: TABLE inventory_new; Type: ACL; Schema: lego; Owner: jargoth
--

GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE lego.inventory_new TO web;


--
-- TOC entry 3181 (class 0 OID 0)
-- Dependencies: 217
-- Name: TABLE inventory_user; Type: ACL; Schema: lego; Owner: jargoth
--

GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE lego.inventory_user TO web;


--
-- TOC entry 3182 (class 0 OID 0)
-- Dependencies: 228
-- Name: SEQUENCE model_id_seq; Type: ACL; Schema: lego; Owner: jargoth
--

GRANT ALL ON SEQUENCE lego.model_id_seq TO web;


--
-- TOC entry 3183 (class 0 OID 0)
-- Dependencies: 229
-- Name: TABLE model; Type: ACL; Schema: lego; Owner: jargoth
--

GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE lego.model TO web;


--
-- TOC entry 3184 (class 0 OID 0)
-- Dependencies: 233
-- Name: TABLE model_bricks; Type: ACL; Schema: lego; Owner: jargoth
--

GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE lego.model_bricks TO web;


--
-- TOC entry 3185 (class 0 OID 0)
-- Dependencies: 236
-- Name: TABLE model_bricks_admin; Type: ACL; Schema: lego; Owner: jargoth
--

GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE lego.model_bricks_admin TO web;


--
-- TOC entry 3186 (class 0 OID 0)
-- Dependencies: 230
-- Name: TABLE model_header; Type: ACL; Schema: lego; Owner: jargoth
--

GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE lego.model_header TO web;


--
-- TOC entry 3187 (class 0 OID 0)
-- Dependencies: 231
-- Name: TABLE model_line_error; Type: ACL; Schema: lego; Owner: jargoth
--

GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE lego.model_line_error TO web;


--
-- TOC entry 3188 (class 0 OID 0)
-- Dependencies: 241
-- Name: TABLE model_modelcat; Type: ACL; Schema: lego; Owner: jargoth
--

GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE lego.model_modelcat TO web;


--
-- TOC entry 3189 (class 0 OID 0)
-- Dependencies: 232
-- Name: TABLE model_primitives; Type: ACL; Schema: lego; Owner: jargoth
--

GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE lego.model_primitives TO web;


--
-- TOC entry 3190 (class 0 OID 0)
-- Dependencies: 238
-- Name: TABLE model_step; Type: ACL; Schema: lego; Owner: jargoth
--

GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE lego.model_step TO web;


--
-- TOC entry 3191 (class 0 OID 0)
-- Dependencies: 235
-- Name: TABLE model_submodel; Type: ACL; Schema: lego; Owner: jargoth
--

GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE lego.model_submodel TO web;


--
-- TOC entry 3192 (class 0 OID 0)
-- Dependencies: 240
-- Name: TABLE modelcat; Type: ACL; Schema: lego; Owner: jargoth
--

GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE lego.modelcat TO web;


--
-- TOC entry 3193 (class 0 OID 0)
-- Dependencies: 222
-- Name: SEQUENCE project_order_seq; Type: ACL; Schema: lego; Owner: jargoth
--

GRANT ALL ON SEQUENCE lego.project_order_seq TO web;


--
-- TOC entry 3194 (class 0 OID 0)
-- Dependencies: 223
-- Name: TABLE project; Type: ACL; Schema: lego; Owner: jargoth
--

GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE lego.project TO web;


--
-- TOC entry 3195 (class 0 OID 0)
-- Dependencies: 224
-- Name: TABLE project_bricks; Type: ACL; Schema: lego; Owner: jargoth
--

GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE lego.project_bricks TO web;


--
-- TOC entry 3196 (class 0 OID 0)
-- Dependencies: 225
-- Name: TABLE project_bricks_container; Type: ACL; Schema: lego; Owner: jargoth
--

GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE lego.project_bricks_container TO web;


--
-- TOC entry 3197 (class 0 OID 0)
-- Dependencies: 243
-- Name: TABLE project_model; Type: ACL; Schema: lego; Owner: jargoth
--

GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE lego.project_model TO web;


--
-- TOC entry 3198 (class 0 OID 0)
-- Dependencies: 237
-- Name: TABLE regenerate_modelpic; Type: ACL; Schema: lego; Owner: jargoth
--

GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE lego.regenerate_modelpic TO web;


--
-- TOC entry 3199 (class 0 OID 0)
-- Dependencies: 206
-- Name: TABLE replacing; Type: ACL; Schema: lego; Owner: jargoth
--

GRANT SELECT ON TABLE lego.replacing TO web;


--
-- TOC entry 3200 (class 0 OID 0)
-- Dependencies: 218
-- Name: TABLE similar_colors; Type: ACL; Schema: lego; Owner: jargoth
--

GRANT SELECT ON TABLE lego.similar_colors TO web;


--
-- TOC entry 3201 (class 0 OID 0)
-- Dependencies: 199
-- Name: TABLE "user"; Type: ACL; Schema: lego; Owner: jargoth
--

GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE lego."user" TO web;


--
-- TOC entry 3202 (class 0 OID 0)
-- Dependencies: 219
-- Name: TABLE user_parameters; Type: ACL; Schema: lego; Owner: jargoth
--

GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE lego.user_parameters TO web;


-- Completed on 2023-03-27 18:58:20

--
-- PostgreSQL database dump complete
--

