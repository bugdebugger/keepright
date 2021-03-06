

import java.util.HashMap;
import java.util.Map;

import org.openstreetmap.osmosis.core.pipeline.common.TaskManagerFactory;
import org.openstreetmap.osmosis.core.plugin.PluginLoader;


/*

osmosis-plugin for exporting planet files into tab-separated text files
suitable for postgres COPY commands. This is a modified version of the
Postgresql-Dataset-Dump-Writer task.

some of the changes include:
* special formatting of node ids needed for joining files with bash's JOIN command
* more redundand output of columns (eg. nodes' lat/lon plus x/y coordinates)
* output of nodes' geometry is non-standard: coordinates are in meters, not lat/lon!
* output first and last node for each way


compile this with classpath pointing to osmosis.jar, postgis.jar and postgresql.jar:
providing you downloaded osmosis from http://bretth.dev.openstreetmap.org/osmosis-build/osmosis-0.46.zip
and extracted it to /home/haraldk/OSM/osmosis-0.46/

cd /home/haraldk/OSM/keepright/planet

javac -cp "/home/haraldk/OSM/osmosis-0.46/lib/default/osmosis-core-0.46.jar:/home/haraldk/OSM/osmosis-0.46/lib/default/postgis-jdbc-2.2.1.jar:/home/haraldk/OSM/osmosis-0.46/lib/default/postgresql-42.1.4.jar:/home/haraldk/OSM/osmosis-0.46/lib/default/osmosis-pgsnapshot-0.46.jar:." *.java

copy resulting .class files into osmosis.jar using your favourite zip program (!)

zip plugins/pl.zip Mercator.class pl.class PostgreSqlMyDatasetDumpWriter.class MyCopyFileWriter.class plugin.xml PostgreSqlMyDatasetDumpWriterFactory.class

run with
 ~/OSM/osmosis-0.46/bin/osmosis -p pl --read-xml file=planet.osm --pl

*/

public class pl implements PluginLoader {

	@Override
	public Map<String, TaskManagerFactory> loadTaskFactories() {

		PostgreSqlMyDatasetDumpWriterFactory factory;
		HashMap<String, TaskManagerFactory> mymap;

		factory = new PostgreSqlMyDatasetDumpWriterFactory();
		mymap = new HashMap<String, TaskManagerFactory>();

		mymap.put("pl", factory);
		return mymap;
	}

}

