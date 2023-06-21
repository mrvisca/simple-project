PGDMP     &                    {         	   myproject    15.2    15.2 h    �           0    0    ENCODING    ENCODING        SET client_encoding = 'UTF8';
                      false            �           0    0 
   STDSTRINGS 
   STDSTRINGS     (   SET standard_conforming_strings = 'on';
                      false            �           0    0 
   SEARCHPATH 
   SEARCHPATH     8   SELECT pg_catalog.set_config('search_path', '', false);
                      false            �           1262    22502 	   myproject    DATABASE     �   CREATE DATABASE myproject WITH TEMPLATE = template0 ENCODING = 'UTF8' LOCALE_PROVIDER = libc LOCALE = 'English_United States.1252';
    DROP DATABASE myproject;
                postgres    false            �           0    0 	   myproject    DATABASE PROPERTIES     H   ALTER ROLE postgres IN DATABASE myproject SET search_path TO 'testaja';
                     postgres    false                        2615    22503    testaja    SCHEMA        CREATE SCHEMA testaja;
    DROP SCHEMA testaja;
                postgres    false            �            1259    22631    bisnis    TABLE       CREATE TABLE testaja.bisnis (
    id bigint NOT NULL,
    owner_id integer NOT NULL,
    name character varying(255) NOT NULL,
    default_warehouse integer NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
    DROP TABLE testaja.bisnis;
       testaja         heap    postgres    false    6            �            1259    22630    bisnis_id_seq    SEQUENCE     w   CREATE SEQUENCE testaja.bisnis_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 %   DROP SEQUENCE testaja.bisnis_id_seq;
       testaja          postgres    false    227    6            �           0    0    bisnis_id_seq    SEQUENCE OWNED BY     A   ALTER SEQUENCE testaja.bisnis_id_seq OWNED BY testaja.bisnis.id;
          testaja          postgres    false    226            �            1259    22638    cabangs    TABLE     �   CREATE TABLE testaja.cabangs (
    id bigint NOT NULL,
    bisnis_id integer NOT NULL,
    name character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
    DROP TABLE testaja.cabangs;
       testaja         heap    postgres    false    6            �            1259    22637    cabangs_id_seq    SEQUENCE     x   CREATE SEQUENCE testaja.cabangs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 &   DROP SEQUENCE testaja.cabangs_id_seq;
       testaja          postgres    false    229    6            �           0    0    cabangs_id_seq    SEQUENCE OWNED BY     C   ALTER SEQUENCE testaja.cabangs_id_seq OWNED BY testaja.cabangs.id;
          testaja          postgres    false    228            �            1259    22659 
   categories    TABLE       CREATE TABLE testaja.categories (
    id bigint NOT NULL,
    bisnis_id integer NOT NULL,
    cabang_id integer NOT NULL,
    name character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
    DROP TABLE testaja.categories;
       testaja         heap    postgres    false    6            �            1259    22658    categories_id_seq    SEQUENCE     {   CREATE SEQUENCE testaja.categories_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 )   DROP SEQUENCE testaja.categories_id_seq;
       testaja          postgres    false    6    233            �           0    0    categories_id_seq    SEQUENCE OWNED BY     I   ALTER SEQUENCE testaja.categories_id_seq OWNED BY testaja.categories.id;
          testaja          postgres    false    232            �            1259    22697    clients    TABLE       CREATE TABLE testaja.clients (
    id bigint NOT NULL,
    bisnis_id integer NOT NULL,
    cabang_id integer NOT NULL,
    name character varying(255) NOT NULL,
    telp integer NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
    DROP TABLE testaja.clients;
       testaja         heap    postgres    false    6            �            1259    22696    clients_id_seq    SEQUENCE     x   CREATE SEQUENCE testaja.clients_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 &   DROP SEQUENCE testaja.clients_id_seq;
       testaja          postgres    false    241    6            �           0    0    clients_id_seq    SEQUENCE OWNED BY     C   ALTER SEQUENCE testaja.clients_id_seq OWNED BY testaja.clients.id;
          testaja          postgres    false    240            �            1259    22600    failed_jobs    TABLE     '  CREATE TABLE testaja.failed_jobs (
    id bigint NOT NULL,
    uuid character varying(255) NOT NULL,
    connection text NOT NULL,
    queue text NOT NULL,
    payload text NOT NULL,
    exception text NOT NULL,
    failed_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);
     DROP TABLE testaja.failed_jobs;
       testaja         heap    postgres    false    6            �            1259    22599    failed_jobs_id_seq    SEQUENCE     |   CREATE SEQUENCE testaja.failed_jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 *   DROP SEQUENCE testaja.failed_jobs_id_seq;
       testaja          postgres    false    6    221            �           0    0    failed_jobs_id_seq    SEQUENCE OWNED BY     K   ALTER SEQUENCE testaja.failed_jobs_id_seq OWNED BY testaja.failed_jobs.id;
          testaja          postgres    false    220            �            1259    22575 
   migrations    TABLE     �   CREATE TABLE testaja.migrations (
    id integer NOT NULL,
    migration character varying(255) NOT NULL,
    batch integer NOT NULL
);
    DROP TABLE testaja.migrations;
       testaja         heap    postgres    false    6            �            1259    22574    migrations_id_seq    SEQUENCE     �   CREATE SEQUENCE testaja.migrations_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 )   DROP SEQUENCE testaja.migrations_id_seq;
       testaja          postgres    false    6    216            �           0    0    migrations_id_seq    SEQUENCE OWNED BY     I   ALTER SEQUENCE testaja.migrations_id_seq OWNED BY testaja.migrations.id;
          testaja          postgres    false    215            �            1259    22592    password_reset_tokens    TABLE     �   CREATE TABLE testaja.password_reset_tokens (
    email character varying(255) NOT NULL,
    token character varying(255) NOT NULL,
    created_at timestamp(0) without time zone
);
 *   DROP TABLE testaja.password_reset_tokens;
       testaja         heap    postgres    false    6            �            1259    22666    pay_methods    TABLE     y  CREATE TABLE testaja.pay_methods (
    id bigint NOT NULL,
    bisnis_id integer NOT NULL,
    cabang_id integer NOT NULL,
    name character varying(255) NOT NULL,
    tipe character varying(255) DEFAULT 'lainnya'::character varying NOT NULL,
    norek integer DEFAULT 0 NOT NULL,
    is_active boolean DEFAULT true NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT pay_methods_tipe_check CHECK (((tipe)::text = ANY ((ARRAY['Transfer Bank'::character varying, 'E-Wallet'::character varying, 'QRIS'::character varying, 'lainnya'::character varying])::text[])))
);
     DROP TABLE testaja.pay_methods;
       testaja         heap    postgres    false    6            �            1259    22665    pay_methods_id_seq    SEQUENCE     |   CREATE SEQUENCE testaja.pay_methods_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 *   DROP SEQUENCE testaja.pay_methods_id_seq;
       testaja          postgres    false    6    235            �           0    0    pay_methods_id_seq    SEQUENCE OWNED BY     K   ALTER SEQUENCE testaja.pay_methods_id_seq OWNED BY testaja.pay_methods.id;
          testaja          postgres    false    234            �            1259    22612    personal_access_tokens    TABLE     �  CREATE TABLE testaja.personal_access_tokens (
    id bigint NOT NULL,
    tokenable_type character varying(255) NOT NULL,
    tokenable_id bigint NOT NULL,
    name character varying(255) NOT NULL,
    token character varying(64) NOT NULL,
    abilities text,
    last_used_at timestamp(0) without time zone,
    expires_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
 +   DROP TABLE testaja.personal_access_tokens;
       testaja         heap    postgres    false    6            �            1259    22611    personal_access_tokens_id_seq    SEQUENCE     �   CREATE SEQUENCE testaja.personal_access_tokens_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 5   DROP SEQUENCE testaja.personal_access_tokens_id_seq;
       testaja          postgres    false    6    223            �           0    0    personal_access_tokens_id_seq    SEQUENCE OWNED BY     a   ALTER SEQUENCE testaja.personal_access_tokens_id_seq OWNED BY testaja.personal_access_tokens.id;
          testaja          postgres    false    222            �            1259    22646    products    TABLE     �  CREATE TABLE testaja.products (
    id bigint NOT NULL,
    bisnis_id integer NOT NULL,
    cabang_id integer NOT NULL,
    category_id integer NOT NULL,
    name character varying(255) NOT NULL,
    modal integer NOT NULL,
    price integer NOT NULL,
    stock integer NOT NULL,
    satuan character varying(255) DEFAULT 'pcs'::character varying NOT NULL,
    is_active boolean DEFAULT true NOT NULL,
    is_jual boolean DEFAULT true NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT products_satuan_check CHECK (((satuan)::text = ANY ((ARRAY['pcs'::character varying, 'bundle'::character varying])::text[])))
);
    DROP TABLE testaja.products;
       testaja         heap    postgres    false    6            �            1259    22645    products_id_seq    SEQUENCE     y   CREATE SEQUENCE testaja.products_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 '   DROP SEQUENCE testaja.products_id_seq;
       testaja          postgres    false    6    231            �           0    0    products_id_seq    SEQUENCE OWNED BY     E   ALTER SEQUENCE testaja.products_id_seq OWNED BY testaja.products.id;
          testaja          postgres    false    230            �            1259    22624    roles    TABLE     �   CREATE TABLE testaja.roles (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
    DROP TABLE testaja.roles;
       testaja         heap    postgres    false    6            �            1259    22623    roles_id_seq    SEQUENCE     v   CREATE SEQUENCE testaja.roles_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 $   DROP SEQUENCE testaja.roles_id_seq;
       testaja          postgres    false    6    225            �           0    0    roles_id_seq    SEQUENCE OWNED BY     ?   ALTER SEQUENCE testaja.roles_id_seq OWNED BY testaja.roles.id;
          testaja          postgres    false    224            �            1259    22690    transaction_details    TABLE     \  CREATE TABLE testaja.transaction_details (
    id bigint NOT NULL,
    transaction_id integer NOT NULL,
    product_id integer NOT NULL,
    price integer NOT NULL,
    qty integer NOT NULL,
    qty_return integer NOT NULL,
    total integer NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
 (   DROP TABLE testaja.transaction_details;
       testaja         heap    postgres    false    6            �            1259    22689    transaction_details_id_seq    SEQUENCE     �   CREATE SEQUENCE testaja.transaction_details_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 2   DROP SEQUENCE testaja.transaction_details_id_seq;
       testaja          postgres    false    6    239            �           0    0    transaction_details_id_seq    SEQUENCE OWNED BY     [   ALTER SEQUENCE testaja.transaction_details_id_seq OWNED BY testaja.transaction_details.id;
          testaja          postgres    false    238            �            1259    22679    transactions    TABLE     �  CREATE TABLE testaja.transactions (
    id bigint NOT NULL,
    bisnis_id integer NOT NULL,
    cabang_id integer NOT NULL,
    user_id integer NOT NULL,
    client_id integer NOT NULL,
    reff character varying(255) NOT NULL,
    grandtotal integer NOT NULL,
    amount integer NOT NULL,
    payment_id integer NOT NULL,
    tipe character varying(255) DEFAULT 'transaksi'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT transactions_tipe_check CHECK (((tipe)::text = ANY ((ARRAY['transaksi'::character varying, 'open bill'::character varying])::text[])))
);
 !   DROP TABLE testaja.transactions;
       testaja         heap    postgres    false    6            �            1259    22678    transactions_id_seq    SEQUENCE     }   CREATE SEQUENCE testaja.transactions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 +   DROP SEQUENCE testaja.transactions_id_seq;
       testaja          postgres    false    237    6            �           0    0    transactions_id_seq    SEQUENCE OWNED BY     M   ALTER SEQUENCE testaja.transactions_id_seq OWNED BY testaja.transactions.id;
          testaja          postgres    false    236            �            1259    22582    users    TABLE     �  CREATE TABLE testaja.users (
    id bigint NOT NULL,
    role_id integer NOT NULL,
    bisnis_id integer NOT NULL,
    cabang_id integer NOT NULL,
    name character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    email_verified_at timestamp(0) without time zone,
    password character varying(255) NOT NULL,
    remember_token character varying(100),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
    DROP TABLE testaja.users;
       testaja         heap    postgres    false    6            �            1259    22581    users_id_seq    SEQUENCE     v   CREATE SEQUENCE testaja.users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 $   DROP SEQUENCE testaja.users_id_seq;
       testaja          postgres    false    6    218            �           0    0    users_id_seq    SEQUENCE OWNED BY     ?   ALTER SEQUENCE testaja.users_id_seq OWNED BY testaja.users.id;
          testaja          postgres    false    217            �           2604    22634 	   bisnis id    DEFAULT     h   ALTER TABLE ONLY testaja.bisnis ALTER COLUMN id SET DEFAULT nextval('testaja.bisnis_id_seq'::regclass);
 9   ALTER TABLE testaja.bisnis ALTER COLUMN id DROP DEFAULT;
       testaja          postgres    false    227    226    227            �           2604    22641 
   cabangs id    DEFAULT     j   ALTER TABLE ONLY testaja.cabangs ALTER COLUMN id SET DEFAULT nextval('testaja.cabangs_id_seq'::regclass);
 :   ALTER TABLE testaja.cabangs ALTER COLUMN id DROP DEFAULT;
       testaja          postgres    false    228    229    229            �           2604    22662    categories id    DEFAULT     p   ALTER TABLE ONLY testaja.categories ALTER COLUMN id SET DEFAULT nextval('testaja.categories_id_seq'::regclass);
 =   ALTER TABLE testaja.categories ALTER COLUMN id DROP DEFAULT;
       testaja          postgres    false    233    232    233            �           2604    22700 
   clients id    DEFAULT     j   ALTER TABLE ONLY testaja.clients ALTER COLUMN id SET DEFAULT nextval('testaja.clients_id_seq'::regclass);
 :   ALTER TABLE testaja.clients ALTER COLUMN id DROP DEFAULT;
       testaja          postgres    false    240    241    241            �           2604    22603    failed_jobs id    DEFAULT     r   ALTER TABLE ONLY testaja.failed_jobs ALTER COLUMN id SET DEFAULT nextval('testaja.failed_jobs_id_seq'::regclass);
 >   ALTER TABLE testaja.failed_jobs ALTER COLUMN id DROP DEFAULT;
       testaja          postgres    false    220    221    221            �           2604    22578    migrations id    DEFAULT     p   ALTER TABLE ONLY testaja.migrations ALTER COLUMN id SET DEFAULT nextval('testaja.migrations_id_seq'::regclass);
 =   ALTER TABLE testaja.migrations ALTER COLUMN id DROP DEFAULT;
       testaja          postgres    false    216    215    216            �           2604    22669    pay_methods id    DEFAULT     r   ALTER TABLE ONLY testaja.pay_methods ALTER COLUMN id SET DEFAULT nextval('testaja.pay_methods_id_seq'::regclass);
 >   ALTER TABLE testaja.pay_methods ALTER COLUMN id DROP DEFAULT;
       testaja          postgres    false    235    234    235            �           2604    22615    personal_access_tokens id    DEFAULT     �   ALTER TABLE ONLY testaja.personal_access_tokens ALTER COLUMN id SET DEFAULT nextval('testaja.personal_access_tokens_id_seq'::regclass);
 I   ALTER TABLE testaja.personal_access_tokens ALTER COLUMN id DROP DEFAULT;
       testaja          postgres    false    223    222    223            �           2604    22649    products id    DEFAULT     l   ALTER TABLE ONLY testaja.products ALTER COLUMN id SET DEFAULT nextval('testaja.products_id_seq'::regclass);
 ;   ALTER TABLE testaja.products ALTER COLUMN id DROP DEFAULT;
       testaja          postgres    false    230    231    231            �           2604    22627    roles id    DEFAULT     f   ALTER TABLE ONLY testaja.roles ALTER COLUMN id SET DEFAULT nextval('testaja.roles_id_seq'::regclass);
 8   ALTER TABLE testaja.roles ALTER COLUMN id DROP DEFAULT;
       testaja          postgres    false    224    225    225            �           2604    22693    transaction_details id    DEFAULT     �   ALTER TABLE ONLY testaja.transaction_details ALTER COLUMN id SET DEFAULT nextval('testaja.transaction_details_id_seq'::regclass);
 F   ALTER TABLE testaja.transaction_details ALTER COLUMN id DROP DEFAULT;
       testaja          postgres    false    239    238    239            �           2604    22682    transactions id    DEFAULT     t   ALTER TABLE ONLY testaja.transactions ALTER COLUMN id SET DEFAULT nextval('testaja.transactions_id_seq'::regclass);
 ?   ALTER TABLE testaja.transactions ALTER COLUMN id DROP DEFAULT;
       testaja          postgres    false    237    236    237            �           2604    22585    users id    DEFAULT     f   ALTER TABLE ONLY testaja.users ALTER COLUMN id SET DEFAULT nextval('testaja.users_id_seq'::regclass);
 8   ALTER TABLE testaja.users ALTER COLUMN id DROP DEFAULT;
       testaja          postgres    false    218    217    218            {          0    22631    bisnis 
   TABLE DATA           `   COPY testaja.bisnis (id, owner_id, name, default_warehouse, created_at, updated_at) FROM stdin;
    testaja          postgres    false    227   s       }          0    22638    cabangs 
   TABLE DATA           O   COPY testaja.cabangs (id, bisnis_id, name, created_at, updated_at) FROM stdin;
    testaja          postgres    false    229   �       �          0    22659 
   categories 
   TABLE DATA           ]   COPY testaja.categories (id, bisnis_id, cabang_id, name, created_at, updated_at) FROM stdin;
    testaja          postgres    false    233   �       �          0    22697    clients 
   TABLE DATA           `   COPY testaja.clients (id, bisnis_id, cabang_id, name, telp, created_at, updated_at) FROM stdin;
    testaja          postgres    false    241   ;�       u          0    22600    failed_jobs 
   TABLE DATA           b   COPY testaja.failed_jobs (id, uuid, connection, queue, payload, exception, failed_at) FROM stdin;
    testaja          postgres    false    221   ��       p          0    22575 
   migrations 
   TABLE DATA           ;   COPY testaja.migrations (id, migration, batch) FROM stdin;
    testaja          postgres    false    216   ��       s          0    22592    password_reset_tokens 
   TABLE DATA           J   COPY testaja.password_reset_tokens (email, token, created_at) FROM stdin;
    testaja          postgres    false    219   ��       �          0    22666    pay_methods 
   TABLE DATA           v   COPY testaja.pay_methods (id, bisnis_id, cabang_id, name, tipe, norek, is_active, created_at, updated_at) FROM stdin;
    testaja          postgres    false    235   Ɓ       w          0    22612    personal_access_tokens 
   TABLE DATA           �   COPY testaja.personal_access_tokens (id, tokenable_type, tokenable_id, name, token, abilities, last_used_at, expires_at, created_at, updated_at) FROM stdin;
    testaja          postgres    false    223   0�                 0    22646    products 
   TABLE DATA           �   COPY testaja.products (id, bisnis_id, cabang_id, category_id, name, modal, price, stock, satuan, is_active, is_jual, created_at, updated_at) FROM stdin;
    testaja          postgres    false    231   �       y          0    22624    roles 
   TABLE DATA           B   COPY testaja.roles (id, name, created_at, updated_at) FROM stdin;
    testaja          postgres    false    225   ��       �          0    22690    transaction_details 
   TABLE DATA           �   COPY testaja.transaction_details (id, transaction_id, product_id, price, qty, qty_return, total, created_at, updated_at) FROM stdin;
    testaja          postgres    false    239   ��       �          0    22679    transactions 
   TABLE DATA           �   COPY testaja.transactions (id, bisnis_id, cabang_id, user_id, client_id, reff, grandtotal, amount, payment_id, tipe, created_at, updated_at) FROM stdin;
    testaja          postgres    false    237   �       r          0    22582    users 
   TABLE DATA           �   COPY testaja.users (id, role_id, bisnis_id, cabang_id, name, email, email_verified_at, password, remember_token, created_at, updated_at) FROM stdin;
    testaja          postgres    false    218   w�       �           0    0    bisnis_id_seq    SEQUENCE SET     =   SELECT pg_catalog.setval('testaja.bisnis_id_seq', 1, false);
          testaja          postgres    false    226            �           0    0    cabangs_id_seq    SEQUENCE SET     >   SELECT pg_catalog.setval('testaja.cabangs_id_seq', 1, false);
          testaja          postgres    false    228            �           0    0    categories_id_seq    SEQUENCE SET     @   SELECT pg_catalog.setval('testaja.categories_id_seq', 2, true);
          testaja          postgres    false    232            �           0    0    clients_id_seq    SEQUENCE SET     =   SELECT pg_catalog.setval('testaja.clients_id_seq', 1, true);
          testaja          postgres    false    240            �           0    0    failed_jobs_id_seq    SEQUENCE SET     B   SELECT pg_catalog.setval('testaja.failed_jobs_id_seq', 1, false);
          testaja          postgres    false    220            �           0    0    migrations_id_seq    SEQUENCE SET     A   SELECT pg_catalog.setval('testaja.migrations_id_seq', 13, true);
          testaja          postgres    false    215            �           0    0    pay_methods_id_seq    SEQUENCE SET     B   SELECT pg_catalog.setval('testaja.pay_methods_id_seq', 1, false);
          testaja          postgres    false    234            �           0    0    personal_access_tokens_id_seq    SEQUENCE SET     L   SELECT pg_catalog.setval('testaja.personal_access_tokens_id_seq', 2, true);
          testaja          postgres    false    222            �           0    0    products_id_seq    SEQUENCE SET     >   SELECT pg_catalog.setval('testaja.products_id_seq', 2, true);
          testaja          postgres    false    230            �           0    0    roles_id_seq    SEQUENCE SET     <   SELECT pg_catalog.setval('testaja.roles_id_seq', 1, false);
          testaja          postgres    false    224            �           0    0    transaction_details_id_seq    SEQUENCE SET     I   SELECT pg_catalog.setval('testaja.transaction_details_id_seq', 2, true);
          testaja          postgres    false    238            �           0    0    transactions_id_seq    SEQUENCE SET     B   SELECT pg_catalog.setval('testaja.transactions_id_seq', 2, true);
          testaja          postgres    false    236            �           0    0    users_id_seq    SEQUENCE SET     ;   SELECT pg_catalog.setval('testaja.users_id_seq', 3, true);
          testaja          postgres    false    217            �           2606    22636    bisnis bisnis_pkey 
   CONSTRAINT     Q   ALTER TABLE ONLY testaja.bisnis
    ADD CONSTRAINT bisnis_pkey PRIMARY KEY (id);
 =   ALTER TABLE ONLY testaja.bisnis DROP CONSTRAINT bisnis_pkey;
       testaja            postgres    false    227            �           2606    22643    cabangs cabangs_pkey 
   CONSTRAINT     S   ALTER TABLE ONLY testaja.cabangs
    ADD CONSTRAINT cabangs_pkey PRIMARY KEY (id);
 ?   ALTER TABLE ONLY testaja.cabangs DROP CONSTRAINT cabangs_pkey;
       testaja            postgres    false    229            �           2606    22664    categories categories_pkey 
   CONSTRAINT     Y   ALTER TABLE ONLY testaja.categories
    ADD CONSTRAINT categories_pkey PRIMARY KEY (id);
 E   ALTER TABLE ONLY testaja.categories DROP CONSTRAINT categories_pkey;
       testaja            postgres    false    233            �           2606    22702    clients clients_pkey 
   CONSTRAINT     S   ALTER TABLE ONLY testaja.clients
    ADD CONSTRAINT clients_pkey PRIMARY KEY (id);
 ?   ALTER TABLE ONLY testaja.clients DROP CONSTRAINT clients_pkey;
       testaja            postgres    false    241            �           2606    22608    failed_jobs failed_jobs_pkey 
   CONSTRAINT     [   ALTER TABLE ONLY testaja.failed_jobs
    ADD CONSTRAINT failed_jobs_pkey PRIMARY KEY (id);
 G   ALTER TABLE ONLY testaja.failed_jobs DROP CONSTRAINT failed_jobs_pkey;
       testaja            postgres    false    221            �           2606    22610 #   failed_jobs failed_jobs_uuid_unique 
   CONSTRAINT     _   ALTER TABLE ONLY testaja.failed_jobs
    ADD CONSTRAINT failed_jobs_uuid_unique UNIQUE (uuid);
 N   ALTER TABLE ONLY testaja.failed_jobs DROP CONSTRAINT failed_jobs_uuid_unique;
       testaja            postgres    false    221            �           2606    22580    migrations migrations_pkey 
   CONSTRAINT     Y   ALTER TABLE ONLY testaja.migrations
    ADD CONSTRAINT migrations_pkey PRIMARY KEY (id);
 E   ALTER TABLE ONLY testaja.migrations DROP CONSTRAINT migrations_pkey;
       testaja            postgres    false    216            �           2606    22598 0   password_reset_tokens password_reset_tokens_pkey 
   CONSTRAINT     r   ALTER TABLE ONLY testaja.password_reset_tokens
    ADD CONSTRAINT password_reset_tokens_pkey PRIMARY KEY (email);
 [   ALTER TABLE ONLY testaja.password_reset_tokens DROP CONSTRAINT password_reset_tokens_pkey;
       testaja            postgres    false    219            �           2606    22677    pay_methods pay_methods_pkey 
   CONSTRAINT     [   ALTER TABLE ONLY testaja.pay_methods
    ADD CONSTRAINT pay_methods_pkey PRIMARY KEY (id);
 G   ALTER TABLE ONLY testaja.pay_methods DROP CONSTRAINT pay_methods_pkey;
       testaja            postgres    false    235            �           2606    22619 2   personal_access_tokens personal_access_tokens_pkey 
   CONSTRAINT     q   ALTER TABLE ONLY testaja.personal_access_tokens
    ADD CONSTRAINT personal_access_tokens_pkey PRIMARY KEY (id);
 ]   ALTER TABLE ONLY testaja.personal_access_tokens DROP CONSTRAINT personal_access_tokens_pkey;
       testaja            postgres    false    223            �           2606    22622 :   personal_access_tokens personal_access_tokens_token_unique 
   CONSTRAINT     w   ALTER TABLE ONLY testaja.personal_access_tokens
    ADD CONSTRAINT personal_access_tokens_token_unique UNIQUE (token);
 e   ALTER TABLE ONLY testaja.personal_access_tokens DROP CONSTRAINT personal_access_tokens_token_unique;
       testaja            postgres    false    223            �           2606    22657    products products_pkey 
   CONSTRAINT     U   ALTER TABLE ONLY testaja.products
    ADD CONSTRAINT products_pkey PRIMARY KEY (id);
 A   ALTER TABLE ONLY testaja.products DROP CONSTRAINT products_pkey;
       testaja            postgres    false    231            �           2606    22629    roles roles_pkey 
   CONSTRAINT     O   ALTER TABLE ONLY testaja.roles
    ADD CONSTRAINT roles_pkey PRIMARY KEY (id);
 ;   ALTER TABLE ONLY testaja.roles DROP CONSTRAINT roles_pkey;
       testaja            postgres    false    225            �           2606    22695 ,   transaction_details transaction_details_pkey 
   CONSTRAINT     k   ALTER TABLE ONLY testaja.transaction_details
    ADD CONSTRAINT transaction_details_pkey PRIMARY KEY (id);
 W   ALTER TABLE ONLY testaja.transaction_details DROP CONSTRAINT transaction_details_pkey;
       testaja            postgres    false    239            �           2606    22688    transactions transactions_pkey 
   CONSTRAINT     ]   ALTER TABLE ONLY testaja.transactions
    ADD CONSTRAINT transactions_pkey PRIMARY KEY (id);
 I   ALTER TABLE ONLY testaja.transactions DROP CONSTRAINT transactions_pkey;
       testaja            postgres    false    237            �           2606    22591    users users_email_unique 
   CONSTRAINT     U   ALTER TABLE ONLY testaja.users
    ADD CONSTRAINT users_email_unique UNIQUE (email);
 C   ALTER TABLE ONLY testaja.users DROP CONSTRAINT users_email_unique;
       testaja            postgres    false    218            �           2606    22589    users users_pkey 
   CONSTRAINT     O   ALTER TABLE ONLY testaja.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);
 ;   ALTER TABLE ONLY testaja.users DROP CONSTRAINT users_pkey;
       testaja            postgres    false    218            �           1259    22620 8   personal_access_tokens_tokenable_type_tokenable_id_index    INDEX     �   CREATE INDEX personal_access_tokens_tokenable_type_tokenable_id_index ON testaja.personal_access_tokens USING btree (tokenable_type, tokenable_id);
 M   DROP INDEX testaja.personal_access_tokens_tokenable_type_tokenable_id_index;
       testaja            postgres    false    223    223            {   (   x�3�4��,NNTp�/*�/J,������W� ��	m      }   $   x�3�4�tNLJ�KWp�O���K��"�=... 8�      �   L   x�3�4B�Č�<�����lN##c]3]#Cc+#+lb\F`��y��(z���-��,��L���b���� zQ�      �   <   x�3�4B���������tNC#cS3sN##c]3]#CC#+c+lb\1z\\\ G��      u      x������ � �      p   �   x�m�Mn� �u8L����w�4�0Mi]�`������ƶʂ��x�EI0A��[�9&�W*ٍ]@�=��v�>r	X�#�OJ-������e?�\�(�G7O���'��g�''7��j=�9�4��2��a+*y��3Tv�c�)6y=K�V���ҭ��@�ms��W��p�ʪݱL�\"5�ȍ����?�E����C�Rm�깸T�瘷���)`��'��x��]N�r��{L�7��"��5V�4      s      x������ � �      �   Z   x�3�4Ҽ�LΜ�̼��DN�N##c]3]#CCK+c3+lb\F`�NΎ�!E�y�i�E
N�yٜ�F�&�f��$����� 7��      w   �   x����N1 k�WD)�������@��yw� q�/qu�D;i��v���������O�p�Ӷ��v��w (��a���Q�(�A� @�V�3ؤT}�bޭ爘\������O�N���	��z��a~�.����D�
��T+��*0�T�8״F��u�Y��D�����N��nt�[�G�ҟ�e�7T�         u   x�3�4B#N�̒Ē|NC �44�&��Ŝ%@hd`d�k`�kd�`hdeblel�M�ː}3S<�K���R��9-!�)KΤҼ��TlfY����[��p��qqq �%       y   !   x�3��/�K-��".#N���L/F��� �      �   ?   x�3�4A#  2`L##c]3]#CC#+Sc+lb\F� h�n�M���!V1�=... �N       �   `   x�3�4��`�x### �� �gIQb^qbvq&�������������������	61.#��FP3�8��f E�R��2sr�u�Y`����� �� �      r   $  x���Oo�0 �s��J��:=�l.��N���*-J��ݲ,�-������g�ό�+]�(8pIϴ��ۭ��\R.,�$ ��}4�d4!�	B�G�=�zU"��$�}2/��Ou�����Ʌ?jD&����-8|Y�}��ٱ�;�Q��\d%�%����ᇿ1�|)���0\^�d�-7q��3GU�JZ��Z�2/�M��nO,k��i1#-9��9S�Q�98�i;4�����5!!n�T^�/�g#�D���*�kn�X�����ώ��[�a| Ӈ�     