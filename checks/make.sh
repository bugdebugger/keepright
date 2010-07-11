#!/bin/bash
#
# script for updating multiple keepright databases
#

#
# call ./make.sh [--loop] [ AllButUSAndAU | US | AU ] [17]
# to make keepright check one or more databases and upload the results
# use the parameter --loop to make make.sh run until a file called
# /tmp/stop_keepright is found.
# optionally provide a schema name [17] where to start. This is useful
# for restarting at a given position in the loop
#
# call this script out of the checks directory

FIRSTRUN=1
STARTSCHEMA="$3"


# updates one single schema depending on the (global) variable
# schema containing the name of the schema
process_schema() {

	if [ $FIRSTRUN -ne 1 -o "$schema" = "$STARTSCHEMA" ] ; then

		echo "updating schema $schema";
		FIRSTRUN=0

		svn up ../
		./updateDB.sh $schema >> make_$schema.log 2>&1
		php export_errors.php $schema >> make_$schema.log 2>&1
		php webUpdateClient.php --remote $schema >> make_$schema.log 2>&1 &
	fi


	if [ -f /tmp/stop_keepright ]; then
		exit;
	fi
}




while { [ "$1" != "--loop" ] && [ $FIRSTRUN -eq 1 ] ; } || { [ "$1" = "--loop" ] && [ ! -f /tmp/stop_keepright ] ; } ; do

	for i do	# loop all given parameter values

		case "$i" in

			AllButUSAndAU)

				for schema in 1 2 70 71 72 4 5 6 7 8 9 10 11 13 14 15 18 19 46 68 17 47 48 73 74 75
				do
					process_schema
				done

			;;
			US)

				for schema in 20 21 22 23 24 25 26 27 28 29 30 31 32 33 34 35 36 37 38 39 40 41 42 43 44 45 51 52 53 54 55 56 57 58 59 60 61 62 63 64 65 69
				do
					process_schema
				done

			;;
			AU)

				for schema in 50
				do
					process_schema
				done

			;;
		esac
	done
	FIRSTRUN=0
done