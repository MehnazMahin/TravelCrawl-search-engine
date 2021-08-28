path=`pwd`
query=$1

#recompile project if needed
#mvn clean install

#collect all jar files for dependencies
#mvn dependency:copy-dependencies

mvn exec:java -Dexec.args="$path $query"
