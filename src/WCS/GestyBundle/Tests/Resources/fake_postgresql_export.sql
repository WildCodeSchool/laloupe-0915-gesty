--
-- PostgreSQL database dump
--

SET statement_timeout = 0;
SET lock_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

--
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


--
-- Name: hstore; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS hstore WITH SCHEMA public;


--
-- Name: EXTENSION hstore; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION hstore IS 'data type for storing sets of (key, value) pairs';


SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: multipass_absences; Type: TABLE; Schema: public; Owner: gesty_lalou_7818; Tablespace: 
--

CREATE TABLE multipass_absences (
    id integer NOT NULL,
    datedebut timestamp without time zone,
    datefin timestamp without time zone,
    justificatif boolean,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    message character varying,
    attestation_file_name character varying,
    attestation_content_type character varying,
    child_id integer
);


ALTER TABLE multipass_absences OWNER TO gesty_lalou_7818;

--
-- Name: multipass_absences_id_seq; Type: SEQUENCE; Schema: public; Owner: gesty_lalou_7818
--

CREATE SEQUENCE multipass_absences_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE multipass_absences_id_seq OWNER TO gesty_lalou_7818;

--
-- Name: multipass_absences_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: gesty_lalou_7818
--

ALTER SEQUENCE multipass_absences_id_seq OWNED BY multipass_absences.id;


--
-- Name: multipass_canteen_plannings; Type: TABLE; Schema: public; Owner: gesty_lalou_7818; Tablespace: 
--

CREATE TABLE multipass_canteen_plannings (
    id integer NOT NULL,
    child_id integer,
    school_year_id integer,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL
);


ALTER TABLE multipass_canteen_plannings OWNER TO gesty_lalou_7818;

--
-- Name: multipass_canteen_plannings_id_seq; Type: SEQUENCE; Schema: public; Owner: gesty_lalou_7818
--

CREATE SEQUENCE multipass_canteen_plannings_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE multipass_canteen_plannings_id_seq OWNER TO gesty_lalou_7818;

--
-- Name: multipass_canteen_plannings_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: gesty_lalou_7818
--

ALTER SEQUENCE multipass_canteen_plannings_id_seq OWNED BY multipass_canteen_plannings.id;


--
-- Name: multipass_children; Type: TABLE; Schema: public; Owner: gesty_lalou_7818; Tablespace: 
--

CREATE TABLE multipass_children (
    id integer NOT NULL,
    firstname character varying,
    lastname character varying,
    birthdate date,
    user_id integer,
    classroom_id integer,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    monday boolean,
    tuesday boolean,
    thursday boolean,
    friday boolean,
    allergy boolean,
    allergy_details character varying,
    no_pork_regime boolean,
    rules_signed boolean,
    allow_emergency boolean,
    content_correct boolean
);


ALTER TABLE multipass_children OWNER TO gesty_lalou_7818;

--
-- Name: multipass_children_id_seq; Type: SEQUENCE; Schema: public; Owner: gesty_lalou_7818
--

CREATE SEQUENCE multipass_children_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE multipass_children_id_seq OWNER TO gesty_lalou_7818;

--
-- Name: multipass_children_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: gesty_lalou_7818
--

ALTER SEQUENCE multipass_children_id_seq OWNED BY multipass_children.id;


--
-- Name: multipass_classrooms; Type: TABLE; Schema: public; Owner: gesty_lalou_7818; Tablespace: 
--

CREATE TABLE multipass_classrooms (
    id integer NOT NULL,
    name character varying,
    ps boolean,
    ms boolean,
    gs boolean,
    cp boolean,
    ce1 boolean,
    ce2 boolean,
    cm1 boolean,
    cm2 boolean,
    school_id integer,
    tps boolean,
    clis boolean
);


ALTER TABLE multipass_classrooms OWNER TO gesty_lalou_7818;

--
-- Name: multipass_classrooms_id_seq; Type: SEQUENCE; Schema: public; Owner: gesty_lalou_7818
--

CREATE SEQUENCE multipass_classrooms_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE multipass_classrooms_id_seq OWNER TO gesty_lalou_7818;

--
-- Name: multipass_classrooms_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: gesty_lalou_7818
--

ALTER SEQUENCE multipass_classrooms_id_seq OWNED BY multipass_classrooms.id;


--
-- Name: multipass_holidays; Type: TABLE; Schema: public; Owner: gesty_lalou_7818; Tablespace: 
--

CREATE TABLE multipass_holidays (
    id integer NOT NULL,
    start date,
    finish date,
    school_year_id integer,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL
);


ALTER TABLE multipass_holidays OWNER TO gesty_lalou_7818;

--
-- Name: multipass_holidays_id_seq; Type: SEQUENCE; Schema: public; Owner: gesty_lalou_7818
--

CREATE SEQUENCE multipass_holidays_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE multipass_holidays_id_seq OWNER TO gesty_lalou_7818;

--
-- Name: multipass_holidays_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: gesty_lalou_7818
--

ALTER SEQUENCE multipass_holidays_id_seq OWNED BY multipass_holidays.id;


--
-- Name: multipass_homes; Type: TABLE; Schema: public; Owner: gesty_lalou_7818; Tablespace: 
--

CREATE TABLE multipass_homes (
    id integer NOT NULL,
    firstname character varying,
    lastname character varying,
    phone character varying,
    phone_2 character varying,
    address_street character varying,
    address_postcode character varying,
    address_city character varying,
    payment_method character varying,
    gender character varying,
    caf character varying,
    user_id integer,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    rib character varying,
    approved boolean DEFAULT false,
    address_evidence character varying,
    address_evidence_2 character varying,
    caf_evidence character varying,
    salary_evidence character varying,
    salary_evidence_2 character varying,
    salary_evidence_3 character varying,
    salary_evidence_4 character varying,
    salary_evidence_5 character varying,
    salary_evidence_6 character varying,
    active_transfer boolean
);


ALTER TABLE multipass_homes OWNER TO gesty_lalou_7818;

--
-- Name: multipass_homes_id_seq; Type: SEQUENCE; Schema: public; Owner: gesty_lalou_7818
--

CREATE SEQUENCE multipass_homes_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE multipass_homes_id_seq OWNER TO gesty_lalou_7818;

--
-- Name: multipass_homes_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: gesty_lalou_7818
--

ALTER SEQUENCE multipass_homes_id_seq OWNED BY multipass_homes.id;


--
-- Name: multipass_school_years; Type: TABLE; Schema: public; Owner: gesty_lalou_7818; Tablespace: 
--

CREATE TABLE multipass_school_years (
    id integer NOT NULL,
    start date,
    finish date,
    active boolean,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL
);


ALTER TABLE multipass_school_years OWNER TO gesty_lalou_7818;

--
-- Name: multipass_school_years_id_seq; Type: SEQUENCE; Schema: public; Owner: gesty_lalou_7818
--

CREATE SEQUENCE multipass_school_years_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE multipass_school_years_id_seq OWNER TO gesty_lalou_7818;

--
-- Name: multipass_school_years_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: gesty_lalou_7818
--

ALTER SEQUENCE multipass_school_years_id_seq OWNED BY multipass_school_years.id;


--
-- Name: multipass_schools; Type: TABLE; Schema: public; Owner: gesty_lalou_7818; Tablespace: 
--

CREATE TABLE multipass_schools (
    id integer NOT NULL,
    name character varying,
    municipality character varying
);


ALTER TABLE multipass_schools OWNER TO gesty_lalou_7818;

--
-- Name: multipass_schools_id_seq; Type: SEQUENCE; Schema: public; Owner: gesty_lalou_7818
--

CREATE SEQUENCE multipass_schools_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE multipass_schools_id_seq OWNER TO gesty_lalou_7818;

--
-- Name: multipass_schools_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: gesty_lalou_7818
--

ALTER SEQUENCE multipass_schools_id_seq OWNED BY multipass_schools.id;


--
-- Name: multipass_users; Type: TABLE; Schema: public; Owner: gesty_lalou_7818; Tablespace: 
--

CREATE TABLE multipass_users (
    id integer NOT NULL,
    email character varying DEFAULT ''::character varying NOT NULL,
    encrypted_password character varying DEFAULT ''::character varying NOT NULL,
    reset_password_token character varying,
    reset_password_sent_at timestamp without time zone,
    remember_created_at timestamp without time zone,
    sign_in_count integer DEFAULT 0 NOT NULL,
    current_sign_in_at timestamp without time zone,
    last_sign_in_at timestamp without time zone,
    current_sign_in_ip character varying,
    last_sign_in_ip character varying,
    created_at timestamp without time zone NOT NULL,
    updated_at timestamp without time zone NOT NULL,
    roles_mask integer,
    confirmation_token character varying,
    confirmed_at timestamp without time zone,
    confirmation_sent_at timestamp without time zone,
    unconfirmed_email character varying
);


ALTER TABLE multipass_users OWNER TO gesty_lalou_7818;

--
-- Name: multipass_users_id_seq; Type: SEQUENCE; Schema: public; Owner: gesty_lalou_7818
--

CREATE SEQUENCE multipass_users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE multipass_users_id_seq OWNER TO gesty_lalou_7818;

--
-- Name: multipass_users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: gesty_lalou_7818
--

ALTER SEQUENCE multipass_users_id_seq OWNED BY multipass_users.id;


--
-- Name: schema_migrations; Type: TABLE; Schema: public; Owner: gesty_lalou_7818; Tablespace: 
--

CREATE TABLE schema_migrations (
    version character varying NOT NULL
);


ALTER TABLE schema_migrations OWNER TO gesty_lalou_7818;

--
-- Name: versions; Type: TABLE; Schema: public; Owner: gesty_lalou_7818; Tablespace: 
--

CREATE TABLE versions (
    id integer NOT NULL,
    item_type character varying NOT NULL,
    item_id integer NOT NULL,
    event character varying NOT NULL,
    whodunnit character varying,
    object text,
    created_at timestamp without time zone
);


ALTER TABLE versions OWNER TO gesty_lalou_7818;

--
-- Name: versions_id_seq; Type: SEQUENCE; Schema: public; Owner: gesty_lalou_7818
--

CREATE SEQUENCE versions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE versions_id_seq OWNER TO gesty_lalou_7818;

--
-- Name: versions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: gesty_lalou_7818
--

ALTER SEQUENCE versions_id_seq OWNED BY versions.id;


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: gesty_lalou_7818
--

ALTER TABLE ONLY multipass_absences ALTER COLUMN id SET DEFAULT nextval('multipass_absences_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: gesty_lalou_7818
--

ALTER TABLE ONLY multipass_canteen_plannings ALTER COLUMN id SET DEFAULT nextval('multipass_canteen_plannings_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: gesty_lalou_7818
--

ALTER TABLE ONLY multipass_children ALTER COLUMN id SET DEFAULT nextval('multipass_children_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: gesty_lalou_7818
--

ALTER TABLE ONLY multipass_classrooms ALTER COLUMN id SET DEFAULT nextval('multipass_classrooms_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: gesty_lalou_7818
--

ALTER TABLE ONLY multipass_holidays ALTER COLUMN id SET DEFAULT nextval('multipass_holidays_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: gesty_lalou_7818
--

ALTER TABLE ONLY multipass_homes ALTER COLUMN id SET DEFAULT nextval('multipass_homes_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: gesty_lalou_7818
--

ALTER TABLE ONLY multipass_school_years ALTER COLUMN id SET DEFAULT nextval('multipass_school_years_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: gesty_lalou_7818
--

ALTER TABLE ONLY multipass_schools ALTER COLUMN id SET DEFAULT nextval('multipass_schools_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: gesty_lalou_7818
--

ALTER TABLE ONLY multipass_users ALTER COLUMN id SET DEFAULT nextval('multipass_users_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: gesty_lalou_7818
--

ALTER TABLE ONLY versions ALTER COLUMN id SET DEFAULT nextval('versions_id_seq'::regclass);


--
-- Data for Name: multipass_absences; Type: TABLE DATA; Schema: public; Owner: gesty_lalou_7818
--

COPY multipass_absences (id, datedebut, datefin, justificatif, created_at, updated_at, message, attestation_file_name, attestation_content_type, child_id) FROM stdin;
\.


--
-- Name: multipass_absences_id_seq; Type: SEQUENCE SET; Schema: public; Owner: gesty_lalou_7818
--

SELECT pg_catalog.setval('multipass_absences_id_seq', 1, false);


--
-- Data for Name: multipass_canteen_plannings; Type: TABLE DATA; Schema: public; Owner: gesty_lalou_7818
--

COPY multipass_canteen_plannings (id, child_id, school_year_id, created_at, updated_at) FROM stdin;
\.


--
-- Name: multipass_canteen_plannings_id_seq; Type: SEQUENCE SET; Schema: public; Owner: gesty_lalou_7818
--

SELECT pg_catalog.setval('multipass_canteen_plannings_id_seq', 1, false);


--
-- Data for Name: multipass_children; Type: TABLE DATA; Schema: public; Owner: gesty_lalou_7818
--

COPY multipass_children (id, firstname, lastname, birthdate, user_id, classroom_id, created_at, updated_at, monday, tuesday, thursday, friday, allergy, allergy_details, no_pork_regime, rules_signed, allow_emergency, content_correct) FROM stdin;
79	Valentine	Dupont	2012-02-12	1	1	2015-08-13 14:17:11.502156	2015-09-15 15:40:48.359308	t	t	t	t	f		f	t	t	t
47	Emma	Dupont 	2010-12-24	2	2	2015-08-05 14:06:51.889983	2015-09-16 07:34:20.258865	t	t	t	t	f		f	t	t	t
81	Samuel	Pichon	2010-05-27	3	2	2015-08-13 15:04:37.623823	2015-09-16 07:34:39.966828	t	t	t	t	f		f	t	t	t
221	Lilou	Pichon	2011-11-11	3	4	2015-09-23 07:42:02.144217	2015-09-23 07:42:02.144217	t	t	t	t	f		f	t	t	t
\.


--
-- Name: multipass_children_id_seq; Type: SEQUENCE SET; Schema: public; Owner: gesty_lalou_7818
--

SELECT pg_catalog.setval('multipass_children_id_seq', 263, true);


--
-- Data for Name: multipass_classrooms; Type: TABLE DATA; Schema: public; Owner: gesty_lalou_7818
--

COPY multipass_classrooms (id, name, ps, ms, gs, cp, ce1, ce2, cm1, cm2, school_id, tps, clis) FROM stdin;
1	CP/CE1 DE MME MACHIN Laurence	f	f	f	t	t	f	f	f	3	f	f
2	PS/MS DE MME MACHINE Marie-Agnès	t	t	f	f	f	f	f	f	3	f	f
3	MS/GS DE MME TRUC Anne-Lise	f	t	t	f	f	f	f	f	3	f	f
4	CE1/CE2 DE MME CHOSE Hélène	f	f	f	f	t	t	f	f	3	f	f
\.


--
-- Name: multipass_classrooms_id_seq; Type: SEQUENCE SET; Schema: public; Owner: gesty_lalou_7818
--

SELECT pg_catalog.setval('multipass_classrooms_id_seq', 21, true);


--
-- Data for Name: multipass_holidays; Type: TABLE DATA; Schema: public; Owner: gesty_lalou_7818
--

COPY multipass_holidays (id, start, finish, school_year_id, created_at, updated_at) FROM stdin;
\.


--
-- Name: multipass_holidays_id_seq; Type: SEQUENCE SET; Schema: public; Owner: gesty_lalou_7818
--

SELECT pg_catalog.setval('multipass_holidays_id_seq', 1, false);


--
-- Data for Name: multipass_homes; Type: TABLE DATA; Schema: public; Owner: gesty_lalou_7818
--

COPY multipass_homes (id, firstname, lastname, phone, phone_2, address_street, address_postcode, address_city, payment_method, gender, caf, user_id, created_at, updated_at, rib, approved, address_evidence, address_evidence_2, caf_evidence, salary_evidence, salary_evidence_2, salary_evidence_3, salary_evidence_4, salary_evidence_5, salary_evidence_6, active_transfer) FROM stdin;
12	Sébastien	DUPONT	0607080910		44 rue Machin	28240	La Loupe	check	Mr	1118415	1	2015-07-31 13:54:47.233352	2015-07-31 13:57:15.537232		t	domicile-dupont.pdf	\N	caf-dupont.pdf	salaire-dupont-1.pdf	salaire-dupont-2.pdf	salaire-dupont-3.pdf	\N	\N	\N	f
13	candice	PICHON	0601020304	0202020202	6 allée des bidules	28240	la loupe	check	Mme	1109817	2	2015-08-03 08:20:17.964383	2015-08-24 12:03:02.58459		t	domicile-pichon.pdf	\N	caf-pichon.pdf	salaire-pichon-1.pdf	salaire-pichon-2.pdf	salaire-pichon-3.pdf	\N	\N	\N	f
3	CATHERINE	DURAND	0203040506	0606060606	22 rue Tralala	28240	LA LOUPE	check	Mme		3	2015-07-29 13:40:36.549154	2015-07-30 06:55:56.339793		t	domicile-durand.pdf	\N	\N	salaire-durand-1.pdf	\N	\N	\N	\N	\N	\N
2	Amandine	LENOIR	0205060708	0608090708	Salle des sports	28240	La Loupe	transfer	Mme	0604987	4	2015-07-29 11:38:49.977929	2015-09-03 12:55:16.468762	Fr7614505000010414539159273	t	domicile-lenoir.pdf	\N	\N	salaire-lenoir-1.pdf	salaire-lenoir-2.pdf	salaire-lenoir-3.pdf	salaire-lenoir-4.pdf	salaire-lenoir-5.pdf	salaire-lenoir-6.pdf	t
\.


--
-- Name: multipass_homes_id_seq; Type: SEQUENCE SET; Schema: public; Owner: gesty_lalou_7818
--

SELECT pg_catalog.setval('multipass_homes_id_seq', 192, true);


--
-- Data for Name: multipass_school_years; Type: TABLE DATA; Schema: public; Owner: gesty_lalou_7818
--

COPY multipass_school_years (id, start, finish, active, created_at, updated_at) FROM stdin;
\.


--
-- Name: multipass_school_years_id_seq; Type: SEQUENCE SET; Schema: public; Owner: gesty_lalou_7818
--

SELECT pg_catalog.setval('multipass_school_years_id_seq', 1, false);


--
-- Data for Name: multipass_schools; Type: TABLE DATA; Schema: public; Owner: gesty_lalou_7818
--

COPY multipass_schools (id, name, municipality) FROM stdin;
1	Ecole "Roland Garros"	La Loupe
3	Ecole Notre Dame des Fleurs	La Loupe
2	Ecole "Les Ecureuils"	La Loupe
\.


--
-- Name: multipass_schools_id_seq; Type: SEQUENCE SET; Schema: public; Owner: gesty_lalou_7818
--

SELECT pg_catalog.setval('multipass_schools_id_seq', 3, true);


--
-- Data for Name: multipass_users; Type: TABLE DATA; Schema: public; Owner: gesty_lalou_7818
--

COPY multipass_users (id, email, encrypted_password, reset_password_token, reset_password_sent_at, remember_created_at, sign_in_count, current_sign_in_at, last_sign_in_at, current_sign_in_ip, last_sign_in_ip, created_at, updated_at, roles_mask, confirmation_token, confirmed_at, confirmation_sent_at, unconfirmed_email) FROM stdin;
1	toto@email.com	$2a$10$9nMM59wbh3GjcehmlAHDXesBemKEiKTXulJR2UwCYL9.1FNTLc5uu	\N	\N	2015-10-09 05:00:20.356845	10	2015-10-09 10:58:13.288594	2015-10-09 06:08:51.458812	192.168.0.1	192.168.0.1	2015-07-29 19:47:36.018668	2015-10-09 10:58:13.290466	\N	02c85d22460753da502ee8e11d28b0809c03f031288b9f33aa5d90305797904f	2015-07-29 20:00:03.677389	2015-07-29 19:47:36.020826	\N
2	tata@email.com	$2a$10$BHtwLWDuPP8Ls8PAz7sIEO7z0xyCgJts6TBk.vX/fIrXBMR63Uuhu	\N	\N	\N	6	2015-08-26 15:38:25.574972	2015-08-26 09:07:05.459329	192.168.0.1	192.168.0.1	2015-07-29 14:08:09.841024	2015-08-26 15:38:25.577171	\N	9269f2a3edbe231a49ac9b00ac3e6fda968a040f4d23a2b257e670e5322bdb3b	2015-07-30 17:02:50.833823	2015-07-29 14:08:09.842614	\N
3	titi@email.com	$2a$10$lcs.klEkuGwz0FKmNCaM1O9J7RO2UCJG195/0l9313IOhzcT6o2Lu	\N	\N	2015-10-12 16:12:31.188938	5	2015-10-12 16:12:31.198609	2015-10-12 16:11:42.13663	192.168.0.1	192.168.0.1	2015-07-29 12:19:30.09679	2015-10-12 16:12:31.200668	\N	50e3b9a359e9b8c16d2b463d7ba5591310f4da158b747b236d4059b5097aa9af	2015-07-29 13:31:38.309722	2015-07-29 12:19:30.098317	\N
4	tutu@email.com	$2a$10$xPpeMS6ICc/EyKvCQuZxVOdE0ih2xFrJFqdSM2vcAXCkbQWbJJLsO	\N	\N	\N	6	2015-09-30 17:38:12.821707	2015-08-29 19:40:29.471513	192.168.0.1	192.168.0.1	2015-07-31 10:27:14.310271	2015-09-30 17:38:12.82346	\N	22a7bcfa4129900db21e354c6a8c73581bdade2097949c34a0262d9c14ca169b	2015-07-31 11:08:34.140014	2015-07-31 10:27:14.312268	\N
\.


--
-- Name: multipass_users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: gesty_lalou_7818
--

SELECT pg_catalog.setval('multipass_users_id_seq', 261, true);


--
-- Data for Name: schema_migrations; Type: TABLE DATA; Schema: public; Owner: gesty_lalou_7818
--

COPY schema_migrations (version) FROM stdin;
20150715140835
20150728083449
20150728035102
\.


\.


--
-- Name: versions_id_seq; Type: SEQUENCE SET; Schema: public; Owner: gesty_lalou_7818
--

SELECT pg_catalog.setval('versions_id_seq', 65, true);


--
-- Name: multipass_absences_pkey; Type: CONSTRAINT; Schema: public; Owner: gesty_lalou_7818; Tablespace: 
--

ALTER TABLE ONLY multipass_absences
    ADD CONSTRAINT multipass_absences_pkey PRIMARY KEY (id);


--
-- Name: multipass_canteen_plannings_pkey; Type: CONSTRAINT; Schema: public; Owner: gesty_lalou_7818; Tablespace: 
--

ALTER TABLE ONLY multipass_canteen_plannings
    ADD CONSTRAINT multipass_canteen_plannings_pkey PRIMARY KEY (id);


--
-- Name: multipass_children_pkey; Type: CONSTRAINT; Schema: public; Owner: gesty_lalou_7818; Tablespace: 
--

ALTER TABLE ONLY multipass_children
    ADD CONSTRAINT multipass_children_pkey PRIMARY KEY (id);


--
-- Name: multipass_classrooms_pkey; Type: CONSTRAINT; Schema: public; Owner: gesty_lalou_7818; Tablespace: 
--

ALTER TABLE ONLY multipass_classrooms
    ADD CONSTRAINT multipass_classrooms_pkey PRIMARY KEY (id);


--
-- Name: multipass_holidays_pkey; Type: CONSTRAINT; Schema: public; Owner: gesty_lalou_7818; Tablespace: 
--

ALTER TABLE ONLY multipass_holidays
    ADD CONSTRAINT multipass_holidays_pkey PRIMARY KEY (id);


--
-- Name: multipass_homes_pkey; Type: CONSTRAINT; Schema: public; Owner: gesty_lalou_7818; Tablespace: 
--

ALTER TABLE ONLY multipass_homes
    ADD CONSTRAINT multipass_homes_pkey PRIMARY KEY (id);


--
-- Name: multipass_school_years_pkey; Type: CONSTRAINT; Schema: public; Owner: gesty_lalou_7818; Tablespace: 
--

ALTER TABLE ONLY multipass_school_years
    ADD CONSTRAINT multipass_school_years_pkey PRIMARY KEY (id);


--
-- Name: multipass_schools_pkey; Type: CONSTRAINT; Schema: public; Owner: gesty_lalou_7818; Tablespace: 
--

ALTER TABLE ONLY multipass_schools
    ADD CONSTRAINT multipass_schools_pkey PRIMARY KEY (id);


--
-- Name: multipass_users_pkey; Type: CONSTRAINT; Schema: public; Owner: gesty_lalou_7818; Tablespace: 
--

ALTER TABLE ONLY multipass_users
    ADD CONSTRAINT multipass_users_pkey PRIMARY KEY (id);


--
-- Name: versions_pkey; Type: CONSTRAINT; Schema: public; Owner: gesty_lalou_7818; Tablespace: 
--

ALTER TABLE ONLY versions
    ADD CONSTRAINT versions_pkey PRIMARY KEY (id);


--
-- Name: index_multipass_canteen_plannings_on_child_id; Type: INDEX; Schema: public; Owner: gesty_lalou_7818; Tablespace: 
--

CREATE INDEX index_multipass_canteen_plannings_on_child_id ON multipass_canteen_plannings USING btree (child_id);


--
-- Name: index_multipass_canteen_plannings_on_school_year_id; Type: INDEX; Schema: public; Owner: gesty_lalou_7818; Tablespace: 
--

CREATE INDEX index_multipass_canteen_plannings_on_school_year_id ON multipass_canteen_plannings USING btree (school_year_id);


--
-- Name: index_multipass_children_on_classroom_id; Type: INDEX; Schema: public; Owner: gesty_lalou_7818; Tablespace: 
--

CREATE INDEX index_multipass_children_on_classroom_id ON multipass_children USING btree (classroom_id);


--
-- Name: index_multipass_children_on_user_id; Type: INDEX; Schema: public; Owner: gesty_lalou_7818; Tablespace: 
--

CREATE INDEX index_multipass_children_on_user_id ON multipass_children USING btree (user_id);


--
-- Name: index_multipass_classrooms_on_school_id; Type: INDEX; Schema: public; Owner: gesty_lalou_7818; Tablespace: 
--

CREATE INDEX index_multipass_classrooms_on_school_id ON multipass_classrooms USING btree (school_id);


--
-- Name: index_multipass_holidays_on_school_year_id; Type: INDEX; Schema: public; Owner: gesty_lalou_7818; Tablespace: 
--

CREATE INDEX index_multipass_holidays_on_school_year_id ON multipass_holidays USING btree (school_year_id);


--
-- Name: index_multipass_homes_on_user_id; Type: INDEX; Schema: public; Owner: gesty_lalou_7818; Tablespace: 
--

CREATE INDEX index_multipass_homes_on_user_id ON multipass_homes USING btree (user_id);


--
-- Name: index_multipass_users_on_email; Type: INDEX; Schema: public; Owner: gesty_lalou_7818; Tablespace: 
--

CREATE UNIQUE INDEX index_multipass_users_on_email ON multipass_users USING btree (email);


--
-- Name: index_multipass_users_on_reset_password_token; Type: INDEX; Schema: public; Owner: gesty_lalou_7818; Tablespace: 
--

CREATE UNIQUE INDEX index_multipass_users_on_reset_password_token ON multipass_users USING btree (reset_password_token);


--
-- Name: index_versions_on_item_type_and_item_id; Type: INDEX; Schema: public; Owner: gesty_lalou_7818; Tablespace: 
--

CREATE INDEX index_versions_on_item_type_and_item_id ON versions USING btree (item_type, item_id);


--
-- Name: unique_schema_migrations; Type: INDEX; Schema: public; Owner: gesty_lalou_7818; Tablespace: 
--

CREATE UNIQUE INDEX unique_schema_migrations ON schema_migrations USING btree (version);


--
-- Name: public; Type: ACL; Schema: -; Owner: postgresql
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgresql;
GRANT ALL ON SCHEMA public TO postgresql;
GRANT USAGE ON SCHEMA public TO PUBLIC;
GRANT CREATE ON SCHEMA public TO admin;
GRANT CREATE ON SCHEMA public TO gesty_lalou_7818;


--
-- PostgreSQL database dump complete
--

