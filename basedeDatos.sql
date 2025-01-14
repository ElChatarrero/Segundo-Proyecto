PGDMP       /                 |            tienda_virtual    16.2    16.2 b    4           0    0    ENCODING    ENCODING        SET client_encoding = 'UTF8';
                      false            5           0    0 
   STDSTRINGS 
   STDSTRINGS     (   SET standard_conforming_strings = 'on';
                      false            6           0    0 
   SEARCHPATH 
   SEARCHPATH     8   SELECT pg_catalog.set_config('search_path', '', false);
                      false            7           1262    41921    tienda_virtual    DATABASE     �   CREATE DATABASE tienda_virtual WITH TEMPLATE = template0 ENCODING = 'UTF8' LOCALE_PROVIDER = libc LOCALE = 'Spanish_Venezuela.1252';
    DROP DATABASE tienda_virtual;
                postgres    false            �            1259    41965 	   categoria    TABLE       CREATE TABLE public.categoria (
    id_categoria bigint NOT NULL,
    nombre character varying(255),
    descripcion text,
    datecreated date DEFAULT now(),
    status integer DEFAULT 1,
    portada character varying(100),
    ruta character varying(255)
);
    DROP TABLE public.categoria;
       public         heap    postgres    false            �            1259    41964    categoria_id_categoria_seq    SEQUENCE     �   CREATE SEQUENCE public.categoria_id_categoria_seq
    START WITH 0
    INCREMENT BY 1
    MINVALUE 0
    NO MAXVALUE
    CACHE 1;
 1   DROP SEQUENCE public.categoria_id_categoria_seq;
       public          postgres    false    224            8           0    0    categoria_id_categoria_seq    SEQUENCE OWNED BY     Y   ALTER SEQUENCE public.categoria_id_categoria_seq OWNED BY public.categoria.id_categoria;
          public          postgres    false    223            �            1259    41996    detalle_pedido    TABLE     �   CREATE TABLE public.detalle_pedido (
    id bigint NOT NULL,
    pedido_id bigint,
    uniforme_id bigint,
    precio double precision,
    cantidad integer
);
 "   DROP TABLE public.detalle_pedido;
       public         heap    postgres    false            �            1259    41995    detalle_pedido_id_seq    SEQUENCE     ~   CREATE SEQUENCE public.detalle_pedido_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 ,   DROP SEQUENCE public.detalle_pedido_id_seq;
       public          postgres    false    230            9           0    0    detalle_pedido_id_seq    SEQUENCE OWNED BY     O   ALTER SEQUENCE public.detalle_pedido_id_seq OWNED BY public.detalle_pedido.id;
          public          postgres    false    229            �            1259    42003    detalle_temp    TABLE     �   CREATE TABLE public.detalle_temp (
    id bigint NOT NULL,
    uniforme_id bigint,
    precio double precision,
    cantidad integer,
    transaccionid character varying(255),
    personaid bigint NOT NULL
);
     DROP TABLE public.detalle_temp;
       public         heap    postgres    false            �            1259    42002    detalle_temp_id_seq    SEQUENCE     |   CREATE SEQUENCE public.detalle_temp_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 *   DROP SEQUENCE public.detalle_temp_id_seq;
       public          postgres    false    232            :           0    0    detalle_temp_id_seq    SEQUENCE OWNED BY     K   ALTER SEQUENCE public.detalle_temp_id_seq OWNED BY public.detalle_temp.id;
          public          postgres    false    231            �            1259    66703    detalle_temp_personaid_seq    SEQUENCE     �   CREATE SEQUENCE public.detalle_temp_personaid_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 1   DROP SEQUENCE public.detalle_temp_personaid_seq;
       public          postgres    false    232            ;           0    0    detalle_temp_personaid_seq    SEQUENCE OWNED BY     Y   ALTER SEQUENCE public.detalle_temp_personaid_seq OWNED BY public.detalle_temp.personaid;
          public          postgres    false    237            �            1259    58486    imagen    TABLE     o   CREATE TABLE public.imagen (
    id bigint NOT NULL,
    uniforme_id bigint,
    img character varying(100)
);
    DROP TABLE public.imagen;
       public         heap    postgres    false            �            1259    58485    imagen_id_seq    SEQUENCE     v   CREATE SEQUENCE public.imagen_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 $   DROP SEQUENCE public.imagen_id_seq;
       public          postgres    false    234            <           0    0    imagen_id_seq    SEQUENCE OWNED BY     ?   ALTER SEQUENCE public.imagen_id_seq OWNED BY public.imagen.id;
          public          postgres    false    233            �            1259    41944    modulo    TABLE     �   CREATE TABLE public.modulo (
    id_modulo bigint NOT NULL,
    nombre character varying(50),
    descripcion text,
    status integer DEFAULT 1
);
    DROP TABLE public.modulo;
       public         heap    postgres    false            �            1259    41943    modulo_id_modulo_seq    SEQUENCE     |   CREATE SEQUENCE public.modulo_id_modulo_seq
    START WITH 1
    INCREMENT BY 1
    MINVALUE 0
    NO MAXVALUE
    CACHE 1;
 +   DROP SEQUENCE public.modulo_id_modulo_seq;
       public          postgres    false    220            =           0    0    modulo_id_modulo_seq    SEQUENCE OWNED BY     M   ALTER SEQUENCE public.modulo_id_modulo_seq OWNED BY public.modulo.id_modulo;
          public          postgres    false    219            �            1259    41987    pedido    TABLE       CREATE TABLE public.pedido (
    id_pedido bigint NOT NULL,
    persona_id bigint,
    fecha date DEFAULT now(),
    monto double precision,
    tipopago_id bigint,
    referenciacobro character varying(255),
    status character varying(100),
    direccion_envio text
);
    DROP TABLE public.pedido;
       public         heap    postgres    false            �            1259    41986    pedido_id_pedido_seq    SEQUENCE     }   CREATE SEQUENCE public.pedido_id_pedido_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 +   DROP SEQUENCE public.pedido_id_pedido_seq;
       public          postgres    false    228            >           0    0    pedido_id_pedido_seq    SEQUENCE OWNED BY     M   ALTER SEQUENCE public.pedido_id_pedido_seq OWNED BY public.pedido.id_pedido;
          public          postgres    false    227            �            1259    41954    permisos    TABLE     �   CREATE TABLE public.permisos (
    id_permiso bigint NOT NULL,
    rol_id bigint,
    modulo_id bigint,
    r integer DEFAULT 0,
    w integer DEFAULT 0,
    u integer DEFAULT 0,
    d integer DEFAULT 0
);
    DROP TABLE public.permisos;
       public         heap    postgres    false            �            1259    41953    permisos_id_permiso_seq    SEQUENCE        CREATE SEQUENCE public.permisos_id_permiso_seq
    START WITH 0
    INCREMENT BY 1
    MINVALUE 0
    NO MAXVALUE
    CACHE 1;
 .   DROP SEQUENCE public.permisos_id_permiso_seq;
       public          postgres    false    222            ?           0    0    permisos_id_permiso_seq    SEQUENCE OWNED BY     S   ALTER SEQUENCE public.permisos_id_permiso_seq OWNED BY public.permisos.id_permiso;
          public          postgres    false    221            �            1259    41933    persona    TABLE     ,  CREATE TABLE public.persona (
    id_persona bigint NOT NULL,
    nacionalidad character varying(1),
    cedula character varying(20),
    nombre character varying(100),
    apellido character varying(100),
    telefono character varying(20),
    email_user character varying(30),
    password character varying(100),
    nombre_fiscal character varying(80),
    direccion_fiscal character varying(100),
    token character varying(100),
    rol_id bigint,
    datecreated date DEFAULT now(),
    status integer DEFAULT 1,
    rif character varying(15)
);
    DROP TABLE public.persona;
       public         heap    postgres    false            �            1259    41932    persona_id_persona_seq    SEQUENCE     ~   CREATE SEQUENCE public.persona_id_persona_seq
    START WITH 0
    INCREMENT BY 1
    MINVALUE 0
    NO MAXVALUE
    CACHE 1;
 -   DROP SEQUENCE public.persona_id_persona_seq;
       public          postgres    false    218            @           0    0    persona_id_persona_seq    SEQUENCE OWNED BY     Q   ALTER SEQUENCE public.persona_id_persona_seq OWNED BY public.persona.id_persona;
          public          postgres    false    217            �            1259    41923    rol    TABLE     �   CREATE TABLE public.rol (
    id_rol bigint NOT NULL,
    nombre_rol character varying(50),
    descripcion text,
    status integer DEFAULT 1
);
    DROP TABLE public.rol;
       public         heap    postgres    false            �            1259    41922    rol_id_rol_seq    SEQUENCE     v   CREATE SEQUENCE public.rol_id_rol_seq
    START WITH 1
    INCREMENT BY 1
    MINVALUE 0
    NO MAXVALUE
    CACHE 1;
 %   DROP SEQUENCE public.rol_id_rol_seq;
       public          postgres    false    216            A           0    0    rol_id_rol_seq    SEQUENCE OWNED BY     A   ALTER SEQUENCE public.rol_id_rol_seq OWNED BY public.rol.id_rol;
          public          postgres    false    215            �            1259    66691    tipopago    TABLE     �   CREATE TABLE public.tipopago (
    idtipopago bigint NOT NULL,
    tipopago character varying(100),
    status integer DEFAULT 1
);
    DROP TABLE public.tipopago;
       public         heap    postgres    false            �            1259    66690    tipopago_idtipopago_seq    SEQUENCE     �   CREATE SEQUENCE public.tipopago_idtipopago_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 .   DROP SEQUENCE public.tipopago_idtipopago_seq;
       public          postgres    false    236            B           0    0    tipopago_idtipopago_seq    SEQUENCE OWNED BY     S   ALTER SEQUENCE public.tipopago_idtipopago_seq OWNED BY public.tipopago.idtipopago;
          public          postgres    false    235            �            1259    41976    uniforme    TABLE     l  CREATE TABLE public.uniforme (
    id_uniforme bigint NOT NULL,
    categoria_id bigint,
    codigo character varying(30),
    nombre character varying(255),
    descripcion text,
    precio double precision,
    stock integer,
    imagen character varying(100),
    datecreated date DEFAULT now(),
    status integer DEFAULT 1,
    ruta character varying(255)
);
    DROP TABLE public.uniforme;
       public         heap    postgres    false            �            1259    41975    uniforme_id_uniforme_seq    SEQUENCE     �   CREATE SEQUENCE public.uniforme_id_uniforme_seq
    START WITH 0
    INCREMENT BY 1
    MINVALUE 0
    NO MAXVALUE
    CACHE 1;
 /   DROP SEQUENCE public.uniforme_id_uniforme_seq;
       public          postgres    false    226            C           0    0    uniforme_id_uniforme_seq    SEQUENCE OWNED BY     U   ALTER SEQUENCE public.uniforme_id_uniforme_seq OWNED BY public.uniforme.id_uniforme;
          public          postgres    false    225            Y           2604    41968    categoria id_categoria    DEFAULT     �   ALTER TABLE ONLY public.categoria ALTER COLUMN id_categoria SET DEFAULT nextval('public.categoria_id_categoria_seq'::regclass);
 E   ALTER TABLE public.categoria ALTER COLUMN id_categoria DROP DEFAULT;
       public          postgres    false    224    223    224            a           2604    41999    detalle_pedido id    DEFAULT     v   ALTER TABLE ONLY public.detalle_pedido ALTER COLUMN id SET DEFAULT nextval('public.detalle_pedido_id_seq'::regclass);
 @   ALTER TABLE public.detalle_pedido ALTER COLUMN id DROP DEFAULT;
       public          postgres    false    229    230    230            b           2604    42006    detalle_temp id    DEFAULT     r   ALTER TABLE ONLY public.detalle_temp ALTER COLUMN id SET DEFAULT nextval('public.detalle_temp_id_seq'::regclass);
 >   ALTER TABLE public.detalle_temp ALTER COLUMN id DROP DEFAULT;
       public          postgres    false    231    232    232            c           2604    66704    detalle_temp personaid    DEFAULT     �   ALTER TABLE ONLY public.detalle_temp ALTER COLUMN personaid SET DEFAULT nextval('public.detalle_temp_personaid_seq'::regclass);
 E   ALTER TABLE public.detalle_temp ALTER COLUMN personaid DROP DEFAULT;
       public          postgres    false    237    232            d           2604    58489 	   imagen id    DEFAULT     f   ALTER TABLE ONLY public.imagen ALTER COLUMN id SET DEFAULT nextval('public.imagen_id_seq'::regclass);
 8   ALTER TABLE public.imagen ALTER COLUMN id DROP DEFAULT;
       public          postgres    false    233    234    234            R           2604    41947    modulo id_modulo    DEFAULT     t   ALTER TABLE ONLY public.modulo ALTER COLUMN id_modulo SET DEFAULT nextval('public.modulo_id_modulo_seq'::regclass);
 ?   ALTER TABLE public.modulo ALTER COLUMN id_modulo DROP DEFAULT;
       public          postgres    false    220    219    220            _           2604    41990    pedido id_pedido    DEFAULT     t   ALTER TABLE ONLY public.pedido ALTER COLUMN id_pedido SET DEFAULT nextval('public.pedido_id_pedido_seq'::regclass);
 ?   ALTER TABLE public.pedido ALTER COLUMN id_pedido DROP DEFAULT;
       public          postgres    false    228    227    228            T           2604    41957    permisos id_permiso    DEFAULT     z   ALTER TABLE ONLY public.permisos ALTER COLUMN id_permiso SET DEFAULT nextval('public.permisos_id_permiso_seq'::regclass);
 B   ALTER TABLE public.permisos ALTER COLUMN id_permiso DROP DEFAULT;
       public          postgres    false    222    221    222            O           2604    41936    persona id_persona    DEFAULT     x   ALTER TABLE ONLY public.persona ALTER COLUMN id_persona SET DEFAULT nextval('public.persona_id_persona_seq'::regclass);
 A   ALTER TABLE public.persona ALTER COLUMN id_persona DROP DEFAULT;
       public          postgres    false    218    217    218            M           2604    41926 
   rol id_rol    DEFAULT     h   ALTER TABLE ONLY public.rol ALTER COLUMN id_rol SET DEFAULT nextval('public.rol_id_rol_seq'::regclass);
 9   ALTER TABLE public.rol ALTER COLUMN id_rol DROP DEFAULT;
       public          postgres    false    215    216    216            e           2604    66694    tipopago idtipopago    DEFAULT     z   ALTER TABLE ONLY public.tipopago ALTER COLUMN idtipopago SET DEFAULT nextval('public.tipopago_idtipopago_seq'::regclass);
 B   ALTER TABLE public.tipopago ALTER COLUMN idtipopago DROP DEFAULT;
       public          postgres    false    236    235    236            \           2604    41979    uniforme id_uniforme    DEFAULT     |   ALTER TABLE ONLY public.uniforme ALTER COLUMN id_uniforme SET DEFAULT nextval('public.uniforme_id_uniforme_seq'::regclass);
 C   ALTER TABLE public.uniforme ALTER COLUMN id_uniforme DROP DEFAULT;
       public          postgres    false    226    225    226            $          0    41965 	   categoria 
   TABLE DATA           j   COPY public.categoria (id_categoria, nombre, descripcion, datecreated, status, portada, ruta) FROM stdin;
    public          postgres    false    224   �s       *          0    41996    detalle_pedido 
   TABLE DATA           V   COPY public.detalle_pedido (id, pedido_id, uniforme_id, precio, cantidad) FROM stdin;
    public          postgres    false    230   �t       ,          0    42003    detalle_temp 
   TABLE DATA           c   COPY public.detalle_temp (id, uniforme_id, precio, cantidad, transaccionid, personaid) FROM stdin;
    public          postgres    false    232   �t       .          0    58486    imagen 
   TABLE DATA           6   COPY public.imagen (id, uniforme_id, img) FROM stdin;
    public          postgres    false    234   �t                  0    41944    modulo 
   TABLE DATA           H   COPY public.modulo (id_modulo, nombre, descripcion, status) FROM stdin;
    public          postgres    false    220   �u       (          0    41987    pedido 
   TABLE DATA           |   COPY public.pedido (id_pedido, persona_id, fecha, monto, tipopago_id, referenciacobro, status, direccion_envio) FROM stdin;
    public          postgres    false    228   uv       "          0    41954    permisos 
   TABLE DATA           M   COPY public.permisos (id_permiso, rol_id, modulo_id, r, w, u, d) FROM stdin;
    public          postgres    false    222   �v                 0    41933    persona 
   TABLE DATA           �   COPY public.persona (id_persona, nacionalidad, cedula, nombre, apellido, telefono, email_user, password, nombre_fiscal, direccion_fiscal, token, rol_id, datecreated, status, rif) FROM stdin;
    public          postgres    false    218   w                 0    41923    rol 
   TABLE DATA           F   COPY public.rol (id_rol, nombre_rol, descripcion, status) FROM stdin;
    public          postgres    false    216   z       0          0    66691    tipopago 
   TABLE DATA           @   COPY public.tipopago (idtipopago, tipopago, status) FROM stdin;
    public          postgres    false    236   �z       &          0    41976    uniforme 
   TABLE DATA           �   COPY public.uniforme (id_uniforme, categoria_id, codigo, nombre, descripcion, precio, stock, imagen, datecreated, status, ruta) FROM stdin;
    public          postgres    false    226   �z       D           0    0    categoria_id_categoria_seq    SEQUENCE SET     H   SELECT pg_catalog.setval('public.categoria_id_categoria_seq', 6, true);
          public          postgres    false    223            E           0    0    detalle_pedido_id_seq    SEQUENCE SET     D   SELECT pg_catalog.setval('public.detalle_pedido_id_seq', 1, false);
          public          postgres    false    229            F           0    0    detalle_temp_id_seq    SEQUENCE SET     B   SELECT pg_catalog.setval('public.detalle_temp_id_seq', 11, true);
          public          postgres    false    231            G           0    0    detalle_temp_personaid_seq    SEQUENCE SET     I   SELECT pg_catalog.setval('public.detalle_temp_personaid_seq', 1, false);
          public          postgres    false    237            H           0    0    imagen_id_seq    SEQUENCE SET     <   SELECT pg_catalog.setval('public.imagen_id_seq', 18, true);
          public          postgres    false    233            I           0    0    modulo_id_modulo_seq    SEQUENCE SET     B   SELECT pg_catalog.setval('public.modulo_id_modulo_seq', 6, true);
          public          postgres    false    219            J           0    0    pedido_id_pedido_seq    SEQUENCE SET     C   SELECT pg_catalog.setval('public.pedido_id_pedido_seq', 1, false);
          public          postgres    false    227            K           0    0    permisos_id_permiso_seq    SEQUENCE SET     G   SELECT pg_catalog.setval('public.permisos_id_permiso_seq', 186, true);
          public          postgres    false    221            L           0    0    persona_id_persona_seq    SEQUENCE SET     E   SELECT pg_catalog.setval('public.persona_id_persona_seq', 12, true);
          public          postgres    false    217            M           0    0    rol_id_rol_seq    SEQUENCE SET     <   SELECT pg_catalog.setval('public.rol_id_rol_seq', 4, true);
          public          postgres    false    215            N           0    0    tipopago_idtipopago_seq    SEQUENCE SET     E   SELECT pg_catalog.setval('public.tipopago_idtipopago_seq', 2, true);
          public          postgres    false    235            O           0    0    uniforme_id_uniforme_seq    SEQUENCE SET     F   SELECT pg_catalog.setval('public.uniforme_id_uniforme_seq', 9, true);
          public          postgres    false    225            ~           2606    58491    imagen imagen_pkey 
   CONSTRAINT     P   ALTER TABLE ONLY public.imagen
    ADD CONSTRAINT imagen_pkey PRIMARY KEY (id);
 <   ALTER TABLE ONLY public.imagen DROP CONSTRAINT imagen_pkey;
       public            postgres    false    234            t           2606    41974    categoria pk_categoria 
   CONSTRAINT     ^   ALTER TABLE ONLY public.categoria
    ADD CONSTRAINT pk_categoria PRIMARY KEY (id_categoria);
 @   ALTER TABLE ONLY public.categoria DROP CONSTRAINT pk_categoria;
       public            postgres    false    224            |           2606    42008    detalle_temp pk_detalle 
   CONSTRAINT     U   ALTER TABLE ONLY public.detalle_temp
    ADD CONSTRAINT pk_detalle PRIMARY KEY (id);
 A   ALTER TABLE ONLY public.detalle_temp DROP CONSTRAINT pk_detalle;
       public            postgres    false    232            z           2606    42001    detalle_pedido pk_id 
   CONSTRAINT     R   ALTER TABLE ONLY public.detalle_pedido
    ADD CONSTRAINT pk_id PRIMARY KEY (id);
 >   ALTER TABLE ONLY public.detalle_pedido DROP CONSTRAINT pk_id;
       public            postgres    false    230            p           2606    41952    modulo pk_modulo 
   CONSTRAINT     U   ALTER TABLE ONLY public.modulo
    ADD CONSTRAINT pk_modulo PRIMARY KEY (id_modulo);
 :   ALTER TABLE ONLY public.modulo DROP CONSTRAINT pk_modulo;
       public            postgres    false    220            x           2606    41994    pedido pk_pedido 
   CONSTRAINT     U   ALTER TABLE ONLY public.pedido
    ADD CONSTRAINT pk_pedido PRIMARY KEY (id_pedido);
 :   ALTER TABLE ONLY public.pedido DROP CONSTRAINT pk_pedido;
       public            postgres    false    228            r           2606    41963    permisos pk_permisos 
   CONSTRAINT     Z   ALTER TABLE ONLY public.permisos
    ADD CONSTRAINT pk_permisos PRIMARY KEY (id_permiso);
 >   ALTER TABLE ONLY public.permisos DROP CONSTRAINT pk_permisos;
       public            postgres    false    222            j           2606    41942    persona pk_persona 
   CONSTRAINT     X   ALTER TABLE ONLY public.persona
    ADD CONSTRAINT pk_persona PRIMARY KEY (id_persona);
 <   ALTER TABLE ONLY public.persona DROP CONSTRAINT pk_persona;
       public            postgres    false    218            h           2606    41931 
   rol pk_rol 
   CONSTRAINT     L   ALTER TABLE ONLY public.rol
    ADD CONSTRAINT pk_rol PRIMARY KEY (id_rol);
 4   ALTER TABLE ONLY public.rol DROP CONSTRAINT pk_rol;
       public            postgres    false    216            v           2606    41985    uniforme pk_uniforme 
   CONSTRAINT     [   ALTER TABLE ONLY public.uniforme
    ADD CONSTRAINT pk_uniforme PRIMARY KEY (id_uniforme);
 >   ALTER TABLE ONLY public.uniforme DROP CONSTRAINT pk_uniforme;
       public            postgres    false    226            �           2606    66696    tipopago tipopago_pkey 
   CONSTRAINT     \   ALTER TABLE ONLY public.tipopago
    ADD CONSTRAINT tipopago_pkey PRIMARY KEY (idtipopago);
 @   ALTER TABLE ONLY public.tipopago DROP CONSTRAINT tipopago_pkey;
       public            postgres    false    236            l           2606    42057    persona unq_cedula 
   CONSTRAINT     O   ALTER TABLE ONLY public.persona
    ADD CONSTRAINT unq_cedula UNIQUE (cedula);
 <   ALTER TABLE ONLY public.persona DROP CONSTRAINT unq_cedula;
       public            postgres    false    218            n           2606    42055    persona unq_email 
   CONSTRAINT     R   ALTER TABLE ONLY public.persona
    ADD CONSTRAINT unq_email UNIQUE (email_user);
 ;   ALTER TABLE ONLY public.persona DROP CONSTRAINT unq_email;
       public            postgres    false    218            �           2606    42024    uniforme fk_categoria    FK CONSTRAINT     �   ALTER TABLE ONLY public.uniforme
    ADD CONSTRAINT fk_categoria FOREIGN KEY (categoria_id) REFERENCES public.categoria(id_categoria) ON UPDATE CASCADE ON DELETE CASCADE NOT VALID;
 ?   ALTER TABLE ONLY public.uniforme DROP CONSTRAINT fk_categoria;
       public          postgres    false    4724    224    226            �           2606    42019    permisos fk_modulo    FK CONSTRAINT     �   ALTER TABLE ONLY public.permisos
    ADD CONSTRAINT fk_modulo FOREIGN KEY (modulo_id) REFERENCES public.modulo(id_modulo) ON UPDATE CASCADE ON DELETE CASCADE NOT VALID;
 <   ALTER TABLE ONLY public.permisos DROP CONSTRAINT fk_modulo;
       public          postgres    false    220    4720    222            �           2606    42039    detalle_pedido fk_pedido    FK CONSTRAINT     �   ALTER TABLE ONLY public.detalle_pedido
    ADD CONSTRAINT fk_pedido FOREIGN KEY (pedido_id) REFERENCES public.pedido(id_pedido) ON UPDATE CASCADE ON DELETE CASCADE NOT VALID;
 B   ALTER TABLE ONLY public.detalle_pedido DROP CONSTRAINT fk_pedido;
       public          postgres    false    4728    230    228            �           2606    42034    pedido fk_persona    FK CONSTRAINT     �   ALTER TABLE ONLY public.pedido
    ADD CONSTRAINT fk_persona FOREIGN KEY (persona_id) REFERENCES public.persona(id_persona) ON UPDATE CASCADE ON DELETE CASCADE NOT VALID;
 ;   ALTER TABLE ONLY public.pedido DROP CONSTRAINT fk_persona;
       public          postgres    false    218    4714    228            �           2606    66709    detalle_temp fk_persona    FK CONSTRAINT     �   ALTER TABLE ONLY public.detalle_temp
    ADD CONSTRAINT fk_persona FOREIGN KEY (personaid) REFERENCES public.persona(id_persona) NOT VALID;
 A   ALTER TABLE ONLY public.detalle_temp DROP CONSTRAINT fk_persona;
       public          postgres    false    218    232    4714            �           2606    42014    permisos fk_rol    FK CONSTRAINT     �   ALTER TABLE ONLY public.permisos
    ADD CONSTRAINT fk_rol FOREIGN KEY (rol_id) REFERENCES public.rol(id_rol) ON UPDATE CASCADE ON DELETE CASCADE NOT VALID;
 9   ALTER TABLE ONLY public.permisos DROP CONSTRAINT fk_rol;
       public          postgres    false    222    4712    216            �           2606    42029    persona fk_rol    FK CONSTRAINT     �   ALTER TABLE ONLY public.persona
    ADD CONSTRAINT fk_rol FOREIGN KEY (rol_id) REFERENCES public.rol(id_rol) ON UPDATE CASCADE ON DELETE CASCADE NOT VALID;
 8   ALTER TABLE ONLY public.persona DROP CONSTRAINT fk_rol;
       public          postgres    false    218    216    4712            �           2606    66697    pedido fk_tipopago    FK CONSTRAINT     �   ALTER TABLE ONLY public.pedido
    ADD CONSTRAINT fk_tipopago FOREIGN KEY (tipopago_id) REFERENCES public.tipopago(idtipopago) ON UPDATE CASCADE ON DELETE CASCADE NOT VALID;
 <   ALTER TABLE ONLY public.pedido DROP CONSTRAINT fk_tipopago;
       public          postgres    false    228    236    4736            �           2606    42044    detalle_pedido fk_uniforme    FK CONSTRAINT     �   ALTER TABLE ONLY public.detalle_pedido
    ADD CONSTRAINT fk_uniforme FOREIGN KEY (uniforme_id) REFERENCES public.uniforme(id_uniforme) ON UPDATE CASCADE ON DELETE CASCADE NOT VALID;
 D   ALTER TABLE ONLY public.detalle_pedido DROP CONSTRAINT fk_uniforme;
       public          postgres    false    230    4726    226            �           2606    42049    detalle_temp fk_uniforme    FK CONSTRAINT     �   ALTER TABLE ONLY public.detalle_temp
    ADD CONSTRAINT fk_uniforme FOREIGN KEY (uniforme_id) REFERENCES public.uniforme(id_uniforme) ON UPDATE CASCADE ON DELETE CASCADE NOT VALID;
 B   ALTER TABLE ONLY public.detalle_temp DROP CONSTRAINT fk_uniforme;
       public          postgres    false    232    226    4726            �           2606    58492    imagen fk_uniforme    FK CONSTRAINT     �   ALTER TABLE ONLY public.imagen
    ADD CONSTRAINT fk_uniforme FOREIGN KEY (uniforme_id) REFERENCES public.uniforme(id_uniforme);
 <   ALTER TABLE ONLY public.imagen DROP CONSTRAINT fk_uniforme;
       public          postgres    false    234    4726    226            $   �   x�u�[n�0E��Ut �k��n�R4B
���k�����~ܣs=�L9�1���;�L���F(��0�g��:/��>��6� ��j����̓E���zMS�f��K���pO}�h�m�,T=_;�3��^� �!��6Xmt����:��W�Jr�y<V������ڠ��C�{1-8�|{%�:(,Ӊ;��~
���c�ǧd���r9��IĊq�Zy��(���X/aT��\�gK)�1H�C      *      x������ � �      ,      x������ � �      .   �   x�-��j1����\���w)���]�n��2�;�8plG;�~�>��ڥU3 �&cL�d�����>���UB2f�訮J6mhϙ 
�aw�H#G+RB �fN6�Z��߸q0d�H��+�c�����j�q�u��a4M�X�������7F9dk���
Ar� �t�$����uk}4(ZQ����GQ�W�qk;��A�VBrեv�eK�E�d�A4�!ܖ���6W��r�S��y��y���p;          ~   x�MMK
1]'��	����n���l*�Xhhf�?)B�"�����=��-���<a�-�(ډ#N�u�,q�[�\V�N,�&���3�%~�fLB�Ov���Lќ����#����Z�o�#�wlc@�      (      x������ � �      "   _   x�M�A
� Dѵ9L��D�����Vp�bx������M��.v��A�8�<�}_�k����s��q����x��g7J{ )O(�J�������M�#�           x���_n�FƟgO�p0���"Mh�����g!c-�7A}���+��b!q���$��%?~u���6hu8��~�gR0y��<v^�_�wԝޔ�Nq)���ڲ���P����<mcIE{S��R�+�k��"y���ǧ
5�+�����ǝ,L	1�u������a�O3��Ƹ��*�}pqCk�y.TM�D�<&��}���b������L!]����	O�|q.�z;vӃ ��CO�:�_�i��r;|�nڈ&X�ʺ�59�C��0>��t���7b��� �h�NE���?���
k�>���_�[�i���=��7�i�����V��S;>�E
�xQ��z0�Cu;��miKH6@����s�;׌Εb�MM�4"㬁�t���\J}�
��0."�U �IV�"�"�&k�`mh�A)��T[+�$GH�y-�)�b%N')q�P�������΃⣉6:�;�_�^}�ws#eF��N�����e�k��r��|+�,4�����3��񞩯W^�9�7�^��������i^JI	w�Z�ןmY�Ӎz)�,�\�����E���ۭ�D/D;�9c��y]p�5��՜	�l��9 ��b�g+Ë�p����E���?�ӉG���/�A�u�����%��'X�����l}�KN|K}�x?�g�!XC�3�^�V��wV�g1�N��q	�`�sj�_n������l�'�#2HmMl��ڴ�ę���������Tu*�Ъ��ml�ݼ��v���X         m   x�3�.-H-*�,�/Bb*��*�d��$rrr:��f�e�%� ���@*K�S�Rs����� -Ɯ�yɉE�@Ti�B	�dN� �$�� )�IDX���� ��7      0   '   x�3�tMKM.�,��4�2�HL�W��/��rc���� ���      &   �  x����r� E����f�H���h��|A6ؐ�*@y��O#,{��vfJU�ۧQ_Z*�kĸ��ߣF���/4�t�--��Ϡ/��MCt��(\�QV�u��^=+(_dĀ"��٩q���rFkԏ�Մ�i@�]ysB2�p�rT7mU񭱜8�V���$�hy�X+�M6Fk>8�$�薼)�KB����D�݂.:��k#>�ӆ��a����is� �{�a�^\S�S��xk��w�ޚ#t:o=�� ��O�b��p����f�N<?�X�����0�x�r���rd�ɫ��C�y2�2�/��V���_����m�����<!��|��� a�?ZͿc4w��e.|mf��(��������F��{�s�9MD8��$��g�LK�4z�!�/n5�~2pX���f�Z��wm�]�0Ap�Ϊ��C��ϰ�焿ӐIH[6���mLQ#vT$V��b&� ?�`�� ���R     