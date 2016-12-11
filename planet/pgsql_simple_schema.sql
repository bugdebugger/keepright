-- Database creation script for the simple PostgreSQL schema.
-- modified to include more redundancy by Harald Kleiner


-- Create a table which will contain a single row defining the current schema version.
CREATE UNLOGGED TABLE schema_info (
    version integer NOT NULL
);
INSERT INTO schema_info VALUES (1);


-- Create a table for users.
CREATE UNLOGGED TABLE users (
    id bigint NOT NULL,
    user_name text
);


-- Create a table for nodes.
CREATE UNLOGGED TABLE nodes (
    id bigint NOT NULL,
    user_id bigint,
    tstamp timestamp without time zone NOT NULL
);
-- Add a postgis point column holding the location of the node.
SELECT AddGeometryColumn('nodes', 'geom', 4326, 'POINT', 2);

ALTER TABLE nodes
    ADD lat double precision,
    ADD lon double precision,
    ADD x double precision,
    ADD y double precision;



-- Create a table for node tags.
CREATE UNLOGGED TABLE node_tags (
    node_id bigint NOT NULL,
    k text NOT NULL,
    v text
);


-- Create a table for ways.
CREATE UNLOGGED TABLE ways (
    id bigint NOT NULL,
    user_id bigint,
    tstamp timestamp without time zone NOT NULL,
    first_node_id bigint,
    last_node_id bigint,
    first_node_lat double precision,
    first_node_lon double precision,
    first_node_x double precision,
    first_node_y double precision,
    last_node_lat double precision,
    last_node_lon double precision,
    last_node_x double precision,
    last_node_y double precision,
    node_count integer
);


-- Create a table for representing way to node relationships.
CREATE UNLOGGED TABLE way_nodes (
    way_id bigint NOT NULL,
    node_id bigint NOT NULL,
    sequence_id integer NOT NULL,
    lat double precision,
    lon double precision,
    x double precision,
    y double precision
);


-- Create a table for way tags.
CREATE UNLOGGED TABLE way_tags (
    way_id bigint NOT NULL,
    k text NOT NULL,
    v text
);


-- Create a table for relations.
CREATE UNLOGGED TABLE relations (
    id bigint NOT NULL,
    user_id bigint,
    tstamp timestamp without time zone NOT NULL
);


-- Create a table for representing relation member relationships.
CREATE UNLOGGED TABLE relation_members (
    relation_id bigint NOT NULL,
    member_id bigint NOT NULL,
    member_role text NOT NULL,
    member_type character(1) NOT NULL,
    sequence_id integer NOT NULL
);


-- Create a table for relation tags.
CREATE UNLOGGED TABLE relation_tags (
    relation_id bigint NOT NULL,
    k text NOT NULL,
    v text
);

